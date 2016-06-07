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
* CLASSNAME:        ordem_servico
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class ordem_servico{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_ORDEM_SERVICO_TI;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NUM_CGC_CPF_FORNECEDOR;   // (normal Attribute)
	var $SEQ_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $NUM_ORDEM_SERVICO;   // (normal Attribute)
	var $VAL_PAGAMENTO;   // (normal Attribute)
	var $NUM_NOTA_FISCAL;   // (normal Attribute)
	var $DAT_INICIO;   // (normal Attribute)
	var $DAT_FIM;   // (normal Attribute)
	var $DAT_ENTREGA;   // (normal Attribute)
	var $DSC_ORDEM_SERVICO;   // (normal Attribute)
	var $NUM_PEC;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function ordem_servico(){
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
	function getSEQ_ORDEM_SERVICO_TI(){
		return $this->SEQ_ORDEM_SERVICO_TI;
	}

	function getNUM_CGC_CPF_FORNECEDOR(){
		return $this->NUM_CGC_CPF_FORNECEDOR;
	}

	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	}

	function getNUM_ORDEM_SERVICO(){
		return $this->NUM_ORDEM_SERVICO;
	}

	function getVAL_PAGAMENTO(){
		return $this->VAL_PAGAMENTO;
	}

	function getNUM_NOTA_FISCAL(){
		return $this->NUM_NOTA_FISCAL;
	}

	function getDAT_INICIO(){
		return $this->DAT_INICIO;
	}

	function getDAT_FIM(){
		return $this->DAT_FIM;
	}

	function getDAT_ENTREGA(){
		return $this->DAT_ENTREGA;
	}

	function getDSC_ORDEM_SERVICO(){
		return $this->DSC_ORDEM_SERVICO;
	}

	function getNUM_PEC(){
		return $this->NUM_PEC;
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
	function setSEQ_ORDEM_SERVICO_TI($val){
		$this->SEQ_ORDEM_SERVICO_TI =  $val;
	}

	function setNUM_CGC_CPF_FORNECEDOR($val){
		$this->NUM_CGC_CPF_FORNECEDOR =  $val;
	}

	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}

	function setNUM_ORDEM_SERVICO($val){
		$this->NUM_ORDEM_SERVICO =  $val;
	}

	function setVAL_PAGAMENTO($val){
		$this->VAL_PAGAMENTO =  $val;
	}

	function setNUM_NOTA_FISCAL($val){
		$this->NUM_NOTA_FISCAL =  $val;
	}

	function setDAT_INICIO($val){
		$this->DAT_INICIO =  $val;
	}

	function setDAT_FIM($val){
		$this->DAT_FIM =  $val;
	}

	function setDAT_ENTREGA($val){
		$this->DAT_ENTREGA =  $val;
	}

	function setDSC_ORDEM_SERVICO($val){
		$this->DSC_ORDEM_SERVICO =  $val;
	}

	function setNUM_PEC($val){
		$this->NUM_PEC =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.ordem_servico_ti WHERE SEQ_ORDEM_SERVICO_TI = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_ORDEM_SERVICO_TI = $row->SEQ_ORDEM_SERVICO_TI;
		$this->NUM_CGC_CPF_FORNECEDOR = $row->NUM_CGC_CPF_FORNECEDOR;
		$this->SEQ_ITEM_CONFIGURACAO = $row->SEQ_ITEM_CONFIGURACAO;
		$this->NUM_ORDEM_SERVICO = $row->NUM_ORDEM_SERVICO;
		$this->VAL_PAGAMENTO = $row->VAL_PAGAMENTO;
		$this->NUM_NOTA_FISCAL = $row->NUM_NOTA_FISCAL;
		$this->DAT_INICIO = $row->DAT_INICIO;
		$this->DAT_FIM = $row->DAT_FIM;
		$this->DAT_ENTREGA = $row->DAT_ENTREGA;
		$this->DSC_ORDEM_SERVICO = $row->DSC_ORDEM_SERVICO;
		$this->NUM_PEC = $row->NUM_PEC;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_ORDEM_SERVICO_TI, NUM_CGC_CPF_FORNECEDOR, SEQ_ITEM_CONFIGURACAO, NUM_ORDEM_SERVICO, VAL_PAGAMENTO,
						  			   NUM_NOTA_FISCAL, DAT_INICIO, DAT_FIM, DAT_ENTREGA, DSC_ORDEM_SERVICO, NUM_PEC ";
		$sqlCorpo  = "FROM gestaoti.ordem_servico_ti
						WHERE 1=1 ";

		if($this->SEQ_ORDEM_SERVICO_TI != ""){
			$sqlCorpo .= "  and SEQ_ORDEM_SERVICO_TI = $this->SEQ_ORDEM_SERVICO_TI ";
		}
		if($this->NUM_CGC_CPF_FORNECEDOR != ""){
			$sqlCorpo .= "  and NUM_CGC_CPF_FORNECEDOR = $this->NUM_CGC_CPF_FORNECEDOR ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->NUM_ORDEM_SERVICO != ""){
			$sqlCorpo .= "  and upper(NUM_ORDEM_SERVICO) like '%".strtoupper($this->NUM_ORDEM_SERVICO)."%'  ";
		}
		if($this->VAL_PAGAMENTO != ""){
			$sqlCorpo .= "  and VAL_PAGAMENTO = $this->VAL_PAGAMENTO ";
		}
		if($this->NUM_NOTA_FISCAL != ""){
			$sqlCorpo .= "  and upper(NUM_NOTA_FISCAL) like '%".strtoupper($this->NUM_NOTA_FISCAL)."%'  ";
		}
		if($this->DAT_INICIO != "" && $this->DAT_INICIO_FINAL == "" ){
			$sqlCorpo .= "  and DAT_INICIO >= '".ConvDataAMD($this->DAT_INICIO)."' ";
		}
		if($this->DAT_INICIO != "" && $this->DAT_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_INICIO between '".ConvDataAMD($this->DAT_INICIO)."' and '".ConvDataAMD($this->DAT_INICIO_FINAL)."' ";
		}
		if($this->DAT_INICIO == "" && $this->DAT_INICIO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_INICIO <= '".ConvDataAMD($this->DAT_INICIO_FINAL)."' ";
		}
		if($this->DAT_FIM != "" && $this->DAT_FIM_FINAL == "" ){
			$sqlCorpo .= "  and DAT_FIM >= '".ConvDataAMD($this->DAT_FIM)."' ";
		}
		if($this->DAT_FIM != "" && $this->DAT_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DAT_FIM between '".ConvDataAMD($this->DAT_FIM)."' and '".ConvDataAMD($this->DAT_FIM_FINAL)."' ";
		}
		if($this->DAT_FIM == "" && $this->DAT_FIM_FINAL != "" ){
			$sqlCorpo .= "  and DAT_FIM <= '".ConvDataAMD($this->DAT_FIM_FINAL)."' ";
		}
		if($this->DAT_ENTREGA != "" && $this->DAT_ENTREGA_FINAL == "" ){
			$sqlCorpo .= "  and DAT_ENTREGA >= '".ConvDataAMD($this->DAT_ENTREGA)."' ";
		}
		if($this->DAT_ENTREGA != "" && $this->DAT_ENTREGA_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ENTREGA between '".ConvDataAMD($this->DAT_ENTREGA)."' and '".ConvDataAMD($this->DAT_ENTREGA_FINAL)."' ";
		}
		if($this->DAT_ENTREGA == "" && $this->DAT_ENTREGA_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ENTREGA <= '".ConvDataAMD($this->DAT_ENTREGA_FINAL)."' ";
		}
		if($this->DSC_ORDEM_SERVICO != ""){
			$sqlCorpo .= "  and upper(DSC_ORDEM_SERVICO) like '%".strtoupper($this->DSC_ORDEM_SERVICO)."%'  ";
		}
		if($this->NUM_PEC != ""){
			$sqlCorpo .= "  and upper(NUM_PEC) like '%".strtoupper($this->NUM_PEC)."%'  ";
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
		$sql = "DELETE FROM gestaoti.ordem_servico_ti WHERE SEQ_ORDEM_SERVICO_TI = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_ORDEM_SERVICO_TI = $this->database->GetSequenceValue("gestaoti.SEQ_ORDEM_SERVICO_TI"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.ordem_servico_ti (SEQ_ORDEM_SERVICO_TI,
										   NUM_CGC_CPF_FORNECEDOR,
										   SEQ_ITEM_CONFIGURACAO,
										   NUM_ORDEM_SERVICO,
										   VAL_PAGAMENTO,
										   NUM_NOTA_FISCAL,
										   DAT_INICIO,
										   DAT_FIM,
										   DAT_ENTREGA,
										   DSC_ORDEM_SERVICO,
										   NUM_PEC )
				VALUES (".$this->SEQ_ORDEM_SERVICO_TI.",
						".$this->database->iif($this->NUM_CGC_CPF_FORNECEDOR=="", "NULL", "'".$this->NUM_CGC_CPF_FORNECEDOR."'").",
						".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
						".$this->database->iif($this->NUM_ORDEM_SERVICO=="", "NULL", "'".$this->NUM_ORDEM_SERVICO."'").",
						".$this->database->iif($this->VAL_PAGAMENTO=="", "NULL", "'".$this->VAL_PAGAMENTO."'").",
						".$this->database->iif($this->NUM_NOTA_FISCAL=="", "NULL", "'".$this->NUM_NOTA_FISCAL."'").",
						".$this->database->iif($this->DAT_INICIO=="", "NULL", "to_date('".$this->DAT_INICIO."','yyyy-mm-dd')").",
						".$this->database->iif($this->DAT_FIM=="", "NULL", "to_date('".$this->DAT_FIM."','yyyy-mm-dd')").",
						".$this->database->iif($this->DAT_ENTREGA=="", "NULL", "to_date('".$this->DAT_ENTREGA."','yyyy-mm-dd')").",
						".$this->database->iif($this->DSC_ORDEM_SERVICO=="", "NULL", "'".$this->DSC_ORDEM_SERVICO."'").",
						".$this->database->iif($this->NUM_PEC=="", "NULL", "'".$this->NUM_PEC."'")."
				)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.ordem_servico_ti
				 SET  NUM_CGC_CPF_FORNECEDOR = ".$this->database->iif($this->NUM_CGC_CPF_FORNECEDOR=="", "NULL", "'".$this->NUM_CGC_CPF_FORNECEDOR."'").",
				 	  SEQ_ITEM_CONFIGURACAO = ".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
				 	  NUM_ORDEM_SERVICO = ".$this->database->iif($this->NUM_ORDEM_SERVICO=="", "NULL", "'".$this->NUM_ORDEM_SERVICO."'").",
				 	  VAL_PAGAMENTO = ".$this->database->iif($this->VAL_PAGAMENTO=="", "NULL", "'".$this->VAL_PAGAMENTO."'").",
				 	  NUM_NOTA_FISCAL = ".$this->database->iif($this->NUM_NOTA_FISCAL=="", "NULL", "'".$this->NUM_NOTA_FISCAL."'").",
				 	  DAT_INICIO = ".$this->database->iif($this->DAT_INICIO=="", "NULL", "to_date('".$this->DAT_INICIO."','yyyy-mm-dd')").",
				 	  DAT_FIM = ".$this->database->iif($this->DAT_FIM=="", "NULL", "to_date('".$this->DAT_FIM."','yyyy-mm-dd')").",
				 	  DAT_ENTREGA = ".$this->database->iif($this->DAT_ENTREGA=="", "NULL", "to_date('".$this->DAT_ENTREGA."','yyyy-mm-dd')").",
				 	  DSC_ORDEM_SERVICO = ".$this->database->iif($this->DSC_ORDEM_SERVICO=="", "NULL", "'".$this->DSC_ORDEM_SERVICO."'").",
				 	  NUM_PEC = ".$this->database->iif($this->NUM_PEC=="", "NULL", "'".$this->NUM_PEC."'")."
				 WHERE SEQ_ORDEM_SERVICO_TI = $id ";
		$result = $this->database->query($sql);

	}

} // class : end
?>