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
* CLASSNAME:        area_externa_envolvida
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class area_externa_envolvida{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_ITEM_CONFIGURACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_AREA_EXTERNA;   // (normal Attribute)
	var $NOM_CONTATO;   // (normal Attribute)
	var $NUM_TELEFONE;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function area_externa_envolvida(){
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
	function getSEQ_AREA_EXTERNA(){
		return $this->SEQ_AREA_EXTERNA;
	}

	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	}

	function getNOM_CONTATO(){
		return $this->NOM_CONTATO;
	}

	function getNUM_TELEFONE(){
		return $this->NUM_TELEFONE;
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
	function setSEQ_AREA_EXTERNA($val){
		$this->SEQ_AREA_EXTERNA =  $val;
	}

	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}

	function setNOM_CONTATO($val){
		$this->NOM_CONTATO =  $val;
	}

	function setNUM_TELEFONE($val){
		$this->NUM_TELEFONE =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.area_externa_envolvida WHERE SEQ_ITEM_CONFIGURACAO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_AREA_EXTERNA = $row->seq_area_externa;
		$this->SEQ_ITEM_CONFIGURACAO = $row->seq_item_configuracao;
		$this->NOM_CONTATO = $row->nom_contato;
		$this->NUM_TELEFONE = $row->num_telefone;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_AREA_EXTERNA , SEQ_ITEM_CONFIGURACAO , NOM_CONTATO , NUM_TELEFONE ";
		$sqlCorpo  = "FROM  gestaoti.area_externa_envolvida
					  WHERE 1=1 ";

		if($this->SEQ_AREA_EXTERNA != ""){
			$sqlCorpo .= "  and SEQ_AREA_EXTERNA = $this->SEQ_AREA_EXTERNA ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->NOM_CONTATO != ""){
			$sqlCorpo .= "  and upper(NOM_CONTATO) like '%".strtoupper($this->NOM_CONTATO)."%'  ";
		}
		if($this->NUM_TELEFONE != ""){
			$sqlCorpo .= "  and upper(NUM_TELEFONE) like '%".strtoupper($this->NUM_TELEFONE)."%'  ";
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
		$sql = "DELETE FROM gestaoti.area_externa_envolvida WHERE SEQ_ITEM_CONFIGURACAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.area_externa_envolvida (SEQ_ITEM_CONFIGURACAO, SEQ_AREA_EXTERNA, NOM_CONTATO, NUM_TELEFONE )
				VALUES ( ".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",".$this->database->iif($this->SEQ_AREA_EXTERNA=="", "NULL", "'".$this->SEQ_AREA_EXTERNA."'").",".$this->database->iif($this->NOM_CONTATO=="", "NULL", "'".$this->NOM_CONTATO."'").",".$this->database->iif($this->NUM_TELEFONE=="", "NULL", "'".$this->NUM_TELEFONE."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.gestaoti.area_externa_envolvida
				 SET  SEQ_AREA_EXTERNA = ".$this->database->iif($this->SEQ_AREA_EXTERNA=="", "NULL", "'".$this->SEQ_AREA_EXTERNA."'").",
				 	  NOM_CONTATO = ".$this->database->iif($this->NOM_CONTATO=="", "NULL", "'".$this->NOM_CONTATO."'").",
					  NUM_TELEFONE = ".$this->database->iif($this->NUM_TELEFONE=="", "NULL", "'".$this->NUM_TELEFONE."'")."
				 WHERE SEQ_ITEM_CONFIGURACAO = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>