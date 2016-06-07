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
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
}

// **********************
// CLASS DECLARATION
// **********************

class tipo_funcao_administrativa{ 
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_TIPO_FUNCAO_ADMINISTRATIVA;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina
	
	var $NOM_TIPO_FUNCAO_ADMINISTRATIVA;   // (normal Attribute)
	
	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function tipo_funcao_administrativa(){
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
	
	
	function getSEQ_TIPO_FUNCAO_ADMINISTRATIVA(){
		return $this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA;
	}
	
	function getNOM_TIPO_FUNCAO_ADMINISTRATIVA(){
		return $this->NOM_TIPO_FUNCAO_ADMINISTRATIVA;
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
	
	
	function setSEQ_TIPO_FUNCAO_ADMINISTRATIVA($val){
		$this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA =  $val;
	}
	
	function setNOM_TIPO_FUNCAO_ADMINISTRATIVA($val){
		$this->NOM_TIPO_FUNCAO_ADMINISTRATIVA =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id){
		$sql =  "SELECT * FROM gestaoti.tipo_funcao_administrativa WHERE seq_tipo_funcao_administrativa = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result);
		$this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA = $row->seq_tipo_funcao_administrativa;
		$this->NOM_TIPO_FUNCAO_ADMINISTRATIVA = $row->nom_tipo_funcao_administrativa;
	}
	
	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		
		$sqlSelect = " SELECT SEQ_TIPO_FUNCAO_ADMINISTRATIVA , NOM_TIPO_FUNCAO_ADMINISTRATIVA ";
		$sqlCorpo  = " FROM gestaoti.tipo_funcao_administrativa
			      WHERE 1=1 ";
			
		if($this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA != ""){
			$sqlCorpo .= "  and SEQ_TIPO_FUNCAO_ADMINISTRATIVA = $this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA ";
		}
		if($this->NOM_TIPO_FUNCAO_ADMINISTRATIVA != ""){
			$sqlCorpo .= "  and upper(NOM_TIPO_FUNCAO_ADMINISTRATIVA) like '%".mb_strtoupper($this->NOM_TIPO_FUNCAO_ADMINISTRATIVA, 'LATIN1')."%'  ";
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
		$sql = "DELETE FROM gestaoti.tipo_funcao_administrativa WHERE SEQ_TIPO_FUNCAO_ADMINISTRATIVA = $id;";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert(){
		$this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA = $this->database->GetsequenceValue("gestaoti.seq_tipo_funcao_administrativa"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.tipo_funcao_administrativa (seq_tipo_funcao_administrativa, nom_tipo_funcao_administrativa )
				VALUES (".$this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA.",
					".$this->database->iif($this->NOM_TIPO_FUNCAO_ADMINISTRATIVA=="", "NULL", "'".$this->NOM_TIPO_FUNCAO_ADMINISTRATIVA."'")." )";
		$result = $this->database->query($sql);
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id){
		$sql = " UPDATE gestaoti.tipo_funcao_administrativa 
                         SET  NOM_TIPO_FUNCAO_ADMINISTRATIVA = ".$this->iif($this->NOM_TIPO_FUNCAO_ADMINISTRATIVA=="", "NULL", "'".$this->NOM_TIPO_FUNCAO_ADMINISTRATIVA."'")." 
                         WHERE SEQ_TIPO_FUNCAO_ADMINISTRATIVA = $id ";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();
	
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