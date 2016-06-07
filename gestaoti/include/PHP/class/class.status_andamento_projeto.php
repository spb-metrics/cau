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
*
* -------------------------------------------------------
* CLASSNAME:        status_andamento_projeto
* -------------------------------------------------------
*
*/
include_once("include/PHP/class/class.database.php");

// **********************
// CLASS DECLARATION
// **********************

class status_andamento_projeto{ 
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_STATUS_ANDAMENTO_PROJETO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página
	
	var $NOM_STATUS_ANDAMENTO_PROJETO;   // (normal Attribute)
	
	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function status_andamento_projeto(){
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
	
	
	function getSEQ_STATUS_ANDAMENTO_PROJETO(){
		return $this->SEQ_STATUS_ANDAMENTO_PROJETO;
	}
	
	function getNOM_STATUS_ANDAMENTO_PROJETO(){
		return $this->NOM_STATUS_ANDAMENTO_PROJETO;
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
	
	
	function setSEQ_STATUS_ANDAMENTO_PROJETO($val){
		$this->SEQ_STATUS_ANDAMENTO_PROJETO =  $val;
	}
	
	function setNOM_STATUS_ANDAMENTO_PROJETO($val){
		$this->NOM_STATUS_ANDAMENTO_PROJETO =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id){
		$sql =  "SELECT * FROM gestaoti.status_andamento_projeto WHERE SEQ_STATUS_ANDAMENTO_PROJETO = $id;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = mysql_error();
		$row = mysql_fetch_object($result);
		
		$this->SEQ_STATUS_ANDAMENTO_PROJETO = $row->SEQ_STATUS_ANDAMENTO_PROJETO;
		$this->NOM_STATUS_ANDAMENTO_PROJETO = $row->NOM_STATUS_ANDAMENTO_PROJETO;
	}
	
	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		
		$sqlSelect = " SELECT SEQ_STATUS_ANDAMENTO_PROJETO , NOM_STATUS_ANDAMENTO_PROJETO ";
		$sqlCorpo  = " FROM gestaoti.status_andamento_projeto
			      WHERE 1=1 ";
			
		if($this->SEQ_STATUS_ANDAMENTO_PROJETO != ""){
			$sqlCorpo .= "  and SEQ_STATUS_ANDAMENTO_PROJETO = $this->SEQ_STATUS_ANDAMENTO_PROJETO ";
		}
		if($this->NOM_STATUS_ANDAMENTO_PROJETO != ""){
			$sqlCorpo .= "  and upper(NOM_STATUS_ANDAMENTO_PROJETO) like '%".strtoupper($this->NOM_STATUS_ANDAMENTO_PROJETO)."%'  ";
		}
		if($orderBy != "" ){
			$sqlOrder = " order by $orderBy ";
		}
		
		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);
			$sqlOrder .= " limit $vLimit, $vQtdRegistros ";
			$this->database->query("select count(1) " . $sqlCorpo);
			$rowCount = mysql_fetch_array($this->database->result, MYSQL_NUM);
			$this->setrowCount($rowCount[0]);
		}
		
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
		if(!$this->database->result) $this->error = mysql_error();
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id){
		$sql = "DELETE FROM gestaoti.status_andamento_projeto WHERE SEQ_STATUS_ANDAMENTO_PROJETO = $id;";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert(){
		$this->SEQ_STATUS_ANDAMENTO_PROJETO = ""; // clear key for autoincrement
		
		$sql = "INSERT INTO gestaoti.status_andamento_projeto ( NOM_STATUS_ANDAMENTO_PROJETO ) VALUES ( ".$this->iif($this->NOM_STATUS_ANDAMENTO_PROJETO=="", "NULL", "'".$this->NOM_STATUS_ANDAMENTO_PROJETO."'")." )";
		$result = $this->database->query($sql);
		$this->SEQ_STATUS_ANDAMENTO_PROJETO = mysql_insert_id($this->database->link);
		if(!$result) $this->error = mysql_error();
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id){
		$sql = " UPDATE gestaoti.status_andamento_projeto SET  NOM_STATUS_ANDAMENTO_PROJETO = ".$this->iif($this->NOM_STATUS_ANDAMENTO_PROJETO=="", "NULL", "'".$this->NOM_STATUS_ANDAMENTO_PROJETO."'")." WHERE SEQ_STATUS_ANDAMENTO_PROJETO = $id ";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();
	
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