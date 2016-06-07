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
* CLASSNAME:        sistema_operacional
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class sistema_operacional{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_SISTEMA_OPERACIONAL;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_SISTEMA_OPERACIONAL;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function sistema_operacional(){
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
	function getSEQ_SISTEMA_OPERACIONAL(){
		return $this->SEQ_SISTEMA_OPERACIONAL;
	}

	function getNOM_SISTEMA_OPERACIONAL(){
		return $this->NOM_SISTEMA_OPERACIONAL;
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
	function setSEQ_SISTEMA_OPERACIONAL($val){
		$this->SEQ_SISTEMA_OPERACIONAL =  $val;
	}

	function setNOM_SISTEMA_OPERACIONAL($val){
		$this->NOM_SISTEMA_OPERACIONAL =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.sistema_operacional WHERE SEQ_SISTEMA_OPERACIONAL = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_SISTEMA_OPERACIONAL = $row->seq_sistema_operacional;
		$this->NOM_SISTEMA_OPERACIONAL = $row->nom_sistema_operacional;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_SISTEMA_OPERACIONAL , NOM_SISTEMA_OPERACIONAL ";
		$sqlCorpo  = "FROM gestaoti.sistema_operacional
						WHERE 1=1 ";

		if($this->SEQ_SISTEMA_OPERACIONAL != ""){
			$sqlCorpo .= "  and SEQ_SISTEMA_OPERACIONAL = $this->SEQ_SISTEMA_OPERACIONAL ";
		}
		if($this->NOM_SISTEMA_OPERACIONAL != ""){
			$sqlCorpo .= "  and upper(NOM_SISTEMA_OPERACIONAL) like '%".strtoupper($this->NOM_SISTEMA_OPERACIONAL)."%'  ";
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
		$sql = "DELETE FROM gestaoti.sistema_operacional WHERE SEQ_SISTEMA_OPERACIONAL = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_SISTEMA_OPERACIONAL = $this->database->GetSequenceValue("gestaoti.SEQ_SISTEMA_OPERACIONAL"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.sistema_operacional (SEQ_SISTEMA_OPERACIONAL,
												 NOM_SISTEMA_OPERACIONAL )
				VALUES (".$this->SEQ_SISTEMA_OPERACIONAL.",
						".$this->database->iif($this->NOM_SISTEMA_OPERACIONAL=="", "NULL", "'".$this->NOM_SISTEMA_OPERACIONAL."'")."
				)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.sistema_operacional
				 SET  NOM_SISTEMA_OPERACIONAL = ".$this->database->iif($this->NOM_SISTEMA_OPERACIONAL=="", "NULL", "'".$this->NOM_SISTEMA_OPERACIONAL."'")."
				 WHERE SEQ_SISTEMA_OPERACIONAL = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>