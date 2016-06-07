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
* CLASSNAME:        tarefa
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class tarefa_ti{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_TAREFA_TI;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NUM_MATRICULA_RECURSO;   // (normal Attribute)
	var $SEQ_OS;   // (normal Attribute)
	var $SEQ_EPM;   // (normal Attribute)
	var $SEQ_STATUS_TAREFA_TI;   // (normal Attribute)
	var $DAT_CRIACAO_TAREFA;   // (normal Attribute)
	var $DAT_ATUALIZACAO_TAREFA;   // (normal Attribute)
	var $NUM_MATRICULA_LIDER;   // (normal Attribute)
	var $FLG_APROVA_EXTRA;   // (normal Attribute)
	var $NUM_MATRICULA_LIDER_APROVA;   // (normal Attribute)
	var $DAT_APROVACAO_EXTRA;   // (normal Attribute)
	var $QTD_HORA_TOTAL_UTEIS;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function tarefa_ti(){
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
	function getSEQ_TAREFA_TI(){
		return $this->SEQ_TAREFA_TI;
	}

	function getNUM_MATRICULA_RECURSO(){
		return $this->NUM_MATRICULA_RECURSO;
	}

	function getSEQ_OS(){
		return $this->SEQ_OS;
	}

	function getSEQ_EPM(){
		return $this->SEQ_EPM;
	}

	function getSEQ_STATUS_TAREFA_TI(){
		return $this->SEQ_STATUS_TAREFA_TI;
	}

	function getDAT_CRIACAO_TAREFA(){
		return $this->DAT_CRIACAO_TAREFA;
	}

	function getDAT_ATUALIZACAO_TAREFA(){
		return $this->DAT_ATUALIZACAO_TAREFA;
	}

	function getNUM_MATRICULA_LIDER(){
		return $this->NUM_MATRICULA_LIDER;
	}

	function getFLG_APROVA_EXTRA(){
		return $this->FLG_APROVA_EXTRA;
	}

	function getNUM_MATRICULA_LIDER_APROVA(){
		return $this->NUM_MATRICULA_LIDER_APROVA;
	}

	function getDAT_APROVACAO_EXTRA(){
		return $this->DAT_APROVACAO_EXTRA;
	}

	function getQTD_HORA_TOTAL_UTEIS(){
		return $this->QTD_HORA_TOTAL_UTEIS;
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
	function setSEQ_TAREFA_TI($val){
		$this->SEQ_TAREFA_TI =  $val;
	}

	function setNUM_MATRICULA_RECURSO($val){
		$this->NUM_MATRICULA_RECURSO =  $val;
	}

	function setSEQ_OS($val){
		$this->SEQ_OS =  $val;
	}

	function setSEQ_EPM($val){
		$this->SEQ_EPM =  $val;
	}

	function setSEQ_STATUS_TAREFA_TI($val){
		$this->SEQ_STATUS_TAREFA_TI =  $val;
	}

	function setDAT_CRIACAO_TAREFA($val){
		$this->DAT_CRIACAO_TAREFA =  $val;
	}

	function setDAT_ATUALIZACAO_TAREFA($val){
		$this->DAT_ATUALIZACAO_TAREFA =  $val;
	}

	function setNUM_MATRICULA_LIDER($val){
		$this->NUM_MATRICULA_LIDER =  $val;
	}

	function setFLG_APROVA_EXTRA($val){
		$this->FLG_APROVA_EXTRA =  $val;
	}

	function setNUM_MATRICULA_LIDER_APROVA($val){
		$this->NUM_MATRICULA_LIDER_APROVA =  $val;
	}

	function setDAT_APROVACAO_EXTRA($val){
		$this->DAT_APROVACAO_EXTRA =  $val;
	}

	function setQTD_HORA_TOTAL_UTEIS($val){
		$this->QTD_HORA_TOTAL_UTEIS =  $val;
	}

	function setFLG_PROJETO($val){
		$this->FLG_PROJETO =  $val;
	}

	function setFLG_OS($val){
		$this->FLG_OS =  $val;
	}

	function setFLG_VALIDA_DTCORRENTE($val){
		$this->FLG_VALIDA_DTCORRENTE =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.tarefa WHERE SEQ_TAREFA_TI = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_TAREFA_TI = $row->seq_tarefa_ti;
		$this->NUM_MATRICULA_RECURSO = $row->num_matricula_recurso;
		$this->SEQ_OS = $row->seq_os;
		$this->SEQ_EPM = $row->seq_epm;
		$this->SEQ_STATUS_TAREFA_TI = $row->seq_status_tarefa_ti;
		$this->DAT_CRIACAO_TAREFA = $row->dat_criacao_tarefa;
		$this->DAT_ATUALIZACAO_TAREFA = $row->dat_atualizacao_tarefa;
		$this->NUM_MATRICULA_LIDER = $row->num_matricula_lider;
		$this->FLG_APROVA_EXTRA = $row->flg_aprova_extra;
		$this->NUM_MATRICULA_LIDER_APROVA = $row->num_matricula_lider_aprova;
		$this->DAT_APROVACAO_EXTRA = $row->dat_aprovacao_extra;
		$this->QTD_HORA_TOTAL_UTEIS = $row->qtd_hora_total_uteis;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_TAREFA_TI , NUM_MATRICULA_RECURSO , SEQ_OS , SEQ_EPM , SEQ_STATUS_TAREFA_TI , DAT_CRIACAO_TAREFA , DAT_ATUALIZACAO_TAREFA , NUM_MATRICULA_LIDER , FLG_APROVA_EXTRA , NUM_MATRICULA_LIDER_APROVA , DAT_APROVACAO_EXTRA , QTD_HORA_TOTAL_UTEIS ";
		$sqlCorpo  = "FROM gestaoti.tarefa_ti
						WHERE 1=1 ";

		if($this->SEQ_TAREFA_TI != ""){
			$sqlCorpo .= "  and SEQ_TAREFA_TI = $this->SEQ_TAREFA_TI ";
		}
		if($this->FLG_PROJETO != ""){
			$sqlCorpo .= "  and SEQ_EPM IS NOT NULL ";
		}
		if($this->FLG_OS != ""){
			$sqlCorpo .= "  and SEQ_OS IS NOT NULL ";
		}
		if($this->FLG_VALIDA_DTCORRENTE != ""){
			$sqlCorpo .= "  and CURDATE( ) = DATE_FORMAT( DAT_CRIACAO_TAREFA, '%Y-%m-%d' ) ";
		}
		if($this->NUM_MATRICULA_RECURSO != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_RECURSO = $this->NUM_MATRICULA_RECURSO ";
		}
		if($this->SEQ_OS != ""){
			$sqlCorpo .= "  and SEQ_OS = $this->SEQ_OS ";
		}
		if($this->SEQ_EPM != ""){
			$sqlCorpo .= "  and SEQ_EPM = $this->SEQ_EPM ";
		}
		if($this->SEQ_STATUS_TAREFA_TI != ""){
			$sqlCorpo .= "  and SEQ_STATUS_TAREFA_TI = $this->SEQ_STATUS_TAREFA_TI ";
		}
		if($this->DAT_CRIACAO_TAREFA != "" && $this->DAT_CRIACAO_TAREFA_FINAL == "" ){
			$sqlCorpo .= "  and DAT_CRIACAO_TAREFA >= '".ConvDataAMD($this->DAT_CRIACAO_TAREFA)."' ";
		}
		if($this->DAT_CRIACAO_TAREFA != "" && $this->DAT_CRIACAO_TAREFA_FINAL != "" ){
			$sqlCorpo .= "  and DAT_CRIACAO_TAREFA between '".ConvDataAMD($this->DAT_CRIACAO_TAREFA)."' and '".ConvDataAMD($this->DAT_CRIACAO_TAREFA_FINAL)."' ";
		}
		if($this->DAT_CRIACAO_TAREFA == "" && $this->DAT_CRIACAO_TAREFA_FINAL != "" ){
			$sqlCorpo .= "  and DAT_CRIACAO_TAREFA <= '".ConvDataAMD($this->DAT_CRIACAO_TAREFA_FINAL)."' ";
		}
		if($this->DAT_ATUALIZACAO_TAREFA != "" && $this->DAT_ATUALIZACAO_TAREFA_FINAL == "" ){
			$sqlCorpo .= "  and DAT_ATUALIZACAO_TAREFA >= '".ConvDataAMD($this->DAT_ATUALIZACAO_TAREFA)."' ";
		}
		if($this->DAT_ATUALIZACAO_TAREFA != "" && $this->DAT_ATUALIZACAO_TAREFA_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ATUALIZACAO_TAREFA between '".ConvDataAMD($this->DAT_ATUALIZACAO_TAREFA)."' and '".ConvDataAMD($this->DAT_ATUALIZACAO_TAREFA_FINAL)."' ";
		}
		if($this->DAT_ATUALIZACAO_TAREFA == "" && $this->DAT_ATUALIZACAO_TAREFA_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ATUALIZACAO_TAREFA <= '".ConvDataAMD($this->DAT_ATUALIZACAO_TAREFA_FINAL)."' ";
		}
		if($this->NUM_MATRICULA_LIDER != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_LIDER = $this->NUM_MATRICULA_LIDER ";
		}
		if($this->FLG_APROVA_EXTRA != ""){
			$sqlCorpo .= "  and FLG_APROVA_EXTRA = '$this->FLG_APROVA_EXTRA' ";
		}
		if($this->NUM_MATRICULA_LIDER_APROVA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_LIDER_APROVA = $this->NUM_MATRICULA_LIDER_APROVA ";
		}
		if($this->DAT_APROVACAO_EXTRA != "" && $this->DAT_APROVACAO_EXTRA_FINAL == "" ){
			$sqlCorpo .= "  and DAT_APROVACAO_EXTRA >= '".ConvDataAMD($this->DAT_APROVACAO_EXTRA)."' ";
		}
		if($this->DAT_APROVACAO_EXTRA != "" && $this->DAT_APROVACAO_EXTRA_FINAL != "" ){
			$sqlCorpo .= "  and DAT_APROVACAO_EXTRA between '".ConvDataAMD($this->DAT_APROVACAO_EXTRA)."' and '".ConvDataAMD($this->DAT_APROVACAO_EXTRA_FINAL)."' ";
		}
		if($this->DAT_APROVACAO_EXTRA == "" && $this->DAT_APROVACAO_EXTRA_FINAL != "" ){
			$sqlCorpo .= "  and DAT_APROVACAO_EXTRA <= '".ConvDataAMD($this->DAT_APROVACAO_EXTRA_FINAL)."' ";
		}
		if($this->QTD_HORA_TOTAL_UTEIS != ""){
			$sqlCorpo .= "  and QTD_HORA_TOTAL_UTEIS = $this->QTD_HORA_TOTAL_UTEIS ";
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
		$sql = "DELETE FROM gestaoti.tarefa_ti WHERE SEQ_TAREFA_TI = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_TAREFA_TI = $this->database->GetSequenceValue("gestaoti.SEQ_TAREFA_TI"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.tarefa_ti (SEQ_TAREFA_TI,
									NUM_MATRICULA_RECURSO,
									SEQ_OS,
									SEQ_EPM,
									SEQ_STATUS_TAREFA_TI,
									DAT_CRIACAO_TAREFA,
									DAT_ATUALIZACAO_TAREFA,
									NUM_MATRICULA_LIDER,
									FLG_APROVA_EXTRA,
									NUM_MATRICULA_LIDER_APROVA,
									DAT_APROVACAO_EXTRA,
									QTD_HORA_TOTAL_UTEIS )
				VALUES (".$this->SEQ_TAREFA_TI.",
						".$this->database->iif($this->NUM_MATRICULA_RECURSO=="", "NULL", "'".$this->NUM_MATRICULA_RECURSO."'").",
						".$this->database->iif($this->SEQ_OS=="", "NULL", "'".$this->SEQ_OS."'").",
						".$this->database->iif($this->SEQ_EPM=="", "NULL", "'".$this->SEQ_EPM."'").",
						".$this->database->iif($this->SEQ_STATUS_TAREFA_TI=="", "NULL", "'".$this->SEQ_STATUS_TAREFA_TI."'").",
						".$this->database->iif($this->DAT_CRIACAO_TAREFA=="", "NULL", "to_date('".$this->DAT_CRIACAO_TAREFA."','yyyy-mm-dd')").",
						".$this->database->iif($this->DAT_ATUALIZACAO_TAREFA=="", "NULL", "to_date('".$this->DAT_ATUALIZACAO_TAREFA."','yyyy-mm-dd')").",
						".$this->database->iif($this->NUM_MATRICULA_LIDER=="", "NULL", "'".$this->NUM_MATRICULA_LIDER."'").",
						".$this->database->iif($this->FLG_APROVA_EXTRA=="", "NULL", "'".$this->FLG_APROVA_EXTRA."'").",
						".$this->database->iif($this->NUM_MATRICULA_LIDER_APROVA=="", "NULL", "'".$this->NUM_MATRICULA_LIDER_APROVA."'").",
						".$this->database->iif($this->DAT_APROVACAO_EXTRA=="", "NULL", "to_date('".$this->DAT_APROVACAO_EXTRA."','yyyy-mm-dd')").",
						".$this->database->iif($this->QTD_HORA_TOTAL_UTEIS=="", "NULL", "'".$this->QTD_HORA_TOTAL_UTEIS."'")."
				)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = "UPDATE tarefa_ti
				SET NUM_MATRICULA_RECURSO = ".$this->database->iif($this->NUM_MATRICULA_RECURSO=="", "NULL", "'".$this->NUM_MATRICULA_RECURSO."'").",
					SEQ_OS = ".$this->database->iif($this->SEQ_OS=="", "NULL", "'".$this->SEQ_OS."'").",
					SEQ_EPM = ".$this->database->iif($this->SEQ_EPM=="", "NULL", "'".$this->SEQ_EPM."'").",
					SEQ_STATUS_TAREFA_TI = ".$this->database->iif($this->SEQ_STATUS_TAREFA_TI=="", "NULL", "'".$this->SEQ_STATUS_TAREFA_TI."'").",
					DAT_CRIACAO_TAREFA = ".$this->database->iif($this->DAT_CRIACAO_TAREFA=="", "NULL", "to_date('".$this->DAT_CRIACAO_TAREFA."','yyyy-mm-dd')").",
					DAT_ATUALIZACAO_TAREFA = ".$this->database->iif($this->DAT_ATUALIZACAO_TAREFA=="", "NULL", "to_date('".$this->DAT_ATUALIZACAO_TAREFA."','yyyy-mm-dd')").",
					NUM_MATRICULA_LIDER = ".$this->database->iif($this->NUM_MATRICULA_LIDER=="", "NULL", "'".$this->NUM_MATRICULA_LIDER."'").",
					FLG_APROVA_EXTRA = ".$this->database->iif($this->FLG_APROVA_EXTRA=="", "NULL", "'".$this->FLG_APROVA_EXTRA."'").",
					NUM_MATRICULA_LIDER_APROVA = ".$this->database->iif($this->NUM_MATRICULA_LIDER_APROVA=="", "NULL", "'".$this->NUM_MATRICULA_LIDER_APROVA."'").",
					DAT_APROVACAO_EXTRA = ".$this->database->iif($this->DAT_APROVACAO_EXTRA=="", "NULL", "to_date('".$this->DAT_APROVACAO_EXTRA."','yyyy-mm-dd')").",
					QTD_HORA_TOTAL_UTEIS = ".$this->database->iif($this->QTD_HORA_TOTAL_UTEIS=="", "NULL", "'".$this->QTD_HORA_TOTAL_UTEIS."'")."
				WHERE SEQ_TAREFA_TI = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>