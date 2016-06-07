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
* Nome da Classe:	aprovacao_chamado
* Nome da tabela:	aprovacao_chamado
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
class aprovacao_chamado{
	// class : begin

	// ***********************
	// DECLARA��O DE ATRIBUTOS
	// ***********************
	var $SEQ_APROVACAO_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $SEQ_CHAMADO;   // (normal Attribute)
	var $NUM_MATRICULA;   // (normal Attribute)
	var $DTH_APROVACAO;   // (normal Attribute)
	var $DTH_PREVISTA;   // (normal Attribute)
	var $TXT_JUSTIFICATIVA;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function aprovacao_chamado(){
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

	function getSEQ_APROVACAO_CHAMADO(){
		return $this->SEQ_APROVACAO_CHAMADO;
	}

	function getSEQ_CHAMADO(){
		return $this->SEQ_CHAMADO;
	}

	function getNUM_MATRICULA(){
		return $this->NUM_MATRICULA;
	}

	function getDTH_APROVACAO(){
		return $this->DTH_APROVACAO;
	}

	function getDTH_PREVISTA(){
		return $this->DTH_PREVISTA;
	}

	function getTXT_JUSTIFICATIVA(){
		return $this->TXT_JUSTIFICATIVA;
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

	function setSEQ_APROVACAO_CHAMADO($val){
		$this->SEQ_APROVACAO_CHAMADO =  $val;
	}

	function setSEQ_CHAMADO($val){
		$this->SEQ_CHAMADO =  $val;
	}

	function setNUM_MATRICULA($val){
		$this->NUM_MATRICULA =  $val;
	}

	function setDTH_APROVACAO($val){
		$this->DTH_APROVACAO =  $val;
	}

	function setDTH_PREVISTA($val){
		$this->DTH_PREVISTA =  $val;
	}

	function setTXT_JUSTIFICATIVA($val){
		$this->TXT_JUSTIFICATIVA =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_APROVACAO_CHAMADO , SEQ_CHAMADO , NUM_MATRICULA , to_char(DTH_APROVACAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_APROVACAO, DTH_APROVACAO as DTH_APROVACAO_DATA , to_char(DTH_PREVISTA, 'dd/mm/yyyy hh24:mi:ss') as DTH_PREVISTA, DTH_PREVISTA as DTH_PREVISTA_DATA , TXT_JUSTIFICATIVA
			    FROM gestaoti.aprovacao_chamado
				WHERE SEQ_APROVACAO_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_APROVACAO_CHAMADO = $row->seq_aprovacao_chamado;
		$this->SEQ_CHAMADO = $row->seq_chamado;
		$this->NUM_MATRICULA = $row->num_matricula;
		$this->DTH_APROVACAO = $row->dth_aprovacao;
		$this->DTH_PREVISTA = $row->dth_prevista;
		$this->TXT_JUSTIFICATIVA = $row->txt_justificativa;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function GetUltimoAprovacao($id){
		$sql = "SELECT SEQ_APROVACAO_CHAMADO, SEQ_CHAMADO, NUM_MATRICULA,
						to_char(DTH_APROVACAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_APROVACAO,
						to_char(DTH_PREVISTA, 'dd/mm/yyyy hh24:mi:ss') as DTH_PREVISTA, TXT_JUSTIFICATIVA
				FROM gestaoti.aprovacao_chamado a
				where  SEQ_CHAMADO = $id
				and SEQ_APROVACAO_CHAMADO = (select max(SEQ_APROVACAO_CHAMADO)
				                             FROM gestaoti.aprovacao_chamado b
				                             where b.SEQ_CHAMADO = a.SEQ_CHAMADO)";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		if($this->database->rows > 0){
			$row = pg_fetch_object($result, 0);
			$this->SEQ_APROVACAO_CHAMADO = $row->seq_aprovacao_chamado;
			$this->SEQ_CHAMADO = $row->seq_chamado;
			$this->NUM_MATRICULA = $row->num_matricula;
			$this->DTH_APROVACAO = $row->dth_aprovacao;
			$this->DTH_PREVISTA = $row->dth_prevista;
			$this->TXT_JUSTIFICATIVA = $row->txt_justificativa;
		}
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_APROVACAO_CHAMADO , SEQ_CHAMADO , NUM_MATRICULA ,
							 to_char(DTH_PREVISTA, 'dd/mm/yyyy hh24:mi:ss') as DTH_PREVISTA,
							 DTH_PREVISTA as DTH_PREVISTA_DATA,
			  			     to_char(DTH_APROVACAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_APROVACAO,
						     DTH_APROVACAO as DTH_APROVACAO_DATA, TXT_JUSTIFICATIVA, b.NOM_COLABORADOR ";
		$sqlCorpo  = "FROM gestaoti.aprovacao_chamado a, gestaoti.viw_colaborador b
					  WHERE a.num_matricula = b.NUM_MATRICULA_COLABORADOR ";

		if($this->SEQ_APROVACAO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_APROVACAO_CHAMADO = $this->SEQ_APROVACAO_CHAMADO ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
		}
		if($this->DTH_APROVACAO != "" && $this->DTH_APROVACAO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_APROVACAO >= to_date('".$this->DTH_APROVACAO."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_APROVACAO != "" && $this->DTH_APROVACAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_APROVACAO between to_date('".$this->DTH_APROVACAO."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_APROVACAO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_APROVACAO == "" && $this->DTH_APROVACAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_APROVACAO <= to_date('".$this->DTH_APROVACAO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_PREVISTA != "" && $this->DTH_PREVISTA_FINAL == "" ){
			$sqlCorpo .= "  and DTH_PREVISTA >= to_date('".$this->DTH_PREVISTA."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_PREVISTA != "" && $this->DTH_PREVISTA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_PREVISTA between to_date('".$this->DTH_PREVISTA."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_PREVISTA_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_PREVISTA == "" && $this->DTH_PREVISTA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_PREVISTA <= to_date('".$this->DTH_PREVISTA_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->TXT_JUSTIFICATIVA != ""){
			$sqlCorpo .= "  and upper(TXT_JUSTIFICATIVA) like '%".strtoupper($this->TXT_JUSTIFICATIVA)."%'  ";
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
		$sql = "DELETE FROM gestaoti.aprovacao_chamado WHERE SEQ_APROVACAO_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_APROVACAO_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_APROVACAO_CHAMADO");

		$sql = "INSERT INTO gestaoti.aprovacao_chamado(SEQ_APROVACAO_CHAMADO,
										  SEQ_CHAMADO,
										  NUM_MATRICULA,
										  DTH_APROVACAO,
										  DTH_PREVISTA,
										  TXT_JUSTIFICATIVA
									)
							 VALUES (".$this->iif($this->SEQ_APROVACAO_CHAMADO=="", "NULL", "'".$this->SEQ_APROVACAO_CHAMADO."'").",
									 ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
									 ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
									 '".date("Y-m-d H:i:s")."',
									 ".$this->iif($this->DTH_PREVISTA=="", "NULL", "'".$this->DTH_PREVISTA."'").",
									 ".$this->iif($this->TXT_JUSTIFICATIVA=="", "NULL", "'".$this->TXT_JUSTIFICATIVA."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.aprovacao_chamado
				 SET SEQ_CHAMADO = ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
					 NUM_MATRICULA = ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
					 DTH_APROVACAO = ".$this->iif($this->DTH_APROVACAO=="", "NULL", "to_date('".$this->DTH_APROVACAO."', 'dd/mm/yyyy hh24:mi:ss')").",
					 DTH_PREVISTA = ".$this->iif($this->DTH_PREVISTA=="", "NULL", "to_date('".$this->DTH_PREVISTA."', 'dd/mm/yyyy hh24:mi:ss')").",
					 TXT_JUSTIFICATIVA = ".$this->iif($this->TXT_JUSTIFICATIVA=="", "NULL", "'".$this->TXT_JUSTIFICATIVA."'")."
				WHERE SEQ_APROVACAO_CHAMADO = $id ";
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