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
* Nome da Classe:	motivo_cancelamento
* Nome da tabela:	janela_mudanca
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// DECLARAÇÃO DA CLASSE
// **********************
class janela_mudanca{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************	
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página
	
	var $seq_janela_mudanca;   // KEY ATTR. WITH AUTOINCREMENT
	var $dsc_janela_mudanca;   // (normal Attribute)
	var $hora_inicio_mudanca;   // (normal Attribute)
	var $hora_fim_mudanca; // (normal Attribute)
	var $minuto_inicio_mudanca;   // (normal Attribute)
	var $minuto_fim_mudanca; // (normal Attribute)
	var $dia_semana_inicial;// (normal Attribute)
	var $dia_semana_final;// (normal Attribute)
	var $limite_para_rdm;// (normal Attribute)
	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function janela_mudanca(){
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
	function getSeq_janela_mudanca(){
		return $this->seq_janela_mudanca;
	}
	function getDsc_janela_mudanca(){
		return $this->dsc_janela_mudanca;
	}
	function getHora_inicio_mudanca(){
		return $this->hora_inicio_mudanca;
	}	
	function getHora_fim_mudanca(){
		return $this->hora_fim_mudanca;
	}	
	function getMinuto_inicio_mudanca(){
		return $this->minuto_inicio_mudanca;
	}
	function getMinuto_fim_mudanca(){
		return $this->minuto_fim_mudanca;
	} 
	function getDia_semana_inicial(){
		return $this->dia_semana_inicial;
	}
	function getDia_semana_final(){
		return $this->dia_semana_final;
	}
	function getLimite_para_rdm(){
		return $this->limite_para_rdm;
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
	function setSeq_janela_mudanca($val){
		$this->seq_janela_mudanca = $val;
	}
	function setDsc_janela_mudanca($val){
		$this->dsc_janela_mudanca = $val;
	}
	function setHora_inicio_mudanca($val){
		$this->hora_inicio_mudanca = $val;
	}
	function setHora_fim_mudanca($val){
		$this->hora_fim_mudanca = $val;
	}
	
	function setMinuto_inicio_mudanca($val){
		$this->minuto_inicio_mudanca = $val;
	}
	function setMinuto_fim_mudanca($val){
		$this->minuto_fim_mudanca = $val;
	} 
	
	function setDia_semana_inicial($val){
		$this->dia_semana_inicial = $val;
	}
	function setDia_semana_final($val){
		$this->dia_semana_final = $val;
	}
	function setLimite_para_rdm($val){
		$this->limite_para_rdm = $val;
	}
	
	  
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT seq_janela_mudanca, dsc_janela_mudanca, hora_inicio_mudanca, 
       				  minuto_inicio_mudanca, hora_fim_mudanca, minuto_fim_mudanca, 
       				  dia_semana_inicial, dia_semana_final,limite_para_rdm FROM gestaoti.janela_mudanca
				WHERE seq_janela_mudanca = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->seq_janela_mudanca = $row->seq_janela_mudanca;
		$this->dsc_janela_mudanca = $row->dsc_janela_mudanca;
		$this->hora_inicio_mudanca = $row->hora_inicio_mudanca;
		$this->minuto_inicio_mudanca = $row->minuto_inicio_mudanca;
		$this->hora_fim_mudanca = $row->hora_fim_mudanca;
		$this->minuto_fim_mudanca = $row->minuto_fim_mudanca;
		$this->dia_semana_inicial = $row->dia_semana_inicial;
		$this->dia_semana_final = $row->dia_semana_final;
		$this->limite_para_rdm = $row->limite_para_rdm;
		
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT seq_janela_mudanca, dsc_janela_mudanca, hora_inicio_mudanca, 
       				  minuto_inicio_mudanca, hora_fim_mudanca, minuto_fim_mudanca, 
       				  dia_semana_inicial, dia_semana_final,limite_para_rdm ";
 
		$sqlCorpo  = " FROM gestaoti.janela_mudanca
						WHERE 1=1 ";

		if($this->seq_janela_mudanca != ""){
			$sqlCorpo .= "  and seq_janela_mudanca = $this->seq_janela_mudanca ";
		}
		if($this->dsc_janela_mudanca != ""){
			$sqlCorpo .= "  and upper(dsc_janela_mudanca) like '%".strtoupper($this->dsc_janela_mudanca)."%'  ";
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
		$sql = "DELETE FROM gestaoti.janela_mudanca WHERE seq_janela_mudanca = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->seq_janela_mudanca = $this->database->GetSequenceValue("gestaoti.seq_janela_mudanca");

		$sql = "INSERT INTO gestaoti.janela_mudanca(
            				seq_janela_mudanca, dsc_janela_mudanca, hora_inicio_mudanca, 
           					minuto_inicio_mudanca, hora_fim_mudanca, minuto_fim_mudanca, 
            				dia_semana_inicial, dia_semana_final,limite_para_rdm
						 )
				 VALUES (".$this->iif($this->seq_janela_mudanca=="", "NULL", "'".$this->seq_janela_mudanca."'").",
						 ".$this->iif($this->dsc_janela_mudanca=="", "NULL", "'".$this->dsc_janela_mudanca."'").",
						 ".$this->iif($this->hora_inicio_mudanca=="", "NULL", "'".$this->hora_inicio_mudanca."'").",
						 ".$this->iif($this->minuto_inicio_mudanca=="", "NULL", "'".$this->minuto_inicio_mudanca."'").",
						 ".$this->iif($this->hora_fim_mudanca=="", "NULL", "'".$this->hora_fim_mudanca."'").",
						 ".$this->iif($this->minuto_fim_mudanca=="", "NULL", "'".$this->minuto_fim_mudanca."'").",
						 ".$this->iif($this->dia_semana_inicial=="", "NULL", "'".$this->dia_semana_inicial."'").",
						 ".$this->iif($this->dia_semana_final=="", "NULL", "'".$this->dia_semana_final."'").",
						  ".$this->iif($this->limite_para_rdm=="", "NULL", "'".$this->limite_para_rdm."'")."
				 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.janela_mudanca SET ".
			   " dsc_janela_mudanca = ".$this->iif($this->dsc_janela_mudanca=="", "NULL", "'".$this->dsc_janela_mudanca."'").",".
		 	   " hora_inicio_mudanca = ".$this->iif($this->hora_inicio_mudanca=="", "NULL", "'".$this->hora_inicio_mudanca."'").",".
			   " minuto_inicio_mudanca = ".$this->iif($this->minuto_inicio_mudanca=="", "NULL", "'".$this->minuto_inicio_mudanca."'").",".
		       " hora_fim_mudanca = ".$this->iif($this->hora_fim_mudanca=="", "NULL", "'".$this->hora_fim_mudanca."'").",".
		       " minuto_fim_mudanca = ".$this->iif($this->minuto_fim_mudanca=="", "NULL", "'".$this->minuto_fim_mudanca."'").",".
		       " dia_semana_inicial = ".$this->iif($this->dia_semana_inicial=="", "NULL", "'".$this->dia_semana_inicial."'").",".
		       " dia_semana_final = ".$this->iif($this->dia_semana_final=="", "NULL", "'".$this->dia_semana_final."'").",".
			   " limite_para_rdm = ".$this->iif($this->limite_para_rdm=="", "NULL", "'".$this->limite_para_rdm."'").
			   " WHERE seq_janela_mudanca = $id ";
		$result = $this->database->query($sql);
	}
	
	function selectByIC($id, $tipo){ 
		
		$sql = "";
		
		if($tipo==2){
			$sql = "SELECT JM.seq_janela_mudanca, dsc_janela_mudanca, hora_inicio_mudanca, 
			minuto_inicio_mudanca, hora_fim_mudanca, minuto_fim_mudanca, 
			dia_semana_inicial, dia_semana_final,limite_para_rdm 
			FROM gestaoti.janela_mudanca JM, gestaoti.janela_mudacao_item_configuracao JMI
			where 
			JM.seq_janela_mudanca = JMI.seq_janela_mudanca
			AND seq_item_configuracao =$id";
		}else{
			$sql ="SELECT JM.seq_janela_mudanca, dsc_janela_mudanca, hora_inicio_mudanca, 
			minuto_inicio_mudanca, hora_fim_mudanca, minuto_fim_mudanca, 
			dia_semana_inicial, dia_semana_final,limite_para_rdm 
			FROM gestaoti.janela_mudanca JM, gestaoti.janela_mudanca_servidor JMS
			where 
			JM.seq_janela_mudanca = JMS.seq_janela_mudanca
			AND seq_servidor =$id";
		}
		
		//$sql .= " UNION
	
		
		$result = $this->database->query($sql);
		$result = $this->database->result;
//		//$row = pg_fetch_object($result, 0);
//		if ($row = pg_fetch_object($result)){
//			$this->seq_janela_mudanca = $row->seq_janela_mudanca;
//			$this->dsc_janela_mudanca = $row->dsc_janela_mudanca;
//			$this->hora_inicio_mudanca = $row->hora_inicio_mudanca;
//			$this->minuto_inicio_mudanca = $row->minuto_inicio_mudanca;
//			$this->hora_fim_mudanca = $row->hora_fim_mudanca;
//			$this->minuto_fim_mudanca = $row->minuto_fim_mudanca;
//			$this->dia_semana_inicial = $row->dia_semana_inicial;
//			$this->dia_semana_final = $row->dia_semana_final;
//			$this->limite_para_rdm = $row->limite_para_rdm;
//		}
		
	}
	
	function selectByIC_XXX($id, $tipo){ 
		
		$sql = "";
		
		if($tipo==2){
			$sql = "SELECT JM.seq_janela_mudanca, dsc_janela_mudanca, hora_inicio_mudanca, 
			minuto_inicio_mudanca, hora_fim_mudanca, minuto_fim_mudanca, 
			dia_semana_inicial, dia_semana_final,limite_para_rdm 
			FROM gestaoti.janela_mudanca JM, gestaoti.janela_mudacao_item_configuracao JMI
			where 
			JM.seq_janela_mudanca = JMI.seq_janela_mudanca
			AND seq_item_configuracao =$id";
		}else{
			$sql ="SELECT JM.seq_janela_mudanca, dsc_janela_mudanca, hora_inicio_mudanca, 
			minuto_inicio_mudanca, hora_fim_mudanca, minuto_fim_mudanca, 
			dia_semana_inicial, dia_semana_final,limite_para_rdm 
			FROM gestaoti.janela_mudanca JM, gestaoti.janela_mudanca_servidor JMS
			where 
			JM.seq_janela_mudanca = JMS.seq_janela_mudanca
			AND seq_servidor =$id";
		}
		
		//$sql .= " UNION
	
		
		$result = $this->database->query($sql);
		$result = $this->database->result;
		//$row = pg_fetch_object($result, 0);
		if ($row = pg_fetch_object($result)){
			$this->seq_janela_mudanca = $row->seq_janela_mudanca;
			$this->dsc_janela_mudanca = $row->dsc_janela_mudanca;
			$this->hora_inicio_mudanca = $row->hora_inicio_mudanca;
			$this->minuto_inicio_mudanca = $row->minuto_inicio_mudanca;
			$this->hora_fim_mudanca = $row->hora_fim_mudanca;
			$this->minuto_fim_mudanca = $row->minuto_fim_mudanca;
			$this->dia_semana_inicial = $row->dia_semana_inicial;
			$this->dia_semana_final = $row->dia_semana_final;
			$this->limite_para_rdm = $row->limite_para_rdm;
		}
		
	}
	
	function selectByIC_X($id){ 
		
		$sql = " SELECT JM.seq_janela_mudanca, dsc_janela_mudanca, hora_inicio_mudanca, 
		minuto_inicio_mudanca, hora_fim_mudanca, minuto_fim_mudanca, 
		dia_semana_inicial, dia_semana_final,limite_para_rdm 
		FROM gestaoti.janela_mudanca JM, gestaoti.janela_mudacao_item_configuracao JMI
		where 
		JM.seq_janela_mudanca = JMI.seq_janela_mudanca
		AND seq_item_configuracao =$id";
		$sql .= " UNION
		SELECT JM.seq_janela_mudanca, dsc_janela_mudanca, hora_inicio_mudanca, 
		minuto_inicio_mudanca, hora_fim_mudanca, minuto_fim_mudanca, 
		dia_semana_inicial, dia_semana_final,limite_para_rdm 
		FROM gestaoti.janela_mudanca JM, gestaoti.janela_mudanca_servidor JMS
		where 
		JM.seq_janela_mudanca = JMS.seq_janela_mudanca
		AND seq_servidor =$id";
		
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->seq_janela_mudanca = $row->seq_janela_mudanca;
		$this->dsc_janela_mudanca = $row->dsc_janela_mudanca;
		$this->hora_inicio_mudanca = $row->hora_inicio_mudanca;
		$this->minuto_inicio_mudanca = $row->minuto_inicio_mudanca;
		$this->hora_fim_mudanca = $row->hora_fim_mudanca;
		$this->minuto_fim_mudanca = $row->minuto_fim_mudanca;
		$this->dia_semana_inicial = $row->dia_semana_inicial;
		$this->dia_semana_final = $row->dia_semana_final;
		$this->limite_para_rdm = $row->limite_para_rdm;
		
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