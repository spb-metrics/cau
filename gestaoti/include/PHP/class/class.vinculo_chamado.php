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
* Nome da Classe:	atribuicao_chamado
* Nome da tabela:	vinculo_chamado
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
class vinculo_chamado{
	// class : begin

	// ***********************
	// DECLARA��O DE ATRIBUTOS
	// ***********************
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $SEQ_CHAMADO_MASTER;
	var $SEQ_CHAMADO_FILHO;
 	var $NUM_MATRICULA;
 	var $DTH_VINCULACAO;
 	var $SEQ_SITUACAO_CHAMADO_MASTER;

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function vinculo_chamado(){
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

	function getSEQ_CHAMADO_MASTER(){
		return $this->SEQ_CHAMADO_MASTER;
	}
	function getSEQ_CHAMADO_FILHO(){
		return $this->SEQ_CHAMADO_FILHO;
	}
	function getNUM_MATRICULA(){
		return $this->NUM_MATRICULA;
	}
	function getDTH_VINCULACAO(){
		return $this->DTH_VINCULACAO;
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

	function setSEQ_CHAMADO_MASTER($val){
		$this->SEQ_CHAMADO_MASTER =  $val;
	}
	function setSEQ_CHAMADO_FILHO($val){
		$this->SEQ_CHAMADO_FILHO =  $val;
	}
	function setNUM_MATRICULA($val){
		$this->NUM_MATRICULA =  $val;
	}
	function setDTH_VINCULACAO($val){
		$this->DTH_VINCULACAO =  $val;
	}
	function setSEQ_SITUACAO_CHAMADO_MASTER($val){
		$this->SEQ_SITUACAO_CHAMADO_MASTER =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_CHAMADO_MASTER, SEQ_CHAMADO_FILHO, NUM_MATRICULA,
					   to_char(DTH_VINCULACAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_VINCULACAO
				FROM gestaoti.vinculo_chamado
				WHERE SEQ_ATRIBUICAO_CHAMADO = $id";
		//print $sql;
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_CHAMADO_MASTER = $row->seq_chamado_master;
		$this->SEQ_CHAMADO_FILHO = $row->seq_chamado_filho;
		$this->NUM_MATRICULA = $row->num_matricula;
		$this->DTH_VINCULACAO = $row->dth_vinculacao;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT SEQ_CHAMADO_MASTER, SEQ_CHAMADO_FILHO, NUM_MATRICULA,
					          to_char(DTH_VINCULACAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_VINCULACAO ";
		$sqlCorpo  = " FROM gestaoti.vinculo_chamado a
					   WHERE 1=1 ";

		if($this->SEQ_CHAMADO_MASTER != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO_MASTER = $this->SEQ_CHAMADO_MASTER ";
		}
		if($this->SEQ_CHAMADO_FILHO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO_FILHO = $this->SEQ_CHAMADO_FILHO ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
		}
		if($this->DTH_VINCULACAO != "" && $this->DTH_VINCULACAO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_VINCULACAO >= to_date('".$this->DTH_VINCULACAO."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_VINCULACAO != "" && $this->DTH_VINCULACAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_VINCULACAO between to_date('".$this->DTH_VINCULACAO."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_VINCULACAO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_VINCULACAO == "" && $this->DTH_VINCULACAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_VINCULACAO <= to_date('".$this->DTH_VINCULACAO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->SEQ_SITUACAO_CHAMADO_MASTER != ""){
			$sqlCorpo .= "  and exists (select 1 from gestaoti.chamado
										where SEQ_CHAMADO = a.SEQ_CHAMADO_MASTER
										and SEQ_SITUACAO_CHAMADO in (".$this->SEQ_SITUACAO_CHAMADO_MASTER.")
										) ";
		}

		$sqlCount = $sqlCorpo;

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
	function delete($v_SEQ_CHAMADO_MASTER, $v_SEQ_CHAMADO_FILHO){
		$sql = "DELETE FROM gestaoti.vinculo_chamado
				WHERE SEQ_CHAMADO_MASTER = $v_SEQ_CHAMADO_MASTER
				and   SEQ_CHAMADO_FILHO = $v_SEQ_CHAMADO_FILHO";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.vinculo_chamado(SEQ_CHAMADO_MASTER,
										  SEQ_CHAMADO_FILHO,
										  NUM_MATRICULA,
										  DTH_VINCULACAO
									)
							 VALUES (".$this->iif($this->SEQ_CHAMADO_MASTER=="", "NULL", "'".$this->SEQ_CHAMADO_MASTER."'").",
									 ".$this->iif($this->SEQ_CHAMADO_FILHO=="", "NULL", "'".$this->SEQ_CHAMADO_FILHO."'").",
									 ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
									 '".date("Y-m-d H:i:s")."'
							 		) ";
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