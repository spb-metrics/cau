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
class janela_mudacao_item_configuracao{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página
	
	var $seq_janela_mudanca;   // (normal Attribute) seq_janela_mudanca
	var $seq_item_configuracao;   // (normal Attribute) seq_servidor

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function janela_mudacao_item_configuracao(){
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
	 
	function getSeq_janela_mudanca(){
		return $this->seq_janela_mudanca;
	}

	function getSeq_item_configuracao(){
		return $this->seq_item_configuracao;
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
	function setSeq_janela_mudanca($val){
		$this->seq_janela_mudanca =  $val;
	}

	function setSeq_item_configuracao($val){
		$this->seq_item_configuracao =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	 

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		//$this->setvQtdRegistros($vQtdRegistros);
		  
		$sqlSelect = "SELECT seq_janela_mudanca, seq_item_configuracao ";
		$sqlCorpo  = "FROM gestaoti.janela_mudacao_item_configuracao WHERE 1=1 ";

		if($this->seq_janela_mudanca != ""){
			$sqlCorpo .= "  and seq_janela_mudanca = $this->seq_janela_mudanca ";
		}
		if($this->seq_item_configuracao != ""){
			$sqlCorpo .= "  and seq_item_configuracao = $this->seq_item_configuracao ";
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
	function deleteBySeq_janela_mudanca($id){
		$sql = "DELETE FROM gestaoti.janela_mudacao_item_configuracao WHERE seq_janela_mudanca = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.janela_mudacao_item_configuracao( seq_janela_mudanca, seq_item_configuracao)
				VALUES (".$this->database->iif($this->seq_janela_mudanca=="", "NULL", "'".$this->seq_janela_mudanca."'").", ".$this->database->iif($this->seq_item_configuracao=="", "NULL", "'".$this->seq_item_configuracao."'")." )";
		$result = $this->database->query($sql);
	}

 
} // class : end
?>