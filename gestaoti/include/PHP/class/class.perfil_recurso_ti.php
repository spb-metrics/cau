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
* CLASSNAME:        perfil_recurso_ti
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
class perfil_recurso_ti{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_PERFIL_RECURSO_TI;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina


	var $NOM_PERFIL_RECURSO_TI;   // (normal Attribute)
	var $VAL_HORA;

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados
	var $SEQ_PERFIL_ACESSO_ADMINISTRADOR;
	var $SEQ_PERFIL_RECURSO_TI_REMOVER;
	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function perfil_recurso_ti(){
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
	function getSEQ_PERFIL_RECURSO_TI(){
		return $this->SEQ_PERFIL_RECURSO_TI;
	}

	function getNOM_PERFIL_RECURSO_TI(){
		return $this->NOM_PERFIL_RECURSO_TI;
	}
	function getVAL_HORA(){
		return $this->VAL_HORA;
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
	function setSEQ_PERFIL_RECURSO_TI($val){
		$this->SEQ_PERFIL_RECURSO_TI =  $val;
	}

	function setNOM_PERFIL_RECURSO_TI($val){
		$this->NOM_PERFIL_RECURSO_TI =  $val;
	}
	function setVAL_HORA($val){
		$this->VAL_HORA =  $val;
	}
	function setSEQ_PERFIL_RECURSO_TI_REMOVER($val){
		$this->SEQ_PERFIL_RECURSO_TI_REMOVER = $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.perfil_recurso_ti WHERE SEQ_PERFIL_RECURSO_TI = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_PERFIL_RECURSO_TI = $row->seq_perfil_recurso_ti;
		$this->NOM_PERFIL_RECURSO_TI = $row->nom_perfil_recurso_ti;
		$this->VAL_HORA = $row->val_hora;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT SEQ_PERFIL_RECURSO_TI , NOM_PERFIL_RECURSO_TI, VAL_HORA ";
		$sqlCorpo  = " FROM  gestaoti.perfil_recurso_ti ";

		$sqlCorpo .= " WHERE 1=1 ";

		if($this->SEQ_PERFIL_RECURSO_TI != ""){
			$sqlCorpo .= "  and SEQ_PERFIL_RECURSO_TI = $this->SEQ_PERFIL_RECURSO_TI ";
		}
		if($this->NOM_PERFIL_RECURSO_TI != ""){
			$sqlCorpo .= "  and upper(NOM_PERFIL_RECURSO_TI) like '%".strtoupper($this->NOM_PERFIL_RECURSO_TI)."%'  ";
		}
		if($this->SEQ_PERFIL_RECURSO_TI_REMOVER != ""){
			$sqlCorpo .= "  and SEQ_PERFIL_RECURSO_TI not in ($this->SEQ_PERFIL_RECURSO_TI_REMOVER) ";
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
		$sql = "DELETE FROM gestaoti.perfil_recurso_ti WHERE SEQ_PERFIL_RECURSO_TI = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_PERFIL_RECURSO_TI = $this->database->GetSequenceValue("gestaoti.SEQ_PERFIL_RECURSO_TI"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.perfil_recurso_ti (SEQ_PERFIL_RECURSO_TI, NOM_PERFIL_RECURSO_TI, VAL_HORA )
				VALUES ( ".$this->SEQ_PERFIL_RECURSO_TI.",
						 ".$this->database->iif($this->NOM_PERFIL_RECURSO_TI=="", "NULL", "'".$this->NOM_PERFIL_RECURSO_TI."'").",
						 ".$this->database->iif($this->VAL_HORA=="", "NULL", "'".$this->VAL_HORA."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.perfil_recurso_ti SET
						NOM_PERFIL_RECURSO_TI = ".$this->database->iif($this->NOM_PERFIL_RECURSO_TI=="", "NULL", "'".$this->NOM_PERFIL_RECURSO_TI."'").",
						VAL_HORA = ".$this->database->iif($this->VAL_HORA=="", "NULL", "'".$this->VAL_HORA."'")."
				 WHERE SEQ_PERFIL_RECURSO_TI = $id ";
		$result = $this->database->query($sql);
	}
} // class : end
?>