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
* Nome da Classe:	correcao_time_sheet
* Nome da tabela:	correcao_time_sheet
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// DECLARAÇÃO DA CLASSE
// **********************
class correcao_time_sheet{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_TIME_SHEET;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $DTH_INICIO_CORRECAO;   // (normal Attribute)
	var $DTH_FIM_CORRECAO;   // (normal Attribute)
	var $TXT_JUSTIFICATIVA_CORRECAO;   // (normal Attribute)
	var $FLG_APROVADO;   // (normal Attribute)
	var $NUM_MATRICULA_APROVADOR;   // (normal Attribute)
	var $SEQ_EQUIPE_TI;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function correcao_time_sheet(){
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

	function getSEQ_TIME_SHEET(){
		return $this->SEQ_TIME_SHEET;
	}

	function getDTH_INICIO_CORRECAO(){
		return $this->DTH_INICIO_CORRECAO;
	}

	function getDTH_FIM_CORRECAO(){
		return $this->DTH_FIM_CORRECAO;
	}

	function getTXT_JUSTIFICATIVA_CORRECAO(){
		return $this->TXT_JUSTIFICATIVA_CORRECAO;
	}

	function getFLG_APROVADO(){
		return $this->FLG_APROVADO;
	}

	function getNUM_MATRICULA_APROVADOR(){
		return $this->NUM_MATRICULA_APROVADOR;
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

	function setSEQ_TIME_SHEET($val){
		$this->SEQ_TIME_SHEET =  $val;
	}

	function setDTH_INICIO_CORRECAO($val){
		$this->DTH_INICIO_CORRECAO =  $val;
	}

	function setDTH_FIM_CORRECAO($val){
		$this->DTH_FIM_CORRECAO =  $val;
	}

	function setTXT_JUSTIFICATIVA_CORRECAO($val){
		$this->TXT_JUSTIFICATIVA_CORRECAO =  $val;
	}

	function setFLG_APROVADO($val){
		$this->FLG_APROVADO =  $val;
	}

	function setNUM_MATRICULA_APROVADOR($val){
		$this->NUM_MATRICULA_APROVADOR =  $val;
	}

	function setSEQ_EQUIPE_TI($val){
		$this->SEQ_EQUIPE_TI = $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT a.SEQ_TIME_SHEET , to_char(a.DTH_INICIO_CORRECAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_CORRECAO,
				       a.DTH_INICIO_CORRECAO as DTH_INICIO_CORRECAO_DATA , to_char(a.DTH_FIM_CORRECAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_FIM_CORRECAO,
				       a.DTH_FIM_CORRECAO as DTH_FIM_CORRECAO_DATA, a.TXT_JUSTIFICATIVA_CORRECAO, a.FLG_APROVADO, a.NUM_MATRICULA_APROVADOR,
				       c.NOM_COLABORADOR
				FROM gestaoti.correcao_time_sheet a, time_sheet b, viw_colaborador c
				WHERE a.SEQ_TIME_SHEET = b.SEQ_TIME_SHEET
				and b.NUM_MATRICULA = c.NUM_MATRICULA_COLABORADOR
				and a.SEQ_TIME_SHEET = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_TIME_SHEET = $row->SEQ_TIME_SHEET;
		$this->DTH_INICIO_CORRECAO = $row->DTH_INICIO_CORRECAO;
		$this->DTH_FIM_CORRECAO = $row->DTH_FIM_CORRECAO;
		$this->TXT_JUSTIFICATIVA_CORRECAO = $row->TXT_JUSTIFICATIVA_CORRECAO;
		$this->FLG_APROVADO = $row->FLG_APROVADO;
		$this->NUM_MATRICULA_APROVADOR = $row->NUM_MATRICULA_APROVADOR;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT a.SEQ_TIME_SHEET , to_char(a.DTH_INICIO_CORRECAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_CORRECAO,
      								   to_char(b.DTH_INICIO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO,
      								   to_char(b.DTH_FIM, 'dd/mm/yyyy hh24:mi:ss') as DTH_FIM,
								       a.DTH_INICIO_CORRECAO as DTH_INICIO_CORRECAO_DATA , to_char(a.DTH_FIM_CORRECAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_FIM_CORRECAO,
								       a.DTH_FIM_CORRECAO as DTH_FIM_CORRECAO_DATA, a.TXT_JUSTIFICATIVA_CORRECAO, a.FLG_APROVADO, a.NUM_MATRICULA_APROVADOR,
								       c.NOM_COLABORADOR, d.SEQ_EQUIPE_TI, b.SEQ_CHAMADO ";
		$sqlCorpo  = "FROM gestaoti.correcao_time_sheet a, time_sheet b, viw_colaborador c, recurso_ti d
								WHERE a.SEQ_TIME_SHEET = b.SEQ_TIME_SHEET
								and b.NUM_MATRICULA = c.NUM_MATRICULA_COLABORADOR
								and b.NUM_MATRICULA = d.NUM_MATRICULA_RECURSO ";
		if($orderBy != "" ){
			$sqlCorpo .= " order by $orderBy ";
		}
		$sqlCorpo .= ") PAGING
						WHERE 1=1 ";

		if($this->SEQ_TIME_SHEET != ""){
			$sqlCorpo .= "  and SEQ_TIME_SHEET = $this->SEQ_TIME_SHEET ";
		}
		if($this->DTH_INICIO_CORRECAO != "" && $this->DTH_INICIO_CORRECAO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO_CORRECAO >= to_date('".$this->DTH_INICIO_CORRECAO."', 'dd/mm/yyyy') ";
		}
		if($this->DTH_INICIO_CORRECAO != "" && $this->DTH_INICIO_CORRECAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_CORRECAO between to_date('".$this->DTH_INICIO_CORRECAO."', 'dd/mm/yyyy') and to_date('".$this->DTH_INICIO_CORRECAO_FINAL."', 'dd/mm/yyyy') ";
		}
		if($this->DTH_INICIO_CORRECAO == "" && $this->DTH_INICIO_CORRECAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_CORRECAO <= to_date('".$this->DTH_INICIO_CORRECAO_FINAL."', 'dd/mm/yyyy') ";
		}
		if($this->DTH_FIM_CORRECAO != "" && $this->DTH_FIM_CORRECAO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_FIM_CORRECAO >= to_date('".$this->DTH_FIM_CORRECAO."', 'dd/mm/yyyy') ";
		}
		if($this->DTH_FIM_CORRECAO != "" && $this->DTH_FIM_CORRECAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM_CORRECAO between to_date('".$this->DTH_FIM_CORRECAO."', 'dd/mm/yyyy') and to_date('".$this->DTH_FIM_CORRECAO_FINAL."', 'dd/mm/yyyy') ";
		}
		if($this->DTH_FIM_CORRECAO == "" && $this->DTH_FIM_CORRECAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM_CORRECAO <= to_date('".$this->DTH_FIM_CORRECAO_FINAL."', 'dd/mm/yyyy') ";
		}
		if($this->TXT_JUSTIFICATIVA_CORRECAO != ""){
			$sqlCorpo .= "  and upper(TXT_JUSTIFICATIVA_CORRECAO) like '%".strtoupper($this->TXT_JUSTIFICATIVA_CORRECAO)."%'  ";
		}
		if($this->FLG_APROVADO != ""){
			$sqlCorpo .= "  and FLG_APROVADO = '$this->FLG_APROVADO' ";
		}
		if($this->NUM_MATRICULA_APROVADOR == "NULL"){
			$sqlCorpo .= "  and NUM_MATRICULA_APROVADOR is null ";
		}elseif($this->NUM_MATRICULA_APROVADOR == "NOT NULL"){
			$sqlCorpo .= "  and NUM_MATRICULA_APROVADOR is not null ";
		}elseif($this->NUM_MATRICULA_APROVADOR != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_APROVADOR = $this->NUM_MATRICULA_APROVADOR ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
		}
		$sqlCount = $sqlCorpo;

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
		$sql = "DELETE FROM gestaoti.correcao_time_sheet WHERE SEQ_TIME_SHEET = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$sql = "INSERT INTO gestaoti.correcao_time_sheet(SEQ_TIME_SHEET,
										  DTH_INICIO_CORRECAO,
										  DTH_FIM_CORRECAO,
										  TXT_JUSTIFICATIVA_CORRECAO,
										  FLG_APROVADO,
										  NUM_MATRICULA_APROVADOR
									)
							 VALUES (".$this->iif($this->SEQ_TIME_SHEET=="", "NULL", "'".$this->SEQ_TIME_SHEET."'").",
									 ".$this->iif($this->DTH_INICIO_CORRECAO=="", "NULL", "to_date('".$this->DTH_INICIO_CORRECAO."', 'dd/mm/yyyy hh24:mi:ss')").",
									 ".$this->iif($this->DTH_FIM_CORRECAO=="", "NULL", "to_date('".$this->DTH_FIM_CORRECAO."', 'dd/mm/yyyy hh24:mi:ss')").",
									 ".$this->iif($this->TXT_JUSTIFICATIVA_CORRECAO=="", "NULL", "'".$this->TXT_JUSTIFICATIVA_CORRECAO."'").",
									 ".$this->iif($this->FLG_APROVADO=="", "NULL", "'".$this->FLG_APROVADO."'").",
									 ".$this->iif($this->NUM_MATRICULA_APROVADOR=="", "NULL", "'".$this->NUM_MATRICULA_APROVADOR."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.correcao_time_sheet
				 SET DTH_INICIO_CORRECAO = ".$this->iif($this->DTH_INICIO_CORRECAO=="", "NULL", "to_date('".$this->DTH_INICIO_CORRECAO."', 'dd/mm/yyyy hh24:mi:ss')").",
					 DTH_FIM_CORRECAO = ".$this->iif($this->DTH_FIM_CORRECAO=="", "NULL", "to_date('".$this->DTH_FIM_CORRECAO."', 'dd/mm/yyyy hh24:mi:ss')").",
					 TXT_JUSTIFICATIVA_CORRECAO = ".$this->iif($this->TXT_JUSTIFICATIVA_CORRECAO=="", "NULL", "'".$this->TXT_JUSTIFICATIVA_CORRECAO."'").",
					 FLG_APROVADO = ".$this->iif($this->FLG_APROVADO=="", "NULL", "'".$this->FLG_APROVADO."'").",
					 NUM_MATRICULA_APROVADOR = ".$this->iif($this->NUM_MATRICULA_APROVADOR=="", "NULL", "'".$this->NUM_MATRICULA_APROVADOR."'")."
				WHERE SEQ_TIME_SHEET = $id ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function avaliar($id){
		$sql = " UPDATE gestaoti.correcao_time_sheet
				 SET FLG_APROVADO = ".$this->iif($this->FLG_APROVADO=="", "NULL", "'".$this->FLG_APROVADO."'").",
					 NUM_MATRICULA_APROVADOR = ".$this->iif($this->NUM_MATRICULA_APROVADOR=="", "NULL", "'".$this->NUM_MATRICULA_APROVADOR."'")."
				WHERE SEQ_TIME_SHEET = $id ";
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