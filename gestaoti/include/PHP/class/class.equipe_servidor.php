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
* CLASSNAME:        EQUIPE_SERVIDOR
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class EQUIPE_SERVIDOR{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_SERVIDOR;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NUM_MATRICULA_RECURSO;   // (normal Attribute)
	var $NUM_ORDEM;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function EQUIPE_SERVIDOR(){
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
	function getSEQ_SERVIDOR(){
		return $this->SEQ_SERVIDOR;
	}

	function getNUM_MATRICULA_RECURSO(){
		return $this->NUM_MATRICULA_RECURSO;
	}

	function getNUM_ORDEM(){
		return $this->NUM_ORDEM;
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
	function setSEQ_SERVIDOR($val){
		$this->SEQ_SERVIDOR =  $val;
	}

	function setNUM_MATRICULA_RECURSO($val){
		$this->NUM_MATRICULA_RECURSO =  $val;
	}

	function setNUM_ORDEM($val){
		$this->NUM_ORDEM =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.EQUIPE_SERVIDOR WHERE SEQ_SERVIDOR = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_SERVIDOR = $row->seq_servidor;
		$this->NUM_MATRICULA_RECURSO = $row->num_matricula_recurso;
		$this->NUM_ORDEM = $row->num_ordem;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);


		$sqlSelect = "SELECT SEQ_SERVIDOR , NUM_MATRICULA_RECURSO , NUM_ORDEM ";
		$sqlCorpo  = "FROM gestaoti.EQUIPE_SERVIDOR
						WHERE 1=1 ";

		if($this->SEQ_SERVIDOR != ""){
			$sqlCorpo .= "  and SEQ_SERVIDOR = $this->SEQ_SERVIDOR ";
		}
		if($this->NUM_MATRICULA_RECURSO != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_RECURSO = $this->NUM_MATRICULA_RECURSO ";
		}
		if($this->NUM_ORDEM != ""){
			$sqlCorpo .= "  and NUM_ORDEM = $this->NUM_ORDEM ";
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
		$sql = "DELETE FROM gestaoti.EQUIPE_SERVIDOR WHERE SEQ_SERVIDOR = $id";
		$result = $this->database->query($sql);
	}

	function deleteAlocacao($id){
		$sql = "DELETE FROM gestaoti.EQUIPE_SERVIDOR WHERE NUM_MATRICULA_RECURSO = $id";
		$result = $this->database->query($sql);
	}
	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.EQUIPE_SERVIDOR (SEQ_SERVIDOR, NUM_MATRICULA_RECURSO, NUM_ORDEM )
				VALUES (".$this->database->iif($this->SEQ_SERVIDOR=="", "NULL", "'".$this->SEQ_SERVIDOR."'").",
						".$this->database->iif($this->NUM_MATRICULA_RECURSO=="", "NULL", "'".$this->NUM_MATRICULA_RECURSO."'").",
						".$this->database->iif($this->NUM_ORDEM=="", "NULL", "'".$this->NUM_ORDEM."'")." )";
		//print $sql;
		$result = $this->database->query($sql);
	}
} // class : end
?>