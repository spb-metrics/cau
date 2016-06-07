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
* CLASSNAME:        andamento_projeto
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
class andamento_projeto{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_ANDAMENTO_PROJETO_SOFTWARE;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_STATUS_ANDAMENTO_PROJETO;   // (normal Attribute)
	var $SEQ_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $DAT_ANDAMENTO_PROJETO_SOFTWARE;   // (normal Attribute)
	var $NOM_MODULO;   // (normal Attribute)
	var $SEQ_FASE_PROJETO;   // (normal Attribute)
	var $DES_ANDAMENTO_PROJETO_SOFTWARE;   // (normal Attribute)
	var $DES_PROXIMOS_PASSOS;   // (normal Attribute)
	var $DES_JUSTIFICATIVA;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function andamento_projeto(){
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
	function getSEQ_ANDAMENTO_PROJETO_SOFTWARE(){
		return $this->SEQ_ANDAMENTO_PROJETO_SOFTWARE;
	}

	function getSEQ_STATUS_ANDAMENTO_PROJETO(){
		return $this->SEQ_STATUS_ANDAMENTO_PROJETO;
	}

	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	}

	function getDAT_ANDAMENTO_PROJETO_SOFTWARE(){
		return $this->DAT_ANDAMENTO_PROJETO_SOFTWARE;
	}

	function getNOM_MODULO(){
		return $this->NOM_MODULO;
	}

	function getSEQ_FASE_PROJETO(){
		return $this->SEQ_FASE_PROJETO;
	}

	function getDES_ANDAMENTO_PROJETO_SOFTWARE(){
		return $this->DES_ANDAMENTO_PROJETO_SOFTWARE;
	}

	function getDES_PROXIMOS_PASSOS(){
		return $this->DES_PROXIMOS_PASSOS;
	}

	function getDES_JUSTIFICATIVA(){
		return $this->DES_JUSTIFICATIVA;
	}

	// **********************
	// SETTER METHODS
	// **********************


	function setrowCount(){
		$this->rowCount = ;
	}

	function setvQtdRegistros(){
		$this->vQtdRegistros = ;
	}


	function setSEQ_ANDAMENTO_PROJETO_SOFTWARE($val){
		$this->SEQ_ANDAMENTO_PROJETO_SOFTWARE =  $val;
	}

