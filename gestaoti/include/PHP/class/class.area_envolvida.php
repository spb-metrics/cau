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
* CLASSNAME:        area_envolvida
* FOR TABLE:  		area_envolvida
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class area_envolvida{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_ITEM_CONFIGURACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $COD_UOR;   // (normal Attribute)
	var $NUM_MATRICULA_GESTOR;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function area_envolvida(){
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
	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	}

	function getCOD_UOR(){
		return $this->COD_UOR;
	}

	function getNUM_MATRICULA_GESTOR(){
		return $this->NUM_MATRICULA_GESTOR;
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
	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}

	function setCOD_UOR($val){
		$this->COD_UOR =  $val;
	}

	function setNUM_MATRICULA_GESTOR($val){
		$this->NUM_MATRICULA_GESTOR =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.area_envolvida WHERE SEQ_ITEM_CONFIGURACAO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_ITEM_CONFIGURACAO = $row->seq_item_configuracao;
		$this->COD_UOR = $row->cod_uor;
		$this->NUM_MATRICULA_GESTOR = $row->num_matricula_gestor;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT SEQ_ITEM_CONFIGURACAO , COD_UOR , NUM_MATRICULA_GESTOR ";
		$sqlCorpo  = " FROM gestaoti.area_envolvida
					   WHERE 1=1 ";

		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->COD_UOR != ""){
			$sqlCorpo .= "  and COD_UOR = $this->COD_UOR ";
		}
		if($this->NUM_MATRICULA_GESTOR != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_GESTOR = $this->NUM_MATRICULA_GESTOR ";
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
		$sql = "DELETE FROM gestaoti.area_envolvida WHERE SEQ_ITEM_CONFIGURACAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.area_envolvida (SEQ_ITEM_CONFIGURACAO, COD_UOR, NUM_MATRICULA_GESTOR )
				VALUES ( ".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").", ".$this->database->iif($this->COD_UOR=="", "NULL", "'".$this->COD_UOR."'").",".$this->database->iif($this->NUM_MATRICULA_GESTOR=="", "NULL", "'".$this->NUM_MATRICULA_GESTOR."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.area_envolvida SET  COD_UOR = ".$this->database->iif($this->COD_UOR=="", "NULL", "'".$this->COD_UOR."'").",NUM_MATRICULA_GESTOR = ".$this->database->iif($this->NUM_MATRICULA_GESTOR=="", "NULL", "'".$this->NUM_MATRICULA_GESTOR."'")." WHERE SEQ_ITEM_CONFIGURACAO = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>