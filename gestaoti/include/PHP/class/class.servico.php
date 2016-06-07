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
* CLASSNAME:        servico
* GENERATION DATE:  11.03.2008
* -------------------------------------------------------
*
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class servico{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_SERVICO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_TIPO_SERVICO;   // (normal Attribute)
	var $SEQ_CONTRATO_SLA;   // (normal Attribute)
	var $NOM_SERVICO;   // (normal Attribute)
	var $DES_SERVICO;   // (normal Attribute)
	var $DES_CLIENTES;   // (normal Attribute)
	var $DES_QUANTIDADE_CLIENTES;   // (normal Attribute)
	var $DES_QUANTIDADE_CLIENTES_CONCORRENTES;   // (normal Attribute)
	var $QTD_MINUTOS_ATENDIMENTO;   // (normal Attribute)
	var $VAL_PERCENT_DISPONIBILIDADE;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function servico(){
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
	function getSEQ_SERVICO(){
		return $this->SEQ_SERVICO;
	}

	function getSEQ_TIPO_SERVICO(){
		return $this->SEQ_TIPO_SERVICO;
	}

	function getSEQ_CONTRATO_SLA(){
		return $this->SEQ_CONTRATO_SLA;
	}

	function getNOM_SERVICO(){
		return $this->NOM_SERVICO;
	}

	function getDES_SERVICO(){
		return $this->DES_SERVICO;
	}

	function getDES_CLIENTES(){
		return $this->DES_CLIENTES;
	}

	function getDES_QUANTIDADE_CLIENTES(){
		return $this->DES_QUANTIDADE_CLIENTES;
	}

	function getDES_QUANTIDADE_CLIENTES_CONCORRENTES(){
		return $this->DES_QUANTIDADE_CLIENTES_CONCORRENTES;
	}

	function getQTD_MINUTOS_ATENDIMENTO(){
		return $this->QTD_MINUTOS_ATENDIMENTO;
	}

	function getVAL_PERCENT_DISPONIBILIDADE(){
		return $this->VAL_PERCENT_DISPONIBILIDADE;
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
	function setSEQ_SERVICO($val){
		$this->SEQ_SERVICO =  $val;
	}

	function setSEQ_TIPO_SERVICO($val){
		$this->SEQ_TIPO_SERVICO =  $val;
	}

	function setSEQ_CONTRATO_SLA($val){
		$this->SEQ_CONTRATO_SLA =  $val;
	}

	function setNOM_SERVICO($val){
		$this->NOM_SERVICO =  $val;
	}

	function setDES_SERVICO($val){
		$this->DES_SERVICO =  $val;
	}

	function setDES_CLIENTES($val){
		$this->DES_CLIENTES =  $val;
	}

	function setDES_QUANTIDADE_CLIENTES($val){
		$this->DES_QUANTIDADE_CLIENTES =  $val;
	}

	function setDES_QUANTIDADE_CLIENTES_CONCORRENTES($val){
		$this->DES_QUANTIDADE_CLIENTES_CONCORRENTES =  $val;
	}

	function setQTD_MINUTOS_ATENDIMENTO($val){
		$this->QTD_MINUTOS_ATENDIMENTO =  $val;
	}

	function setVAL_PERCENT_DISPONIBILIDADE($val){
		$this->VAL_PERCENT_DISPONIBILIDADE =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.servico WHERE SEQ_SERVICO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_SERVICO = $row->SEQ_SERVICO;
		$this->SEQ_TIPO_SERVICO = $row->SEQ_TIPO_SERVICO;
		$this->SEQ_CONTRATO_SLA = $row->SEQ_CONTRATO_SLA;
		$this->NOM_SERVICO = $row->NOM_SERVICO;
		$this->DES_SERVICO = $row->DES_SERVICO;
		$this->DES_CLIENTES = $row->DES_CLIENTES;
		$this->DES_QUANTIDADE_CLIENTES = $row->DES_QUANTIDADE_CLIENTES;
		$this->DES_QUANTIDADE_CLIENTES_CONCORRENTES = $row->DES_QUANTIDADE_CLIENTES_CONCORRENTES;
		$this->QTD_MINUTOS_ATENDIMENTO = $row->QTD_MINUTOS_ATENDIMENTO;
		$this->VAL_PERCENT_DISPONIBILIDADE = $row->VAL_PERCENT_DISPONIBILIDADE;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT SEQ_SERVICO , SEQ_TIPO_SERVICO , SEQ_CONTRATO_SLA , NOM_SERVICO , DES_SERVICO , DES_CLIENTES , DES_QUANTIDADE_CLIENTES , DES_QUANTIDADE_CLIENTES_CONCORRENTES , QTD_MINUTOS_ATENDIMENTO , VAL_PERCENT_DISPONIBILIDADE ";
		$sqlCorpo  = " FROM gestaoti.servico
			      WHERE 1=1 ";

		if($this->SEQ_SERVICO != ""){
			$sqlCorpo .= "  and SEQ_SERVICO = $this->SEQ_SERVICO ";
		}
		if($this->SEQ_TIPO_SERVICO != ""){
			$sqlCorpo .= "  and SEQ_TIPO_SERVICO = $this->SEQ_TIPO_SERVICO ";
		}
		if($this->SEQ_CONTRATO_SLA != ""){
			$sqlCorpo .= "  and SEQ_CONTRATO_SLA = $this->SEQ_CONTRATO_SLA ";
		}
		if($this->NOM_SERVICO != ""){
			$sqlCorpo .= "  and upper(NOM_SERVICO) like '%".strtoupper($this->NOM_SERVICO)."%'  ";
		}
		if($this->DES_SERVICO != ""){
			$sqlCorpo .= "  and upper(DES_SERVICO) like '%".strtoupper($this->DES_SERVICO)."%'  ";
		}
		if($this->DES_CLIENTES != ""){
			$sqlCorpo .= "  and upper(DES_CLIENTES) like '%".strtoupper($this->DES_CLIENTES)."%'  ";
		}
		if($this->DES_QUANTIDADE_CLIENTES != ""){
			$sqlCorpo .= "  and upper(DES_QUANTIDADE_CLIENTES) like '%".strtoupper($this->DES_QUANTIDADE_CLIENTES)."%'  ";
		}
		if($this->DES_QUANTIDADE_CLIENTES_CONCORRENTES != ""){
			$sqlCorpo .= "  and upper(DES_QUANTIDADE_CLIENTES_CONCORRENTES) like '%".strtoupper($this->DES_QUANTIDADE_CLIENTES_CONCORRENTES)."%'  ";
		}
		if($this->QTD_MINUTOS_ATENDIMENTO != ""){
			$sqlCorpo .= "  and QTD_MINUTOS_ATENDIMENTO = $this->QTD_MINUTOS_ATENDIMENTO ";
		}
		if($this->VAL_PERCENT_DISPONIBILIDADE != ""){
			$sqlCorpo .= "  and VAL_PERCENT_DISPONIBILIDADE = $this->VAL_PERCENT_DISPONIBILIDADE ";
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
		$sql = "DELETE FROM gestaoti.servico WHERE SEQ_SERVICO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_SERVICO = $this->database->GetSequenceValue("gestaoti.SEQ_"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.servico ( SEQ_TIPO_SERVICO,SEQ_CONTRATO_SLA,NOM_SERVICO,DES_SERVICO,DES_CLIENTES,DES_QUANTIDADE_CLIENTES,DES_QUANTIDADE_CLIENTES_CONCORRENTES,QTD_MINUTOS_ATENDIMENTO,VAL_PERCENT_DISPONIBILIDADE ) VALUES ( ".$this->database->iif($this->SEQ_TIPO_SERVICO=="", "NULL", "'".$this->SEQ_TIPO_SERVICO."'").",".$this->database->iif($this->SEQ_CONTRATO_SLA=="", "NULL", "'".$this->SEQ_CONTRATO_SLA."'").",".$this->database->iif($this->NOM_SERVICO=="", "NULL", "'".$this->NOM_SERVICO."'").",".$this->database->iif($this->DES_SERVICO=="", "NULL", "'".$this->DES_SERVICO."'").",".$this->database->iif($this->DES_CLIENTES=="", "NULL", "'".$this->DES_CLIENTES."'").",".$this->database->iif($this->DES_QUANTIDADE_CLIENTES=="", "NULL", "'".$this->DES_QUANTIDADE_CLIENTES."'").",".$this->database->iif($this->DES_QUANTIDADE_CLIENTES_CONCORRENTES=="", "NULL", "'".$this->DES_QUANTIDADE_CLIENTES_CONCORRENTES."'").",".$this->database->iif($this->QTD_MINUTOS_ATENDIMENTO=="", "NULL", "'".$this->QTD_MINUTOS_ATENDIMENTO."'").",".$this->database->iif($this->VAL_PERCENT_DISPONIBILIDADE=="", "NULL", "'".$this->VAL_PERCENT_DISPONIBILIDADE."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.servico SET  SEQ_TIPO_SERVICO = ".$this->database->iif($this->SEQ_TIPO_SERVICO=="", "NULL", "'".$this->SEQ_TIPO_SERVICO."'").",SEQ_CONTRATO_SLA = ".$this->database->iif($this->SEQ_CONTRATO_SLA=="", "NULL", "'".$this->SEQ_CONTRATO_SLA."'").",NOM_SERVICO = ".$this->database->iif($this->NOM_SERVICO=="", "NULL", "'".$this->NOM_SERVICO."'").",DES_SERVICO = ".$this->database->iif($this->DES_SERVICO=="", "NULL", "'".$this->DES_SERVICO."'").",DES_CLIENTES = ".$this->database->iif($this->DES_CLIENTES=="", "NULL", "'".$this->DES_CLIENTES."'").",DES_QUANTIDADE_CLIENTES = ".$this->database->iif($this->DES_QUANTIDADE_CLIENTES=="", "NULL", "'".$this->DES_QUANTIDADE_CLIENTES."'").",DES_QUANTIDADE_CLIENTES_CONCORRENTES = ".$this->database->iif($this->DES_QUANTIDADE_CLIENTES_CONCORRENTES=="", "NULL", "'".$this->DES_QUANTIDADE_CLIENTES_CONCORRENTES."'").",QTD_MINUTOS_ATENDIMENTO = ".$this->database->iif($this->QTD_MINUTOS_ATENDIMENTO=="", "NULL", "'".$this->QTD_MINUTOS_ATENDIMENTO."'").",VAL_PERCENT_DISPONIBILIDADE = ".$this->database->iif($this->VAL_PERCENT_DISPONIBILIDADE=="", "NULL", "'".$this->VAL_PERCENT_DISPONIBILIDADE."'")." WHERE SEQ_SERVICO = $id ";
		$result = $this->database->query($sql);

	}

} // class : end
?>