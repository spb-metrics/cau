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
* Nome da Classe:	historico_acesso_chamado
* Nome da tabela:	historico_acesso_chamado
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
class historico_acesso_chamado{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_HISTORICO_ACESSO_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_CHAMADO;   // (normal Attribute)
	var $NUM_MATRICULA;   // (normal Attribute)
	var $DTH_ACESSO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function historico_acesso_chamado(){
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

	function getSEQ_HISTORICO_ACESSO_CHAMADO(){
		return $this->SEQ_HISTORICO_ACESSO_CHAMADO;
	}

	function getSEQ_CHAMADO(){
		return $this->SEQ_CHAMADO;
	}

	function getNUM_MATRICULA(){
		return $this->NUM_MATRICULA;
	}

	function getDTH_ACESSO(){
		return $this->DTH_ACESSO;
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

	function setSEQ_HISTORICO_ACESSO_CHAMADO($val){
		$this->SEQ_HISTORICO_ACESSO_CHAMADO =  $val;
	}

	function setSEQ_CHAMADO($val){
		$this->SEQ_CHAMADO =  $val;
	}

	function setNUM_MATRICULA($val){
		$this->NUM_MATRICULA =  $val;
	}

	function setDTH_ACESSO($val){
		$this->DTH_ACESSO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_HISTORICO_ACESSO_CHAMADO , SEQ_CHAMADO , NUM_MATRICULA , to_char(DTH_ACESSO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ACESSO, DTH_ACESSO as DTH_ACESSO_DATA
			    FROM gestaoti.historico_acesso_chamado
				WHERE SEQ_HISTORICO_ACESSO_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_HISTORICO_ACESSO_CHAMADO = $row->seq_historico_acesso_chamado;
		$this->SEQ_CHAMADO = $row->seq_chamado;
		$this->NUM_MATRICULA = $row->num_matricula;
		$this->DTH_ACESSO = $row->dth_acesso;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_HISTORICO_ACESSO_CHAMADO, SEQ_CHAMADO, NUM_MATRICULA as NUM_MATRICULA,
						  	 to_char(DTH_ACESSO,'dd/mm/yyyy hh24:mi:ss') as DTH_ACESSO, b.NOM_COLABORADOR ";
		$sqlCorpo  = "FROM gestaoti.historico_acesso_chamado a, gestaoti.viw_colaborador b
					  WHERE a.NUM_MATRICULA = b.NUM_MATRICULA_COLABORADOR ";

		if($this->SEQ_HISTORICO_ACESSO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_HISTORICO_ACESSO_CHAMADO = $this->SEQ_HISTORICO_ACESSO_CHAMADO ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
		}
		if($this->DTH_ACESSO != "" && $this->DTH_ACESSO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ACESSO >= to_date('".$this->DTH_ACESSO." 01:00:00', 'dd/mm/yyyy hh24:mi.ss') ";
		}
		if($this->DTH_ACESSO != "" && $this->DTH_ACESSO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ACESSO between to_date('".$this->DTH_ACESSO." 01:00:00', 'dd/mm/yyyy hh24:mi.ss') and to_date('".$this->DTH_ACESSO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi.ss') ";
		}
		if($this->DTH_ACESSO == "" && $this->DTH_ACESSO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ACESSO <= to_date('".$this->DTH_ACESSO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi.ss') ";
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
		$sql = "DELETE FROM gestaoti.historico_acesso_chamado WHERE SEQ_HISTORICO_ACESSO_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_HISTORICO_ACESSO_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_HISTORICO_ACESSO_CHAMADO");

		$sql = "INSERT INTO gestaoti.historico_acesso_chamado(SEQ_HISTORICO_ACESSO_CHAMADO,
										  SEQ_CHAMADO,
										  NUM_MATRICULA,
										  DTH_ACESSO
									)
							 VALUES (".$this->iif($this->SEQ_HISTORICO_ACESSO_CHAMADO=="", "NULL", "'".$this->SEQ_HISTORICO_ACESSO_CHAMADO."'").",
									 ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
									 ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
									 '".date("Y-m-d H:i:s")."'
							 		) ";
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