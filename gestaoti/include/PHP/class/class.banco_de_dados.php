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
* CLASSNAME:        banco_de_dados
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class banco_de_dados{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_BANCO_DE_DADOS;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_BANCO_DE_DADOS;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function banco_de_dados(){
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
	function getSEQ_BANCO_DE_DADOS(){
		return $this->SEQ_BANCO_DE_DADOS;
	}

	function getNOM_BANCO_DE_DADOS(){
		return $this->NOM_BANCO_DE_DADOS;
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
	function setSEQ_BANCO_DE_DADOS($val){
		$this->SEQ_BANCO_DE_DADOS =  $val;
	}

	function setNOM_BANCO_DE_DADOS($val){
		$this->NOM_BANCO_DE_DADOS =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		if($id != ""){
			$sql =  "SELECT * FROM gestaoti.banco_de_dados WHERE SEQ_BANCO_DE_DADOS = $id";
			$result =  $this->database->query($sql);
			$result = $this->database->result;

			$row = pg_fetch_object($result, 0);
			$this->SEQ_BANCO_DE_DADOS = $row->seq_banco_de_dados;
			$this->NOM_BANCO_DE_DADOS = $row->nom_banco_de_dados;
		}
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_BANCO_DE_DADOS , NOM_BANCO_DE_DADOS ";
		$sqlCorpo  = "FROM gestaoti.banco_de_dados
						WHERE 1=1 ";

		if($this->SEQ_BANCO_DE_DADOS != ""){
			$sqlCorpo .= "  and SEQ_BANCO_DE_DADOS = $this->SEQ_BANCO_DE_DADOS ";
		}
		if($this->NOM_BANCO_DE_DADOS != ""){
			$sqlCorpo .= "  and upper(NOM_BANCO_DE_DADOS) like '%".strtoupper($this->NOM_BANCO_DE_DADOS)."%'  ";
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
		$sql = "DELETE FROM gestaoti.banco_de_dados WHERE SEQ_BANCO_DE_DADOS = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_BANCO_DE_DADOS = $this->database->GetSequenceValue("gestaoti.SEQ_BANCO_DE_DADOS"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.banco_de_dados (SEQ_BANCO_DE_DADOS, NOM_BANCO_DE_DADOS )
				VALUES (".$this->SEQ_BANCO_DE_DADOS.",
					    ".$this->database->iif($this->NOM_BANCO_DE_DADOS=="", "NULL", "'".$this->NOM_BANCO_DE_DADOS."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.banco_de_dados
				 SET  NOM_BANCO_DE_DADOS = ".$this->database->iif($this->NOM_BANCO_DE_DADOS=="", "NULL", "'".$this->NOM_BANCO_DE_DADOS."'")."
				 WHERE SEQ_BANCO_DE_DADOS = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>