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
* CLASSNAME:        ifrgestao_ti.viw_uor
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class unidades_organizacionais{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $COD_UOR;   // (normal Attribute)
	var $UOR_COD_UOR;   // (normal Attribute)
	var $UOR_NOME;   // (normal Attribute)
	var $UOR_SIGLA;   // (normal Attribute)
	var $UOR_DEP_CODIGO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function unidades_organizacionais(){
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
	function getCOD_UOR(){
		return $this->COD_UOR;
	}

	function getUOR_COD_UOR(){
		return $this->UOR_COD_UOR;
	}

	function getUOR_NOME(){
		return $this->UOR_NOME;
	}

	function getUOR_SIGLA(){
		return $this->UOR_SIGLA;
	}

	function getUOR_DEP_CODIGO(){
		return $this->UOR_DEP_CODIGO;
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
	function setCOD_UOR($val){
		$this->COD_UOR =  $val;
	}

	function setUOR_COD_UOR($val){
		$this->UOR_COD_UOR =  $val;
	}

	function setUOR_NOME($val){
		$this->UOR_NOME =  $val;
	}

	function setUOR_SIGLA($val){
		$this->UOR_SIGLA =  $val;
	}

	function setUOR_DEP_CODIGO($val){
		$this->UOR_DEP_CODIGO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "select idunidade as COD_UOR,
				       iddivisao as UOR_COD_UOR,
				       dsunidade as UOR_NOME,
				       dsunidade as UOR_SIGLA,
				       iddiretoria as UOR_DEP_CODIGO
				 FROM corp.tbunidade = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->COD_UOR = $row->cod_uor;
		$this->UOR_COD_UOR = $row->uor_cod_uor;
		$this->UOR_NOME = $row->uor_nome;
		$this->UOR_SIGLA = $row->uor_sigla;
		$this->UOR_DEP_CODIGO = $row->uor_dep_codigo;
	}

	function GetUorCodigo($id){
		if($id != ""){
			$sql =  "SELECT idunidade as COD_UOR
					 FROM corp.tbunidade
					 WHERE dsunidade = '$id'";
			$result =  $this->database->query($sql);
			$result = $this->database->result;
			$row = pg_fetch_object($result, 0);
			return $row->cod_uor;
		}
	}

	function GetUorSigla($id){
		$sql =  "SELECT dsunidade as UOR_SIGLA
				 FROM corp.tbunidade
				 WHERE idunidade = '$id'";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		return $row->uor_sigla;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);


		$sqlSelect = "select  idunidade as COD_UOR,
						      iddivisao as UOR_COD_UOR,
						      dsunidade as UOR_NOME,
						      dsunidade as UOR_SIGLA,
						      iddiretoria as UOR_DEP_CODIGO ";
		$sqlCorpo  = "FROM corp.tbunidade
					  where stativo is true ";

		if($this->COD_UOR != ""){
			$sqlCorpo .= "  and idunidade = $this->COD_UOR ";
		}
		if($this->UOR_COD_UOR != ""){
			$sqlCorpo .= "  and iddivisao = $this->UOR_COD_UOR ";
		}
		if($this->UOR_NOME != ""){
			$sqlCorpo .= "  and upper(dsunidade) like '%".strtoupper($this->UOR_NOME)."%'  ";
		}
		if($this->UOR_SIGLA != ""){
			$sqlCorpo .= "  and upper(dsunidade) like '%".strtoupper($this->UOR_SIGLA)."%'  ";
		}
		if($this->UOR_DEP_CODIGO != ""){
			$sqlCorpo .= "  and iddiretoria = $this->UOR_DEP_CODIGO ";
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