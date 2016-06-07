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
* CLASSNAME:        dependencias
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
}

// **********************
// CLASS DECLARATION
// **********************
class dependencias{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $DEP_CODIGO;   // (normal Attribute)
	var $DEP_SIGLA;   // (normal Attribute)
	var $DEP_NOME;   // (normal Attribute)
	var $DEP_DEP_CODIGO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function dependencias(){
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

	function getDEP_CODIGO(){
		return $this->DEP_CODIGO;
	}

	function getDEP_SIGLA(){
		return $this->DEP_SIGLA;
	}

	function getDEP_NOME(){
		return $this->DEP_NOME;
	}

	function getDEP_DEP_CODIGO(){
		return $this->DEP_DEP_CODIGO;
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
	function setDEP_CODIGO($val){
		$this->DEP_CODIGO =  $val;
	}

	function setDEP_SIGLA($val){
		$this->DEP_SIGLA =  $val;
	}

	function setDEP_NOME($val){
		$this->DEP_NOME =  $val;
	}

	function setDEP_DEP_CODIGO($val){
		$this->DEP_DEP_CODIGO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		if($id != ""){
			$sql =  "SELECT CD_DEPENDENCIA, SG_DEPENDENCIA, NO_DEPENDENCIA, DEP_CD_DEPENDENCIA
					 FROM gestaoti.viw_diretoria
					 WHERE CD_DEPENDENCIA = $id";
			$result =  $this->database->query($sql);
			$result = $this->database->result;

			$row = pg_fetch_object($result, 0);
			$this->DEP_CODIGO = $row->cd_dependencia;
			$this->DEP_SIGLA = $row->sg_dependencia;
			$this->DEP_NOME = $row->no_dependencia;
			$this->DEP_DEP_CODIGO = $row->dep_cd_dependencia;
		}
	}

	function GetSiglaDependencia($id){
		if($id != ""){
			$sql =  "SELECT CD_DEPENDENCIA, SG_DEPENDENCIA, NO_DEPENDENCIA, DEP_CD_DEPENDENCIA
					 FROM gestaoti.viw_diretoria
					 WHERE CD_DEPENDENCIA = $id";
			$result =  $this->database->query($sql);
			$result = $this->database->result;
			$row = pg_fetch_object($result, 0);
			return $row->sg_dependencia;
		}
	}

	function GetCodDependencia($id){
		if($id != ""){
			$sql =  "SELECT CD_DEPENDENCIA, SG_DEPENDENCIA, NO_DEPENDENCIA, DEP_CD_DEPENDENCIA
					 FROM gestaoti.viw_diretoria
					 WHERE SG_DEPENDENCIA = '$id'";
			$result =  $this->database->query($sql);
			$result = $this->database->result;

			$row = pg_fetch_object($result, 0);
			return $row->cd_dependencia;
		}
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT CD_DEPENDENCIA, SG_DEPENDENCIA, NO_DEPENDENCIA, DEP_CD_DEPENDENCIA ";
		$sqlCorpo  = "FROM gestaoti.viw_diretoria
						WHERE 1=1 ";

		if($this->DEP_CODIGO != ""){
			$sqlCorpo .= "  and iddiretoria = $this->DEP_CODIGO ";
		}
		if($this->DEP_SIGLA != ""){
			$sqlCorpo .= "  and upper(sgdiretoria) like '%".strtoupper($this->DEP_SIGLA)."%'  ";
		}
		if($this->DEP_NOME != ""){
			$sqlCorpo .= "  and upper(nodiretoria) like '%".strtoupper($this->DEP_NOME)."%'  ";
		}
		if($this->DEP_DEP_CODIGO != ""){
		//	$sqlCorpo .= "  and DEP_DEP_CODIGO = $this->DEP_DEP_CODIGO ";
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
	function selectParamEquipe($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT DISTINCT iddiretoria as CD_DEPENDENCIA, sgdiretoria as SG_DEPENDENCIA, nodiretoria as NO_DEPENDENCIA, null as DEP_CD_DEPENDENCIA ";
		$sqlCorpo  = "FROM corp.tbdiretoria LEFT OUTER JOIN gestaoti.equipe_ti b on a.iddiretoria = b.COD_DEPENDENCIA
					  where 1=1 ";

		if($this->DEP_CODIGO != ""){
			$sqlCorpo .= "  and DEP_CODIGO = $this->DEP_CODIGO ";
		}
		if($this->DEP_SIGLA != ""){
			$sqlCorpo .= "  and upper(DEP_SIGLA) like '%".strtoupper($this->DEP_SIGLA)."%'  ";
		}
		if($this->DEP_NOME != ""){
			$sqlCorpo .= "  and upper(DEP_NOME) like '%".strtoupper($this->DEP_NOME)."%'  ";
		}
		if($this->DEP_DEP_CODIGO != ""){
			$sqlCorpo .= "  and DEP_DEP_CODIGO = $this->DEP_DEP_CODIGO ";
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

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $this->iif($vSelected == $row[0],"Selected", ""), $row[1]." - ".$row[2]);
			$cont++;
		}
		return $aItemOption;
	}

	function comboSimples($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $this->iif($vSelected == $row[0],"Selected", ""), $row[1]);
			$cont++;
		}
		return $aItemOption;
	}

	function comboSimplesEquipe($OrderBy, $vSelected=""){
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