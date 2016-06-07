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
* Nome da Classe:	equipe_ti
* Data de cria��o:	03.09.2008
* Nome do Arquivo:	D:\Tiago\Pessoal\pages\gestaoti/GeraPHP/include/PHP/class/class.equipe_ti.php
* Nome da tabela:	equipe_ti
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
class equipe_ti{
	// class : begin

	// ***********************
	// DECLARA��O DE ATRIBUTOS
	// ***********************
	var $SEQ_EQUIPE_TI;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para pagina��o de resultados
	var $vQtdRegistros; // Quantidade de registros por p�gina

	var $NOM_EQUIPE_TI;   // (normal Attribute)
	var $NUM_MATRICULA_LIDER;   // (normal Attribute)
	var $NUM_MATRICULA_SUBSTITUTO;   // (normal Attribute)
	var $NUM_MATRICULA_PRIORIZADOR;   // (normal Attribute)
	var $COD_DEPENDENCIA;   // (normal Attribute)
	var $NOM_LIDER;
	var $DSC_EMAIL_LIDER;
	var $NOM_SUBSTITUTO;
	var $DSC_EMAIL_SUBSTITUTO;
	var $SEQ_CENTRAL_ATENDIMENTO; 

	var $database; // Instance of class database
	var $error; // Descri��o de erro ao efetuar a��o no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function equipe_ti(){
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

	function getSEQ_EQUIPE_TI(){
		return $this->SEQ_EQUIPE_TI;
	}

	function getNOM_EQUIPE_TI(){
		return $this->NOM_EQUIPE_TI;
	}

	function getNUM_MATRICULA_LIDER(){
		return $this->NUM_MATRICULA_LIDER;
	}

	function getNUM_MATRICULA_SUBSTITUTO(){
		return $this->NUM_MATRICULA_SUBSTITUTO;
	}

	function getNUM_MATRICULA_PRIORIZADOR(){
		return $this->NUM_MATRICULA_PRIORIZADOR;
	}

