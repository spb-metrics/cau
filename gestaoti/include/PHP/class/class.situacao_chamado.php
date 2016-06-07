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
// DECLARA��O DA CLASSE
// **********************
class situacao_chamado{
	// class : begin

	// ***********************
	// DECLARA��O DE ATRIBUTOS
	// ***********************
	var $SEQ_SITUACAO_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $DSC_SITUACAO_CHAMADO;   // (normal Attribute)
	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados
	var $parametro;

	// C�digos de situa��es para o processamento autom�tico do sistema
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

		// Inicializa��o dos c�digos
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
	// SELECT METHOD COM PAR�METROS
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