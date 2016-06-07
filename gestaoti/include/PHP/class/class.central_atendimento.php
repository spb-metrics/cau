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
*
* -------------------------------------------------------
* CLASSNAME:        tipo_servico
* -------------------------------------------------------
*
*/

if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
}
 

// **********************
// CLASS DECLARATION
// **********************

class central_atendimento{ 
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_CENTRAL_ATENDIMENTO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina
	
	var $NOM_CENTRAL_ATENDIMENTO;   // (normal Attribute)
	var $SEQ_CENTRAL_ATENDIMENTO_REMOVER;  
	
	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function central_atendimento(){
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
	
	
	function getSEQ_CENTRAL_ATENDIMENTO(){
		return $this->SEQ_CENTRAL_ATENDIMENTO;
	}
	
	function getNOM_CENTRAL_ATENDIMENTO(){
		return $this->NOM_CENTRAL_ATENDIMENTO;
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
	
	
	function setSEQ_CENTRAL_ATENDIMENTO($val){
		$this->SEQ_CENTRAL_ATENDIMENTO =  $val;
	}
	
	function setNOM_CENTRAL_ATENDIMENTO($val){
		$this->NOM_CENTRAL_ATENDIMENTO =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id){
		$sql =  "SELECT SEQ_CENTRAL_ATENDIMENTO , NOM_CENTRAL_ATENDIMENTO FROM gestaoti.CENTRAL_ATENDIMENTO WHERE SEQ_CENTRAL_ATENDIMENTO =".$id;
		
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		
		$this->SEQ_CENTRAL_ATENDIMENTO = $row->seq_central_atendimento;
		$this->NOM_CENTRAL_ATENDIMENTO = $row->nom_central_atendimento;
	}
	
	
	function GetNomeCentral($id){
		if($id != ""){
			$sql =  "SELECT SEQ_CENTRAL_ATENDIMENTO , NOM_CENTRAL_ATENDIMENTO FROM gestaoti.CENTRAL_ATENDIMENTO WHERE SEQ_CENTRAL_ATENDIMENTO =".$id;
		
			$result =  $this->database->query($sql);
			$result = $this->database->result;
			if(!$result) $this->error = $this->database->error;
			$row = pg_fetch_object($result);
			return $row->nom_central_atendimento;
		}else{
			return "";
		}
	}
	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		
		$sqlSelect = " SELECT SEQ_CENTRAL_ATENDIMENTO , NOM_CENTRAL_ATENDIMENTO ";
		$sqlCorpo  = " FROM gestaoti.CENTRAL_ATENDIMENTO
			      WHERE 1=1 ";
			
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo .= "  and SEQ_CENTRAL_ATENDIMENTO = $this->SEQ_CENTRAL_ATENDIMENTO ";
		}
		if($this->SEQ_CENTRAL_ATENDIMENTO_REMOVER != ""){
			$sqlCorpo .= "  and SEQ_CENTRAL_ATENDIMENTO <> $this->SEQ_CENTRAL_ATENDIMENTO_REMOVER ";
		}
		if($this->NOM_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo .= "  and upper(NOM_CENTRAL_ATENDIMENTO) like '%".strtoupper($this->NOM_CENTRAL_ATENDIMENTO)."%'  ";
		}
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
		
		
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder); 
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id){
		$sql = "DELETE FROM gestaoti.CENTRAL_ATENDIMENTO WHERE SEQ_CENTRAL_ATENDIMENTO = $id;";
		$result = $this->database->query($sql); 
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert(){
		
		$this->SEQ_CENTRAL_ATENDIMENTO = $this->database->GetSequenceValue("gestaoti.SEQ_CENTRAL_ATENDIMENTO");
		//$this->SEQ_CENTRAL_ATENDIMENTO = ""; // clear key for autoincrement
		
		$sql = "INSERT INTO gestaoti.CENTRAL_ATENDIMENTO ( SEQ_CENTRAL_ATENDIMENTO,NOM_CENTRAL_ATENDIMENTO ) 
		VALUES (".$this->iif($this->SEQ_CENTRAL_ATENDIMENTO=="", "NULL", "'".$this->SEQ_CENTRAL_ATENDIMENTO."'").",
		  ".$this->iif($this->NOM_CENTRAL_ATENDIMENTO=="", "NULL", "'".$this->NOM_CENTRAL_ATENDIMENTO."'")." )";
		
		$result = $this->database->query($sql);  
		
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id){
		$sql = " UPDATE gestaoti.CENTRAL_ATENDIMENTO SET  NOM_CENTRAL_ATENDIMENTO = ".$this->iif($this->NOM_CENTRAL_ATENDIMENTO=="", "NULL", "'".$this->NOM_CENTRAL_ATENDIMENTO."'")." WHERE SEQ_CENTRAL_ATENDIMENTO = $id ";
		$result = $this->database->query($sql); 
	
	}
	
	function combo($OrderBy, $vSelected="", $vRemover=""){
		
		if($vRemover!=""){
			 $this->SEQ_CENTRAL_ATENDIMENTO_REMOVER = $vRemover;
		} 
		
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