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
* CLASSNAME:        feriado
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
class feriado{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_FERIADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_FERIADO;   // (normal Attribute)
        var $DTH_FERIADO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados
	var $parametro;

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function feriado(){
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
	function getSEQ_FERIADO(){
		return $this->SEQ_FERIADO;
	}

	function getNOM_FERIADO(){
		return $this->NOM_FERIADO;
	}
        
        function getDTH_FERIADO(){
		return $this->DTH_FERIADO;
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
	function setSEQ_FERIADO($val){
		$this->SEQ_FERIADO =  $val;
	}

	function setNOM_FERIADO($val){
		$this->NOM_FERIADO =  $val;
	}
        
        function setDTH_FERIADO($val){
		$this->DTH_FERIADO =  $val;
	}


	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.feriado WHERE seq_feriado = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result);
		$this->SEQ_FERIADO = $row->seq_feriado;
		$this->NOM_FERIADO = $row->nom_feriado;
                $this->DTH_FERIADO = $row->dth_feriado;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT seq_feriado , nom_feriado, dth_feriado ";
		$sqlCorpo  = "FROM gestaoti.feriado
			      WHERE 1=1 ";

		if($this->SEQ_FERIADO != ""){
			$sqlCorpo .= "  and seq_feriado = $this->SEQ_FERIADO ";
		}
		if($this->NOM_FERIADO != ""){
			$sqlCorpo .= "  and upper(nom_feriado) like '%".mb_strtoupper($this->NOM_FERIADO,'LATIN1')."%'  ";
		}
                if($this->DTH_FERIADO != ""){
			$sqlCorpo .= "  and dth_feriado = '$this->DTH_FERIADO'  ";
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
		$sql = "DELETE FROM gestaoti.feriado WHERE seq_feriado = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->seq_feriado = $this->database->GetsequenceValue("gestaoti.seq_feriado"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.feriado (seq_feriado, nom_feriado, dth_feriado )
                        VALUES (".$this->seq_feriado.",
                                ".$this->database->iif($this->NOM_FERIADO=="", "NULL", "'".$this->NOM_FERIADO."'").",
                                ".$this->database->iif($this->DTH_FERIADO=="", "NULL", "'".$this->DTH_FERIADO."'")."    )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.feriado
                         SET  nom_feriado = ".$this->database->iif($this->NOM_FERIADO=="", "NULL", "'".$this->NOM_FERIADO."'").",
                              dth_feriado = ".$this->database->iif($this->DTH_FERIADO=="", "NULL", "'".$this->DTH_FERIADO."'")."
                         WHERE seq_feriado = $id ";
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