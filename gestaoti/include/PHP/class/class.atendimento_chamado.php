<?php
/*
Copyright 2011 da EMBRATUR
�Este arquivo � parte do programa CAU - Central de Atendimento ao Usu�rio
�O CAU � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela 
 Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
�Este programa � distribu�do na esperan�a que possa ser� �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer� 
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
�Observe no diret�rio gestaoti/install/ a c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licensa_uso.htm". 
 Se preferir acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software 
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA� 02110-1301, USA
*/
/*
* -------------------------------------------------------
* Nome da Classe:	atendimento_chamado
* Nome da tabela:	atendimento_chamado
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
}

// **********************
// DECLARA��O DA CLASSE
// **********************
class atendimento_chamado{
	// class : begin

	// ***********************
	// DECLARA��O DE ATRIBUTOS
	// ***********************
	var $SEQ_ATENDIMENTO_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $SEQ_CHAMADO;   // (normal Attribute)
	var $NUM_MATRICULA;   // (normal Attribute)
	var $DTH_ATENDIMENTO_CHAMADO;   // (normal Attribute)
	var $TXT_ATENDIMENTO_CHAMADO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function atendimento_chamado(){
		$this->database = new Database();
	}

	// **********************
	// GETTER METHODS
	// **********************
	function getrowCount(){
		return $this->rowCount;
	}

	function getvQtdRegistros(){
		return $this->vQtdRegistros;
	}

	function getSEQ_ATENDIMENTO_CHAMADO(){
		return $this->SEQ_ATENDIMENTO_CHAMADO;
	}

	function getSEQ_CHAMADO(){
		return $this->SEQ_CHAMADO;
	}

	function getNUM_MATRICULA(){
		return $this->NUM_MATRICULA;
	}

	function getDTH_ATENDIMENTO_CHAMADO(){
		return $this->DTH_ATENDIMENTO_CHAMADO;
	}

	function getTXT_ATENDIMENTO_CHAMADO(){
		return $this->TXT_ATENDIMENTO_CHAMADO;
	}

	// **********************
	// SETTER METHODS
	// **********************
	function setrowCount($val){
		$this->rowCount = $val;
	}

	function setvQtdRegistros($val){
		$this->vQtdRegistros = $val;
	}

	function setSEQ_ATENDIMENTO_CHAMADO($val){
		$this->SEQ_ATENDIMENTO_CHAMADO =  $val;
	}

	function setSEQ_CHAMADO($val){
		$this->SEQ_CHAMADO =  $val;
	}

	function setNUM_MATRICULA($val){
		$this->NUM_MATRICULA =  $val;
	}

	function setDTH_ATENDIMENTO_CHAMADO($val){
		$this->DTH_ATENDIMENTO_CHAMADO =  $val;
	}

	function setTXT_ATENDIMENTO_CHAMADO($val){
		$this->TXT_ATENDIMENTO_CHAMADO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_ATENDIMENTO_CHAMADO , SEQ_CHAMADO , NUM_MATRICULA , to_char(DTH_ATENDIMENTO_CHAMADO, 'dd/mm/yyyy hh24/mi/ss') as DTH_ATENDIMENTO_CHAMADO, DTH_ATENDIMENTO_CHAMADO as DTH_ATENDIMENTO_CHAMADO_DATA , TXT_ATENDIMENTO_CHAMADO
			    FROM gestaoti.atendimento_chamado
				WHERE SEQ_ATENDIMENTO_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_ATENDIMENTO_CHAMADO = $row->seq_atendimento_chamado;
		$this->SEQ_CHAMADO = $row->seq_chamado;
		$this->NUM_MATRICULA = $row->num_matricula;
		$this->DTH_ATENDIMENTO_CHAMADO = $row->dth_atendimento_chamado;
		$this->TXT_ATENDIMENTO_CHAMADO = $row->txt_atendimento_chamado;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_ATENDIMENTO_CHAMADO, SEQ_CHAMADO, NUM_MATRICULA, to_char(DTH_ATENDIMENTO_CHAMADO,'dd/mm/yyyy hh24:mi:ss') as DTH_ATENDIMENTO_CHAMADO, TXT_ATENDIMENTO_CHAMADO, b.NOM_COLABORADOR ";
		$sqlCorpo  = "FROM gestaoti.atendimento_chamado a, gestaoti.viw_colaborador b
					  WHERE a.NUM_MATRICULA = b.NUM_MATRICULA_COLABORADOR ";

		if($this->SEQ_ATENDIMENTO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_ATENDIMENTO_CHAMADO = $this->SEQ_ATENDIMENTO_CHAMADO ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
		}
		if($this->DTH_ATENDIMENTO_CHAMADO != "" && $this->DTH_ATENDIMENTO_CHAMADO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ATENDIMENTO_CHAMADO >= to_date('".$this->DTH_ATENDIMENTO_CHAMADO."', 'dd/mm/yyyy hh24/mi/ss') ";
		}
		if($this->DTH_ATENDIMENTO_CHAMADO != "" && $this->DTH_ATENDIMENTO_CHAMADO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ATENDIMENTO_CHAMADO between to_date('".$this->DTH_ATENDIMENTO_CHAMADO."', 'dd/mm/yyyy hh24/mi/ss') and to_date('".$this->DTH_ATENDIMENTO_CHAMADO_FINAL."', 'dd/mm/yyyy hh24/mi/ss') ";
		}
		if($this->DTH_ATENDIMENTO_CHAMADO == "" && $this->DTH_ATENDIMENTO_CHAMADO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ATENDIMENTO_CHAMADO <= to_date('".$this->DTH_ATENDIMENTO_CHAMADO_FINAL."', 'dd/mm/yyyy hh24/mi/ss') ";
		}
		if($this->TXT_ATENDIMENTO_CHAMADO != ""){
			$sqlCorpo .= "  and upper(TXT_ATENDIMENTO_CHAMADO) like '%".strtoupper($this->TXT_ATENDIMENTO_CHAMADO)."%'  ";
		}
		$sqlCount = $sqlCorpo;

		if($orderBy != "" ){
			$sqlCorpo .= " order by $orderBy ";
		}

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);
			$sqlCorpo .= " limit $vQtdRegistros offset $vLimit  ";
			// Pegar a quantidade de registros GERAL
			$this->database->query("select count(1) " . $sqlCount);
			$rowCount = pg_fetch_array($this->database->result);
			$this->setrowCount($rowCount[0]);

		}

		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}

	// **********************
	// DELETE
	// **********************
	function delete($id){
		$sql = "DELETE FROM gestaoti.atendimento_chamado WHERE SEQ_ATENDIMENTO_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_ATENDIMENTO_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_ATENDIMENTO_CHAMADO");

		$sql = "INSERT INTO gestaoti.atendimento_chamado(SEQ_ATENDIMENTO_CHAMADO,
										  SEQ_CHAMADO,
										  NUM_MATRICULA,
										  DTH_ATENDIMENTO_CHAMADO,
										  TXT_ATENDIMENTO_CHAMADO
									)
							 VALUES (".$this->iif($this->SEQ_ATENDIMENTO_CHAMADO=="", "NULL", "'".$this->SEQ_ATENDIMENTO_CHAMADO."'").",
									 ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
									 ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
									 '".date("Y-m-d H:i:s")."',
									 ".$this->iif($this->TXT_ATENDIMENTO_CHAMADO=="", "NULL", "'".$this->TXT_ATENDIMENTO_CHAMADO."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.atendimento_chamado
				 SET SEQ_CHAMADO = ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
					 NUM_MATRICULA = ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
					 DTH_ATENDIMENTO_CHAMADO = ".$this->iif($this->DTH_ATENDIMENTO_CHAMADO=="", "NULL", "to_date('".$this->DTH_ATENDIMENTO_CHAMADO."', 'dd/mm/yyyy hh24/mi/ss')").",
					 TXT_ATENDIMENTO_CHAMADO = ".$this->iif($this->TXT_ATENDIMENTO_CHAMADO=="", "NULL", "'".$this->TXT_ATENDIMENTO_CHAMADO."'")."
				WHERE SEQ_ATENDIMENTO_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $this->iif($vSelected == $row[0],"Selected", ""), $row[1]);
			$cont++;
		}
		return $aItemOption;
	}

	function iif($Condicao, $Sim, $Nao){
		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
	}

} // class : end
?>