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
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina
	
	var $NOM_TIPO_SERVICO;   // (normal Attribute)
	
	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados
	
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
	// SELECT METHOD COM PAR�METROS
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