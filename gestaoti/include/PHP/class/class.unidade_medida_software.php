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
* CLASSNAME:        unidade_medida_software
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class unidade_medida_software{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_UNIDADE_MEDIDA_SOFTWARE;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_UNIDADE_MEDIDA_SOFTWARE;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function unidade_medida_software(){
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
	function getSEQ_UNIDADE_MEDIDA_SOFTWARE(){
		return $this->SEQ_UNIDADE_MEDIDA_SOFTWARE;
	}

	function getNOM_UNIDADE_MEDIDA_SOFTWARE(){
		return $this->NOM_UNIDADE_MEDIDA_SOFTWARE;
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
	function setSEQ_UNIDADE_MEDIDA_SOFTWARE($val){
		$this->SEQ_UNIDADE_MEDIDA_SOFTWARE =  $val;
	}

	function setNOM_UNIDADE_MEDIDA_SOFTWARE($val){
		$this->NOM_UNIDADE_MEDIDA_SOFTWARE =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.unidade_medida_software WHERE SEQ_UNIDADE_MEDIDA_SOFTWARE = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_UNIDADE_MEDIDA_SOFTWARE = $row->seq_unidade_medida_software;
		$this->NOM_UNIDADE_MEDIDA_SOFTWARE = $row->nom_unidade_medida_software;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_UNIDADE_MEDIDA_SOFTWARE , NOM_UNIDADE_MEDIDA_SOFTWARE ";
		$sqlCorpo  = "FROM gestaoti.unidade_medida_software
						WHERE 1=1 ";

		if($this->SEQ_UNIDADE_MEDIDA_SOFTWARE != ""){
			$sqlCorpo .= "  and SEQ_UNIDADE_MEDIDA_SOFTWARE = $this->SEQ_UNIDADE_MEDIDA_SOFTWARE ";
		}
		if($this->NOM_UNIDADE_MEDIDA_SOFTWARE != ""){
			$sqlCorpo .= "  and upper(NOM_UNIDADE_MEDIDA_SOFTWARE) like '%".strtoupper($this->NOM_UNIDADE_MEDIDA_SOFTWARE)."%'  ";
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
		$sql = "DELETE FROM gestaoti.unidade_medida_software WHERE SEQ_UNIDADE_MEDIDA_SOFTWARE = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_UNIDADE_MEDIDA_SOFTWARE = $this->database->GetSequenceValue("gestaoti.SEQ_UNIDADE_MEDIDA_SOFTWARE"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.unidade_medida_software (SEQ_UNIDADE_MEDIDA_SOFTWARE, NOM_UNIDADE_MEDIDA_SOFTWARE)
				VALUES (".$this->SEQ_UNIDADE_MEDIDA_SOFTWARE.",
						".$this->database->iif($this->NOM_UNIDADE_MEDIDA_SOFTWARE=="", "NULL", "'".$this->NOM_UNIDADE_MEDIDA_SOFTWARE."'")."
				)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.unidade_medida_software SET  NOM_UNIDADE_MEDIDA_SOFTWARE = ".$this->database->iif($this->NOM_UNIDADE_MEDIDA_SOFTWARE=="", "NULL", "'".$this->NOM_UNIDADE_MEDIDA_SOFTWARE."'")." WHERE SEQ_UNIDADE_MEDIDA_SOFTWARE = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>