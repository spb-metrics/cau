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
* Nome da Classe:	historico_chamado
* Nome da tabela:	historico_chamado
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
	include_once("../gestaoti/include/PHP/class/class.situacao_chamado.php");
	include_once "../gestaoti/include/PHP/class/class.pagina.php";
}else{
	include_once("include/PHP/class/class.database.postgres.php");
	include_once("include/PHP/class/class.situacao_chamado.php");
	include_once("include/PHP/class/class.pagina.php");
}

// **********************
// DECLARAÇÃO DA CLASSE
// **********************
class historico_chamado{
	// class : begin

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_HISTORICO_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $SEQ_CHAMADO;   // (normal Attribute)
	var $NUM_MATRICULA;   // (normal Attribute)
	var $DTH_HISTORICO;   // (normal Attribute)
	var $SEQ_SITUACAO_CHAMADO;   // (normal Attribute)
	var $SEQ_MOTIVO_SUSPENCAO;   // (normal Attribute)
	var $TXT_HISTORICO;   // (normal Attribute)
	var $situacao_chamado;
	var $pagina;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function historico_chamado(){
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

	function getSEQ_HISTORICO_CHAMADO(){
		return $this->SEQ_HISTORICO_CHAMADO;
	}

	function getSEQ_CHAMADO(){
		return $this->SEQ_CHAMADO;
	}

	function getNUM_MATRICULA(){
		return $this->NUM_MATRICULA;
	}

	function getDTH_HISTORICO(){
		return $this->DTH_HISTORICO;
	}

	function getSEQ_SITUACAO_CHAMADO(){
		return $this->SEQ_SITUACAO_CHAMADO;
	}

	function getSEQ_MOTIVO_SUSPENCAO(){
		return $this->SEQ_MOTIVO_SUSPENCAO;
	}

	function getTXT_HISTORICO(){
		return $this->TXT_HISTORICO;
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

	function setSEQ_HISTORICO_CHAMADO($val){
		$this->SEQ_HISTORICO_CHAMADO =  $val;
	}

	function setSEQ_CHAMADO($val){
		$this->SEQ_CHAMADO =  $val;
	}

	function setNUM_MATRICULA($val){
		$this->NUM_MATRICULA =  $val;
	}

	function setDTH_HISTORICO($val){
		$this->DTH_HISTORICO =  $val;
	}

	function setSEQ_SITUACAO_CHAMADO($val){
		$this->SEQ_SITUACAO_CHAMADO =  $val;
	}

	function setSEQ_MOTIVO_SUSPENCAO($val){
		$this->SEQ_MOTIVO_SUSPENCAO =  $val;
	}

	function setTXT_HISTORICO($val){
		$this->TXT_HISTORICO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_HISTORICO_CHAMADO , SEQ_CHAMADO , NUM_MATRICULA , to_char(DTH_HISTORICO, 'dd/mm/yyyy hh24:mi:ss') as DTH_HISTORICO,
					   DTH_HISTORICO as DTH_HISTORICO_DATA , SEQ_SITUACAO_CHAMADO , SEQ_MOTIVO_SUSPENCAO , TXT_HISTORICO
			    FROM gestaoti.historico_chamado
				WHERE SEQ_HISTORICO_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_HISTORICO_CHAMADO = $row->seq_historico_chamado;
		$this->SEQ_CHAMADO = $row->seq_chamado;
		$this->NUM_MATRICULA = $row->num_matricula;
		$this->DTH_HISTORICO = $row->dth_historico;
		$this->SEQ_SITUACAO_CHAMADO = $row->seq_situacao_chamado;
		$this->SEQ_MOTIVO_SUSPENCAO = $row->seq_motivo_suspencao;
		$this->TXT_HISTORICO = $row->txt_historico;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_HISTORICO_CHAMADO, SEQ_CHAMADO, NUM_MATRICULA,
					         to_char(DTH_HISTORICO, 'dd/mm/yyyy hh24:mi:ss') as DTH_HISTORICO, DTH_HISTORICO as DTH_HISTORICO_DATA,
					         a.SEQ_SITUACAO_CHAMADO, a.SEQ_MOTIVO_SUSPENCAO, TXT_HISTORICO, b.DSC_SITUACAO_CHAMADO,
						     c.DSC_MOTIVO_SUSPENCAO ";
		$sqlCorpo  = "FROM gestaoti.historico_chamado a LEFT OUTER JOIN gestaoti.motivo_suspencao c on (a.SEQ_MOTIVO_SUSPENCAO = c.SEQ_MOTIVO_SUSPENCAO), gestaoti.situacao_chamado b
					  WHERE a.SEQ_SITUACAO_CHAMADO = b.SEQ_SITUACAO_CHAMADO";

		if($this->SEQ_HISTORICO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_HISTORICO_CHAMADO = $this->SEQ_HISTORICO_CHAMADO ";
		}
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NUM_MATRICULA != ""){
			$sqlCorpo .= "  and NUM_MATRICULA = $this->NUM_MATRICULA ";
		}
		if($this->DTH_HISTORICO != "" && $this->DTH_HISTORICO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_HISTORICO >= to_date('".$this->DTH_HISTORICO."', 'dd/mm/yyyy') ";
		}
		if($this->DTH_HISTORICO != "" && $this->DTH_HISTORICO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_HISTORICO between to_date('".$this->DTH_HISTORICO."', 'dd/mm/yyyy') and to_date('".$this->DTH_HISTORICO_FINAL."', 'dd/mm/yyyy') ";
		}
		if($this->DTH_HISTORICO == "" && $this->DTH_HISTORICO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_HISTORICO <= to_date('".$this->DTH_HISTORICO_FINAL."', 'dd/mm/yyyy') ";
		}
		if($this->SEQ_SITUACAO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SITUACAO_CHAMADO = $this->SEQ_SITUACAO_CHAMADO ";
		}
		if($this->SEQ_MOTIVO_SUSPENCAO != ""){
			$sqlCorpo .= "  and SEQ_MOTIVO_SUSPENCAO = $this->SEQ_MOTIVO_SUSPENCAO ";
		}
		if($this->TXT_HISTORICO != ""){
			$sqlCorpo .= "  and upper(TXT_HISTORICO) like '%".strtoupper($this->TXT_HISTORICO)."%'  ";
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
		$sql = "DELETE FROM gestaoti.historico_chamado WHERE SEQ_HISTORICO_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************

	function insert(){
		$this->SEQ_HISTORICO_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_HISTORICO_CHAMADO");

		$sql = "INSERT INTO gestaoti.historico_chamado(SEQ_HISTORICO_CHAMADO,
										  SEQ_CHAMADO,
										  NUM_MATRICULA,
										  DTH_HISTORICO,
										  SEQ_SITUACAO_CHAMADO,
										  SEQ_MOTIVO_SUSPENCAO,
										  TXT_HISTORICO
									)
							 VALUES (".$this->iif($this->SEQ_HISTORICO_CHAMADO=="", "NULL", "'".$this->SEQ_HISTORICO_CHAMADO."'").",
									 ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
									 ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
									 '".date("Y-m-d H:i:s")."',
									 ".$this->iif($this->SEQ_SITUACAO_CHAMADO=="", "NULL", "'".$this->SEQ_SITUACAO_CHAMADO."'").",
									 ".$this->iif($this->SEQ_MOTIVO_SUSPENCAO=="", "NULL", "'".$this->SEQ_MOTIVO_SUSPENCAO."'").",
									 ".$this->iif($this->TXT_HISTORICO=="", "NULL", "'".$this->TXT_HISTORICO."'")."
							 		) ";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.historico_chamado
				 SET SEQ_CHAMADO = ".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
					 NUM_MATRICULA = ".$this->iif($this->NUM_MATRICULA=="", "NULL", "'".$this->NUM_MATRICULA."'").",
					 DTH_HISTORICO = ".$this->iif($this->DTH_HISTORICO=="", "NULL", "to_date('".$this->DTH_HISTORICO."', 'dd/mm/yyyy')").",
					 SEQ_SITUACAO_CHAMADO = ".$this->iif($this->SEQ_SITUACAO_CHAMADO=="", "NULL", "'".$this->SEQ_SITUACAO_CHAMADO."'").",
					 SEQ_MOTIVO_SUSPENCAO = ".$this->iif($this->SEQ_MOTIVO_SUSPENCAO=="", "NULL", "'".$this->SEQ_MOTIVO_SUSPENCAO."'").",
					 TXT_HISTORICO = ".$this->iif($this->TXT_HISTORICO=="", "NULL", "'".$this->TXT_HISTORICO."'")."
				WHERE SEQ_HISTORICO_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}

	function GetUltimaSituacao($v_SEQ_CHAMADO){
		$sql = "select SEQ_SITUACAO_CHAMADO
				FROM gestaoti.historico_chamado a
				where seq_chamado = $v_SEQ_CHAMADO
				and SEQ_HISTORICO_CHAMADO = (select max(SEQ_HISTORICO_CHAMADO)
				                             FROM gestaoti.historico_chamado b
				                             where b.SEQ_CHAMADO = a.SEQ_CHAMADO)";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		return $row->seq_situacao_chamado;
	}


	function GetDTHUltimaSituacao($v_SEQ_CHAMADO){
		$sql = "select to_char(DTH_HISTORICO, 'dd/mm/yyyy hh24:mi:ss') as DTH_HISTORICO
				FROM gestaoti.historico_chamado a
				where seq_chamado = $v_SEQ_CHAMADO
				and SEQ_HISTORICO_CHAMADO = (select max(SEQ_HISTORICO_CHAMADO)
				                             FROM gestaoti.historico_chamado b
				                             where b.SEQ_CHAMADO = a.SEQ_CHAMADO)";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		return $row->dth_historico;
	}

	// Método que verifica se o chamado já foi contingenciado. Caso posiitivo retorna 1
	function GetFlgAtendimentoContingenciado($v_SEQ_CHAMADO){
		$this->situacao_chamado = new situacao_chamado();
		$sql = "select  1
				from gestaoti.historico_chamado a
				where a.SEQ_CHAMADO = $v_SEQ_CHAMADO
				and a.SEQ_SITUACAO_CHAMADO = ".$this->situacao_chamado->COD_Contingenciado." -- Contingenciado
				and not exists (select 1 from gestaoti.historico_chamado b
						where b.SEQ_CHAMADO = a.SEQ_CHAMADO
						and b.SEQ_SITUACAO_CHAMADO in (".$this->situacao_chamado->COD_Em_Andamento.", ".$this->situacao_chamado->COD_Aguardando_Atendimento.") -- Em andamento e aguardando atendimento (Reaberto pelo cliente)
						and b.SEQ_HISTORICO_CHAMADO > a.SEQ_HISTORICO_CHAMADO
						)";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		if($this->database->rows > 0){
			return true;
		}else{
			return false;
		}
	}

	// Método que verifica se o chamado já foi contingenciado. Caso posiitivo retorna 1
	function GetDthContingenciamento($v_SEQ_CHAMADO){
		$this->situacao_chamado = new situacao_chamado();
		$sql = "select to_char(dth_historico, 'dd/mm/yyyy hh24:mi:ss') as dth_historico
				from gestaoti.historico_chamado a
				where a.SEQ_CHAMADO = $v_SEQ_CHAMADO
				and a.SEQ_SITUACAO_CHAMADO = ".$this->situacao_chamado->COD_Contingenciado." -- Contingenciado
				and not exists (select 1 from gestaoti.historico_chamado b
						where b.SEQ_CHAMADO = a.SEQ_CHAMADO
						and b.SEQ_SITUACAO_CHAMADO in (".$this->situacao_chamado->COD_Em_Andamento.", ".$this->situacao_chamado->COD_Aguardando_Atendimento.") -- Em andamento e aguardando atendimento (Reaberto pelo cliente)
						and b.SEQ_HISTORICO_CHAMADO > a.SEQ_HISTORICO_CHAMADO
						)";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		if($this->database->rows > 0){
			$row = pg_fetch_array($this->database->result);
			return $row["dth_historico"];
		}else{
			return "";
		}
	}

