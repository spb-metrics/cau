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
* CLASSNAME:        tipo_ocorrencia
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
class parametro{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página
	var $COD_PARAMETRO;   // KEY ATTR. WITH AUTOINCREMENT
	var $NOM_PARAMETRO;
	var $VAL_PARAMETRO;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function parametro(){
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
	function getCOD_PARAMETRO(){
		return $this->COD_PARAMETRO;
	}

	function getNOM_PARAMETRO(){
		return $this->NOM_PARAMETRO;
	}

	function getVAL_PARAMETRO(){
		return $this->VAL_PARAMETRO;
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
	function setCOD_PARAMETRO($val){
		$this->COD_PARAMETRO =  $val;
	}

	function setNOM_PARAMETRO($val){
		$this->NOM_PARAMETRO =  $val;
	}

	function setVAL_PARAMETRO($val){
		$this->VAL_PARAMETRO =  $val;
	}
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.parametro WHERE COD_PARAMETRO = '$id'";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result);
		$this->COD_PARAMETRO = $row->cod_parametro;
		$this->NOM_PARAMETRO = $row->nom_parametro;
		$this->VAL_PARAMETRO = $row->val_parametro;
	}

	function GetValorParametro($id){
		$sql =  "SELECT VAL_PARAMETRO
				 FROM gestaoti.parametro
				 WHERE COD_PARAMETRO = '$id'";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		if($this->database->rows > 0){
			$row = pg_fetch_object($result);
			return $row->val_parametro;
		}else{
			return "";
		}
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT COD_PARAMETRO, NOM_PARAMETRO, VAL_PARAMETRO ";
		$sqlCorpo  = "FROM gestaoti.parametro
					  WHERE 1=1 ";

		if($this->COD_PARAMETRO != ""){
			$sqlCorpo .= "  and COD_PARAMETRO = $this->COD_PARAMETRO ";
		}
		if($this->NOM_PARAMETRO != ""){
			$sqlCorpo .= "  and upper(NOM_PARAMETRO) like '%".strtoupper($this->NOM_PARAMETRO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.parametro WHERE COD_PARAMETRO = '$id'";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.parametro (COD_PARAMETRO, NOM_PARAMETRO, VAL_PARAMETRO)
				VALUES ('".$this->COD_PARAMETRO."',
						".$this->database->iif($this->NOM_PARAMETRO=="", "NULL", "'".$this->NOM_PARAMETRO."'").",
						".$this->database->iif($this->VAL_PARAMETRO=="", "NULL", "'".$this->VAL_PARAMETRO."'").
						" )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.parametro
				 SET
				 	NOM_PARAMETRO = ".$this->database->iif($this->NOM_PARAMETRO=="", "NULL", "'".$this->NOM_PARAMETRO."'").",
				 	VAL_PARAMETRO = ".$this->database->iif($this->VAL_PARAMETRO=="", "NULL", "'".$this->VAL_PARAMETRO."'")."
				 WHERE COD_PARAMETRO = '$id' ";
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