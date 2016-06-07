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
* Nome da Classe:	edificacao
* Nome da tabela:	edificacao
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
class edificacao{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_EDIFICACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NOM_EDIFICACAO;   // (normal Attribute)
	var $COD_DEPENDENCIA;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function edificacao(){
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

	function getSEQ_EDIFICACAO(){
		return $this->SEQ_EDIFICACAO;
	}

	function getNOM_EDIFICACAO(){
		return $this->NOM_EDIFICACAO;
	}

	function getCOD_DEPENDENCIA(){
		return $this->COD_DEPENDENCIA;
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

	function setSEQ_EDIFICACAO($val){
		$this->SEQ_EDIFICACAO =  $val;
	}

	function setNOM_EDIFICACAO($val){
		$this->NOM_EDIFICACAO =  $val;
	}

	function setCOD_DEPENDENCIA($val){
		$this->COD_DEPENDENCIA =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_EDIFICACAO , NOM_EDIFICACAO , COD_DEPENDENCIA
			    FROM gestaoti.edificacao
				WHERE SEQ_EDIFICACAO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_EDIFICACAO = $row->seq_edificacao;
		$this->NOM_EDIFICACAO = $row->nom_edificacao;
		$this->COD_DEPENDENCIA = $row->cod_dependencia;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_EDIFICACAO, NOM_EDIFICACAO, NULL as COD_DEPENDENCIA, NULL as SG_DEPENDENCIA, NULL as NO_DEPENDENCIA ";
		$sqlCorpo  = "FROM gestaoti.edificacao
					  WHERE 1=1 ";

		if($this->SEQ_EDIFICACAO != ""){
			$sqlCorpo .= "  and SEQ_EDIFICACAO = $this->SEQ_EDIFICACAO ";
		}
		if($this->NOM_EDIFICACAO != ""){
			$sqlCorpo .= "  and upper(NOM_EDIFICACAO) like '%".strtoupper($this->NOM_EDIFICACAO)."%'  ";
		}
		if($this->COD_DEPENDENCIA != ""){
			//$sqlCorpo .= "  and COD_DEPENDENCIA = $this->COD_DEPENDENCIA ";
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
		$sql = "DELETE FROM gestaoti.edificacao WHERE SEQ_EDIFICACAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_EDIFICACAO = $this->database->GetSequenceValue("gestaoti.SEQ_EDIFICACAO_INFRAERO");

		$sql = "INSERT INTO gestaoti.edificacao(SEQ_EDIFICACAO,
										  NOM_EDIFICACAO,
										  COD_DEPENDENCIA
									)
							 VALUES (".$this->iif($this->SEQ_EDIFICACAO=="", "NULL", "'".$this->SEQ_EDIFICACAO."'").",
									 ".$this->iif($this->NOM_EDIFICACAO=="", "NULL", "'".$this->NOM_EDIFICACAO."'").",
									 ".$this->iif($this->COD_DEPENDENCIA=="", "NULL", "'".$this->COD_DEPENDENCIA."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.edificacao
				 SET NOM_EDIFICACAO = ".$this->iif($this->NOM_EDIFICACAO=="", "NULL", "'".$this->NOM_EDIFICACAO."'").",
					 COD_DEPENDENCIA = ".$this->iif($this->COD_DEPENDENCIA=="", "NULL", "'".$this->COD_DEPENDENCIA."'")."
				WHERE SEQ_EDIFICACAO = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			//$aItemOption[$cont] = array($row[0], $this->iif($vSelected == $row[0],"Selected", ""),$row["no_dependencia"]." - ". $row["nom_edificacao"]);
			$aItemOption[$cont] = array($row[0], $this->iif($vSelected == $row[0],"Selected", ""),$row["nom_edificacao"]);
			$cont++;
		}
		return $aItemOption;
	}

	function comboSimples($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $this->iif($vSelected == $row[0],"Selected", ""), $row["nom_edificacao"]);
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