	function GetQtdMinutosSuspensaMinutosCorridos($v_SEQ_CHAMADO){
		$this->situacao_chamado = new situacao_chamado();
		$this->pagina = new pagina();

		$sql = "select  to_char(dth_historico, 'dd/mm/yyyy hh24:mi:ss') as dth_historico,
						( select to_char(dth_historico, 'dd/mm/yyyy hh24:mi:ss') as dth_historico
						  from gestaoti.historico_chamado
						  where SEQ_CHAMADO = a.SEQ_CHAMADO
						  and SEQ_HISTORICO_CHAMADO > a.SEQ_HISTORICO_CHAMADO
						  order by SEQ_HISTORICO_CHAMADO asc
						  limit 1 offset 0
					        ) as dth_historico_seguinte
				from gestaoti.historico_chamado a
				where SEQ_CHAMADO = $v_SEQ_CHAMADO
				and SEQ_SITUACAO_CHAMADO = ".$this->situacao_chamado->COD_Suspenca."
				order by SEQ_HISTORICO_CHAMADO";
		$result = $this->database->query($sql);
		$result = $this->database->result;

		$somaMinutos = 0;
		if($this->database->rows > 0){
			while ($row = pg_fetch_array($this->database->result)){
				if($row["dth_historico_seguinte"] != ""){
					// Verificar quantos minutos corridos entre as datas
					$auxSoma += $this->pagina->dateDiffMinutes($row["dth_historico"], $row["dth_historico_seguinte"]);
					$somaMinutos += $auxSoma;
					//print "<br><br>somaMinutos=".$auxSoma;
					//print "<br>dth_historico=".$row["dth_historico"]." | dth_historico_seguinte=".$row["dth_historico_seguinte"]." | somaMinutos=".$somaMinutos;
				}else{
					$auxSoma += $this->pagina->dateDiffMinutes($row["dth_historico"], date("d/m/Y H:i:s"));
					$somaMinutos += $auxSoma;
					//print "<br><br>somaMinutos=".$auxSoma;
					//print "<br>dth_historico=".$row["dth_historico"]." | dth_historico_seguinte=".date("d/m/Y H:i:s")." | somaMinutos=".$somaMinutos;
				}
			}
		}
		return $somaMinutos;
	}

