<?php
/*
Copyright 2011 da EMBRATUR
�Este arquivo � parte do programa CAU - Central de Atendimento ao Usu�rio
�O CAU � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela 
 Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
�Este programa � distribu�do na esperan�a que possa ser� �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer� 
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
�Observe no diret�rio gestaoti/install/ a c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licensa_uso.htm". 
 Se preferir acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software 
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA� 02110-1301, USA
*/
/*
* -------------------------------------------------------
* Nome da Classe:	patrimonio_chamado
* Nome da tabela:	patrimonio_chamado
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
}

// **********************
// DECLARA��O DA CLASSE
// **********************
class patrimonio_chamado{
	// class : begin

	// ***********************
	// DECLARA��O DE ATRIBUTOS
	// ***********************
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $SEQ_CHAMADO;   // (normal Attribute)
	var $NUM_PATRIMONIO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function patrimonio_chamado(){
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

	function getSEQ_CHAMADO(){
		return $this->SEQ_CHAMADO;
	}

	function getNUM_PATRIMONIO(){
		return $this->NUM_PATRIMONIO;
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

	function setSEQ_CHAMADO($val){
		$this->SEQ_CHAMADO =  $val;
	}

	function setNUM_PATRIMONIO($val){
		$this->NUM_PATRIMONIO =  $val;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_CHAMADO , NUM_PATRIMONIO ";
		$sqlCorpo  = "FROM gestaoti.patrimonio_chamado
					  WHERE 1=1
						";


		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NUM_PATRIMONIO != ""){
			$sqlCorpo .= "  and upper(NUM_PATRIMONIO) like '%".strtoupper($this->NUM_PATRIMONIO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.patrimonio_chamado WHERE SEQ_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$sql = "INSERT INTO gestaoti.patrimonio_chamado(SEQ_CHAMADO,
										 	   NUM_PATRIMONIO
									)
							 VALUES (".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
									 ".$this->iif($this->NUM_PATRIMONIO=="", "NULL", "'".$this->NUM_PATRIMONIO."'")."
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