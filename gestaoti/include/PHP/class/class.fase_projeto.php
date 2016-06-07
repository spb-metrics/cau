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
* CLASSNAME:        FASE_PROJETO
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class FASE_PROJETO{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_FASE_PROJETO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_FASE_PROJETO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function FASE_PROJETO(){
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
	function getSEQ_FASE_PROJETO(){
		return $this->SEQ_FASE_PROJETO;
	}

	function getNOM_FASE_PROJETO(){
		return $this->NOM_FASE_PROJETO;
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
	function setSEQ_FASE_PROJETO($val){
		$this->SEQ_FASE_PROJETO =  $val;
	}

	function setNOM_FASE_PROJETO($val){
		$this->NOM_FASE_PROJETO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.FASE_PROJETO WHERE SEQ_FASE_PROJETO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_FASE_PROJETO = $row->seq_fase_projeto;
		$this->NOM_FASE_PROJETO = $row->nom_fase_projeto;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_FASE_PROJETO , NOM_FASE_PROJETO ";
		$sqlCorpo  = "FROM gestaoti.FASE_PROJETO
						WHERE 1=1 ";

		if($this->SEQ_FASE_PROJETO != ""){
			$sqlCorpo .= "  and SEQ_FASE_PROJETO = $this->SEQ_FASE_PROJETO ";
		}
		if($this->NOM_FASE_PROJETO != ""){
			$sqlCorpo .= "  and upper(NOM_FASE_PROJETO) like '%".strtoupper($this->NOM_FASE_PROJETO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.FASE_PROJETO WHERE SEQ_FASE_PROJETO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_FASE_PROJETO = $this->database->GetSequenceValue("gestaoti.SEQ_FASE_PROJETO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.FASE_PROJETO (SEQ_FASE_PROJETO, NOM_FASE_PROJETO )
				VALUES (".$this->SEQ_FASE_PROJETO.",
					    ".$this->database->iif($this->NOM_FASE_PROJETO=="", "NULL", "'".$this->NOM_FASE_PROJETO."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.FASE_PROJETO
				 SET  NOM_FASE_PROJETO = ".$this->database->iif($this->NOM_FASE_PROJETO=="", "NULL", "'".$this->NOM_FASE_PROJETO."'")."
				 WHERE SEQ_FASE_PROJETO = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>