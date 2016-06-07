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
* CLASSNAME:        marca_hardware
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class marca_hardware{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_MARCA_HARDWARE;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NOM_MARCA_HARDWARE;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function marca_hardware(){
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
	function getSEQ_MARCA_HARDWARE(){
		return $this->SEQ_MARCA_HARDWARE;
	}

	function getNOM_MARCA_HARDWARE(){
		return $this->NOM_MARCA_HARDWARE;
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
	function setSEQ_MARCA_HARDWARE($val){
		$this->SEQ_MARCA_HARDWARE =  $val;
	}

	function setNOM_MARCA_HARDWARE($val){
		$this->NOM_MARCA_HARDWARE =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.marca_hardware WHERE SEQ_MARCA_HARDWARE = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_MARCA_HARDWARE = $row->seq_marca_hardware;
		$this->NOM_MARCA_HARDWARE = $row->nom_marca_hardware;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT SEQ_MARCA_HARDWARE , NOM_MARCA_HARDWARE ";
		$sqlCorpo  = " FROM gestaoti.marca_hardware
					   WHERE 1=1 ";

		if($this->SEQ_MARCA_HARDWARE != ""){
			$sqlCorpo .= "  and SEQ_MARCA_HARDWARE = $this->SEQ_MARCA_HARDWARE ";
		}
		if($this->NOM_MARCA_HARDWARE != ""){
			$sqlCorpo .= "  and upper(NOM_MARCA_HARDWARE) like '%".strtoupper($this->NOM_MARCA_HARDWARE)."%'  ";
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
		$sql = "DELETE FROM gestaoti.marca_hardware WHERE SEQ_MARCA_HARDWARE = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_MARCA_HARDWARE = $this->database->GetSequenceValue("gestaoti.SEQ_MARCA_HARDWARE"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.marca_hardware (SEQ_MARCA_HARDWARE, NOM_MARCA_HARDWARE )
				VALUES (".$this->SEQ_MARCA_HARDWARE.",
						".$this->database->iif($this->NOM_MARCA_HARDWARE=="", "NULL", "'".$this->NOM_MARCA_HARDWARE."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.marca_hardware
				 SET  NOM_MARCA_HARDWARE = ".$this->database->iif($this->NOM_MARCA_HARDWARE=="", "NULL", "'".$this->NOM_MARCA_HARDWARE."'")."
				 WHERE SEQ_MARCA_HARDWARE = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>