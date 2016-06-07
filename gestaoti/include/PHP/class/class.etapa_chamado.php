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
* CLASSNAME:        FASE_ITEM_CONFIGURACAO
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
}

// **********************
// CLASS DECLARATION
// **********************
class etapa_chamado{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_ETAPA_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_CHAMADO;   // (normal Attribute)
	var $NOM_ETAPA_CHAMADO;   // (normal Attribute)
	var $DTH_INICIO_PREVISTO;   // (normal Attribute)
	var $DTH_INICIO_EFETIVO;    // (normal Attribute)
	var $DTH_FIM_PREVISTO;   // (normal Attribute)
	var $DTH_FIM_EFETIVO;    // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function etapa_chamado(){
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
	function getSEQ_ETAPA_CHAMADO(){
		return $this->SEQ_ETAPA_CHAMADO;
	}

	function getSEQ_CHAMADO(){
		return $this->SEQ_CHAMADO;
	}

	function getNOM_ETAPA_CHAMADO(){
		return $this->NOM_ETAPA_CHAMADO;
	}

	function getDTH_INICIO_PREVISTO(){
		return $this->DTH_INICIO_PREVISTO;
	}

	function getDTH_FIM_PREVISTO(){
		return $this->DTH_FIM_PREVISTO;
	}

	function getDTH_INICIO_EFETIVO(){
		return $this->DTH_INICIO_EFETIVO;
	}

	function getDTH_FIM_EFETIVO(){
		return $this->DTH_FIM_EFETIVO;
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
	function setSEQ_ETAPA_CHAMADO($val){
		$this->SEQ_ETAPA_CHAMADO =  $val;
	}

	function setSEQ_CHAMADO($val){
		$this->SEQ_CHAMADO =  $val;
	}

	function setDTH_INICIO_PREVISTO($val){
		$this->DTH_INICIO_PREVISTO =  $val;
	}

	function setDTH_INICIO_EFETIVO($val){
		$this->DTH_INICIO_EFETIVO =  $val;
	}

	function setNOM_ETAPA_CHAMADO($val){
		$this->NOM_ETAPA_CHAMADO =  $val;
	}

	function setDTH_FIM_PREVISTO($val){
		$this->DTH_FIM_PREVISTO =  $val;
	}

	function setDTH_FIM_EFETIVO($val){
		$this->DTH_FIM_EFETIVO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT SEQ_ETAPA_CHAMADO, NOM_ETAPA_CHAMADO, SEQ_CHAMADO,
						 to_char(DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO,
						 to_char(DTH_INICIO_PREVISTO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_PREVISTO,
						 to_char(DTH_FIM_PREVISTO, 'dd/mm/yyyy hh24:mi:ss') as DTH_FIM_PREVISTO,
						 to_char(DTH_FIM_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_FIM_EFETIVO
				FROM gestaoti.ETAPA_CHAMADO WHERE SEQ_ETAPA_CHAMADO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_ETAPA_CHAMADO = $row->seq_etapa_chamado;
		$this->SEQ_CHAMADO = $row->seq_chamado;
		$this->NOM_ETAPA_CHAMADO = $row->nom_etapa_chamado;
		$this->DTH_INICIO_PREVISTO = $row->dth_inicio_previsto;
		$this->DTH_INICIO_EFETIVO = $row->dth_inicio_efetivo;
		$this->DTH_FIM_PREVISTO = $row->dth_fim_previsto;
		$this->DTH_FIM_EFETIVO = $row->dth_fim_efetivo;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);


		$sqlSelect = "SELECT SEQ_ETAPA_CHAMADO, NOM_ETAPA_CHAMADO, SEQ_CHAMADO,
							 to_char(DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO,
							 to_char(DTH_INICIO_PREVISTO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_PREVISTO,
							 to_char(DTH_FIM_PREVISTO, 'dd/mm/yyyy hh24:mi:ss') as DTH_FIM_PREVISTO,
							 to_char(DTH_FIM_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_FIM_EFETIVO
					  ";
		$sqlCorpo  = "FROM gestaoti.etapa_chamado
						WHERE 1=1 ";

		if($this->SEQ_ETAPA_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_ETAPA_CHAMADO = $this->SEQ_ETAPA_CHAMADO ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NOM_ETAPA_CHAMADO != ""){
			$sqlCorpo .= "  and upper(NOM_ETAPA_CHAMADO) like '%".strtoupper($this->NOM_ETAPA_CHAMADO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.etapa_chamado WHERE SEQ_ETAPA_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_ETAPA_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_ETAPA_CHAMADO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.ETAPA_CHAMADO (
						SEQ_ETAPA_CHAMADO,
						SEQ_CHAMADO,
						NOM_ETAPA_CHAMADO,
						DTH_INICIO_PREVISTO,
						DTH_FIM_PREVISTO
						)
				VALUES (".$this->SEQ_ETAPA_CHAMADO.",
						".$this->database->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
						".$this->database->iif($this->NOM_ETAPA_CHAMADO=="", "NULL", "'".$this->NOM_ETAPA_CHAMADO."'").",
						".$this->database->iif($this->DTH_INICIO_PREVISTO=="", "NULL", "'".$this->DTH_INICIO_PREVISTO."'").",
						".$this->database->iif($this->DTH_FIM_PREVISTO=="", "NULL", "'".$this->DTH_FIM_PREVISTO."'")."
						)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.ETAPA_CHAMADO SET
					NOM_ETAPA_CHAMADO = ".$this->database->iif($this->NOM_ETAPA_CHAMADO=="", "NULL", "'".$this->NOM_ETAPA_CHAMADO."'").",
					DTH_INICIO_PREVISTO = ".$this->database->iif($this->DTH_INICIO_PREVISTO=="", "NULL", "'".$this->DTH_INICIO_PREVISTO."'").",
					DTH_FIM_PREVISTO = ".$this->database->iif($this->DTH_FIM_PREVISTO=="", "NULL", "'".$this->DTH_FIM_PREVISTO."'")."
				WHERE SEQ_ETAPA_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}

	function SetInicioEfetivo($id){
		$sql = " UPDATE gestaoti.ETAPA_CHAMADO
				 SET DTH_INICIO_EFETIVO = '".date("Y-m-d H:i:s")."'
				WHERE SEQ_ETAPA_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}

	function SetFimEfetivo($id){
		$sql = " UPDATE gestaoti.ETAPA_CHAMADO
				 SET DTH_FIM_EFETIVO = '".date("Y-m-d H:i:s")."'
				WHERE SEQ_ETAPA_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}

} // class : end
?>