	function getCOD_DEPENDENCIA(){
		return $this->COD_DEPENDENCIA;
	}
	function getSEQ_CENTRAL_ATENDIMENTO(){
		return $this->SEQ_CENTRAL_ATENDIMENTO;
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

	function setSEQ_EQUIPE_TI($val){
		$this->SEQ_EQUIPE_TI =  $val;
	}

	function setNOM_EQUIPE_TI($val){
		$this->NOM_EQUIPE_TI =  $val;
	}

	function setNUM_MATRICULA_LIDER($val){
		$this->NUM_MATRICULA_LIDER =  $val;
	}

	function setNUM_MATRICULA_SUBSTITUTO($val){
		$this->NUM_MATRICULA_SUBSTITUTO =  $val;
	}

	function setNUM_MATRICULA_PRIORIZADOR($val){
		$this->NUM_MATRICULA_PRIORIZADOR =  $val;
	}

	function setCOD_DEPENDENCIA($val){
		$this->COD_DEPENDENCIA =  $val;
	}
	

	function setSEQ_CENTRAL_ATENDIMENTO($val){
		$this->SEQ_CENTRAL_ATENDIMENTO =  $val;
	}
	
	

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_EQUIPE_TI , NOM_EQUIPE_TI , NUM_MATRICULA_LIDER , NUM_MATRICULA_SUBSTITUTO , NUM_MATRICULA_PRIORIZADOR , COD_DEPENDENCIA, SEQ_CENTRAL_ATENDIMENTO 
                        FROM gestaoti.equipe_ti
                        WHERE SEQ_EQUIPE_TI = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result,0);
		$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
		$this->NOM_EQUIPE_TI = $row->nom_equipe_ti;
		$this->NUM_MATRICULA_LIDER = $row->num_matricula_lider;
		$this->NUM_MATRICULA_SUBSTITUTO = $row->num_matricula_substituto;
		$this->NUM_MATRICULA_PRIORIZADOR = $row->num_matricula_priorizador;
		$this->COD_DEPENDENCIA = $row->cod_dependencia;
		$this->SEQ_CENTRAL_ATENDIMENTO = $row->seq_central_atendimento;
	}

	function EmailLiderSubstituto($id){
		$sql = "select b.NOM_COLABORADOR as NOM_LIDER, b.DSC_EMAIL as DSC_EMAIL_LIDER,
                               c.NOM_COLABORADOR as NOM_SUBSTITUTO, C.DSC_EMAIL as DSC_EMAIL_SUBSTITUTO
                        from gestaoti.equipe_ti a, gestaoti.viw_colaborador b, gestaoti.viw_colaborador c
                        where a.NUM_MATRICULA_LIDER = b.NUM_MATRICULA_COLABORADOR
                        and a.NUM_MATRICULA_SUBSTITUTO = c.NUM_MATRICULA_COLABORADOR
                        and a.SEQ_EQUIPE_TI = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result);
		$this->NOM_LIDER = $row->nom_lider;
		$this->DSC_EMAIL_LIDER = $row->dsc_email_lider;
		$this->NOM_SUBSTITUTO = $row->nom_substituto;
		$this->DSC_EMAIL_SUBSTITUTO = $row->dsc_email_substituto;
	}

	// ****************************
	// SELECT METHOD COM PAR�METROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_EQUIPE_TI , NOM_EQUIPE_TI , NUM_MATRICULA_LIDER , NUM_MATRICULA_SUBSTITUTO , NUM_MATRICULA_PRIORIZADOR , COD_DEPENDENCIA, SEQ_CENTRAL_ATENDIMENTO ";
		$sqlCorpo  = "
                              FROM gestaoti.equipe_ti
                              WHERE 1=1 ";

		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
		}
		if($this->NOM_EQUIPE_TI != ""){
			$sqlCorpo .= "  and upper(NOM_EQUIPE_TI) like '%".strtoupper($this->NOM_EQUIPE_TI)."%'  ";
		}
		if($this->NUM_MATRICULA_LIDER != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_LIDER = $this->NUM_MATRICULA_LIDER ";
		}
		if($this->NUM_MATRICULA_SUBSTITUTO != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_SUBSTITUTO = $this->NUM_MATRICULA_SUBSTITUTO ";
		}
		if($this->NUM_MATRICULA_PRIORIZADOR != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_PRIORIZADOR = $this->NUM_MATRICULA_PRIORIZADOR ";
		}
		if($this->COD_DEPENDENCIA != ""){
			$sqlCorpo .= "  and COD_DEPENDENCIA = $this->COD_DEPENDENCIA ";
		}
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo .= "  and SEQ_CENTRAL_ATENDIMENTO = $this->SEQ_CENTRAL_ATENDIMENTO ";
		}
		$sqlCount = $sqlCorpo;
		if($orderBy != "" ){
			$sqlCorpo .= " order by $orderBy ";
		}
		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);
			$vFinal = $vLimit + $vQtdRegistros;
			$sqlCorpo .= " limit $vQtdRegistros offset $vLimit ";
		}

		if($vNumPagina != ""){
			// Pegar a quantidade de registros GERAL
			$this->database->query("select count(1) " . $sqlCount);
			$rowCount = pg_fetch_array($this->database->result);
			$this->setrowCount($rowCount[0]);
		}
		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}

	function selectQuantidadeEquipesPorCliente($v_NUM_MATRICULA_CLIENTE){
		$sql = "select count(1) as cont
				from gestaoti.equipe_ti
				where num_matricula_priorizador = $v_NUM_MATRICULA_CLIENTE";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_array($result);
		return $row[0];
	}

	// **********************
	// DELETE
	// **********************

	function delete($id){
		$sql = "DELETE FROM gestaoti.equipe_ti WHERE SEQ_EQUIPE_TI = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_EQUIPE_TI = $this->database->GetSequenceValue("gestaoti.SEQ_EQUIPE_TI");

		$sql = "INSERT INTO gestaoti.equipe_ti( SEQ_EQUIPE_TI,
                                                        NOM_EQUIPE_TI,
                                                        NUM_MATRICULA_LIDER,
                                                        NUM_MATRICULA_SUBSTITUTO,
                                                        NUM_MATRICULA_PRIORIZADOR,
                                                        COD_DEPENDENCIA,
                                                        SEQ_CENTRAL_ATENDIMENTO
                                            )
                             VALUES (".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
                                     ".$this->iif($this->NOM_EQUIPE_TI=="", "NULL", "'".$this->NOM_EQUIPE_TI."'").",
                                     ".$this->iif($this->NUM_MATRICULA_LIDER=="", "NULL", "'".$this->NUM_MATRICULA_LIDER."'").",
                                     ".$this->iif($this->NUM_MATRICULA_SUBSTITUTO=="", "NULL", "'".$this->NUM_MATRICULA_SUBSTITUTO."'").",
                                     ".$this->iif($this->NUM_MATRICULA_PRIORIZADOR=="", "NULL", "'".$this->NUM_MATRICULA_PRIORIZADOR."'").",
                                     ".$this->iif($this->COD_DEPENDENCIA=="", "NULL", "'".$this->COD_DEPENDENCIA."'").",
                                     ".$this->iif($this->SEQ_CENTRAL_ATENDIMENTO=="", "NULL", "'".$this->SEQ_CENTRAL_ATENDIMENTO."'")."
                                    ) ";
		
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.equipe_ti
                         SET NOM_EQUIPE_TI = ".$this->iif($this->NOM_EQUIPE_TI=="", "NULL", "'".$this->NOM_EQUIPE_TI."'").",
                             NUM_MATRICULA_LIDER = ".$this->iif($this->NUM_MATRICULA_LIDER=="", "NULL", "'".$this->NUM_MATRICULA_LIDER."'").",
                             NUM_MATRICULA_SUBSTITUTO = ".$this->iif($this->NUM_MATRICULA_SUBSTITUTO=="", "NULL", "'".$this->NUM_MATRICULA_SUBSTITUTO."'").",
                             NUM_MATRICULA_PRIORIZADOR = ".$this->iif($this->NUM_MATRICULA_PRIORIZADOR=="", "NULL", "'".$this->NUM_MATRICULA_PRIORIZADOR."'").",
                             SEQ_CENTRAL_ATENDIMENTO = ".$this->iif($this->SEQ_CENTRAL_ATENDIMENTO=="", "NULL", "'".$this->SEQ_CENTRAL_ATENDIMENTO."'").",
                             COD_DEPENDENCIA = ".$this->iif($this->COD_DEPENDENCIA=="", "NULL", "'".$this->COD_DEPENDENCIA."'")."
				WHERE SEQ_EQUIPE_TI = $id ";
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