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
* CLASSNAME:  area_atuacao
* FOR TABLE:  area_atuacao
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class area_atuacao{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_AREA_ATUACAO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NOM_AREA_ATUACAO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function area_atuacao(){
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
	function getSEQ_AREA_ATUACAO(){
		return $this->SEQ_AREA_ATUACAO;
	}

	function getNOM_AREA_ATUACAO(){
		return $this->NOM_AREA_ATUACAO;
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
	function setSEQ_AREA_ATUACAO($val){
		$this->SEQ_AREA_ATUACAO =  $val;
	}

	function setNOM_AREA_ATUACAO($val){
		$this->NOM_AREA_ATUACAO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.area_atuacao WHERE SEQ_AREA_ATUACAO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_AREA_ATUACAO = $row->seq_area_atuacao;
		$this->NOM_AREA_ATUACAO = $row->nom_area_atuacao;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " select * ";
		$sqlCorpo  = " FROM gestaoti.(
						SELECT PAGING.*, ROWNUM PAGING_RN
      					FROM gestaoti.(
								SELECT SEQ_AREA_ATUACAO , NOM_AREA_ATUACAO ";
		$sqlCorpo  = "FROM gestaoti.area_atuacao
						WHERE 1=1 ";

		if($this->SEQ_AREA_ATUACAO != ""){
			$sqlCorpo .= "  and SEQ_AREA_ATUACAO = $this->SEQ_AREA_ATUACAO ";
		}
		if($this->NOM_AREA_ATUACAO != ""){
			$sqlCorpo .= "  and upper(NOM_AREA_ATUACAO) like '%".strtoupper($this->NOM_AREA_ATUACAO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.area_atuacao WHERE SEQ_AREA_ATUACAO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_AREA_ATUACAO = $this->database->GetSequenceValue("gestaoti.SEQ_AREA_ATUACAO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.area_atuacao ( SEQ_AREA_ATUACAO,
										   NOM_AREA_ATUACAO )
				VALUES (".$this->SEQ_AREA_ATUACAO.",
						".$this->database->iif($this->NOM_AREA_ATUACAO=="", "NULL", "'".$this->NOM_AREA_ATUACAO."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.area_atuacao
				 SET  NOM_AREA_ATUACAO = ".$this->database->iif($this->NOM_AREA_ATUACAO=="", "NULL", "'".$this->NOM_AREA_ATUACAO."'")."
				 WHERE SEQ_AREA_ATUACAO = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>