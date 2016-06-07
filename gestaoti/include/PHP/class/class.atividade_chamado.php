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
* Nome da Classe:	atividade_chamado
* Nome da tabela:	atividade_chamado
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
class atividade_chamado{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_ATIVIDADE_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $SEQ_ATIVIDADE_CHAMADO_REMOVER;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_TIPO_CHAMADO;   // (normal Attribute)
	var $SEQ_SUBTIPO_CHAMADO;   // (normal Attribute)
	var $DSC_ATIVIDADE_CHAMADO;   // (normal Attribute)
	var $QTD_MIN_SLA_TRIAGEM;   // (normal Attribute)
	var $QTD_MIN_SLA_ATENDIMENTO;   // (normal Attribute)
	var $QTD_MIN_SLA_SOLUCAO_FINAL;   // (normal Attribute)
	var $FLG_ATENDIMENTO_EXTERNO;   // (normal Attribute)
	var $FLG_FORMA_MEDICAO_TEMPO;
	var $SEQ_EQUIPE_TI;
	var $TXT_ATIVIDADE;
	var $SEQ_TIPO_OCORRENCIA;
	var $SEQ_CENTRAL_ATENDIMENTO;
	var $NUM_MATRICULA_APROVADOR;
 	var $NUM_MATRICULA_APROVADOR_SUBSTITUTO;
 	var $FLG_EXIGE_APROVACAO;
 	 

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	var $parametro;

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function atividade_chamado(){
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

	function getSEQ_ATIVIDADE_CHAMADO(){
		return $this->SEQ_ATIVIDADE_CHAMADO;
	}
	
	function getSEQ_ATIVIDADE_CHAMADO_REMOVER(){
		return $this->SEQ_ATIVIDADE_CHAMADO_REMOVER;
	}
	
	function getSEQ_TIPO_CHAMADO(){
		return $this->SEQ_TIPO_CHAMADO;
	}
	

	function getSEQ_SUBTIPO_CHAMADO(){
		return $this->SEQ_SUBTIPO_CHAMADO;
	}

	function getDSC_ATIVIDADE_CHAMADO(){
		return $this->DSC_ATIVIDADE_CHAMADO;
	}

	function getQTD_MIN_SLA_TRIAGEM(){
		return $this->QTD_MIN_SLA_TRIAGEM;
	}

	function getQTD_MIN_SLA_ATENDIMENTO(){
		return $this->QTD_MIN_SLA_ATENDIMENTO;
	}

	function getFLG_ATENDIMENTO_EXTERNO(){
		return $this->FLG_ATENDIMENTO_EXTERNO;
	}

	function getFLG_FORMA_MEDICAO_TEMPO(){
		return $this->FLG_FORMA_MEDICAO_TEMPO;
	}

	function getSEQ_CENTRAL_ATENDIMENTO(){
		return $this->SEQ_CENTRAL_ATENDIMENTO;
	}
	
	function getNUM_MATRICULA_APROVADOR_SUBSTITUTO(){
		return $this->NUM_MATRICULA_APROVADOR_SUBSTITUTO;
	}
	
	function getNUM_MATRICULA_APROVADOR(){
		return $this->NUM_MATRICULA_APROVADOR;
	}
	
	function getFLG_EXIGE_APROVACAO(){
		return $this->FLG_EXIGE_APROVACAO;
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

	function setSEQ_ATIVIDADE_CHAMADO($val){
		$this->SEQ_ATIVIDADE_CHAMADO =  $val;
	}
	
	function setSEQ_ATIVIDADE_CHAMADO_REMOVER($val){
		$this->SEQ_ATIVIDADE_CHAMADO_REMOVER =  $val;
	}
	
	function setSEQ_TIPO_CHAMADO($val){
		$this->SEQ_TIPO_CHAMADO =  $val;
	}

	function setSEQ_SUBTIPO_CHAMADO($val){
		$this->SEQ_SUBTIPO_CHAMADO =  $val;
	}

	function setDSC_ATIVIDADE_CHAMADO($val){
		$this->DSC_ATIVIDADE_CHAMADO =  $val;
	}

	function setQTD_MIN_SLA_TRIAGEM($val){
		$this->QTD_MIN_SLA_TRIAGEM =  $val;
	}

	function setQTD_MIN_SLA_ATENDIMENTO($val){
		$this->QTD_MIN_SLA_ATENDIMENTO =  $val;
	}

	function setFLG_ATENDIMENTO_EXTERNO($val){
		$this->FLG_ATENDIMENTO_EXTERNO =  $val;
	}

	function setFLG_FORMA_MEDICAO_TEMPO($val){
		$this->FLG_FORMA_MEDICAO_TEMPO =  $val;
	}

	function setQTD_MIN_SLA_SOLUCAO_FINAL($val){
		$this->QTD_MIN_SLA_SOLUCAO_FINAL =  $val;
	}

	function setSEQ_EQUIPE_TI($val){
		$this->SEQ_EQUIPE_TI =  $val;
	}

	function setTXT_ATIVIDADE($val){
		$this->TXT_ATIVIDADE =  $val;
	}
	function setSEQ_TIPO_OCORRENCIA($val){
		$this->SEQ_TIPO_OCORRENCIA =  $val;
	}

	function setSEQ_CENTRAL_ATENDIMENTO($val){
		$this->SEQ_CENTRAL_ATENDIMENTO =  $val;
	}	
	
	function setNUM_MATRICULA_APROVADOR($val){
		$this->NUM_MATRICULA_APROVADOR =  $val;
	}
	
	function setNUM_MATRICULA_APROVADOR_SUBSTITUTO($val){
		$this->NUM_MATRICULA_APROVADOR_SUBSTITUTO =  $val;
	}
	
	function setFLG_EXIGE_APROVACAO($val){
		$this->FLG_EXIGE_APROVACAO =  $val;
	}
	 
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_ATIVIDADE_CHAMADO , SEQ_SUBTIPO_CHAMADO , DSC_ATIVIDADE_CHAMADO , QTD_MIN_SLA_TRIAGEM , QTD_MIN_SLA_ATENDIMENTO ,
					   FLG_ATENDIMENTO_EXTERNO, FLG_FORMA_MEDICAO_TEMPO, QTD_MIN_SLA_SOLUCAO_FINAL, SEQ_EQUIPE_TI, TXT_ATIVIDADE,
					   SEQ_TIPO_OCORRENCIA,FLG_EXIGE_APROVACAO,NUM_MATRICULA_APROVADOR,NUM_MATRICULA_APROVADOR_SUBSTITUTO
			    FROM gestaoti.atividade_chamado
				WHERE SEQ_ATIVIDADE_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_ATIVIDADE_CHAMADO = $row->seq_atividade_chamado;
		$this->SEQ_SUBTIPO_CHAMADO = $row->seq_subtipo_chamado;
		$this->DSC_ATIVIDADE_CHAMADO = $row->dsc_atividade_chamado;
		$this->QTD_MIN_SLA_TRIAGEM = $row->qtd_min_sla_triagem;
		$this->QTD_MIN_SLA_ATENDIMENTO = $row->qtd_min_sla_atendimento;
		$this->QTD_MIN_SLA_SOLUCAO_FINAL = $row->qtd_min_sla_solucao_final;
		$this->FLG_ATENDIMENTO_EXTERNO = $row->flg_atendimento_externo;
		$this->FLG_FORMA_MEDICAO_TEMPO = $row->flg_forma_medicao_tempo;
		$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
		$this->TXT_ATIVIDADE = $row->txt_atividade;
		$this->SEQ_TIPO_OCORRENCIA = $row->seq_tipo_ocorrencia;
		$this->NUM_MATRICULA_APROVADOR = $row->num_matricula_aprovador;
		$this->NUM_MATRICULA_APROVADOR_SUBSTITUTO = $row->num_matricula_aprovador_substituto;
		$this->FLG_EXIGE_APROVACAO = $row->flg_exige_aprovacao;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_ATIVIDADE_CHAMADO , a.SEQ_SUBTIPO_CHAMADO , DSC_ATIVIDADE_CHAMADO , QTD_MIN_SLA_TRIAGEM , QTD_MIN_SLA_ATENDIMENTO ,
							 QTD_MIN_SLA_SOLUCAO_FINAL, a.FLG_ATENDIMENTO_EXTERNO, FLG_FORMA_MEDICAO_TEMPO, SEQ_EQUIPE_TI, TXT_ATIVIDADE,
							 SEQ_TIPO_OCORRENCIA,FLG_EXIGE_APROVACAO,NUM_MATRICULA_APROVADOR,NUM_MATRICULA_APROVADOR_SUBSTITUTO ";
		$sqlCorpo  = "FROM gestaoti.atividade_chamado a ";
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo  .= ", gestaoti.subtipo_chamado b,gestaoti.tipo_chamado c WHERE 1=1 ";
		}else if($this->SEQ_TIPO_CHAMADO != ""){
			$sqlCorpo  .= ", gestaoti.subtipo_chamado b,gestaoti.tipo_chamado c WHERE 1=1 ";
		}else{
			$sqlCorpo  .= " WHERE 1=1 ";
		}

		if($this->SEQ_ATIVIDADE_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_ATIVIDADE_CHAMADO = $this->SEQ_ATIVIDADE_CHAMADO ";
		}
		if($this->SEQ_ATIVIDADE_CHAMADO_REMOVER != ""){
			$sqlCorpo .= "  and SEQ_ATIVIDADE_CHAMADO <> $this->SEQ_ATIVIDADE_CHAMADO_REMOVER ";
		}
		
		if($this->SEQ_SUBTIPO_CHAMADO != ""){
			$sqlCorpo .= "  and a.SEQ_SUBTIPO_CHAMADO = $this->SEQ_SUBTIPO_CHAMADO ";
		}
		if($this->DSC_ATIVIDADE_CHAMADO != ""){
			$sqlCorpo .= "  and upper(DSC_ATIVIDADE_CHAMADO) like '%".strtoupper($this->DSC_ATIVIDADE_CHAMADO)."%'  ";
		}
		if($this->QTD_MIN_SLA_TRIAGEM != ""){
			$sqlCorpo .= "  and QTD_MIN_SLA_TRIAGEM = $this->QTD_MIN_SLA_TRIAGEM ";
		}
		if($this->QTD_MIN_SLA_ATENDIMENTO != ""){
			$sqlCorpo .= "  and QTD_MIN_SLA_ATENDIMENTO = $this->QTD_MIN_SLA_ATENDIMENTO ";
		}
		if($this->FLG_ATENDIMENTO_EXTERNO != ""){
			$sqlCorpo .= "  and FLG_ATENDIMENTO_EXTERNO = '$this->FLG_ATENDIMENTO_EXTERNO' ";
		}
		if($this->FLG_FORMA_MEDICAO_TEMPO != ""){
			$sqlCorpo .= "  and FLG_FORMA_MEDICAO_TEMPO = '$this->FLG_FORMA_MEDICAO_TEMPO' ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_TI = '$this->SEQ_EQUIPE_TI' ";
		}
		if($this->SEQ_TIPO_OCORRENCIA != ""){
			$sqlCorpo .= "  and SEQ_TIPO_OCORRENCIA = '$this->SEQ_TIPO_OCORRENCIA' ";
		}
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo .= " and  a.SEQ_SUBTIPO_CHAMADO = b.SEQ_SUBTIPO_CHAMADO and 
			b.SEQ_TIPO_CHAMADO = c.SEQ_TIPO_CHAMADO  and 
			c.seq_central_atendimento = '$this->SEQ_CENTRAL_ATENDIMENTO' ";
		}
		
