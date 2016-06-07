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
* Nome da Classe:	equipe_atribuicao
* Data de criação:	24.09.2008
* Nome do Arquivo:	D:\Tiago\Pessoal\pages\gestaoti/GeraPHP/include/PHP/class/class.equipe_atribuicao.php
* Nome da tabela:	equipe_atribuicao
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// DECLARAÇÃO DA CLASSE
// **********************
class equipe_atribuicao{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_EQUIPE_ATRIBUICAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_EQUIPE_TI;   // (normal Attribute)
	var $DSC_EQUIPE_ATRIBUICAO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	var $SEQ_CENTRAL_ATENDIMENTO;  // (normal Attribute)
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function equipe_atribuicao(){
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

	function getSEQ_EQUIPE_ATRIBUICAO(){
		return $this->SEQ_EQUIPE_ATRIBUICAO;
	}

	function getSEQ_EQUIPE_TI(){
		return $this->SEQ_EQUIPE_TI;
	}

	function getDSC_EQUIPE_ATRIBUICAO(){
		return $this->DSC_EQUIPE_ATRIBUICAO;
	}
	
	function getSEQ_CENTRAL_ATENDIMENTO(){
		return $this->SEQ_CENTRAL_ATENDIMENTO;
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

	function setSEQ_EQUIPE_ATRIBUICAO($val){
		$this->SEQ_EQUIPE_ATRIBUICAO =  $val;
	}

	function setSEQ_EQUIPE_TI($val){
		$this->SEQ_EQUIPE_TI =  $val;
	}

	function setDSC_EQUIPE_ATRIBUICAO($val){
		$this->DSC_EQUIPE_ATRIBUICAO =  $val;
	}
	
	function setSEQ_CENTRAL_ATENDIMENTO($val){
		$this->SEQ_CENTRAL_ATENDIMENTO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_EQUIPE_ATRIBUICAO , SEQ_EQUIPE_TI , DSC_EQUIPE_ATRIBUICAO
			    FROM gestaoti.equipe_atribuicao
				WHERE SEQ_EQUIPE_ATRIBUICAO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_EQUIPE_ATRIBUICAO = $row->seq_equipe_atribuicao;
		$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
		$this->DSC_EQUIPE_ATRIBUICAO = $row->dsc_equipe_atribuicao;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT a.SEQ_EQUIPE_ATRIBUICAO , a.SEQ_EQUIPE_TI , a.DSC_EQUIPE_ATRIBUICAO ";
		$sqlCorpo  = "FROM gestaoti.equipe_atribuicao a, gestaoti.equipe_ti b ";

		$sqlCorpo .= " WHERE a.SEQ_EQUIPE_TI = b.SEQ_EQUIPE_TI AND 1=1 ";

		if($this->SEQ_EQUIPE_ATRIBUICAO != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_ATRIBUICAO = $this->SEQ_EQUIPE_ATRIBUICAO ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
		}
		if($this->DSC_EQUIPE_ATRIBUICAO != ""){
			$sqlCorpo .= "  and upper(DSC_EQUIPE_ATRIBUICAO) like '%".strtoupper($this->DSC_EQUIPE_ATRIBUICAO)."%'  ";
		}
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo .= "  and b.SEQ_CENTRAL_ATENDIMENTO = $this->SEQ_CENTRAL_ATENDIMENTO ";
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
		$sql = "DELETE FROM gestaoti.equipe_atribuicao WHERE SEQ_EQUIPE_ATRIBUICAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_EQUIPE_ATRIBUICAO = $this->database->GetSequenceValue("gestaoti.SEQ_EQUIPE_ATRIBUICAO");

		$sql = "INSERT INTO gestaoti.equipe_atribuicao(SEQ_EQUIPE_ATRIBUICAO,
										  SEQ_EQUIPE_TI,
										  DSC_EQUIPE_ATRIBUICAO
									)
							 VALUES (".$this->iif($this->SEQ_EQUIPE_ATRIBUICAO=="", "NULL", "'".$this->SEQ_EQUIPE_ATRIBUICAO."'").",
									 ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
									 ".$this->iif($this->DSC_EQUIPE_ATRIBUICAO=="", "NULL", "'".$this->DSC_EQUIPE_ATRIBUICAO."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.equipe_atribuicao
				 SET SEQ_EQUIPE_TI = ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
					 DSC_EQUIPE_ATRIBUICAO = ".$this->iif($this->DSC_EQUIPE_ATRIBUICAO=="", "NULL", "'".$this->DSC_EQUIPE_ATRIBUICAO."'")."
				WHERE SEQ_EQUIPE_ATRIBUICAO = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row["seq_equipe_atribuicao"], $this->iif($vSelected == $row["seq_equipe_atribuicao"],"Selected", ""), $row["dsc_equipe_atribuicao"]);
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