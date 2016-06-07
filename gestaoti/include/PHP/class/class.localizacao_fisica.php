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
* Nome da Classe:	localizacao_fisica
* Data de criação:	03.09.2008
* Nome do Arquivo:	D:\Tiago\Pessoal\pages\gestaoti/GeraPHP/include/PHP/class/class.localizacao_fisica.php
* Nome da tabela:	localizacao_fisica
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
class localizacao_fisica{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_LOCALIZACAO_FISICA;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_EDIFICACAO;   // (normal Attribute)
	var $NOM_LOCALIZACAO_FISICA;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function localizacao_fisica(){
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

	function getSEQ_LOCALIZACAO_FISICA(){
		return $this->SEQ_LOCALIZACAO_FISICA;
	}

	function getSEQ_EDIFICACAO(){
		return $this->SEQ_EDIFICACAO;
	}

	function getNOM_LOCALIZACAO_FISICA(){
		return $this->NOM_LOCALIZACAO_FISICA;
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

	function setSEQ_LOCALIZACAO_FISICA($val){
		$this->SEQ_LOCALIZACAO_FISICA =  $val;
	}

	function setSEQ_EDIFICACAO($val){
		$this->SEQ_EDIFICACAO =  $val;
	}

	function setNOM_LOCALIZACAO_FISICA($val){
		$this->NOM_LOCALIZACAO_FISICA =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_LOCALIZACAO_FISICA , SEQ_EDIFICACAO , NOM_LOCALIZACAO_FISICA
			    FROM gestaoti.localizacao_fisica
				WHERE SEQ_LOCALIZACAO_FISICA = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_LOCALIZACAO_FISICA = $row->seq_localizacao_fisica;
		$this->SEQ_EDIFICACAO = $row->seq_edificacao;
		$this->NOM_LOCALIZACAO_FISICA = $row->nom_localizacao_fisica;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_LOCALIZACAO_FISICA , SEQ_EDIFICACAO , NOM_LOCALIZACAO_FISICA ";
		$sqlCorpo  = "FROM gestaoti.localizacao_fisica
						WHERE 1=1 ";

		if($this->SEQ_LOCALIZACAO_FISICA != ""){
			$sqlCorpo .= "  and SEQ_LOCALIZACAO_FISICA = $this->SEQ_LOCALIZACAO_FISICA ";
		}
		if($this->SEQ_EDIFICACAO != ""){
			$sqlCorpo .= "  and SEQ_EDIFICACAO = $this->SEQ_EDIFICACAO ";
		}
		if($this->NOM_LOCALIZACAO_FISICA != ""){
			$sqlCorpo .= "  and upper(NOM_LOCALIZACAO_FISICA) like '%".strtoupper($this->NOM_LOCALIZACAO_FISICA)."%'  ";
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
		$sql = "DELETE FROM gestaoti.localizacao_fisica WHERE SEQ_LOCALIZACAO_FISICA = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_LOCALIZACAO_FISICA = $this->database->GetSequenceValue("gestaoti.SEQ_LOCALIZACAO_FISICA");

		$sql = "INSERT INTO gestaoti.localizacao_fisica(SEQ_LOCALIZACAO_FISICA,
										  SEQ_EDIFICACAO,
										  NOM_LOCALIZACAO_FISICA
									)
							 VALUES (".$this->iif($this->SEQ_LOCALIZACAO_FISICA=="", "NULL", "'".$this->SEQ_LOCALIZACAO_FISICA."'").",
									 ".$this->iif($this->SEQ_EDIFICACAO=="", "NULL", "'".$this->SEQ_EDIFICACAO."'").",
									 ".$this->iif($this->NOM_LOCALIZACAO_FISICA=="", "NULL", "'".$this->NOM_LOCALIZACAO_FISICA."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.localizacao_fisica
				 SET SEQ_EDIFICACAO = ".$this->iif($this->SEQ_EDIFICACAO=="", "NULL", "'".$this->SEQ_EDIFICACAO."'").",
					 NOM_LOCALIZACAO_FISICA = ".$this->iif($this->NOM_LOCALIZACAO_FISICA=="", "NULL", "'".$this->NOM_LOCALIZACAO_FISICA."'")."
				WHERE SEQ_LOCALIZACAO_FISICA = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row["seq_localizacao_fisica"], $this->iif($vSelected == $row["seq_localizacao_fisica"],"Selected", ""), $row["nom_localizacao_fisica"]);
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