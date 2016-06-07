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
* CLASSNAME:        perfil_acesso
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// CLASS DECLARATION
// **********************
class perfil_acesso{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_PERFIL_ACESSO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NOM_PERFIL_ACESSO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	var $SEQ_PERFIL_ACESSO_ADMINISTRADOR;
	var $SEQ_PERFIL_ACESSO_COLABORADOR;
	var $SEQ_PERFIL_ACESSO_GESTOR_TI;
	var $SEQ_PERFIL_ACESSO_COORDENADOR_TI;
	var $SEQ_PERFIL_ACESSO_GERENTE_DE_MUDANCAS;
	var $SEQ_PERFIL_ACESSO_EXECUTOR_DE_MUDANCAS;
	var $SEQ_PERFIL_ACESSO_REQUISITANTE_DE_MUDANCAS;
	var $SEQ_PERFIL_ACESSO_REMOVER;

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function perfil_acesso(){
		$this->database = new Database();
		$this->SEQ_PERFIL_ACESSO_ADMINISTRADOR = 2;
		$this->SEQ_PERFIL_ACESSO_COLABORADOR = 1;
		$this->SEQ_PERFIL_ACESSO_GESTOR_TI = 4;
		$this->SEQ_PERFIL_ACESSO_COORDENADOR_TI = 5;
		$this->SEQ_PERFIL_ACESSO_GERENTE_DE_MUDANCAS = 6;
		$this->SEQ_PERFIL_ACESSO_EXECUTOR_DE_MUDANCAS = 7;
		$this->SEQ_PERFIL_ACESSO_REQUISITANTE_DE_MUDANCAS = 8;
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
	function getSEQ_PERFIL_ACESSO(){
		return $this->SEQ_PERFIL_ACESSO;
	}

	function getNOM_PERFIL_ACESSO(){
		return $this->NOM_PERFIL_ACESSO;
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
	function setSEQ_PERFIL_ACESSO($val){
		$this->SEQ_PERFIL_ACESSO =  $val;
	}

	function setNOM_PERFIL_ACESSO($val){
		$this->NOM_PERFIL_ACESSO =  $val;
	}
	function setSEQ_PERFIL_ACESSO_REMOVER($val){
		$this->SEQ_PERFIL_ACESSO_REMOVER = $val;
	}
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.perfil_acesso WHERE SEQ_PERFIL_ACESSO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_PERFIL_ACESSO = $row->seq_perfil_acesso;
		$this->NOM_PERFIL_ACESSO = $row->nom_perfil_acesso;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_PERFIL_ACESSO , NOM_PERFIL_ACESSO ";
		$sqlCorpo  = "FROM  gestaoti.perfil_acesso
						WHERE 1=1 ";

		if($this->SEQ_PERFIL_ACESSO != ""){
			$sqlCorpo .= "  and SEQ_PERFIL_ACESSO = $this->SEQ_PERFIL_ACESSO ";
		}
		if($this->NOM_PERFIL_ACESSO != ""){
			$sqlCorpo .= "  and upper(NOM_PERFIL_ACESSO) like '%".strtoupper($this->NOM_PERFIL_ACESSO)."%'  ";
		}
		if($this->SEQ_PERFIL_ACESSO_REMOVER != ""){
			$sqlCorpo .= "  and SEQ_PERFIL_ACESSO not in ($this->SEQ_PERFIL_ACESSO_REMOVER) ";
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
		$sql = "DELETE FROM gestaoti.perfil_acesso WHERE SEQ_PERFIL_ACESSO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_PERFIL_ACESSO = $this->database->GetSequenceValue("gestaoti.SEQ_PERFIL_ACESSO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.perfil_acesso (SEQ_PERFIL_ACESSO, NOM_PERFIL_ACESSO )
				VALUES (".$this->SEQ_PERFIL_ACESSO.",
						".$this->database->iif($this->NOM_PERFIL_ACESSO=="", "NULL", "'".$this->NOM_PERFIL_ACESSO."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.perfil_acesso
				 SET  NOM_PERFIL_ACESSO = ".$this->database->iif($this->NOM_PERFIL_ACESSO=="", "NULL", "'".$this->NOM_PERFIL_ACESSO."'")."
				 WHERE SEQ_PERFIL_ACESSO = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $vSelected == $row[0]?"Selected":"", $row[1]);
			$cont++;
		}
		return $aItemOption;
	}
} // class : end
?>