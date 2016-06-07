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
* CLASSNAME:        menu_perfil_acesso
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
class responsavel_unidade_organizacional{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_UNIDADE_ORGANIZACIONAL;   // (normal Attribute) num_matricula_recurso
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_PESSOA;   // (normal Attribute) seq_perfil_acesso

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function responsavel_unidade_organizacional(){
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
	function getSEQ_UNIDADE_ORGANIZACIONAL(){
		return $this->SEQ_UNIDADE_ORGANIZACIONAL;
	}

	function getSEQ_PESSOA(){
		return $this->SEQ_PESSOA;
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
	function setSEQ_UNIDADE_ORGANIZACIONAL($val){
		$this->SEQ_UNIDADE_ORGANIZACIONAL =  $val;
	}

	function setSEQ_PESSOA($val){
		$this->SEQ_PESSOA =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	 

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		//$this->setvQtdRegistros($vQtdRegistros);
		$sqlSelect = "  select b.SEQ_UNIDADE_ORGANIZACIONAL, c.SEQ_PESSOA, c.NOME, b.NOM_UNIDADE_ORGANIZACIONAL ";
		$sqlCorpo  = "  from gestaoti.responsavel_unidade_organizacional a,
                                     gestaoti.unidade_organizacional b,
                                     gestaoti.pessoa c
                                where a.seq_unidade_organizacional = b.seq_unidade_organizacional
                                and   a.seq_pessoa = c.seq_pessoa ";

		if($this->SEQ_UNIDADE_ORGANIZACIONAL != ""){
			$sqlCorpo .= "  and a.SEQ_UNIDADE_ORGANIZACIONAL = $this->SEQ_UNIDADE_ORGANIZACIONAL ";
		}
		if($this->SEQ_PESSOA != ""){
			$sqlCorpo .= "  and a.SEQ_PESSOA = $this->SEQ_PESSOA ";
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
	function deleteBySEQ_UNIDADE_ORGANIZACIONAL($id){
		$sql = "DELETE FROM gestaoti.responsavel_unidade_organizacional WHERE SEQ_UNIDADE_ORGANIZACIONAL = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.responsavel_unidade_organizacional ( SEQ_UNIDADE_ORGANIZACIONAL, SEQ_PESSOA )
			VALUES (".$this->database->iif($this->SEQ_UNIDADE_ORGANIZACIONAL=="", "NULL", "'".$this->SEQ_UNIDADE_ORGANIZACIONAL."'").", 
                                ".$this->database->iif($this->SEQ_PESSOA=="", "NULL", "'".$this->SEQ_PESSOA."'")." )";
		$result = $this->database->query($sql);
	}

 
} // class : end
?>