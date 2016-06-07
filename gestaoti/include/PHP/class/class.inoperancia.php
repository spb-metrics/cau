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
* CLASSNAME:        inoperancia
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class inoperancia{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_INOPERANCIA_ITEM_CONFIG;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $SEQ_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $DTH_INICIO;   // (normal Attribute)
	var $TXT_MOTIVO;   // (normal Attribute)
	var $DTH_FIM;   // (normal Attribute)
	var $TXT_SOLUCAO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function inoperancia(){
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
	function getSEQ_INOPERANCIA_ITEM_CONFIG(){
		return $this->SEQ_INOPERANCIA_ITEM_CONFIG;
	}

	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	}

	function getDTH_INICIO(){
		return $this->DTH_INICIO;
	}

	function getTXT_MOTIVO(){
		return $this->TXT_MOTIVO;
	}

	function getDTH_FIM(){
		return $this->DTH_FIM;
	}

	function getTXT_SOLUCAO(){
		return $this->TXT_SOLUCAO;
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
	function setSEQ_INOPERANCIA_ITEM_CONFIG($val){
		$this->SEQ_INOPERANCIA_ITEM_CONFIG =  $val;
	}

	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}

	function setDTH_INICIO($val){
		$this->DTH_INICIO =  $val;
	}

	function setTXT_MOTIVO($val){
		$this->TXT_MOTIVO =  $val;
	}

	function setDTH_FIM($val){
		$this->DTH_FIM =  $val;
	}

	function setTXT_SOLUCAO($val){
		$this->TXT_SOLUCAO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.INOPERANCIA_ITEM_CONFIGURACAO WHERE SEQ_INOPERANCIA_ITEM_CONFIG = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_INOPERANCIA_ITEM_CONFIG = $row->seq_inoperancia_item_config;
		$this->SEQ_ITEM_CONFIGURACAO = $row->seq_item_configuracao;
		$this->DTH_INICIO = $row->dth_inicio;
		$this->TXT_MOTIVO = $row->txt_motivo;
		$this->DTH_FIM = $row->dth_fim;
		$this->TXT_SOLUCAO = $row->txt_solucao;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_INOPERANCIA_ITEM_CONFIG, SEQ_ITEM_CONFIGURACAO,
						  		to_char(DTH_INICIO,'yyyy-mm-dd hh:mi') as DTH_INICIO,
								TXT_MOTIVO,
								to_char(DTH_FIM,'yyyy-mm-dd hh:mi') as DTH_FIM,
								TXT_SOLUCAO ";
		$sqlCorpo  = "FROM gestaoti.INOPERANCIA_ITEM_CONFIGURACAO
						WHERE 1=1 ";
		if($this->SEQ_INOPERANCIA_ITEM_CONFIG != ""){
			$sqlCorpo .= "  and SEQ_INOPERANCIA_ITEM_CONFIG = $this->SEQ_INOPERANCIA_ITEM_CONFIG ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO >= '".ConvDataAMD($this->DTH_INICIO)."' ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO between '".ConvDataAMD($this->DTH_INICIO)."' and '".ConvDataAMD($this->DTH_INICIO_FINAL)."' ";
		}
		if($this->DTH_INICIO == "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO <= '".ConvDataAMD($this->DTH_INICIO_FINAL)."' ";
		}
		if($this->TXT_MOTIVO != ""){
			$sqlCorpo .= "  and upper(TXT_MOTIVO) like '%".strtoupper($this->TXT_MOTIVO)."%'  ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL == "" ){
			$sqlCorpo .= "  and DTH_FIM >= '".ConvDataAMD($this->DTH_FIM)."' ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM between '".ConvDataAMD($this->DTH_FIM)."' and '".ConvDataAMD($this->DTH_FIM_FINAL)."' ";
		}
		if($this->DTH_FIM == "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM <= '".ConvDataAMD($this->DTH_FIM_FINAL)."' ";
		}
		if($this->TXT_SOLUCAO != ""){
			$sqlCorpo .= "  and upper(TXT_SOLUCAO) like '%".strtoupper($this->TXT_SOLUCAO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.INOPERANCIA_ITEM_CONFIGURACAO WHERE SEQ_ITEM_CONFIGURACAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_INOPERANCIA_ITEM_CONFIG = $this->database->GetSequenceValue("gestaoti.SEQ_INOPERANCIA_ITEM_CONFIG"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.INOPERANCIA_ITEM_CONFIGURACAO (
						SEQ_INOPERANCIA_ITEM_CONFIG,
						SEQ_ITEM_CONFIGURACAO,
						DTH_INICIO,
						TXT_MOTIVO,
						DTH_FIM,
						TXT_SOLUCAO )
				VALUES (".$this->SEQ_INOPERANCIA_ITEM_CONFIG.",
						".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
						".$this->database->iif($this->DTH_INICIO=="", "NULL", "to_date('".$this->DTH_INICIO."','yyyy-mm-dd hh:mi:ss')").",
						".$this->database->iif($this->TXT_MOTIVO=="", "NULL", "'".$this->TXT_MOTIVO."'").",
						".$this->database->iif($this->DTH_FIM=="", "NULL", "to_date('".$this->DTH_FIM."','yyyy-mm-dd hh:mi:ss')").",
						".$this->database->iif($this->TXT_SOLUCAO=="", "NULL", "'".$this->TXT_SOLUCAO."'")."
						)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = "UPDATE INOPERANCIA_ITEM_CONFIGURACAO
				SET SEQ_ITEM_CONFIGURACAO = ".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
					DTH_INICIO = ".$this->database->iif($this->DTH_INICIO=="", "NULL", "to_date('".$this->DTH_INICIO."','yyyy-mm-dd hh:mi:ss')").",
					TXT_MOTIVO = ".$this->database->iif($this->TXT_MOTIVO=="", "NULL", "'".$this->TXT_MOTIVO."'").",
					DTH_FIM = ".$this->database->iif($this->DTH_FIM=="", "NULL", "to_date('".$this->DTH_FIM."','yyyy-mm-dd hh:mi:ss')").",
					TXT_SOLUCAO = ".$this->database->iif($this->TXT_SOLUCAO=="", "NULL", "'".$this->TXT_SOLUCAO."'")."
					WHERE SEQ_INOPERANCIA_ITEM_CONFIG = $id ";
		$result = $this->database->query($sql);
	}

} // class : end
?>