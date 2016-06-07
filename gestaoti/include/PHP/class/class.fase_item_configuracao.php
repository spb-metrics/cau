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
* CLASSNAME:        FASE_ITEM_CONFIGURACAO
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class fase_item_configuracao{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_FASE_ITEM_CONFIGURACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $DAT_INICIO_FASE_PROJETO;   // (normal Attribute)
	var $SEQ_FASE_PROJETO;   // (normal Attribute)
	var $DSC_FASE_ITEM_CONFIGURACAO;   // (normal Attribute)

	// Campos novos
	var $DAT_FIM_FASE_PROJETO;   // (normal Attribute)
	var $TXT_OBSERVACAO_FASE;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function FASE_ITEM_CONFIGURACAO(){
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
	function getSEQ_FASE_ITEM_CONFIGURACAO(){
		return $this->SEQ_FASE_ITEM_CONFIGURACAO;
	}

	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	}

	function getDAT_INICIO_FASE_PROJETO(){
		return $this->DAT_INICIO_FASE_PROJETO;
	}

	function getSEQ_FASE_PROJETO(){
		return $this->SEQ_FASE_PROJETO;
	}

	function getDSC_FASE_ITEM_CONFIGURACAO(){
		return $this->DSC_FASE_ITEM_CONFIGURACAO;
	}

	function getDAT_FIM_FASE_PROJETO(){
		return $this->DAT_FIM_FASE_PROJETO;
	}

	function getTXT_OBSERVACAO_FASE(){
		return $this->TXT_OBSERVACAO_FASE;
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
	function setSEQ_FASE_ITEM_CONFIGURACAO($val){
		$this->SEQ_FASE_ITEM_CONFIGURACAO =  $val;
	}

	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}

	function setDAT_INICIO_FASE_PROJETO($val){
		$this->DAT_INICIO_FASE_PROJETO =  $val;
	}

	function setSEQ_FASE_PROJETO($val){
		$this->SEQ_FASE_PROJETO =  $val;
	}

	function setDSC_FASE_ITEM_CONFIGURACAO($val){
		$this->DSC_FASE_ITEM_CONFIGURACAO =  $val;
	}

	function setDAT_FIM_FASE_PROJETO($val){
		$this->DAT_FIM_FASE_PROJETO =  $val;
	}

	function setTXT_OBSERVACAO_FASE($val){
		$this->TXT_OBSERVACAO_FASE =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.FASE_ITEM_CONFIGURACAO WHERE SEQ_FASE_ITEM_CONFIGURACAO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_FASE_ITEM_CONFIGURACAO = $row->seq_fase_item_configuracao;
		$this->SEQ_ITEM_CONFIGURACAO = $row->seq_item_configuracao;
		$this->DAT_INICIO_FASE_PROJETO = $row->dat_inicio_fase_projeto;
		$this->SEQ_FASE_PROJETO = $row->seq_fase_projeto;
		$this->DSC_FASE_ITEM_CONFIGURACAO = $row->dsc_fase_item_configuracao;
		$this->DAT_FIM_FASE_PROJETO = $row->dat_fim_fase_projeto;
		$this->TXT_OBSERVACAO_FASE = $row->txt_observacao_fase;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);


		$sqlSelect = "SELECT SEQ_FASE_ITEM_CONFIGURACAO, DSC_FASE_ITEM_CONFIGURACAO, SEQ_ITEM_CONFIGURACAO, SEQ_FASE_PROJETO,
								to_char(DAT_INICIO_FASE_PROJETO,'yyyy-mm-dd') as DAT_INICIO_FASE_PROJETO,
								to_char(DAT_FIM_FASE_PROJETO,'yyyy-mm-dd') as DAT_FIM_FASE_PROJETO,
								TXT_OBSERVACAO_FASE ";
		$sqlCorpo  = "FROM gestaoti.FASE_ITEM_CONFIGURACAO
						WHERE 1=1 ";

		if($this->SEQ_FASE_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_FASE_ITEM_CONFIGURACAO = $this->SEQ_FASE_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->DAT_INICIO_FASE_PROJETO != "" && $this->DAT_INICIO_FASE_PROJETO_FINAL == "" ){
			$sqlCorpo .= "  and DAT_INICIO_FASE_PROJETO >= '".ConvDataAMD($this->DAT_INICIO_FASE_PROJETO)."' ";
		}
		if($this->DAT_INICIO_FASE_PROJETO != "" && $this->DAT_INICIO_FASE_PROJETO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_INICIO_FASE_PROJETO between '".ConvDataAMD($this->DAT_INICIO_FASE_PROJETO)."' and '".ConvDataAMD($this->DAT_INICIO_FASE_PROJETO_FINAL)."' ";
		}
		if($this->DAT_INICIO_FASE_PROJETO == "" && $this->DAT_INICIO_FASE_PROJETO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_INICIO_FASE_PROJETO <= '".ConvDataAMD($this->DAT_INICIO_FASE_PROJETO_FINAL)."' ";
		}
		if($this->NOM_TITULO != ""){
			$sqlCorpo .= "  and upper(NOM_TITULO) like '%".strtoupper($this->NOM_TITULO)."%'  ";
		}
		if($this->SEQ_FASE_PROJETO != ""){
			$sqlCorpo .= "  and SEQ_FASE_PROJETO = '$this->SEQ_FASE_PROJETO' ";
		}
		if($this->DSC_FASE_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and upper(DSC_FASE_ITEM_CONFIGURACAO) like '%".strtoupper($this->DSC_FASE_ITEM_CONFIGURACAO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.FASE_ITEM_CONFIGURACAO WHERE SEQ_ITEM_CONFIGURACAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_FASE_ITEM_CONFIGURACAO = $this->database->GetSequenceValue("gestaoti.SEQ_FASE_ITEM_CONFIGURACAO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.FASE_ITEM_CONFIGURACAO (
						SEQ_FASE_ITEM_CONFIGURACAO,
						SEQ_ITEM_CONFIGURACAO,
						DAT_INICIO_FASE_PROJETO,
						SEQ_FASE_PROJETO,
						DSC_FASE_ITEM_CONFIGURACAO,
						DAT_FIM_FASE_PROJETO,
						TXT_OBSERVACAO_FASE
						)
				VALUES (".$this->SEQ_FASE_ITEM_CONFIGURACAO.",
						".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
						".$this->database->iif($this->DAT_INICIO_FASE_PROJETO=="", "NULL", "to_date('".$this->DAT_INICIO_FASE_PROJETO."','yyyy-mm-dd')").",
						".$this->database->iif($this->SEQ_FASE_PROJETO=="", "NULL", "'".$this->SEQ_FASE_PROJETO."'").",
						".$this->database->iif($this->DSC_FASE_ITEM_CONFIGURACAO=="", "NULL", "'".$this->DSC_FASE_ITEM_CONFIGURACAO."'").",
						".$this->database->iif($this->DAT_FIM_FASE_PROJETO=="", "NULL", "to_date('".$this->DAT_FIM_FASE_PROJETO."','yyyy-mm-dd')").",
						".$this->database->iif($this->TXT_OBSERVACAO_FASE=="", "NULL", "'".$this->TXT_OBSERVACAO_FASE."'")."
						)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.FASE_ITEM_CONFIGURACAO SET
					SEQ_ITEM_CONFIGURACAO = ".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
					DAT_INICIO_FASE_PROJETO = ".$this->database->iif($this->DAT_INICIO_FASE_PROJETO=="", "NULL", "to_date('".$this->DAT_INICIO_FASE_PROJETO."','yyyy-mm-dd')").",
					NOM_TITULO = ".$this->database->iif($this->NOM_TITULO=="", "NULL", "'".$this->NOM_TITULO."'").",
					SEQ_FASE_PROJETO = ".$this->database->iif($this->SEQ_FASE_PROJETO=="", "NULL", "'".$this->SEQ_FASE_PROJETO."'").",
					DSC_FASE_ITEM_CONFIGURACAO = ".$this->database->iif($this->DSC_FASE_ITEM_CONFIGURACAO=="", "NULL", "'".$this->DSC_FASE_ITEM_CONFIGURACAO."'").",
					DAT_FIM_FASE_PROJETO = ".$this->database->iif($this->DAT_FIM_FASE_PROJETO=="", "NULL", "to_date('".$this->DAT_FIM_FASE_PROJETO."','yyyy-mm-dd')").",
					TXT_OBSERVACAO_FASE = ".$this->database->iif($this->TXT_OBSERVACAO_FASE=="", "NULL", "'".$this->TXT_OBSERVACAO_FASE."'")."
				WHERE SEQ_FASE_ITEM_CONFIGURACAO = $id ";
		$result = $this->database->query($sql);
	}

} // class : end
?>