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
* CLASSNAME:        item_configuracao_suporte
* -------------------------------------------------------
*
*/
include_once("include/PHP/class/class.database.php");

// **********************
// CLASS DECLARATION
// **********************

class item_configuracao_suporte{ 
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_ITEM_CONFIGURACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página
	
	var $SEQ_SISTEMA_OPERACIONAL;   // (normal Attribute)
	var $SEQ_MARCA_HARDWARE;   // (normal Attribute)
	var $SEQ_TIPO_HARDWARE;   // (normal Attribute)
	var $NUM_IP;   // (normal Attribute)
	var $NOM_MODELO;   // (normal Attribute)
	var $DES_LOCALIZACAO;   // (normal Attribute)
	var $DES_PROCESSADORES;   // (normal Attribute)
	var $DES_OBSERVACAO;   // (normal Attribute)
	var $DAT_AQUISICAO;   // (normal Attribute)
	var $DAT_ATUALIZACAO;   // (normal Attribute)
	var $NUM_IDENTIFICADOR;   // (normal Attribute)
	var $NUM_SERIE;   // (normal Attribute)
	var $DES_COR;   // (normal Attribute)
	var $EMP_NUMERO_MATRICULA_DETENTOR;   // (normal Attribute)
	var $FLG_ATIVO;   // (normal Attribute)
	var $QTD_VIDA_UTIL_ESTIMADA;   // (normal Attribute)
	var $VAL_DEPRECIACAO_ACUMULADA;   // (normal Attribute)
	var $VAL_AQUISICAO;   // (normal Attribute)
	
	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function item_configuracao_suporte(){
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
	
	
	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	}
	
	function getSEQ_SISTEMA_OPERACIONAL(){
		return $this->SEQ_SISTEMA_OPERACIONAL;
	}
	
	function getSEQ_MARCA_HARDWARE(){
		return $this->SEQ_MARCA_HARDWARE;
	}
	
	function getSEQ_TIPO_HARDWARE(){
		return $this->SEQ_TIPO_HARDWARE;
	}
	
	function getNUM_IP(){
		return $this->NUM_IP;
	}
	
	function getNOM_MODELO(){
		return $this->NOM_MODELO;
	}
	
	function getDES_LOCALIZACAO(){
		return $this->DES_LOCALIZACAO;
	}
	
	function getDES_PROCESSADORES(){
		return $this->DES_PROCESSADORES;
	}
	
	function getDES_OBSERVACAO(){
		return $this->DES_OBSERVACAO;
	}
	
	function getDAT_AQUISICAO(){
		return $this->DAT_AQUISICAO;
	}
	
	function getDAT_ATUALIZACAO(){
		return $this->DAT_ATUALIZACAO;
	}
	
	function getNUM_IDENTIFICADOR(){
		return $this->NUM_IDENTIFICADOR;
	}
	
	function getNUM_SERIE(){
		return $this->NUM_SERIE;
	}
	
	function getDES_COR(){
		return $this->DES_COR;
	}
	
	function getEMP_NUMERO_MATRICULA_DETENTOR(){
		return $this->EMP_NUMERO_MATRICULA_DETENTOR;
	}
	
	function getFLG_ATIVO(){
		return $this->FLG_ATIVO;
	}
	
	function getQTD_VIDA_UTIL_ESTIMADA(){
		return $this->QTD_VIDA_UTIL_ESTIMADA;
	}
	
	function getVAL_DEPRECIACAO_ACUMULADA(){
		return $this->VAL_DEPRECIACAO_ACUMULADA;
	}
	
	function getVAL_AQUISICAO(){
		return $this->VAL_AQUISICAO;
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
	
	
	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}
	
	function setSEQ_SISTEMA_OPERACIONAL($val){
		$this->SEQ_SISTEMA_OPERACIONAL =  $val;
	}
	
	function setSEQ_MARCA_HARDWARE($val){
		$this->SEQ_MARCA_HARDWARE =  $val;
	}
	
	function setSEQ_TIPO_HARDWARE($val){
		$this->SEQ_TIPO_HARDWARE =  $val;
	}
	
