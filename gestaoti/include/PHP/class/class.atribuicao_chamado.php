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
* Nome da Classe:	atribuicao_chamado
* Nome da tabela:	atribuicao_chamado
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
	include_once("../gestaoti/include/PHP/class/class.situacao_chamado.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
	include_once("include/PHP/class/class.situacao_chamado.php");
}

// **********************
// DECLARA��O DA CLASSE
// **********************
class atribuicao_chamado{
	// class : begin

	// ***********************
	// DECLARA��O DE ATRIBUTOS
	// ***********************
	var $SEQ_ATRIBUICAO_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $SEQ_CHAMADO;   // (normal Attribute)
	var $SEQ_EQUIPE_TI;   // (normal Attribute)
	var $SEQ_SITUACAO_CHAMADO;   // (normal Attribute)
	var $NUM_MATRICULA;   // (normal Attribute)
	var $TXT_ATIVIDADE;   // (normal Attribute)
	var $DTH_ATRIBUICAO;   // (normal Attribute)
	var $DTH_INICIO_EFETIVO;
	var $DTH_ENCERRAMENTO_EFETIVO;
	var $SEQ_EQUIPE_ATRIBUICAO;
	var $situacao_chamado;

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function atribuicao_chamado(){
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

	function getSEQ_ATRIBUICAO_CHAMADO(){
		return $this->SEQ_ATRIBUICAO_CHAMADO;
	}

	function getSEQ_CHAMADO(){
		return $this->SEQ_CHAMADO;
	}

	function getSEQ_EQUIPE_TI(){
		return $this->SEQ_EQUIPE_TI;
	}

	function getSEQ_SITUACAO_CHAMADO(){
		return $this->SEQ_SITUACAO_CHAMADO;
	}

	function getNUM_MATRICULA(){
		return $this->NUM_MATRICULA;
	}

	function getTXT_ATIVIDADE(){
		return $this->TXT_ATIVIDADE;
	}

	function getDTH_ATRIBUICAO(){
		return $this->DTH_ATRIBUICAO;
	}

	function getDTH_INICIO_EFETIVO(){
		return $this->DTH_INICIO_EFETIVO;
	}

	function getDTH_ENCERRAMENTO_EFETIVO(){
		return $this->DTH_ENCERRAMENTO_EFETIVO;
	}
	function getSEQ_EQUIPE_ATRIBUICAO(){
		return $this->SEQ_EQUIPE_ATRIBUICAO;
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

	function setSEQ_ATRIBUICAO_CHAMADO($val){
		$this->SEQ_ATRIBUICAO_CHAMADO =  $val;
	}

	function setSEQ_CHAMADO($val){
		$this->SEQ_CHAMADO =  $val;
	}

	function setSEQ_EQUIPE_TI($val){
		$this->SEQ_EQUIPE_TI =  $val;
	}

	function setSEQ_SITUACAO_CHAMADO($val){
		$this->SEQ_SITUACAO_CHAMADO =  $val;
	}

	function setNUM_MATRICULA($val){
		$this->NUM_MATRICULA =  $val;
	}

	function setTXT_ATIVIDADE($val){
		$this->TXT_ATIVIDADE =  $val;
	}

	function setDTH_ATRIBUICAO($val){
		$this->DTH_ATRIBUICAO =  $val;
	}

	function setDTH_INICIO_EFETIVO($val){
		$this->DTH_INICIO_EFETIVO = $val;
	}

	function setDTH_ENCERRAMENTO_EFETIVO($val){
		$this->DTH_ENCERRAMENTO_EFETIVO = $val;
	}

	function setSEQ_EQUIPE_ATRIBUICAO($val){
		$this->SEQ_EQUIPE_ATRIBUICAO = $val;
	}
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_ATRIBUICAO_CHAMADO, SEQ_CHAMADO, SEQ_EQUIPE_TI, SEQ_SITUACAO_CHAMADO, NUM_MATRICULA, TXT_ATIVIDADE,
					   to_char(DTH_ATRIBUICAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ATRIBUICAO, DTH_ATRIBUICAO as DTH_ATRIBUICAO_DATA,
					   to_char(DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO, DTH_INICIO_EFETIVO as DTH_INICIO_EFETIVO_DATA,
					   to_char(DTH_ENCERRAMENTO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ENCERRAMENTO_EFETIVO, DTH_ENCERRAMENTO_EFETIVO as DTH_ENCERRAMENTO_EFETIVO_DATA,
					   SEQ_EQUIPE_ATRIBUICAO
			    FROM gestaoti.atribuicao_chamado
				WHERE SEQ_ATRIBUICAO_CHAMADO = $id";
		//print $sql;
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_ATRIBUICAO_CHAMADO = $row->seq_atribuicao_chamado;
		$this->SEQ_CHAMADO = $row->seq_chamado;
		$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
		$this->SEQ_SITUACAO_CHAMADO = $row->seq_situacao_chamado;
		$this->NUM_MATRICULA = $row->num_matricula;
		$this->TXT_ATIVIDADE = $row->txt_atividade;
		$this->DTH_ATRIBUICAO = $row->dth_atribuicao;
		$this->DTH_INICIO_EFETIVO = $row->dth_inicio_efetivo;
		$this->DTH_ENCERRAMENTO_EFETIVO = $row->dth_encerramento_efetivo;
		$this->SEQ_EQUIPE_ATRIBUICAO = $row->seq_equipe_atribuicao;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function selectMatricula(){
		// Buscar apenas atribui��es n�o encerradas
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_EXEC = $this->situacao_chamado->CODS_EM_ANDAMENTO;

		$sql = "SELECT SEQ_ATRIBUICAO_CHAMADO, SEQ_CHAMADO, SEQ_EQUIPE_TI, SEQ_SITUACAO_CHAMADO, NUM_MATRICULA, TXT_ATIVIDADE,
					   to_char(DTH_ATRIBUICAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ATRIBUICAO, DTH_ATRIBUICAO as DTH_ATRIBUICAO_DATA,
					   to_char(DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO, DTH_INICIO_EFETIVO as DTH_INICIO_EFETIVO_DATA,
					   to_char(DTH_ENCERRAMENTO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ENCERRAMENTO_EFETIVO, DTH_ENCERRAMENTO_EFETIVO as DTH_ENCERRAMENTO_EFETIVO_DATA,
					   SEQ_EQUIPE_ATRIBUICAO
			    FROM gestaoti.atribuicao_chamado
				WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
				and   NUM_MATRICULA = ".$this->NUM_MATRICULA."
				and   SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC) ";
		
		$result = $this->database->query($sql);
		$result = $this->database->result;
		
		while ($row = pg_fetch_object($this->database->result)){			
			//$row = pg_fetch_object($result, 0);
			$this->SEQ_ATRIBUICAO_CHAMADO = $row->seq_atribuicao_chamado;
			$this->SEQ_CHAMADO = $row->seq_chamado;
			$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
			$this->SEQ_SITUACAO_CHAMADO = $row->seq_situacao_chamado;
			$this->NUM_MATRICULA = $row->num_matricula;
			$this->TXT_ATIVIDADE = $row->txt_atividade;
			$this->DTH_ATRIBUICAO = $row->dth_atribuicao;
			$this->DTH_INICIO_EFETIVO = $row->dth_inicio_efetivo;
			$this->DTH_ENCERRAMENTO_EFETIVO = $row->dth_encerramento_efetivo;
			$this->SEQ_EQUIPE_ATRIBUICAO = $row->seq_equipe_atribuicao;
		}
	
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " select * ";
		$sqlCorpo  = " FROM (
							SELECT a.SEQ_ATRIBUICAO_CHAMADO, a.SEQ_CHAMADO, a.SEQ_EQUIPE_TI ,a.SEQ_SITUACAO_CHAMADO, a.NUM_MATRICULA,
								   a.TXT_ATIVIDADE, b.DSC_SITUACAO_CHAMADO, c.NOM_COLABORADOR, d.NOM_EQUIPE_TI,
								   to_char(DTH_ATRIBUICAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ATRIBUICAO, DTH_ATRIBUICAO as DTH_ATRIBUICAO_DATA,
								   to_char(DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO, DTH_INICIO_EFETIVO as DTH_INICIO_EFETIVO_DATA,
								   to_char(DTH_ENCERRAMENTO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ENCERRAMENTO_EFETIVO, DTH_ENCERRAMENTO_EFETIVO as DTH_ENCERRAMENTO_EFETIVO_DATA,
								   e.SEQ_EQUIPE_ATRIBUICAO, e.DSC_EQUIPE_ATRIBUICAO
							FROM gestaoti.atribuicao_chamado a LEFT OUTER JOIN gestaoti.viw_colaborador c ON (a.NUM_MATRICULA = c.NUM_MATRICULA_COLABORADOR)
									LEFT OUTER JOIN gestaoti.equipe_atribuicao e ON (a.SEQ_EQUIPE_ATRIBUICAO = e.SEQ_EQUIPE_ATRIBUICAO),
								    gestaoti.situacao_chamado b, gestaoti.equipe_ti d
							where a.SEQ_SITUACAO_CHAMADO = b.SEQ_SITUACAO_CHAMADO
							and a.SEQ_EQUIPE_TI = d.SEQ_EQUIPE_TI
							";
		if($orderBy != "" ){
			$sqlCorpo .= " order by $orderBy ";
		}
		$sqlCorpo .= ") PAGING
						WHERE 1=1 ";

		if($this->SEQ_ATRIBUICAO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_ATRIBUICAO_CHAMADO = $this->SEQ_ATRIBUICAO_CHAMADO ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
		}
		if($this->SEQ_SITUACAO_CHAMADO == "CODS_EM_ANDAMENTO"){
			$this->situacao_chamado = new situacao_chamado();
			$v_SEQ_SITUACAO_CHAMADO_EXEC = $this->situacao_chamado->CODS_EM_ANDAMENTO;
			$sqlCorpo .= "  and SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC) ";
		}elseif($this->SEQ_SITUACAO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SITUACAO_CHAMADO = $this->SEQ_SITUACAO_CHAMADO ";
		}
		if($this->SEQ_EQUIPE_ATRIBUICAO != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_ATRIBUICAO = $this->SEQ_EQUIPE_ATRIBUICAO ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
		}
		if($this->TXT_ATIVIDADE != ""){
			$sqlCorpo .= "  and upper(TXT_ATIVIDADE) like '%".strtoupper($this->TXT_ATIVIDADE)."%'  ";
		}
		if($this->DTH_ATRIBUICAO != "" && $this->DTH_ATRIBUICAO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ATRIBUICAO >= to_date('".$this->DTH_ATRIBUICAO."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ATRIBUICAO != "" && $this->DTH_ATRIBUICAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ATRIBUICAO between to_date('".$this->DTH_ATRIBUICAO."', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_ATRIBUICAO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ATRIBUICAO == "" && $this->DTH_ATRIBUICAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ATRIBUICAO <= to_date('".$this->DTH_ATRIBUICAO_FINAL."', 'dd/mm/yyyy hh24:mi:ss') ";
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
		$sql = "DELETE FROM gestaoti.atribuicao_chamado WHERE SEQ_ATRIBUICAO_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_ATRIBUICAO_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_ATRIBUICAO_CHAMADO");

		$sql = "INSERT INTO gestaoti.atribuicao_chamado(SEQ_ATRIBUICAO_CHAMADO,
										  SEQ_CHAMADO,
										  SEQ_EQUIPE_TI,
										  SEQ_SITUACAO_CHAMADO,
										  NUM_MATRICULA,
										  TXT_ATIVIDADE,
										  DTH_ATRIBUICAO,
										  SEQ_EQUIPE_ATRIBUICAO
									)
							 VALUES (".$this->iif($this->SEQ_ATRIBUICAO_CHAMADO=="", "NULL", "'".$this->SEQ_ATRIBUICAO_CHAMADO."'").",
									 ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
									 ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
									 ".$this->iif($this->SEQ_SITUACAO_CHAMADO=="", "NULL", "'".$this->SEQ_SITUACAO_CHAMADO."'").",
									 ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
									 ".$this->iif($this->TXT_ATIVIDADE=="", "NULL", "'".$this->TXT_ATIVIDADE."'").",
									 '".date("Y-m-d H:i:s")."',
									 ".$this->iif($this->SEQ_EQUIPE_ATRIBUICAO=="", "NULL", "'".$this->SEQ_EQUIPE_ATRIBUICAO."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.atribuicao_chamado
				 SET SEQ_CHAMADO = ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
					 SEQ_EQUIPE_TI = ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
					 SEQ_SITUACAO_CHAMADO = ".$this->iif($this->SEQ_SITUACAO_CHAMADO=="", "NULL", "'".$this->SEQ_SITUACAO_CHAMADO."'").",
					 NUM_MATRICULA = ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
					 TXT_ATIVIDADE = ".$this->iif($this->TXT_ATIVIDADE=="", "NULL", "'".$this->TXT_ATIVIDADE."'").",
					 DTH_ATRIBUICAO = ".$this->iif($this->DTH_ATRIBUICAO=="", "NULL", "to_date('".$this->DTH_ATRIBUICAO."', 'dd/mm/yyyy hh24:mi:ss')").",
					 SEQ_EQUIPE_ATRIBUICAO = ".$this->iif($this->SEQ_EQUIPE_ATRIBUICAO=="", "NULL", "'".$this->SEQ_EQUIPE_ATRIBUICAO."'")."
				WHERE SEQ_ATRIBUICAO_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}

	// ************************************************
	// ATUALIZAR SITUA��O DAS ATRIBUI��ES DE UM CHAMADO
	// ************************************************
	function AtualizarSituacao(){
		// N�o atualizar
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_ENCERRADO = $this->situacao_chamado->COD_Encerrada.", ".$this->situacao_chamado->COD_Cancelado;

		if($this->NUM_MATRICULA == ""){
			$sql = " UPDATE gestaoti.atribuicao_chamado
					 SET SEQ_SITUACAO_CHAMADO = ".$this->SEQ_SITUACAO_CHAMADO."
					 WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
					 AND SEQ_SITUACAO_CHAMADO not in ($v_SEQ_SITUACAO_CHAMADO_ENCERRADO) ";
		}else{
			$sql = " UPDATE gestaoti.atribuicao_chamado
					 SET SEQ_SITUACAO_CHAMADO = ".$this->SEQ_SITUACAO_CHAMADO."
					 WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
					 and NUM_MATRICULA = ".$this->NUM_MATRICULA."
					 AND SEQ_SITUACAO_CHAMADO not in ($v_SEQ_SITUACAO_CHAMADO_ENCERRADO) ";
		}
		if($this->SEQ_ATRIBUICAO_CHAMADO != ""){
			$sql .= " and SEQ_ATRIBUICAO_CHAMADO =  ".$this->SEQ_ATRIBUICAO_CHAMADO;
		}
		$result = $this->database->query($sql);
	}

	function ReabrirChamado(){
		$database = new Database();
		$this->situacao_chamado = new situacao_chamado();
		// Pesquisar as atribui��es do chamado
		$sql = "select SEQ_CHAMADO, SEQ_EQUIPE_TI, SEQ_SITUACAO_CHAMADO, NUM_MATRICULA, TXT_ATIVIDADE, DTH_ATRIBUICAO, SEQ_EQUIPE_ATRIBUICAO
				from gestaoti.atribuicao_chamado
				WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
				";
		$result = $database->query($sql);
		while ($row = pg_fetch_array($database->result)){
			if($row["seq_situacao_chamado"] == $this->situacao_chamado->COD_Encerrada || $row["seq_situacao_chamado"] == $this->situacao_chamado->COD_Cancelado){
				// Se a atribui��o estiver encerrada, Inserir nova igual
				$this->SEQ_EQUIPE_TI = $row["seq_equipe_ti"];
				$this->SEQ_SITUACAO_CHAMADO = $this->situacao_chamado->COD_Aguardando_Atendimento;
				$this->NUM_MATRICULA = $row["num_matricula"];
				$this->TXT_ATIVIDADE = "Chamado reaberto pelo cliente. Atividade original:".$row["txt_atividade"];
				$this->SEQ_EQUIPE_ATRIBUICAO = $row["seq_equipe_atribuicao"];
				$this->insert();
			}else{ // sen�o, atualizar o encerramento efetivo e a situa��o
				if($this->NUM_MATRICULA == ""){
					$sql = " UPDATE gestaoti.atribuicao_chamado
							 SET SEQ_SITUACAO_CHAMADO = ".$this->SEQ_SITUACAO_CHAMADO.",
							 	 DTH_ENCERRAMENTO_EFETIVO = NULL
							 WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
						   ";
				}else{
					$sql = " UPDATE gestaoti.atribuicao_chamado
							 SET SEQ_SITUACAO_CHAMADO = ".$this->SEQ_SITUACAO_CHAMADO.",
							 	 DTH_ENCERRAMENTO_EFETIVO = NULL
							 WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
							 and NUM_MATRICULA = ".$this->NUM_MATRICULA."
						   ";
				}
				if($this->SEQ_ATRIBUICAO_CHAMADO != ""){
					$sql .= " and SEQ_ATRIBUICAO_CHAMADO =  ".$this->SEQ_ATRIBUICAO_CHAMADO;
				}
				$result = $this->database->query($sql);
			}
		}
	}


	// ********************
	// AnalisarEncerramento
	// ********************
	function AnalisarEncerramento(){
		// Buscar apenas atribui��es n�o encerradas
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_EXEC = $this->situacao_chamado->CODS_EM_ANDAMENTO;

		$sql = "select count(1) as RETORNO
				FROM gestaoti.atribuicao_chamado
				where SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
				and SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC) ";
		//print $sql;
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		if($row->retorno > 0){ // Existem outras equipes/profissionais em atendimento
			return 1;
		}else{ // N�o existem outras equipes/profissionais em atendimento
			return 0;
		}
	}

	// ***************************
	// AnalisarEncerramentoEquipe
	// ***************************
	function AnalisarEncerramentoEquipe(){
		// Buscar se existem atribui��es da equipe em aberto
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_EXEC = $this->situacao_chamado->CODS_EM_ANDAMENTO;

		$sql = "select count(1) as RETORNO
				FROM gestaoti.atribuicao_chamado
				where SEQ_CHAMADO        =  ".$this->SEQ_CHAMADO."
				and   SEQ_EQUIPE_TI      =  ".$this->SEQ_EQUIPE_TI."
				and   NUM_MATRICULA      <> ".$this->NUM_MATRICULA."
				and SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC) ";
		//print $sql;
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		if($row->retorno > 0){ // Existem outras equipes/profissionais em atendimento
			return 1;
		}else{ // N�o existem outras equipes/profissionais em atendimento
			return 0;
		}
	}

	// ***************************
	// AnalisarEncerramentoEquipe
	// ***************************
	function SelectEncerramentoEquipe(){
		// Buscar se existem atribui��es da equipe em aberto
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_EXEC = $this->situacao_chamado->CODS_EM_ANDAMENTO;

		$sql = "select SEQ_ATRIBUICAO_CHAMADO, SEQ_CHAMADO, SEQ_EQUIPE_TI ,SEQ_SITUACAO_CHAMADO, NUM_MATRICULA,
					   TXT_ATIVIDADE, DTH_INICIO_EFETIVO
				FROM gestaoti.atribuicao_chamado
				where SEQ_CHAMADO        =  ".$this->SEQ_CHAMADO."
				and   SEQ_EQUIPE_TI      =  ".$this->SEQ_EQUIPE_TI."
				and   NUM_MATRICULA      <> ".$this->NUM_MATRICULA."
				and SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC) ";
		//print $sql;
		$this->database->query($sql);
	}

	// ***********************************************
	// UPDATE de ATUALIZA��O DA DATA EFETIVA DE IN�CIO
	// ***********************************************
	function AtualizaDTH_INICIO_EFETIVO(){
		$sql = " UPDATE gestaoti.atribuicao_chamado
				 SET DTH_INICIO_EFETIVO = '".date("Y-m-d H:i:s")."'
				WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
				 and  NUM_MATRICULA = ".$this->NUM_MATRICULA;
		if($this->SEQ_ATRIBUICAO_CHAMADO != ""){
			$sql .= " and SEQ_ATRIBUICAO_CHAMADO =  ".$this->SEQ_ATRIBUICAO_CHAMADO;
		}
		$result = $this->database->query($sql);
	}

	function AtualizaDTH_INICIO_EFETIVO_CANCELAMENTO(){
		$sql = " UPDATE gestaoti.atribuicao_chamado
				 SET DTH_INICIO_EFETIVO = '".date("Y-m-d H:i:s")."'
				WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
				 and  DTH_INICIO_EFETIVO is null";
		$result = $this->database->query($sql);
	}

	// ***********************************************
	// UPDATE de ATUALIZA��O DA DATA EFETIVA DE IN�CIO
	// ***********************************************
	function AtualizaDTH_INICIO_EFETIVO_TRIAGEM($v_DTH_INICIO_EFETIVO){
		$sql = " UPDATE gestaoti.atribuicao_chamado
				 SET DTH_INICIO_EFETIVO = '".$v_DTH_INICIO_EFETIVO."'
				WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
				 and  NUM_MATRICULA = ".$this->NUM_MATRICULA;
		if($this->SEQ_ATRIBUICAO_CHAMADO != ""){
			$sql .= " and SEQ_ATRIBUICAO_CHAMADO =  ".$this->SEQ_ATRIBUICAO_CHAMADO;
		}
		$result = $this->database->query($sql);
	}

	// ***********************************************
	// UPDATE de ATUALIZA��O DA DATA EFETIVA DE IN�CIO
	// ***********************************************
	function AtualizaDTH_ENCERRAMENTO_EFETIVO(){
		if($this->NUM_MATRICULA != ""){
			$sql = " UPDATE gestaoti.atribuicao_chamado
					 SET DTH_ENCERRAMENTO_EFETIVO = '".date("Y-m-d H:i:s")."'
					 WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
					 and NUM_MATRICULA = ".$this->NUM_MATRICULA;
		}else{
			$sql = " UPDATE gestaoti.atribuicao_chamado
					 SET DTH_ENCERRAMENTO_EFETIVO = '".date("Y-m-d H:i:s")."'
					 WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO;
		}
		if($this->SEQ_ATRIBUICAO_CHAMADO != ""){
			$sql .= " and SEQ_ATRIBUICAO_CHAMADO =  ".$this->SEQ_ATRIBUICAO_CHAMADO;
		}
		$result = $this->database->query($sql);
	}

	// ******************
	// PesquisaAtribuicao
	// ******************
	function PesquisaAtribuicao(){
		// Buscar apenas atribui��es n�o encerradas
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_EXEC = $this->situacao_chamado->CODS_EM_ANDAMENTO.",".$this->situacao_chamado->COD_Aguardando_Planejamento;

		if($this->NUM_MATRICULA != ""){
			// Pesquisar atribui��o do chamado para o profissional
			$sql = "SELECT a.SEQ_ATRIBUICAO_CHAMADO, a.SEQ_CHAMADO, a.SEQ_EQUIPE_TI, a.SEQ_SITUACAO_CHAMADO, a.NUM_MATRICULA, a.TXT_ATIVIDADE,
					       to_char(a.DTH_ATRIBUICAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ATRIBUICAO, a.DTH_ATRIBUICAO as DTH_ATRIBUICAO_DATA,
					       b.DSC_EQUIPE_ATRIBUICAO, b.SEQ_EQUIPE_ATRIBUICAO
					FROM gestaoti.atribuicao_chamado a LEFT OUTER JOIN gestaoti.equipe_atribuicao b
						ON (a.SEQ_EQUIPE_ATRIBUICAO = b.SEQ_EQUIPE_ATRIBUICAO)
					WHERE a.SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
					and a.NUM_MATRICULA = ".$this->NUM_MATRICULA."
					and a.SEQ_EQUIPE_TI = ".$this->SEQ_EQUIPE_TI."
					and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC) ";
			$result = $this->database->query($sql);
			$result = $this->database->result;
			if($this->database->rows > 0){
				$row = pg_fetch_object($result, 0);
				$this->SEQ_ATRIBUICAO_CHAMADO = $row->seq_atribuicao_chamado;
				$this->SEQ_CHAMADO = $row->seq_chamado;
				$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
				$this->SEQ_SITUACAO_CHAMADO = $row->seq_situacao_chamado;
				$this->NUM_MATRICULA = $row->num_matricula;
				$this->TXT_ATIVIDADE = $row->txt_atividade;
				$this->DTH_ATRIBUICAO = $row->dth_atribuicao;
				$this->SEQ_EQUIPE_ATRIBUICAO = $row->seq_equipe_atribuicao;
				$this->DSC_EQUIPE_ATRIBUICAO = $row->dsc_equipe_atribuicao;
			}else{ // Atribui��o para o profissional n�o encontrada
				// Pesquisar atribui��o da equipe
				$sql = "SELECT a.SEQ_ATRIBUICAO_CHAMADO, a.SEQ_CHAMADO, a.SEQ_EQUIPE_TI, a.SEQ_SITUACAO_CHAMADO, a.NUM_MATRICULA, a.TXT_ATIVIDADE,
						       to_char(a.DTH_ATRIBUICAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ATRIBUICAO, a.DTH_ATRIBUICAO as DTH_ATRIBUICAO_DATA,
						       b.DSC_EQUIPE_ATRIBUICAO, b.SEQ_EQUIPE_ATRIBUICAO
						FROM gestaoti.atribuicao_chamado a LEFT OUTER JOIN gestaoti.equipe_atribuicao b ON (a.SEQ_EQUIPE_ATRIBUICAO = b.SEQ_EQUIPE_ATRIBUICAO)
						WHERE a.SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
						and a.SEQ_EQUIPE_TI = ".$this->SEQ_EQUIPE_TI."
						and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC) ";
				$result = $this->database->query($sql);
				$result = $this->database->result;
				if($this->database->rows > 0){
					$row = pg_fetch_object($result, 0);
					$this->SEQ_ATRIBUICAO_CHAMADO = $row->seq_atribuicao_chamado;
					$this->SEQ_CHAMADO = $row->seq_chamado;
					$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
					$this->SEQ_SITUACAO_CHAMADO = $row->seq_situacao_chamado;
					$this->NUM_MATRICULA = $row->num_matricula;
					$this->TXT_ATIVIDADE = $row->txt_atividade;
					$this->DTH_ATRIBUICAO = $row->dth_atribuicao;
					$this->SEQ_EQUIPE_ATRIBUICAO = ""; //$row->SEQ_EQUIPE_ATRIBUICAO;
					$this->DSC_EQUIPE_ATRIBUICAO = ""; //$row->DSC_EQUIPE_ATRIBUICAO;
				}else{ // Se n�o encontrado - Retornar NULO
					// Atendimento n�o pode ser realizado pois a tribui��o n�o foi feita � equipe e ao profissional
					$this->SEQ_ATRIBUICAO_CHAMADO = "";
					$this->SEQ_CHAMADO = "";
					$this->SEQ_EQUIPE_TI = "";
					$this->SEQ_SITUACAO_CHAMADO = "";
					$this->NUM_MATRICULA = "";
					$this->TXT_ATIVIDADE = "";
					$this->DTH_ATRIBUICAO = "";
					$this->SEQ_EQUIPE_ATRIBUICAO = "";
					$this->DSC_EQUIPE_ATRIBUICAO = "";
				}
			}
		}else{
			// Pesquisar atribui��o da equipe
			$sql = "SELECT a.SEQ_ATRIBUICAO_CHAMADO, a.SEQ_CHAMADO, a.SEQ_EQUIPE_TI, a.SEQ_SITUACAO_CHAMADO, a.NUM_MATRICULA, a.TXT_ATIVIDADE,
					       to_char(a.DTH_ATRIBUICAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ATRIBUICAO, a.DTH_ATRIBUICAO as DTH_ATRIBUICAO_DATA,
					       b.DSC_EQUIPE_ATRIBUICAO, b.SEQ_EQUIPE_ATRIBUICAO
					FROM gestaoti.atribuicao_chamado a LEFT OUTER JOIN gestaoti.equipe_atribuicao b ON (a.SEQ_EQUIPE_ATRIBUICAO = b.SEQ_EQUIPE_ATRIBUICAO)
					where a.SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
					and a.SEQ_EQUIPE_TI = ".$this->SEQ_EQUIPE_TI."
					and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC) ";
			$result = $this->database->query($sql);
			$result = $this->database->result;
			if($this->database->rows > 0){
				$row = pg_fetch_object($result, 0);
				$this->SEQ_ATRIBUICAO_CHAMADO = $row->seq_atribuicao_chamado;
				$this->SEQ_CHAMADO = $row->seq_chamado;
				$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
				$this->SEQ_SITUACAO_CHAMADO = $row->seq_situacao_chamado;
				$this->NUM_MATRICULA = $row->num_matricula;
				$this->TXT_ATIVIDADE = $row->txt_atividade;
				$this->DTH_ATRIBUICAO = $row->dth_atribuicao;
			}else{ // Se n�o encontrado - Retornar NULO
				// Atendimento n�o pode ser realizado pois a tribui��o n�o foi feita � equipe e ao profissional
				$this->SEQ_ATRIBUICAO_CHAMADO = "";
				$this->SEQ_CHAMADO = "";
				$this->SEQ_EQUIPE_TI = "";
				$this->SEQ_SITUACAO_CHAMADO = "";
				$this->NUM_MATRICULA = "";
				$this->TXT_ATIVIDADE = "";
				$this->DTH_ATRIBUICAO = "";
			}
		}
	}

	// ******************
	// ValidarAtribui��o
	// ******************
	function ValidarAtribui��o(){
		// Buscar apenas atribui��es n�o encerradas
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_EXEC = $this->situacao_chamado->CODS_EM_ANDAMENTO;

		if($this->NUM_MATRICULA != ""){
			// Pesquisar atribui��o do chamado para o profissional
			$sql = "SELECT SEQ_ATRIBUICAO_CHAMADO, SEQ_CHAMADO, SEQ_EQUIPE_TI, SEQ_SITUACAO_CHAMADO, NUM_MATRICULA, TXT_ATIVIDADE,
						   to_char(DTH_ATRIBUICAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ATRIBUICAO, DTH_ATRIBUICAO as DTH_ATRIBUICAO_DATA
				    FROM gestaoti.atribuicao_chamado
					where SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
					and NUM_MATRICULA = ".$this->NUM_MATRICULA."
					and SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC) ";
			$result = $this->database->query($sql);
			$result = $this->database->result;
			if($this->database->rows > 0){
				$row = pg_fetch_object($result, 0);
				$this->SEQ_ATRIBUICAO_CHAMADO = $row->seq_atribuicao_chamado;
				$this->SEQ_CHAMADO = $row->seq_chamado;
				$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
				$this->SEQ_SITUACAO_CHAMADO = $row->seq_situacao_chamado;
				$this->NUM_MATRICULA = $row->num_matricula;
				$this->TXT_ATIVIDADE = $row->txt_atividade;
				$this->DTH_ATRIBUICAO = $row->dth_atribuicao;
			}else{
				// Atendimento n�o pode ser realizado pois a tribui��o n�o foi feita � equipe e ao profissional
				$this->SEQ_ATRIBUICAO_CHAMADO = "";
				$this->SEQ_CHAMADO = "";
				$this->SEQ_EQUIPE_TI = "";
				$this->SEQ_SITUACAO_CHAMADO = "";
				$this->NUM_MATRICULA = "";
				$this->TXT_ATIVIDADE = "";
				$this->DTH_ATRIBUICAO = "";
			}
		}else{
			// Pesquisar atribui��o da equipe
			$sql = "SELECT SEQ_ATRIBUICAO_CHAMADO, SEQ_CHAMADO, SEQ_EQUIPE_TI, SEQ_SITUACAO_CHAMADO, NUM_MATRICULA, TXT_ATIVIDADE,
						   to_char(DTH_ATRIBUICAO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ATRIBUICAO, DTH_ATRIBUICAO as DTH_ATRIBUICAO_DATA
				    FROM gestaoti.atribuicao_chamado
					where SEQ_CHAMADO = ".$this->SEQ_CHAMADO."
					and SEQ_EQUIPE_TI = ".$this->SEQ_EQUIPE_TI."
					and SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC) ";
			$result = $this->database->query($sql);
			$result = $this->database->result;
			if($this->database->rows > 0){
				$row = pg_fetch_object($result, 0);
				$this->SEQ_ATRIBUICAO_CHAMADO = $row->seq_atribuicao_chamado;
				$this->SEQ_CHAMADO = $row->seq_chamado;
				$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
				$this->SEQ_SITUACAO_CHAMADO = $row->seq_situacao_chamado;
				$this->NUM_MATRICULA = $row->num_matricula;
				$this->TXT_ATIVIDADE = $row->txt_atividade;
				$this->DTH_ATRIBUICAO = $row->dth_atribuicao;
			}else{ // Se n�o encontrado - Retornar NULO
				// Atendimento n�o pode ser realizado pois a tribui��o n�o foi feita � equipe e ao profissional
				$this->SEQ_ATRIBUICAO_CHAMADO = "";
				$this->SEQ_CHAMADO = "";
				$this->SEQ_EQUIPE_TI = "";
				$this->SEQ_SITUACAO_CHAMADO = "";
				$this->NUM_MATRICULA = "";
				$this->TXT_ATIVIDADE = "";
				$this->DTH_ATRIBUICAO = "";
			}
		}
	}

	// *********************************
	// Atualizar registro de atribui��es
	// *********************************
	function AtualizarAtribuicao($v_SEQ_CHAMADO, $v_SEQ_EQUIPE_TI, $v_NUM_MATRICULA, $v_SEQ_SITUACAO_CHAMADO, $v_SEQ_EQUIPE_ATRIBUICAO, $flgNovaAtribuicao=""){
		$v_TMP_TXT_ATIVIDADE = $this->TXT_ATIVIDADE;
		// N�o atualizar
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_ENCERRADO = $this->situacao_chamado->COD_Encerrada.", ".$this->situacao_chamado->COD_Cancelado;

		// Verificar se existe atribuicao
		$this->setSEQ_CHAMADO($v_SEQ_CHAMADO);
		$this->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
		$this->setNUM_MATRICULA($v_NUM_MATRICULA);
		$this->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
		$this->PesquisaAtribuicao();
		// Verificar se existe atribui��o
		if($this->SEQ_ATRIBUICAO_CHAMADO != "" ){
			// Verificar se existe atribui��o para o profissional
			if($this->NUM_MATRICULA == ""){ // Existe atribui��o da equipe mas o profissional ainda n�o foi designado
				if($v_SEQ_EQUIPE_ATRIBUICAO != ""){
					$sql = " UPDATE gestaoti.atribuicao_chamado
							 SET SEQ_SITUACAO_CHAMADO = $v_SEQ_SITUACAO_CHAMADO,
								 NUM_MATRICULA = $v_NUM_MATRICULA,
								 SEQ_EQUIPE_ATRIBUICAO = $v_SEQ_EQUIPE_ATRIBUICAO
							WHERE SEQ_ATRIBUICAO_CHAMADO = ".$this->SEQ_ATRIBUICAO_CHAMADO."
							  and SEQ_SITUACAO_CHAMADO not in ($v_SEQ_SITUACAO_CHAMADO_ENCERRADO) ";
				}else{
					$sql = " UPDATE gestaoti.atribuicao_chamado
							 SET SEQ_SITUACAO_CHAMADO = $v_SEQ_SITUACAO_CHAMADO,
								 NUM_MATRICULA = $v_NUM_MATRICULA
							WHERE SEQ_ATRIBUICAO_CHAMADO = ".$this->SEQ_ATRIBUICAO_CHAMADO."
							  and SEQ_SITUACAO_CHAMADO not in ($v_SEQ_SITUACAO_CHAMADO_ENCERRADO) ";
				}
				// Atualizar registro
				$result = $this->database->query($sql);
			}elseif($this->NUM_MATRICULA == $v_NUM_MATRICULA && $this->SEQ_EQUIPE_TI == $v_SEQ_EQUIPE_TI){ // Existe atribui��o para o profissional
				// Atualizar situa��o e atribui��o
				$sql = " UPDATE gestaoti.atribuicao_chamado
						 SET SEQ_SITUACAO_CHAMADO = $v_SEQ_SITUACAO_CHAMADO,
						 	 SEQ_EQUIPE_ATRIBUICAO = $v_SEQ_EQUIPE_ATRIBUICAO
						 WHERE SEQ_ATRIBUICAO_CHAMADO = ".$this->SEQ_ATRIBUICAO_CHAMADO."
						   and SEQ_SITUACAO_CHAMADO not in ($v_SEQ_SITUACAO_CHAMADO_ENCERRADO) ";
				$result = $this->database->query($sql);
			}else{ //if($this->NUM_MATRICULA != $v_NUM_MATRICULA){ // Existe atribui��o da equipe mas outro profissional assumiu primeiro
				// Inserir registro clone com outro profissional
				$this->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$this->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
				$this->setNUM_MATRICULA($v_NUM_MATRICULA);
				$this->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
				$this->setTXT_ATIVIDADE($this->TXT_ATIVIDADE);
				$this->setSEQ_EQUIPE_ATRIBUICAO($v_SEQ_EQUIPE_ATRIBUICAO);
				$this->insert();
			}
		}else{
			if($flgNovaAtribuicao == 1){
				// Inserir registro clone com outro profissional
				$this->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$this->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
				$this->setNUM_MATRICULA($v_NUM_MATRICULA);
				$this->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
				$this->setTXT_ATIVIDADE($v_TMP_TXT_ATIVIDADE);
				//$this->setSEQ_EQUIPE_ATRIBUICAO($v_SEQ_EQUIPE_ATRIBUICAO);
				$this->insert();
			}else{
				return false;
			}
		}
	}

	function EquipeAtendimento($v_SEQ_CHAMADO){
		$this->situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_EXEC = $this->situacao_chamado->CODS_EM_ANDAMENTO;

		$sql = "select a.*
				FROM (
				  SELECT nom_colaborador as NOME_ABREVIADO,
				         num_matricula_colaborador as num_matricula
				  FROM gestaoti.viw_colaborador) a, gestaoti.atribuicao_chamado b
				WHERE a.NUM_MATRICULA = b.NUM_MATRICULA
				and b.seq_chamado = $v_SEQ_CHAMADO
				and b.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC)
				order by 1";
		$result = $this->database->query($sql);
		$retorno = "";
		$db = new database();
		while ($row = pg_fetch_array($this->database->result)){
			// Verificar se o chamado est� em execu��o neste momento pelo profissional
			$sql = "select count(1)
					FROM gestaoti.time_sheet
					where num_matricula = ".$row["num_matricula"]."
					and seq_chamado = $v_SEQ_CHAMADO
					and dth_fim is null";
			$rs = $db->query($sql);
			$rowCount = pg_fetch_array($db->result);
			$nome = substr($row["nome_abreviado"], 0, strpos($row["nome_abreviado"]," "));
			if($rowCount[0] == "0"){
				$retorno .= $nome.", ";
			}else{
				$retorno .= "<font color=red>".$nome."</font>, ";
			}
		}
		return substr($retorno, 0, strlen($retorno)-2);
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