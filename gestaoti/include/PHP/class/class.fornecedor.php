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
* CLASSNAME:        fornecedor
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class fornecedor{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $NUM_CPF_CGC;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_FORNECEDOR;   // (normal Attribute)
	var $NO_RAZAO_SOCIAL;   // (normal Attribute)
	var $NO_CONTATO;   // (normal Attribute)
	var $NUM_TELEFONE_CONTATO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function fornecedor(){
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
	function getNUM_CPF_CGC(){
		return $this->NUM_CPF_CGC;
	}

	function getNOM_FORNECEDOR(){
		return $this->NOM_FORNECEDOR;
	}

	function getNO_RAZAO_SOCIAL(){
		return $this->NO_RAZAO_SOCIAL;
	}

	function getNO_CONTATO(){
		return $this->NO_CONTATO;
	}

	function getNUM_TELEFONE_CONTATO(){
		return $this->NUM_TELEFONE_CONTATO;
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
	function setNUM_CPF_CGC($val){
		$this->NUM_CPF_CGC =  $val;
	}

	function setNOM_FORNECEDOR($val){
		$this->NOM_FORNECEDOR =  $val;
	}

	function setNO_RAZAO_SOCIAL($val){
		$this->NO_RAZAO_SOCIAL =  $val;
	}

	function setNO_CONTATO($val){
		$this->NO_CONTATO =  $val;
	}

	function setNUM_TELEFONE_CONTATO($val){
		$this->NUM_TELEFONE_CONTATO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.viw_fornecedor WHERE NUM_CPF_CGC = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->NUM_CPF_CGC = $row->NUM_CPF_CGC;
		$this->NOM_FORNECEDOR = $row->NOM_FORNECEDOR;
		$this->NO_RAZAO_SOCIAL = $row->NO_RAZAO_SOCIAL;
		$this->NO_CONTATO = $row->NO_CONTATO;
		$this->NUM_TELEFONE_CONTATO = $row->NUM_TELEFONE_CONTATO;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);


		$sqlSelect = "SELECT NUM_CPF_CGC , NOM_FORNECEDOR , NO_RAZAO_SOCIAL , NO_CONTATO , NUM_TELEFONE_CONTATO ";
		$sqlCorpo  = "FROM gestaoti.viw_fornecedor
						WHERE 1=1 ";

		if($this->NUM_CPF_CGC != ""){
			$sqlCorpo .= "  and NUM_CPF_CGC = $this->NUM_CPF_CGC ";
		}
		if($this->NOM_FORNECEDOR != ""){
			$sqlCorpo .= "  and upper(NOM_FORNECEDOR) like '%".strtoupper($this->NOM_FORNECEDOR)."%'  ";
		}
		if($this->NO_RAZAO_SOCIAL != ""){
			$sqlCorpo .= "  and upper(NO_RAZAO_SOCIAL) like '%".strtoupper($this->NO_RAZAO_SOCIAL)."%'  ";
		}
		if($this->NO_CONTATO != ""){
			$sqlCorpo .= "  and upper(NO_CONTATO) like '%".strtoupper($this->NO_CONTATO)."%'  ";
		}
		if($this->NUM_TELEFONE_CONTATO != ""){
			$sqlCorpo .= "  and upper(NUM_TELEFONE_CONTATO) like '%".strtoupper($this->NUM_TELEFONE_CONTATO)."%'  ";
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

} // class : end
?>