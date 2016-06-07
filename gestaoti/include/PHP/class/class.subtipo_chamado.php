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
* Nome da Classe:	subtipo_chamado
* Nome da tabela:	subtipo_chamado
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
}

// **********************
// DECLARA��O DA CLASSE
// **********************
class subtipo_chamado{
	// class : begin

	// ***********************
	// DECLARA��O DE ATRIBUTOS
	// ***********************
	var $SEQ_SUBTIPO_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $SEQ_TIPO_CHAMADO;   // (normal Attribute)
	var $SEQ_TIPO_OCORRENCIA;   // (normal Attribute)
	var $DSC_SUBTIPO_CHAMADO;   // (normal Attribute)
	var $FLG_ATENDIMENTO_EXTERNO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	var $SEQ_SUBTIPO_CHAMADO_MANUTENCAO_SISTEMAS;
	var $SEQ_SUBTIPO_CHAMADO_FORA;
	var $SEQ_CENTRAL_ATENDIMENTO; 

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function subtipo_chamado(){
		$this->database = new Database();
		// Codigo cadastrado para manuten��es em sistemas de informa��o
		$this->SEQ_SUBTIPO_CHAMADO_MANUTENCAO_SISTEMAS = "13";
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

	function getSEQ_SUBTIPO_CHAMADO(){
		return $this->SEQ_SUBTIPO_CHAMADO;
	}

	function getSEQ_TIPO_CHAMADO(){
		return $this->SEQ_TIPO_CHAMADO;
	}

	function getDSC_SUBTIPO_CHAMADO(){
		return $this->DSC_SUBTIPO_CHAMADO;
	}

	function getFLG_ATENDIMENTO_EXTERNO(){
		return $this->FLG_ATENDIMENTO_EXTERNO;
	}
	
	function getSEQ_CENTRAL_ATENDIMENTO(){
		return $this->SEQ_CENTRAL_ATENDIMENTO;
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

	function setSEQ_SUBTIPO_CHAMADO($val){
		$this->SEQ_SUBTIPO_CHAMADO =  $val;
	}

	function setSEQ_TIPO_CHAMADO($val){
		$this->SEQ_TIPO_CHAMADO =  $val;
	}

	function setDSC_SUBTIPO_CHAMADO($val){
		$this->DSC_SUBTIPO_CHAMADO =  $val;
	}

	function setFLG_ATENDIMENTO_EXTERNO($val){
		$this->FLG_ATENDIMENTO_EXTERNO =  $val;
	}

	function setSEQ_SUBTIPO_CHAMADO_FORA($val){
		$this->SEQ_SUBTIPO_CHAMADO_FORA = $val;
	}

	function setSEQ_TIPO_OCORRENCIA($val){
		$this->SEQ_TIPO_OCORRENCIA = $val;
	}

	function setSEQ_CENTRAL_ATENDIMENTO($val){
		$this->SEQ_CENTRAL_ATENDIMENTO =  $val;
	}	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_SUBTIPO_CHAMADO , a.SEQ_TIPO_CHAMADO , DSC_SUBTIPO_CHAMADO , a.FLG_ATENDIMENTO_EXTERNO, b.seq_central_atendimento
			    FROM gestaoti.subtipo_chamado a ,gestaoti.tipo_chamado b
				WHERE  a.SEQ_TIPO_CHAMADO = b.SEQ_TIPO_CHAMADO and SEQ_SUBTIPO_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_SUBTIPO_CHAMADO = $row->seq_subtipo_chamado;
		$this->SEQ_TIPO_CHAMADO = $row->seq_tipo_chamado;
		$this->DSC_SUBTIPO_CHAMADO = $row->dsc_subtipo_chamado;
		$this->FLG_ATENDIMENTO_EXTERNO = $row->flg_atendimento_externo;
		$this->SEQ_CENTRAL_ATENDIMENTO = $row->seq_central_atendimento;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT distinct SEQ_SUBTIPO_CHAMADO , a.SEQ_TIPO_CHAMADO , DSC_SUBTIPO_CHAMADO , a.FLG_ATENDIMENTO_EXTERNO ";
		
		$sqlCorpo  = "FROM gestaoti.subtipo_chamado a ";
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo  .= ",gestaoti.tipo_chamado b WHERE 1=1 ";
		}else{
			$sqlCorpo  .= " WHERE 1=1 ";
		}
		

		if($this->SEQ_SUBTIPO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SUBTIPO_CHAMADO = $this->SEQ_SUBTIPO_CHAMADO ";
		}
		if($this->SEQ_TIPO_CHAMADO != ""){
			$sqlCorpo .= "  and a.SEQ_TIPO_CHAMADO = $this->SEQ_TIPO_CHAMADO ";
		}
		if($this->DSC_SUBTIPO_CHAMADO != ""){
			$sqlCorpo .= "  and upper(DSC_SUBTIPO_CHAMADO) like '%".strtoupper($this->DSC_SUBTIPO_CHAMADO)."%'  ";
		}
		if($this->FLG_ATENDIMENTO_EXTERNO != ""){
			$sqlCorpo .= "  and a.FLG_ATENDIMENTO_EXTERNO = '$this->FLG_ATENDIMENTO_EXTERNO' ";
		}
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo .= "  and a.SEQ_TIPO_CHAMADO = b.SEQ_TIPO_CHAMADO and b.seq_central_atendimento = '$this->SEQ_CENTRAL_ATENDIMENTO' ";
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

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParamCombo($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT distinct a.SEQ_SUBTIPO_CHAMADO , a.SEQ_TIPO_CHAMADO , a.DSC_SUBTIPO_CHAMADO ";
		$sqlCorpo  = "FROM gestaoti.subtipo_chamado a, gestaoti.atividade_chamado b ";
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo  .= ",gestaoti.tipo_chamado c ";
	 
		}
		$sqlCorpo  .= " WHERE a.SEQ_SUBTIPO_CHAMADO = b.SEQ_SUBTIPO_CHAMADO ";
		
		if($this->SEQ_SUBTIPO_CHAMADO != ""){
			$sqlCorpo .= "  and a.SEQ_SUBTIPO_CHAMADO = $this->SEQ_SUBTIPO_CHAMADO ";
		}
		if($this->SEQ_TIPO_CHAMADO != ""){
			$sqlCorpo .= "  and a.SEQ_TIPO_CHAMADO = $this->SEQ_TIPO_CHAMADO ";
		}
		if($this->DSC_SUBTIPO_CHAMADO != ""){
			$sqlCorpo .= "  and upper(DSC_SUBTIPO_CHAMADO) like '%".strtoupper($this->DSC_SUBTIPO_CHAMADO)."%'  ";
		}
		if($this->FLG_ATENDIMENTO_EXTERNO != ""){
			$sqlCorpo .= "  and b.FLG_ATENDIMENTO_EXTERNO = '$this->FLG_ATENDIMENTO_EXTERNO' ";
		}
		if($this->SEQ_SUBTIPO_CHAMADO_FORA != ""){
			$sqlCorpo .= "  and a.SEQ_SUBTIPO_CHAMADO not in ('$this->SEQ_SUBTIPO_CHAMADO_FORA') ";
		}
		if($this->SEQ_TIPO_OCORRENCIA != ""){
			$sqlCorpo .= "  and b.SEQ_TIPO_OCORRENCIA = $this->SEQ_TIPO_OCORRENCIA ";
		}
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo .= "  and a.SEQ_TIPO_CHAMADO = c.SEQ_TIPO_CHAMADO and c.seq_central_atendimento = '$this->SEQ_CENTRAL_ATENDIMENTO' ";
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
		$sql = "DELETE FROM gestaoti.subtipo_chamado WHERE SEQ_SUBTIPO_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_SUBTIPO_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_SUBTIPO_CHAMADO");

