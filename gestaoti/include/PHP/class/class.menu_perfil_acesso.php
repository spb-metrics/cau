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
* CLASSNAME:        menu_perfil_acesso
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class menu_perfil_acesso{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_PERFIL_ACESSO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_MENU_ACESSO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function menu_perfil_acesso(){
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
	function getSEQ_MENU_ACESSO(){
		return $this->SEQ_MENU_ACESSO;
	}

	function getSEQ_PERFIL_ACESSO(){
		return $this->SEQ_PERFIL_ACESSO;
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
	function setSEQ_MENU_ACESSO($val){
		$this->SEQ_MENU_ACESSO =  $val;
	}

	function setSEQ_PERFIL_ACESSO($val){
		$this->SEQ_PERFIL_ACESSO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.menu_perfil_acesso WHERE SEQ_PERFIL_ACESSO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_MENU_ACESSO = $row->SEQ_MENU_ACESSO;
		$this->SEQ_PERFIL_ACESSO = $row->SEQ_PERFIL_ACESSO;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_MENU_ACESSO , SEQ_PERFIL_ACESSO ";
		$sqlCorpo  = "FROM gestaoti.menu_perfil_acesso
						WHERE 1=1 ";

		if($this->SEQ_MENU_ACESSO != ""){
			$sqlCorpo .= "  and SEQ_MENU_ACESSO = $this->SEQ_MENU_ACESSO ";
		}
		if($this->SEQ_PERFIL_ACESSO != ""){
			$sqlCorpo .= "  and SEQ_PERFIL_ACESSO = $this->SEQ_PERFIL_ACESSO ";
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
		$sql = "DELETE FROM gestaoti.menu_perfil_acesso WHERE SEQ_MENU_ACESSO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.menu_perfil_acesso ( SEQ_MENU_ACESSO, SEQ_PERFIL_ACESSO )
				VALUES (".$this->database->iif($this->SEQ_MENU_ACESSO=="", "NULL", "'".$this->SEQ_MENU_ACESSO."'").", ".$this->database->iif($this->SEQ_PERFIL_ACESSO=="", "NULL", "'".$this->SEQ_PERFIL_ACESSO."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = "UPDATE menu_perfil_acesso
				SET  SEQ_MENU_ACESSO = ".$this->database->iif($this->SEQ_MENU_ACESSO=="", "NULL", "'".$this->SEQ_MENU_ACESSO."'")."
				WHERE SEQ_PERFIL_ACESSO = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>