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
class atividade_rdm_template{
	// class : begin
	  
	// ***********************
	// DECLARA��O DE ATRIBUTOS
	// ***********************
	var $SEQ_ATIVIDADE_RDM_TEMPLATE;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $SEQ_RDM_TEMPLATE;   // (normal Attribute)
	var $SEQ_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $SEQ_TIPO_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $SEQ_SERVIDOR;   // (normal Attribute)
	var $ORDEM;   // (normal Attribute) 
	var $DESCRICAO;   // (normal Attribute)	 
	var $SEQ_EQUIPE_TI; 
	
	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function atividade_rdm_template(){ 
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

	function getSEQ_ATIVIDADE_RDM_TEMPLATE(){
		return $this->SEQ_ATIVIDADE_RDM_TEMPLATE;
	}

	function getSEQ_RDM_TEMPLATE(){
		return $this->SEQ_RDM_TEMPLATE;
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
	 
	function getDESCRICAO(){
		return $this->DESCRICAO;
	}
	
	 
	function getSEQ_EQUIPE_TI(){
		return $this->SEQ_EQUIPE_TI;
	}	
	function getORDEM(){
		return $this->ORDEM;
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

	function setSEQ_ATIVIDADE_RDM_TEMPLATE($val){
		$this->SEQ_ATIVIDADE_RDM_TEMPLATE =  $val;
	}

	function setSEQ_RDM_TEMPLATE($val){
		$this->SEQ_RDM_TEMPLATE =  $val;
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
	 

	function setDESCRICAO($val){
		$this->DESCRICAO =  $val;
	}
	 
	
	function setSEQ_EQUIPE_TI($val){
		$this->SEQ_EQUIPE_TI =  $val;
	}
	
	function setORDEM($val){
		$this->ORDEM =  $val;
	} 

	 
	// **********************
	// SELECT METHOD / LOAD
	// **********************	

	function select($id){
		$sql = "SELECT SEQ_ATIVIDADE_RDM_TEMPLATE ,ORDEM, SEQ_RDM_TEMPLATE , SEQ_ITEM_CONFIGURACAO , SEQ_SERVIDOR , DESCRICAO,
				SEQ_EQUIPE_TI FROM gestaoti.atividade_rdm_template
				WHERE SEQ_ATIVIDADE_RDM_TEMPLATE = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_ATIVIDADE_RDM_TEMPLATE = $row->seq_atividade_rdm_template;
		$this->SEQ_RDM_TEMPLATE = $row->seq_rdm_template;
		$this->SEQ_ITEM_CONFIGURACAO = $row->seq_item_configuracao;
		$this->SEQ_SERVIDOR = $row->seq_servidor;		 
		$this->DESCRICAO = $row->descricao;		
		$this->SEQ_EQUIPE_TI  = $row->seq_equipe_ti;
		$this->ORDEM  = $row->ordem;	 
		 
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_ATIVIDADE_RDM_TEMPLATE ,ORDEM, SEQ_RDM_TEMPLATE , SEQ_ITEM_CONFIGURACAO , SEQ_SERVIDOR ,
							 DESCRICAO,SEQ_EQUIPE_TI ";
		$sqlCorpo  = "FROM gestaoti.atividade_rdm_template 
					  WHERE 1 = 1 ";

		if($this->SEQ_ATIVIDADE_RDM_TEMPLATE != ""){
			$sqlCorpo .= "  and SEQ_ATIVIDADE_RDM_TEMPLATE = $this->SEQ_ATIVIDADE_RDM_TEMPLATE";
		}
		if($this->SEQ_RDM_TEMPLATE != ""){
			$sqlCorpo .= "  and SEQ_RDM_TEMPLATE = $this->SEQ_RDM_TEMPLATE ";
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
		$sql = "DELETE FROM gestaoti.atividade_rdm_template WHERE SEQ_ATIVIDADE_RDM_TEMPLATE = $id";
		$result = $this->database->query($sql);
	}
	// **********************
	function deleteByRDM($id){
		$sql = "DELETE FROM gestaoti.atividade_rdm_template WHERE SEQ_RDM_TEMPLATE = $id";
		$result = $this->database->query($sql);
	}
	

	// **********************
	// INSERT
	// **********************
	
	function insert(){
		$this->SEQ_ATIVIDADE_RDM_TEMPLATE = $this->database->GetSequenceValue("gestaoti.SEQ_ATIVIDADE_RDM_TEMPLATE");
		
		$sql = "INSERT INTO gestaoti.atividade_rdm_template(SEQ_ATIVIDADE_RDM_TEMPLATE,
										  SEQ_RDM_TEMPLATE,
										  SEQ_ITEM_CONFIGURACAO,
										  SEQ_SERVIDOR,										  
										  DESCRICAO,									   
										  ORDEM,
										  SEQ_EQUIPE_TI
									)
							 VALUES (".$this->iif($this->SEQ_ATIVIDADE_RDM_TEMPLATE=="", "NULL", "'".$this->SEQ_ATIVIDADE_RDM_TEMPLATE."'").",
									 ".$this->iif($this->SEQ_RDM_TEMPLATE=="", "NULL", "'".$this->SEQ_RDM_TEMPLATE."'").",
									 ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
									 ".$this->iif($this->SEQ_SERVIDOR=="", "NULL", "'".$this->SEQ_SERVIDOR."'").",									 
									 ".$this->iif($this->DESCRICAO=="", "NULL", "'".$this->DESCRICAO."'").",									  
									 ".$this->iif($this->ORDEM=="", "NULL", "'".$this->ORDEM."'").",
									  ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'")."									   
							 		) ";
		
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// ********************** 
	
	function update($id){
		$sql = " UPDATE gestaoti.atividade_rdm_template
				 SET SEQ_RDM_TEMPLATE = ".$this->iif($this->SEQ_RDM_TEMPLATE=="", "NULL", "'".$this->SEQ_RDM_TEMPLATE."'").",
					 SEQ_ITEM_CONFIGURACAO = ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
					 SEQ_SERVIDOR = ".$this->iif($this->SEQ_SERVIDOR=="", "NULL", "'".$this->SEQ_SERVIDOR."'").",					 
					 DESCRICAO = ".$this->iif($this->DESCRICAO=="", "NULL", "'".$this->DESCRICAO."'").",					 
					 ORDEM = ".$this->iif($this->ORDEM=="", "NULL", "'".$this->ORDEM."'").",
					 SEQ_EQUIPE_TI = ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'")."					 
				WHERE SEQ_ATIVIDADE_RDM_TEMPLATE = $id ";		 
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