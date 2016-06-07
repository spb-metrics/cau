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
* Nome da Classe:	tipo_chamado
* Nome da tabela:	tipo_chamado
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
	include_once("../gestaoti/include/PHP/class/class.parametro.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
	include_once("include/PHP/class/class.parametro.php");
}

// **********************
// DECLARAÇÃO DA CLASSE
// **********************
class tipo_chamado{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_TIPO_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $DSC_TIPO_CHAMADO;   // (normal Attribute)
	var $FLG_ATENDIMENTO_EXTERNO;   // (normal Attribute)
	var $COD_TIPO_SISTEMAS_INFORMACAO;
	var $SEQ_TIPO_CHAMADO_NAO_EXIBIR;
	var $SEQ_TIPO_OCORRENCIA;
	var $SEQ_CENTRAL_ATENDIMENTO;  // (normal Attribute)
	var $FLG_UTILIZADO_SLA;  // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	var $parametro;

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function tipo_chamado(){
		$this->database = new Database();
		$this->parametro = new parametro();

		$this->COD_TIPO_SISTEMAS_INFORMACAO = $this->parametro->GetValorParametro("COD_TIPO_SISTEMAS_INFORMACAO");
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

	function getSEQ_TIPO_CHAMADO(){
		return $this->SEQ_TIPO_CHAMADO;
	}

	function getDSC_TIPO_CHAMADO(){
		return $this->DSC_TIPO_CHAMADO;
	}

	function getFLG_ATENDIMENTO_EXTERNO(){
		return $this->FLG_ATENDIMENTO_EXTERNO;
	}
	
	function getSEQ_CENTRAL_ATENDIMENTO(){
		return $this->SEQ_CENTRAL_ATENDIMENTO;
	}

	function getFLG_UTILIZADO_SLA(){
		return $this->FLG_UTILIZADO_SLA;
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

	function setSEQ_TIPO_CHAMADO($val){
		$this->SEQ_TIPO_CHAMADO =  $val;
	}

	function setDSC_TIPO_CHAMADO($val){
		$this->DSC_TIPO_CHAMADO =  $val;
	}

	function setFLG_ATENDIMENTO_EXTERNO($val){
		$this->FLG_ATENDIMENTO_EXTERNO =  $val;
	}

	function setSEQ_TIPO_CHAMADO_NAO_EXIBIR($val){
		$this->SEQ_TIPO_CHAMADO_NAO_EXIBIR =  $val;
	}
	function setSEQ_TIPO_OCORRENCIA($val){
		$this->SEQ_TIPO_OCORRENCIA =  $val;
	}
	
	function setSEQ_CENTRAL_ATENDIMENTO($val){
		$this->SEQ_CENTRAL_ATENDIMENTO =  $val;
	}
	
	function setFLG_UTILIZADO_SLA($val){
		$this->FLG_UTILIZADO_SLA =  $val;
	}
	
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_TIPO_CHAMADO , DSC_TIPO_CHAMADO , FLG_ATENDIMENTO_EXTERNO,seq_central_atendimento,FLG_UTILIZADO_SLA
			    FROM gestaoti.tipo_chamado
				WHERE SEQ_TIPO_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		if($this->database->rows > 0){
			$row = pg_fetch_object($result, 0);
			$this->SEQ_TIPO_CHAMADO = $row->seq_tipo_chamado;
			$this->DSC_TIPO_CHAMADO = $row->dsc_tipo_chamado;
			$this->FLG_ATENDIMENTO_EXTERNO = $row->flg_atendimento_externo;
			$this->SEQ_CENTRAL_ATENDIMENTO = $row->seq_central_atendimento;
			$this->FLG_UTILIZADO_SLA= $row->flg_utilizado_sla;
		}
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_TIPO_CHAMADO , DSC_TIPO_CHAMADO , FLG_ATENDIMENTO_EXTERNO,seq_central_atendimento,flg_utilizado_sla ";
		$sqlCorpo  = "FROM gestaoti.tipo_chamado
						WHERE 1=1 ";

		if($this->SEQ_TIPO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_TIPO_CHAMADO = $this->SEQ_TIPO_CHAMADO ";
		}
		if($this->DSC_TIPO_CHAMADO != ""){
			$sqlCorpo .= "  and upper(DSC_TIPO_CHAMADO) like '%".strtoupper($this->DSC_TIPO_CHAMADO)."%'  ";
		}
		if($this->FLG_ATENDIMENTO_EXTERNO != ""){
			$sqlCorpo .= "  and FLG_ATENDIMENTO_EXTERNO = '$this->FLG_ATENDIMENTO_EXTERNO' ";
		}
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo .= "  and seq_central_atendimento = '$this->SEQ_CENTRAL_ATENDIMENTO' ";
		}
		if($this->FLG_UTILIZADO_SLA != ""){
			$sqlCorpo .= "  and FLG_UTILIZADO_SLA = '$this->FLG_UTILIZADO_SLA' ";
		}
		
		if($this->SEQ_TIPO_CHAMADO_NAO_EXIBIR != ""){
			$sqlCorpo .= "  and SEQ_TIPO_CHAMADO not in ($this->SEQ_TIPO_CHAMADO_NAO_EXIBIR) ";
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
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParamCombo($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT distinct a.SEQ_TIPO_CHAMADO , a.DSC_TIPO_CHAMADO,seq_central_atendimento,flg_utilizado_sla ";
		$sqlCorpo  = "FROM gestaoti.tipo_chamado a, gestaoti.subtipo_chamado b, gestaoti.atividade_chamado c
					  WHERE a.SEQ_TIPO_CHAMADO = b.SEQ_TIPO_CHAMADO
						and b.SEQ_SUBTIPO_CHAMADO = c.SEQ_SUBTIPO_CHAMADO
					  ";

		if($this->SEQ_TIPO_CHAMADO != ""){
			$sqlCorpo .= "  and a.SEQ_TIPO_CHAMADO = $this->SEQ_TIPO_CHAMADO ";
		}
		if($this->DSC_TIPO_CHAMADO != ""){
			$sqlCorpo .= "  and upper(DSC_TIPO_CHAMADO) like '%".strtoupper($this->DSC_TIPO_CHAMADO)."%'  ";
		}
		if($this->FLG_ATENDIMENTO_EXTERNO != ""){
			$sqlCorpo .= "  and c.FLG_ATENDIMENTO_EXTERNO = '$this->FLG_ATENDIMENTO_EXTERNO' ";
		}
		if($this->SEQ_TIPO_CHAMADO_NAO_EXIBIR != ""){
			$sqlCorpo .= "  and a.SEQ_TIPO_CHAMADO not in ($this->SEQ_TIPO_CHAMADO_NAO_EXIBIR) ";
		}
		if($this->SEQ_TIPO_OCORRENCIA != ""){
			$sqlCorpo .= "  and c.SEQ_TIPO_OCORRENCIA = $this->SEQ_TIPO_OCORRENCIA ";
		}
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo .= "  and a.seq_central_atendimento = '$this->SEQ_CENTRAL_ATENDIMENTO' ";
		}
		if($this->FLG_UTILIZADO_SLA != ""){
			$sqlCorpo .= "  and a.flg_utilizado_sla = '$this->FLG_UTILIZADO_SLA' ";
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
		$sql = "DELETE FROM gestaoti.tipo_chamado WHERE SEQ_TIPO_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_TIPO_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_TIPO_CHAMADO");

		$sql = "INSERT INTO gestaoti.tipo_chamado(SEQ_TIPO_CHAMADO,
										  DSC_TIPO_CHAMADO,
										  FLG_ATENDIMENTO_EXTERNO,seq_central_atendimento,FLG_UTILIZADO_SLA
									)
							 VALUES (".$this->iif($this->SEQ_TIPO_CHAMADO=="", "NULL", "'".$this->SEQ_TIPO_CHAMADO."'").",
									 ".$this->iif($this->DSC_TIPO_CHAMADO=="", "NULL", "'".$this->DSC_TIPO_CHAMADO."'").",
									 ".$this->iif($this->FLG_ATENDIMENTO_EXTERNO=="", "NULL", "'".$this->FLG_ATENDIMENTO_EXTERNO."'").",
									 ".$this->iif($this->SEQ_CENTRAL_ATENDIMENTO=="", "NULL", "'".$this->SEQ_CENTRAL_ATENDIMENTO."'").",
									 ".$this->iif($this->FLG_UTILIZADO_SLA=="", "NULL", "'".$this->FLG_UTILIZADO_SLA."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.tipo_chamado
				 SET DSC_TIPO_CHAMADO = ".$this->iif($this->DSC_TIPO_CHAMADO=="", "NULL", "'".$this->DSC_TIPO_CHAMADO."'").",
				 seq_central_atendimento = ".$this->iif($this->SEQ_CENTRAL_ATENDIMENTO=="", "NULL", "'".$this->SEQ_CENTRAL_ATENDIMENTO."'").",
				 FLG_UTILIZADO_SLA = ".$this->iif($this->FLG_UTILIZADO_SLA=="", "NULL", "'".$this->FLG_UTILIZADO_SLA."'").",
					 FLG_ATENDIMENTO_EXTERNO = ".$this->iif($this->FLG_ATENDIMENTO_EXTERNO=="", "NULL", "'".$this->FLG_ATENDIMENTO_EXTERNO."'")."
				WHERE SEQ_TIPO_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParamCombo($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $this->iif($vSelected == $row[0],"Selected", ""), $row[1]);
			$cont++;
		}
		return $aItemOption;
	}

	function combo2($OrderBy, $vSelected=""){
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