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
* Nome da Classe:	anexo_rdm
* Nome da tabela:	anexo_rdm
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
class rdm{
	
	var $SQL_EXPORT;
	
	// class : begin
	// ***********************
	// DECLARAÇÃO DE CONTANTES
	// ***********************
	var $NORMAL;
	var $EMERGENCIAL;
	
	var $DSC_NORMAL;
	var $DSC_EMERGENCIAL;
	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************	
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página
	
	var $SEQ_RDM;    
	var $NUM_MATRICULA_SOLICITANTE;  
	var $TITULO;  
	var $JUSTIFICATIVA;   
	var $IMPACTO_NAO_EXECUTAR;   
	var $SITUACAO_ATUAL;  
	var $TIPO;  
	var $NOME_RESP_CHECKLIST;    
	var $DDD_TELEFONE_RESP_CHECKLIST;    
	var $NUMERO_TELEFONE_RESP_CHECKLIST;  
	var $EMAIL_RESP_CHECKLIST;    
	var $OBSERVACAO;   
	var $DATA_HORA_ABERTURA; 
	var $DATA_HORA_ULTIMA_ATUALIZACAO;   
	var $DATA_HORA_PREVISTA_EXECUCAO; 
	var $DATA_HORA_INICO_EXECUCAO;   
	var $DATA_HORA_FIM_EXECUCAO;   
	/*
	 * VERIFICAR A NECESSIDADE DESTE CAMPOS
	 * 
	var $DATA_HORA_APROVACAO;   // (normal Attribute)
	var $DATA_HORA_PLANEJAMENTO;   // (normal Attribute)	
	var $DATA_HORA_CANCELAMENTO;   // (normal Attribute)
	var $DATA_HORA_FINALIZACAO;   // (normal Attribute)	
	var $DATA_HORA_INICO_EXECUCAO;   // (normal Attribute)
	var $DATA_HORA_FIM_EXECUCAO;   // (normal Attribute)	
	 */

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	
	// **********************
	// CAMPOS PARA PESQUISA
	// **********************
	var $DATA_HORA_ABERTURA_FINAL;
	var $DATA_HORA_PREVISTA_EXECUCAO_FINAL; 

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function rdm(){
	 
		$this->NORMAL = 1;
		$this->EMERGENCIAL = 2;
		//descricao
		$this->DSC_NORMAL = "Normal";
		$this->DSC_EMERGENCIAL = "Emergencial";
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

	 
	function getSEQ_RDM(){
		return $this->SEQ_RDM;
	}
	
	function getNUM_MATRICULA_SOLICITANTE(){
		return $this->NUM_MATRICULA_SOLICITANTE;
	}
	function getTITULO(){
		return $this->TITULO;
	}
	function getJUSTIFICATIVA(){
		return $this->JUSTIFICATIVA;
	}
	function getIMPACTO_NAO_EXECUTAR(){
		return $this->IMPACTO_NAO_EXECUTAR;
	}
	function getSITUACAO_ATUAL(){
		return $this->SITUACAO_ATUAL;
	}	
	function getTIPO(){
		return $this->TIPO;
	}
	function getNOME_RESP_CHECKLIST(){
		return $this->NOME_RESP_CHECKLIST;
	}
	function getDDD_TELEFONE_RESP_CHECKLIST(){
		return $this->DDD_TELEFONE_RESP_CHECKLIST;
	}
	
	function getNUMERO_TELEFONE_RESP_CHECKLIST(){
		return $this->NUMERO_TELEFONE_RESP_CHECKLIST;
	}
	function getEMAIL_RESP_CHECKLIST(){
		return $this->EMAIL_RESP_CHECKLIST;
	}
	function getOBSERVACAO(){
		return $this->OBSERVACAO;
	}	  
	function getDATA_HORA_PREVISTA_EXECUCAO(){
		return $this->DATA_HORA_PREVISTA_EXECUCAO;
	}
	function getDATA_HORA_ABERTURA(){
		return $this->DATA_HORA_ABERTURA;
	}
	function getDATA_HORA_ULTIMA_ATUALIZACAO(){
		return $this->DATA_HORA_ULTIMA_ATUALIZACAO;
	}
	function getDATA_HORA_INICO_EXECUCAO(){
		return $this->DATA_HORA_INICO_EXECUCAO;
	}
	function getDATA_HORA_FIM_EXECUCAO(){
		return $this->DATA_HORA_FIM_EXECUCAO;
	} 
	function getDATA_HORA_ABERTURA_FINAL(){
		return $this->DATA_HORA_ABERTURA_FINAL;
	} 
	function getDATA_HORA_PREVISTA_EXECUCAO_FINAL(){
		return $this->DATA_HORA_PREVISTA_EXECUCAO_FINAL;
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
	 
	function setSEQ_RDM($val){
		$this->SEQ_RDM =  $val;
	}
	function setNUM_MATRICULA_SOLICITANTE($val){
		$this->NUM_MATRICULA_SOLICITANTE =  $val;
	}
	function setTITULO($val){
		$this->TITULO =  $val;
	}
	function setJUSTIFICATIVA($val){
		$this->JUSTIFICATIVA =  $val;
	}
	function setIMPACTO_NAO_EXECUTAR($val){
		$this->IMPACTO_NAO_EXECUTAR =  $val;
	}
	function setSITUACAO_ATUAL($val){
		$this->SITUACAO_ATUAL =  $val;
	}
	function setTIPO($val){
		$this->TIPO =  $val;
	}
	function setNOME_RESP_CHECKLIST($val){
		$this->NOME_RESP_CHECKLIST =  $val;
	}
	function setDDD_TELEFONE_RESP_CHECKLIST($val){
		$this->DDD_TELEFONE_RESP_CHECKLIST =  $val;
	}
	function setNUMERO_TELEFONE_RESP_CHECKLIST($val){
		$this->NUMERO_TELEFONE_RESP_CHECKLIST =  $val;
	} 
	function setEMAIL_RESP_CHECKLIST($val){
		$this->EMAIL_RESP_CHECKLIST =  $val;
	} 
	function setOBSERVACAO($val){
		$this->OBSERVACAO =  $val;
	}  
	function setDATA_HORA_ABERTURA($val){
		$this->DATA_HORA_ABERTURA =  $val;
	}
	
	function setDATA_HORA_PREVISTA_EXECUCAO($val){
		$this->DATA_HORA_PREVISTA_EXECUCAO =  $val;
	}
	function setDATA_HORA_ULTIMA_ATUALIZACAO($val){
		$this->DATA_HORA_ULTIMA_ATUALIZACAO =  $val;
	}
	function setDATA_HORA_INICO_EXECUCAO($val){
		$this->DATA_HORA_INICO_EXECUCAO =  $val;
	}
	function setDATA_HORA_FIM_EXECUCAO($val){
		$this->DATA_HORA_FIM_EXECUCAO =  $val;
	}
	function setDATA_HORA_ABERTURA_FINAL($val){
		$this->DATA_HORA_ABERTURA_FINAL =  $val;
	}
	function setDATA_HORA_PREVISTA_EXECUCAO_FINAL($val){
		$this->DATA_HORA_PREVISTA_EXECUCAO_FINAL =  $val;
	}
	  
	// **********************
	// SELECT METHOD / LOAD
	// **********************	

	function select($id){
		$sql = "SELECT titulo, justificativa, impacto_nao_executar, nome_resp_checklist, 
      				 seq_rdm, ddd_telefone_resp_checklist, numero_telefone_resp_checklist, 
      				 num_matricula_solicitante, situacao_atual, data_hora_prevista_execucao, 
       				 data_hora_inicio_execucao, data_hora_fim_execucao, tipo, observacao,
       				 data_hora_abertura ,data_hora_ultima_atualizacao,email_resp_checklist      				 
       			FROM gestaoti.rdm
				WHERE seq_rdm = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
				
		$this->SEQ_RDM = $row->seq_rdm;
		$this->NUM_MATRICULA_SOLICITANTE= $row->num_matricula_solicitante;
		$this->TITULO= $row->titulo;
		$this->JUSTIFICATIVA = $row->justificativa;
		$this->IMPACTO_NAO_EXECUTAR = $row->impacto_nao_executar;
		$this->SITUACAO_ATUAL = $row->situacao_atual;
		$this->TIPO = $row->tipo;
		$this->NOME_RESP_CHECKLIST = $row->nome_resp_checklist;
		$this->DDD_TELEFONE_RESP_CHECKLIST = $row->ddd_telefone_resp_checklist;
		$this->NUMERO_TELEFONE_RESP_CHECKLIST = $row->numero_telefone_resp_checklist;
		$this->OBSERVACAO = $row->observacao;
		$this->DATA_HORA_ABERTURA = $row->data_hora_abertura;
		$this->DATA_HORA_PREVISTA_EXECUCAO = $row->data_hora_prevista_execucao;
		$this->DATA_HORA_ULTIMA_ATUALIZACAO = $row->data_hora_ultima_atualizacao;
		$this->EMAIL_RESP_CHECKLIST = $row->email_resp_checklist;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT titulo, justificativa, impacto_nao_executar, nome_resp_checklist, 
      				 seq_rdm, ddd_telefone_resp_checklist, numero_telefone_resp_checklist, 
      				 num_matricula_solicitante, situacao_atual, data_hora_prevista_execucao, 
       				 data_hora_inicio_execucao, data_hora_fim_execucao, tipo, observacao,
       				 data_hora_abertura,data_hora_ultima_atualizacao,email_resp_checklist   ";
		$sqlCorpo  = "FROM gestaoti.rdm
					  WHERE 1 = 1 ";

		 
		if($this->SEQ_RDM != ""){
			$sqlCorpo .= "  and SEQ_RDM = $this->SEQ_RDM ";
		}
		if($this->NUM_MATRICULA_SOLICITANTE != ""){
			$sqlCorpo .= "  and num_matricula_solicitante = $this->NUM_MATRICULA_SOLICITANTE ";
		}		
		if($this->TITULO != ""){
			$sqlCorpo .= "  and upper(TITULO) like '%".strtoupper($this->TITULO)."%'  ";
		}		
		if($this->NOME_RESP_CHECKLIST != ""){
			$sqlCorpo .= "  and upper(nome_resp_checklist) like '%".strtoupper($this->NOME_RESP_CHECKLIST)."%'  ";
		}
		if($this->TIPO != ""){
			$sqlCorpo .= "  and tipo = $this->TIPO ";
		}
		if($this->SITUACAO_ATUAL != ""){
			$sqlCorpo .= "  and SITUACAO_ATUAL = $this->SITUACAO_ATUAL ";
		}
		if($this->DATA_HORA_ABERTURA != "" && $this->DATA_HORA_ABERTURA_FINAL == "" ){
			$sqlCorpo .= "  and data_hora_abertura >= to_date('".$this->DATA_HORA_ABERTURA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_ABERTURA != "" && $this->DATA_HORA_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and data_hora_abertura between to_date('".$this->DATA_HORA_ABERTURA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DATA_HORA_ABERTURA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_ABERTURA == "" && $this->DATA_HORA_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and data_hora_abertura <= to_date('".$this->DATA_HORA_ABERTURA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		
		if($this->DATA_HORA_PREVISTA_EXECUCAO != "" && $this->DATA_HORA_PREVISTA_EXECUCAO_FINAL == "" ){
			$sqlCorpo .= "  and data_hora_prevista_execucao >= to_date('".$this->DATA_HORA_PREVISTA_EXECUCAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_PREVISTA_EXECUCAO != "" && $this->DATA_HORA_PREVISTA_EXECUCAO_FINAL != "" ){
			$sqlCorpo .= "  and data_hora_prevista_execucao between to_date('".$this->DATA_HORA_PREVISTA_EXECUCAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DATA_HORA_PREVISTA_EXECUCAO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_PREVISTA_EXECUCAO == "" && $this->DATA_HORA_PREVISTA_EXECUCAO_FINAL != "" ){
			$sqlCorpo .= "  and data_hora_prevista_execucao <= to_date('".$this->DATA_HORA_PREVISTA_EXECUCAO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}		
		if($this->DATA_HORA_INICO_EXECUCAO != "" && $this->DATA_HORA_FIM_EXECUCAO == "" ){
			$sqlCorpo .= "  and data_hora_inicio_execucao >= to_date('".$this->DATA_HORA_INICO_EXECUCAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_INICO_EXECUCAO != "" && $this->DATA_HORA_FIM_EXECUCAO != "" ){
			$sqlCorpo .= "  and data_hora_inicio_execucao between to_date('".$this->DATA_HORA_INICO_EXECUCAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DATA_HORA_FIM_EXECUCAO." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_INICO_EXECUCAO == "" && $this->DATA_HORA_FIM_EXECUCAO != "" ){
			$sqlCorpo .= "  and data_hora_inicio_execucao <= to_date('".$this->DATA_HORA_FIM_EXECUCAO." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		
		$this->SQL_EXPORT = $sqlSelect . $sqlCorpo;

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
	
	function selectPFM($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT titulo, justificativa, impacto_nao_executar, nome_resp_checklist, 
      				 seq_rdm, ddd_telefone_resp_checklist, numero_telefone_resp_checklist, 
      				 num_matricula_solicitante, situacao_atual, data_hora_prevista_execucao, 
       				 data_hora_inicio_execucao, data_hora_fim_execucao, tipo, observacao,
       				 data_hora_abertura,data_hora_ultima_atualizacao,email_resp_checklist   ";
		$sqlCorpo  = "FROM gestaoti.rdm
					  WHERE SITUACAO_ATUAL IN (3,7,8) ";

		 
		if($this->SEQ_RDM != ""){
			$sqlCorpo .= "  and SEQ_RDM = $this->SEQ_RDM ";
		}
		if($this->NUM_MATRICULA_SOLICITANTE != ""){
			$sqlCorpo .= "  and num_matricula_solicitante = $this->NUM_MATRICULA_SOLICITANTE ";
		}		
		if($this->TITULO != ""){
			$sqlCorpo .= "  and upper(TITULO) like '%".strtoupper($this->TITULO)."%'  ";
		}		
		if($this->NOME_RESP_CHECKLIST != ""){
			$sqlCorpo .= "  and upper(nome_resp_checklist) like '%".strtoupper($this->NOME_RESP_CHECKLIST)."%'  ";
		}
		if($this->TIPO != ""){
			$sqlCorpo .= "  and tipo = $this->TIPO ";
		}
//		if($this->SITUACAO_ATUAL != ""){
//			$sqlCorpo .= "  and SITUACAO_ATUAL = $this->SITUACAO_ATUAL ";
//		}
		if($this->DATA_HORA_ABERTURA != "" && $this->DATA_HORA_ABERTURA_FINAL == "" ){
			$sqlCorpo .= "  and data_hora_abertura >= to_date('".$this->DATA_HORA_ABERTURA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_ABERTURA != "" && $this->DATA_HORA_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and data_hora_abertura between to_date('".$this->DATA_HORA_ABERTURA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DATA_HORA_ABERTURA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_ABERTURA == "" && $this->DATA_HORA_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and data_hora_abertura <= to_date('".$this->DATA_HORA_ABERTURA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		
		if($this->DATA_HORA_PREVISTA_EXECUCAO != "" && $this->DATA_HORA_PREVISTA_EXECUCAO_FINAL == "" ){
			$sqlCorpo .= "  and data_hora_prevista_execucao >= to_date('".$this->DATA_HORA_PREVISTA_EXECUCAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_PREVISTA_EXECUCAO != "" && $this->DATA_HORA_PREVISTA_EXECUCAO_FINAL != "" ){
			$sqlCorpo .= "  and data_hora_prevista_execucao between to_date('".$this->DATA_HORA_PREVISTA_EXECUCAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DATA_HORA_PREVISTA_EXECUCAO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_PREVISTA_EXECUCAO == "" && $this->DATA_HORA_PREVISTA_EXECUCAO_FINAL != "" ){
			$sqlCorpo .= "  and data_hora_prevista_execucao <= to_date('".$this->DATA_HORA_PREVISTA_EXECUCAO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}		
		if($this->DATA_HORA_INICO_EXECUCAO != "" && $this->DATA_HORA_FIM_EXECUCAO == "" ){
			$sqlCorpo .= "  and data_hora_inicio_execucao >= to_date('".$this->DATA_HORA_INICO_EXECUCAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_INICO_EXECUCAO != "" && $this->DATA_HORA_FIM_EXECUCAO != "" ){
			$sqlCorpo .= "  and data_hora_inicio_execucao between to_date('".$this->DATA_HORA_INICO_EXECUCAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DATA_HORA_FIM_EXECUCAO." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DATA_HORA_INICO_EXECUCAO == "" && $this->DATA_HORA_FIM_EXECUCAO != "" ){
			$sqlCorpo .= "  and data_hora_inicio_execucao <= to_date('".$this->DATA_HORA_FIM_EXECUCAO." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}

		$this->SQL_EXPORT = $sqlSelect . $sqlCorpo;
		
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
		$sql = "DELETE FROM gestaoti.atividade_rdm WHERE SEQ_ATIVIDADE_RDM = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_RDM = $this->database->GetSequenceValue("gestaoti.SEQ_RDM");
		
		$sql = "INSERT INTO gestaoti.rdm(
				SEQ_RDM,   
				NUM_MATRICULA_SOLICITANTE,  
				TITULO,
				JUSTIFICATIVA,   
				IMPACTO_NAO_EXECUTAR,   
				SITUACAO_ATUAL,
				TIPO,
				NOME_RESP_CHECKLIST,    
				DDD_TELEFONE_RESP_CHECKLIST,    
				NUMERO_TELEFONE_RESP_CHECKLIST,   
				OBSERVACAO,
				DATA_HORA_ABERTURA,
				DATA_HORA_ULTIMA_ATUALIZACAO,
				DATA_HORA_PREVISTA_EXECUCAO,
				EMAIL_RESP_CHECKLIST
				)
				VALUES (
				".$this->iif($this->SEQ_RDM=="", "NULL", "'".$this->SEQ_RDM."'").",
			 	".$this->iif($this->NUM_MATRICULA_SOLICITANTE=="", "NULL", "'".$this->NUM_MATRICULA_SOLICITANTE."'").",
			 	".$this->iif($this->TITULO=="", "NULL", "'".$this->TITULO."'").",
			 	".$this->iif($this->JUSTIFICATIVA=="", "NULL", "'".$this->JUSTIFICATIVA."'").",
			 	".$this->iif($this->IMPACTO_NAO_EXECUTAR=="", "NULL", "'".$this->IMPACTO_NAO_EXECUTAR."'").",
			 	".$this->iif($this->SITUACAO_ATUAL=="", "NULL", "'".$this->SITUACAO_ATUAL."'").",
			 	".$this->iif($this->TIPO=="", "NULL", "'".$this->TIPO."'").",
			 	".$this->iif($this->NOME_RESP_CHECKLIST=="", "NULL", "'".$this->NOME_RESP_CHECKLIST."'").",
			 	".$this->iif($this->DDD_TELEFONE_RESP_CHECKLIST=="", "NULL", "'".$this->DDD_TELEFONE_RESP_CHECKLIST."'").",
			 	".$this->iif($this->NUMERO_TELEFONE_RESP_CHECKLIST=="", "NULL", "'".$this->NUMERO_TELEFONE_RESP_CHECKLIST."'").",
			 	".$this->iif($this->OBSERVACAO=="", "NULL", "'".$this->OBSERVACAO."'").",
			 	".$this->iif($this->DATA_HORA_ABERTURA=="", "NULL", "'".$this->DATA_HORA_ABERTURA."'").",
			 	".$this->iif($this->DATA_HORA_ULTIMA_ATUALIZACAO=="", "NULL", "'".$this->DATA_HORA_ULTIMA_ATUALIZACAO."'").",
			 	".$this->iif($this->DATA_HORA_PREVISTA_EXECUCAO=="", "NULL", "'".$this->DATA_HORA_PREVISTA_EXECUCAO."'").",
			 	".$this->iif($this->EMAIL_RESP_CHECKLIST=="", "NULL", "'".$this->EMAIL_RESP_CHECKLIST."'")."
				) ";
		
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// ********************** 
	function update($id){
		$sql = "UPDATE gestaoti.rdm SET 				
				NUM_MATRICULA_SOLICITANTE = ".$this->iif($this->NUM_MATRICULA_SOLICITANTE=="", "NULL", "'".$this->NUM_MATRICULA_SOLICITANTE."'").", 
				TITULO = ".$this->iif($this->TITULO=="", "NULL", "'".$this->TITULO."'").",
				JUSTIFICATIVA = ".$this->iif($this->JUSTIFICATIVA=="", "NULL", "'".$this->JUSTIFICATIVA."'").",  
				IMPACTO_NAO_EXECUTAR = ".$this->iif($this->IMPACTO_NAO_EXECUTAR=="", "NULL", "'".$this->IMPACTO_NAO_EXECUTAR."'").",   
				SITUACAO_ATUAL = ".$this->iif($this->SITUACAO_ATUAL=="", "NULL", "'".$this->SITUACAO_ATUAL."'").",
				TIPO = ".$this->iif($this->TIPO=="", "NULL", "'".$this->TIPO."'").",
				NOME_RESP_CHECKLIST = ".$this->iif($this->NOME_RESP_CHECKLIST=="", "NULL", "'".$this->NOME_RESP_CHECKLIST."'").",    
				DDD_TELEFONE_RESP_CHECKLIST = ".$this->iif($this->DDD_TELEFONE_RESP_CHECKLIST=="", "NULL", "'".$this->DDD_TELEFONE_RESP_CHECKLIST."'").",    
				NUMERO_TELEFONE_RESP_CHECKLIST = ".$this->iif($this->NUMERO_TELEFONE_RESP_CHECKLIST=="", "NULL", "'".$this->NUMERO_TELEFONE_RESP_CHECKLIST."'").",   
				OBSERVACAO = ".$this->iif($this->OBSERVACAO=="", "NULL", "'".$this->OBSERVACAO."'").",			
				DATA_HORA_ULTIMA_ATUALIZACAO = ".$this->iif($this->DATA_HORA_ULTIMA_ATUALIZACAO=="", "NULL", "'".$this->DATA_HORA_ULTIMA_ATUALIZACAO."'").",
				DATA_HORA_PREVISTA_EXECUCAO = ".$this->iif($this->DATA_HORA_PREVISTA_EXECUCAO=="", "NULL", "'".$this->DATA_HORA_PREVISTA_EXECUCAO."'").",
				EMAIL_RESP_CHECKLIST = ".$this->iif($this->EMAIL_RESP_CHECKLIST=="", "NULL", "'".$this->EMAIL_RESP_CHECKLIST."'")."
			    WHERE SEQ_RDM = $id ";
		
		$result = $this->database->query($sql);
	}
	function updateSituacao($id){
		$sql = "UPDATE gestaoti.rdm SET 				
				SITUACAO_ATUAL = ".$this->iif($this->SITUACAO_ATUAL=="", "NULL", "'".$this->SITUACAO_ATUAL."'").",
				DATA_HORA_ULTIMA_ATUALIZACAO = ".$this->iif($this->DATA_HORA_ULTIMA_ATUALIZACAO=="", "NULL", "'".$this->DATA_HORA_ULTIMA_ATUALIZACAO."'")." 
			    WHERE SEQ_RDM = $id ";
		
		$result = $this->database->query($sql);
	}
	
	function updateDataHoraPrevistaExecucao($id){
		$sql = "UPDATE gestaoti.rdm SET 				
				DATA_HORA_PREVISTA_EXECUCAO = ".$this->iif($this->DATA_HORA_PREVISTA_EXECUCAO=="", "NULL", "'".$this->DATA_HORA_PREVISTA_EXECUCAO."'").",
				DATA_HORA_ULTIMA_ATUALIZACAO = ".$this->iif($this->DATA_HORA_ULTIMA_ATUALIZACAO=="", "NULL", "'".$this->DATA_HORA_ULTIMA_ATUALIZACAO."'")." 
			    WHERE SEQ_RDM = $id ";
		
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
	function getTipoDescricao($tipo){
			
		switch($tipo)
		{
			case $this->NORMAL: 
				return $this->DSC_NORMAL;
				break; 
			case $this->EMERGENCIAL: 
				return $this->DSC_EMERGENCIAL;
				break;  
			default:  
				return " - ";
		} 
	}
	function comboTipo($selected){		
		$aTipo = Array();
		$aTipo[0] = array($RDM->NORMAL, iif($selected==$this->NORMAL, "Selected", ""), $this->DSC_NORMAL);
		$aTipo[1] = array($RDM->EMERGENCIAL, iif($selected==$this->EMERGENCIAL, "Selected", ""), $this->DSC_EMERGENCIAL);
				
		return $aTipo;
	}
	
	 

} // class : end
?>
