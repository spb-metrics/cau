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
* CLASSNAME:        item_configuracao_software
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class item_configuracao_software{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_ITEM_CONFIGURACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_TIPO_SOFTWARE;   // (normal Attribute)
	var $SEQ_STATUS_SOFTWARE;   // (normal Attribute)
	var $FLG_EM_MANUTENCAO;   // (normal Attribute)
	var $FLG_PETI;   // (normal Attribute)
	var $NUM_ITEM_PETI;   // (normal Attribute)
	var $FLG_DESCONTINUADO;   // (normal Attribute)
	var $FLG_SISTEMA_WEB;   // (normal Attribute)
	var $DSC_LOCALIZACAO_DOCUMENTACAO;   // (normal Attribute)
	var $VAL_TAMANHO_SOFTWARE;   // (normal Attribute)
	var $SEQ_UNIDADE_MEDIDA_SOFTWARE;   // (normal Attribute)
	var $VAL_AQUISICAO;   // (normal Attribute)
	var $SEQ_FREQUENCIA_MANUTENCAO;
	var $FLG_TAMANHO;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function item_configuracao_software(){
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
	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	}

	function getSEQ_TIPO_SOFTWARE(){
		return $this->SEQ_TIPO_SOFTWARE;
	}

	function getSEQ_STATUS_SOFTWARE(){
		return $this->SEQ_STATUS_SOFTWARE;
	}

	function getFLG_EM_MANUTENCAO(){
		return $this->FLG_EM_MANUTENCAO;
	}

	function getFLG_PETI(){
		return $this->FLG_PETI;
	}

	function getNUM_ITEM_PETI(){
		return $this->NUM_ITEM_PETI;
	}

	function getFLG_DESCONTINUADO(){
		return $this->FLG_DESCONTINUADO;
	}

	function getFLG_SISTEMA_WEB(){
		return $this->FLG_SISTEMA_WEB;
	}

	function getDSC_LOCALIZACAO_DOCUMENTACAO(){
		return $this->DSC_LOCALIZACAO_DOCUMENTACAO;
	}

	function getVAL_TAMANHO_SOFTWARE(){
		return $this->VAL_TAMANHO_SOFTWARE;
	}

	function getSEQ_UNIDADE_MEDIDA_SOFTWARE(){
		return $this->SEQ_UNIDADE_MEDIDA_SOFTWARE;
	}

	function getVAL_AQUISICAO(){
		return $this->VAL_AQUISICAO;
	}

	function getSEQ_FREQUENCIA_MANUTENCAO(){
		return $this->SEQ_FREQUENCIA_MANUTENCAO;
	}
	function getFLG_TAMANHO(){
		return $this->FLG_TAMANHO;
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
	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}

	function setSEQ_TIPO_SOFTWARE($val){
		$this->SEQ_TIPO_SOFTWARE =  $val;
	}

	function setSEQ_STATUS_SOFTWARE($val){
		$this->SEQ_STATUS_SOFTWARE =  $val;
	}

	function setFLG_EM_MANUTENCAO($val){
		$this->FLG_EM_MANUTENCAO =  $val;
	}

	function setFLG_PETI($val){
		$this->FLG_PETI =  $val;
	}

	function setNUM_ITEM_PETI($val){
		$this->NUM_ITEM_PETI =  $val;
	}

	function setFLG_DESCONTINUADO($val){
		$this->FLG_DESCONTINUADO =  $val;
	}

	function setFLG_SISTEMA_WEB($val){
		$this->FLG_SISTEMA_WEB =  $val;
	}

	function setDSC_LOCALIZACAO_DOCUMENTACAO($val){
		$this->DSC_LOCALIZACAO_DOCUMENTACAO =  $val;
	}

	function setVAL_TAMANHO_SOFTWARE($val){
		$this->VAL_TAMANHO_SOFTWARE =  $val;
	}

	function setSEQ_UNIDADE_MEDIDA_SOFTWARE($val){
		$this->SEQ_UNIDADE_MEDIDA_SOFTWARE =  $val;
	}

	function setVAL_AQUISICAO($val){
		$this->VAL_AQUISICAO =  $val;
	}
	function setSEQ_FREQUENCIA_MANUTENCAO($val){
		$this->SEQ_FREQUENCIA_MANUTENCAO =  $val;
	}
	function setFLG_TAMANHO($val){
		$this->FLG_TAMANHO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.item_configuracao_software WHERE SEQ_ITEM_CONFIGURACAO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		if($this->database->rows  > 0){
			$row = pg_fetch_object($result, 0);
			$this->SEQ_ITEM_CONFIGURACAO = $row->seq_item_configuracao;
			$this->SEQ_TIPO_SOFTWARE = $row->seq_tipo_software;
			$this->SEQ_STATUS_SOFTWARE = $row->seq_status_software;
			$this->FLG_EM_MANUTENCAO = $row->flg_em_manutencao;
			$this->FLG_PETI = $row->flg_peti;
			$this->NUM_ITEM_PETI = $row->num_item_peti;
			$this->FLG_DESCONTINUADO = $row->flg_descontinuado;
			$this->FLG_SISTEMA_WEB = $row->flg_sistema_web;
			$this->DSC_LOCALIZACAO_DOCUMENTACAO = $row->dsc_localizacao_documentacao;
			$this->VAL_TAMANHO_SOFTWARE = $row->val_tamanho_software;
			$this->SEQ_UNIDADE_MEDIDA_SOFTWARE = $row->seq_unidade_medida_software;
			$this->VAL_AQUISICAO = $row->val_aquisicao;
			$this->SEQ_FREQUENCIA_MANUTENCAO = $row->seq_frequencia_manutencao;
			$this->FLG_TAMANHO = $row->flg_tamanho;
		}

	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT SEQ_ITEM_CONFIGURACAO, SEQ_TIPO_SOFTWARE, SEQ_STATUS_SOFTWARE, FLG_EM_MANUTENCAO, FLG_PETI ,
						  	   NUM_ITEM_PETI, FLG_DESCONTINUADO, FLG_SISTEMA_WEB, DSC_LOCALIZACAO_DOCUMENTACAO, VAL_TAMANHO_SOFTWARE,
							   SEQ_UNIDADE_MEDIDA_SOFTWARE , VAL_AQUISICAO, SEQ_FREQUENCIA_MANUTENCAO, FLG_TAMANHO ";
		$sqlCorpo  = " FROM gestaoti.item_configuracao_software
					   WHERE 1=1 ";
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_TIPO_SOFTWARE != ""){
			$sqlCorpo .= "  and SEQ_TIPO_SOFTWARE = $this->SEQ_TIPO_SOFTWARE ";
		}
		if($this->SEQ_STATUS_SOFTWARE != ""){
			$sqlCorpo .= "  and SEQ_STATUS_SOFTWARE = $this->SEQ_STATUS_SOFTWARE ";
		}
		if($this->FLG_EM_MANUTENCAO != ""){
			$sqlCorpo .= "  and FLG_EM_MANUTENCAO = '$this->FLG_EM_MANUTENCAO' ";
		}
		if($this->FLG_PETI != ""){
			$sqlCorpo .= "  and FLG_PETI = '$this->FLG_PETI' ";
		}
		if($this->NUM_ITEM_PETI != ""){
			$sqlCorpo .= "  and upper(NUM_ITEM_PETI) like '%".strtoupper($this->NUM_ITEM_PETI)."%'  ";
		}
		if($this->FLG_DESCONTINUADO != ""){
			$sqlCorpo .= "  and FLG_DESCONTINUADO = '$this->FLG_DESCONTINUADO' ";
		}
		if($this->FLG_SISTEMA_WEB != ""){
			$sqlCorpo .= "  and FLG_SISTEMA_WEB = '$this->FLG_SISTEMA_WEB' ";
		}
		if($this->DSC_LOCALIZACAO_DOCUMENTACAO != ""){
			$sqlCorpo .= "  and upper(DSC_LOCALIZACAO_DOCUMENTACAO) like '%".strtoupper($this->DSC_LOCALIZACAO_DOCUMENTACAO)."%'  ";
		}
		if($this->VAL_TAMANHO_SOFTWARE != ""){
			$sqlCorpo .= "  and VAL_TAMANHO_SOFTWARE = $this->VAL_TAMANHO_SOFTWARE ";
		}
		if($this->SEQ_UNIDADE_MEDIDA_SOFTWARE != ""){
			$sqlCorpo .= "  and SEQ_UNIDADE_MEDIDA_SOFTWARE = $this->SEQ_UNIDADE_MEDIDA_SOFTWARE ";
		}
		if($this->VAL_AQUISICAO != ""){
			$sqlCorpo .= "  and VAL_AQUISICAO = $this->VAL_AQUISICAO ";
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
		$sql = "DELETE FROM gestaoti.item_configuracao_software WHERE SEQ_ITEM_CONFIGURACAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.item_configuracao_software
				(SEQ_ITEM_CONFIGURACAO, SEQ_TIPO_SOFTWARE, SEQ_STATUS_SOFTWARE, FLG_EM_MANUTENCAO, FLG_PETI, NUM_ITEM_PETI, FLG_DESCONTINUADO, FLG_SISTEMA_WEB,
				 DSC_LOCALIZACAO_DOCUMENTACAO,VAL_TAMANHO_SOFTWARE,SEQ_UNIDADE_MEDIDA_SOFTWARE,VAL_AQUISICAO, SEQ_FREQUENCIA_MANUTENCAO, FLG_TAMANHO )
				VALUES ( ".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
						 ".$this->database->iif($this->SEQ_TIPO_SOFTWARE=="", "NULL", "'".$this->SEQ_TIPO_SOFTWARE."'").",
						 ".$this->database->iif($this->SEQ_STATUS_SOFTWARE=="", "NULL", "'".$this->SEQ_STATUS_SOFTWARE."'").",
						 ".$this->database->iif($this->FLG_EM_MANUTENCAO=="", "NULL", "'".$this->FLG_EM_MANUTENCAO."'").",
						 ".$this->database->iif($this->FLG_PETI=="", "NULL", "'".$this->FLG_PETI."'").",
						 ".$this->database->iif($this->NUM_ITEM_PETI=="", "NULL", "'".$this->NUM_ITEM_PETI."'").",
						 ".$this->database->iif($this->FLG_DESCONTINUADO=="", "NULL", "'".$this->FLG_DESCONTINUADO."'").",
						 ".$this->database->iif($this->FLG_SISTEMA_WEB=="", "NULL", "'".$this->FLG_SISTEMA_WEB."'").",
						 ".$this->database->iif($this->DSC_LOCALIZACAO_DOCUMENTACAO=="", "NULL", "'".$this->DSC_LOCALIZACAO_DOCUMENTACAO."'").",
						 ".$this->database->iif($this->VAL_TAMANHO_SOFTWARE=="", "NULL", "'".$this->VAL_TAMANHO_SOFTWARE."'").",
						 ".$this->database->iif($this->SEQ_UNIDADE_MEDIDA_SOFTWARE=="", "NULL", "'".$this->SEQ_UNIDADE_MEDIDA_SOFTWARE."'").",
						 ".$this->database->iif($this->VAL_AQUISICAO=="", "NULL", "'".$this->VAL_AQUISICAO."'").",
						 ".$this->database->iif($this->SEQ_FREQUENCIA_MANUTENCAO=="", "NULL", "'".$this->SEQ_FREQUENCIA_MANUTENCAO."'").",
						 ".$this->database->iif($this->FLG_TAMANHO=="", "NULL", "'".$this->FLG_TAMANHO."'")."
						  )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.item_configuracao_software
				SET  SEQ_TIPO_SOFTWARE = ".$this->database->iif($this->SEQ_TIPO_SOFTWARE=="", "NULL", "'".$this->SEQ_TIPO_SOFTWARE."'").",
					 SEQ_STATUS_SOFTWARE = ".$this->database->iif($this->SEQ_STATUS_SOFTWARE=="", "NULL", "'".$this->SEQ_STATUS_SOFTWARE."'").",
					 FLG_EM_MANUTENCAO = ".$this->database->iif($this->FLG_EM_MANUTENCAO=="", "NULL", "'".$this->FLG_EM_MANUTENCAO."'").",
					 FLG_PETI = ".$this->database->iif($this->FLG_PETI=="", "NULL", "'".$this->FLG_PETI."'").",
					 NUM_ITEM_PETI = ".$this->database->iif($this->NUM_ITEM_PETI=="", "NULL", "'".$this->NUM_ITEM_PETI."'").",
					 FLG_DESCONTINUADO = ".$this->database->iif($this->FLG_DESCONTINUADO=="", "NULL", "'".$this->FLG_DESCONTINUADO."'").",
					 FLG_SISTEMA_WEB = ".$this->database->iif($this->FLG_SISTEMA_WEB=="", "NULL", "'".$this->FLG_SISTEMA_WEB."'").",
					 DSC_LOCALIZACAO_DOCUMENTACAO = ".$this->database->iif($this->DSC_LOCALIZACAO_DOCUMENTACAO=="", "NULL", "'".$this->DSC_LOCALIZACAO_DOCUMENTACAO."'").",
					 VAL_TAMANHO_SOFTWARE = ".$this->database->iif($this->VAL_TAMANHO_SOFTWARE=="", "NULL", "'".$this->VAL_TAMANHO_SOFTWARE."'").",
					 SEQ_UNIDADE_MEDIDA_SOFTWARE = ".$this->database->iif($this->SEQ_UNIDADE_MEDIDA_SOFTWARE=="", "NULL", "'".$this->SEQ_UNIDADE_MEDIDA_SOFTWARE."'").",
					 VAL_AQUISICAO = ".$this->database->iif($this->VAL_AQUISICAO=="", "NULL", "'".$this->VAL_AQUISICAO."'").",
					 SEQ_FREQUENCIA_MANUTENCAO = ".$this->database->iif($this->SEQ_FREQUENCIA_MANUTENCAO=="", "NULL", "'".$this->SEQ_FREQUENCIA_MANUTENCAO."'")." ,
					 FLG_TAMANHO = ".$this->database->iif($this->FLG_TAMANHO=="", "NULL", "'".$this->FLG_TAMANHO."'")."
					 WHERE SEQ_ITEM_CONFIGURACAO = $id ";
		$result = $this->database->query($sql);

	}

} // class : end
?>