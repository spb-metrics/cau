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
// DECLARA��O DA CLASSE
// **********************
class rdm_template{
	// class : begin
	 
	// ***********************
	// DECLARA��O DE ATRIBUTOS
	// ***********************	
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina
	
	var $SEQ_RDM_TEMPLATE; 
	var $SEQ_RDM_ORIGEM;
	var $TITULO;  
	var $JUSTIFICATIVA;   
	var $IMPACTO_NAO_EXECUTAR;  
	var $NOME_RESP_CHECKLIST;    
	var $DDD_TELEFONE_RESP_CHECKLIST;    
	var $NUMERO_TELEFONE_RESP_CHECKLIST;  
	var $EMAIL_RESP_CHECKLIST;    
	var $OBSERVACAO;   
	  
	 
	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados
	
	

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function rdm_template(){	  
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

	 
	function getSEQ_RDM_TEMPLATE(){
		return $this->SEQ_RDM_TEMPLATE;
	}
	
	function getSEQ_RDM_ORIGEM(){
		return $this->SEQ_RDM_ORIGEM;
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
	 
	
	// **********************
	// SETTER METHODS
	// **********************
	function setrowCount($val){
		$this->rowCount = $val;
	}

	function setvQtdRegistros($val){
		$this->vQtdRegistros = $val;
	}
	 
	function setSEQ_RDM_TEMPLATE($val){
		$this->SEQ_RDM_TEMPLATE =  $val;
	}
	function setSEQ_RDM_ORIGEM($val){
		$this->SEQ_RDM_ORIGEM =  $val;
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
	 
	  
	// **********************
	// SELECT METHOD / LOAD
	// **********************	

	function select($id){
		$sql = "SELECT titulo, justificativa, impacto_nao_executar, nome_resp_checklist, 
      				 seq_rdm_template, seq_rdm_origem,ddd_telefone_resp_checklist, numero_telefone_resp_checklist, 
      				   observacao,email_resp_checklist      				 
       			FROM gestaoti.rdm_template
				WHERE SEQ_RDM_TEMPLATE = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
				
		$this->SEQ_RDM_TEMPLATE = $row->seq_rdm_template;
		$this->SEQ_RDM_ORIGEM = $row->seq_rdm_origem;		 
		$this->TITULO= $row->titulo;
		$this->JUSTIFICATIVA = $row->justificativa;
		$this->IMPACTO_NAO_EXECUTAR = $row->impacto_nao_executar;		
		$this->NOME_RESP_CHECKLIST = $row->nome_resp_checklist;
		$this->DDD_TELEFONE_RESP_CHECKLIST = $row->ddd_telefone_resp_checklist;
		$this->NUMERO_TELEFONE_RESP_CHECKLIST = $row->numero_telefone_resp_checklist;
		$this->OBSERVACAO = $row->observacao;		
		$this->EMAIL_RESP_CHECKLIST = $row->email_resp_checklist;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT titulo, justificativa, impacto_nao_executar, nome_resp_checklist, 
      				 seq_rdm_template,seq_rdm_origem, ddd_telefone_resp_checklist, numero_telefone_resp_checklist, 
      				  observacao,email_resp_checklist   ";
		$sqlCorpo  = "FROM gestaoti.rdm_template
					  WHERE 1 = 1 ";

		 
		if($this->SEQ_RDM_TEMPLATE != ""){
			$sqlCorpo .= "  and SEQ_RDM_TEMPLATE = $this->SEQ_RDM_TEMPLATE ";
		}
		 	
		if($this->TITULO != ""){
			$sqlCorpo .= "  and upper(TITULO) like '%".strtoupper($this->TITULO)."%'  ";
		}	

		if($this->JUSTIFICATIVA != ""){
			$sqlCorpo .= "  and upper(JUSTIFICATIVA) like '%".strtoupper($this->JUSTIFICATIVA)."%'  ";
		}	
		
		if($this->IMPACTO_NAO_EXECUTAR != ""){
			$sqlCorpo .= "  and upper(impacto_nao_executar) like '%".strtoupper($this->IMPACTO_NAO_EXECUTAR)."%'  ";
		}	
		
		if($this->NOME_RESP_CHECKLIST != ""){
			$sqlCorpo .= "  and upper(nome_resp_checklist) like '%".strtoupper($this->NOME_RESP_CHECKLIST)."%'  ";
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
		$sql = "DELETE FROM gestaoti.rdm_template WHERE SEQ_RDM_TEMPLATE = $id";
		$result = $this->database->query($sql);
	}
	
	function deleteByRDMOrigem($id){
		$sql = "DELETE FROM gestaoti.rdm_template WHERE SEQ_RDM_ORIGEM = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_RDM_TEMPLATE = $this->database->GetSequenceValue("gestaoti.SEQ_RDM_TEMPLATE");
		
		$sql = "INSERT INTO gestaoti.rdm_template(
				SEQ_RDM_TEMPLATE, 
				SEQ_RDM_ORIGEM, 
				TITULO,
				JUSTIFICATIVA,   
				IMPACTO_NAO_EXECUTAR, 
				NOME_RESP_CHECKLIST,    
				DDD_TELEFONE_RESP_CHECKLIST,    
				NUMERO_TELEFONE_RESP_CHECKLIST,   
				OBSERVACAO,
				EMAIL_RESP_CHECKLIST)
				VALUES (
				".$this->iif($this->SEQ_RDM_TEMPLATE=="", "NULL", "'".$this->SEQ_RDM_TEMPLATE."'").",			 	 
				".$this->iif($this->SEQ_RDM_ORIGEM=="", "NULL", "'".$this->SEQ_RDM_ORIGEM."'").",
			 	".$this->iif($this->TITULO=="", "NULL", "'".$this->TITULO."'").",
			 	".$this->iif($this->JUSTIFICATIVA=="", "NULL", "'".$this->JUSTIFICATIVA."'").",
			 	".$this->iif($this->IMPACTO_NAO_EXECUTAR=="", "NULL", "'".$this->IMPACTO_NAO_EXECUTAR."'").",			 	
			 	".$this->iif($this->NOME_RESP_CHECKLIST=="", "NULL", "'".$this->NOME_RESP_CHECKLIST."'").",
			 	".$this->iif($this->DDD_TELEFONE_RESP_CHECKLIST=="", "NULL", "'".$this->DDD_TELEFONE_RESP_CHECKLIST."'").",
			 	".$this->iif($this->NUMERO_TELEFONE_RESP_CHECKLIST=="", "NULL", "'".$this->NUMERO_TELEFONE_RESP_CHECKLIST."'").",
			 	".$this->iif($this->OBSERVACAO=="", "NULL", "'".$this->OBSERVACAO."'").",			 	
			 	".$this->iif($this->EMAIL_RESP_CHECKLIST=="", "NULL", "'".$this->EMAIL_RESP_CHECKLIST."'")."
				) ";
		
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// ********************** 
	function update($id){
		$sql = "UPDATE gestaoti.rdm_template SET
				TITULO = ".$this->iif($this->TITULO=="", "NULL", "'".$this->TITULO."'").",
				JUSTIFICATIVA = ".$this->iif($this->JUSTIFICATIVA=="", "NULL", "'".$this->JUSTIFICATIVA."'").",  
				IMPACTO_NAO_EXECUTAR = ".$this->iif($this->IMPACTO_NAO_EXECUTAR=="", "NULL", "'".$this->IMPACTO_NAO_EXECUTAR."'").",
				NOME_RESP_CHECKLIST = ".$this->iif($this->NOME_RESP_CHECKLIST=="", "NULL", "'".$this->NOME_RESP_CHECKLIST."'").",    
				DDD_TELEFONE_RESP_CHECKLIST = ".$this->iif($this->DDD_TELEFONE_RESP_CHECKLIST=="", "NULL", "'".$this->DDD_TELEFONE_RESP_CHECKLIST."'").",    
				NUMERO_TELEFONE_RESP_CHECKLIST = ".$this->iif($this->NUMERO_TELEFONE_RESP_CHECKLIST=="", "NULL", "'".$this->NUMERO_TELEFONE_RESP_CHECKLIST."'").",   
				OBSERVACAO = ".$this->iif($this->OBSERVACAO=="", "NULL", "'".$this->OBSERVACAO."'").",	
				SEQ_RDM_ORIGEM = ".$this->iif($this->SEQ_RDM_ORIGEM=="", "NULL", "'".$this->SEQ_RDM_ORIGEM."'").",			
				EMAIL_RESP_CHECKLIST = ".$this->iif($this->EMAIL_RESP_CHECKLIST=="", "NULL", "'".$this->EMAIL_RESP_CHECKLIST."'")."
			    WHERE SEQ_RDM_TEMPLATE = $id ";
		
		$result = $this->database->query($sql);
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
