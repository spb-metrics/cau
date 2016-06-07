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
* CLASSNAME:        unidade_organizacional
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	require_once "../gestaoti/include/PHP/class/class.database.postgres.php";
}else{
	require_once "include/PHP/class/class.database.postgres.php";
}

// **********************
// CLASS DECLARATION
// **********************
class unidade_organizacional{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_UNIDADE_ORGANIZACIONAL;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina
        var $NOM_UNIDADE_ORGANIZACIONAL;   // (normal Attribute)
        var $SEQ_UNIDADE_ORGANIZACIONAL_PAI;
        var $SGL_UNIDADE_ORGANIZACIONAL;   // (normal Attribute)
        
	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados
	var $parametro;

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function unidade_organizacional(){
		$this->database = new Database();
		$this->parametro = new parametro();
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
	function getSEQ_UNIDADE_ORGANIZACIONAL(){
		return $this->SEQ_UNIDADE_ORGANIZACIONAL;
	}

	function getNOM_UNIDADE_ORGANIZACIONAL(){
		return $this->NOM_UNIDADE_ORGANIZACIONAL;
	}
        
    function getSEQ_UNIDADE_ORGANIZACIONAL_PAI(){
		return $this->SEQ_UNIDADE_ORGANIZACIONAL_PAI;
	}
        
    function getSGL_UNIDADE_ORGANIZACIONAL(){
		return $this->SGL_UNIDADE_ORGANIZACIONAL;
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
	function setSEQ_UNIDADE_ORGANIZACIONAL($val){
		$this->SEQ_UNIDADE_ORGANIZACIONAL =  $val;
	}

	function setNOM_UNIDADE_ORGANIZACIONAL($val){
		$this->NOM_UNIDADE_ORGANIZACIONAL =  $val;
	}
        
        function setSEQ_UNIDADE_ORGANIZACIONAL_PAI($val){
		$this->SEQ_UNIDADE_ORGANIZACIONAL_PAI =  $val;
	}
        
        function setSGL_UNIDADE_ORGANIZACIONAL($val){
		$this->SGL_UNIDADE_ORGANIZACIONAL =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.unidade_organizacional WHERE seq_unidade_organizacional = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result);
		$this->SEQ_UNIDADE_ORGANIZACIONAL = $row->seq_unidade_organizacional;
		$this->NOM_UNIDADE_ORGANIZACIONAL = $row->nom_unidade_organizacional;
		$this->SEQ_UNIDADE_ORGANIZACIONAL_PAI = $row->seq_unidade_organizacional_pai;
		$this->SGL_UNIDADE_ORGANIZACIONAL = $row->sgl_unidade_organizacional;
	}
	
	function GetUorSigla($id){
		$sql =  "SELECT * FROM gestaoti.unidade_organizacional WHERE seq_unidade_organizacional = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result);
		return $row->sgl_unidade_organizacional;
	}
	
	function GetUorNome($id){
		$sql =  "SELECT * FROM gestaoti.unidade_organizacional WHERE seq_unidade_organizacional = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result);
		return $row->nom_unidade_organizacional;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT * ";
		$sqlCorpo  = "FROM gestaoti.unidade_organizacional
                              WHERE 1=1 ";

		if($this->SEQ_UNIDADE_ORGANIZACIONAL != ""){
			$sqlCorpo .= "  and seq_unidade_organizacional = $this->SEQ_UNIDADE_ORGANIZACIONAL ";
		}
		if($this->NOM_UNIDADE_ORGANIZACIONAL != ""){
			$sqlCorpo .= "  and upper(nom_unidade_organizacional) like '%".mb_strtoupper($this->NOM_UNIDADE_ORGANIZACIONAL,'LATIN1')."%'  ";
		}
                if($this->SEQ_UNIDADE_ORGANIZACIONAL_PAI != ""){
			$sqlCorpo .= "  and seq_unidade_organizacional_pai = $this->SEQ_UNIDADE_ORGANIZACIONAL_PAI  ";
		}
                if($this->SGL_UNIDADE_ORGANIZACIONAL != ""){
			$sqlCorpo .= "  and upper(sgl_unidade_organizacional) like '%".mb_strtoupper($this->SGL_UNIDADE_ORGANIZACIONAL,'LATIN1')."%'  ";
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
		$sql = "DELETE FROM gestaoti.unidade_organizacional WHERE seq_unidade_organizacional = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_UNIDADE_ORGANIZACIONAL = $this->database->GetsequenceValue("gestaoti.seq_unidade_organizacional"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.unidade_organizacional (seq_unidade_organizacional, 
                                                                     nom_unidade_organizacional,
                                                                     seq_unidade_organizacional_pai,
                                                                     sgl_unidade_organizacional)
                        VALUES (".$this->SEQ_UNIDADE_ORGANIZACIONAL.",
                                ".$this->database->iif($this->NOM_UNIDADE_ORGANIZACIONAL=="", "NULL", "'".$this->NOM_UNIDADE_ORGANIZACIONAL."'").",
                                ".$this->database->iif($this->SEQ_UNIDADE_ORGANIZACIONAL_PAI=="", "NULL", "'".$this->SEQ_UNIDADE_ORGANIZACIONAL_PAI."'").",
                                ".$this->database->iif($this->SGL_UNIDADE_ORGANIZACIONAL=="", "NULL", "'".$this->SGL_UNIDADE_ORGANIZACIONAL."'")."
                                )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.unidade_organizacional
                         SET  
                            nom_unidade_organizacional = ".$this->database->iif($this->NOM_UNIDADE_ORGANIZACIONAL=="", "NULL", "'".$this->NOM_UNIDADE_ORGANIZACIONAL."'").",
                            seq_unidade_organizacional_pai = ".$this->database->iif($this->SEQ_UNIDADE_ORGANIZACIONAL_PAI=="", "NULL", "'".$this->SEQ_UNIDADE_ORGANIZACIONAL_PAI."'").",
                            sgl_unidade_organizacional = ".$this->database->iif($this->SGL_UNIDADE_ORGANIZACIONAL=="", "NULL", "'".$this->SGL_UNIDADE_ORGANIZACIONAL."'")."
                         WHERE seq_unidade_organizacional = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $vSelected == $row[0]?"Selected":"", $row[1]);
			$cont++;
		}
		return $aItemOption;
	}

} // class : end
?>