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
* CLASSNAME:        tipo_hardware
* -------------------------------------------------------
*
*/
include_once("include/PHP/class/class.database.php");

// **********************
// CLASS DECLARATION
// **********************

class tipo_hardware{ 
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_TIPO_HARDWARE;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina
	
	var $NOM_TIPO_HARDWARE;   // (normal Attribute)
	
	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function tipo_hardware(){
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
	
	
	function getSEQ_TIPO_HARDWARE(){
		return $this->SEQ_TIPO_HARDWARE;
	}
	
	function getNOM_TIPO_HARDWARE(){
		return $this->NOM_TIPO_HARDWARE;
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
	
	
	function setSEQ_TIPO_HARDWARE($val){
		$this->SEQ_TIPO_HARDWARE =  $val;
	}
	
	function setNOM_TIPO_HARDWARE($val){
		$this->NOM_TIPO_HARDWARE =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id){
		$sql =  "SELECT * FROM gestaoti.tipo_hardware WHERE SEQ_TIPO_HARDWARE = $id;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = mysql_error();
		$row = mysql_fetch_object($result);
		
		$this->SEQ_TIPO_HARDWARE = $row->SEQ_TIPO_HARDWARE;
		$this->NOM_TIPO_HARDWARE = $row->NOM_TIPO_HARDWARE;
	}
	
	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		
		$sqlSelect = " SELECT SEQ_TIPO_HARDWARE , NOM_TIPO_HARDWARE ";
		$sqlCorpo  = " FROM gestaoti.tipo_hardware
			      WHERE 1=1 ";
			
		if($this->SEQ_TIPO_HARDWARE != ""){
			$sqlCorpo .= "  and SEQ_TIPO_HARDWARE = $this->SEQ_TIPO_HARDWARE ";
		}
		if($this->NOM_TIPO_HARDWARE != ""){
			$sqlCorpo .= "  and upper(NOM_TIPO_HARDWARE) like '%".strtoupper($this->NOM_TIPO_HARDWARE)."%'  ";
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
		$sql = "DELETE FROM gestaoti.tipo_hardware WHERE SEQ_TIPO_HARDWARE = $id;";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert(){
		$this->SEQ_TIPO_HARDWARE = ""; // clear key for autoincrement
		
		$sql = "INSERT INTO gestaoti.tipo_hardware ( NOM_TIPO_HARDWARE ) VALUES ( ".$this->iif($this->NOM_TIPO_HARDWARE=="", "NULL", "'".$this->NOM_TIPO_HARDWARE."'")." )";
		$result = $this->database->query($sql);
		$this->SEQ_TIPO_HARDWARE = mysql_insert_id($this->database->link);
		if(!$result) $this->error = mysql_error();
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id){
		$sql = " UPDATE gestaoti.tipo_hardware SET  NOM_TIPO_HARDWARE = ".$this->iif($this->NOM_TIPO_HARDWARE=="", "NULL", "'".$this->NOM_TIPO_HARDWARE."'")." WHERE SEQ_TIPO_HARDWARE = $id ";
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