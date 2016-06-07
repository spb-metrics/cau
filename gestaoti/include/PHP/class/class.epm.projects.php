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
class projetos{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $COD_PROJETO;
	var $NOM_PROJETO;
	var $SIG_EXECUTENTE;
	var $SIG_DEMANDANTE;
	var $NOM_LIDER;
	var $NOM_PROJETO_REDUZIDO;
	var $TIP_PROJETO;
	var $STA_PROJETO;
	var $FLG_ATRASO;
	var $FLG_ANDAMENTO;
	var $DAT_ULTIMA_PUBLICACAO;
	var $QTD_DURACAO;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function projetos(){
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

	function setNOM_PROJETO($val){
		$this->NOM_PROJETO =  $val;
	}

	function setSIG_EXECUTENTE($val){
		$this->SIG_EXECUTENTE =  $val;
	}

	function setSIG_DEMANDANTE($val){
		$this->SIG_DEMANDANTE =  $val;
	}

	function setNOM_LIDER($val){
		$this->NOM_LIDER =  $val;
	}

	function setNOM_PROJETO_REDUZIDO($val){
		$this->NOM_PROJETO_REDUZIDO =  $val;
	}

	function setTIP_PROJETO($val){
		$this->TIP_PROJETO =  $val;
	}

	function setSTA_PROJETO($val){
		$this->STA_PROJETO =  $val;
	}

	function setFLG_ATRASO($val){
		$this->FLG_ATRASO =  $val;
	}

	function setFLG_ANDAMENTO($val){
		$this->FLG_ANDAMENTO =  $val;
	}

	function setDAT_ULTIMA_PUBLICACAO($val){
		$this->DAT_ULTIMA_PUBLICACAO =  $val;
	}

	function setQTD_DURACAO($val){
		$this->QTD_DURACAO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT
						MSP_WEB_PROJECTS.WPROJ_ID as COD_PROJETO,
						PROJ_NAME as NOM_PROJETO,
						CAST([ProjectEnterpriseText1] AS TEXT)    as NOM_EXECUTENTE,
						CAST([ProjectEnterpriseText2] AS TEXT)    as SIG_DEMANDANTE,
						CAST([ProjectEnterpriseText3] AS TEXT)    as NOM_LIDER,
						CAST([ProjectEnterpriseText4] AS TEXT)    as NOM_PROJETO_REDUZIDO,
						CAST([ProjectEnterpriseText5] AS TEXT)    as TIP_PROJETO,
						CAST([ProjectEnterpriseText10] AS TEXT)   as STA_PROJETO,
						CAST([ProjectEnterpriseText11] AS TEXT)   as FLG_ATRASO,
						CAST([ProjectEnterpriseText13] AS TEXT)   as FLG_ANDAMENTO,
						WPROJ_LAST_PUB as DAT_ULTIMA_PUBLICACAO,
						ProjectEnterpriseDuration1 as QTD_DURACAO
				FROM gestaoti.MSP_WEB_PROJECTS (nolock), MSP_VIEW_PROJ_PROJECTS_ENT (nolock)
				WHERE 	MSP_WEB_PROJECTS.WPROJ_ID = MSP_VIEW_PROJ_PROJECTS_ENT.WPROJ_ID
				and MSP_WEB_PROJECTS.WPROJ_ID = $id ";

		$this->database->query($sql);
		$row = odbc_fetch_array($this->database->result);

		$this->COD_PROJETO = $row["COD_PROJETO"];
		$this->NOM_PROJETO = $row["NOM_PROJETO"];
		$this->NOM_EXECUTENTE = $row["NOM_EXECUTENTE"];
		$this->SIG_DEMANDANTE = $row["SIG_DEMANDANTE"];
		$this->NOM_LIDER = $row["NOM_LIDER"];
		$this->NOM_PROJETO_REDUZIDO = $row["NOM_PROJETO_REDUZIDO"];
		$this->TIP_PROJETO = $row["TIP_PROJETO"];
		$this->STA_PROJETO = $row["STA_PROJETO"];
		$this->FLG_ATRASO = $row["FLG_ATRASO"];
		$this->FLG_ANDAMENTO = $row["FLG_ANDAMENTO"];
		$this->DAT_ULTIMA_PUBLICACAO = $row["DAT_ULTIMA_PUBLICACAO"];
		$this->QTD_DURACAO = $row["QTD_DURACAO"];
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 2, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		if($vNumPagina != ""){
			$sqlSelect = " select top $vQtdRegistros
								MSP_WEB_PROJECTS.WPROJ_ID as COD_PROJETO,
								PROJ_NAME as NOM_PROJETO,
								CAST([ProjectEnterpriseText1] AS TEXT)    as NOM_EXECUTENTE,
								CAST([ProjectEnterpriseText2] AS TEXT)    as SIG_DEMANDANTE,
								CAST([ProjectEnterpriseText3] AS TEXT)    as NOM_LIDER,
								CAST([ProjectEnterpriseText4] AS TEXT)    as NOM_PROJETO_REDUZIDO,
								CAST([ProjectEnterpriseText5] AS TEXT)    as TIP_PROJETO,
								CAST([ProjectEnterpriseText10] AS TEXT)   as STA_PROJETO,
								CAST([ProjectEnterpriseText11] AS TEXT)   as FLG_ATRASO,
								CAST([ProjectEnterpriseText13] AS TEXT)   as FLG_ANDAMENTO,
								WPROJ_LAST_PUB as DAT_ULTIMA_PUBLICACAO,
								ProjectEnterpriseDuration1 as QTD_DURACAO
								";
		}else{
			$sqlSelect = " select
								MSP_WEB_PROJECTS.WPROJ_ID as COD_PROJETO,
								PROJ_NAME as NOM_PROJETO,
								CAST([ProjectEnterpriseText1] AS TEXT)    as NOM_EXECUTENTE,
								CAST([ProjectEnterpriseText2] AS TEXT)    as SIG_DEMANDANTE,
								CAST([ProjectEnterpriseText3] AS TEXT)    as NOM_LIDER,
								CAST([ProjectEnterpriseText4] AS TEXT)    as NOM_PROJETO_REDUZIDO,
								CAST([ProjectEnterpriseText5] AS TEXT)    as TIP_PROJETO,
								CAST([ProjectEnterpriseText10] AS TEXT)   as STA_PROJETO,
								CAST([ProjectEnterpriseText11] AS TEXT)   as FLG_ATRASO,
								CAST([ProjectEnterpriseText13] AS TEXT)   as FLG_ANDAMENTO,
								WPROJ_LAST_PUB as DAT_ULTIMA_PUBLICACAO,
								ProjectEnterpriseDuration1 as QTD_DURACAO
								";
		}
		$sqlCorpo  = "	FROM gestaoti.MSP_WEB_PROJECTS (nolock), MSP_VIEW_PROJ_PROJECTS_ENT (nolock)
						WHERE 	MSP_WEB_PROJECTS.WPROJ_ID = MSP_VIEW_PROJ_PROJECTS_ENT.WPROJ_ID
					";

		if($this->COD_PROJETO != ""){
			$sqlCorpo .= "  and MSP_WEB_PROJECTS.WPROJ_ID = ".$this->COD_PROJETO." ";
		}

		if($this->NOM_PROJETO != ""){
			$sqlCorpo .= "  and upper(PROJ_NAME) like '%".strtoupper($this->NOM_PROJETO)."%' ";
		}

		if($this->SIG_EXECUTENTE != ""){
			$sqlCorpo .= "  and ProjectEnterpriseText1 like '%".strtoupper($this->SIG_EXECUTENTE)."%' ";
		}

		if($this->SIG_DEMANDANTE != ""){
			$sqlCorpo .= "  and ProjectEnterpriseText2 like '%".strtoupper($this->SIG_DEMANDANTE)."%' ";
		}

		if($this->NOM_LIDER != ""){
			$sqlCorpo .= "  and ProjectEnterpriseText3 like '%".strtoupper($this->NOM_LIDER)."%' ";
		}

		if($this->NOM_PROJETO_REDUZIDO != ""){
			$sqlCorpo .= "  and ProjectEnterpriseText4 like '%".strtoupper($this->NOM_PROJETO_REDUZIDO)."%' ";
		}

		if($this->TIP_PROJETO != ""){
			$sqlCorpo .= "  and ProjectEnterpriseText5 like '%".strtoupper($this->TIP_PROJETO)."%' ";
		}

		if($this->STA_PROJETO != ""){
			$sqlCorpo .= "  and ProjectEnterpriseText10 like '%".strtoupper($this->STA_PROJETO)."%' ";
		}

		if($this->FLG_ATRASO != ""){
			$sqlCorpo .= "  and ProjectEnterpriseText11 like '%".strtoupper($this->FLG_ATRASO)."%' ";
		}

		if($this->FLG_ANDAMENTO != ""){
			$sqlCorpo .= "  and ProjectEnterpriseText13 like '%".strtoupper($this->FLG_ANDAMENTO)."%' ";
		}


		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);

			$sqlOrder  .= "
						and MSP_WEB_PROJECTS.WPROJ_ID NOT IN (
							select top $vLimit a.WPROJ_ID
							FROM gestaoti.MSP_WEB_PROJECTS a (nolock), MSP_VIEW_PROJ_PROJECTS_ENT b (nolock)
							WHERE 	a.WPROJ_ID = b.WPROJ_ID
					     ";
			if($this->COD_PROJETO != ""){
				$sqlOrder .= "  and a.WPROJ_ID = ".$this->COD_PROJETO." ";
			}

			if($this->NOM_PROJETO != ""){
				$sqlOrder .= "  and upper(PROJ_NAME) like '%".strtoupper($this->NOM_PROJETO)."%' ";
			}

			if($this->SIG_EXECUTENTE != ""){
				$sqlOrder .= "  and ProjectEnterpriseText1 like '%".strtoupper($this->SIG_EXECUTENTE)."%' ";
			}

			if($this->SIG_DEMANDANTE != ""){
				$sqlOrder .= "  and ProjectEnterpriseText2 like '%".strtoupper($this->SIG_DEMANDANTE)."%' ";
			}

			if($this->NOM_LIDER != ""){
				$sqlOrder .= "  and ProjectEnterpriseText3 like '%".strtoupper($this->NOM_LIDER)."%' ";
			}

			if($this->NOM_PROJETO_REDUZIDO != ""){
				$sqlOrder .= "  and ProjectEnterpriseText4 like '%".strtoupper($this->NOM_PROJETO_REDUZIDO)."%' ";
			}

			if($this->TIP_PROJETO != ""){
				$sqlOrder .= "  and ProjectEnterpriseText5 like '%".strtoupper($this->TIP_PROJETO)."%' ";
			}

			if($this->STA_PROJETO != ""){
				$sqlOrder .= "  and ProjectEnterpriseText10 like '%".strtoupper($this->STA_PROJETO)."%' ";
			}

			if($this->FLG_ATRASO != ""){
				$sqlOrder .= "  and ProjectEnterpriseText11 like '%".strtoupper($this->FLG_ATRASO)."%' ";
			}

			if($this->FLG_ANDAMENTO != ""){
				$sqlOrder .= "  and ProjectEnterpriseText13 like '%".strtoupper($this->FLG_ANDAMENTO)."%' ";
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