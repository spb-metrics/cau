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
*
* -------------------------------------------------------
* CLASSNAME:        historico_item_configuracao
* -------------------------------------------------------
*
*/
include_once("include/PHP/class/class.database.php");

// **********************
// CLASS DECLARATION
// **********************

class historico_item_configuracao{ 
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_HISTORICO_ITEM_CONFIGURACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina
	
	var $SEQ_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $DAT_HISTORICO_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $NOM_TITULO;   // (normal Attribute)
	var $FLG_FASE_PROJETO;   // (normal Attribute)
	var $DES_HISTORICO_ITEM_CONFIGURACAO;   // (normal Attribute)
	
	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function historico_item_configuracao(){
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
	
	
	function getSEQ_HISTORICO_ITEM_CONFIGURACAO(){
		return $this->SEQ_HISTORICO_ITEM_CONFIGURACAO;
	}
	
	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	}
	
	function getDAT_HISTORICO_ITEM_CONFIGURACAO(){
		return $this->DAT_HISTORICO_ITEM_CONFIGURACAO;
	}
	
	function getNOM_TITULO(){
		return $this->NOM_TITULO;
	}
	
	function getFLG_FASE_PROJETO(){
		return $this->FLG_FASE_PROJETO;
	}
	
	function getDES_HISTORICO_ITEM_CONFIGURACAO(){
		return $this->DES_HISTORICO_ITEM_CONFIGURACAO;
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
	
	
	function setSEQ_HISTORICO_ITEM_CONFIGURACAO($val){
		$this->SEQ_HISTORICO_ITEM_CONFIGURACAO =  $val;
	}
	
	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}
	
	function setDAT_HISTORICO_ITEM_CONFIGURACAO($val){
		$this->DAT_HISTORICO_ITEM_CONFIGURACAO =  $val;
	}
	
	function setNOM_TITULO($val){
		$this->NOM_TITULO =  $val;
	}
	
	function setFLG_FASE_PROJETO($val){
		$this->FLG_FASE_PROJETO =  $val;
	}
	
	function setDES_HISTORICO_ITEM_CONFIGURACAO($val){
		$this->DES_HISTORICO_ITEM_CONFIGURACAO =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id){
		$sql =  "SELECT * FROM gestaoti.historico_item_configuracao WHERE SEQ_HISTORICO_ITEM_CONFIGURACAO = $id;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = mysql_error();
		$row = mysql_fetch_object($result);
		
		$this->SEQ_HISTORICO_ITEM_CONFIGURACAO = $row->SEQ_HISTORICO_ITEM_CONFIGURACAO;
		$this->SEQ_ITEM_CONFIGURACAO = $row->SEQ_ITEM_CONFIGURACAO;
		$this->DAT_HISTORICO_ITEM_CONFIGURACAO = $row->DAT_HISTORICO_ITEM_CONFIGURACAO;
		$this->NOM_TITULO = $row->NOM_TITULO;
		$this->FLG_FASE_PROJETO = $row->FLG_FASE_PROJETO;
		$this->DES_HISTORICO_ITEM_CONFIGURACAO = $row->DES_HISTORICO_ITEM_CONFIGURACAO;
	}
	
	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		
		$sqlSelect = " SELECT SEQ_HISTORICO_ITEM_CONFIGURACAO , SEQ_ITEM_CONFIGURACAO , DAT_HISTORICO_ITEM_CONFIGURACAO , NOM_TITULO , FLG_FASE_PROJETO , DES_HISTORICO_ITEM_CONFIGURACAO ";
		$sqlCorpo  = " FROM gestaoti.historico_item_configuracao
			      WHERE 1=1 ";
			