	function setSEQ_STATUS_ANDAMENTO_PROJETO($val){
		$this->SEQ_STATUS_ANDAMENTO_PROJETO =  $val;
	}

	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}

	function setDAT_ANDAMENTO_PROJETO_SOFTWARE($val){
		$this->DAT_ANDAMENTO_PROJETO_SOFTWARE =  $val;
	}

	function setNOM_MODULO($val){
		$this->NOM_MODULO =  $val;
	}

	function setSEQ_FASE_PROJETO($val){
		$this->SEQ_FASE_PROJETO =  $val;
	}

	function setDES_ANDAMENTO_PROJETO_SOFTWARE($val){
		$this->DES_ANDAMENTO_PROJETO_SOFTWARE =  $val;
	}

	function setDES_PROXIMOS_PASSOS($val){
		$this->DES_PROXIMOS_PASSOS =  $val;
	}

	function setDES_JUSTIFICATIVA($val){
		$this->DES_JUSTIFICATIVA =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.andamento_projeto WHERE SEQ_ANDAMENTO_PROJETO_SOFTWARE = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_ANDAMENTO_PROJETO_SOFTWARE = $row->SEQ_ANDAMENTO_PROJETO_SOFTWARE;
		$this->SEQ_STATUS_ANDAMENTO_PROJETO = $row->SEQ_STATUS_ANDAMENTO_PROJETO;
		$this->SEQ_ITEM_CONFIGURACAO = $row->SEQ_ITEM_CONFIGURACAO;
		$this->DAT_ANDAMENTO_PROJETO_SOFTWARE = $row->DAT_ANDAMENTO_PROJETO_SOFTWARE;
		$this->NOM_MODULO = $row->NOM_MODULO;
		$this->SEQ_FASE_PROJETO = $row->SEQ_FASE_PROJETO;
		$this->DES_ANDAMENTO_PROJETO_SOFTWARE = $row->DES_ANDAMENTO_PROJETO_SOFTWARE;
		$this->DES_PROXIMOS_PASSOS = $row->DES_PROXIMOS_PASSOS;
		$this->DES_JUSTIFICATIVA = $row->DES_JUSTIFICATIVA;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT SEQ_ANDAMENTO_PROJETO_SOFTWARE , SEQ_STATUS_ANDAMENTO_PROJETO , SEQ_ITEM_CONFIGURACAO , DAT_ANDAMENTO_PROJETO_SOFTWARE , NOM_MODULO , SEQ_FASE_PROJETO , DES_ANDAMENTO_PROJETO_SOFTWARE , DES_PROXIMOS_PASSOS , DES_JUSTIFICATIVA ";
		$sqlCorpo  = " FROM gestaoti.andamento_projeto
			      WHERE 1=1 ";

		if($this->SEQ_ANDAMENTO_PROJETO_SOFTWARE != ""){
			$sqlCorpo .= "  and SEQ_ANDAMENTO_PROJETO_SOFTWARE = $this->SEQ_ANDAMENTO_PROJETO_SOFTWARE ";
		}
		if($this->SEQ_STATUS_ANDAMENTO_PROJETO != ""){
			$sqlCorpo .= "  and SEQ_STATUS_ANDAMENTO_PROJETO = $this->SEQ_STATUS_ANDAMENTO_PROJETO ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->DAT_ANDAMENTO_PROJETO_SOFTWARE != "" && $this->DAT_ANDAMENTO_PROJETO_SOFTWARE_FINAL == "" ){
			$sqlCorpo .= "  and DAT_ANDAMENTO_PROJETO_SOFTWARE >= '".ConvDataAMD($this->DAT_ANDAMENTO_PROJETO_SOFTWARE)."' ";
		}
		if($this->DAT_ANDAMENTO_PROJETO_SOFTWARE != "" && $this->DAT_ANDAMENTO_PROJETO_SOFTWARE_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ANDAMENTO_PROJETO_SOFTWARE between '".ConvDataAMD($this->DAT_ANDAMENTO_PROJETO_SOFTWARE)."' and '".ConvDataAMD($this->DAT_ANDAMENTO_PROJETO_SOFTWARE_FINAL)."' ";
		}
		if($this->DAT_ANDAMENTO_PROJETO_SOFTWARE == "" && $this->DAT_ANDAMENTO_PROJETO_SOFTWARE_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ANDAMENTO_PROJETO_SOFTWARE <= '".ConvDataAMD($this->DAT_ANDAMENTO_PROJETO_SOFTWARE_FINAL)."' ";
		}
		if($this->NOM_MODULO != ""){
			$sqlCorpo .= "  and upper(NOM_MODULO) like '%".strtoupper($this->NOM_MODULO)."%'  ";
		}
		if($this->SEQ_FASE_PROJETO != ""){
			$sqlCorpo .= "  and SEQ_FASE_PROJETO = '$this->SEQ_FASE_PROJETO' ";
		}
		if($this->DES_ANDAMENTO_PROJETO_SOFTWARE != ""){
			$sqlCorpo .= "  and upper(DES_ANDAMENTO_PROJETO_SOFTWARE) like '%".strtoupper($this->DES_ANDAMENTO_PROJETO_SOFTWARE)."%'  ";
		}
		if($this->DES_PROXIMOS_PASSOS != ""){
			$sqlCorpo .= "  and upper(DES_PROXIMOS_PASSOS) like '%".strtoupper($this->DES_PROXIMOS_PASSOS)."%'  ";
		}
		if($this->DES_JUSTIFICATIVA != ""){
			$sqlCorpo .= "  and upper(DES_JUSTIFICATIVA) like '%".strtoupper($this->DES_JUSTIFICATIVA)."%'  ";
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
		$sql = "DELETE FROM gestaoti.andamento_projeto WHERE SEQ_ANDAMENTO_PROJETO_SOFTWARE = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_ANDAMENTO_PROJETO_SOFTWARE = $this->database->GetSequenceValue("gestaoti.SEQ_"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.andamento_projeto ( SEQ_STATUS_ANDAMENTO_PROJETO,SEQ_ITEM_CONFIGURACAO,DAT_ANDAMENTO_PROJETO_SOFTWARE,NOM_MODULO,SEQ_FASE_PROJETO,DES_ANDAMENTO_PROJETO_SOFTWARE,DES_PROXIMOS_PASSOS,DES_JUSTIFICATIVA ) VALUES ( ".$this->database->iif($this->SEQ_STATUS_ANDAMENTO_PROJETO=="", "NULL", "'".$this->SEQ_STATUS_ANDAMENTO_PROJETO."'").",".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",".$this->database->iif($this->DAT_ANDAMENTO_PROJETO_SOFTWARE=="", "NULL", "'".$this->DAT_ANDAMENTO_PROJETO_SOFTWARE."'").",".$this->database->iif($this->NOM_MODULO=="", "NULL", "'".$this->NOM_MODULO."'").",".$this->database->iif($this->SEQ_FASE_PROJETO=="", "NULL", "'".$this->SEQ_FASE_PROJETO."'").",".$this->database->iif($this->DES_ANDAMENTO_PROJETO_SOFTWARE=="", "NULL", "'".$this->DES_ANDAMENTO_PROJETO_SOFTWARE."'").",".$this->database->iif($this->DES_PROXIMOS_PASSOS=="", "NULL", "'".$this->DES_PROXIMOS_PASSOS."'").",".$this->database->iif($this->DES_JUSTIFICATIVA=="", "NULL", "'".$this->DES_JUSTIFICATIVA."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.andamento_projeto SET  SEQ_STATUS_ANDAMENTO_PROJETO = ".$this->database->iif($this->SEQ_STATUS_ANDAMENTO_PROJETO=="", "NULL", "'".$this->SEQ_STATUS_ANDAMENTO_PROJETO."'").",SEQ_ITEM_CONFIGURACAO = ".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",DAT_ANDAMENTO_PROJETO_SOFTWARE = ".$this->database->iif($this->DAT_ANDAMENTO_PROJETO_SOFTWARE=="", "NULL", "'".$this->DAT_ANDAMENTO_PROJETO_SOFTWARE."'").",NOM_MODULO = ".$this->database->iif($this->NOM_MODULO=="", "NULL", "'".$this->NOM_MODULO."'").",SEQ_FASE_PROJETO = ".$this->database->iif($this->SEQ_FASE_PROJETO=="", "NULL", "'".$this->SEQ_FASE_PROJETO."'").",DES_ANDAMENTO_PROJETO_SOFTWARE = ".$this->database->iif($this->DES_ANDAMENTO_PROJETO_SOFTWARE=="", "NULL", "'".$this->DES_ANDAMENTO_PROJETO_SOFTWARE."'").",DES_PROXIMOS_PASSOS = ".$this->database->iif($this->DES_PROXIMOS_PASSOS=="", "NULL", "'".$this->DES_PROXIMOS_PASSOS."'").",DES_JUSTIFICATIVA = ".$this->database->iif($this->DES_JUSTIFICATIVA=="", "NULL", "'".$this->DES_JUSTIFICATIVA."'")." WHERE SEQ_ANDAMENTO_PROJETO_SOFTWARE = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>