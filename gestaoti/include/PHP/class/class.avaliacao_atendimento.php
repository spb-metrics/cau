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
* Nome da Classe:	avaliacao_atendimento
* Nome da tabela:	avaliacao_atendimento
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
}

// **********************
// DECLARAÇÃO DA CLASSE
// **********************
class avaliacao_atendimento{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_AVALIACAO_ATENDIMENTO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NOM_AVALIACAO_ATENDIMENTO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function avaliacao_atendimento(){
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

	function getSEQ_AVALIACAO_ATENDIMENTO(){
		return $this->SEQ_AVALIACAO_ATENDIMENTO;
	}

	function getNOM_AVALIACAO_ATENDIMENTO(){
		return $this->NOM_AVALIACAO_ATENDIMENTO;
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

	function setSEQ_AVALIACAO_ATENDIMENTO($val){
		$this->SEQ_AVALIACAO_ATENDIMENTO =  $val;
	}

	function setNOM_AVALIACAO_ATENDIMENTO($val){
		$this->NOM_AVALIACAO_ATENDIMENTO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_AVALIACAO_ATENDIMENTO , NOM_AVALIACAO_ATENDIMENTO
			    FROM gestaoti.avaliacao_atendimento
				WHERE SEQ_AVALIACAO_ATENDIMENTO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_AVALIACAO_ATENDIMENTO = $row->seq_avaliacao_atendimento;
		$this->NOM_AVALIACAO_ATENDIMENTO = $row->nom_avaliacao_atendimento;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_AVALIACAO_ATENDIMENTO , NOM_AVALIACAO_ATENDIMENTO ";
		$sqlCorpo  = "FROM gestaoti.avaliacao_atendimento
					  WHERE 1 = 1 ";

		if($this->SEQ_AVALIACAO_ATENDIMENTO != ""){
			$sqlCorpo .= "  and SEQ_AVALIACAO_ATENDIMENTO = $this->SEQ_AVALIACAO_ATENDIMENTO ";
		}
		if($this->NOM_AVALIACAO_ATENDIMENTO != ""){
			$sqlCorpo .= "  and upper(NOM_AVALIACAO_ATENDIMENTO) like '%".strtoupper($this->NOM_AVALIACAO_ATENDIMENTO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.avaliacao_atendimento WHERE SEQ_AVALIACAO_ATENDIMENTO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_AVALIACAO_ATENDIMENTO = $this->database->GetSequenceValue("gestaoti.SEQ_AVALIACAO_ATENDIMENTO");

		$sql = "INSERT INTO gestaoti.avaliacao_atendimento(SEQ_AVALIACAO_ATENDIMENTO,
										  NOM_AVALIACAO_ATENDIMENTO
									)
							 VALUES (".$this->iif($this->SEQ_AVALIACAO_ATENDIMENTO=="", "NULL", "'".$this->SEQ_AVALIACAO_ATENDIMENTO."'").",
									 ".$this->iif($this->NOM_AVALIACAO_ATENDIMENTO=="", "NULL", "'".$this->NOM_AVALIACAO_ATENDIMENTO."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.avaliacao_atendimento
				 SET NOM_AVALIACAO_ATENDIMENTO = ".$this->iif($this->NOM_AVALIACAO_ATENDIMENTO=="", "NULL", "'".$this->NOM_AVALIACAO_ATENDIMENTO."'")."
				WHERE SEQ_AVALIACAO_ATENDIMENTO = $id ";
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
	
	function obterTodos(){
		$aItemOption = Array();
		$this->selectParam(1);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $row[1]);
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