		if($this->SEQ_HISTORICO_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_HISTORICO_ITEM_CONFIGURACAO = $this->SEQ_HISTORICO_ITEM_CONFIGURACAO ";
		}		
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->DAT_HISTORICO_ITEM_CONFIGURACAO != "" && $this->DAT_HISTORICO_ITEM_CONFIGURACAO_FINAL == "" ){
			$sqlCorpo .= "  and DAT_HISTORICO_ITEM_CONFIGURACAO >= '".ConvDataAMD($this->DAT_HISTORICO_ITEM_CONFIGURACAO)."' ";
		}
		if($this->DAT_HISTORICO_ITEM_CONFIGURACAO != "" && $this->DAT_HISTORICO_ITEM_CONFIGURACAO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_HISTORICO_ITEM_CONFIGURACAO between '".ConvDataAMD($this->DAT_HISTORICO_ITEM_CONFIGURACAO)."' and '".ConvDataAMD($this->DAT_HISTORICO_ITEM_CONFIGURACAO_FINAL)."' ";
		}
		if($this->DAT_HISTORICO_ITEM_CONFIGURACAO == "" && $this->DAT_HISTORICO_ITEM_CONFIGURACAO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_HISTORICO_ITEM_CONFIGURACAO <= '".ConvDataAMD($this->DAT_HISTORICO_ITEM_CONFIGURACAO_FINAL)."' ";
		}
		if($this->NOM_TITULO != ""){
			$sqlCorpo .= "  and upper(NOM_TITULO) like '%".strtoupper($this->NOM_TITULO)."%'  ";
		}
		if($this->FLG_FASE_PROJETO != ""){
			$sqlCorpo .= "  and FLG_FASE_PROJETO = '$this->FLG_FASE_PROJETO' ";
		}
		if($this->DES_HISTORICO_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(DES_HISTORICO_ITEM_CONFIGURACAO) like '%".strtoupper($this->DES_HISTORICO_ITEM_CONFIGURACAO)."%'  ";
		}
		if($orderBy != ""){
			$sqlOrder = " order by $orderBy ";
		}
		
		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);
			$sqlOrder .= " limit $vLimit, $vQtdRegistros ";
			$this->database->query("select count(1) " . $sqlCorpo);
			$rowCount = mysql_fetch_array($this->database->result, MYSQL_NUM);
			$this->setrowCount($rowCount[0]);
		}
		
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
		if(!$this->database->result) $this->error = mysql_error();
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id){
		$sql = "DELETE FROM gestaoti.historico_item_configuracao WHERE SEQ_ITEM_CONFIGURACAO = $id;";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert(){
		$this->SEQ_HISTORICO_ITEM_CONFIGURACAO = ""; // clear key for autoincrement
		
		$sql = "INSERT INTO gestaoti.historico_item_configuracao ( SEQ_ITEM_CONFIGURACAO,DAT_HISTORICO_ITEM_CONFIGURACAO,NOM_TITULO,FLG_FASE_PROJETO,DES_HISTORICO_ITEM_CONFIGURACAO ) VALUES ( ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",".$this->iif($this->DAT_HISTORICO_ITEM_CONFIGURACAO=="", "NULL", "'".$this->DAT_HISTORICO_ITEM_CONFIGURACAO."'").",".$this->iif($this->NOM_TITULO=="", "NULL", "'".$this->NOM_TITULO."'").",".$this->iif($this->FLG_FASE_PROJETO=="", "NULL", "'".$this->FLG_FASE_PROJETO."'").",".$this->iif($this->DES_HISTORICO_ITEM_CONFIGURACAO=="", "NULL", "'".$this->DES_HISTORICO_ITEM_CONFIGURACAO."'")." )";
		$result = $this->database->query($sql);
		$this->SEQ_HISTORICO_ITEM_CONFIGURACAO = mysql_insert_id($this->database->link);
		if(!$result) $this->error = mysql_error();
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id){
		$sql = " UPDATE gestaoti.historico_item_configuracao SET  SEQ_ITEM_CONFIGURACAO = ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",DAT_HISTORICO_ITEM_CONFIGURACAO = ".$this->iif($this->DAT_HISTORICO_ITEM_CONFIGURACAO=="", "NULL", "'".$this->DAT_HISTORICO_ITEM_CONFIGURACAO."'").",NOM_TITULO = ".$this->iif($this->NOM_TITULO=="", "NULL", "'".$this->NOM_TITULO."'").",FLG_FASE_PROJETO = ".$this->iif($this->FLG_FASE_PROJETO=="", "NULL", "'".$this->FLG_FASE_PROJETO."'").",DES_HISTORICO_ITEM_CONFIGURACAO = ".$this->iif($this->DES_HISTORICO_ITEM_CONFIGURACAO=="", "NULL", "'".$this->DES_HISTORICO_ITEM_CONFIGURACAO."'")." WHERE SEQ_HISTORICO_ITEM_CONFIGURACAO = $id ";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();
	
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