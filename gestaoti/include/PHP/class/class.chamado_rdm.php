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
* Nome da Classe:	anexo_rdm
* Nome da tabela:	anexo_rdm
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
class chamado_rdm{
	// class : begin
	
	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_RDM;   // (normal Attribute)
	 

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function chamado_rdm(){ 
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

	function getSEQ_RDM(){
		return $this->SEQ_RDM;
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

	function setSEQ_RDM($val){
		$this->SEQ_RDM =  $val;
	} 
	
	 
	// **********************
	// SELECT METHOD / LOAD
	// **********************	

	function select($idRDM){
		$sql = "SELECT seq_chamado, seq_rdm  FROM gestaoti.chamado_rdm WHERE seq_rdm= $idRDM";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_CHAMADO = $row->seq_chamado;
		$this->SEQ_RDM = $row->seq_rdm;
		 
		 
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT r.seq_chamado, r.seq_rdm,c.txt_chamado,a.dsc_atividade_chamado ";
		$sqlCorpo  = "FROM gestaoti.chamado_rdm r, gestaoti.chamado c, gestaoti.atividade_chamado a
					  WHERE 
					  	r.seq_chamado = c.seq_chamado and					  	 
					  	c.SEQ_ATIVIDADE_CHAMADO = a.SEQ_ATIVIDADE_CHAMADO
					 ";

		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and r.seq_chamado = $this->SEQ_CHAMADO";
		}
		if($this->SEQ_RDM != ""){
			$sqlCorpo .= "  and r.SEQ_RDM = $this->SEQ_RDM ";
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
	function delete($idRDM,$idChamado){
		$sql = "DELETE FROM gestaoti.chamado_rdm WHERE seq_chamado =".$idChamado." and SEQ_RDM =".$idRDM;
		$result = $this->database->query($sql);
	}
	// **********************
	function deleteByRDM($id){
		$sql = "DELETE FROM gestaoti.chamado_rdm WHERE SEQ_RDM = $id";
		$result = $this->database->query($sql);
	}
	

	// **********************
	// INSERT
	// **********************
	
	function insert(){
		 
		
		$sql = "INSERT INTO gestaoti.chamado_rdm(seq_chamado,SEQ_RDM )
				VALUES (".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
						 ".$this->iif($this->SEQ_RDM=="", "NULL", "'".$this->SEQ_RDM."'")."
				 		) ";
		
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// ********************** 
	
	function update($idRDM,$idChamado){
		$sql = " UPDATE gestaoti.chamado_rdm
				 SET seq_chamado = ".$this->iif($this->SEQ_RDM=="", "NULL", "'".$this->SEQ_RDM."'").",
					 SEQ_RDM = ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'")." 
				WHERE  seq_chamado =".$idChamado." and SEQ_RDM =".$idRDM;		 
		$result = $this->database->query($sql);
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