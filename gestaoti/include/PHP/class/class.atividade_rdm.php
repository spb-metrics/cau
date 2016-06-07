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
class atividade_rdm{
	// class : begin
	// ***********************
	// DECLARAÇÃO DE CONTANTES
	// ***********************
	var $NAO_INICIADA;
	var $EM_EXECUCAO;
	var $PARADA;
	var $FINALIZADA;
	var $FALHA_NA_EXECUCAO;
	var $SUSPENSA;
	var $REABERTA;
	
	var $DSC_NAO_INICIADA;
	var $DSC_EM_EXECUCAO;
	var $DSC_PARADA;
	var $DSC_FINALIZADA;
	var $DSC_FALHA_NA_EXECUCAO;
	var $DSC_SUSPENSA;
	var $DSC_REABERTA;
	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_ATIVIDADE_RDM;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_RDM;   // (normal Attribute)
	var $SEQ_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $SEQ_TIPO_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $SEQ_SERVIDOR;   // (normal Attribute)
	var $ORDEM;   // (normal Attribute)
	var $DATA_HORA_PREVISTA_EXECUCAO;   // (normal Attribute)
	var $DESCRICAO;   // (normal Attribute)
	var $SITUACAO;
	var $SEQ_EQUIPE_TI;
	var $NUM_MATRICULA_RECURSO;
	var $DATA_HORA_INICIO_EXECUCAO;
	var $DATA_HORA_FIM_EXECUCAO;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function atividade_rdm(){	 
		$this->NAO_INICIADA = 1;
		$this->EM_EXECUCAO = 2;
		$this->PARADA = 3;
		$this->FINALIZADA = 4;
		$this->FALHA_NA_EXECUCAO = 5;
		$this->SUSPENSA = 6;
		$this->REABERTA = 7;
		
		$this->DSC_NAO_INICIADA = "Não iniciada";
		$this->DSC_EM_EXECUCAO = "Em execução";
		$this->DSC_PARADA = "Parada";
		$this->DSC_FINALIZADA = "Finalizada";
		$this->DSC_FALHA_NA_EXECUCAO = "Falha na execução";
		$this->DSC_SUSPENSA = "Suspensa";
		$this->DSC_REABERTA = "Reaberta";
		
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

	function getSEQ_ATIVIDADE_RDM(){
		return $this->SEQ_ATIVIDADE_RDM;
	}

	function getSEQ_RDM(){
		return $this->SEQ_RDM;
	}
 
	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	} 
	function getSEQ_SERVIDOR(){
		return $this->SEQ_SERVIDOR;
	}
	function getSEQ_TIPO_ITEM_CONFIGURACAO(){
		return $this->SEQ_TIPO_ITEM_CONFIGURACAO;
	} 
	function getDATA_HORA_PREVISTA_EXECUCAO(){
		return $this->DATA_HORA_PREVISTA_EXECUCAO;
	}

	function getDESCRICAO(){
		return $this->DESCRICAO;
	}
	
	function getSITUACAO(){
		return $this->SITUACAO;
	}
	function getSEQ_EQUIPE_TI(){
		return $this->SEQ_EQUIPE_TI;
	}	
	function getORDEM(){
		return $this->ORDEM;
	}	
	
	function getNUM_MATRICULA_RECURSO(){
		return $this->NUM_MATRICULA_RECURSO;
	}
	function getDATA_HORA_INICIO_EXECUCAO(){
		return $this->DATA_HORA_INICIO_EXECUCAO;
	}
	function getDATA_HORA_FIM_EXECUCAO(){
		return $this->DATA_HORA_FIM_EXECUCAO;
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

	function setSEQ_ATIVIDADE_RDM($val){
		$this->SEQ_ATIVIDADE_RDM =  $val;
	}

	function setSEQ_RDM($val){
		$this->SEQ_RDM =  $val;
	}

	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}
	
	function setSEQ_SERVIDOR($val){
		$this->SEQ_SERVIDOR =  $val;
	}
	function setSEQ_TIPO_ITEM_CONFIGURACAO($val){
		$this->SEQ_TIPO_ITEM_CONFIGURACAO =  $val;
	}
	function setDATA_HORA_PREVISTA_EXECUCAO($val){
		$this->DATA_HORA_PREVISTA_EXECUCAO =  $val;
	}

