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
*
* -------------------------------------------------------
* CLASSNAME:        empregados
* -------------------------------------------------------
*
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class empregados{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $NUM_MATRICULA_RECURSO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NOM_LOGIN_REDE;   // (normal Attribute)
	var $NOME;   // (normal Attribute)
	var $NOME_ABREVIADO;   // (normal Attribute)
	var $NOME_GUERRA;   // (normal Attribute)
	var $DEP_SIGLA;   // (normal Attribute)
	var $UOR_SIGLA;   // (normal Attribute)
	var $DES_EMAIL;   // (normal Attribute)
	var $NUM_DDD;   // (normal Attribute)
	var $NUM_TELEFONE;   // (normal Attribute)
	var $NUM_VOIP;   // (normal Attribute)
	var $DES_ATATUS;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function empregados(){
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
	function getNUM_MATRICULA_RECURSO(){
		return $this->NUM_MATRICULA_RECURSO;
	}

	function getNOM_LOGIN_REDE(){
		return $this->NOM_LOGIN_REDE;
	}

	function getNOME(){
		return $this->NOME;
	}

	function getNOME_ABREVIADO(){
		return $this->NOME_ABREVIADO;
	}

	function getNOME_GUERRA(){
		return $this->NOME_GUERRA;
	}

	function getDEP_SIGLA(){
		return $this->DEP_SIGLA;
	}

	function getUOR_SIGLA(){
		return $this->UOR_SIGLA;
	}

	function getDES_EMAIL(){
		return $this->DES_EMAIL;
	}

	function getNUM_DDD(){
		return $this->NUM_DDD;
	}

	function getNUM_TELEFONE(){
		return $this->NUM_TELEFONE;
	}

	function getNUM_VOIP(){
		return $this->NUM_VOIP;
	}

	function getDES_ATATUS(){
		return $this->DES_ATATUS;
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
	function setNUM_MATRICULA_RECURSO($val){
		$this->NUM_MATRICULA_RECURSO =  $val;
	}

	function setNOM_LOGIN_REDE($val){
		$this->NOM_LOGIN_REDE =  $val;
	}

	function setNOME($val){
		$this->NOME =  $val;
	}

	function setNOME_ABREVIADO($val){
		$this->NOME_ABREVIADO =  $val;
	}

	function setNOME_GUERRA($val){
		$this->NOME_GUERRA =  $val;
	}

	function setDEP_SIGLA($val){
		$this->DEP_SIGLA =  $val;
	}

	function setUOR_SIGLA($val){
		$this->UOR_SIGLA =  $val;
	}

	function setDES_EMAIL($val){
		$this->DES_EMAIL =  $val;
	}

	function setNUM_DDD($val){
		$this->NUM_DDD =  $val;
	}

	function setNUM_TELEFONE($val){
		$this->NUM_TELEFONE =  $val;
	}

	function setNUM_VOIP($val){
		$this->NUM_VOIP =  $val;
	}

	function setDES_ATATUS($val){
		$this->DES_ATATUS =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.empregados WHERE NUM_MATRICULA_RECURSO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->NUM_MATRICULA_RECURSO = $row->NUM_MATRICULA_RECURSO;
		$this->NOM_LOGIN_REDE = $row->NOM_LOGIN_REDE;
		$this->NOME = $row->NOME;
		$this->NOME_ABREVIADO = $row->NOME_ABREVIADO;
		$this->NOME_GUERRA = $row->NOME_GUERRA;
		$this->DEP_SIGLA = $row->DEP_SIGLA;
		$this->UOR_SIGLA = $row->UOR_SIGLA;
		$this->DES_EMAIL = $row->DES_EMAIL;
		$this->NUM_DDD = $row->NUM_DDD;
		$this->NUM_TELEFONE = $row->NUM_TELEFONE;
		$this->NUM_VOIP = $row->NUM_VOIP;
		$this->DES_ATATUS = $row->DES_ATATUS;
	}

	function GetNumeroMatricula($id){
		$sql =  "SELECT NUM_MATRICULA_RECURSO
				 FROM gestaoti.empregados
				 WHERE NOM_LOGIN_REDE = '$id';";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		return $row->NUM_MATRICULA_RECURSO;
	}

	function GetNomLoginRedeMatricula($id){
		$sql =  "SELECT NOM_LOGIN_REDE
				 FROM gestaoti.empregados
				 WHERE NUM_MATRICULA_RECURSO = '$id';";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		return $row->NOM_LOGIN_REDE;
	}

	function GetNomeEmpregado($id){
		$sql =  "SELECT NOME
				 FROM gestaoti.empregados
				 WHERE NUM_MATRICULA_RECURSO = '$id';";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		return $row->NOME;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT NUM_MATRICULA_RECURSO , NOM_LOGIN_REDE , NOME , NOME_ABREVIADO , NOME_GUERRA , DEP_SIGLA , UOR_SIGLA , DES_EMAIL , NUM_DDD , NUM_TELEFONE , NUM_VOIP , DES_ATATUS ";
		$sqlCorpo  = " FROM gestaoti.empregados
			      WHERE 1=1 ";

		if($this->NUM_MATRICULA_RECURSO != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_RECURSO = $this->NUM_MATRICULA_RECURSO ";
		}
		if($this->NOM_LOGIN_REDE != ""){
			$sqlCorpo .= "  and upper(NOM_LOGIN_REDE) like '%".strtoupper($this->NOM_LOGIN_REDE)."%'  ";
		}
		if($this->NOME != ""){
			$sqlCorpo .= "  and upper(NOME) like '%".strtoupper($this->NOME)."%'  ";
		}
		if($this->NOME_ABREVIADO != ""){
			$sqlCorpo .= "  and upper(NOME_ABREVIADO) like '%".strtoupper($this->NOME_ABREVIADO)."%'  ";
		}
		if($this->NOME_GUERRA != ""){
			$sqlCorpo .= "  and upper(NOME_GUERRA) like '%".strtoupper($this->NOME_GUERRA)."%'  ";
		}
		if($this->DEP_SIGLA != ""){
			$sqlCorpo .= "  and upper(DEP_SIGLA) like '%".strtoupper($this->DEP_SIGLA)."%'  ";
		}
		if($this->UOR_SIGLA != ""){
			$sqlCorpo .= "  and upper(UOR_SIGLA) like '%".strtoupper($this->UOR_SIGLA)."%'  ";
		}
		if($this->DES_EMAIL != ""){
			$sqlCorpo .= "  and upper(DES_EMAIL) like '%".strtoupper($this->DES_EMAIL)."%'  ";
		}
		if($this->NUM_DDD != ""){
			$sqlCorpo .= "  and upper(NUM_DDD) like '%".strtoupper($this->NUM_DDD)."%'  ";
		}
		if($this->NUM_TELEFONE != ""){
			$sqlCorpo .= "  and upper(NUM_TELEFONE) like '%".strtoupper($this->NUM_TELEFONE)."%'  ";
		}
		if($this->NUM_VOIP != ""){
			$sqlCorpo .= "  and upper(NUM_VOIP) like '%".strtoupper($this->NUM_VOIP)."%'  ";
		}
		if($this->DES_ATATUS != ""){
			$sqlCorpo .= "  and upper(DES_ATATUS) like '%".strtoupper($this->DES_ATATUS)."%'  ";
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