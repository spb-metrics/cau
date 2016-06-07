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
* CLASSNAME:        tipo_servico
* -------------------------------------------------------
*
*/
include_once("include/PHP/class/class.database.php");

// **********************
// CLASS DECLARATION
// **********************

class tipo_servico{ 
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_TIPO_SERVICO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página
	
	var $NOM_TIPO_SERVICO;   // (normal Attribute)
	
	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function tipo_servico(){
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
	
	
	function getSEQ_TIPO_SERVICO(){
		return $this->SEQ_TIPO_SERVICO;
	}
	
	function getNOM_TIPO_SERVICO(){
		return $this->NOM_TIPO_SERVICO;
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
	
	
	function setSEQ_TIPO_SERVICO($val){
		$this->SEQ_TIPO_SERVICO =  $val;
	}
	
	function setNOM_TIPO_SERVICO($val){
		$this->NOM_TIPO_SERVICO =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id){
		$sql =  "SELECT * FROM gestaoti.tipo_servico WHERE SEQ_TIPO_SERVICO = $id;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = mysql_error();
		$row = mysql_fetch_object($result);
		
		$this->SEQ_TIPO_SERVICO = $row->SEQ_TIPO_SERVICO;
		$this->NOM_TIPO_SERVICO = $row->NOM_TIPO_SERVICO;
	}
	
	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		
		$sqlSelect = " SELECT SEQ_TIPO_SERVICO , NOM_TIPO_SERVICO ";
		$sqlCorpo  = " FROM gestaoti.tipo_servico
			      WHERE 1=1 ";
			
		if($this->SEQ_TIPO_SERVICO != ""){
			$sqlCorpo .= "  and SEQ_TIPO_SERVICO = $this->SEQ_TIPO_SERVICO ";
		}
		if($this->NOM_TIPO_SERVICO != ""){
			$sqlCorpo .= "  and upper(NOM_TIPO_SERVICO) like '%".strtoupper($this->NOM_TIPO_SERVICO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.tipo_servico WHERE SEQ_TIPO_SERVICO = $id;";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert(){
		
		$this->SEQ_TIPO_SERVICO = $this->database->GetSequenceValue("gestaoti.SEQ_TIPO_SERVICO");
		//$this->SEQ_TIPO_SERVICO = ""; // clear key for autoincrement
		
		$sql = "INSERT INTO gestaoti.tipo_servico ( NOM_TIPO_SERVICO ) VALUES ( ".$this->iif($this->NOM_TIPO_SERVICO=="", "NULL", "'".$this->NOM_TIPO_SERVICO."'")." )";
		$result = $this->database->query($sql);
		$this->SEQ_TIPO_SERVICO = mysql_insert_id($this->database->link);
		if(!$result) $this->error = mysql_error();
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id){
		$sql = " UPDATE gestaoti.tipo_servico SET  NOM_TIPO_SERVICO = ".$this->iif($this->NOM_TIPO_SERVICO=="", "NULL", "'".$this->NOM_TIPO_SERVICO."'")." WHERE SEQ_TIPO_SERVICO = $id ";
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