		if($this->SEQ_TIPO_CHAMADO != ""){
			$sqlCorpo .= " and  a.SEQ_SUBTIPO_CHAMADO = b.SEQ_SUBTIPO_CHAMADO and 
			b.SEQ_TIPO_CHAMADO = c.SEQ_TIPO_CHAMADO  and 
			c.SEQ_TIPO_CHAMADO = '$this->SEQ_TIPO_CHAMADO' ";
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
		$sql = "DELETE FROM gestaoti.atividade_chamado WHERE SEQ_ATIVIDADE_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_ATIVIDADE_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_ATIVIDADE_CHAMADO");

		$sql = "INSERT INTO gestaoti.atividade_chamado(SEQ_ATIVIDADE_CHAMADO,
										  SEQ_SUBTIPO_CHAMADO,
										  DSC_ATIVIDADE_CHAMADO,
										  QTD_MIN_SLA_TRIAGEM,
										  QTD_MIN_SLA_ATENDIMENTO,
										  QTD_MIN_SLA_SOLUCAO_FINAL,
										  FLG_ATENDIMENTO_EXTERNO,
										  FLG_FORMA_MEDICAO_TEMPO,
										  SEQ_EQUIPE_TI,
										  TXT_ATIVIDADE,
										  SEQ_TIPO_OCORRENCIA,
										  FLG_EXIGE_APROVACAO,
										  NUM_MATRICULA_APROVADOR,
										  NUM_MATRICULA_APROVADOR_SUBSTITUTO
									)
							 VALUES (".$this->iif($this->SEQ_ATIVIDADE_CHAMADO=="", "NULL", "'".$this->SEQ_ATIVIDADE_CHAMADO."'").",
									 ".$this->iif($this->SEQ_SUBTIPO_CHAMADO=="", "NULL", "'".$this->SEQ_SUBTIPO_CHAMADO."'").",
									 ".$this->iif($this->DSC_ATIVIDADE_CHAMADO=="", "NULL", "'".$this->DSC_ATIVIDADE_CHAMADO."'").",
									 ".$this->iif($this->QTD_MIN_SLA_TRIAGEM=="", "NULL", "'".$this->QTD_MIN_SLA_TRIAGEM."'").",
									 ".$this->iif($this->QTD_MIN_SLA_ATENDIMENTO=="", "NULL", "'".$this->QTD_MIN_SLA_ATENDIMENTO."'").",
									 ".$this->iif($this->QTD_MIN_SLA_SOLUCAO_FINAL=="", "NULL", "'".$this->QTD_MIN_SLA_SOLUCAO_FINAL."'").",
									 ".$this->iif($this->FLG_ATENDIMENTO_EXTERNO=="", "NULL", "'".$this->FLG_ATENDIMENTO_EXTERNO."'").",
									 ".$this->iif($this->FLG_FORMA_MEDICAO_TEMPO=="", "NULL", "'".$this->FLG_FORMA_MEDICAO_TEMPO."'").",
									 ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
									 ".$this->iif($this->TXT_ATIVIDADE=="", "NULL", "'".$this->TXT_ATIVIDADE."'").",
									 ".$this->iif($this->SEQ_TIPO_OCORRENCIA=="", "NULL", "'".$this->SEQ_TIPO_OCORRENCIA."'").",
									 ".$this->iif($this->FLG_EXIGE_APROVACAO=="", "NULL", "'".$this->FLG_EXIGE_APROVACAO."'").",
									 ".$this->iif($this->NUM_MATRICULA_APROVADOR=="", "NULL", "'".$this->NUM_MATRICULA_APROVADOR."'").",
									 ".$this->iif($this->NUM_MATRICULA_APROVADOR_SUBSTITUTO=="", "NULL", "'".$this->NUM_MATRICULA_APROVADOR_SUBSTITUTO."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.atividade_chamado
				 SET SEQ_SUBTIPO_CHAMADO = ".$this->iif($this->SEQ_SUBTIPO_CHAMADO=="", "NULL", "'".$this->SEQ_SUBTIPO_CHAMADO."'").",
					 DSC_ATIVIDADE_CHAMADO = ".$this->iif($this->DSC_ATIVIDADE_CHAMADO=="", "NULL", "'".$this->DSC_ATIVIDADE_CHAMADO."'").",
					 QTD_MIN_SLA_TRIAGEM = ".$this->iif($this->QTD_MIN_SLA_TRIAGEM=="", "NULL", "'".$this->QTD_MIN_SLA_TRIAGEM."'").",
					 QTD_MIN_SLA_ATENDIMENTO = ".$this->iif($this->QTD_MIN_SLA_ATENDIMENTO=="", "NULL", "'".$this->QTD_MIN_SLA_ATENDIMENTO."'").",
					 QTD_MIN_SLA_SOLUCAO_FINAL = ".$this->iif($this->QTD_MIN_SLA_SOLUCAO_FINAL=="", "NULL", "'".$this->QTD_MIN_SLA_SOLUCAO_FINAL."'").",
					 FLG_ATENDIMENTO_EXTERNO = ".$this->iif($this->FLG_ATENDIMENTO_EXTERNO=="", "NULL", "'".$this->FLG_ATENDIMENTO_EXTERNO."'").",
					 FLG_FORMA_MEDICAO_TEMPO = ".$this->iif($this->FLG_FORMA_MEDICAO_TEMPO=="", "NULL", "'".$this->FLG_FORMA_MEDICAO_TEMPO."'").",
					 SEQ_EQUIPE_TI = ".$this->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'").",
					 TXT_ATIVIDADE = ".$this->iif($this->TXT_ATIVIDADE=="", "NULL", "'".$this->TXT_ATIVIDADE."'").",
					 SEQ_TIPO_OCORRENCIA = ".$this->iif($this->SEQ_TIPO_OCORRENCIA=="", "NULL", "'".$this->SEQ_TIPO_OCORRENCIA."'").",
					 FLG_EXIGE_APROVACAO = ".$this->iif($this->FLG_EXIGE_APROVACAO=="", "NULL", "'".$this->FLG_EXIGE_APROVACAO."'").",
					 NUM_MATRICULA_APROVADOR = ".$this->iif($this->NUM_MATRICULA_APROVADOR=="", "NULL", "'".$this->NUM_MATRICULA_APROVADOR."'").",
					 NUM_MATRICULA_APROVADOR_SUBSTITUTO = ".$this->iif($this->NUM_MATRICULA_APROVADOR_SUBSTITUTO=="", "NULL", "'".$this->NUM_MATRICULA_APROVADOR_SUBSTITUTO."'")."
				WHERE SEQ_ATIVIDADE_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row["seq_atividade_chamado"], $this->iif($vSelected == $row["seq_atividade_chamado"],"Selected", ""), $row["dsc_atividade_chamado"]);
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