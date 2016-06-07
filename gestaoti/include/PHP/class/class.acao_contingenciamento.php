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
* CLASSNAME:        tipo_item_configuracao
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
class acao_contingenciamento{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_ACAO_CONTINGENCIAMENTO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_ACAO_CONTINGENCIAMENTO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function acao_contingenciamento(){
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
	function getSEQ_ACAO_CONTINGENCIAMENTO(){
		return $this->SEQ_ACAO_CONTINGENCIAMENTO;
	}

	function getNOM_ACAO_CONTINGENCIAMENTO(){
		return $this->NOM_ACAO_CONTINGENCIAMENTO;
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
	function setSEQ_ACAO_CONTINGENCIAMENTO($val){
		$this->SEQ_ACAO_CONTINGENCIAMENTO =  $val;
	}

	function setNOM_ACAO_CONTINGENCIAMENTO($val){
		$this->NOM_ACAO_CONTINGENCIAMENTO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.acao_contingenciamento WHERE SEQ_ACAO_CONTINGENCIAMENTO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_ACAO_CONTINGENCIAMENTO = $row->seq_acao_contingenciamento;
		$this->NOM_ACAO_CONTINGENCIAMENTO = $row->nom_acao_contingenciamento;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);


		$sqlSelect = "SELECT SEQ_ACAO_CONTINGENCIAMENTO , NOM_ACAO_CONTINGENCIAMENTO ";
		$sqlCorpo  = "FROM gestaoti.acao_contingenciamento
						WHERE 1=1 ";

		if($this->SEQ_ACAO_CONTINGENCIAMENTO != ""){
			$sqlCorpo .= "  and SEQ_ACAO_CONTINGENCIAMENTO = $this->SEQ_ACAO_CONTINGENCIAMENTO ";
		}
		if($this->NOM_ACAO_CONTINGENCIAMENTO != ""){
			$sqlCorpo .= "  and upper(NOM_ACAO_CONTINGENCIAMENTO) like '%".strtoupper($this->NOM_ACAO_CONTINGENCIAMENTO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.acao_contingenciamento WHERE SEQ_ACAO_CONTINGENCIAMENTO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_ACAO_CONTINGENCIAMENTO = $this->database->GetSequenceValue("gestaoti.SEQ_ACAO_CONTINGENCIAMENTO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.acao_contingenciamento (SEQ_ACAO_CONTINGENCIAMENTO, NOM_ACAO_CONTINGENCIAMENTO )
				VALUES (".$this->SEQ_ACAO_CONTINGENCIAMENTO.",
						".$this->database->iif($this->NOM_ACAO_CONTINGENCIAMENTO=="", "NULL", "'".$this->NOM_ACAO_CONTINGENCIAMENTO."'")."
				)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.acao_contingenciamento SET  NOM_ACAO_CONTINGENCIAMENTO = ".$this->database->iif($this->NOM_ACAO_CONTINGENCIAMENTO=="", "NULL", "'".$this->NOM_ACAO_CONTINGENCIAMENTO."'")." WHERE SEQ_ACAO_CONTINGENCIAMENTO = $id ";
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