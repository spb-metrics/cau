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
* Nome da Classe:	time_sheet
* Nome da tabela:	time_sheet
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// DECLARAÇÃO DA CLASSE
// **********************
class time_sheet{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_TIME_SHEET;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_CHAMADO;   // (normal Attribute)
	var $NUM_MATRICULA;   // (normal Attribute)
	var $DTH_INICIO;   // (normal Attribute)
	var $DTH_FIM;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	var $COD_DEPENDENCIA;
	var $SEQ_EQUIPE_TI;
	var $v_DTH_INICIO_FINAL;
	var $NOM_COLABORADOR;
	var $NOM_EQUIPE_TI;

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function time_sheet(){
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

	function getSEQ_CHAMADO(){
		return $this->SEQ_CHAMADO;
	}

	function getNUM_MATRICULA(){
		return $this->NUM_MATRICULA;
	}

	function getDTH_INICIO(){
		return $this->DTH_INICIO;
	}

	function getDTH_FIM(){
		return $this->DTH_FIM;
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

	function setSEQ_CHAMADO($val){
		$this->SEQ_CHAMADO =  $val;
	}

	function setNUM_MATRICULA($val){
		$this->NUM_MATRICULA =  $val;
	}

	function setDTH_INICIO($val){
		$this->DTH_INICIO =  $val;
	}

	function setDTH_INICIO_FINAL($val){
		$this->DTH_INICIO_FINAL =  $val;
	}

	function setDTH_FIM($val){
		$this->DTH_FIM =  $val;
	}

	function setCOD_DEPENDENCIA($val){
		$this->COD_DEPENDENCIA = $val;
	}

	function setSEQ_EQUIPE_TI($val){
		$this->SEQ_EQUIPE_TI = $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_TIME_SHEET, SEQ_CHAMADO, NUM_MATRICULA, to_char(DTH_INICIO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO,
				       DTH_INICIO as DTH_INICIO_DATA, to_char(DTH_FIM, 'dd/mm/yyyy hh24:mi:ss') as DTH_FIM, DTH_FIM as DTH_FIM_DATA,
				       b.NOM_COLABORADOR, d.NOM_EQUIPE_TI, d.SEQ_EQUIPE_TI, d.COD_DEPENDENCIA
				FROM gestaoti.time_sheet a, gestaoti.viw_colaborador b, gestaoti.recurso_ti c, gestaoti.equipe_ti d
				WHERE a.NUM_MATRICULA = b.NUM_MATRICULA_COLABORADOR
				and a.NUM_MATRICULA = c.NUM_MATRICULA_RECURSO
				and c.SEQ_EQUIPE_TI = d.SEQ_EQUIPE_TI
				and a.SEQ_TIME_SHEET = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_TIME_SHEET = $row->SEQ_TIME_SHEET;
		$this->SEQ_CHAMADO = $row->SEQ_CHAMADO;
		$this->NUM_MATRICULA = $row->NUM_MATRICULA;
		$this->DTH_INICIO = $row->DTH_INICIO;
		$this->DTH_FIM = $row->DTH_FIM;
		$this->COD_DEPENDENCIA = $row->COD_DEPENDENCIA;
		$this->NOM_EQUIPE_TI = $row->NOM_EQUIPE_TI;
		$this->SEQ_EQUIPE_TI = $row->SEQ_EQUIPE_TI;
		$this->NOM_COLABORADOR = $row->NOM_COLABORADOR;
	}

	// ***********************
	// SELECT VALIDAR CORRECAO
	// ***********************
	function ValidarSolicitacaoCorrecao($v_NUM_MATRICULA, $v_SEQ_TIME_SHEET, $v_DTH_INICIO, $v_DTH_FIM){
		$vcont = 0;
		$sql = "SELECT count(1) as CONT
				FROM gestaoti.time_sheet
				WHERE DTH_INICIO between to_date('".$v_DTH_INICIO."','dd/mm/yyyy hh24:mi:ss')
				and                      to_date('".$v_DTH_FIM."','dd/mm/yyyy hh24:mi:ss')
				and NUM_MATRICULA = $v_NUM_MATRICULA
				and SEQ_TIME_SHEET <> $v_SEQ_TIME_SHEET";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$vcont = $row->CONT;

		$sql = "SELECT count(1) as CONT
				FROM gestaoti.time_sheet
				WHERE DTH_FIM between to_date('".$v_DTH_INICIO."','dd/mm/yyyy hh24:mi:ss')
				and                   to_date('".$v_DTH_FIM."','dd/mm/yyyy hh24:mi:ss')
				and NUM_MATRICULA = $v_NUM_MATRICULA
				and SEQ_TIME_SHEET <> $v_SEQ_TIME_SHEET";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$vcont = $vcont + $row->CONT;
		if($vcont > 0){
			return false;
		}else{
			return true;
		}
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_TIME_SHEET , SEQ_CHAMADO , NUM_MATRICULA ,
					         to_char(DTH_INICIO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO,
					         to_char(DTH_FIM, 'dd/mm/yyyy hh24:mi:ss') as DTH_FIM,
							 b.NOM_COLABORADOR ";
		$sqlCorpo  = "FROM gestaoti.time_sheet a, gestaoti.viw_colaborador b
					  WHERE a.NUM_MATRICULA = b.NUM_MATRICULA_COLABORADOR ";

		if($this->SEQ_TIME_SHEET != ""){
			$sqlCorpo .= "  and SEQ_TIME_SHEET = $this->SEQ_TIME_SHEET ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO >= to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO between to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_INICIO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO == "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO <= to_date('".$this->DTH_INICIO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL == "" ){
			$sqlCorpo .= "  and DTH_FIM >= to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM between to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_FIM_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM == "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM <= to_date('".$this->DTH_FIM_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
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

	// ****************************
	// Pesquisa time sheet
	// ****************************
	function selectTimeSheet($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT SEQ_TIME_SHEET , SEQ_CHAMADO , NUM_MATRICULA , to_char(DTH_INICIO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_FORM,
							  to_char(DTH_FIM, 'dd/mm/yyyy hh24:mi:ss') as DTH_FIM_FORM,
							  /* round((DTH_FIM - DTH_INICIO)*60*60*24) as QTD_SEGUNDOS_DURACAO, */
							  DTH_INICIO as DTH_INICIO, DTH_FIM as DTH_FIM, b.NOM_COLABORADOR, c.SEQ_EQUIPE_TI, d.NOM_EQUIPE_TI,
							  d.COD_DEPENDENCIA ";
		$sqlCorpo  = " FROM gestaoti.time_sheet a, gestaoti.viw_colaborador b, gestaoti.recurso_ti c, gestaoti.equipe_ti d
					   WHERE a.NUM_MATRICULA = b.NUM_MATRICULA_COLABORADOR
						 and a.NUM_MATRICULA = c.NUM_MATRICULA_RECURSO
						 and c.SEQ_EQUIPE_TI = d.SEQ_EQUIPE_TI ";

		if($this->SEQ_TIME_SHEET != ""){
			$sqlCorpo .= "  and SEQ_TIME_SHEET = $this->SEQ_TIME_SHEET ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO >= to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO between to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_INICIO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO == "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO <= to_date('".$this->DTH_INICIO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL == "" ){
			$sqlCorpo .= "  and DTH_FIM >= to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM between to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_FIM_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM == "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM <= to_date('".$this->DTH_FIM_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and c.SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
		}
		if($this->COD_DEPENDENCIA != ""){
			$sqlCorpo .= "  and COD_DEPENDENCIA = $this->COD_DEPENDENCIA ";
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

	// ****************************
	// Pesquisa time sheet
	// ****************************
	function resumoTimeSheet(){
		$sqlCorpo  = "SELECT sum(round((DTH_FIM - DTH_INICIO)*60*60*24)) as QTD_SEGUNDOS_DURACAO,
							 d.NOM_EQUIPE_TI,
							 b.NOM_COLABORADOR
					  FROM gestaoti.time_sheet a, gestaoti.viw_colaborador b, gestaoti.recurso_ti c, gestaoti.equipe_ti d
					  where a.NUM_MATRICULA = b.NUM_MATRICULA_COLABORADOR
					  and a.NUM_MATRICULA = c.NUM_MATRICULA_RECURSO
					  and c.SEQ_EQUIPE_TI = d.SEQ_EQUIPE_TI ";

		if($this->SEQ_TIME_SHEET != ""){
			$sqlCorpo .= "  and SEQ_TIME_SHEET = $this->SEQ_TIME_SHEET ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO >= to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO between to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_INICIO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO == "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO <= to_date('".$this->DTH_INICIO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL == "" ){
			$sqlCorpo .= "  and DTH_FIM >= to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM between to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_FIM_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM == "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM <= to_date('".$this->DTH_FIM_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and d.SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
		}
		if($this->COD_DEPENDENCIA != ""){
			$sqlCorpo .= "  and d.COD_DEPENDENCIA = $this->COD_DEPENDENCIA ";
		}

		$sqlCorpo .= "
			group by d.NOM_EQUIPE_TI, b.NOM_COLABORADOR
			order by b.NOM_COLABORADOR ";
		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}

	// ****************************
	// Pesquisa time sheet
	// ****************************
	function relatorioHorasTrabalhadas(){
		$sqlSelect	= "SELECT to_char(DTH_INICIO, 'dd/mm/yyyy') as DTH_INICIO,
						       d.NOM_EQUIPE_TI,
						       b.NOM_COLABORADOR,
						       sum(round((DTH_FIM - DTH_INICIO)*60*60*24)) as QTD_SEGUNDOS_DURACAO,
       						   count(a.SEQ_TIME_SHEET) as QTD_LANCAMENTOS ";
		$sqlCorpo = "FROM gestaoti.time_sheet a, gestaoti.viw_colaborador b, gestaoti.recurso_ti c, gestaoti.equipe_ti d
					 where a.NUM_MATRICULA = b.NUM_MATRICULA_COLABORADOR
					 and a.NUM_MATRICULA = c.NUM_MATRICULA_RECURSO
					 and c.SEQ_EQUIPE_TI = d.SEQ_EQUIPE_TI ";

		if($this->SEQ_TIME_SHEET != ""){
			$sqlCorpo .= "  and SEQ_TIME_SHEET = $this->SEQ_TIME_SHEET ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO >= to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO between to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_INICIO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO == "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO <= to_date('".$this->DTH_INICIO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL == "" ){
			$sqlCorpo .= "  and DTH_FIM >= to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM between to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_FIM_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM == "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM <= to_date('".$this->DTH_FIM_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and d.SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
		}
		if($this->COD_DEPENDENCIA != ""){
			$sqlCorpo .= "  and d.COD_DEPENDENCIA = $this->COD_DEPENDENCIA ";
		}

		$sqlOrder = "
			group by to_char(DTH_INICIO, 'dd/mm/yyyy'), d.NOM_EQUIPE_TI, b.NOM_COLABORADOR
			order by b.NOM_COLABORADOR, DTH_INICIO ";


		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}

	function relatorioHorasTrabalhadasChamado(){
		$sqlSelect	= "SELECT a.SEQ_CHAMADO,
						       d.NOM_EQUIPE_TI,
						       b.NOM_COLABORADOR,
						       sum(round((DTH_FIM - DTH_INICIO)*60*60*24)) as QTD_SEGUNDOS_DURACAO,
       						   count(a.SEQ_TIME_SHEET) as QTD_LANCAMENTOS ";
		$sqlCorpo = "FROM gestaoti.time_sheet a, gestaoti.viw_colaborador b, gestaoti.recurso_ti c, gestaoti.equipe_ti d
					 where a.NUM_MATRICULA = b.NUM_MATRICULA_COLABORADOR
					 and a.NUM_MATRICULA = c.NUM_MATRICULA_RECURSO
					 and c.SEQ_EQUIPE_TI = d.SEQ_EQUIPE_TI ";

		if($this->SEQ_TIME_SHEET != ""){
			$sqlCorpo .= "  and SEQ_TIME_SHEET = $this->SEQ_TIME_SHEET ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO >= to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO != "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO between to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_INICIO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO == "" && $this->DTH_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO <= to_date('".$this->DTH_INICIO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL == "" ){
			$sqlCorpo .= "  and DTH_FIM >= to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM != "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM between to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_FIM_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_FIM == "" && $this->DTH_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DTH_FIM <= to_date('".$this->DTH_FIM_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and d.SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
		}
		if($this->COD_DEPENDENCIA != ""){
			$sqlCorpo .= "  and d.COD_DEPENDENCIA = $this->COD_DEPENDENCIA ";
		}

		$sqlOrder = "
			group by a.SEQ_CHAMADO, d.NOM_EQUIPE_TI, b.NOM_COLABORADOR
			order by b.NOM_COLABORADOR, a.SEQ_CHAMADO ";


		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}
	// **********************
	// DELETE
	// **********************
	function delete($id){
		$sql = "DELETE FROM gestaoti.time_sheet WHERE SEQ_TIME_SHEET = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_TIME_SHEET = $this->database->GetSequenceValue("gestaoti.SEQ_TIME_SHEET");

		$sql = "INSERT INTO gestaoti.time_sheet(SEQ_TIME_SHEET,
										  SEQ_CHAMADO,
										  NUM_MATRICULA,
										  DTH_INICIO
									)
							 VALUES (".$this->iif($this->SEQ_TIME_SHEET=="", "NULL", "'".$this->SEQ_TIME_SHEET."'").",
									 ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
									 ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
									 '".date("Y-m-d H:i:s")."'
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.time_sheet
				 SET SEQ_CHAMADO = ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
					 NUM_MATRICULA = ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
					 DTH_INICIO = ".$this->iif($this->DTH_INICIO=="", "NULL", "to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss')").",
					 DTH_FIM = ".$this->iif($this->DTH_FIM=="", "NULL", "to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss')")."
				WHERE SEQ_TIME_SHEET = $id ";
		$result = $this->database->query($sql);
	}

	// **********************
	// corrigir datas
	// **********************
	function CorrigirDatas($id){
		$sql = " UPDATE gestaoti.time_sheet
				 SET DTH_INICIO = ".$this->iif($this->DTH_INICIO=="", "NULL", "to_date('".$this->DTH_INICIO."', 'dd/mm/yyyy hh24:mi:ss')").",
					 DTH_FIM = ".$this->iif($this->DTH_FIM=="", "NULL", "to_date('".$this->DTH_FIM."', 'dd/mm/yyyy hh24:mi:ss')")."
				WHERE SEQ_TIME_SHEET = $id ";
		$result = $this->database->query($sql);
	}

	function  FinalizarTarefa(){
		// Terminar a tarefa
		$sql = " UPDATE gestaoti.time_sheet
				 SET DTH_FIM = '".date("Y-m-d H:i:s")."'
				 WHERE NUM_MATRICULA = ".$this->NUM_MATRICULA."
				 and SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
				 and DTH_FIM is null ";
		$result = $this->database->query($sql);
	}

	function IniciarTarefa(){
		// Terminar as tarefas em aberto
		$sql = " UPDATE gestaoti.time_sheet
				 SET DTH_FIM = '".date("Y-m-d H:i:s")."'
				 WHERE NUM_MATRICULA = ".$this->NUM_MATRICULA."
				 and DTH_FIM is null ";
		$result = $this->database->query($sql);

		// Inserir registro
		$this->insert();
	}

	function VerificarInicioAtividadeChamado($v_SEQ_CHAMADO, $v_NUM_MATRICULA){
		$sql = "select count(1)
				FROM gestaoti.time_sheet
				where seq_chamado = $v_SEQ_CHAMADO
				and NUM_MATRICULA = $v_NUM_MATRICULA
				and DTH_FIM is null";
		$result = $this->database->query($sql);
		$rowCount = pg_fetch_array($this->database->result);
		if($rowCount[0] > 0){
			return 1; // Atividade em aberto
		}else{
			return 0; // Atividade fechada
		}
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