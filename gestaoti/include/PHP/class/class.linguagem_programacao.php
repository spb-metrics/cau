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
* CLASSNAME:        linguagem_programacao
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class linguagem_programacao{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_LINGUAGEM_PROGRAMACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_LINGUAGEM_PROGRAMACAO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function linguagem_programacao(){
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
	function getSEQ_LINGUAGEM_PROGRAMACAO(){
		return $this->SEQ_LINGUAGEM_PROGRAMACAO;
	}

	function getNOM_LINGUAGEM_PROGRAMACAO(){
		return $this->NOM_LINGUAGEM_PROGRAMACAO;
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
	function setSEQ_LINGUAGEM_PROGRAMACAO($val){
		$this->SEQ_LINGUAGEM_PROGRAMACAO =  $val;
	}

	function setNOM_LINGUAGEM_PROGRAMACAO($val){
		$this->NOM_LINGUAGEM_PROGRAMACAO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		if($id != ""){
			$sql =  "SELECT * FROM gestaoti.linguagem_programacao WHERE SEQ_LINGUAGEM_PROGRAMACAO = $id";
			$result =  $this->database->query($sql);
			$result = $this->database->result;

			$row = pg_fetch_object($result, 0);
			$this->SEQ_LINGUAGEM_PROGRAMACAO = $row->seq_linguagem_programacao;
			$this->NOM_LINGUAGEM_PROGRAMACAO = $row->nom_linguagem_programacao;
		}
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT SEQ_LINGUAGEM_PROGRAMACAO , NOM_LINGUAGEM_PROGRAMACAO ";
		$sqlCorpo  = " FROM gestaoti.linguagem_programacao
					   WHERE 1=1 ";
		if($this->SEQ_LINGUAGEM_PROGRAMACAO != ""){
			$sqlCorpo .= "  and SEQ_LINGUAGEM_PROGRAMACAO = $this->SEQ_LINGUAGEM_PROGRAMACAO ";
		}
		if($this->NOM_LINGUAGEM_PROGRAMACAO != ""){
			$sqlCorpo .= "  and upper(NOM_LINGUAGEM_PROGRAMACAO) like '%".strtoupper($this->NOM_LINGUAGEM_PROGRAMACAO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.linguagem_programacao WHERE SEQ_LINGUAGEM_PROGRAMACAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_LINGUAGEM_PROGRAMACAO = $this->database->GetSequenceValue("gestaoti.SEQ_LINGUAGEM_PROGRAMACAO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.linguagem_programacao ( SEQ_LINGUAGEM_PROGRAMACAO, NOM_LINGUAGEM_PROGRAMACAO )
				VALUES (".$this->SEQ_LINGUAGEM_PROGRAMACAO.",
						".$this->database->iif($this->NOM_LINGUAGEM_PROGRAMACAO=="", "NULL", "'".$this->NOM_LINGUAGEM_PROGRAMACAO."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.linguagem_programacao
				 SET  NOM_LINGUAGEM_PROGRAMACAO = ".$this->database->iif($this->NOM_LINGUAGEM_PROGRAMACAO=="", "NULL", "'".$this->NOM_LINGUAGEM_PROGRAMACAO."'")."
				 WHERE SEQ_LINGUAGEM_PROGRAMACAO = $id ";
		$result = $this->database->query($sql);
	}

} // class : end
?>