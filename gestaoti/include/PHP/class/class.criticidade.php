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
* CLASSNAME:        criticidade
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class criticidade{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_CRITICIDADE;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_CRITICIDADE;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function criticidade(){
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
	function getSEQ_CRITICIDADE(){
		return $this->SEQ_CRITICIDADE;
	}

	function getNOM_CRITICIDADE(){
		return $this->NOM_CRITICIDADE;
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
	function setSEQ_CRITICIDADE($val){
		$this->SEQ_CRITICIDADE =  $val;
	}

	function setNOM_CRITICIDADE($val){
		$this->NOM_CRITICIDADE =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.criticidade WHERE SEQ_CRITICIDADE = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result);
		$this->SEQ_CRITICIDADE = $row->seq_criticidade;
		$this->NOM_CRITICIDADE = $row->nom_criticidade;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_CRITICIDADE , NOM_CRITICIDADE ";
		$sqlCorpo  = "FROM gestaoti.criticidade
						WHERE 1=1 ";

		if($this->SEQ_CRITICIDADE != ""){
			$sqlCorpo .= "  and SEQ_CRITICIDADE = $this->SEQ_CRITICIDADE ";
		}
		if($this->NOM_CRITICIDADE != ""){
			$sqlCorpo .= "  and upper(NOM_CRITICIDADE) like '%".strtoupper($this->NOM_CRITICIDADE)."%'  ";
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
		$sql = "DELETE FROM gestaoti.criticidade WHERE SEQ_CRITICIDADE = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_CRITICIDADE = $this->database->GetSequenceValue("gestaoti.SEQ_CRITICIDADE"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.criticidade (SEQ_CRITICIDADE, NOM_CRITICIDADE )
				VALUES (".$this->SEQ_CRITICIDADE.",
						".$this->database->iif($this->NOM_CRITICIDADE=="", "NULL", "'".$this->NOM_CRITICIDADE."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.criticidade
				 SET  NOM_CRITICIDADE = ".$this->database->iif($this->NOM_CRITICIDADE=="", "NULL", "'".$this->NOM_CRITICIDADE."'")."
				 WHERE SEQ_CRITICIDADE = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>