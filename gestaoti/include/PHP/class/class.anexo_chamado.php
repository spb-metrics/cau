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
* Nome da Classe:	anexo_chamado
* Nome da tabela:	anexo_chamado
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
class anexo_chamado{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_ANEXO_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_CHAMADO;   // (normal Attribute)
	var $NOM_ARQUIVO_SISTEMA;   // (normal Attribute)
	var $NOM_ARQUIVO_ORIGINAL;   // (normal Attribute)
	var $DTH_ANEXO;   // (normal Attribute)
	var $NUM_MATRICULA;   // (normal Attribute)
	var $EXTENCAO_ARQUIVO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function anexo_chamado(){
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

	function getSEQ_ANEXO_CHAMADO(){
		return $this->SEQ_ANEXO_CHAMADO;
	}

	function getSEQ_CHAMADO(){
		return $this->SEQ_CHAMADO;
	}

	function getNOM_ARQUIVO_SISTEMA(){
		return $this->NOM_ARQUIVO_SISTEMA;
	}

	function getNOM_ARQUIVO_ORIGINAL(){
		return $this->NOM_ARQUIVO_ORIGINAL;
	}

	function getDTH_ANEXO(){
		return $this->DTH_ANEXO;
	}

	function getNUM_MATRICULA(){
		return $this->NUM_MATRICULA;
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

	function setSEQ_ANEXO_CHAMADO($val){
		$this->SEQ_ANEXO_CHAMADO =  $val;
	}

	function setSEQ_CHAMADO($val){
		$this->SEQ_CHAMADO =  $val;
	}

	function setNOM_ARQUIVO_SISTEMA($val){
		$this->NOM_ARQUIVO_SISTEMA =  $val;
	}

	function setNOM_ARQUIVO_ORIGINAL($val){
		$this->NOM_ARQUIVO_ORIGINAL =  $val;
	}

	function setDTH_ANEXO($val){
		$this->DTH_ANEXO =  $val;
	}

	function setNUM_MATRICULA($val){
		$this->NUM_MATRICULA =  $val;
	}

	function setEXTENCAO_ARQUIVO($val){
		$this->EXTENCAO_ARQUIVO =  $val;
	}
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_ANEXO_CHAMADO , SEQ_CHAMADO , NOM_ARQUIVO_SISTEMA , NOM_ARQUIVO_ORIGINAL , to_char(DTH_ANEXO, 'dd/mm/yyyy') as DTH_ANEXO, DTH_ANEXO as DTH_ANEXO_DATA , NUM_MATRICULA
			    FROM gestaoti.anexo_chamado
				WHERE SEQ_ANEXO_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_ANEXO_CHAMADO = $row->seq_anexo_chamado;
		$this->SEQ_CHAMADO = $row->seq_chamado;
		$this->NOM_ARQUIVO_SISTEMA = $row->nom_arquivo_sistema;
		$this->NOM_ARQUIVO_ORIGINAL = $row->nom_arquivo_original;
		$this->DTH_ANEXO = $row->dth_anexo;
		$this->NUM_MATRICULA = $row->num_matricula;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_ANEXO_CHAMADO , SEQ_CHAMADO , NOM_ARQUIVO_SISTEMA , NOM_ARQUIVO_ORIGINAL ,
							 to_char(DTH_ANEXO, 'dd/mm/yyyy hh24:mi.ss') as DTH_ANEXO, DTH_ANEXO as DTH_ANEXO_DATA, NUM_MATRICULA,
							 b.NOM_COLABORADOR ";
		$sqlCorpo  = "FROM gestaoti.anexo_chamado a, gestaoti.viw_colaborador b
					  WHERE a.NUM_MATRICULA = b.NUM_MATRICULA_COLABORADOR ";

		if($this->SEQ_ANEXO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_ANEXO_CHAMADO = $this->SEQ_ANEXO_CHAMADO ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NOM_ARQUIVO_SISTEMA != ""){
			$sqlCorpo .= "  and upper(NOM_ARQUIVO_SISTEMA) like '%".strtoupper($this->NOM_ARQUIVO_SISTEMA)."%'  ";
		}
		if($this->NOM_ARQUIVO_ORIGINAL != ""){
			$sqlCorpo .= "  and upper(NOM_ARQUIVO_ORIGINAL) like '%".strtoupper($this->NOM_ARQUIVO_ORIGINAL)."%'  ";
		}
		if($this->DTH_ANEXO != "" && $this->DTH_ANEXO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ANEXO >= to_date('".$this->DTH_ANEXO."', 'dd/mm/yyyy') ";
		}
		if($this->DTH_ANEXO != "" && $this->DTH_ANEXO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ANEXO between to_date('".$this->DTH_ANEXO."', 'dd/mm/yyyy') and to_date('".$this->DTH_ANEXO_FINAL."', 'dd/mm/yyyy') ";
		}
		if($this->DTH_ANEXO == "" && $this->DTH_ANEXO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ANEXO <= to_date('".$this->DTH_ANEXO_FINAL."', 'dd/mm/yyyy') ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
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
		$sql = "DELETE FROM gestaoti.anexo_chamado WHERE SEQ_ANEXO_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_ANEXO_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_ANEXO_CHAMADO");
		$this->NOM_ARQUIVO_SISTEMA = $this->iif($this->EXTENCAO_ARQUIVO=="", "", $this->SEQ_ANEXO_CHAMADO.".".$this->EXTENCAO_ARQUIVO);

		$sql = "INSERT INTO gestaoti.anexo_chamado(SEQ_ANEXO_CHAMADO,
										  SEQ_CHAMADO,
										  NOM_ARQUIVO_SISTEMA,
										  NOM_ARQUIVO_ORIGINAL,
										  DTH_ANEXO,
										  NUM_MATRICULA
									)
							 VALUES (".$this->iif($this->SEQ_ANEXO_CHAMADO=="", "NULL", "'".$this->SEQ_ANEXO_CHAMADO."'").",
									 ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
									 ".$this->iif($this->NOM_ARQUIVO_SISTEMA=="", "NULL", "'".$this->NOM_ARQUIVO_SISTEMA."'").",
									 ".$this->iif($this->NOM_ARQUIVO_ORIGINAL=="", "NULL", "'".$this->NOM_ARQUIVO_ORIGINAL."'").",
									 current_date,
									 ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.anexo_chamado
				 SET SEQ_CHAMADO = ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
					 NOM_ARQUIVO_SISTEMA = ".$this->iif($this->NOM_ARQUIVO_SISTEMA=="", "NULL", "'".$this->NOM_ARQUIVO_SISTEMA."'").",
					 NOM_ARQUIVO_ORIGINAL = ".$this->iif($this->NOM_ARQUIVO_ORIGINAL=="", "NULL", "'".$this->NOM_ARQUIVO_ORIGINAL."'").",
					 DTH_ANEXO = ".$this->iif($this->DTH_ANEXO=="", "NULL", "to_date('".$this->DTH_ANEXO."', 'dd/mm/yyyy')").",
					 NUM_MATRICULA = ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'")."
				WHERE SEQ_ANEXO_CHAMADO = $id ";
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