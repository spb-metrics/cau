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
* Nome da Classe:	prioridade_chamado
* Nome da tabela:	prioridade_chamado
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
}

// **********************
// DECLARAÇÃO DA CLASSE
// **********************
class prioridade_chamado{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_PRIORIDADE_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $DSC_PRIORIDADE_CHAMADO;   // (normal Attribute)
	var $COD_Prioridade_Padrao;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function prioridade_chamado(){
		$this->database = new Database();
		$this->COD_Prioridade_Padrao = 1;
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

	function getSEQ_PRIORIDADE_CHAMADO(){
		return $this->SEQ_PRIORIDADE_CHAMADO;
	}

	function getDSC_PRIORIDADE_CHAMADO(){
		return $this->DSC_PRIORIDADE_CHAMADO;
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

	function setSEQ_PRIORIDADE_CHAMADO($val){
		$this->SEQ_PRIORIDADE_CHAMADO =  $val;
	}

	function setDSC_PRIORIDADE_CHAMADO($val){
		$this->DSC_PRIORIDADE_CHAMADO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_PRIORIDADE_CHAMADO , DSC_PRIORIDADE_CHAMADO
			    FROM gestaoti.prioridade_chamado
				WHERE SEQ_PRIORIDADE_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_PRIORIDADE_CHAMADO = $row->seq_prioridade_chamado;
		$this->DSC_PRIORIDADE_CHAMADO = $row->dsc_prioridade_chamado;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_PRIORIDADE_CHAMADO , DSC_PRIORIDADE_CHAMADO ";
		$sqlCorpo  = "FROM gestaoti.prioridade_chamado
						WHERE 1=1 ";

		if($this->SEQ_PRIORIDADE_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_PRIORIDADE_CHAMADO = $this->SEQ_PRIORIDADE_CHAMADO ";
		}
		if($this->DSC_PRIORIDADE_CHAMADO != ""){
			$sqlCorpo .= "  and upper(DSC_PRIORIDADE_CHAMADO) like '%".strtoupper($this->DSC_PRIORIDADE_CHAMADO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.prioridade_chamado WHERE SEQ_PRIORIDADE_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_PRIORIDADE_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_PRIORIDADE_CHAMADO");

		$sql = "INSERT INTO gestaoti.prioridade_chamado(SEQ_PRIORIDADE_CHAMADO,
										  DSC_PRIORIDADE_CHAMADO
									)
							 VALUES (".$this->iif($this->SEQ_PRIORIDADE_CHAMADO=="", "NULL", "'".$this->SEQ_PRIORIDADE_CHAMADO."'").",
									 ".$this->iif($this->DSC_PRIORIDADE_CHAMADO=="", "NULL", "'".$this->DSC_PRIORIDADE_CHAMADO."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.prioridade_chamado
				 SET DSC_PRIORIDADE_CHAMADO = ".$this->iif($this->DSC_PRIORIDADE_CHAMADO=="", "NULL", "'".$this->DSC_PRIORIDADE_CHAMADO."'")."
				WHERE SEQ_PRIORIDADE_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $this->iif($vSelected == $row[0],"Selected", ""), $row[1]);
			$cont++;
		}
		return $aItemOption;
	}

	function iif($Condicao, $Sim, $Nao){
		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
	}

} // class : end
?>