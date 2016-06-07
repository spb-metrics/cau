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
* Nome da Classe:	destino_triagem
* Nome da tabela:	destino_triagem
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
class destino_triagem{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_EQUIPE_TI;   // (normal Attribute)
	//var $COD_DEPENDENCIA;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function destino_triagem(){
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

	function getCOD_DEPENDENCIA(){
	//	return $this->COD_DEPENDENCIA;
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

	function setCOD_DEPENDENCIA($val){
		//$this->COD_DEPENDENCIA =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_EQUIPE_TI
			    FROM gestaoti.destino_triagem
				WHERE  = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_EQUIPE_TI = $row->SEQ_EQUIPE_TI;
		//$this->COD_DEPENDENCIA = $row->COD_DEPENDENCIA;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT a.SEQ_EQUIPE_TI, b.NOM_EQUIPE_TI, c.SG_DEPENDENCIA, NO_DEPENDENCIA ";
		$sqlCorpo  = "FROM gestaoti.destino_triagem a, gestaoti.equipe_ti b
					  WHERE a.SEQ_EQUIPE_TI = b.SEQ_EQUIPE_TI
					 ";
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and a.SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
		}
//		if($this->COD_DEPENDENCIA != ""){
//			$sqlCorpo .= "  and a.COD_DEPENDENCIA = $this->COD_DEPENDENCIA ";
//		}
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
	function delete($v_SEQ_EQUIPE_TI, $v_COD_DEPENDENCIA=""){
		$sql = "DELETE FROM gestaoti.destino_triagem
				WHERE SEQ_EQUIPE_TI   = $v_SEQ_EQUIPE_TI ";
				  //and COD_DEPENDENCIA = $v_COD_DEPENDENCIA";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		/*$sql = "INSERT INTO gestaoti.destino_triagem(SEQ_EQUIPE_TI,
										    COD_DEPENDENCIA
									)
							 VALUES (".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
									 ".$this->iif($this->COD_DEPENDENCIA=="", "NULL", "'".$this->COD_DEPENDENCIA."'")."
							 		) ";
	    */
		$sql = "INSERT INTO gestaoti.destino_triagem(SEQ_EQUIPE_TI)
				VALUES (".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").") ";
		$result = $this->database->query($sql);
	}


	function BuscarDependenciasEquipe($v_SEQ_EQUIPE_TI){
		/*
		$sql = "SELECT COD_DEPENDENCIA
				FROM gestaoti.destino_triagem
				where SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI";
		$this->database->query($sql);
		$vRetorno = "";
		$cont = 1;
		while ($row = pg_fetch_array($this->database->result)){
			$vRetorno .= $row[0];
			if($cont < $this->database->rows){
				$vRetorno .= ", ";
			}
			$cont++;
		}
		*/
		return "";
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