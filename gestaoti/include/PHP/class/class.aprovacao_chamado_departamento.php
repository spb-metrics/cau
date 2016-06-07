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
* Nome da Classe:	aprovacao_chamado
* Nome da tabela:	aprovacao_chamado
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
class aprovacao_chamado_departamento{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_APROVACAO_CHAMADO_DEPARTAMENTO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_CHAMADO;   // (normal Attribute)
	var $ID_UNIDADE;   // (normal Attribute)
	var $ID_COORDENACAO;   // (normal Attribute)
	 
	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function aprovacao_chamado_departamento(){
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

	function getSEQ_APROVACAO_CHAMADO_DEPARTAMENTO(){
		return $this->SEQ_APROVACAO_CHAMADO_DEPARTAMENTO;
	}

	function getSEQ_CHAMADO(){
		return $this->SEQ_CHAMADO;
	}

	function getID_UNIDADE(){
		return $this->ID_UNIDADE;
	}

	function getID_COORDENACAO(){
		return $this->ID_COORDENACAO;
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

	function setSEQ_APROVACAO_CHAMADO_SUPERIOR($val){
		$this->SEQ_APROVACAO_CHAMADO_SUPERIOR =  $val;
	}

	function setSEQ_CHAMADO($val){
		$this->SEQ_CHAMADO =  $val;
	}

	function setID_UNIDADE($val){
		$this->ID_UNIDADE =  $val;
	}
	
	function setID_COORDENACAO($val){
		$this->ID_COORDENACAO =  $val;
	}

	 

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_APROVACAO_CHAMADO_DEPARTAMENTO,SEQ_CHAMADO,ID_UNIDADE,ID_COORDENACAO
			    FROM gestaoti.aprovacao_chamado_departamento
				WHERE SEQ_APROVACAO_CHAMADO_DEPARTAMENTO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0); 
		$this->SEQ_APROVACAO_CHAMADO_DEPARTAMENTO = $row->seq_aprovacao_chamado_departamento;
		$this->SEQ_CHAMADO = $row->seq_chamado; 
		$this->ID_UNIDADE = $row->id_unidade; 
		$this->ID_COORDENACAO = $row->id_coordenacao; 
	}
	
	function selectByIdChamado($id){
		$sql = "SELECT SEQ_APROVACAO_CHAMADO_DEPARTAMENTO,SEQ_CHAMADO,ID_UNIDADE,ID_COORDENACAO
			    FROM gestaoti.aprovacao_chamado_departamento
				WHERE SEQ_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_APROVACAO_CHAMADO_DEPARTAMENTO = $row->seq_aprovacao_chamado_departamento;
		$this->SEQ_CHAMADO = $row->seq_chamado; 
		$this->ID_UNIDADE = $row->id_unidade; 
		$this->ID_COORDENACAO = $row->id_coordenacao; 
	}
	
	function selectByIdUnidade($id){
		$sql = "SELECT SEQ_APROVACAO_CHAMADO_DEPARTAMENTO,SEQ_CHAMADO,ID_UNIDADE,ID_COORDENACAO
			    FROM gestaoti.aprovacao_chamado_departamento
				WHERE ID_UNIDADE = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_APROVACAO_CHAMADO_DEPARTAMENTO = $row->seq_aprovacao_chamado_departamento;
		$this->SEQ_CHAMADO = $row->seq_chamado; 
		$this->ID_UNIDADE = $row->id_unidade; 
		$this->ID_COORDENACAO = $row->id_coordenacao; 
	}
	
	function selectByIdCoordenacao($id){
		$sql = "SELECT SEQ_APROVACAO_CHAMADO_DEPARTAMENTO,SEQ_CHAMADO,ID_UNIDADE,ID_COORDENACAO
			    FROM gestaoti.aprovacao_chamado_departamento
				WHERE ID_COORDENACAO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_APROVACAO_CHAMADO_DEPARTAMENTO = $row->seq_aprovacao_chamado_departamento;
		$this->SEQ_CHAMADO = $row->seq_chamado; 
		$this->ID_UNIDADE = $row->id_unidade; 
		$this->ID_COORDENACAO = $row->id_coordenacao; 
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function GetUltimoAprovacao($id){
		$sql = "SELECT SEQ_APROVACAO_CHAMADO_DEPARTAMENTO,SEQ_CHAMADO,ID_UNIDADE,ID_COORDENACAO
				FROM gestaoti.aprovacao_chamado_departamento a
				where  SEQ_CHAMADO = $id
				and SEQ_APROVACAO_CHAMADO_SUPERIOR = (select max(SEQ_APROVACAO_CHAMADO_DEPARTAMENTO)
				                             FROM gestaoti.aprovacao_chamado_departamento b
				                             where b.SEQ_CHAMADO = a.SEQ_CHAMADO)";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		if($this->database->rows > 0){
			$row = pg_fetch_object($result, 0);
			$this->SEQ_APROVACAO_CHAMADO_DEPARTAMENTO = $row->seq_aprovacao_chamado_departamento;
			$this->SEQ_CHAMADO = $row->seq_chamado; 
			$this->ID_UNIDADE = $row->id_unidade; 
			$this->ID_COORDENACAO = $row->id_coordenacao;
		}
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_APROVACAO_CHAMADO_DEPARTAMENTO,SEQ_CHAMADO,ID_UNIDADE,ID_COORDENACAO ";
		$sqlCorpo  = "FROM gestaoti.aprovacao_chamado_departamento a 
					  WHERE 1 = 1 ";

		if($this->SEQ_APROVACAO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_APROVACAO_CHAMADO_DEPARTAMENTO = $this->SEQ_APROVACAO_CHAMADO_DEPARTAMENTO ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->ID_UNIDADE != ""){
			$sqlCorpo .= "  and ID_UNIDADE = $this->ID_UNIDADE ";
		}
		if($this->ID_COORDENACAO != ""){
			$sqlCorpo .= "  and ID_COORDENACAO = $this->ID_COORDENACAO ";
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
		$sql = "DELETE FROM gestaoti.aprovacao_chamado_departamento WHERE SEQ_APROVACAO_CHAMADO_DEPARTAMENTO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_APROVACAO_CHAMADO_DEPARTAMENTO = $this->database->GetSequenceValue("gestaoti.SEQ_APROVACAO_CHAMADO_DEPARTAMENTO");

		$sql = "INSERT INTO gestaoti.aprovacao_chamado_departamento(SEQ_APROVACAO_CHAMADO_DEPARTAMENTO,
										  SEQ_CHAMADO,
										  ID_UNIDADE ,
										  ID_COORDENACAO
									)
							 VALUES (".$this->iif($this->SEQ_APROVACAO_CHAMADO_DEPARTAMENTO=="", "NULL", "'".$this->SEQ_APROVACAO_CHAMADO_DEPARTAMENTO."'").",
									 ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
									 ".$this->iif($this->ID_UNIDADE=="", "NULL", "'".$this->ID_UNIDADE."'")." ,
									 ".$this->iif($this->ID_COORDENACAO=="", "NULL", "'".$this->ID_COORDENACAO."'")." 
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.aprovacao_chamado_departamento
				 SET SEQ_CHAMADO = ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
					 ID_UNIDADE = ".$this->iif($this->ID_UNIDADE=="", "NULL", "'".$this->ID_UNIDADE."'")." ,
					 ID_COORDENACAO = ".$this->iif($this->ID_COORDENACAO=="", "NULL", "'".$this->ID_COORDENACAO."'")." 
				WHERE SEQ_APROVACAO_CHAMADO_DEPARTAMENTO = $id ";
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