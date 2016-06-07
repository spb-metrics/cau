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
* CLASSNAME:        time_sheet
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class time_sheet{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_TIME_SHEET;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $SEQ_TAREFA_TI;   // (normal Attribute)
	var $DAT_INICIO;   // (normal Attribute)
	var $DAT_FIM;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function time_sheet(){
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
	function getSEQ_TIME_SHEET(){
		return $this->SEQ_TIME_SHEET;
	}

	function getSEQ_TAREFA_TI(){
		return $this->SEQ_TAREFA_TI;
	}

	function getDAT_INICIO(){
		return $this->DAT_INICIO;
	}

	function getDAT_FIM(){
		return $this->DAT_FIM;
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
	function setSEQ_TIME_SHEET($val){
		$this->SEQ_TIME_SHEET =  $val;
	}

	function setSEQ_TAREFA_TI($val){
		$this->SEQ_TAREFA_TI =  $val;
	}

	function setDAT_INICIO($val){
		$this->DAT_INICIO =  $val;
	}

	function setDAT_FIM($val){
		$this->DAT_FIM =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.time_sheet WHERE SEQ_TIME_SHEET = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_TIME_SHEET = $row->SEQ_TIME_SHEET;
		$this->SEQ_TAREFA_TI = $row->SEQ_TAREFA_TI;
		$this->DAT_INICIO = $row->DAT_INICIO;
		$this->DAT_FIM = $row->DAT_FIM;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);


		$sqlSelect = "SELECT SEQ_TIME_SHEET , SEQ_TAREFA_TI , DAT_INICIO , DAT_FIM ";
		$sqlCorpo  = "FROM gestaoti.time_sheet
						WHERE 1=1 ";

		if($this->SEQ_TIME_SHEET != ""){
			$sqlCorpo .= "  and SEQ_TIME_SHEET = $this->SEQ_TIME_SHEET ";
		}
		if($this->SEQ_TAREFA_TI != ""){
			$sqlCorpo .= "  and SEQ_TAREFA_TI = $this->SEQ_TAREFA_TI ";
		}
		if($this->DAT_INICIO != "" && $this->DAT_INICIO_FINAL == "" ){
			$sqlCorpo .= "  and DAT_INICIO >= '".ConvDataAMD($this->DAT_INICIO)."' ";
		}
		if($this->DAT_INICIO != "" && $this->DAT_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_INICIO between '".ConvDataAMD($this->DAT_INICIO)."' and '".ConvDataAMD($this->DAT_INICIO_FINAL)."' ";
		}
		if($this->DAT_INICIO == "" && $this->DAT_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_INICIO <= '".ConvDataAMD($this->DAT_INICIO_FINAL)."' ";
		}
		if($this->DAT_FIM != "" && $this->DAT_FIM_FINAL == "" ){
			$sqlCorpo .= "  and DAT_FIM >= '".ConvDataAMD($this->DAT_FIM)."' ";
		}
		if($this->DAT_FIM != "" && $this->DAT_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DAT_FIM between '".ConvDataAMD($this->DAT_FIM)."' and '".ConvDataAMD($this->DAT_FIM_FINAL)."' ";
		}
		if($this->DAT_FIM == "" && $this->DAT_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DAT_FIM <= '".ConvDataAMD($this->DAT_FIM_FINAL)."' ";
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
	// MAX
	// **********************
	function max($id){
		$sql = "SELECT MAX(SEQ_TIME_SHEET) as ultimo FROM gestaoti.time_sheet WHERE SEQ_TAREFA_TI =  $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);

		return $row;
	}

	// **********************
	// DELETE
	// **********************
	function delete($id){
		$sql = "DELETE FROM gestaoti.time_sheet WHERE SEQ_TIME_SHEET = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_TIME_SHEET = $this->database->GetSequenceValue("gestaoti.SEQ_TIME_SHEET"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.time_sheet (SEQ_TIME_SHEET,
										SEQ_TAREFA_TI,
										DAT_INICIO,
										DAT_FIM )
				VALUES (".$this->SEQ_TIME_SHEET.",
						".$this->database->iif($this->SEQ_TAREFA_TI=="", "NULL", "'".$this->SEQ_TAREFA_TI."'").",
						".$this->database->iif($this->DAT_INICIO=="", "NULL", "to_date('".$this->DAT_INICIO."','yyyy-mm-dd')").",
						".$this->database->iif($this->DAT_FIM=="", "NULL", "to_date('".$this->DAT_FIM."','yyyy-mm-dd')")."
				)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = "UPDATE time_sheet
				SET SEQ_TAREFA_TI = ".$this->database->iif($this->SEQ_TAREFA_TI=="", "NULL", "'".$this->SEQ_TAREFA_TI."'").",
					DAT_INICIO = ".$this->database->iif($this->DAT_INICIO=="", "NULL", "to_date('".$this->DAT_INICIO."','yyyy-mm-dd')").",
					DAT_FIM = ".$this->database->iif($this->DAT_FIM=="", "NULL", "to_date('".$this->DAT_FIM."','yyyy-mm-dd')")."
				WHERE SEQ_TIME_SHEET = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>