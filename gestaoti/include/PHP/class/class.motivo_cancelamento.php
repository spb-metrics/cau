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
* Nome da Classe:	motivo_cancelamento
* Nome da tabela:	motivo_cancelamento
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// DECLARA��O DA CLASSE
// **********************
class motivo_cancelamento{
	// class : begin

	// ***********************
	// DECLARA��O DE ATRIBUTOS
	// ***********************
	var $SEQ_MOTIVO_CANCELAMENTO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $DSC_MOTIVO_CANCELAMENTO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function motivo_cancelamento(){
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

	function getSEQ_MOTIVO_CANCELAMENTO(){
		return $this->SEQ_MOTIVO_CANCELAMENTO;
	}

	function getDSC_MOTIVO_CANCELAMENTO(){
		return $this->DSC_MOTIVO_CANCELAMENTO;
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

	function setSEQ_MOTIVO_CANCELAMENTO($val){
		$this->SEQ_MOTIVO_CANCELAMENTO =  $val;
	}

	function setDSC_MOTIVO_CANCELAMENTO($val){
		$this->DSC_MOTIVO_CANCELAMENTO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_MOTIVO_CANCELAMENTO , DSC_MOTIVO_CANCELAMENTO
			    FROM gestaoti.motivo_cancelamento
				WHERE SEQ_MOTIVO_CANCELAMENTO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_MOTIVO_CANCELAMENTO = $row->seq_motivo_cancelamento;
		$this->DSC_MOTIVO_CANCELAMENTO = $row->dsc_motivo_cancelamento;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_MOTIVO_CANCELAMENTO , DSC_MOTIVO_CANCELAMENTO ";
		$sqlCorpo  = "FROM gestaoti.motivo_cancelamento
						WHERE 1=1 ";

		if($this->SEQ_MOTIVO_CANCELAMENTO != ""){
			$sqlCorpo .= "  and SEQ_MOTIVO_CANCELAMENTO = $this->SEQ_MOTIVO_CANCELAMENTO ";
		}
		if($this->DSC_MOTIVO_CANCELAMENTO != ""){
			$sqlCorpo .= "  and upper(DSC_MOTIVO_CANCELAMENTO) like '%".strtoupper($this->DSC_MOTIVO_CANCELAMENTO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.motivo_cancelamento WHERE SEQ_MOTIVO_CANCELAMENTO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_MOTIVO_CANCELAMENTO = $this->database->GetSequenceValue("gestaoti.SEQ_MOTIVO_CANCELAMENTO");

		$sql = "INSERT INTO gestaoti.motivo_cancelamento(SEQ_MOTIVO_CANCELAMENTO,
										  DSC_MOTIVO_CANCELAMENTO
									)
							 VALUES (".$this->iif($this->SEQ_MOTIVO_CANCELAMENTO=="", "NULL", "'".$this->SEQ_MOTIVO_CANCELAMENTO."'").",
									 ".$this->iif($this->DSC_MOTIVO_CANCELAMENTO=="", "NULL", "'".$this->DSC_MOTIVO_CANCELAMENTO."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.motivo_cancelamento
				 SET DSC_MOTIVO_CANCELAMENTO = ".$this->iif($this->DSC_MOTIVO_CANCELAMENTO=="", "NULL", "'".$this->DSC_MOTIVO_CANCELAMENTO."'")."
				WHERE SEQ_MOTIVO_CANCELAMENTO = $id ";
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