	function setNUM_IP($val){
		$this->NUM_IP =  $val;
	}
	
	function setNOM_MODELO($val){
		$this->NOM_MODELO =  $val;
	}
	
	function setDES_LOCALIZACAO($val){
		$this->DES_LOCALIZACAO =  $val;
	}
	
	function setDES_PROCESSADORES($val){
		$this->DES_PROCESSADORES =  $val;
	}
	
	function setDES_OBSERVACAO($val){
		$this->DES_OBSERVACAO =  $val;
	}
	
	function setDAT_AQUISICAO($val){
		$this->DAT_AQUISICAO =  $val;
	}
	
	function setDAT_ATUALIZACAO($val){
		$this->DAT_ATUALIZACAO =  $val;
	}
	
	function setNUM_IDENTIFICADOR($val){
		$this->NUM_IDENTIFICADOR =  $val;
	}
	
	function setNUM_SERIE($val){
		$this->NUM_SERIE =  $val;
	}
	
	function setDES_COR($val){
		$this->DES_COR =  $val;
	}
	
	function setEMP_NUMERO_MATRICULA_DETENTOR($val){
		$this->EMP_NUMERO_MATRICULA_DETENTOR =  $val;
	}
	
	function setFLG_ATIVO($val){
		$this->FLG_ATIVO =  $val;
	}
	
	function setQTD_VIDA_UTIL_ESTIMADA($val){
		$this->QTD_VIDA_UTIL_ESTIMADA =  $val;
	}
	
	function setVAL_DEPRECIACAO_ACUMULADA($val){
		$this->VAL_DEPRECIACAO_ACUMULADA =  $val;
	}
	
