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
* CLASSNAME:        frequencia_manutencao
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class frequencia_manutencao{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_FREQUENCIA_MANUTENCAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_FREQUENCIA_MANUTENCAO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function frequencia_manutencao(){
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
	function getSEQ_FREQUENCIA_MANUTENCAO(){
		return $this->SEQ_FREQUENCIA_MANUTENCAO;
	}

	function getNOM_FREQUENCIA_MANUTENCAO(){
		return $this->NOM_FREQUENCIA_MANUTENCAO;
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
	function setSEQ_FREQUENCIA_MANUTENCAO($val){
		$this->SEQ_FREQUENCIA_MANUTENCAO =  $val;
	}

	function setNOM_FREQUENCIA_MANUTENCAO($val){
		$this->NOM_FREQUENCIA_MANUTENCAO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.frequencia_manutencao WHERE SEQ_FREQUENCIA_MANUTENCAO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_FREQUENCIA_MANUTENCAO = $row->seq_frequencia_manutencao;
		$this->NOM_FREQUENCIA_MANUTENCAO = $row->nom_frequencia_manutencao;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);


		$sqlSelect = "SELECT SEQ_FREQUENCIA_MANUTENCAO , NOM_FREQUENCIA_MANUTENCAO ";
		$sqlCorpo  = "FROM gestaoti.frequencia_manutencao
						WHERE 1=1 ";

		if($this->SEQ_FREQUENCIA_MANUTENCAO != ""){
			$sqlCorpo .= "  and SEQ_FREQUENCIA_MANUTENCAO = $this->SEQ_FREQUENCIA_MANUTENCAO ";
		}
		if($this->NOM_FREQUENCIA_MANUTENCAO != ""){
			$sqlCorpo .= "  and upper(NOM_FREQUENCIA_MANUTENCAO) like '%".strtoupper($this->NOM_FREQUENCIA_MANUTENCAO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.frequencia_manutencao WHERE SEQ_FREQUENCIA_MANUTENCAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_FREQUENCIA_MANUTENCAO = $this->database->GetSequenceValue("gestaoti.SEQ_FREQUENCIA_MANUTENCAO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.frequencia_manutencao (SEQ_FREQUENCIA_MANUTENCAO, NOM_FREQUENCIA_MANUTENCAO )
				VALUES (".$this->SEQ_FREQUENCIA_MANUTENCAO.",
						".$this->database->iif($this->NOM_FREQUENCIA_MANUTENCAO=="", "NULL", "'".$this->NOM_FREQUENCIA_MANUTENCAO."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.frequencia_manutencao SET  NOM_FREQUENCIA_MANUTENCAO = ".$this->database->iif($this->NOM_FREQUENCIA_MANUTENCAO=="", "NULL", "'".$this->NOM_FREQUENCIA_MANUTENCAO."'")." WHERE SEQ_FREQUENCIA_MANUTENCAO = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>