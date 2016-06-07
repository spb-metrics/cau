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
include_once("include/PHP/class/class.database.sqlserver.epm.php");

// **********************
// CLASS DECLARATION
// **********************
class atividades{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $COD_PROJETO;

	var $SEQ_ATIVIDADE;
	var $COD_ATIVIDADE;
	var $NUM_NIVEL;
	var $NOM_ATIVIDADE;
	var $PER_COMPLETA;
	var $PER_FISICA_COMPLETA;
	var $Predecessoras;
	var $Sucessoras;
	var $QTD_DURACAO;
	var $DAT_INICIO_PREVISTA;
	var $DAT_FINAL_PREVISTA;
	var $QTD_DURACAO_RESTANTE;
	var $DAT_INICIO_CEDO;
	var $DAT_FINAL_CEDO;
	var $DAT_INICIO_TARDE;
	var $DAT_FINAL_TARDE;
	var $QTD_FREE_SLACK;
	var $TaskHyperlinkHref;
	var $QTD_DURACAO_REAL;
	var $DAT_INICIO_REAL;
	var $DAT_FINAL_REAL;
	var $QTD_DURACAO_BASELINE;
	var $DAT_INICIO_BASELINE;
	var $DAT_FINAL_BASELINE;
	var $DAT_CRIACAO;
	var $FLG_MARCO;
	var $NOM_RECURSOS;
	var $SIG_AREA_RECURSO;
	var $SPI;
	var $TCPI;
	var $CPI;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function atividades(){
		$this->database = new DatabaseEPM();
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
	// **********************
	// SETTER METHODS
	// **********************
	function setrowCount($val){
		$this->rowCount = $val;
	}
	function setvQtdRegistros($val){
		$this->vQtdRegistros = $val;
	}
	function setCOD_PROJETO($val){
		$this->COD_PROJETO =  $val;
	}
	function setSEQ_ATIVIDADE($val){
		$this->SEQ_ATIVIDADE =  $val;
	}
	function setCOD_ATIVIDADE($val){
		$this->COD_ATIVIDADE =  $val;
	}
	function setNUM_NIVEL($val){
		$this->NUM_NIVEL =  $val;
	}
	function setNOM_ATIVIDADE($val){
		$this->NOM_ATIVIDADE =  $val;
	}
	function setPER_COMPLETA($val){
		$this->PER_COMPLETA =  $val;
	}
	function setPER_FISICA_COMPLETA($val){
		$this->PER_FISICA_COMPLETA =  $val;
	}
	function setPredecessoras($val){
		$this->Predecessoras =  $val;
	}
	function setSucessoras($val){
		$this->Sucessoras =  $val;
	}
	function setQTD_DURACAO($val){
		$this->QTD_DURACAO =  $val;
	}
	function setDAT_INICIO_PREVISTA($val){
		$this->DAT_INICIO_PREVISTA =  $val;
	}
	function setDAT_FINAL_PREVISTA($val){
		$this->DAT_FINAL_PREVISTA =  $val;
	}
	function setQTD_DURACAO_RESTANTE($val){
		$this->QTD_DURACAO_RESTANTE =  $val;
	}
	function setDAT_INICIO_CEDO($val){
		$this->DAT_INICIO_CEDO =  $val;
	}
	function setDAT_FINAL_CEDO($val){
		$this->DAT_FINAL_CEDO =  $val;
	}
	function setDAT_INICIO_TARDE($val){
		$this->DAT_INICIO_TARDE =  $val;
	}
	function setDAT_FINAL_TARDE($val){
		$this->DAT_FINAL_TARDE =  $val;
	}
	function setQTD_FREE_SLACK($val){
		$this->QTD_FREE_SLACK =  $val;
	}
	function setTaskHyperlinkHref($val){
		$this->TaskHyperlinkHref =  $val;
	}
	function setQTD_DURACAO_REAL($val){
		$this->QTD_DURACAO_REAL =  $val;
	}
	function setDAT_INICIO_REAL($val){
		$this->DAT_INICIO_REAL =  $val;
	}
	function setDAT_FINAL_REAL($val){
		$this->DAT_FINAL_REAL =  $val;
	}
	function setQTD_DURACAO_BASELINE($val){
		$this->QTD_DURACAO_BASELINE =  $val;
	}
	function setDAT_INICIO_BASELINE($val){
		$this->DAT_INICIO_BASELINE =  $val;
	}
	function setDAT_FINAL_BASELINE($val){
		$this->DAT_FINAL_BASELINE =  $val;
	}
	function setDAT_CRIACAO($val){
		$this->DAT_CRIACAO =  $val;
	}
	function setFLG_MARCO($val){
		$this->FLG_MARCO =  $val;
	}
	function setNOM_RECURSOS($val){
		$this->NOM_RECURSOS =  $val;
	}
	function setSIG_AREA_RECURSO($val){
		$this->SIG_AREA_RECURSO =  $val;
	}
	function setSPI($val){
		$this->SPI =  $val;
	}
	function setTCPI($val){
		$this->TCPI =  $val;
	}
	function setCPI($val){
		$this->CPI =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($v_SEQ_ATIVIDADE, $v_COD_PROJETO){
		$sql = "SELECT
						CAST([TaskUniqueId] AS varchar(10)) as SEQ_ATIVIDADE,
						CAST([TaskOutlineNumber] AS varchar(10)) as COD_ATIVIDADE,
						CAST([TaskOutlineLevel] AS varchar(10)) as NUM_NIVEL,
						CAST([TaskName] AS varchar(400)) as NOM_ATIVIDADE,
						CAST([TaskPercentComplete] AS varchar(10)) as PER_COMPLETA,
						CAST([TaskPhysicalPercentComplete] AS varchar(10)) as PER_FISICA_COMPLETA,
						CAST([TaskPredecessors] AS varchar(20)) as Predecessoras,
						CAST([TaskSuccessors] AS varchar(20)) as Sucessoras,
						CAST([TaskDuration] AS varchar(10)) as QTD_DURACAO,
						CAST([TaskStart] AS varchar(20)) as DAT_INICIO_PREVISTA,
						CAST([TaskFinish] AS varchar(20)) as DAT_FINAL_PREVISTA,
						CAST([TaskRemainingDuration] AS varchar(10)) as QTD_DURACAO_RESTANTE,
						CAST([TaskEarlyStart] AS varchar(20)) as DAT_INICIO_CEDO,
						CAST([TaskEarlyFinish] AS varchar(20)) as DAT_FINAL_CEDO,
						CAST([TaskLateStart] AS varchar(20)) as DAT_INICIO_TARDE,
						CAST([TaskLateFinish] AS varchar(20)) as DAT_FINAL_TARDE,
						CAST([TaskFreeSlack] AS varchar(20)) as QTD_FREE_SLACK,
						CAST([TaskHyperlinkHref] AS varchar(90)) as TaskHyperlinkHref,
						CAST([TaskActualDuration] AS varchar(10)) as QTD_DURACAO_REAL,
						CAST([TaskActualStart] AS varchar(20)) as DAT_INICIO_REAL,
						CAST([TaskActualFinish] AS varchar(20)) as DAT_FINAL_REAL,
						CAST([TaskBaselineDuration] AS varchar(10)) as QTD_DURACAO_BASELINE,
						CAST([TaskBaselineStart] AS varchar(20)) as DAT_INICIO_BASELINE,
						CAST([TaskBaselineFinish] AS varchar(20)) as DAT_FINAL_BASELINE,
						CAST([TaskCreated] AS varchar(20)) as DAT_CRIACAO,
						CAST([TaskMilestone] AS varchar(1)) as FLG_MARCO,
						CAST([TaskResourceNames] AS varchar(500)) as NOM_RECURSOS,
						CAST([TaskResourceGroup] AS varchar(100)) as SIG_AREA_RECURSO,
						CAST([TaskSPI] AS varchar(10)) as SPI,
						CAST([TaskTCPI] AS varchar(10)) as TCPI,
						CAST([TaskCPI] AS varchar(10)) as CPI
				FROM gestaoti.MSP_VIEW_PROJ_TASKS_STD  (nolock)
				where TaskUniqueId = $v_SEQ_ATIVIDADE
				and WPROJ_ID = $v_COD_PROJETO ";

		$this->database->query($sql);
		$row = odbc_fetch_array($this->database->result);

		$this->SEQ_ATIVIDADE = $row["SEQ_ATIVIDADE"];
		$this->COD_ATIVIDADE = $row["COD_ATIVIDADE"];
		$this->NUM_NIVEL = $row["NUM_NIVEL"];
		$this->NOM_ATIVIDADE = $row["NOM_ATIVIDADE"];
		$this->PER_COMPLETA = $row["PER_COMPLETA"];
		$this->PER_FISICA_COMPLETA = $row["PER_FISICA_COMPLETA"];
		$this->Predecessoras = $row["Predecessoras"];
		$this->Sucessoras = $row["Sucessoras"];
		$this->QTD_DURACAO = $row["QTD_DURACAO"];
		$this->DAT_INICIO_PREVISTA = $row["DAT_INICIO_PREVISTA"];
		$this->DAT_FINAL_PREVISTA = $row["DAT_FINAL_PREVISTA"];
		$this->QTD_DURACAO_RESTANTE = $row["QTD_DURACAO_RESTANTE"];
		$this->DAT_INICIO_CEDO = $row["DAT_INICIO_CEDO"];
		$this->DAT_FINAL_CEDO = $row["DAT_FINAL_CEDO"];
		$this->DAT_INICIO_TARDE = $row["DAT_INICIO_TARDE"];
		$this->DAT_FINAL_TARDE = $row["DAT_FINAL_TARDE"];
		$this->QTD_FREE_SLACK = $row["QTD_FREE_SLACK"];
		$this->QTD_DURACAO_REAL = $row["QTD_DURACAO_REAL"];
		$this->DAT_INICIO_REAL = $row["DAT_INICIO_REAL"];
		$this->DAT_FINAL_REAL = $row["DAT_FINAL_REAL"];
		$this->QTD_DURACAO_BASELINE = $row["QTD_DURACAO_BASELINE"];
		$this->DAT_FINAL_BASELINE = $row["DAT_FINAL_BASELINE"];
		$this->DAT_CRIACAO = $row["DAT_CRIACAO"];
		$this->FLG_MARCO = $row["FLG_MARCO"];
		$this->NOM_RECURSOS = $row["NOM_RECURSOS"];
		$this->SIG_AREA_RECURSO = $row["SIG_AREA_RECURSO"];
		$this->SPI = $row["SPI"];
		$this->TCPI = $row["TCPI"];
		$this->CPI = $row["CPI"];
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 2, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		if($vNumPagina != ""){
			$sqlSelect = " select top $vQtdRegistros
								CAST([TaskUniqueId] AS varchar(10)) as SEQ_ATIVIDADE,
								CAST([TaskOutlineNumber] AS varchar(10)) as COD_ATIVIDADE,
								CAST([TaskOutlineLevel] AS varchar(10)) as NUM_NIVEL,
								CAST([TaskName] AS varchar(400)) as NOM_ATIVIDADE,
								CAST([TaskPercentComplete] AS varchar(10)) as PER_COMPLETA,
								CAST([TaskPhysicalPercentComplete] AS varchar(10)) as PER_FISICA_COMPLETA,
								CAST([TaskPredecessors] AS varchar(20)) as Predecessoras,
								CAST([TaskSuccessors] AS varchar(20)) as Sucessoras,
								CAST([TaskDuration] AS varchar(10)) as QTD_DURACAO,
								CAST([TaskStart] AS varchar(20)) as DAT_INICIO_PREVISTA,
								CAST([TaskFinish] AS varchar(20)) as DAT_FINAL_PREVISTA,
								CAST([TaskRemainingDuration] AS varchar(10)) as QTD_DURACAO_RESTANTE,
								CAST([TaskEarlyStart] AS varchar(20)) as DAT_INICIO_CEDO,
								CAST([TaskEarlyFinish] AS varchar(20)) as DAT_FINAL_CEDO,
								CAST([TaskLateStart] AS varchar(20)) as DAT_INICIO_TARDE,
								CAST([TaskLateFinish] AS varchar(20)) as DAT_FINAL_TARDE,
								CAST([TaskFreeSlack] AS varchar(20)) as QTD_FREE_SLACK,
								CAST([TaskHyperlinkHref] AS varchar(90)) as TaskHyperlinkHref,
								CAST([TaskActualDuration] AS varchar(10)) as QTD_DURACAO_REAL,
								CAST([TaskActualStart] AS varchar(20)) as DAT_INICIO_REAL,
								CAST([TaskActualFinish] AS varchar(20)) as DAT_FINAL_REAL,
								CAST([TaskBaselineDuration] AS varchar(10)) as QTD_DURACAO_BASELINE,
								CAST([TaskBaselineStart] AS varchar(20)) as DAT_INICIO_BASELINE,
								CAST([TaskBaselineFinish] AS varchar(20)) as DAT_FINAL_BASELINE,
								CAST([TaskCreated] AS varchar(20)) as DAT_CRIACAO,
								CAST([TaskMilestone] AS varchar(1)) as FLG_MARCO,
								CAST([TaskResourceNames] AS varchar(500)) as NOM_RECURSOS,
								CAST([TaskResourceGroup] AS varchar(100)) as SIG_AREA_RECURSO,
								CAST([TaskSPI] AS varchar(10)) as SPI,
								CAST([TaskTCPI] AS varchar(10)) as TCPI,
								CAST([TaskCPI] AS varchar(10)) as CPI
								";
		}else{
			$sqlSelect = " select
								CAST([TaskUniqueId] AS varchar(10)) as SEQ_ATIVIDADE,
								CAST([TaskOutlineNumber] AS varchar(10)) as COD_ATIVIDADE,
								CAST([TaskOutlineLevel] AS varchar(10)) as NUM_NIVEL,
								CAST([TaskName] AS varchar(400)) as NOM_ATIVIDADE,
								CAST([TaskPercentComplete] AS varchar(10)) as PER_COMPLETA,
								CAST([TaskPhysicalPercentComplete] AS varchar(10)) as PER_FISICA_COMPLETA,
								CAST([TaskPredecessors] AS varchar(20)) as Predecessoras,
								CAST([TaskSuccessors] AS varchar(20)) as Sucessoras,
								CAST([TaskDuration] AS varchar(10)) as QTD_DURACAO,
								CAST([TaskStart] AS varchar(20)) as DAT_INICIO_PREVISTA,
								CAST([TaskFinish] AS varchar(20)) as DAT_FINAL_PREVISTA,
								CAST([TaskRemainingDuration] AS varchar(10)) as QTD_DURACAO_RESTANTE,
								CAST([TaskEarlyStart] AS varchar(20)) as DAT_INICIO_CEDO,
								CAST([TaskEarlyFinish] AS varchar(20)) as DAT_FINAL_CEDO,
								CAST([TaskLateStart] AS varchar(20)) as DAT_INICIO_TARDE,
								CAST([TaskLateFinish] AS varchar(20)) as DAT_FINAL_TARDE,
								CAST([TaskFreeSlack] AS varchar(20)) as QTD_FREE_SLACK,
								CAST([TaskHyperlinkHref] AS varchar(90)) as TaskHyperlinkHref,
								CAST([TaskActualDuration] AS varchar(10)) as QTD_DURACAO_REAL,
								CAST([TaskActualStart] AS varchar(20)) as DAT_INICIO_REAL,
								CAST([TaskActualFinish] AS varchar(20)) as DAT_FINAL_REAL,
								CAST([TaskBaselineDuration] AS varchar(10)) as QTD_DURACAO_BASELINE,
								CAST([TaskBaselineStart] AS varchar(20)) as DAT_INICIO_BASELINE,
								CAST([TaskBaselineFinish] AS varchar(20)) as DAT_FINAL_BASELINE,
								CAST([TaskCreated] AS varchar(20)) as DAT_CRIACAO,
								CAST([TaskMilestone] AS varchar(1)) as FLG_MARCO,
								CAST([TaskResourceNames] AS varchar(500)) as NOM_RECURSOS,
								CAST([TaskResourceGroup] AS varchar(100)) as SIG_AREA_RECURSO,
								CAST([TaskSPI] AS varchar(10)) as SPI,
								CAST([TaskTCPI] AS varchar(10)) as TCPI,
								CAST([TaskCPI] AS varchar(10)) as CPI
								";
		}
		$sqlCorpo  = "	FROM gestaoti.MSP_VIEW_PROJ_TASKS_STD  (nolock)
						where 1=1
					";

		if($this->COD_PROJETO != ""){
			$sqlCorpo .= "  and WPROJ_ID = ".$this->COD_PROJETO." ";
		}
		if($this->SEQ_ATIVIDADE != ""){
			$sqlCorpo .= "  and TaskUniqueId = ".$this->SEQ_ATIVIDADE." ";
		}
		if($this->COD_ATIVIDADE != ""){
			$sqlCorpo .= "  and CAST([TaskOutlineNumber] AS varchar(10)) = '".$this->COD_ATIVIDADE."' ";
		}
		if($this->NUM_NIVEL != ""){
			$sqlCorpo .= "  and TaskOutlineLevel = ".$this->NUM_NIVEL." ";
		}
		if($this->NOM_ATIVIDADE != ""){
			$sqlCorpo .= "  and upper(TaskName) like '%".strtoupper($this->NOM_ATIVIDADE)."%' ";
		}
		if($this->PER_COMPLETA != ""){
			$sqlCorpo .= "  and TaskPercentComplete = ".$this->PER_COMPLETA." ";
		}
		if($this->PER_FISICA_COMPLETA != ""){
			$sqlCorpo .= "  and TaskPhysicalPercentComplete = ".$this->PER_FISICA_COMPLETA." ";
		}
		if($this->Predecessoras != ""){
			$sqlCorpo .= "  and TaskPredecessors = '".strtoupper($this->Predecessoras)."' ";
		}
		if($this->Sucessoras != ""){
			$sqlCorpo .= "  and TaskSuccessors = '".strtoupper($this->Sucessoras)."' ";
		}
		if($this->QTD_DURACAO != ""){
			$sqlCorpo .= "  and TaskDuration = ".$this->QTD_DURACAO." ";
		}
		if($this->DAT_INICIO_PREVISTA != ""){
			$sqlCorpo .= "  and TaskStart = '".$this->DAT_INICIO_PREVISTA."' ";
		}
		if($this->DAT_FINAL_PREVISTA != ""){
			$sqlCorpo .= "  and TaskFinish = '".$this->DAT_FINAL_PREVISTA."' ";
		}
		if($this->QTD_DURACAO_RESTANTE != ""){
			$sqlCorpo .= "  and TaskRemainingDuration = ".$this->QTD_DURACAO_RESTANTE." ";
		}
		if($this->DAT_INICIO_CEDO != ""){
			$sqlCorpo .= "  and TaskEarlyStart = '".$this->DAT_INICIO_CEDO."' ";
		}
		if($this->DAT_FINAL_CEDO != ""){
			$sqlCorpo .= "  and TaskEarlyFinish = '".$this->DAT_FINAL_CEDO."' ";
		}
		if($this->DAT_INICIO_TARDE != ""){
			$sqlCorpo .= "  and TaskLateStart = '".$this->DAT_INICIO_TARDE."' ";
		}
		if($this->DAT_FINAL_TARDE != ""){
			$sqlCorpo .= "  and TaskLateFinish = '".$this->DAT_FINAL_TARDE."' ";
		}
		if($this->QTD_FREE_SLACK != ""){
			$sqlCorpo .= "  and TaskFreeSlack = ".$this->QTD_FREE_SLACK." ";
		}
		if($this->DAT_INICIO_REAL != ""){
			$sqlCorpo .= "  and TaskActualStart = ".$this->DAT_INICIO_REAL." ";
		}
		if($this->QTD_DURACAO_REAL != ""){
			$sqlCorpo .= "  and TaskActualFinish = ".$this->QTD_DURACAO_REAL." ";
		}
		if($this->QTD_DURACAO_BASELINE != ""){
			$sqlCorpo .= "  and TaskBaselineDuration = ".$this->QTD_DURACAO_BASELINE." ";
		}
		if($this->DAT_INICIO_BASELINE != ""){
			$sqlCorpo .= "  and TaskBaselineStart = ".$this->DAT_INICIO_BASELINE." ";
		}
		if($this->DAT_FINAL_BASELINE != ""){
			$sqlCorpo .= "  and TaskBaselineFinish = ".$this->DAT_FINAL_BASELINE." ";
		}
		if($this->DAT_CRIACAO != ""){
			$sqlCorpo .= "  and TaskCreated = ".$this->DAT_CRIACAO." ";
		}
		if($this->FLG_MARCO != ""){
			$sqlCorpo .= "  and TaskMilestone = ".$this->FLG_MARCO." ";
		}
		if($this->NOM_RECURSOS != ""){
				$sqlCorpo .= "  and upper(TaskResourceNames) like '%".strtoupper($this->NOM_RECURSOS)."%' ";
		}
		if($this->SIG_AREA_RECURSO != ""){
				$sqlCorpo .= "  and upper(TaskResourceGroup) like '%".strtoupper($this->SIG_AREA_RECURSO)."%' ";
		}

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);

			$sqlOrder  .= "
						and TaskUniqueId NOT IN (
							select top $vLimit a.TaskUniqueId
							FROM gestaoti.MSP_VIEW_PROJ_TASKS_STD a (nolock)
							where 1=1
					     ";
			if($this->COD_PROJETO != ""){
				$sqlOrder .= "  and a.WPROJ_ID = ".$this->COD_PROJETO." ";
			}
			if($this->SEQ_ATIVIDADE != ""){
				$sqlOrder .= "  and a.TaskUniqueId = ".$this->SEQ_ATIVIDADE." ";
			}
			if($this->COD_ATIVIDADE != ""){
				$sqlOrder .= "  and CAST([a.TaskOutlineNumber] AS varchar(10)) = '".$this->COD_ATIVIDADE."' ";
			}
			if($this->NUM_NIVEL != ""){
				$sqlOrder .= "  and a.TaskOutlineLevel = ".$this->NUM_NIVEL." ";
			}
			if($this->NOM_ATIVIDADE != ""){
				$sqlOrder .= "  and upper(a.TaskName) like '%".strtoupper($this->NOM_ATIVIDADE)."%' ";
			}
			if($this->PER_COMPLETA != ""){
				$sqlOrder .= "  and a.TaskPercentComplete = ".$this->PER_COMPLETA." ";
			}
			if($this->PER_FISICA_COMPLETA != ""){
				$sqlOrder .= "  and a.TaskPhysicalPercentComplete = ".$this->PER_FISICA_COMPLETA." ";
			}
			if($this->Predecessoras != ""){
				$sqlOrder .= "  and a.TaskPredecessors = '".strtoupper($this->Predecessoras)."' ";
			}
			if($this->Sucessoras != ""){
				$sqlOrder .= "  and a.TaskSuccessors = '".strtoupper($this->Sucessoras)."' ";
			}
			if($this->QTD_DURACAO != ""){
				$sqlOrder .= "  and a.TaskDuration = ".$this->QTD_DURACAO." ";
			}
			if($this->DAT_INICIO_PREVISTA != ""){
				$sqlOrder .= "  and a.TaskStart = '".$this->DAT_INICIO_PREVISTA."' ";
			}
			if($this->DAT_FINAL_PREVISTA != ""){
				$sqlOrder .= "  and a.TaskFinish = '".$this->DAT_FINAL_PREVISTA."' ";
			}
			if($this->QTD_DURACAO_RESTANTE != ""){
				$sqlOrder .= "  and a.TaskRemainingDuration = ".$this->QTD_DURACAO_RESTANTE." ";
			}
			if($this->DAT_INICIO_CEDO != ""){
				$sqlOrder .= "  and TaskEarlyStart = '".$this->DAT_INICIO_CEDO."' ";
			}
			if($this->DAT_FINAL_CEDO != ""){
				$sqlOrder .= "  and a.TaskEarlyFinish = '".$this->DAT_FINAL_CEDO."' ";
			}
			if($this->DAT_INICIO_TARDE != ""){
				$sqlOrder .= "  and a.TaskLateStart = '".$this->DAT_INICIO_TARDE."' ";
			}
			if($this->DAT_FINAL_TARDE != ""){
				$sqlOrder .= "  and a.TaskLateFinish = '".$this->DAT_FINAL_TARDE."' ";
			}
			if($this->QTD_FREE_SLACK != ""){
				$sqlOrder .= "  and a.TaskFreeSlack = ".$this->QTD_FREE_SLACK." ";
			}
			if($this->DAT_INICIO_REAL != ""){
				$sqlOrder .= "  and a.TaskActualStart = ".$this->DAT_INICIO_REAL." ";
			}
			if($this->QTD_DURACAO_REAL != ""){
				$sqlOrder .= "  and a.TaskActualFinish = ".$this->QTD_DURACAO_REAL." ";
			}
			if($this->QTD_DURACAO_BASELINE != ""){
				$sqlOrder .= "  and TaskBaselineDuration = ".$this->QTD_DURACAO_BASELINE." ";
			}
			if($this->DAT_INICIO_BASELINE != ""){
				$sqlOrder .= "  and a.TaskBaselineStart = ".$this->DAT_INICIO_BASELINE." ";
			}
			if($this->DAT_FINAL_BASELINE != ""){
				$sqlOrder .= "  and a.TaskBaselineFinish = ".$this->DAT_FINAL_BASELINE." ";
			}
			if($this->DAT_CRIACAO != ""){
				$sqlOrder .= "  and a.TaskCreated = ".$this->DAT_CRIACAO." ";
			}
			if($this->FLG_MARCO != ""){
				$sqlOrder .= "  and a.TaskMilestone = ".$this->FLG_MARCO." ";
			}
			if($this->NOM_RECURSOS != ""){
				$sqlOrder .= "  and upper(a.TaskResourceNames) like '%".strtoupper($this->NOM_RECURSOS)."%' ";
			}
			if($this->SIG_AREA_RECURSO != ""){
				$sqlOrder .= "  and upper(a.TaskResourceGroup) like '%".strtoupper($this->SIG_AREA_RECURSO)."%' ";
			}

			$sqlOrder .= " order by $orderBy ";

			$sqlOrder  .= ") ";

			$db = new DatabaseEPM();
			$db->query("select count(1) as contador " . $sqlCorpo);
			$rowCount = odbc_fetch_array($db->result);
			$this->setrowCount($rowCount["contador"]);
			$db = "";
		}

		$sqlOrder .= " order by $orderBy ";
		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);//print $sqlSelect . $sqlCorpo . $sqlOrder;

	}
} // class : end
?>