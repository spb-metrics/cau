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
* CLASSNAME:        item_configuracao
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
}

// **********************
// CLASS DECLARATION
// **********************
class item_configuracao{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_ITEM_CONFIGURACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_TIPO_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $SEQ_SERVICO;   // (normal Attribute)
	var $NUM_MATRICULA_GESTOR;   // (normal Attribute)
	var $NUM_MATRICULA_LIDER;   // (normal Attribute)
	var $SIG_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $NOM_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $COD_UOR_AREA_GESTORA;   // (normal Attribute)
	var $TXT_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $SEQ_TIPO_DISPONIBILIDADE;   // (normal Attribute)
	var $SEQ_PRIORIDADE;   // (normal Attribute)
	var $UOR_SIGLA;
	var $SEQ_CRITICIDADE;
	var $SEQ_EQUIPE_TI;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function item_configuracao(){
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

	function getSEQ_TIPO_ITEM_CONFIGURACAO(){
		return $this->SEQ_TIPO_ITEM_CONFIGURACAO;
	}

	function getSEQ_SERVICO(){
		return $this->SEQ_SERVICO;
	}

	function getNUM_MATRICULA_GESTOR(){
		return $this->NUM_MATRICULA_GESTOR;
	}

	function getNUM_MATRICULA_LIDER(){
		return $this->NUM_MATRICULA_LIDER;
	}

	function getSIG_ITEM_CONFIGURACAO(){
		return $this->SIG_ITEM_CONFIGURACAO;
	}

	function getNOM_ITEM_CONFIGURACAO(){
		return $this->NOM_ITEM_CONFIGURACAO;
	}

	function getCOD_UOR_AREA_GESTORA(){
		return $this->COD_UOR_AREA_GESTORA;
	}

	function getTXT_ITEM_CONFIGURACAO(){
		return $this->TXT_ITEM_CONFIGURACAO;
	}

	function getSEQ_TIPO_DISPONIBILIDADE(){
		return $this->SEQ_TIPO_DISPONIBILIDADE;
	}

	function getSEQ_PRIORIDADE(){
		return $this->SEQ_PRIORIDADE;
	}

	function getSEQ_CRITICIDADE(){
		return $this->SEQ_CRITICIDADE;
	}

	function getSEQ_EQUIPE_TI(){
		return $this->SEQ_EQUIPE_TI;
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

	function setSEQ_TIPO_ITEM_CONFIGURACAO($val){
		$this->SEQ_TIPO_ITEM_CONFIGURACAO =  $val;
	}

	function setSEQ_SERVICO($val){
		$this->SEQ_SERVICO =  $val;
	}

	function setNUM_MATRICULA_GESTOR($val){
		$this->NUM_MATRICULA_GESTOR =  $val;
	}

	function setNUM_MATRICULA_LIDER($val){
		$this->NUM_MATRICULA_LIDER =  $val;
	}

	function setSIG_ITEM_CONFIGURACAO($val){
		$this->SIG_ITEM_CONFIGURACAO =  $val;
	}

	function setNOM_ITEM_CONFIGURACAO($val){
		$this->NOM_ITEM_CONFIGURACAO =  $val;
	}

	function setCOD_UOR_AREA_GESTORA($val){
		$this->COD_UOR_AREA_GESTORA =  $val;
	}

	function setTXT_ITEM_CONFIGURACAO($val){
		$this->TXT_ITEM_CONFIGURACAO =  $val;
	}

	function setSEQ_TIPO_DISPONIBILIDADE($val){
		$this->SEQ_TIPO_DISPONIBILIDADE =  $val;
	}

	function setSEQ_PRIORIDADE($val){
		$this->SEQ_PRIORIDADE =  $val;
	}
	function setSEQ_CRITICIDADE($val){
		$this->SEQ_CRITICIDADE =  $val;
	}
	function setUOR_SIGLA($val){
		$this->UOR_SIGLA =  $val;
	}
	function setSEQ_EQUIPE_TI($val){
		$this->SEQ_EQUIPE_TI = $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.item_configuracao WHERE SEQ_ITEM_CONFIGURACAO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_ITEM_CONFIGURACAO = $row->seq_item_configuracao;
		$this->SEQ_TIPO_ITEM_CONFIGURACAO = $row->seq_tipo_item_configuracao;
		//$this->SEQ_SERVICO = $row->seq_servico;
		$this->NUM_MATRICULA_GESTOR = $row->num_matricula_gestor;
		$this->NUM_MATRICULA_LIDER = $row->num_matricula_lider;
		$this->SIG_ITEM_CONFIGURACAO = $row->sig_item_configuracao;
		$this->NOM_ITEM_CONFIGURACAO = $row->nom_item_configuracao;
		$this->COD_UOR_AREA_GESTORA = $row->cod_uor_area_gestora;
		$this->TXT_ITEM_CONFIGURACAO = $row->txt_item_configuracao;
		$this->SEQ_TIPO_DISPONIBILIDADE = $row->seq_tipo_disponibilidade;
		$this->SEQ_PRIORIDADE = $row->seq_prioridade;
		$this->SEQ_CRITICIDADE = $row->seq_criticidade;
		$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_ITEM_CONFIGURACAO , SEQ_TIPO_ITEM_CONFIGURACAO , SEQ_SERVICO , NUM_MATRICULA_GESTOR ,
						  			   NUM_MATRICULA_LIDER , SIG_ITEM_CONFIGURACAO , NOM_ITEM_CONFIGURACAO , COD_UOR_AREA_GESTORA ,
									   TXT_ITEM_CONFIGURACAO , SEQ_TIPO_DISPONIBILIDADE , SEQ_PRIORIDADE, SEQ_CRITICIDADE, SEQ_EQUIPE_TI ";
		$sqlCorpo  = "FROM gestaoti.item_configuracao
						WHERE 1=1 ";

		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_TIPO_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_TIPO_ITEM_CONFIGURACAO = $this->SEQ_TIPO_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_SERVICO != ""){
			$sqlCorpo .= "  and SEQ_SERVICO = $this->SEQ_SERVICO ";
		}
		if($this->NUM_MATRICULA_GESTOR != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_GESTOR = $this->NUM_MATRICULA_GESTOR ";
		}
		if($this->NUM_MATRICULA_LIDER != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_LIDER = $this->NUM_MATRICULA_LIDER ";
		}
		if($this->SIG_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(SIG_ITEM_CONFIGURACAO) like '%".strtoupper($this->SIG_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->NOM_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(NOM_ITEM_CONFIGURACAO) like '%".strtoupper($this->NOM_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->COD_UOR_AREA_GESTORA != ""){
			$sqlCorpo .= "  and COD_UOR_AREA_GESTORA = $this->COD_UOR_AREA_GESTORA ";
		}
		if($this->TXT_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(TXT_ITEM_CONFIGURACAO) like '%".strtoupper($this->TXT_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->SEQ_TIPO_DISPONIBILIDADE != ""){
			$sqlCorpo .= "  and SEQ_TIPO_DISPONIBILIDADE = $this->SEQ_TIPO_DISPONIBILIDADE ";
		}
		if($this->SEQ_PRIORIDADE != ""){
			$sqlCorpo .= "  and SEQ_PRIORIDADE = $this->SEQ_PRIORIDADE ";
		}
		if($this->SEQ_CRITICIDADE != ""){
			$sqlCorpo .= "  and SEQ_CRITICIDADE = $this->SEQ_CRITICIDADE ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
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

	function selectParamSoftware($orderBy = 1, $vListar, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT a.SEQ_ITEM_CONFIGURACAO , a.SEQ_TIPO_ITEM_CONFIGURACAO , a.SEQ_SERVICO , a.NUM_MATRICULA_GESTOR ,
							  	   a.NUM_MATRICULA_LIDER, a.SIG_ITEM_CONFIGURACAO , a.NOM_ITEM_CONFIGURACAO , a.COD_UOR_AREA_GESTORA,
								   a.TXT_ITEM_CONFIGURACAO, a.SEQ_TIPO_DISPONIBILIDADE, a.SEQ_PRIORIDADE, b.NOME, c.UOR_SIGLA, c.UOR_NOME,
								   d.FLG_EM_MANUTENCAO, a.SEQ_EQUIPE_TI ";
		$sqlCorpo  = " FROM gestaoti.item_configuracao a,
							(select
								a.idpessoa as NUM_MATRICULA_RECURSO,
								e.dsunidade as LOTACAO
							  from sgrh.tbpessoa a, sgrh.tbvinculofuncional c, corp.tbunidade e
							  where c.idvinculofuncional= a.idvinculofuncionalatual
							  and e.idunidade= c.idunidadelotacao) b,
							(select  idunidade as COD_UOR,
						      iddivisao as UOR_COD_UOR,
						      dsunidade as UOR_NOME,
						      dsunidade as UOR_SIGLA,
						      iddiretoria as UOR_DEP_CODIGO
							 FROM corp.tbunidade) c,
							item_configuracao_software d,
							(select  idunidade as COD_UOR,
						      iddivisao as UOR_COD_UOR,
						      dsunidade as UOR_NOME,
						      dsunidade as UOR_SIGLA,
						      iddiretoria as UOR_DEP_CODIGO
							 FROM corp.tbunidade) e
							WHERE a.NUM_MATRICULA_LIDER = b.NUM_MATRICULA_RECURSO
							  and b.LOTACAO = c.UOR_SIGLA
							  and a.SEQ_ITEM_CONFIGURACAO = d.SEQ_ITEM_CONFIGURACAO
							  and a.COD_UOR_AREA_GESTORA = e.UOR_CODIGO
						WHERE 1=1 ";

		if($vListar == "D"){
			$sqlCorpo .= "  and SEQ_STATUS_SOFTWARE not in (3, 9, 8)";
		}elseif($vListar == "M"){
			$sqlCorpo .= "  and d.FLG_EM_MANUTENCAO = 'S' ";
		}
		if($this->UOR_SIGLA != ""){
			$sqlCorpo .= "  and UOR_SIGLA = '$this->UOR_SIGLA' ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_TIPO_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_TIPO_ITEM_CONFIGURACAO = $this->SEQ_TIPO_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_SERVICO != ""){
			$sqlCorpo .= "  and SEQ_SERVICO = $this->SEQ_SERVICO ";
		}
		if($this->NUM_MATRICULA_GESTOR != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_GESTOR = $this->NUM_MATRICULA_GESTOR ";
		}
		if($this->NUM_MATRICULA_LIDER != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_LIDER = $this->NUM_MATRICULA_LIDER ";
		}
		if($this->SIG_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(SIG_ITEM_CONFIGURACAO) like '%".strtoupper($this->SIG_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->NOM_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(NOM_ITEM_CONFIGURACAO) like '%".strtoupper($this->NOM_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->COD_UOR_AREA_GESTORA != ""){
			$sqlCorpo .= "  and COD_UOR_AREA_GESTORA = $this->COD_UOR_AREA_GESTORA ";
		}
		if($this->TXT_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(TXT_ITEM_CONFIGURACAO) like '%".strtoupper($this->TXT_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->SEQ_TIPO_DISPONIBILIDADE != ""){
			$sqlCorpo .= "  and SEQ_TIPO_DISPONIBILIDADE = $this->SEQ_TIPO_DISPONIBILIDADE ";
		}
		if($this->SEQ_PRIORIDADE != ""){
			$sqlCorpo .= "  and SEQ_PRIORIDADE = $this->SEQ_PRIORIDADE ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
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

	function selectAlocacao($v_NUM_MATRICULA_RECURSO, $orderBy = "NOM_ITEM_CONFIGURACAO", $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT a.SEQ_ITEM_CONFIGURACAO , a.SEQ_TIPO_ITEM_CONFIGURACAO , a.SEQ_SERVICO , a.NUM_MATRICULA_GESTOR ,
							  a.NUM_MATRICULA_LIDER , a.SIG_ITEM_CONFIGURACAO , a.NOM_ITEM_CONFIGURACAO , a.COD_UOR_AREA_GESTORA ,
							  a.TXT_ITEM_CONFIGURACAO , a.SEQ_TIPO_DISPONIBILIDADE , a.SEQ_PRIORIDADE, b.QTD_HORA_ALOCADA, a.SEQ_EQUIPE_TI ";
		$sqlCorpo  = " FROM gestaoti.item_configuracao a, gestaoti.equipe_envolvida b
					   WHERE a.SEQ_ITEM_CONFIGURACAO = b.SEQ_ITEM_CONFIGURACAO
					   and b.NUM_MATRICULA_RECURSO = $v_NUM_MATRICULA_RECURSO
					 ";

		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_TIPO_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_TIPO_ITEM_CONFIGURACAO = $this->SEQ_TIPO_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_SERVICO != ""){
			$sqlCorpo .= "  and SEQ_SERVICO = $this->SEQ_SERVICO ";
		}
		if($this->NUM_MATRICULA_GESTOR != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_GESTOR = $this->NUM_MATRICULA_GESTOR ";
		}
		if($this->NUM_MATRICULA_LIDER != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_LIDER = $this->NUM_MATRICULA_LIDER ";
		}
		if($this->SIG_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(SIG_ITEM_CONFIGURACAO) like '%".strtoupper($this->SIG_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->NOM_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(NOM_ITEM_CONFIGURACAO) like '%".strtoupper($this->NOM_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->COD_UOR_AREA_GESTORA != ""){
			$sqlCorpo .= "  and COD_UOR_AREA_GESTORA = $this->COD_UOR_AREA_GESTORA ";
		}
		if($this->TXT_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(TXT_ITEM_CONFIGURACAO) like '%".strtoupper($this->TXT_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->SEQ_TIPO_DISPONIBILIDADE != ""){
			$sqlCorpo .= "  and SEQ_TIPO_DISPONIBILIDADE = $this->SEQ_TIPO_DISPONIBILIDADE ";
		}
		if($this->SEQ_PRIORIDADE != ""){
			$sqlCorpo .= "  and SEQ_PRIORIDADE = $this->SEQ_PRIORIDADE ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
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

	function selectAlocacaoCompleto($orderBy = "NOM_ITEM_CONFIGURACAO", $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT a.SEQ_ITEM_CONFIGURACAO, a.SEQ_TIPO_ITEM_CONFIGURACAO, a.SEQ_SERVICO, a.NUM_MATRICULA_GESTOR,
						  	  a.NUM_MATRICULA_LIDER, a.SIG_ITEM_CONFIGURACAO, a.NOM_ITEM_CONFIGURACAO, a.COD_UOR_AREA_GESTORA,
							  a.TXT_ITEM_CONFIGURACAO, a.SEQ_TIPO_DISPONIBILIDADE , a.SEQ_PRIORIDADE, b.LOTACAO, a.SEQ_EQUIPE_TI ";
		$sqlCorpo  = " FROM gestaoti.item_configuracao a, (select
								a.idpessoa as NUM_MATRICULA_RECURSO,
								e.dsunidade as LOTACAO
							  from sgrh.tbpessoa a, sgrh.tbvinculofuncional c, corp.tbunidade e
							  where c.idvinculofuncional= a.idvinculofuncionalatual
							  and e.idunidade= c.idunidadelotacao) b, item_configuracao_software c
							WHERE a.NUM_MATRICULA_LIDER = b.NUM_MATRICULA_RECURSO
							and a.SEQ_ITEM_CONFIGURACAO = c.SEQ_ITEM_CONFIGURACAO
							and c.SEQ_STATUS_SOFTWARE not in (9)
						WHERE 1=1 ";

		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_TIPO_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_TIPO_ITEM_CONFIGURACAO = $this->SEQ_TIPO_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_SERVICO != ""){
			$sqlCorpo .= "  and SEQ_SERVICO = $this->SEQ_SERVICO ";
		}
		if($this->NUM_MATRICULA_GESTOR != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_GESTOR = $this->NUM_MATRICULA_GESTOR ";
		}
		if($this->NUM_MATRICULA_LIDER != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_LIDER = $this->NUM_MATRICULA_LIDER ";
		}
		if($this->SIG_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(SIG_ITEM_CONFIGURACAO) like '%".strtoupper($this->SIG_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->NOM_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(NOM_ITEM_CONFIGURACAO) like '%".strtoupper($this->NOM_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->COD_UOR_AREA_GESTORA != ""){
			$sqlCorpo .= "  and COD_UOR_AREA_GESTORA = $this->COD_UOR_AREA_GESTORA ";
		}
		if($this->TXT_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(TXT_ITEM_CONFIGURACAO) like '%".strtoupper($this->TXT_ITEM_CONFIGURACAO)."%'  ";
		}
		if($this->SEQ_TIPO_DISPONIBILIDADE != ""){
			$sqlCorpo .= "  and SEQ_TIPO_DISPONIBILIDADE = $this->SEQ_TIPO_DISPONIBILIDADE ";
		}
		if($this->SEQ_PRIORIDADE != ""){
			$sqlCorpo .= "  and SEQ_PRIORIDADE = $this->SEQ_PRIORIDADE ";
		}
		if($this->UOR_SIGLA != ""){
			$sqlCorpo .= "  and upper(LOTACAO) like '%".strtoupper($this->UOR_SIGLA)."%' ";
		}
		if($this->COD_UOR_AREA_GESTORA != ""){
			$sqlCorpo .= "  and upper(COD_UOR_AREA_GESTORA) like '%".strtoupper($this->COD_UOR_AREA_GESTORA)."%' ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
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
		// Pegar a quantidade de registros da consulta
		$this->database->query("select count(1) " . $sqlCorpo);
		//print "select count(1) " . $sqlCorpo;
		$rowCount = pg_fetch_array($this->database->result);
		//print "row = ".$rowCount[0];
		$this->database->rows = $rowCount[0];
		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}

	function selectAreasAtuacao($v_UOR_SIGLA){
		$sql = "select NOM_AREA_ATUACAO as REGIONAL
				FROM gestaoti.area_atuacao
				order by NOM_AREA_ATUACAO ";
		//				AND b1.UOR_SIGLA = '$v_UOR_SIGLA'
		$this->database->query($sql);
	}

	function GetSistemasAfetadosPorServidor($v_SEQ_SERVIDOR){
		$sqlSelect = "select b.SEQ_ITEM_CONFIGURACAO, b.SIG_ITEM_CONFIGURACAO, b.NOM_ITEM_CONFIGURACAO, NUM_MATRICULA_LIDER, NUM_MATRICULA_GESTOR,
				             c.NOM_TIPO_RELAC_ITEM_CONFIG, b.COD_UOR_AREA_GESTORA ";
		$sqlCorpo = "FROM gestaoti.relac_item_configuracao a, gestaoti.item_configuracao b, gestaoti.tipo_relac_item_configuracao c
					 where a.SEQ_ITEM_CONFIGURACAO_PAI = b.SEQ_ITEM_CONFIGURACAO
					   and a.SEQ_TIPO_RELAC_ITEM_CONFIG = c.SEQ_TIPO_RELAC_ITEM_CONFIG
				       and a.seq_servidor = $v_SEQ_SERVIDOR";
		$this->database->query("select count(1) " . $sqlCorpo);
		$rowCount = pg_fetch_array($this->database->result);
		$this->setrowCount($rowCount[0]);
		$this->database->rows = $rowCount[0];
		$this->database->query($sqlSelect.$sqlCorpo);
	}

	function GetSistemasAfetadosPorSistema($v_SEQ_ITEM_CONFIGURACAO){
		$sqlSelect = "select b.SEQ_ITEM_CONFIGURACAO, b.SIG_ITEM_CONFIGURACAO, b.NOM_ITEM_CONFIGURACAO, NUM_MATRICULA_LIDER, NUM_MATRICULA_GESTOR,
				             c.NOM_TIPO_RELAC_ITEM_CONFIG, b.COD_UOR_AREA_GESTORA ";
		$sqlCorpo = "FROM gestaoti.relac_item_configuracao a, gestaoti.item_configuracao b, gestaoti.tipo_relac_item_configuracao c
					 where a.SEQ_ITEM_CONFIGURACAO_PAI = b.SEQ_ITEM_CONFIGURACAO
					   and a.SEQ_TIPO_RELAC_ITEM_CONFIG = c.SEQ_TIPO_RELAC_ITEM_CONFIG
					   and a.SEQ_SERVIDOR is null
					   and a.SEQ_ITEM_CONFIGURACAO_FILHO = $v_SEQ_ITEM_CONFIGURACAO";
		$this->database->query("select count(1) " . $sqlCorpo);
		$rowCount = pg_fetch_array($this->database->result);
		$this->setrowCount($rowCount[0]);
		$this->database->rows = $rowCount[0];
		$this->database->query($sqlSelect.$sqlCorpo);
	}

	function selectQuantidadeItensPorAreaAtuacao($v_SEQ_AREA_ATUACAO, $v_SEQ_PRIORIDADE, $v_UOR_SIGLA){
		$sql = "SELECT count(*) cont
				FROM gestaoti.item_configuracao a, ((select
								a.idpessoa as NUM_MATRICULA_RECURSO,
								e.dsunidade as LOTACAO
							  from sgrh.tbpessoa a, sgrh.tbvinculofuncional c, corp.tbunidade e
							  where c.idvinculofuncional= a.idvinculofuncionalatual
							  and e.idunidade= c.idunidadelotacao) b,
					 					  (select
								a.idpessoa as NUM_MATRICULA_RECURSO,
								e.dsunidade as LOTACAO
							  from sgrh.tbpessoa a, sgrh.tbvinculofuncional c, corp.tbunidade e
							  where c.idvinculofuncional= a.idvinculofuncionalatual
							  and e.idunidade= c.idunidadelotacao) b1,
					 (select  idunidade as COD_UOR,
						      iddivisao as UOR_COD_UOR,
						      dsunidade as UOR_NOME,
						      dsunidade as UOR_SIGLA,
						      iddiretoria as UOR_DEP_CODIGO
							 FROM corp.tbunidade) c, dependencia e, gestao.tiitem_configuracao_software f,
							 					gestaoti.recurso_ti g, gestaoti.area_atuacao h
				WHERE a.NUM_MATRICULA_GESTOR = b.NUM_MATRICULA_RECURSO
				and a.NUM_MATRICULA_LIDER = g.NUM_MATRICULA_RECURSO
				and g.SEQ_AREA_ATUACAO = h.SEQ_AREA_ATUACAO
				and a.NUM_MATRICULA_LIDER = b1.NUM_MATRICULA_RECURSO
				and b.LOTACAO = c.UOR_SIGLA
				and b.DEPENDENCIA = e.SG_DEPENDENCIA
				and e.CD_DEPENDENCIA = c.UOR_DEP_CODIGO
				and a.SEQ_ITEM_CONFIGURACAO = f.SEQ_ITEM_CONFIGURACAO
				and a.SEQ_PRIORIDADE = $v_SEQ_PRIORIDADE
				and h.SEQ_AREA_ATUACAO = $v_SEQ_AREA_ATUACAO
				AND b1.LOTACAO = '$v_UOR_SIGLA' ";
//		print $sql."<br><br>";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_array($result);
		return $row[0];
	}

	function selectQuantidadeItensPorCliente($v_NUM_MATRICULA_CLIENTE){
		$sql = "select count(1) as cont
				FROM gestaoti.item_configuracao
				where num_matricula_gestor = $v_NUM_MATRICULA_CLIENTE";
		//print $sql;
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_array($result);
		return $row[0];
	}

	// Pesquisar equipes priorizadas
	function getEquipesPriorizador($v_NUM_MATRICULA){
		$sqlSelect = "select distinct a.seq_equipe_ti, b.NOM_EQUIPE_TI ";
		$sqlCorpo = "FROM gestaoti.item_configuracao a, gestaoti.equipe_ti b
			      	 where a.seq_equipe_ti = b.seq_equipe_ti
			      	 and b.NUM_MATRICULA_PRIORIZADOR = $v_NUM_MATRICULA ";
		$sqlOrder = " order by 2";
		//print $sql."<br><br>";
		$this->database->query("select count(1) " . $sqlCorpo);
		$rowCount = pg_fetch_array($this->database->result);
		$this->setrowCount($rowCount[0]);
		$this->database->rows = $rowCount[0];
		$this->database->query($sqlSelect.$sqlCorpo.$sqlOrder);
	}
	function getEquipesGestor($v_NUM_MATRICULA){
		$sqlSelect = "select distinct a.seq_equipe_ti, b.NOM_EQUIPE_TI ";
		$sqlCorpo  = "FROM gestaoti.item_configuracao a, gestaoti.equipe_ti b
			          where a.SEQ_EQUIPE_TI = b.SEQ_EQUIPE_TI
			      	  and NUM_MATRICULA_GESTOR = $v_NUM_MATRICULA ";
		$sqlOrder = " order by 2";
		//print $sql."<br><br>";
		$this->database->query("select count(1) " . $sqlCorpo);
		$rowCount = pg_fetch_array($this->database->result);
		$this->setrowCount($rowCount[0]);
		$this->database->rows = $rowCount[0];
		$this->database->query($sqlSelect.$sqlCorpo.$sqlOrder);
	}
	function getEquipesGestorPriorizador($v_NUM_MATRICULA){
		$sqlSelect = " select * ";
		$sqlCorpo  = "from(  select distinct SEQ_EQUIPE_TI, NOM_EQUIPE_TI
					   FROM (
							      select distinct a.seq_equipe_ti, b.NOM_EQUIPE_TI
							      FROM gestaoti.item_configuracao a, gestaoti.equipe_ti b
							      where a.seq_equipe_ti = b.seq_equipe_ti
							      and b.NUM_MATRICULA_PRIORIZADOR = $v_NUM_MATRICULA
							      union all
							      select distinct a.seq_equipe_ti, b.NOM_EQUIPE_TI
							      FROM gestaoti.item_configuracao a, equipe_ti b
							      where a.SEQ_EQUIPE_TI = b.SEQ_EQUIPE_TI
							      and NUM_MATRICULA_GESTOR = $v_NUM_MATRICULA
							)
							order by 2
						) ";
		//print $sql."<br><br>";
		$this->database->query("select count(1) " . $sqlCorpo);
		$rowCount = pg_fetch_array($this->database->result);
		$this->setrowCount($rowCount[0]);
		$this->database->rows = $rowCount[0];
		$this->database->query($sqlSelect.$sqlCorpo);
	}

	// **********************
	// DELETE
	// **********************
	function delete($id){
		$sql = "DELETE FROM gestaoti.item_configuracao WHERE SEQ_ITEM_CONFIGURACAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_ITEM_CONFIGURACAO = $this->database->GetSequenceValue("gestaoti.SEQ_ITEM_CONFIGURACAO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.item_configuracao (
				 SEQ_ITEM_CONFIGURACAO,
				 SEQ_TIPO_ITEM_CONFIGURACAO,
				 SEQ_SERVICO,
				 NUM_MATRICULA_GESTOR,
				 NUM_MATRICULA_LIDER,
				 SIG_ITEM_CONFIGURACAO,
				 NOM_ITEM_CONFIGURACAO,
				 COD_UOR_AREA_GESTORA,
				 TXT_ITEM_CONFIGURACAO,
				 SEQ_TIPO_DISPONIBILIDADE,
				 SEQ_PRIORIDADE,
				 SEQ_CRITICIDADE,
				 SEQ_EQUIPE_TI)
				 VALUES ( ".$this->SEQ_ITEM_CONFIGURACAO.",
				 		  ".$this->database->iif($this->SEQ_TIPO_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_TIPO_ITEM_CONFIGURACAO."'").",
				 		  ".$this->database->iif($this->SEQ_SERVICO=="", "NULL", "'".$this->SEQ_SERVICO."'").",
						  ".$this->database->iif($this->NUM_MATRICULA_GESTOR=="", "NULL", "'".$this->NUM_MATRICULA_GESTOR."'").",
						  ".$this->database->iif($this->NUM_MATRICULA_LIDER=="", "NULL", "'".$this->NUM_MATRICULA_LIDER."'").",
						  ".$this->database->iif($this->SIG_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SIG_ITEM_CONFIGURACAO."'").",
						  ".$this->database->iif($this->NOM_ITEM_CONFIGURACAO=="", "NULL", "'".$this->NOM_ITEM_CONFIGURACAO."'").",
						  ".$this->database->iif($this->COD_UOR_AREA_GESTORA=="", "NULL", "'".$this->COD_UOR_AREA_GESTORA."'").",
						  ".$this->database->iif($this->TXT_ITEM_CONFIGURACAO=="", "NULL", "'".$this->TXT_ITEM_CONFIGURACAO."'").",
						  ".$this->database->iif($this->SEQ_TIPO_DISPONIBILIDADE=="", "NULL", "'".$this->SEQ_TIPO_DISPONIBILIDADE."'").",
						  ".$this->database->iif($this->SEQ_PRIORIDADE=="", "NULL", "'".$this->SEQ_PRIORIDADE."'").",
						  ".$this->database->iif($this->SEQ_CRITICIDADE=="", "NULL", "'".$this->SEQ_CRITICIDADE."'").",
						  ".$this->database->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'")."
						)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.item_configuracao SET
					SEQ_TIPO_ITEM_CONFIGURACAO = ".$this->database->iif($this->SEQ_TIPO_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_TIPO_ITEM_CONFIGURACAO."'").",
					SEQ_SERVICO = ".$this->database->iif($this->SEQ_SERVICO=="", "NULL", "'".$this->SEQ_SERVICO."'").",
					NUM_MATRICULA_GESTOR = ".$this->database->iif($this->NUM_MATRICULA_GESTOR=="", "NULL", "'".$this->NUM_MATRICULA_GESTOR."'").",
					NUM_MATRICULA_LIDER = ".$this->database->iif($this->NUM_MATRICULA_LIDER=="", "NULL", "'".$this->NUM_MATRICULA_LIDER."'").",
					SIG_ITEM_CONFIGURACAO = ".$this->database->iif($this->SIG_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SIG_ITEM_CONFIGURACAO."'").",
					NOM_ITEM_CONFIGURACAO = ".$this->database->iif($this->NOM_ITEM_CONFIGURACAO=="", "NULL", "'".$this->NOM_ITEM_CONFIGURACAO."'").",
					COD_UOR_AREA_GESTORA = ".$this->database->iif($this->COD_UOR_AREA_GESTORA=="", "NULL", "'".$this->COD_UOR_AREA_GESTORA."'").",
					TXT_ITEM_CONFIGURACAO = ".$this->database->iif($this->TXT_ITEM_CONFIGURACAO=="", "NULL", "'".$this->TXT_ITEM_CONFIGURACAO."'").",
					SEQ_TIPO_DISPONIBILIDADE = ".$this->database->iif($this->SEQ_TIPO_DISPONIBILIDADE=="", "NULL", "'".$this->SEQ_TIPO_DISPONIBILIDADE."'").",
					SEQ_PRIORIDADE = ".$this->database->iif($this->SEQ_PRIORIDADE=="", "NULL", "'".$this->SEQ_PRIORIDADE."'").",
					SEQ_CRITICIDADE = ".$this->database->iif($this->SEQ_CRITICIDADE=="", "NULL", "'".$this->SEQ_CRITICIDADE."'").",
					SEQ_EQUIPE_TI = ".$this->database->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'")."
					WHERE SEQ_ITEM_CONFIGURACAO = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row["seq_item_configuracao"], $this->iif($vSelected == $row["seq_item_configuracao"],"Selected", ""), $row["sig_item_configuracao"]." - ".$row["nom_item_configuracao"]);
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