	function setDESCRICAO($val){
		$this->DESCRICAO =  $val;
	}
	function setSITUACAO($val){
		$this->SITUACAO =  $val;
	}
	
	function setSEQ_EQUIPE_TI($val){
		$this->SEQ_EQUIPE_TI =  $val;
	}
	
	function setORDEM($val){
		$this->ORDEM =  $val;
	} 

	function setNUM_MATRICULA_RECURSO($val){
		$this->NUM_MATRICULA_RECURSO =  $val;
	}
	function setDATA_HORA_INICIO_EXECUCAO($val){
		$this->DATA_HORA_INICIO_EXECUCAO =  $val;
	}
	function setDATA_HORA_FIM_EXECUCAO($val){
		$this->DATA_HORA_FIM_EXECUCAO =  $val;
	}
	
	 
	// **********************
	// SELECT METHOD / LOAD
	// **********************	

	function select($id){
		$sql = "SELECT SEQ_ATIVIDADE_RDM ,ORDEM, SEQ_RDM , SEQ_ITEM_CONFIGURACAO , SEQ_SERVIDOR , DATA_HORA_PREVISTA_EXECUCAO , DESCRICAO
			    ,SITUACAO,SEQ_EQUIPE_TI,NUM_MATRICULA_RECURSO FROM gestaoti.atividade_rdm
				WHERE SEQ_ATIVIDADE_RDM = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_ATIVIDADE_RDM = $row->seq_atividade_rdm;
		$this->SEQ_RDM = $row->seq_rdm;
		$this->SEQ_ITEM_CONFIGURACAO = $row->seq_item_configuracao;
		$this->SEQ_SERVIDOR = $row->seq_servidor;
		$this->DATA_HORA_PREVISTA_EXECUCAO = $row->data_hora_prevista_execucao;
		$this->DESCRICAO = $row->descricao;
		$this->SITUACAO = $row->situacao;
		$this->SEQ_EQUIPE_TI  = $row->seq_equipe_ti;
		$this->ORDEM  = $row->ordem;
		$this->NUM_MATRICULA_RECURSO = $row->num_matricula_recurso;
		 
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_ATIVIDADE_RDM ,ORDEM, SEQ_RDM , SEQ_ITEM_CONFIGURACAO , SEQ_SERVIDOR ,
							 DATA_HORA_PREVISTA_EXECUCAO, DESCRICAO,SITUACAO,SEQ_EQUIPE_TI,
							 data_hora_inicio_execucao,data_hora_fim_execucao,num_matricula_recurso ";
		$sqlCorpo  = "FROM gestaoti.atividade_rdm 
					  WHERE 1 = 1 ";

		if($this->SEQ_ATIVIDADE_RDM != ""){
			$sqlCorpo .= "  and SEQ_ATIVIDADE_RDM = $this->SEQ_ATIVIDADE_RDM";
		}
		if($this->SEQ_RDM != ""){
			$sqlCorpo .= "  and SEQ_RDM = $this->SEQ_RDM ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_SERVIDOR != ""){
			$sqlCorpo .= "  and SEQ_SERVIDOR = $this->SEQ_SERVIDOR ";
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
	
	function selectNaoFinalizadas($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_ATIVIDADE_RDM ,ORDEM, SEQ_RDM , SEQ_ITEM_CONFIGURACAO , SEQ_SERVIDOR ,
							 DATA_HORA_PREVISTA_EXECUCAO, DESCRICAO,SITUACAO,SEQ_EQUIPE_TI,
							 data_hora_inicio_execucao,data_hora_fim_execucao,num_matricula_recurso ";
		$sqlCorpo  = "FROM gestaoti.atividade_rdm 
					  WHERE SITUACAO <> $this->FINALIZADA";
		
		if($this->SEQ_RDM != ""){
			$sqlCorpo .= "  and SEQ_RDM = $this->SEQ_RDM ";
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
		$sql = "DELETE FROM gestaoti.atividade_rdm WHERE SEQ_ATIVIDADE_RDM = $id";
		$result = $this->database->query($sql);
	}
	// **********************
	function deleteByRDM($id){
		$sql = "DELETE FROM gestaoti.atividade_rdm WHERE SEQ_RDM = $id";
		$result = $this->database->query($sql);
	}
	

	// **********************
	// INSERT
	// **********************
	
	function insert(){
		$this->SEQ_ATIVIDADE_RDM = $this->database->GetSequenceValue("gestaoti.SEQ_ATIVIDADE_RDM");
		
		$sql = "INSERT INTO gestaoti.atividade_rdm(SEQ_ATIVIDADE_RDM,
										  SEQ_RDM,
										  SEQ_ITEM_CONFIGURACAO,
										  SEQ_SERVIDOR,
										  DATA_HORA_PREVISTA_EXECUCAO,
										  DESCRICAO,
										  SITUACAO,
										  ORDEM,
										  SEQ_EQUIPE_TI,
										  NUM_MATRICULA_RECURSO
									)
							 VALUES (".$this->iif($this->SEQ_ATIVIDADE_RDM=="", "NULL", "'".$this->SEQ_ATIVIDADE_RDM."'").",
									 ".$this->iif($this->SEQ_RDM=="", "NULL", "'".$this->SEQ_RDM."'").",
									 ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
									 ".$this->iif($this->SEQ_SERVIDOR=="", "NULL", "'".$this->SEQ_SERVIDOR."'").",
									 ".$this->iif($this->DATA_HORA_PREVISTA_EXECUCAO=="", "NULL", "'".$this->DATA_HORA_PREVISTA_EXECUCAO."'").",
									 ".$this->iif($this->DESCRICAO=="", "NULL", "'".$this->DESCRICAO."'").",
									 ".$this->iif($this->SITUACAO=="", "NULL", "'".$this->SITUACAO."'").",
									 ".$this->iif($this->ORDEM=="", "NULL", "'".$this->ORDEM."'").",
									  ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
									  ".$this->iif($this->NUM_MATRICULA_RECURSO=="", "NULL", "'".$this->NUM_MATRICULA_RECURSO."'")."
							 		) ";
		
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// ********************** 
	
	function update($id){
		$sql = " UPDATE gestaoti.atividade_rdm
				 SET SEQ_RDM = ".$this->iif($this->SEQ_RDM=="", "NULL", "'".$this->SEQ_RDM."'").",
					 SEQ_ITEM_CONFIGURACAO = ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
					 SEQ_SERVIDOR = ".$this->iif($this->SEQ_SERVIDOR=="", "NULL", "'".$this->SEQ_SERVIDOR."'").",
					 DATA_HORA_PREVISTA_EXECUCAO = ".$this->iif($this->DATA_HORA_PREVISTA_EXECUCAO=="", "NULL", "'".$this->DATA_HORA_PREVISTA_EXECUCAO."'").",
					 DESCRICAO = ".$this->iif($this->DESCRICAO=="", "NULL", "'".$this->DESCRICAO."'").",
					 SITUACAO = ".$this->iif($this->SITUACAO=="", "NULL", "'".$this->SITUACAO."'").",
					 ORDEM = ".$this->iif($this->ORDEM=="", "NULL", "'".$this->ORDEM."'").",
					 SEQ_EQUIPE_TI = ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
					 NUM_MATRICULA_RECURSO = ".$this->iif($this->NUM_MATRICULA_RECURSO=="", "NULL", "'".$this->NUM_MATRICULA_RECURSO."'")."
				WHERE SEQ_ATIVIDADE_RDM = $id ";		 
		$result = $this->database->query($sql);
	}
	
	function updateExecucao($id){
		$sql = " UPDATE gestaoti.atividade_rdm
				 SET 
				 	DATA_HORA_INICIO_EXECUCAO = ".$this->iif($this->DATA_HORA_INICIO_EXECUCAO=="", "NULL", "'".$this->DATA_HORA_INICIO_EXECUCAO."'").",					 
					SITUACAO = ".$this->iif($this->EM_EXECUCAO=="", "NULL", "'".$this->EM_EXECUCAO."'")." 
				WHERE SEQ_ATIVIDADE_RDM = $id ";		 
		$result = $this->database->query($sql);
	}
	
	function updateFinalizacao($id){
		$sql = " UPDATE gestaoti.atividade_rdm
				 SET 
				 	DATA_HORA_FIM_EXECUCAO = ".$this->iif($this->DATA_HORA_FIM_EXECUCAO=="", "NULL", "'".$this->DATA_HORA_FIM_EXECUCAO."'").",					 
					SITUACAO = ".$this->iif($this->FINALIZADA=="", "NULL", "'".$this->FINALIZADA."'")." 
				WHERE SEQ_ATIVIDADE_RDM = $id ";		 
		$result = $this->database->query($sql);
	}
	
	function updateParada($id){
		$sql = " UPDATE gestaoti.atividade_rdm
				 SET SITUACAO = ".$this->iif($this->PARADA=="", "NULL", "'".$this->PARADA."'")." 
				WHERE SEQ_ATIVIDADE_RDM = $id ";		 
		$result = $this->database->query($sql);
	}
	function updateSuspensa($id){
		$sql = " UPDATE gestaoti.atividade_rdm
				 SET SITUACAO = ".$this->iif($this->SUSPENSA=="", "NULL", "'".$this->SUSPENSA."'")." 
				WHERE SEQ_ATIVIDADE_RDM = $id ";		 
		$result = $this->database->query($sql);
	}	
	function updateFalhaExecucao($id){
		$sql = " UPDATE gestaoti.atividade_rdm
				 SET 
				 	DATA_HORA_FIM_EXECUCAO = ".$this->iif($this->DATA_HORA_FIM_EXECUCAO=="", "NULL", "'".$this->DATA_HORA_FIM_EXECUCAO."'").",					 
					SITUACAO = ".$this->iif($this->FALHA_NA_EXECUCAO=="", "NULL", "'".$this->FALHA_NA_EXECUCAO."'")." 
				WHERE SEQ_ATIVIDADE_RDM = $id ";		 
		$result = $this->database->query($sql);
	}
	function updateReabrirByRDM($id){
		$sql = "UPDATE  gestaoti.atividade_rdm 
				SET SITUACAO = ". "".$this->REABERTA."".",
				DATA_HORA_INICIO_EXECUCAO = NULL,
				DATA_HORA_FIM_EXECUCAO = NULL
				WHERE SEQ_RDM = $id";
		$result = $this->database->query($sql);
	}
	
	function combo($selected){		
		$aSituacao = Array();
		$aSituacao[0] = array($this->NAO_INICIADA, iif($selected==$this->NAO_INICIADA, "Selected", ""), $this->DSC_NAO_INICIADA);
		$aSituacao[1] = array($this->EM_EXECUCAO, iif($selected==$this->EM_EXECUCAO, "Selected", ""), $this->DSC_EM_EXECUCAO);
		$aSituacao[2] = array($this->PARADA, iif($selected==$this->PARADA, "Selected", ""), $this->DSC_PARADA);
		$aSituacao[3] = array($this->FINALIZADA, iif($selected==$this->FINALIZADA, "Selected", ""), $this->DSC_FINALIZADA);
		$aSituacao[4] = array($this->FALHA_NA_EXECUCAO, iif($selected==$this->FALHA_NA_EXECUCAO, "Selected", ""), $this->DSC_FALHA_NA_EXECUCAO);
		$aSituacao[5] = array($this->SUSPENSA, iif($selected==$this->SUSPENSA, "Selected", ""), $this->DSC_SUSPENSA);
		$aSituacao[6] = array($this->REABERTA, iif($selected==$this->REABERTA, "Selected", ""), $this->DSC_REABERTA);
		 			
		return $aSituacao;
	}
	
	function getDescricaoSituacaoAtividade($situacao){
			
		switch($situacao)
		{
			case $this->NAO_INICIADA: 
				return $this->DSC_NAO_INICIADA;
				break; 
			case $this->EM_EXECUCAO: 
				return $this->DSC_EM_EXECUCAO;
				break; 
			case $this->PARADA: 
				return $this->DSC_PARADA;
				break; 
			case $this->FINALIZADA: 
				return $this->DSC_FINALIZADA;
				break; 
			case $this->FALHA_NA_EXECUCAO: 
				return $this->DSC_FALHA_NA_EXECUCAO;
				break; 
			case $this->SUSPENSA: 
				return $this->DSC_SUSPENSA;
				break;  
			case $this->REABERTA: 
				return $this->DSC_REABERTA;
				break;  
			default:  
				return " - ";
		}    
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