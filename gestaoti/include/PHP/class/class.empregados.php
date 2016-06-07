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
*
* -------------------------------------------------------
* CLASSNAME:        empregados
* -------------------------------------------------------
*
*/
include_once("include/PHP/class/class.database.php");

// **********************
// CLASS DECLARATION
// **********************

class empregados{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $EMP_NUMERO_MATRICULA;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_LOGIN_REDE;   // (normal Attribute)
	var $NOME;   // (normal Attribute)
	var $NOME_ABREVIADO;   // (normal Attribute)
	var $NOME_GUERRA;   // (normal Attribute)
	var $DEP_SIGLA;   // (normal Attribute)
	var $UOR_SIGLA;   // (normal Attribute)
	var $DES_EMAIL;   // (normal Attribute)
	var $NUM_DDD;   // (normal Attribute)
	var $NUM_TELEFONE;   // (normal Attribute)
	var $NUM_VOIP;   // (normal Attribute)
	var $DES_ATATUS;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function empregados(){
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


	function getEMP_NUMERO_MATRICULA(){
		return $this->EMP_NUMERO_MATRICULA;
	}

	function getNOM_LOGIN_REDE(){
		return $this->NOM_LOGIN_REDE;
	}

	function getNOME(){
		return $this->NOME;
	}

	function getNOME_ABREVIADO(){
		return $this->NOME_ABREVIADO;
	}

	function getNOME_GUERRA(){
		return $this->NOME_GUERRA;
	}

	function getDEP_SIGLA(){
		return $this->DEP_SIGLA;
	}

	function getUOR_SIGLA(){
		return $this->UOR_SIGLA;
	}

	function getDES_EMAIL(){
		return $this->DES_EMAIL;
	}

	function getNUM_DDD(){
		return $this->NUM_DDD;
	}

	function getNUM_TELEFONE(){
		return $this->NUM_TELEFONE;
	}

	function getNUM_VOIP(){
		return $this->NUM_VOIP;
	}

	function getDES_ATATUS(){
		return $this->DES_ATATUS;
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


	function setEMP_NUMERO_MATRICULA($val){
		$this->EMP_NUMERO_MATRICULA =  $val;
	}

	function setNOM_LOGIN_REDE($val){
		$this->NOM_LOGIN_REDE =  $val;
	}

	function setNOME($val){
		$this->NOME =  $val;
	}

	function setNOME_ABREVIADO($val){
		$this->NOME_ABREVIADO =  $val;
	}

	function setNOME_GUERRA($val){
		$this->NOME_GUERRA =  $val;
	}

	function setDEP_SIGLA($val){
		$this->DEP_SIGLA =  $val;
	}

	function setUOR_SIGLA($val){
		$this->UOR_SIGLA =  $val;
	}

	function setDES_EMAIL($val){
		$this->DES_EMAIL =  $val;
	}

	function setNUM_DDD($val){
		$this->NUM_DDD =  $val;
	}

	function setNUM_TELEFONE($val){
		$this->NUM_TELEFONE =  $val;
	}

	function setNUM_VOIP($val){
		$this->NUM_VOIP =  $val;
	}

	function setDES_ATATUS($val){
		$this->DES_ATATUS =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************

	function select($id){
		$sql =  "SELECT * FROM gestaoti.empregados WHERE EMP_NUMERO_MATRICULA = $id;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = mysql_error();
		$row = mysql_fetch_object($result);

		$this->EMP_NUMERO_MATRICULA = $row->EMP_NUMERO_MATRICULA;
		$this->NOM_LOGIN_REDE = $row->NOM_LOGIN_REDE;
		$this->NOME = $row->NOME;
		$this->NOME_ABREVIADO = $row->NOME_ABREVIADO;
		$this->NOME_GUERRA = $row->NOME_GUERRA;
		$this->DEP_SIGLA = $row->DEP_SIGLA;
		$this->UOR_SIGLA = $row->UOR_SIGLA;
		$this->DES_EMAIL = $row->DES_EMAIL;
		$this->NUM_DDD = $row->NUM_DDD;
		$this->NUM_TELEFONE = $row->NUM_TELEFONE;
		$this->NUM_VOIP = $row->NUM_VOIP;
		$this->DES_ATATUS = $row->DES_ATATUS;
	}

	function GetNumeroMatricula($id){
		$sql =  "SELECT EMP_NUMERO_MATRICULA
				 FROM gestaoti.empregados
				 WHERE NOM_LOGIN_REDE = '$id';";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = mysql_error();
		$row = mysql_fetch_object($result);

		return $row->EMP_NUMERO_MATRICULA;
	}

	function GetNomLoginRedeMatricula($id){
		$sql =  "SELECT NOM_LOGIN_REDE
				 FROM gestaoti.empregados
				 WHERE EMP_NUMERO_MATRICULA = '$id';";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = mysql_error();
		$row = mysql_fetch_object($result);

		return $row->NOM_LOGIN_REDE;
	}

	function GetNomeEmpregado($id){
		$sql =  "SELECT NOME
				 FROM gestaoti.empregados
				 WHERE EMP_NUMERO_MATRICULA = '$id';";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = mysql_error();
		$row = mysql_fetch_object($result);

		return $row->NOME;
	}
	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************

	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " SELECT EMP_NUMERO_MATRICULA , NOM_LOGIN_REDE , NOME , NOME_ABREVIADO , NOME_GUERRA , DEP_SIGLA , UOR_SIGLA , DES_EMAIL , NUM_DDD , NUM_TELEFONE , NUM_VOIP , DES_ATATUS ";
		$sqlCorpo  = " FROM gestaoti.empregados
			      WHERE 1=1 ";

		if($this->EMP_NUMERO_MATRICULA != ""){
			$sqlCorpo .= "  and EMP_NUMERO_MATRICULA = $this->EMP_NUMERO_MATRICULA ";
		}
		if($this->NOM_LOGIN_REDE != ""){
			$sqlCorpo .= "  and upper(NOM_LOGIN_REDE) like '%".strtoupper($this->NOM_LOGIN_REDE)."%'  ";
		}
		if($this->NOME != ""){
			$sqlCorpo .= "  and upper(NOME) like '%".strtoupper($this->NOME)."%'  ";
		}
		if($this->NOME_ABREVIADO != ""){
			$sqlCorpo .= "  and upper(NOME_ABREVIADO) like '%".strtoupper($this->NOME_ABREVIADO)."%'  ";
		}
		if($this->NOME_GUERRA != ""){
			$sqlCorpo .= "  and upper(NOME_GUERRA) like '%".strtoupper($this->NOME_GUERRA)."%'  ";
		}
		if($this->DEP_SIGLA != ""){
			$sqlCorpo .= "  and upper(DEP_SIGLA) like '%".strtoupper($this->DEP_SIGLA)."%'  ";
		}
		if($this->UOR_SIGLA != ""){
			$sqlCorpo .= "  and upper(UOR_SIGLA) like '%".strtoupper($this->UOR_SIGLA)."%'  ";
		}
		if($this->DES_EMAIL != ""){
			$sqlCorpo .= "  and upper(DES_EMAIL) like '%".strtoupper($this->DES_EMAIL)."%'  ";
		}
		if($this->NUM_DDD != ""){
			$sqlCorpo .= "  and upper(NUM_DDD) like '%".strtoupper($this->NUM_DDD)."%'  ";
		}
		if($this->NUM_TELEFONE != ""){
			$sqlCorpo .= "  and upper(NUM_TELEFONE) like '%".strtoupper($this->NUM_TELEFONE)."%'  ";
		}
		if($this->NUM_VOIP != ""){
			$sqlCorpo .= "  and upper(NUM_VOIP) like '%".strtoupper($this->NUM_VOIP)."%'  ";
		}
		if($this->DES_ATATUS != ""){
			$sqlCorpo .= "  and upper(DES_ATATUS) like '%".strtoupper($this->DES_ATATUS)."%'  ";
		}
		if($orderBy != "" ){
			$sqlOrder = " order by $orderBy ";
		}

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);
			$sqlOrder .= " limit $vLimit, $vQtdRegistros ";
			$this->database->query("select count(1) " . $sqlCorpo);
			$rowCount = mysql_fetch_array($this->database->result, MYSQL_NUM);
			$this->setrowCount($rowCount[0]);
		}

		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
		if(!$this->database->result) $this->error = mysql_error();
	}