		$sql = "INSERT INTO gestaoti.subtipo_chamado(SEQ_SUBTIPO_CHAMADO,
										  SEQ_TIPO_CHAMADO,
										  DSC_SUBTIPO_CHAMADO,
										  FLG_ATENDIMENTO_EXTERNO
									)
							 VALUES (".$this->iif($this->SEQ_SUBTIPO_CHAMADO=="", "NULL", "'".$this->SEQ_SUBTIPO_CHAMADO."'").",
									 ".$this->iif($this->SEQ_TIPO_CHAMADO=="", "NULL", "'".$this->SEQ_TIPO_CHAMADO."'").",
									 ".$this->iif($this->DSC_SUBTIPO_CHAMADO=="", "NULL", "'".$this->DSC_SUBTIPO_CHAMADO."'").",
									 ".$this->iif($this->FLG_ATENDIMENTO_EXTERNO=="", "NULL", "'".$this->FLG_ATENDIMENTO_EXTERNO."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.subtipo_chamado
				 SET SEQ_TIPO_CHAMADO = ".$this->iif($this->SEQ_TIPO_CHAMADO=="", "NULL", "'".$this->SEQ_TIPO_CHAMADO."'").",
					 DSC_SUBTIPO_CHAMADO = ".$this->iif($this->DSC_SUBTIPO_CHAMADO=="", "NULL", "'".$this->DSC_SUBTIPO_CHAMADO."'").",
					 FLG_ATENDIMENTO_EXTERNO = ".$this->iif($this->FLG_ATENDIMENTO_EXTERNO=="", "NULL", "'".$this->FLG_ATENDIMENTO_EXTERNO."'")."
				WHERE SEQ_SUBTIPO_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParamCombo($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row["seq_subtipo_chamado"], $this->iif($vSelected == $row["seq_subtipo_chamado"],"Selected", ""), $row["dsc_subtipo_chamado"]);
			$cont++;
		}
		return $aItemOption;
	}

	function combo2($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row["seq_subtipo_chamado"], $this->iif($vSelected == $row["seq_subtipo_chamado"],"Selected", ""), $row["dsc_subtipo_chamado"]);
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