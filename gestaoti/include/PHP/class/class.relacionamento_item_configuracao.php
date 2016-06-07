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
* CLASSNAME:        relacionamento_item_configuracao
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class relacionamento_item_configuracao{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_RELAC_ITEM_CONFIGURACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_ITEM_CONFIGURACAO_PAI;   // (normal Attribute)
	var $SEQ_TIPO_RELAC_ITEM_CONFIG;   // (normal Attribute)
	var $SEQ_ITEM_CONFIGURACAO_FILHO;   // (normal Attribute)
	var $SEQ_SERVIDOR;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function relacionamento_item_configuracao(){
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
	function getSEQ_RELAC_ITEM_CONFIGURACAO(){
		return $this->SEQ_RELAC_ITEM_CONFIGURACAO;
	}

	function getSEQ_ITEM_CONFIGURACAO_PAI(){
		return $this->SEQ_ITEM_CONFIGURACAO_PAI;
	}

	function getSEQ_TIPO_RELAC_ITEM_CONFIG(){
		return $this->SEQ_TIPO_RELAC_ITEM_CONFIG;
	}

	function getSEQ_ITEM_CONFIGURACAO_FILHO(){
		return $this->SEQ_ITEM_CONFIGURACAO_FILHO;
	}

	function getSEQ_SERVIDOR(){
		return $this->SEQ_SERVIDOR;
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
	function setSEQ_RELAC_ITEM_CONFIGURACAO($val){
		$this->SEQ_RELAC_ITEM_CONFIGURACAO =  $val;
	}

	function setSEQ_ITEM_CONFIGURACAO_PAI($val){
		$this->SEQ_ITEM_CONFIGURACAO_PAI =  $val;
	}

	function setSEQ_TIPO_RELAC_ITEM_CONFIG($val){
		$this->SEQ_TIPO_RELAC_ITEM_CONFIG =  $val;
	}

	function setSEQ_ITEM_CONFIGURACAO_FILHO($val){
		$this->SEQ_ITEM_CONFIGURACAO_FILHO =  $val;
	}

	function setSEQ_SERVIDOR($val){
		$this->SEQ_SERVIDOR =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.RELAC_ITEM_CONFIGURACAO WHERE SEQ_RELAC_ITEM_CONFIGURACAO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_RELAC_ITEM_CONFIGURACAO = $row->seq_relac_item_configuracao;
		$this->SEQ_ITEM_CONFIGURACAO_PAI = $row->seq_item_configuracao_pai;
		$this->SEQ_TIPO_RELAC_ITEM_CONFIG = $row->seq_tipo_relac_item_config;
		$this->SEQ_ITEM_CONFIGURACAO_FILHO = $row->seq_item_configuracao_filho;
		$this->SEQ_SERVIDOR = $row->seq_servidor;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_RELAC_ITEM_CONFIGURACAO , SEQ_ITEM_CONFIGURACAO_PAI , SEQ_TIPO_RELAC_ITEM_CONFIG , SEQ_ITEM_CONFIGURACAO_FILHO , SEQ_SERVIDOR ";
		$sqlCorpo  = "FROM gestaoti.RELAC_ITEM_CONFIGURACAO
						WHERE 1=1 ";

		if($this->SEQ_RELAC_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_RELAC_ITEM_CONFIGURACAO = $this->SEQ_RELAC_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO_PAI != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO_PAI = $this->SEQ_ITEM_CONFIGURACAO_PAI ";
		}
		if($this->SEQ_TIPO_RELAC_ITEM_CONFIG != ""){
			$sqlCorpo .= "  and SEQ_TIPO_RELAC_ITEM_CONFIG = $this->SEQ_TIPO_RELAC_ITEM_CONFIG ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO_FILHO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO_FILHO = $this->SEQ_ITEM_CONFIGURACAO_FILHO ";
		}
		if($this->SEQ_SERVIDOR != ""){
			$sqlCorpo .= "  and SEQ_SERVIDOR = $this->SEQ_SERVIDOR ";
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
		$sql = "DELETE FROM gestaoti.RELAC_ITEM_CONFIGURACAO WHERE SEQ_RELAC_ITEM_CONFIGURACAO = $id";
		$result = $this->database->query($sql);
	}

	function deleteAll($id){
		$sql = "DELETE FROM gestaoti.RELAC_ITEM_CONFIGURACAO WHERE SEQ_ITEM_CONFIGURACAO_PAI = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_RELAC_ITEM_CONFIGURACAO = $this->database->GetSequenceValue("gestaoti.SEQ_RELAC_ITEM_CONFIGURACAO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.RELAC_ITEM_CONFIGURACAO (SEQ_RELAC_ITEM_CONFIGURACAO,
													 SEQ_ITEM_CONFIGURACAO_PAI,
													 SEQ_TIPO_RELAC_ITEM_CONFIG,
													 SEQ_ITEM_CONFIGURACAO_FILHO,
													 SEQ_SERVIDOR )
				VALUES (".$this->SEQ_RELAC_ITEM_CONFIGURACAO.",
						".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO_PAI=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO_PAI."'").",
						".$this->database->iif($this->SEQ_TIPO_RELAC_ITEM_CONFIG=="", "NULL", "'".$this->SEQ_TIPO_RELAC_ITEM_CONFIG."'").",
						".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO_FILHO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO_FILHO."'").",
						".$this->database->iif($this->SEQ_SERVIDOR=="", "NULL", "'".$this->SEQ_SERVIDOR."'")." )";
		$result = $this->database->query($sql);
	}
} // class : end
?>