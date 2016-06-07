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
* CLASSNAME:        tipo_item_configuracao
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class tipo_item_configuracao{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_TIPO_ITEM_CONFIGURACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_TIPO_ITEM_CONFIGURACAO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function tipo_item_configuracao(){
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
	function getSEQ_TIPO_ITEM_CONFIGURACAO(){
		return $this->SEQ_TIPO_ITEM_CONFIGURACAO;
	}

	function getNOM_TIPO_ITEM_CONFIGURACAO(){
		return $this->NOM_TIPO_ITEM_CONFIGURACAO;
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
	function setSEQ_TIPO_ITEM_CONFIGURACAO($val){
		$this->SEQ_TIPO_ITEM_CONFIGURACAO =  $val;
	}

	function setNOM_TIPO_ITEM_CONFIGURACAO($val){
		$this->NOM_TIPO_ITEM_CONFIGURACAO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.tipo_item_configuracao WHERE SEQ_TIPO_ITEM_CONFIGURACAO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_TIPO_ITEM_CONFIGURACAO = $row->seq_tipo_item_configuracao;
		$this->NOM_TIPO_ITEM_CONFIGURACAO = $row->nom_tipo_item_configuracao;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);


		$sqlSelect = "SELECT SEQ_TIPO_ITEM_CONFIGURACAO , NOM_TIPO_ITEM_CONFIGURACAO ";
		$sqlCorpo  = "FROM gestaoti.tipo_item_configuracao
						WHERE 1=1 ";

		if($this->SEQ_TIPO_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_TIPO_ITEM_CONFIGURACAO = $this->SEQ_TIPO_ITEM_CONFIGURACAO ";
		}
		if($this->NOM_TIPO_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(NOM_TIPO_ITEM_CONFIGURACAO) like '%".strtoupper($this->NOM_TIPO_ITEM_CONFIGURACAO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.tipo_item_configuracao WHERE SEQ_TIPO_ITEM_CONFIGURACAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_TIPO_ITEM_CONFIGURACAO = $this->database->GetSequenceValue("gestaoti.SEQ_TIPO_ITEM_CONFIGURACAO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.tipo_item_configuracao (SEQ_TIPO_ITEM_CONFIGURACAO, NOM_TIPO_ITEM_CONFIGURACAO )
				VALUES (".$this->SEQ_TIPO_ITEM_CONFIGURACAO.",
						".$this->database->iif($this->NOM_TIPO_ITEM_CONFIGURACAO=="", "NULL", "'".$this->NOM_TIPO_ITEM_CONFIGURACAO."'")."
				)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.tipo_item_configuracao SET  NOM_TIPO_ITEM_CONFIGURACAO = ".$this->database->iif($this->NOM_TIPO_ITEM_CONFIGURACAO=="", "NULL", "'".$this->NOM_TIPO_ITEM_CONFIGURACAO."'")." WHERE SEQ_TIPO_ITEM_CONFIGURACAO = $id ";
		$result = $this->database->query($sql);

	}

} // class : end
?>