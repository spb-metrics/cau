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
* Nome da Classe:	situacao_chamado
* Nome da tabela:	situacao_chamado
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
class situacao_chamado{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_SITUACAO_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $DSC_SITUACAO_CHAMADO;   // (normal Attribute)
	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	var $parametro;

	// Códigos de situações para o processamento automático do sistema
	var $COD_Aguardando_Triagem;
	var $COD_Aguardando_Atendimento;
	var $COD_Em_Andamento;
	var $COD_Encerrada;
	var $COD_Suspenca;
	var $COD_Aguardando_Planejamento;
	var $COD_Aprovado;
	var $COD_Contingenciado;
	var $CODS_EM_ANDAMENTO;
	var $COD_Cancelado;
	var $COD_Aguardando_Aprovacao;

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function situacao_chamado(){
		$this->database = new Database();
		$this->parametro = new parametro();

		// Inicialização dos códigos
		$this->COD_Aguardando_Triagem 		= $this->parametro->GetValorParametro("COD_SITUACAO_Aguardando_Triagem");
		$this->COD_Aguardando_Atendimento 	= $this->parametro->GetValorParametro("COD_SITUACAO_Aguardando_Atendimento");
		$this->COD_Em_Andamento 			= $this->parametro->GetValorParametro("COD_SITUACAO_Em_Andamento");
		$this->COD_Encerrada 				= $this->parametro->GetValorParametro("COD_SITUACAO_Encerrado");
		$this->COD_Cancelado 				= $this->parametro->GetValorParametro("COD_SITUACAO_Cancelado");
		$this->COD_Suspenca 				= $this->parametro->GetValorParametro("COD_SITUACAO_Suspenso");
		$this->COD_Aguardando_Planejamento 	= $this->parametro->GetValorParametro("COD_SITUACAO_Aguardando_Planejamento");
		$this->COD_Contingenciado 			= $this->parametro->GetValorParametro("COD_SITUACAO_Contingenciado");
		$this->COD_Aguardando_Avaliacao 	= $this->parametro->GetValorParametro("COD_SITUACAO_Aguardando_Avaliacao");
		//$this->CODS_EM_ANDAMENTO 			= $this->COD_Em_Andamento.",".$this->COD_Suspenca.",".$this->COD_Aguardando_Atendimento.",".$this->COD_Contingenciado;
		$this->CODS_EM_ANDAMENTO 			= $this->parametro->GetValorParametro("CODS_SITUACAOES_EM_ANDAMENTO");
		$this->COD_Aguardando_Aprovacao 	= $this->parametro->GetValorParametro("COD_SITUACAO_Aguardando_Aprovacao");
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

	function getSEQ_SITUACAO_CHAMADO(){
		return $this->SEQ_SITUACAO_CHAMADO;
	}

	function getDSC_SITUACAO_CHAMADO(){
		return $this->DSC_SITUACAO_CHAMADO;
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

	function setSEQ_SITUACAO_CHAMADO($val){
		$this->SEQ_SITUACAO_CHAMADO =  $val;
	}

	function setDSC_SITUACAO_CHAMADO($val){
		$this->DSC_SITUACAO_CHAMADO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_SITUACAO_CHAMADO , DSC_SITUACAO_CHAMADO
			    FROM gestaoti.situacao_chamado
				WHERE SEQ_SITUACAO_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_SITUACAO_CHAMADO = $row->seq_situacao_chamado;
		$this->DSC_SITUACAO_CHAMADO = $row->dsc_situacao_chamado;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_SITUACAO_CHAMADO , DSC_SITUACAO_CHAMADO ";
		$sqlCorpo  = "FROM gestaoti.situacao_chamado
						WHERE 1=1 ";

		if($this->SEQ_SITUACAO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SITUACAO_CHAMADO = $this->SEQ_SITUACAO_CHAMADO ";
		}
		if($this->DSC_SITUACAO_CHAMADO != ""){
			$sqlCorpo .= "  and upper(DSC_SITUACAO_CHAMADO) like '%".strtoupper($this->DSC_SITUACAO_CHAMADO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.situacao_chamado WHERE SEQ_SITUACAO_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_SITUACAO_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_SITUACAO_CHAMADO");

		$sql = "INSERT INTO gestaoti.situacao_chamado(SEQ_SITUACAO_CHAMADO,
										  DSC_SITUACAO_CHAMADO
									)
							 VALUES (".$this->iif($this->SEQ_SITUACAO_CHAMADO=="", "NULL", "'".$this->SEQ_SITUACAO_CHAMADO."'").",
									 ".$this->iif($this->DSC_SITUACAO_CHAMADO=="", "NULL", "'".$this->DSC_SITUACAO_CHAMADO."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.situacao_chamado
				 SET DSC_SITUACAO_CHAMADO = ".$this->iif($this->DSC_SITUACAO_CHAMADO=="", "NULL", "'".$this->DSC_SITUACAO_CHAMADO."'")."
				WHERE SEQ_SITUACAO_CHAMADO = $id ";
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