	// **********************
	// DELETE
	// **********************

	function delete($id){
		$sql = "DELETE FROM gestaoti.empregados WHERE  = $id;";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();

	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->EMP_NUMERO_MATRICULA = ""; // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.empregados ( EMP_NUMERO_MATRICULA,NOM_LOGIN_REDE,NOME,NOME_ABREVIADO,NOME_GUERRA,DEP_SIGLA,UOR_SIGLA,DES_EMAIL,NUM_DDD,NUM_TELEFONE,NUM_VOIP,DES_ATATUS ) VALUES ( ".$this->iif($this->EMP_NUMERO_MATRICULA=="", "NULL", "'".$this->EMP_NUMERO_MATRICULA."'").",".$this->iif($this->NOM_LOGIN_REDE=="", "NULL", "'".$this->NOM_LOGIN_REDE."'").",".$this->iif($this->NOME=="", "NULL", "'".$this->NOME."'").",".$this->iif($this->NOME_ABREVIADO=="", "NULL", "'".$this->NOME_ABREVIADO."'").",".$this->iif($this->NOME_GUERRA=="", "NULL", "'".$this->NOME_GUERRA."'").",".$this->iif($this->DEP_SIGLA=="", "NULL", "'".$this->DEP_SIGLA."'").",".$this->iif($this->UOR_SIGLA=="", "NULL", "'".$this->UOR_SIGLA."'").",".$this->iif($this->DES_EMAIL=="", "NULL", "'".$this->DES_EMAIL."'").",".$this->iif($this->NUM_DDD=="", "NULL", "'".$this->NUM_DDD."'").",".$this->iif($this->NUM_TELEFONE=="", "NULL", "'".$this->NUM_TELEFONE."'").",".$this->iif($this->NUM_VOIP=="", "NULL", "'".$this->NUM_VOIP."'").",".$this->iif($this->DES_ATATUS=="", "NULL", "'".$this->DES_ATATUS."'")." )";
		$result = $this->database->query($sql);
//		$this->EMP_NUMERO_MATRICULA = mysql_insert_id($this->database->link);
		if(!$result) $this->error = mysql_error();
	}

