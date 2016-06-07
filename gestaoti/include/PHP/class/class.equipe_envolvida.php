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
*//*
* -------------------------------------------------------
* CLASSNAME:        equipe_envolvida
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class equipe_envolvida{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************	var $SEQ_ITEM_CONFIGURACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NUM_MATRICULA_RECURSO;   // (normal Attribute)
	var $QTD_HORA_ALOCADA;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function equipe_envolvida(){
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
	function getNUM_MATRICULA_RECURSO(){
		return $this->NUM_MATRICULA_RECURSO;
	}

	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	}

	function getQTD_HORA_ALOCADA(){
		return $this->QTD_HORA_ALOCADA;
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
	function setNUM_MATRICULA_RECURSO($val){
		$this->NUM_MATRICULA_RECURSO =  $val;
	}

	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}

	function setQTD_HORA_ALOCADA($val){
		$this->QTD_HORA_ALOCADA =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.equipe_envolvida WHERE SEQ_ITEM_CONFIGURACAO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->NUM_MATRICULA_RECURSO = $row->num_matricula_recurso;
		$this->SEQ_ITEM_CONFIGURACAO = $row->seq_item_configuracao;
		$this->QTD_HORA_ALOCADA = $row->qtd_hora_alocada;
	}

	function IsAlocado($v_NUM_MATRICULA_RECURSO, $v_SEQ_ITEM_CONFIGURACAO){
		$sql =  "SELECT QTD_HORA_ALOCADA
				 FROM gestaoti.equipe_envolvida
				 WHERE SEQ_ITEM_CONFIGURACAO = $v_SEQ_ITEM_CONFIGURACAO
				 and NUM_MATRICULA_RECURSO = $v_NUM_MATRICULA_RECURSO";
		$this->database->query($sql);
		$rowCount = pg_fetch_array($this->database->result);
		if($rowCount[0]){
			$this->QTD_HORA_ALOCADA = $rowCount[0];
			return true;
		}else{
			$this->QTD_HORA_ALOCADA = "";
			return false;
		}
	}

	function HorasAlocadas($vTipo, $v_UOR_SIGLA){
		$sql = "SELECT sum( a.QTD_HORA_ALOCADA )
				FROM gestaoti.equipe_envolvida a, item_configuracao_software b, item_configuracao c,
					(select decode(I_T, 'I',MATRICULA, substr(CPF,0,9)) as MATRICULA,
                            LOTACAO
                            FROM gestaoti.VIW_AGE_EMPREGADOS) d
				WHERE a.SEQ_ITEM_CONFIGURACAO = b.SEQ_ITEM_CONFIGURACAO
				AND b.SEQ_ITEM_CONFIGURACAO = c.SEQ_ITEM_CONFIGURACAO
				AND c.NUM_MATRICULA_LIDER = d.MATRICULA
				AND d.LOTACAO LIKE '$v_UOR_SIGLA%' ";
		if($vTipo == "D"){ // Se for desenvolvimento
			$sql .= " AND b.SEQ_STATUS_SOFTWARE NOT IN (8, 9 )
					  AND b.SEQ_TIPO_SOFTWARE = 4 ";
		}else{
			$sql .= " AND b.SEQ_STATUS_SOFTWARE in (8) ";
		}
		$result =  $this->database->query($sql);
		if($this->database->rows > 0){

			$row = pg_fetch_array($this->database->result);
			return $row[0];
		}else{
			return 0;
		}
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);


		$sqlSelect = "SELECT NUM_MATRICULA_RECURSO , SEQ_ITEM_CONFIGURACAO , QTD_HORA_ALOCADA ";
		$sqlCorpo  = "FROM gestaoti.equipe_envolvida
						WHERE 1=1 ";

		if($this->NUM_MATRICULA_RECURSO != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_RECURSO = $this->NUM_MATRICULA_RECURSO ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->QTD_HORA_ALOCADA != ""){
			$sqlCorpo .= "  and QTD_HORA_ALOCADA = $this->QTD_HORA_ALOCADA ";
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
	function delete($v_SEQ_ITEM_CONFIGURACAO, $v_NUM_MATRICULA_RECURSO){
		$sql = "DELETE FROM gestaoti.equipe_envolvida
				WHERE SEQ_ITEM_CONFIGURACAO = $v_SEQ_ITEM_CONFIGURACAO
				and NUM_MATRICULA_RECURSO = $v_NUM_MATRICULA_RECURSO";
		$result = $this->database->query($sql);
	}

	function delete1($v_SEQ_ITEM_CONFIGURACAO){
		$sql = "DELETE FROM gestaoti.equipe_envolvida WHERE SEQ_ITEM_CONFIGURACAO = $v_SEQ_ITEM_CONFIGURACAO";
		$result = $this->database->query($sql);
	}

	function deleteAlocacao($v_NUM_MATRICULA_RECURSO){
		$sql = "DELETE FROM gestaoti.equipe_envolvida WHERE NUM_MATRICULA_RECURSO = $v_NUM_MATRICULA_RECURSO";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.equipe_envolvida (SEQ_ITEM_CONFIGURACAO, NUM_MATRICULA_RECURSO, QTD_HORA_ALOCADA )
				VALUES ( ".$this->database->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
						 ".$this->database->iif($this->NUM_MATRICULA_RECURSO=="", "NULL", "'".$this->NUM_MATRICULA_RECURSO."'").",
						 ".$this->database->iif($this->QTD_HORA_ALOCADA=="", "NULL", "'".$this->QTD_HORA_ALOCADA."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($v_NUM_MATRICULA_RECURSO, $v_SEQ_ITEM_CONFIGURACAO){
		$sql = " UPDATE gestaoti.equipe_envolvida
				 SET  QTD_HORA_ALOCADA = ".$this->database->iif($this->QTD_HORA_ALOCADA=="", "NULL", "'".$this->QTD_HORA_ALOCADA."'")."
				 where NUM_MATRICULA_RECURSO = $v_NUM_MATRICULA_RECURSO and
				 	   SEQ_ITEM_CONFIGURACAO = $v_SEQ_ITEM_CONFIGURACAO ";
		$result = $this->database->query($sql);
	}

} // class : end
?>