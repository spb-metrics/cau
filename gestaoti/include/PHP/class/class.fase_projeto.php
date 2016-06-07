<?php
/*
Copyright 2011 da EMBRATUR
 Este arquivo é parte do programa CAU - Central de Atendimento ao Usuário
 O CAU é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela 
 Fundação do Software Livre (FSF); na versão 2 da Licença.
 Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
 Observe no diretório gestaoti/install/ a cópia da Licença Pública Geral GNU, sob o título "licensa_uso.htm". 
 Se preferir acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software 
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
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
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NOM_FASE_PROJETO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

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
	// SELECT METHOD COM PARÂMETROS
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