	function setVAL_AQUISICAO($val){
		$this->VAL_AQUISICAO =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id){
		$sql =  "SELECT * FROM gestaoti.item_configuracao_suporte WHERE SEQ_ITEM_CONFIGURACAO = $id;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = mysql_error();
		$row = mysql_fetch_object($result);
		
		$this->SEQ_ITEM_CONFIGURACAO = $row->SEQ_ITEM_CONFIGURACAO;
		$this->SEQ_SISTEMA_OPERACIONAL = $row->SEQ_SISTEMA_OPERACIONAL;
		$this->SEQ_MARCA_HARDWARE = $row->SEQ_MARCA_HARDWARE;
		$this->SEQ_TIPO_HARDWARE = $row->SEQ_TIPO_HARDWARE;
		$this->NUM_IP = $row->NUM_IP;
		$this->NOM_MODELO = $row->NOM_MODELO;
		$this->DES_LOCALIZACAO = $row->DES_LOCALIZACAO;
		$this->DES_PROCESSADORES = $row->DES_PROCESSADORES;
		$this->DES_OBSERVACAO = $row->DES_OBSERVACAO;
		$this->DAT_AQUISICAO = $row->DAT_AQUISICAO;
		$this->DAT_ATUALIZACAO = $row->DAT_ATUALIZACAO;
		$this->NUM_IDENTIFICADOR = $row->NUM_IDENTIFICADOR;
		$this->NUM_SERIE = $row->NUM_SERIE;
		$this->DES_COR = $row->DES_COR;
		$this->EMP_NUMERO_MATRICULA_DETENTOR = $row->EMP_NUMERO_MATRICULA_DETENTOR;
		$this->FLG_ATIVO = $row->FLG_ATIVO;
		$this->QTD_VIDA_UTIL_ESTIMADA = $row->QTD_VIDA_UTIL_ESTIMADA;
		$this->VAL_DEPRECIACAO_ACUMULADA = $row->VAL_DEPRECIACAO_ACUMULADA;
		$this->VAL_AQUISICAO = $row->VAL_AQUISICAO;
	}
	
	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		
		$sqlSelect = " SELECT SEQ_ITEM_CONFIGURACAO , SEQ_SISTEMA_OPERACIONAL , SEQ_MARCA_HARDWARE , SEQ_TIPO_HARDWARE , NUM_IP , NOM_MODELO , DES_LOCALIZACAO , DES_PROCESSADORES , DES_OBSERVACAO , DAT_AQUISICAO , DAT_ATUALIZACAO , NUM_IDENTIFICADOR , NUM_SERIE , DES_COR , EMP_NUMERO_MATRICULA_DETENTOR , FLG_ATIVO , QTD_VIDA_UTIL_ESTIMADA , VAL_DEPRECIACAO_ACUMULADA , VAL_AQUISICAO ";
		$sqlCorpo  = " FROM gestaoti.item_configuracao_suporte
			      WHERE 1=1 ";
			
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}		
		if($this->SEQ_SISTEMA_OPERACIONAL != ""){
			$sqlCorpo .= "  and SEQ_SISTEMA_OPERACIONAL = $this->SEQ_SISTEMA_OPERACIONAL ";
		}		
		if($this->SEQ_MARCA_HARDWARE != ""){
			$sqlCorpo .= "  and SEQ_MARCA_HARDWARE = $this->SEQ_MARCA_HARDWARE ";
		}		
		if($this->SEQ_TIPO_HARDWARE != ""){
			$sqlCorpo .= "  and SEQ_TIPO_HARDWARE = $this->SEQ_TIPO_HARDWARE ";
		}
		if($this->NUM_IP != ""){
			$sqlCorpo .= "  and upper(NUM_IP) like '%".strtoupper($this->NUM_IP)."%'  ";
		}
		if($this->NOM_MODELO != ""){
			$sqlCorpo .= "  and upper(NOM_MODELO) like '%".strtoupper($this->NOM_MODELO)."%'  ";
		}
		if($this->DES_LOCALIZACAO != ""){
			$sqlCorpo .= "  and upper(DES_LOCALIZACAO) like '%".strtoupper($this->DES_LOCALIZACAO)."%'  ";
		}
		if($this->DES_PROCESSADORES != ""){
			$sqlCorpo .= "  and upper(DES_PROCESSADORES) like '%".strtoupper($this->DES_PROCESSADORES)."%'  ";
		}
		if($this->DES_OBSERVACAO != ""){
			$sqlCorpo .= "  and upper(DES_OBSERVACAO) like '%".strtoupper($this->DES_OBSERVACAO)."%'  ";
		}
		if($this->DAT_AQUISICAO != "" && $this->DAT_AQUISICAO_FINAL == "" ){
			$sqlCorpo .= "  and DAT_AQUISICAO >= '".ConvDataAMD($this->DAT_AQUISICAO)."' ";
		}
		if($this->DAT_AQUISICAO != "" && $this->DAT_AQUISICAO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_AQUISICAO between '".ConvDataAMD($this->DAT_AQUISICAO)."' and '".ConvDataAMD($this->DAT_AQUISICAO_FINAL)."' ";
		}
		if($this->DAT_AQUISICAO == "" && $this->DAT_AQUISICAO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_AQUISICAO <= '".ConvDataAMD($this->DAT_AQUISICAO_FINAL)."' ";
		}
		if($this->DAT_ATUALIZACAO != "" && $this->DAT_ATUALIZACAO_FINAL == "" ){
			$sqlCorpo .= "  and DAT_ATUALIZACAO >= '".ConvDataAMD($this->DAT_ATUALIZACAO)."' ";
		}
		if($this->DAT_ATUALIZACAO != "" && $this->DAT_ATUALIZACAO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ATUALIZACAO between '".ConvDataAMD($this->DAT_ATUALIZACAO)."' and '".ConvDataAMD($this->DAT_ATUALIZACAO_FINAL)."' ";
		}
		if($this->DAT_ATUALIZACAO == "" && $this->DAT_ATUALIZACAO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ATUALIZACAO <= '".ConvDataAMD($this->DAT_ATUALIZACAO_FINAL)."' ";
		}
		if($this->NUM_IDENTIFICADOR != ""){
			$sqlCorpo .= "  and upper(NUM_IDENTIFICADOR) like '%".strtoupper($this->NUM_IDENTIFICADOR)."%'  ";
		}
		if($this->NUM_SERIE != ""){
			$sqlCorpo .= "  and upper(NUM_SERIE) like '%".strtoupper($this->NUM_SERIE)."%'  ";
		}
		if($this->DES_COR != ""){
			$sqlCorpo .= "  and upper(DES_COR) like '%".strtoupper($this->DES_COR)."%'  ";
		}		
		if($this->EMP_NUMERO_MATRICULA_DETENTOR != ""){
			$sqlCorpo .= "  and EMP_NUMERO_MATRICULA_DETENTOR = $this->EMP_NUMERO_MATRICULA_DETENTOR ";
		}
		if($this->FLG_ATIVO != ""){
			$sqlCorpo .= "  and FLG_ATIVO = '$this->FLG_ATIVO' ";
		}		
		if($this->QTD_VIDA_UTIL_ESTIMADA != ""){
			$sqlCorpo .= "  and QTD_VIDA_UTIL_ESTIMADA = $this->QTD_VIDA_UTIL_ESTIMADA ";
		}		
		if($this->VAL_DEPRECIACAO_ACUMULADA != ""){
			$sqlCorpo .= "  and VAL_DEPRECIACAO_ACUMULADA = $this->VAL_DEPRECIACAO_ACUMULADA ";
		}		
		if($this->VAL_AQUISICAO != ""){
			$sqlCorpo .= "  and VAL_AQUISICAO = $this->VAL_AQUISICAO ";
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
		$sql = "DELETE FROM gestaoti.item_configuracao_suporte WHERE SEQ_ITEM_CONFIGURACAO = $id;";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert(){
		$sql = "INSERT INTO gestaoti.item_configuracao_suporte ( SEQ_ITEM_CONFIGURACAO, SEQ_SISTEMA_OPERACIONAL,SEQ_MARCA_HARDWARE,SEQ_TIPO_HARDWARE,NUM_IP,NOM_MODELO,DES_LOCALIZACAO,DES_PROCESSADORES,DES_OBSERVACAO,DAT_AQUISICAO,DAT_ATUALIZACAO,NUM_IDENTIFICADOR,NUM_SERIE,DES_COR,EMP_NUMERO_MATRICULA_DETENTOR,FLG_ATIVO,QTD_VIDA_UTIL_ESTIMADA,VAL_DEPRECIACAO_ACUMULADA,VAL_AQUISICAO ) 
				VALUES ( ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",".$this->iif($this->SEQ_SISTEMA_OPERACIONAL=="", "NULL", "'".$this->SEQ_SISTEMA_OPERACIONAL."'").",".$this->iif($this->SEQ_MARCA_HARDWARE=="", "NULL", "'".$this->SEQ_MARCA_HARDWARE."'").",".$this->iif($this->SEQ_TIPO_HARDWARE=="", "NULL", "'".$this->SEQ_TIPO_HARDWARE."'").",".$this->iif($this->NUM_IP=="", "NULL", "'".$this->NUM_IP."'").",".$this->iif($this->NOM_MODELO=="", "NULL", "'".$this->NOM_MODELO."'").",".$this->iif($this->DES_LOCALIZACAO=="", "NULL", "'".$this->DES_LOCALIZACAO."'").",".$this->iif($this->DES_PROCESSADORES=="", "NULL", "'".$this->DES_PROCESSADORES."'").",".$this->iif($this->DES_OBSERVACAO=="", "NULL", "'".$this->DES_OBSERVACAO."'").",".$this->iif($this->DAT_AQUISICAO=="", "NULL", "'".$this->DAT_AQUISICAO."'").",".$this->iif($this->DAT_ATUALIZACAO=="", "NULL", "'".$this->DAT_ATUALIZACAO."'").",".$this->iif($this->NUM_IDENTIFICADOR=="", "NULL", "'".$this->NUM_IDENTIFICADOR."'").",".$this->iif($this->NUM_SERIE=="", "NULL", "'".$this->NUM_SERIE."'").",".$this->iif($this->DES_COR=="", "NULL", "'".$this->DES_COR."'").",".$this->iif($this->EMP_NUMERO_MATRICULA_DETENTOR=="", "NULL", "'".$this->EMP_NUMERO_MATRICULA_DETENTOR."'").",".$this->iif($this->FLG_ATIVO=="", "NULL", "'".$this->FLG_ATIVO."'").",".$this->iif($this->QTD_VIDA_UTIL_ESTIMADA=="", "NULL", "'".$this->QTD_VIDA_UTIL_ESTIMADA."'").",".$this->iif($this->VAL_DEPRECIACAO_ACUMULADA=="", "NULL", "'".$this->VAL_DEPRECIACAO_ACUMULADA."'").",".$this->iif($this->VAL_AQUISICAO=="", "NULL", "'".$this->VAL_AQUISICAO."'")." )";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id){
		$sql = " UPDATE gestaoti.item_configuracao_suporte SET  SEQ_SISTEMA_OPERACIONAL = ".$this->iif($this->SEQ_SISTEMA_OPERACIONAL=="", "NULL", "'".$this->SEQ_SISTEMA_OPERACIONAL."'").",SEQ_MARCA_HARDWARE = ".$this->iif($this->SEQ_MARCA_HARDWARE=="", "NULL", "'".$this->SEQ_MARCA_HARDWARE."'").",SEQ_TIPO_HARDWARE = ".$this->iif($this->SEQ_TIPO_HARDWARE=="", "NULL", "'".$this->SEQ_TIPO_HARDWARE."'").",NUM_IP = ".$this->iif($this->NUM_IP=="", "NULL", "'".$this->NUM_IP."'").",NOM_MODELO = ".$this->iif($this->NOM_MODELO=="", "NULL", "'".$this->NOM_MODELO."'").",DES_LOCALIZACAO = ".$this->iif($this->DES_LOCALIZACAO=="", "NULL", "'".$this->DES_LOCALIZACAO."'").",DES_PROCESSADORES = ".$this->iif($this->DES_PROCESSADORES=="", "NULL", "'".$this->DES_PROCESSADORES."'").",DES_OBSERVACAO = ".$this->iif($this->DES_OBSERVACAO=="", "NULL", "'".$this->DES_OBSERVACAO."'").",DAT_AQUISICAO = ".$this->iif($this->DAT_AQUISICAO=="", "NULL", "'".$this->DAT_AQUISICAO."'").",DAT_ATUALIZACAO = ".$this->iif($this->DAT_ATUALIZACAO=="", "NULL", "'".$this->DAT_ATUALIZACAO."'").",NUM_IDENTIFICADOR = ".$this->iif($this->NUM_IDENTIFICADOR=="", "NULL", "'".$this->NUM_IDENTIFICADOR."'").",NUM_SERIE = ".$this->iif($this->NUM_SERIE=="", "NULL", "'".$this->NUM_SERIE."'").",DES_COR = ".$this->iif($this->DES_COR=="", "NULL", "'".$this->DES_COR."'").",EMP_NUMERO_MATRICULA_DETENTOR = ".$this->iif($this->EMP_NUMERO_MATRICULA_DETENTOR=="", "NULL", "'".$this->EMP_NUMERO_MATRICULA_DETENTOR."'").",FLG_ATIVO = ".$this->iif($this->FLG_ATIVO=="", "NULL", "'".$this->FLG_ATIVO."'").",QTD_VIDA_UTIL_ESTIMADA = ".$this->iif($this->QTD_VIDA_UTIL_ESTIMADA=="", "NULL", "'".$this->QTD_VIDA_UTIL_ESTIMADA."'").",VAL_DEPRECIACAO_ACUMULADA = ".$this->iif($this->VAL_DEPRECIACAO_ACUMULADA=="", "NULL", "'".$this->VAL_DEPRECIACAO_ACUMULADA."'").",VAL_AQUISICAO = ".$this->iif($this->VAL_AQUISICAO=="", "NULL", "'".$this->VAL_AQUISICAO."'")." WHERE SEQ_ITEM_CONFIGURACAO = $id ";
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