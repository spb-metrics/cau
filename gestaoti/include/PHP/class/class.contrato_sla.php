<?php
/*
Copyright 2011 da EMBRATUR
 Este arquivo é parte do programa CAU - Central de Atendimento ao Usuário
 O CAU é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela 
 Fundação do Software Livre (FSF); na versão 2 da Licença.
 Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
 Observe no diretório gestaoti/install/ a cópia da Licença Pública Geral GNU, sob o título "licensa_uso.htm". 
 Se preferir acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software 
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
/*
*
* -------------------------------------------------------
* CLASSNAME:        contrato_sla
* -------------------------------------------------------
*
*/
include_once("include/PHP/class/class.database.php");

// **********************
// CLASS DECLARATION
// **********************

class contrato_sla{ 
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_CONTRATO_SLA;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página
	
	var $UOR_CODIGO;   // (normal Attribute)
	var $DAT_ASSINATURA;   // (normal Attribute)
	var $FLG_PERIODO_REVISAO;   // (normal Attribute)
	var $DES_LOCALIZACAO_REDE;   // (normal Attribute)
	
	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function contrato_sla(){
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
	
	
	function getSEQ_CONTRATO_SLA(){
		return $this->SEQ_CONTRATO_SLA;
	}
	
	function getUOR_CODIGO(){
		return $this->UOR_CODIGO;
	}
	
	function getDAT_ASSINATURA(){
		return $this->DAT_ASSINATURA;
	}
	
	function getFLG_PERIODO_REVISAO(){
		return $this->FLG_PERIODO_REVISAO;
	}
	
	function getDES_LOCALIZACAO_REDE(){
		return $this->DES_LOCALIZACAO_REDE;
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
	
	
	function setSEQ_CONTRATO_SLA($val){
		$this->SEQ_CONTRATO_SLA =  $val;
	}
	
	function setUOR_CODIGO($val){
		$this->UOR_CODIGO =  $val;
	}
	
	function setDAT_ASSINATURA($val){
		$this->DAT_ASSINATURA =  $val;
	}
	
	function setFLG_PERIODO_REVISAO($val){
		$this->FLG_PERIODO_REVISAO =  $val;
	}
	
	function setDES_LOCALIZACAO_REDE($val){
		$this->DES_LOCALIZACAO_REDE =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id){
		$sql =  "SELECT * FROM gestaoti.contrato_sla WHERE SEQ_CONTRATO_SLA = $id;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = mysql_error();
		$row = mysql_fetch_object($result);
		
		$this->SEQ_CONTRATO_SLA = $row->SEQ_CONTRATO_SLA;
		$this->UOR_CODIGO = $row->UOR_CODIGO;
		$this->DAT_ASSINATURA = $row->DAT_ASSINATURA;
		$this->FLG_PERIODO_REVISAO = $row->FLG_PERIODO_REVISAO;
		$this->DES_LOCALIZACAO_REDE = $row->DES_LOCALIZACAO_REDE;
	}
	
	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		
		$sqlSelect = " SELECT SEQ_CONTRATO_SLA , UOR_CODIGO , DAT_ASSINATURA , FLG_PERIODO_REVISAO , DES_LOCALIZACAO_REDE ";
		$sqlCorpo  = " FROM gestaoti.contrato_sla
			      WHERE 1=1 ";
			
		if($this->SEQ_CONTRATO_SLA != ""){
			$sqlCorpo .= "  and SEQ_CONTRATO_SLA = $this->SEQ_CONTRATO_SLA ";
		}		
		if($this->UOR_CODIGO != ""){
			$sqlCorpo .= "  and UOR_CODIGO = $this->UOR_CODIGO ";
		}
		if($this->DAT_ASSINATURA != "" && $this->DAT_ASSINATURA_FINAL == "" ){
			$sqlCorpo .= "  and DAT_ASSINATURA >= '".ConvDataAMD($this->DAT_ASSINATURA)."' ";
		}
		if($this->DAT_ASSINATURA != "" && $this->DAT_ASSINATURA_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ASSINATURA between '".ConvDataAMD($this->DAT_ASSINATURA)."' and '".ConvDataAMD($this->DAT_ASSINATURA_FINAL)."' ";
		}
		if($this->DAT_ASSINATURA == "" && $this->DAT_ASSINATURA_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ASSINATURA <= '".ConvDataAMD($this->DAT_ASSINATURA_FINAL)."' ";
		}
		if($this->FLG_PERIODO_REVISAO != ""){
			$sqlCorpo .= "  and FLG_PERIODO_REVISAO = '$this->FLG_PERIODO_REVISAO' ";
		}
		if($this->DES_LOCALIZACAO_REDE != ""){
			$sqlCorpo .= "  and upper(DES_LOCALIZACAO_REDE) like '%".strtoupper($this->DES_LOCALIZACAO_REDE)."%'  ";
		}
		if($orderBy != "" ){
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
		$sql = "DELETE FROM gestaoti.contrato_sla WHERE SEQ_CONTRATO_SLA = $id;";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert(){
		$this->SEQ_CONTRATO_SLA = ""; // clear key for autoincrement
		
		$sql = "INSERT INTO gestaoti.contrato_sla ( UOR_CODIGO,DAT_ASSINATURA,FLG_PERIODO_REVISAO,DES_LOCALIZACAO_REDE ) VALUES ( ".$this->iif($this->UOR_CODIGO=="", "NULL", "'".$this->UOR_CODIGO."'").",".$this->iif($this->DAT_ASSINATURA=="", "NULL", "'".$this->DAT_ASSINATURA."'").",".$this->iif($this->FLG_PERIODO_REVISAO=="", "NULL", "'".$this->FLG_PERIODO_REVISAO."'").",".$this->iif($this->DES_LOCALIZACAO_REDE=="", "NULL", "'".$this->DES_LOCALIZACAO_REDE."'")." )";
		$result = $this->database->query($sql);
		$this->SEQ_CONTRATO_SLA = mysql_insert_id($this->database->link);
		if(!$result) $this->error = mysql_error();
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id){
		$sql = " UPDATE gestaoti.contrato_sla SET  UOR_CODIGO = ".$this->iif($this->UOR_CODIGO=="", "NULL", "'".$this->UOR_CODIGO."'").",DAT_ASSINATURA = ".$this->iif($this->DAT_ASSINATURA=="", "NULL", "'".$this->DAT_ASSINATURA."'").",FLG_PERIODO_REVISAO = ".$this->iif($this->FLG_PERIODO_REVISAO=="", "NULL", "'".$this->FLG_PERIODO_REVISAO."'").",DES_LOCALIZACAO_REDE = ".$this->iif($this->DES_LOCALIZACAO_REDE=="", "NULL", "'".$this->DES_LOCALIZACAO_REDE."'")." WHERE SEQ_CONTRATO_SLA = $id ";
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