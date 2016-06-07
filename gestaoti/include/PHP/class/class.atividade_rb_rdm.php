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
class atividade_rb_rdm{
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
	
	var $DSC_NAO_INICIADA;
	var $DSC_EM_EXECUCAO;
	var $DSC_PARADA;
	var $DSC_FINALIZADA;
	var $DSC_FALHA_NA_EXECUCAO;
	var $DSC_SUSPENSA;
	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_ATIVIDADE_RB_RDM;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_RDM;   // (normal Attribute)
	var $SEQ_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $SEQ_TIPO_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $SEQ_SERVIDOR;   // (normal Attribute)
	var $ORDEM;   // (normal Attribute)
	var $DESCRICAO;   // (normal Attribute)
	var $SITUACAO;
	var $SEQ_EQUIPE_TI;
	var $NUM_MATRICULA_RECURSO;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function atividade_rb_rdm(){
		$this->NAO_INICIADA = 1;
		$this->EM_EXECUCAO = 2;
		$this->PARADA = 3;
		$this->FINALIZADA = 4;
		$this->FALHA_NA_EXECUCAO = 5;
		$this->SUSPENSA = 6;
		
		$this->DSC_NAO_INICIADA = "Não iniciada";
		$this->DSC_EM_EXECUCAO = "m execução";
		$this->DSC_PARADA = "Parada";
		$this->DSC_FINALIZADA = "Finalizada";
		$this->DSC_FALHA_NA_EXECUCAO = "Falha na execução";
		$this->DSC_SUSPENSA = "Suspensa";
		
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

	function getORDEM(){
		return $this->ORDEM;
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
	function getNUM_MATRICULA_RECURSO(){
		return $this->NUM_MATRICULA_RECURSO;
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
	function setORDEM($val){
		$this->ORDEM =  $val;
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
	function setNUM_MATRICULA_RECURSO($val){
		$this->NUM_MATRICULA_RECURSO =  $val;
	}
	
	
	 
	// **********************
	// SELECT METHOD / LOAD
	// **********************	

	function select($id){
		$sql = "SELECT SEQ_ATIVIDADE_RB_RDM , SEQ_RDM , SEQ_ITEM_CONFIGURACAO , SEQ_SERVIDOR , ORDEM , DESCRICAO
			    ,SITUACAO, SEQ_EQUIPE_TI,NUM_MATRICULA_RECURSO FROM gestaoti.atividade_rb_rdm
				WHERE SEQ_ATIVIDADE_RB_RDM = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_ATIVIDADE_RB_RDM = $row->seq_atividade_rb_rdm;
		$this->SEQ_RDM = $row->seq_rdm;
		$this->SEQ_ITEM_CONFIGURACAO = $row->seq_item_configuracao;
		$this->SEQ_SERVIDOR = $row->seq_servidor;
		$this->ORDEM = $row->ordem;
		$this->DESCRICAO = $row->descricao;
		$this->SITUACAO = $row->situacao;
		$this->SEQ_EQUIPE_TI = $row->situacao;
		$this->NUM_MATRICULA_RECURSO = $row->num_matricula_recurso;
		 
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_ATIVIDADE_RB_RDM , SEQ_RDM , SEQ_ITEM_CONFIGURACAO , SEQ_SERVIDOR ,
							 ORDEM, DESCRICAO,SITUACAO,SEQ_EQUIPE_TI,data_hora_inicio_execucao,
							 data_hora_fim_execucao,NUM_MATRICULA_RECURSO ";
		$sqlCorpo  = "FROM gestaoti.atividade_rb_rdm 
					  WHERE 1 = 1 ";

		if($this->SEQ_ATIVIDADE_RB_RDM != ""){
			$sqlCorpo .= "  and SEQ_ATIVIDADE_RB_RDM = $this->SEQ_ATIVIDADE_RB_RDM";
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

	// **********************
	// DELETE
	// **********************
	function delete($id){
		$sql = "DELETE FROM gestaoti.atividade_rb_rdm WHERE SEQ_ATIVIDADE_RB_RDM = $id";
		$result = $this->database->query($sql);
	}
	function deleteByRDM($id){
		$sql = "DELETE FROM gestaoti.atividade_rb_rdm WHERE SEQ_RDM = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_ATIVIDADE_RB_RDM = $this->database->GetSequenceValue("gestaoti.SEQ_ATIVIDADE_RB_RDM");
		
		$sql = "INSERT INTO gestaoti.atividade_rb_rdm(SEQ_ATIVIDADE_RB_RDM,
										  SEQ_RDM,
										  SEQ_ITEM_CONFIGURACAO,
										  SEQ_SERVIDOR,
										  ORDEM,
										  DESCRICAO,
										  SITUACAO,
										  SEQ_EQUIPE_TI,
										  NUM_MATRICULA_RECURSO
									)
							 VALUES (".$this->iif($this->SEQ_ATIVIDADE_RB_RDM=="", "NULL", "'".$this->SEQ_ATIVIDADE_RB_RDM."'").",
									 ".$this->iif($this->SEQ_RDM=="", "NULL", "'".$this->SEQ_RDM."'").",
									 ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
									 ".$this->iif($this->SEQ_SERVIDOR=="", "NULL", "'".$this->SEQ_SERVIDOR."'").",
									 ".$this->iif($this->ORDEM=="", "NULL", "".$this->ORDEM."").",
									 ".$this->iif($this->DESCRICAO=="", "NULL", "'".$this->DESCRICAO."'").",
									 ".$this->iif($this->SITUACAO=="", "NULL", "'".$this->SITUACAO."'").",
									 ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
									 ".$this->iif($this->NUM_MATRICULA_RECURSO=="", "NULL", "'".$this->NUM_MATRICULA_RECURSO."'")."
							 		) ";
		
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// ********************** 
	function update($id){
		$sql = " UPDATE gestaoti.atividade_rb_rdm
				 SET SEQ_RDM = ".$this->iif($this->SEQ_RDM=="", "NULL", "'".$this->SEQ_RDM."'").",
					 SEQ_ITEM_CONFIGURACAO = ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
					 SEQ_SERVIDOR = ".$this->iif($this->SEQ_SERVIDOR=="", "NULL", "'".$this->SEQ_SERVIDOR."'").",
					 ORDEM = ".$this->iif($this->ORDEM=="", "NULL", "'".$this->ORDEM."'").",
					 DESCRICAO = ".$this->iif($this->DESCRICAO=="", "NULL", "'".$this->DESCRICAO."'").",
					 SITUACAO = ".$this->iif($this->SITUACAO=="", "NULL", "'".$this->SITUACAO."'").",
					 SEQ_EQUIPE_TI = ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
					 NUM_MATRICULA_RECURSO = ".$this->iif($this->NUM_MATRICULA_RECURSO=="", "NULL", "'".$this->NUM_MATRICULA_RECURSO."'")."
				WHERE SEQ_ATIVIDADE_RB_RDM = $id ";
		
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
		return $aSituacao;
	}
	
	function getDescricaoSituacaoAtividadeRB($situacao){
			
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