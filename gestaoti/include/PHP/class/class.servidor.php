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
* CLASSNAME:        servidor
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class servidor{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_SERVIDOR;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_SISTEMA_OPERACIONAL;   // (normal Attribute)
	var $SEQ_MARCA_HARDWARE;   // (normal Attribute)
	var $NUM_PATRIMONIO;   // (normal Attribute)
	var $NUM_IP;   // (normal Attribute)
	var $NOM_SERVIDOR;   // (normal Attribute)
	var $NOM_MODELO;   // (normal Attribute)
	var $DSC_SERVIDOR;   // (normal Attribute)
	var $DSC_LOCALIZACAO;   // (normal Attribute)
	var $DSC_PROCESSADOR;   // (normal Attribute)
	var $TXT_OBSERVACAO;   // (normal Attribute)
	var $DAT_CRIACAO;   // (normal Attribute)
	var $DAT_ALTERACAO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function servidor(){
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
	function getSEQ_SERVIDOR(){
		return $this->SEQ_SERVIDOR;
	}

	function getSEQ_SISTEMA_OPERACIONAL(){
		return $this->SEQ_SISTEMA_OPERACIONAL;
	}

	function getSEQ_MARCA_HARDWARE(){
		return $this->SEQ_MARCA_HARDWARE;
	}

	function getNUM_PATRIMONIO(){
		return $this->NUM_PATRIMONIO;
	}

	function getNUM_IP(){
		return $this->NUM_IP;
	}

	function getNOM_SERVIDOR(){
		return $this->NOM_SERVIDOR;
	}

	function getNOM_MODELO(){
		return $this->NOM_MODELO;
	}

	function getDSC_SERVIDOR(){
		return $this->DSC_SERVIDOR;
	}

	function getDSC_LOCALIZACAO(){
		return $this->DSC_LOCALIZACAO;
	}

	function getDSC_PROCESSADOR(){
		return $this->DSC_PROCESSADOR;
	}

	function getTXT_OBSERVACAO(){
		return $this->TXT_OBSERVACAO;
	}

	function getDAT_CRIACAO(){
		return $this->DAT_CRIACAO;
	}

	function getDAT_ALTERACAO(){
		return $this->DAT_ALTERACAO;
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
	function setSEQ_SERVIDOR($val){
		$this->SEQ_SERVIDOR =  $val;
	}

	function setSEQ_SISTEMA_OPERACIONAL($val){
		$this->SEQ_SISTEMA_OPERACIONAL =  $val;
	}

	function setSEQ_MARCA_HARDWARE($val){
		$this->SEQ_MARCA_HARDWARE =  $val;
	}

	function setNUM_PATRIMONIO($val){
		$this->NUM_PATRIMONIO =  $val;
	}

	function setNUM_IP($val){
		$this->NUM_IP =  $val;
	}

	function setNOM_SERVIDOR($val){
		$this->NOM_SERVIDOR =  $val;
	}

	function setNOM_MODELO($val){
		$this->NOM_MODELO =  $val;
	}

	function setDSC_SERVIDOR($val){
		$this->DSC_SERVIDOR =  $val;
	}

	function setDSC_LOCALIZACAO($val){
		$this->DSC_LOCALIZACAO =  $val;
	}

	function setDSC_PROCESSADOR($val){
		$this->DSC_PROCESSADOR =  $val;
	}

	function setTXT_OBSERVACAO($val){
		$this->TXT_OBSERVACAO =  $val;
	}

	function setDAT_CRIACAO($val){
		$this->DAT_CRIACAO =  $val;
	}

	function setDAT_ALTERACAO($val){
		$this->DAT_ALTERACAO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.servidor WHERE SEQ_SERVIDOR = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_SERVIDOR = $row->seq_servidor;
		$this->SEQ_SISTEMA_OPERACIONAL = $row->seq_sistema_operacional;
		$this->SEQ_MARCA_HARDWARE = $row->seq_marca_hardware;
		$this->NUM_PATRIMONIO = $row->num_patrimonio;
		$this->NUM_IP = $row->num_ip;
		$this->NOM_SERVIDOR = $row->nom_servidor;
		$this->NOM_MODELO = $row->nom_modelo;
		$this->DSC_SERVIDOR = $row->dsc_servidor;
		$this->DSC_LOCALIZACAO = $row->dsc_localizacao;
		$this->DSC_PROCESSADOR = $row->dsc_processador;
		$this->TXT_OBSERVACAO = $row->txt_observacao;
		$this->DAT_CRIACAO = $row->dat_criacao;
		$this->DAT_ALTERACAO = $row->dat_alteracao;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);


		$sqlSelect = "SELECT SEQ_SERVIDOR , SEQ_SISTEMA_OPERACIONAL , SEQ_MARCA_HARDWARE , NUM_PATRIMONIO , NUM_IP , NOM_SERVIDOR , NOM_MODELO , DSC_SERVIDOR , DSC_LOCALIZACAO , DSC_PROCESSADOR , TXT_OBSERVACAO , DAT_CRIACAO , DAT_ALTERACAO ";
		$sqlCorpo  = "FROM gestaoti.servidor
						WHERE 1=1 ";

		if($this->SEQ_SERVIDOR != ""){
			$sqlCorpo .= "  and SEQ_SERVIDOR = $this->SEQ_SERVIDOR ";
		}
		if($this->SEQ_SISTEMA_OPERACIONAL != ""){
			$sqlCorpo .= "  and SEQ_SISTEMA_OPERACIONAL = $this->SEQ_SISTEMA_OPERACIONAL ";
		}
		if($this->SEQ_MARCA_HARDWARE != ""){
			$sqlCorpo .= "  and SEQ_MARCA_HARDWARE = $this->SEQ_MARCA_HARDWARE ";
		}
		if($this->NUM_PATRIMONIO != ""){
			$sqlCorpo .= "  and upper(NUM_PATRIMONIO) like '%".strtoupper($this->NUM_PATRIMONIO)."%'  ";
		}
		if($this->NUM_IP != ""){
			$sqlCorpo .= "  and upper(NUM_IP) like '%".strtoupper($this->NUM_IP)."%'  ";
		}
		if($this->NOM_SERVIDOR != ""){
			$sqlCorpo .= "  and upper(NOM_SERVIDOR) like '%".strtoupper($this->NOM_SERVIDOR)."%'  ";
		}
		if($this->NOM_MODELO != ""){
			$sqlCorpo .= "  and upper(NOM_MODELO) like '%".strtoupper($this->NOM_MODELO)."%'  ";
		}
		if($this->DSC_SERVIDOR != ""){
			$sqlCorpo .= "  and upper(DSC_SERVIDOR) like '%".strtoupper($this->DSC_SERVIDOR)."%'  ";
		}
		if($this->DSC_LOCALIZACAO != ""){
			$sqlCorpo .= "  and upper(DSC_LOCALIZACAO) like '%".strtoupper($this->DSC_LOCALIZACAO)."%'  ";
		}
		if($this->DSC_PROCESSADOR != ""){
			$sqlCorpo .= "  and upper(DSC_PROCESSADOR) like '%".strtoupper($this->DSC_PROCESSADOR)."%'  ";
		}
		if($this->TXT_OBSERVACAO != ""){
			$sqlCorpo .= "  and upper(TXT_OBSERVACAO) like '%".strtoupper($this->TXT_OBSERVACAO)."%'  ";
		}
		if($this->DAT_CRIACAO != "" && $this->DAT_CRIACAO_FINAL == "" ){
			$sqlCorpo .= "  and DAT_CRIACAO >= '".ConvDataAMD($this->DAT_CRIACAO)."' ";
		}
		if($this->DAT_CRIACAO != "" && $this->DAT_CRIACAO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_CRIACAO between '".ConvDataAMD($this->DAT_CRIACAO)."' and '".ConvDataAMD($this->DAT_CRIACAO_FINAL)."' ";
		}
		if($this->DAT_CRIACAO == "" && $this->DAT_CRIACAO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_CRIACAO <= '".ConvDataAMD($this->DAT_CRIACAO_FINAL)."' ";
		}
		if($this->DAT_ALTERACAO != "" && $this->DAT_ALTERACAO_FINAL == "" ){
			$sqlCorpo .= "  and DAT_ALTERACAO >= '".ConvDataAMD($this->DAT_ALTERACAO)."' ";
		}
		if($this->DAT_ALTERACAO != "" && $this->DAT_ALTERACAO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ALTERACAO between '".ConvDataAMD($this->DAT_ALTERACAO)."' and '".ConvDataAMD($this->DAT_ALTERACAO_FINAL)."' ";
		}
		if($this->DAT_ALTERACAO == "" && $this->DAT_ALTERACAO_FINAL != "" ){
			$sqlCorpo .= "  and DAT_ALTERACAO <= '".ConvDataAMD($this->DAT_ALTERACAO_FINAL)."' ";
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
		$sql = "DELETE FROM gestaoti.servidor WHERE SEQ_SERVIDOR = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_SERVIDOR = $this->database->GetSequenceValue("gestaoti.SEQ_SERVIDOR"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.servidor (SEQ_SERVIDOR,
									  SEQ_SISTEMA_OPERACIONAL,
									  SEQ_MARCA_HARDWARE,
									  NUM_PATRIMONIO,
									  NUM_IP,
									  NOM_SERVIDOR,
									  NOM_MODELO,
									  DSC_SERVIDOR,
									  DSC_LOCALIZACAO,
									  DSC_PROCESSADOR,
									  TXT_OBSERVACAO,
									  DAT_CRIACAO,
									  DAT_ALTERACAO )
					VALUES (".$this->SEQ_SERVIDOR.",
							".$this->database->iif($this->SEQ_SISTEMA_OPERACIONAL=="", "NULL", "'".$this->SEQ_SISTEMA_OPERACIONAL."'").",
							".$this->database->iif($this->SEQ_MARCA_HARDWARE=="", "NULL", "'".$this->SEQ_MARCA_HARDWARE."'").",
							".$this->database->iif($this->NUM_PATRIMONIO=="", "NULL", "'".$this->NUM_PATRIMONIO."'").",
							".$this->database->iif($this->NUM_IP=="", "NULL", "'".$this->NUM_IP."'").",
							".$this->database->iif($this->NOM_SERVIDOR=="", "NULL", "'".$this->NOM_SERVIDOR."'").",
							".$this->database->iif($this->NOM_MODELO=="", "NULL", "'".$this->NOM_MODELO."'").",
							".$this->database->iif($this->DSC_SERVIDOR=="", "NULL", "'".$this->DSC_SERVIDOR."'").",
							".$this->database->iif($this->DSC_LOCALIZACAO=="", "NULL", "'".$this->DSC_LOCALIZACAO."'").",
							".$this->database->iif($this->DSC_PROCESSADOR=="", "NULL", "'".$this->DSC_PROCESSADOR."'").",
							".$this->database->iif($this->TXT_OBSERVACAO=="", "NULL", "'".$this->TXT_OBSERVACAO."'").",
							".$this->database->iif($this->DAT_CRIACAO=="", "NULL", "to_date('".$this->DAT_CRIACAO."','yyyy-mm-dd')").",
							".$this->database->iif($this->DAT_ALTERACAO=="", "NULL", "to_date('".$this->DAT_ALTERACAO."','yyyy-mm-dd')")."
					)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.servidor
				 SET SEQ_SISTEMA_OPERACIONAL = ".$this->database->iif($this->SEQ_SISTEMA_OPERACIONAL=="", "NULL", "'".$this->SEQ_SISTEMA_OPERACIONAL."'").",
					 SEQ_MARCA_HARDWARE = ".$this->database->iif($this->SEQ_MARCA_HARDWARE=="", "NULL", "'".$this->SEQ_MARCA_HARDWARE."'").",
					 NUM_PATRIMONIO = ".$this->database->iif($this->NUM_PATRIMONIO=="", "NULL", "'".$this->NUM_PATRIMONIO."'").",
					 NUM_IP = ".$this->database->iif($this->NUM_IP=="", "NULL", "'".$this->NUM_IP."'").",
					 NOM_SERVIDOR = ".$this->database->iif($this->NOM_SERVIDOR=="", "NULL", "'".$this->NOM_SERVIDOR."'").",
					 NOM_MODELO = ".$this->database->iif($this->NOM_MODELO=="", "NULL", "'".$this->NOM_MODELO."'").",
					 DSC_SERVIDOR = ".$this->database->iif($this->DSC_SERVIDOR=="", "NULL", "'".$this->DSC_SERVIDOR."'").",
					 DSC_LOCALIZACAO = ".$this->database->iif($this->DSC_LOCALIZACAO=="", "NULL", "'".$this->DSC_LOCALIZACAO."'").",
					 DSC_PROCESSADOR = ".$this->database->iif($this->DSC_PROCESSADOR=="", "NULL", "'".$this->DSC_PROCESSADOR."'").",
					 TXT_OBSERVACAO = ".$this->database->iif($this->TXT_OBSERVACAO=="", "NULL", "'".$this->TXT_OBSERVACAO."'").",
					 DAT_CRIACAO = ".$this->database->iif($this->DAT_CRIACAO=="", "NULL", "to_date('".$this->DAT_CRIACAO."','yyyy-mm-dd')").",
					 DAT_ALTERACAO = ".$this->database->iif($this->DAT_ALTERACAO=="", "NULL", "to_date('".$this->DAT_ALTERACAO."','yyyy-mm-dd')")."
				 WHERE SEQ_SERVIDOR = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>