	// **********************
	// UPDATE
	// **********************

	function update($id){
		$sql = " UPDATE gestaoti.empregados SET  EMP_NUMERO_MATRICULA = ".$this->iif($this->EMP_NUMERO_MATRICULA=="", "NULL", "'".$this->EMP_NUMERO_MATRICULA."'").",NOM_LOGIN_REDE = ".$this->iif($this->NOM_LOGIN_REDE=="", "NULL", "'".$this->NOM_LOGIN_REDE."'").",NOME = ".$this->iif($this->NOME=="", "NULL", "'".$this->NOME."'").",NOME_ABREVIADO = ".$this->iif($this->NOME_ABREVIADO=="", "NULL", "'".$this->NOME_ABREVIADO."'").",NOME_GUERRA = ".$this->iif($this->NOME_GUERRA=="", "NULL", "'".$this->NOME_GUERRA."'").",DEP_SIGLA = ".$this->iif($this->DEP_SIGLA=="", "NULL", "'".$this->DEP_SIGLA."'").",UOR_SIGLA = ".$this->iif($this->UOR_SIGLA=="", "NULL", "'".$this->UOR_SIGLA."'").",DES_EMAIL = ".$this->iif($this->DES_EMAIL=="", "NULL", "'".$this->DES_EMAIL."'").",NUM_DDD = ".$this->iif($this->NUM_DDD=="", "NULL", "'".$this->NUM_DDD."'").",NUM_TELEFONE = ".$this->iif($this->NUM_TELEFONE=="", "NULL", "'".$this->NUM_TELEFONE."'").",NUM_VOIP = ".$this->iif($this->NUM_VOIP=="", "NULL", "'".$this->NUM_VOIP."'").",DES_ATATUS = ".$this->iif($this->DES_ATATUS=="", "NULL", "'".$this->DES_ATATUS."'")." WHERE  = $id ";
		$result = $this->database->query($sql);
		if(!$result) $this->error = mysql_error();

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