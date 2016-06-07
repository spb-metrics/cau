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
* CLASSNAME:        area_externa
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
    include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{    
    include_once("include/PHP/class/class.database.postgres.php");
}
// **********************
// CLASS DECLARATION
// **********************
class area_externa{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_AREA_EXTERNA;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_AREA_EXTERNA;   // (normal Attribute)
        var $FLG_SISP;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function area_externa(){
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
	function getSEQ_AREA_EXTERNA(){
		return $this->SEQ_AREA_EXTERNA;
	}

	function getNOM_AREA_EXTERNA(){
		return $this->NOM_AREA_EXTERNA;
	}
        function getFLG_SISP(){
		return $this->FLG_SISP;
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
	function setSEQ_AREA_EXTERNA($val){
		$this->SEQ_AREA_EXTERNA =  $val;
	}

	function setNOM_AREA_EXTERNA($val){
		$this->NOM_AREA_EXTERNA =  $val;
	}
        function setFLG_SISP($val){
		$this->FLG_SISP =  $val;
	}
        

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.area_externa WHERE SEQ_AREA_EXTERNA = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_AREA_EXTERNA = $row->seq_area_externa;
		$this->NOM_AREA_EXTERNA = $row->nom_area_externa;
                $this->FLG_SISP = $row->flg_sisp;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_AREA_EXTERNA , NOM_AREA_EXTERNA ";
		$sqlCorpo  = "FROM gestaoti.area_externa
						WHERE 1=1 ";

		if($this->SEQ_AREA_EXTERNA != ""){
			$sqlCorpo .= "  and SEQ_AREA_EXTERNA = $this->SEQ_AREA_EXTERNA ";
		}
		if($this->NOM_AREA_EXTERNA != ""){
			$sqlCorpo .= "  and upper(NOM_AREA_EXTERNA) like '%".mb_strtoupper($this->NOM_AREA_EXTERNA,'LATIN1')."%'  ";
                        
		}
                if($this->FLG_SISP != ""){
			$sqlCorpo .= "  and upper(FLG_SISP) like '%".strtoupper($this->FLG_SISP)."%'  ";
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

		//print $sqlSelect . $sqlCorpo . $sqlOrder."<br>";
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}

	// **********************
	// DELETE
	// **********************
	function delete($id){
		$sql = "DELETE FROM gestaoti.area_externa WHERE SEQ_AREA_EXTERNA = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_AREA_EXTERNA = $this->database->GetSequenceValue("gestaoti.SEQ_AREA_EXTERNA"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.area_externa (SEQ_AREA_EXTERNA,
										  NOM_AREA_EXTERNA,
                                                                                  FLG_SISP)
				VALUES (".$this->SEQ_AREA_EXTERNA.",
				 	".$this->database->iif($this->NOM_AREA_EXTERNA=="", "NULL", "'".$this->NOM_AREA_EXTERNA."'").",
                                        ".$this->database->iif($this->FLG_SISP=="", "NULL", "'".$this->FLG_SISP."'").")";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.area_externa 
                         SET  
                            NOM_AREA_EXTERNA = ".$this->database->iif($this->NOM_AREA_EXTERNA=="", "NULL", "'".$this->NOM_AREA_EXTERNA."'").",
                            FLG_SISP = ".$this->database->iif($this->FLG_SISP=="", "NULL", "'".$this->FLG_SISP."'")." 
                         WHERE SEQ_AREA_EXTERNA = $id ";
		$result = $this->database->query($sql);
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