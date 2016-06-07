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
* CLASSNAME:        menu_perfil_acesso
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class janela_mudanca_servidor{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina
	
	var $seq_janela_mudanca;   // (normal Attribute) seq_janela_mudanca
	var $seq_servidor;   // (normal Attribute) seq_servidor

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function janela_mudanca_servidor(){
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
	 
	function getSeq_janela_mudanca(){
		return $this->seq_janela_mudanca;
	}

	function getSeq_servidor(){
		return $this->seq_servidor;
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
	function setSeq_janela_mudanca($val){
		$this->seq_janela_mudanca =  $val;
	}

	function setSeq_servidor($val){
		$this->seq_servidor =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	 

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		//$this->setvQtdRegistros($vQtdRegistros);
		  
		$sqlSelect = "SELECT seq_janela_mudanca, seq_servidor ";
		$sqlCorpo  = "FROM gestaoti.janela_mudanca_servidor WHERE 1=1 ";

		if($this->seq_janela_mudanca != ""){
			$sqlCorpo .= "  and seq_janela_mudanca = $this->seq_janela_mudanca ";
		}
		if($this->seq_servidor != ""){
			$sqlCorpo .= "  and seq_servidor = $this->seq_servidor ";
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
	function deleteBySeq_janela_mudanca($id){
		$sql = "DELETE FROM gestaoti.janela_mudanca_servidor WHERE seq_janela_mudanca = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.janela_mudanca_servidor( seq_janela_mudanca, seq_servidor)
				VALUES (".$this->database->iif($this->seq_janela_mudanca=="", "NULL", "'".$this->seq_janela_mudanca."'").", ".$this->database->iif($this->seq_servidor=="", "NULL", "'".$this->seq_servidor."'")." )";
		$result = $this->database->query($sql);
	}

 
} // class : end
?>