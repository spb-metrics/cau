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
class situacao_rdm{
	// class : begin
	// ***********************
	// DECLARAÇÃO DE CONTANTES
	// ***********************
	var $CRIADA;
	var $AGUARDANDO_APROVACAO;
	var $APROVADA;
	var $REPROVADA;	
	var $EM_PLANEJAMENTO;
	var $PLANEJAMENTO_REPROVADO;
	var $AGUARADANDO_EXECUCAO;
	var $EM_EXECUCAO;	
	var $FALHA_NA_EXECUCAO;
	var $FALHA_NA_VALIDACAO;
	var $FINALIZADA_COM_SUCESSO;
	var $FINALIZADA_COM_ERRO;
	var $FINALIZADA_COM_ROLL_BACK;
	var $CANCELADA;
	var $SUSPENSA;
	var $PARADA;
	var $FECHADA;
	var $EXECUTADA;
	var $EXECUTANDO_ROLL_BACK;
	
	var $DSC_CRIADA;
	var $DSC_AGUARDANDO_APROVACAO;
	var $DSC_APROVADA;
	var $DSC_REPROVADA;	
	var $DSC_EM_PLANEJAMENTO;
	var $DSC_PLANEJAMENTO_REPROVADO;
	var $DSC_AGUARADANDO_EXECUCAO;
	var $DSC_EM_EXECUCAO;	
	var $DSC_FALHA_NA_EXECUCAO;
	var $DSC_FALHA_NA_VALIDACAO;
	var $DSC_FINALIZADA_COM_SUCESSO;
	var $DSC_FINALIZADA_COM_ERRO;
	var $DSC_FINALIZADA_COM_ROLL_BACK;
	var $DSC_CANCELADA;
	var $DSC_SUSPENSA;
	var $DSC_PARADA;
	var $DSC_FECHADA;
	var $DSC_EXECUTADA;
	var $DSC_EXECUTANDO_ROLL_BACK;
	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************	
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página
	