	function GetQtdMinutosSuspensaMinutosUteis($v_SEQ_CHAMADO, $HoraInicioExpediente, $HoraInicioIntervalo, $HoraFimIntervalo, $HoraFimExpediente, $aDtFeriado){
		$this->situacao_chamado = new situacao_chamado();
		$this->pagina = new pagina();

		$sql = "select  to_char(dth_historico, 'dd/mm/yyyy hh24:mi:ss') as dth_historico,
						( select to_char(dth_historico, 'dd/mm/yyyy hh24:mi:ss') as dth_historico
						  from gestaoti.historico_chamado
						  where SEQ_CHAMADO = a.SEQ_CHAMADO
						  and SEQ_HISTORICO_CHAMADO > a.SEQ_HISTORICO_CHAMADO
						  order by SEQ_HISTORICO_CHAMADO asc
						  limit 1 offset 0
					        ) as dth_historico_seguinte
				from gestaoti.historico_chamado a
				where SEQ_CHAMADO = $v_SEQ_CHAMADO
				and SEQ_SITUACAO_CHAMADO = ".$this->situacao_chamado->COD_Suspenca."
				order by SEQ_HISTORICO_CHAMADO";
		$result = $this->database->query($sql);
		$result = $this->database->result;

		$somaMinutos = 0;
		if($this->database->rows > 0){
			while ($row = pg_fetch_array($this->database->result)){
				if($row["dth_historico_seguinte"] != ""){
					// Verificar quantos minutos corridos entre as datas
					$auxSoma += $this->pagina->dateDiffMinutosUteis($row["dth_historico"], $row["dth_historico_seguinte"], $HoraInicioExpediente, $HoraInicioIntervalo, $HoraFimIntervalo, $HoraFimExpediente, $aDtFeriado);
					$somaMinutos += $auxSoma;
					//print "<br><br>somaMinutos=".$auxSoma;
					//print "<br>dth_historico=".$row["dth_historico"]." | dth_historico_seguinte=".$row["dth_historico_seguinte"]." | somaMinutos=".$somaMinutos;
				}else{
					$auxSoma += $this->pagina->dateDiffMinutosUteis($row["dth_historico"], date("d/m/Y H:i:s"), $HoraInicioExpediente, $HoraInicioIntervalo, $HoraFimIntervalo, $HoraFimExpediente, $aDtFeriado);
					$somaMinutos += $auxSoma;
					//print "<br><br>somaMinutos=".$auxSoma;
					//print "<br>dth_historico=".$row["dth_historico"]." | dth_historico_seguinte=".date("d/m/Y H:i:s")." | somaMinutos=".$somaMinutos;
				}
			}
		}
		return $somaMinutos;
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