	var $SEQ_SITUACAO_RDM;   // KEY ATTR. WITH AUTOINCREMENT
	var $SEQ_RDM;   // (normal Attribute)
	var $SITUACAO;
	var $DATA_HORA;
	var $OBSERVACAO;   // (normal Attribute)
	var $NUM_MATRICULA_RECURSO;
	 

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function situacao_rdm(){
		$this->CRIADA = 1;
		$this->AGUARDANDO_APROVACAO = 2;
		$this->APROVADA =  3;
		$this->REPROVADA = 4;	
		$this->EM_PLANEJAMENTO = 5;
		$this->PLANEJAMENTO_REPROVADO = 6;
		$this->AGUARADANDO_EXECUCAO = 7;
		$this->EM_EXECUCAO = 8;	
		$this->FALHA_NA_EXECUCAO = 9;
		$this->FALHA_NA_VALIDACAO =10;
		$this->FINALIZADA_COM_SUCESSO = 11;
		$this->CANCELADA = 12;
		$this->SUSPENSA = 13;
		$this->PARADA = 14;
		$this->FECHADA = 15;
		$this->EXECUTADA = 16;	
		$this->FINALIZADA_COM_ERRO = 17;
		$this->FINALIZADA_COM_ROLL_BACK = 18;		
		$this->EXECUTANDO_ROLL_BACK = 19;
		
		//descrição
		$this->DSC_CRIADA = "Criada";
		$this->DSC_AGUARDANDO_APROVACAO = "Aguardando aprovação";
		$this->DSC_APROVADA =  "Aprovada";
		$this->DSC_REPROVADA = "Reprovada";	
		$this->DSC_EM_PLANEJAMENTO = "Em planejamento";
		$this->DSC_PLANEJAMENTO_REPROVADO = "Planejamento reprovado";
		$this->DSC_AGUARADANDO_EXECUCAO = "Aguardando execução";
		$this->DSC_EM_EXECUCAO = "Em execução";	
		$this->DSC_FALHA_NA_EXECUCAO = "Falha na execução";
		$this->DSC_FALHA_NA_VALIDACAO ="Falha na validação";
		$this->DSC_FINALIZADA_COM_SUCESSO = "Finalizada com sucesso";
		$this->DSC_FINALIZADA_COM_ERRO = "Finalizada com erro";
		$this->DSC_FINALIZADA_COM_ROLL_BACK = "Finalizada com rollback";
		$this->DSC_CANCELADA = "Cancelada";
		$this->DSC_SUSPENSA = "Suspensa";
		$this->DSC_PARADA = "Parada";
		$this->DSC_FECHADA = "Fechada";
		$this->DSC_EXECUTADA = "Executada";
		$this->DSC_EXECUTANDO_ROLL_BACK = "Executando rollback";	
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
 	
	function getSEQ_SITUACAO_RDM(){
		return $this->SEQ_SITUACAO_RDM;
	}

	function getSEQ_RDM(){
		return $this->SEQ_RDM;
	} 
	function getDATA_HORA(){
		return $this->DATA_HORA;
	}

	function getOBSERVACAO(){
		return $this->OBSERVACAO;
	}
	
	function getSITUACAO(){
		return $this->SITUACAO;
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

	function setSEQ_SITUACAO_RDM($val){
		$this->SEQ_SITUACAO_RDM =  $val;
	}

	function setSEQ_RDM($val){
		$this->SEQ_RDM =  $val;
	}
 
	function setDATA_HORA($val){
		$this->DATA_HORA =  $val;
	}

	function setOBSERVACAO($val){
		$this->OBSERVACAO =  $val;
	}
	function setSITUACAO($val){
		$this->SITUACAO =  $val;
	}
	function setNUM_MATRICULA_RECURSO($val){
		$this->NUM_MATRICULA_RECURSO =  $val;
	}  
	 
	// **********************
	// SELECT METHOD / LOAD
	// **********************	
	 
	function select($id){
		$sql = "SELECT SEQ_SITUACAO_RDM , SEQ_RDM , SITUACAO , DATA_HORA , OBSERVACAO ,NUM_MATRICULA_RECURSO
			    FROM gestaoti.situacao_rdm
				WHERE SEQ_SITUACAO_RDM = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_SITUACAO_RDM = $row->seq_situacao_rdm;
		$this->SEQ_RDM = $row->seq_rdm;
		$this->SITUACAO = $row->situacao;
		$this->DATA_HORA = $row->data_hora;
		$this->OBSERVACAO = $row->observacao;
		$this->NUM_MATRICULA_RECURSO = $row-> num_matricula_recurso;
		 		 
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_SITUACAO_RDM , SEQ_RDM , DATA_HORA, OBSERVACAO, SITUACAO,NUM_MATRICULA_RECURSO  ";
		$sqlCorpo  = "FROM gestaoti.situacao_rdm
					  WHERE 1 = 1 ";

		if($this->SEQ_SITUACAO_RDM != ""){
			$sqlCorpo .= "  and SEQ_SITUACAO_RDM = $this->SEQ_SITUACAO_RDM";
		}
		if($this->SEQ_RDM != ""){
			$sqlCorpo .= "  and SEQ_RDM = $this->SEQ_RDM ";
		}
		if($this->SITUACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SITUACAO ";
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
		$sql = "DELETE FROM gestaoti.situacao_rdm WHERE SEQ_SITUACAO_RDM = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	
	function insert(){
		$this->SEQ_SITUACAO_RDM = $this->database->GetSequenceValue("gestaoti.SEQ_SITUACAO_RDM");
		
		$sql = "INSERT INTO gestaoti.situacao_rdm(SEQ_SITUACAO_RDM,
										  SEQ_RDM,
										  SITUACAO,										   
										  DATA_HORA,
										  OBSERVACAO,
										  NUM_MATRICULA_RECURSO 
									)
							 VALUES (".$this->iif($this->SEQ_SITUACAO_RDM=="", "NULL", "'".$this->SEQ_SITUACAO_RDM."'").",
									 ".$this->iif($this->SEQ_RDM=="", "NULL", "'".$this->SEQ_RDM."'").",
									 ".$this->iif($this->SITUACAO=="", "NULL", "'".$this->SITUACAO."'").",
									 ".$this->iif($this->DATA_HORA=="", "NULL", "'".$this->DATA_HORA."'").",
									 ".$this->iif($this->OBSERVACAO=="", "NULL", "'".$this->OBSERVACAO."'").",
									 ".$this->iif($this->NUM_MATRICULA_RECURSO=="", "NULL", "".$this->NUM_MATRICULA_RECURSO."")."
							 		) ";
		
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// ********************** 
	
	function update($id){
		$sql = " UPDATE gestaoti.situacao_rdm
				 SET SEQ_RDM = ".$this->iif($this->SEQ_RDM=="", "NULL", "'".$this->SEQ_RDM."'").",
					 SITUACAO = ".$this->iif($this->SITUACAO=="", "NULL", "'".$this->SITUACAO."'").",
					 OBSERVACAO = ".$this->iif($this->OBSERVACAO=="", "NULL", "'".$this->OBSERVACAO."'").", 
					 DATA_HORA =  ".$this->iif($this->DATA_HORA=="", "NULL", "'".$this->DATA_HORA."'")." , 
					 NUM_MATRICULA_RECURSO =  ".$this->iif($this->NUM_MATRICULA_RECURSO=="", "NULL", "'".$this->NUM_MATRICULA_RECURSO."'")."
				WHERE SEQ_SITUACAO_RDM = $id ";
		
		$result = $this->database->query($sql);
	}

	function combo($selected){		
		$aSituacao = Array();
		$aSituacao[0] = array($this->CRIADA, iif($selected==$this->CRIADA, "Selected", ""), $this->DSC_CRIADA);
		$aSituacao[1] = array($this->AGUARDANDO_APROVACAO, iif($selected==$this->AGUARDANDO_APROVACAO, "Selected", ""), $this->DSC_AGUARDANDO_APROVACAO);
		$aSituacao[2] = array($this->REPROVADA, iif($selected==$this->APROVADA, "Selected", ""), $this->DSC_APROVADA);
		$aSituacao[3] = array($this->REPROVADA, iif($selected==$this->REPROVADA, "Selected", ""), $this->DSC_REPROVADA);
		$aSituacao[4] = array($this->EM_PLANEJAMENTO, iif($selected==$this->EM_PLANEJAMENTO, "Selected", ""), $this->DSC_EM_PLANEJAMENTO);
		$aSituacao[5] = array($this->PLANEJAMENTO_REPROVADO, iif($selected==$this->PLANEJAMENTO_REPROVADO, "Selected", ""), $this->DSC_PLANEJAMENTO_REPROVADO);
		$aSituacao[6] = array($this->AGUARADANDO_EXECUCAO, iif($selected==$this->AGUARADANDO_EXECUCAO, "Selected", ""), $this->DSC_AGUARADANDO_EXECUCAO);		
		$aSituacao[7] = array($this->EM_EXECUCAO, iif($selected==$this->EM_EXECUCAO, "Selected", ""), $this->DSC_EM_EXECUCAO);
		$aSituacao[8] = array($this->FALHA_NA_EXECUCAO, iif($selected==$this->FALHA_NA_EXECUCAO, "Selected", ""), $this->DSC_FALHA_NA_EXECUCAO);
		$aSituacao[9] = array($this->FALHA_NA_VALIDACAO, iif($selected==$this->FALHA_NA_VALIDACAO, "Selected", ""), $this->DSC_FALHA_NA_VALIDACAO);
		$aSituacao[10]= array($this->FINALIZADA_COM_SUCESSO, iif($selected==$this->FINALIZADA_COM_SUCESSO, "Selected", ""), $this->DSC_FINALIZADA_COM_SUCESSO);
		$aSituacao[11]= array($this->FINALIZADA_COM_ERRO, iif($selected==$this->FINALIZADA_COM_ERRO, "Selected", ""), $this->DSC_FINALIZADA_COM_ERRO);
		$aSituacao[12]= array($this->FINALIZADA_COM_ROLL_BACK, iif($selected==$this->FINALIZADA_COM_ROLL_BACK, "Selected", ""), $this->DSC_FINALIZADA_COM_ROLL_BACK);
		$aSituacao[13]= array($this->CANCELADA, iif($selected==$this->CANCELADA, "Selected", ""), $this->DSC_CANCELADA);
		$aSituacao[14]= array($this->SUSPENSA, iif($selected==$this->SUSPENSA, "Selected", ""), $this->DSC_SUSPENSA);
		$aSituacao[15]= array($this->PARADA, iif($selected==$this->PARADA, "Selected", ""), $this->DSC_PARADA);
		$aSituacao[16]= array($this->FECHADA, iif($selected==$this->FECHADA, "Selected", ""), $this->DSC_FECHADAA);
		$aSituacao[17]= array($this->EXECUTADA, iif($selected==$this->EXECUTADA, "Selected", ""), $this->DSC_EXECUTADA);
		$aSituacao[18]= array($this->EXECUTANDO_ROLL_BACK, iif($selected==$this->EXECUTANDO_ROLL_BACK, "Selected", ""), $this->DSC_EXECUTANDO_ROLL_BACK);
		
		 
		return $aSituacao;
	}
	
	function getDescricao($situacao){
			
		switch($situacao)
		{
			case $this->CRIADA: 
				return $this->DSC_CRIADA;
				break; 
			case $this->AGUARDANDO_APROVACAO: 
				return $this->DSC_AGUARDANDO_APROVACAO;
				break; 
			case $this->APROVADA: 
				return $this->DSC_APROVADA;
				break; 
			case $this->REPROVADA: 
				return $this->DSC_REPROVADA;
				break; 
			case $this->EM_PLANEJAMENTO: 
				return $this->DSC_EM_PLANEJAMENTO;
				break; 
			case $this->PLANEJAMENTO_REPROVADO: 
				return $this->DSC_PLANEJAMENTO_REPROVADO;
				break; 
			case $this->AGUARADANDO_EXECUCAO: 
				return $this->DSC_AGUARADANDO_EXECUCAO;
				break;
			case $this->EM_EXECUCAO: 
				return $this->DSC_EM_EXECUCAO;
				break;
			case $this->FALHA_NA_EXECUCAO: 
				return $this->DSC_FALHA_NA_EXECUCAO;
				break;
			case $this->FALHA_NA_VALIDACAO: 
				return $this->DSC_FALHA_NA_VALIDACAO;
				break;
			case $this->FINALIZADA_COM_SUCESSO: 
				return $this->DSC_FINALIZADA_COM_SUCESSO;
				break;
			case $this->FINALIZADA_COM_ERRO: 
				return $this->DSC_FINALIZADA_COM_ERRO;
				break;
			case $this->FINALIZADA_COM_ROLL_BACK: 
				return $this->DSC_FINALIZADA_COM_ROLL_BACK;
				break;
			case $this->CANCELADA: 
				return $this->DSC_CANCELADA;
				break;
			case $this->SUSPENSA: 
				return $this->DSC_SUSPENSA;
				break;
			case $this->PARADA: 
				return $this->DSC_PARADA;
				break;
			case $this->FECHADA: 
				return $this->DSC_FECHADA;
				break;
			case $this->EXECUTADA: 
				return $this->DSC_EXECUTADA;
				break;
			case $this->EXECUTANDO_ROLL_BACK: 
				return $this->DSC_EXECUTANDO_ROLL_BACK;
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