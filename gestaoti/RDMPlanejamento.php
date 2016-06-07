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
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.rdm.php';
require_once 'include/PHP/class/class.situacao_rdm.php';
require_once 'include/PHP/class/class.anexo_rdm.php';
require_once 'include/PHP/class/class.atividade_rdm.php';
require_once 'include/PHP/class/class.atividade_rb_rdm.php';
require_once 'include/PHP/class/class.util.php';
require_once 'include/PHP/class/class.equipe_ti.php';
require 'include/PHP/class/class.servidor.php';
require 'include/PHP/class/class.item_configuracao.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();


// ============================================================================================================
// CAREGAR A TELA COM OS DADOS DA RDM
// ============================================================================================================
$RDM = new rdm();
$situacaoRDM = new situacao_rdm();		 
$atividadeRDM = new atividade_rdm();
$atividadeRBRDM = new atividade_rb_rdm();	

if($v_SEQ_RDM != "" && $v_ACAO=="PLANEJAR"){	
	$v_ACAO ="";				 
	
	
	// pesquisa
	$RDM->select($v_SEQ_RDM);
	
	// Dados do Solicitante	
	$empregados = new empregados();
	$empregados->select($RDM->NUM_MATRICULA_SOLICITANTE);
	
	$v_NUM_MATRICULA_SOLICITANTE_REAL = $empregados->NOM_LOGIN_REDE;
	
	$v_TITULO = $RDM->TITULO;
	$v_JUSTIFICATIVA = $RDM->JUSTIFICATIVA;
	$v_IMPACTO_NAO_EXECUTAR = $RDM->IMPACTO_NAO_EXECUTAR;
	$v_OBSERVACAO = $RDM->OBSERVACAO;
	$v_NOME_RESP_CHECKLIST = $RDM->NOME_RESP_CHECKLIST;
	$v_DDD_TELEFONE_RESP_CHECKLIST = $RDM->DDD_TELEFONE_RESP_CHECKLIST;
	$v_NUMERO_TELEFONE_RESP_CHECKLIST = $RDM->NUMERO_TELEFONE_RESP_CHECKLIST;
	
	//ATIVIDADES DA RDM
 	$_SESSION['ID_ATIVIDADE_RDM'] = 0;
 	$_SESSION['ATIVIDADES_RDM'] = array();
	$atividadeRDM->setSEQ_RDM($RDM->SEQ_RDM);
	$atividadeRDM->selectParam("ORDEM");
	
	if($atividadeRDM->database->rows != 0){		 

		while ($row = pg_fetch_array($atividadeRDM->database->result)){		 
		 
			$v_DESCRICAO_ATIVIDADE_ATM = $row["descricao"];
			
			if($row["seq_servidor"]!=""){
				// Servidores						
				$servidor = new servidor();	
				$servidor->select($row["seq_servidor"]);
					
				$v_SEQ_ITEM_CONFIGURACAO_ATM = $row["seq_servidor"];
				$v_NOM_ITEM_CONFIGURACAO_ATM = $servidor->NOM_SERVIDOR;
				$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = 1;
				
			}else if($row["seq_item_configuracao"]!=""){
				// Sistemas de informação				
				$sistemas = new item_configuracao();				 
				$sistemas->select($row["seq_item_configuracao"]);				 
				
				$v_SEQ_ITEM_CONFIGURACAO_ATM = $row["seq_item_configuracao"];
				$v_NOM_ITEM_CONFIGURACAO_ATM = $sistemas->NOM_ITEM_CONFIGURACAO;
				$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = 2;
			} 	 

			$v_SEQ_EQUIPE_TI_ATM = $row["seq_equipe_ti"];
			$v_NUM_MATRICULA_RECURSO_ATM = $row["num_matricula_recurso"];
			
			$array_teste = split(" ",$row["data_hora_prevista_execucao"]);
			$data_array_teste = split("-",$array_teste[0]);
			$hora_array_teste = split(":",$array_teste[1]);			
			$dia_teste = $data_array_teste[2];
			$mes_teste = $data_array_teste[1];
			$ano_teste = $data_array_teste[0];			
			$h_teste = $hora_array_teste[0];
			$min_teste =  $hora_array_teste[1];
			$data_hora_prevista_execucao = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
			
			$v_DATA_EXECUCAO_ATM =  date("d/m/Y",$data_hora_prevista_execucao);			
			$v_HORA_EXECUCAO_ATM = date("H:i", $data_hora_prevista_execucao);
			
//			$_SESSION['ATIVIDADES_RDM'][] = array($_SESSION['ID_ATIVIDADE_RDM']++,$v_SEQ_ITEM_CONFIGURACAO_ATM ,
//					$v_NOM_ITEM_CONFIGURACAO_ATM,$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM,$v_DESCRICAO_ATIVIDADE_ATM,
//					$v_DATA_EXECUCAO_ATM,$v_HORA_EXECUCAO_ATM,$v_SEQ_EQUIPE_TI_ATM);
			$_SESSION['ATIVIDADES_RDM'][] = array($row["seq_atividade_rdm"],$v_SEQ_ITEM_CONFIGURACAO_ATM ,
					$v_NOM_ITEM_CONFIGURACAO_ATM,$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM,$v_DESCRICAO_ATIVIDADE_ATM,
					$v_DATA_EXECUCAO_ATM,$v_HORA_EXECUCAO_ATM,$v_SEQ_EQUIPE_TI_ATM,$v_NUM_MATRICULA_RECURSO_ATM);
					
		} 
		 
		$v_SEQ_ITEM_CONFIGURACAO_ATM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = "";
		$v_DESCRICAO_ATIVIDADE_ATM = "";
		$v_DATA_EXECUCAO_ATM = "";
		$v_HORA_EXECUCAO_ATM = "";
		$v_SEQ_EQUIPE_TI_ATM = "";		
		$v_NUM_MATRICULA_RECURSO_ATM = "";			
		 
	} 
	
	// ATIVIDADES DE ROLLBACK DA RDM
	$_SESSION['ID_ATIVIDADE_RB_RDM'] = 0;
 	$_SESSION['ATIVIDADES_RB_RDM'] = array();
	
	$atividadeRBRDM->setSEQ_RDM($RDM->SEQ_RDM);
	$atividadeRBRDM->selectParam("ORDEM");
	
	if($atividadeRBRDM->database->rows != 0){
		
		while ($row = pg_fetch_array($atividadeRBRDM->database->result)){
			 
			$v_ORDEM_ATRBM = $row["ordem"];
			$v_DESCRICAO_ATIVIDADE_ATRBM = $row["descricao"];
			
			if($row["seq_servidor"]!=""){
				// Servidores				 
				$servidor = new servidor();	
				$servidor->select($row["seq_servidor"]);
				
				$v_SEQ_ITEM_CONFIGURACAO_ATRBM = $row["seq_servidor"];
				$v_NOM_ITEM_CONFIGURACAO_ATRBM = $servidor->NOM_SERVIDOR;
				$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = 1;	
				 
			}else if($row["seq_item_configuracao"]!=""){
				// Sistemas de informação				
				$sistemas = new item_configuracao();				 
				$sistemas->select($row["seq_item_configuracao"]);
				
				$v_SEQ_ITEM_CONFIGURACAO_ATRBM = $row["seq_item_configuracao"];
				$v_NOM_ITEM_CONFIGURACAO_ATRBM = $sistemas->NOM_ITEM_CONFIGURACAO;
				$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = 2;	
				 
			} 
			
			$v_SEQ_EQUIPE_TI_ATRBM = $row["seq_equipe_ti"];
			$v_NUM_MATRICULA_RECURSO_ATBM = $row["num_matricula_recurso"];
			
			
			$_SESSION['ATIVIDADES_RB_RDM'][] = array($row["seq_atividade_rb_rdm"],$v_SEQ_ITEM_CONFIGURACAO_ATRBM ,
					$v_NOM_ITEM_CONFIGURACAO_ATRBM,$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM,$v_DESCRICAO_ATIVIDADE_ATRBM,$v_ORDEM_ATRBM,$v_SEQ_EQUIPE_TI_ATRBM,$v_NUM_MATRICULA_RECURSO_ATBM);
		
		}
		
		$v_SEQ_ITEM_CONFIGURACAO_ATRBM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATRBM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = "";
		$v_DESCRICAO_ATIVIDADE_ATRBM = "";
		$v_ORDEM_ATRBM = "";		 
		$v_SEQ_EQUIPE_TI_ATRBM = "";					
		$v_ACAO_ATRBM = "";		
		$v_NUM_MATRICULA_RECURSO_ATBM ="";
	
	}


}else if($v_SEQ_RDM != "" && ($v_ACAO=="SALVAR" || $v_ACAO=="ENVIAR")){
	$vMsgErro = "";
	$vRegrasVioladas = false;
	
	for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){
		if($_SESSION['ATIVIDADES_RDM'][$i][8]=="" || $_SESSION['ATIVIDADES_RDM'][$i][8]==null){
			$vRegrasVioladas = true;
			$vMsgErro	 = "Você deve informar um responsável para todas as atividades da RDM!";
		}
		break;
	}
	
	if(!$vRegrasVioladas){
		for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){
			if($_SESSION['ATIVIDADES_RB_RDM'][$i][7]=="" || $_SESSION['ATIVIDADES_RB_RDM'][$i][7]==null){
				$vRegrasVioladas = true;
				$vMsgErro	 = "Você deve informar um responsável para todas as atividades de rollback da RDM!";
			}
			break;
		}
	}
	
	
	if(!$vRegrasVioladas){
		if(count($_SESSION['ATIVIDADES_RDM'])>1){		
			foreach ($_SESSION['ATIVIDADES_RDM'] as $key => $row) {
			    $data[$key] = $row[5];
			    $hora[$key] = $row[6];
			}
			array_multisort($data, SORT_ASC, $hora, SORT_ASC, $_SESSION['ATIVIDADES_RDM']);	
		}
		
		$RDM->setSEQ_RDM($v_SEQ_RDM);
		 
		//A data prevista para execuação inicial é a mesma da primeira atividade da RDM		
		$data = split("/",$_SESSION['ATIVIDADES_RDM'][0][5]);
		$d = $data[0];
		$m = $data[1];
		$a = $data[2];
		
		$hora = split(":",$_SESSION['ATIVIDADES_RDM'][0][6]);
		$hr = $hora[0];
		$minuto =  $hora[1];			 
		$dataHoraExecucao = mktime($hr,$minuto, 0,$m,$d,$a);
		
		$util = new util();
		$dataHoraAtual = $util->GetlocalTimeStamp();
		$RDM->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);		
		$RDM->setDATA_HORA_PREVISTA_EXECUCAO(date("Y-m-d H:i:s",$dataHoraExecucao));
		
		
		// ===== ATUALIZAR ATIVIDADES DA RDM =====	
		
		for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){
			$atividadeRDM->setSEQ_RDM($RDM->SEQ_RDM);
			$atividadeRDM->setSEQ_ATIVIDADE_RDM($_SESSION['ATIVIDADES_RDM'][$i][0]);
			$atividadeRDM->setDESCRICAO($_SESSION['ATIVIDADES_RDM'][$i][4]);
			
			if($_SESSION['ATIVIDADES_RDM'][$i][3]==1){//servidores
				$atividadeRDM->setSEQ_SERVIDOR($_SESSION['ATIVIDADES_RDM'][$i][1]);				
			}else if($_SESSION['ATIVIDADES_RDM'][$i][3]==2){//sistemas
				$atividadeRDM->setSEQ_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RDM'][$i][1]);
			} 
			$atividadeRDM->setSEQ_TIPO_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RDM'][$i][3]);
			$atividadeRDM->setORDEM($i+1);
			$atividadeRDM->setSITUACAO($atividadeRDM->NAO_INICIADA);
			
			
			$data_array = split("/",$_SESSION['ATIVIDADES_RDM'][$i][5]);
			$dia = $data_array[0];
			$mes = $data_array[1];
			$ano = $data_array[2];
			
			$hora_array = split(":",$_SESSION['ATIVIDADES_RDM'][$i][6]);
			$h = $hora_array[0];
			$min =  $hora_array[1];			 
			$dataHoraExceucao = mktime($h,$min, 0,$mes,$dia,$ano);
			
			$atividadeRDM->setDATA_HORA_PREVISTA_EXECUCAO(date("Y-m-d H:i:s",$dataHoraExceucao));
			$atividadeRDM->setSEQ_EQUIPE_TI($_SESSION['ATIVIDADES_RDM'][$i][7]);
			$atividadeRDM->setNUM_MATRICULA_RECURSO($_SESSION['ATIVIDADES_RDM'][$i][8]);
			 
			$atividadeRDM->update($_SESSION['ATIVIDADES_RDM'][$i][0]);
		}
	
		// ===== INCLUIR ATIVIDADES DE ROLLBACK DA RDM =====  	 
	    for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){
			$atividadeRBRDM->setSEQ_RDM($RDM->SEQ_RDM);
			$atividadeRBRDM->setSEQ_ATIVIDADE_RDM($_SESSION['ATIVIDADES_RB_RDM'][$i][0]);
			$atividadeRBRDM->setDESCRICAO($_SESSION['ATIVIDADES_RB_RDM'][$i][4]);
			
			if($_SESSION['ATIVIDADES_RDM'][$i][3]==1){//servidores
				$atividadeRBRDM->setSEQ_SERVIDOR($_SESSION['ATIVIDADES_RB_RDM'][$i][1]);				
			}else if($_SESSION['ATIVIDADES_RB_RDM'][$i][3]==2){//sistemas
				$atividadeRBRDM->setSEQ_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RB_RDM'][$i][1]);
			} 
			$atividadeRBRDM->setSEQ_TIPO_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RDM'][$i][3]);
			$atividadeRBRDM->setORDEM($_SESSION['ATIVIDADES_RB_RDM'][$i][5]);
			$atividadeRBRDM->setSITUACAO($atividadeRDM->NAO_INICIADA);			  
			$atividadeRBRDM->setSEQ_EQUIPE_TI($_SESSION['ATIVIDADES_RB_RDM'][$i][6]);
			$atividadeRBRDM->setNUM_MATRICULA_RECURSO($_SESSION['ATIVIDADES_RB_RDM'][$i][7]);
			$atividadeRBRDM->update($_SESSION['ATIVIDADES_RB_RDM'][$i][0]);
		}
		    
	    if($v_ACAO=="ENVIAR"){			
			$situacaoRDM->setSEQ_RDM($RDM->SEQ_RDM);
			$situacaoRDM->setSITUACAO($situacaoRDM->AGUARADANDO_EXECUCAO);
			$situacaoRDM->setDATA_HORA($dataHoraAtual);	
			$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
			$situacaoRDM->insert();	
			 
			$RDM->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);
			$RDM->setSITUACAO_ATUAL($situacaoRDM->getSITUACAO());
			$RDM->updateSituacao($v_SEQ_RDM);
			
			// Enviar e-mail para o solicitante
			$RDMEmail = new rdm();
			$RDMEmail->setSEQ_RDM($v_SEQ_RDM);
			$RDMEmail->select($v_SEQ_RDM); 
			require_once 'include/PHP/class/class.rdm_email.php';
			
			$rdm_email = new rdm_email($pagina,$RDMEmail);
			$rdm_email->sendEmailRDMEnviadaParaExecucao();	
		}	
	
		$RDM->updateDataHoraPrevistaExecucao($v_SEQ_RDM);
		$pagina->redirectTo("RDMDetalhe.php?v_SEQ_RDM=".$v_SEQ_RDM);
	}
	$v_ACAO ="";
}else if($v_SEQ_RDM == ""){	
	$pagina->redirectTo("RDMPesquisar.php");
}


$pagina->SettituloCabecalho("Planejamento da RDM"); // Indica o título do cabeçalho da página
	


if($RDM->NUM_MATRICULA_SOLICITANTE==null){
	$RDM->select($v_SEQ_RDM);
}
// Itens das abas
$aItemAba = Array();
$aItemAba[] = array("RDMPesquisar.php?".$vLink."&vNumPagina=$vNumPagina", "", "Pesquisar");
$aItemAba[] = array("RDMDetalhe.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Detalhes");

if(($RDM->NUM_MATRICULA_SOLICITANTE == $_SESSION["NUM_MATRICULA_RECURSO"])&&
	($RDM->getSITUACAO_ATUAL()==$situacaoRDM->CRIADA || 
	 $RDM->getSITUACAO_ATUAL()==$situacaoRDM->REPROVADA || 
	 $RDM->getSITUACAO_ATUAL()==$situacaoRDM->PLANEJAMENTO_REPROVADO)){
	$aItemAba[] = array("RDMAlteracao.php?v_SEQ_RDM=".$v_SEQ_RDM."&v_ACAO=ALTERAR", "", "Alterar");
}

$APROVAR = false;
if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->AGUARDANDO_APROVACAO)){
	if($RDM->getTIPO() == $RDM->NORMAL){
		if($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isCoordenadorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGerenteDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"])){
			$APROVAR = true;
		}
	}else if($RDM->getTIPO() == $RDM->EMERGENCIAL){
		if($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isCoordenadorTI($_SESSION["SEQ_PERFIL_ACESSO"])){
			$APROVAR = true;
		}
	}
}
if($APROVAR){
	$aItemAba[] = array("RDMAprovacao.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Aprovar/Reprovar");
}	


if(($RDM->NUM_MATRICULA_SOLICITANTE == $_SESSION["NUM_MATRICULA_RECURSO"])&& 
	($RDM->getSITUACAO_ATUAL()==$situacaoRDM->CRIADA)){	
	$aItemAba[] = array("RDMCancelamento.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Cancelar");
}else if ($RDM->getSITUACAO_ATUAL()==$situacaoRDM->AGUARDANDO_APROVACAO){
	if($RDM->getTIPO() == $RDM->EMERGENCIAL){
		if(($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isCoordenadorTI($_SESSION["SEQ_PERFIL_ACESSO"]))){	
			$aItemAba[] = array("RDMCancelamento.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Cancelar");
		}
	}else if($RDM->getTIPO() == $RDM->NORMAL){
		if(($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isCoordenadorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGerenteDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"]))){	
			$aItemAba[] = array("RDMCancelamento.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Cancelar");
		}
	}
}else if ( ($RDM->getSITUACAO_ATUAL()==$situacaoRDM->APROVADA)&& 
			($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
			 $pagina->isGerenteDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"])) ){
			$aItemAba[] = array("RDMCancelamento.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Cancelar");
}


$PLANEJAR = false; 
if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->APROVADA) || 
	($RDM->getSITUACAO_ATUAL()==$situacaoRDM->AGUARADANDO_EXECUCAO)){		
	if($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
		$pagina->isGerenteDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"])){
		$PLANEJAR = true;
	}		
}	
if($PLANEJAR){
	$aItemAba[] = array("#" , "tabact", "Planejar");	 
}			       

if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->AGUARADANDO_EXECUCAO ||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->EM_EXECUCAO||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->PARADA ||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->SUSPENSA  ||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->FALHA_NA_VALIDACAO ) &&
	($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"]) ||
	 $pagina->isExecutorDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"]))){
		$aItemAba[] = array("RDMExecutar.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Executar");
}

if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->EXECUTADA) &&
	($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"]) ||
	 $pagina->isExecutorDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"]))){			
		$aItemAba[] = array("RDMValidacao.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Validar");
}

//$aItemAba[] = array("RDMAnexos.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Anexos");
				 
$pagina->SetaItemAba($aItemAba);

 

// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================
require_once 'include/PHP/class/class.Sajax.php';
$Sajax = new Sajax();

function CarregarComboProfissional($v_SEQ_EQUIPE_TI_ATM){
	if($v_SEQ_EQUIPE_TI_ATM != "" ){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.recurso_ti.php';
		$pagina = new Pagina();
		$recurso_ti = new recurso_ti();
		$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI_ATM);
		//return $pagina->AjaxFormataArrayCombo($recurso_ti->combo("NOME"));
		return $pagina->AjaxFormataArrayCombo($recurso_ti->comboExecutordeMudancas("NOME"));
	}else{
		return "";
	}
}

function CarregarComboProfissionalRB($v_SEQ_EQUIPE_TI_ATRBM){
	if($v_SEQ_EQUIPE_TI_ATRBM != "" ){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.recurso_ti.php';
		$pagina = new Pagina();
		$recurso_ti = new recurso_ti();
		$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI_ATRBM);
		//return $pagina->AjaxFormataArrayCombo($recurso_ti->combo("NOME"));
		return $pagina->AjaxFormataArrayCombo($recurso_ti->comboExecutordeMudancas("NOME"));
		
		
	}else{
		return "";
	}
}

$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export(  "CarregarComboProfissional","CarregarComboProfissionalRB"  );
$Sajax->sajax_handle_client_request();


// Inicio do formulário
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_RDM", $v_SEQ_RDM);

print $pagina->CampoHidden("v_ACAO", $v_ACAO);

?>

<script language="javascript">
	<?
	$Sajax->sajax_show_javascript();
	?>
	//Chamada
	function do_CarregarComboProfissional() {
		x_CarregarComboProfissional(document.form.v_SEQ_EQUIPE_TI_ATM.value, retorno_CarregarComboProfissional);
	}
	// Retorno
	function retorno_CarregarComboProfissional(val) {
		if(val == ""){
			fEncheComboBoxPlus("", document.form.v_NUM_MATRICULA_RECURSO_ATM, "Escolha");
		}else{
			fEncheComboBox(val, document.form.v_NUM_MATRICULA_RECURSO_ATM);
		}
	}
	function do_CarregarComboProfissionalRB() {
		x_CarregarComboProfissionalRB(document.form.v_SEQ_EQUIPE_TI_ATRBM.value, retorno_CarregarComboProfissionalRB);
	}
	// Retorno
	function retorno_CarregarComboProfissionalRB(val) {
		if(val == ""){
			fEncheComboBoxPlus("", document.form.v_NUM_MATRICULA_RECURSO_ATRBM, "Escolha");
		}else{
			fEncheComboBox(val, document.form.v_NUM_MATRICULA_RECURSO_ATRBM);
		}
	}
 
	// =======================================================================
	// Controle de eventos
	// =======================================================================
	// Gestão de Eventos
	// Cross browser event handling for IE 5+, NS6+ and Gecko
	function addEvent(elm, evType, fn, useCapture){
		if (elm.addEventListener){
			// Gecko
			elm.addEventListener(evType, fn, useCapture);
			return true;
		}
		else if (elm.attachEvent){
			// Internet Explorer
			var r = elm.attachEvent('on' + evType, fn);
			return r;
		}else{
			// nutscrape?
			elm['on' + evType] = fn;
		}
	}

	function removeEvent(elm, evType, fn, useCapture){
        if (elm.removeEventListener) {
            // Gecko
            elm.removeEventListener(evType, fn, useCapture);
            return true;
        }
        else
            if (elm.attachEvent) {
                // Internet Explorer
                var r = elm.detachEvent('on' + evType, fn);
                return r;
            }
            else {
                // FF, NS etc..
                elm['on' + evType] = '';
            }
    }

	// Add Listeners
	function addListeners(e){
		// Before unload listener
		addEvent(window, 'beforeunload', exitAlert, false);
	}
	// Flag de validação da saída do fomulário
	var validarSaida = true;
	// Exit Alert
	function exitAlert(e){
		// default warning message
		var msg = "Confirma a saída? Esta ação ocasionará a perda das informações já preenchidas.";

		// set event
		if (!e) { e = window.event; }
		if (e) { e.returnValue = msg; }
		// return warning message
		return msg;
	}

	// Initialise
	//addEvent(window, 'load', addListeners, false);
	
	// ==================================================== FIM AJAX =====================================

	function fValidaFormAtividadesRDM(v_ACAO_ATM){
		 document.form.v_ACAO_ATM.value = v_ACAO_ATM;
		 if(document.form.v_NOM_ITEM_CONFIGURACAO_ATM.value == ""){
			 	alert("Preencha o campo Item de configuração");
			 	return false;
		 }
		 if(document.form.v_DESCRICAO_ATIVIDADE_ATM.value == ""){
			 	alert("Preencha o campo Descrição");
			 	return false;
		 }
		 if(document.form.v_DATA_EXECUCAO_ATM.value == ""){
			 	alert("Preencha o campo Data de execução");
			 	return false;
		 }
		 if(document.form.v_HORA_EXECUCAO_ATM.value == ""){
			 	alert("Preencha o campo Hora de execução");
			 	return false;
		 }
		 if(document.form.v_SEQ_EQUIPE_TI_ATM.value == ""){
			 	alert("Preencha o campo Executor(Equipe)");
			 	return false;
		 }
		 if(document.form.v_NUM_MATRICULA_RECURSO_ATM.value == ""){
			 	alert("Preencha o campo Executor(Profissional)");
			 	return false;
		 }
		 
		 if(!verificarDataMenorQueHoraAtual(document.form.v_DATA_EXECUCAO_ATM.value,document.form.v_HORA_EXECUCAO_ATM.value)){
				return false;
		 }
	 	 
		 return true;
  
	}
	function fValidaFormAtividadesRBRDM(v_ACAO_ATRBM){
		 document.form.v_ACAO_ATRBM.value = v_ACAO_ATRBM;
		 if(document.form.v_NOM_ITEM_CONFIGURACAO_ATRBM.value == ""){
			 	alert("Preencha o campo Item de configuração");
			 	return false;
		 }
		 if(document.form.v_DESCRICAO_ATIVIDADE_ATRBM.value == ""){
			 	alert("Preencha o campo Descrição");
			 	return false;
		 }
		 if(document.form.v_ORDEM_ATRBM.value == ""){
			 	alert("Preencha o campo Ordem da atividade");
			 	return false;
		 }
		 if(document.form.v_HORA_EXECUCAO_ATRBM.value == ""){
			 	alert("Preencha o campo Hora de execução");
			 	return false;
		 }
		 if(document.form.v_SEQ_EQUIPE_TI_ATRBM.value == ""){
			 	alert("Preencha o campo Executor");
			 	return false;
		 }
		 if(document.form.v_NUM_MATRICULA_RECURSO_ATRBM.value == ""){
			 	alert("Preencha o campo Executor(Profissional)");
			 	return false;
		 }

		 
	 	 
	 	return true;
 
	}

	function AnexaNovoArquivo($ID){
		if(document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).value != ""){
			document.getElementById("Newfile"+$ID).style.display = "none";
			$novo = $ID + 1;
			document.getElementById("file"+$novo).style.display = "block";
			document.getElementById("Newfile"+$novo).style.display = "block";
		}else{
			alert("É necessário anexar um arquivo antes de adionar um novo.");
			document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).focus();
		}
	}

	function fExibirAtividadesRDM(){
		if(document.getElementById("tabelaAtividadesRDM").style.display == "none"){
			document.getElementById("tabelaAtividadesRDM").style.display = "block";
			document.getElementById("MaisAtividadesRDM").style.display = "none";
			document.getElementById("MenosAtividadesRDM").style.display = "block";
		}else{
			document.getElementById("tabelaAtividadesRDM").style.display = "none";
			document.getElementById("MaisAtividadesRDM").style.display = "block";
			document.getElementById("MenosAtividadesRDM").style.display = "none";
		}
	}


	function fExcluirATM(vValor){
	    if(confirm("Desejar apagar o registro?")){
	    	document.form.v_ACAO_ATM.value='EXCLUIR_ATM';
	    	document.form.v_SEQ_ID_ATM.value=vValor;
			document.form.submit();
		}
	}
	function fExcluirATRBM(vValor){
	    if(confirm("Desejar apagar o registro?")){
	    	document.form.v_ACAO_ATRBM.value='EXCLUIR_ATRBM';
	    	document.form.v_SEQ_ID_ATRBM.value=vValor;
			document.form.submit();
		}
	}

	function fCancelarAtividadesRDM(){
		document.form.v_ACAO_ATM.value = "";
		document.form.v_NOM_ITEM_CONFIGURACAO_ATM.value = "";		 
		document.form.v_SEQ_ID_ATM.value = "";
		document.form.v_SEQ_ITEM_CONFIGURACAO_ATM.value = "";
		document.form.v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM.value = "";
		document.form.v_DESCRICAO_ATIVIDADE_ATM.value = "";
		document.form.v_DATA_EXECUCAO_ATM.value = "";
		document.form.v_HORA_EXECUCAO_ATM.value = "";
		document.form.v_SEQ_EQUIPE_TI_ATM.value = "";
		document.getElementById("tabelaAtividadesRDM").style.display = "none";
		document.getElementById("MaisAtividadesRDM").style.display = "block";
		document.getElementById("MenosAtividadesRDM").style.display = "none";	
		return false;	 
	}

	function fExibirAtividadesRBRDM(){
		if(document.getElementById("tabelaAtividadesRBRDM").style.display == "none"){
			document.getElementById("tabelaAtividadesRBRDM").style.display = "block";
			document.getElementById("MaisAtividadesRBRDM").style.display = "none";
			document.getElementById("MenosAtividadesRBRDM").style.display = "block";
		}else{
			document.getElementById("tabelaAtividadesRBRDM").style.display = "none";
			document.getElementById("MaisAtividadesRBRDM").style.display = "block";
			document.getElementById("MenosAtividadesRBRDM").style.display = "none";
		}
	}

	function fCancelarAtividadesRBRDM(){
		document.form.v_ACAO_ATRBM.value = "";
		document.form.v_NOM_ITEM_CONFIGURACAO_ATRBM.value = "";
		document.form.v_SEQ_ID_ATRBM.value = "";
		document.form.v_SEQ_ITEM_CONFIGURACAO_ATRBM.value = "";
		document.form.v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM.value = "";
		document.form.v_DESCRICAO_ATIVIDADE_ATRBM.value = "";
		document.form.v_ORDEM_ATRBM.value = "";
		//document.form.v_HORA_EXECUCAO_ATRBM.value == "";
		document.form.v_SEQ_EQUIPE_TI_ATRBM.value == "";		 
		document.getElementById("tabelaAtividadesRBRDM").style.display = "none";
		document.getElementById("MaisAtividadesRBRDM").style.display = "block";
		document.getElementById("MenosAtividadesRBRDM").style.display = "none";
		return false;
		 
	}

	
	 
</script>
<?		


$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
 
$pagina->FechaTabelaPadrao();

// ============================================================================================================
// FORMULARIO DE ATIVIDADES DA RDM
// ============================================================================================================
 
$v_EXIBIR_ATM = "";
//$vMsgErro = "";
// inicia a sessão
//session_start();
 
if (!isset($_SESSION['ID_ATIVIDADE_RDM']) && !isset($_SESSION['ATIVIDADES_RDM'])){
	//print "inicialização da sessão <br>";
  	$_SESSION['ID_ATIVIDADE_RDM'] = 0;
 	$_SESSION['ATIVIDADES_RDM'] = array();
}else if (isset($_SESSION['ID_ATIVIDADE_RDM']) && isset($_SESSION['ATIVIDADES_RDM'])){
	//print "recuperou atividades da sessao <br>";
	//print "Qtde: ".$_SESSION['ID_ATIVIDADE_RDM']."<br>";	
	//print "ID: "+ $_SESSION['ID_ATIVIDADE_RDM'] ."<br>";	 
}


//print "v_ACAO_ATM: ".$v_ACAO_ATM."<br>";
if($v_ACAO_ATM == "INCLUIR_ATM"){
	//validar resgistro
	$v_OK = true;
	for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RDM'][$i][1] == $v_SEQ_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][2] == $v_NOM_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][3] == $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][4] == $v_DESCRICAO_ATIVIDADE_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][5] == $v_DATA_EXECUCAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][6] == $v_HORA_EXECUCAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][7] == $v_SEQ_EQUIPE_TI_ATM&&
	 		$_SESSION['ATIVIDADES_RDM'][$i][8] == $v_NUM_MATRICULA_RECURSO_ATM){
	 			$vMsgErro		 = "Atividade já cadastrada!";
	 			$v_EXIBIR_ATM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RDM'][$i][5] == $v_DATA_EXECUCAO_ATM &&
	 			  $_SESSION['ATIVIDADES_RDM'][$i][6] == $v_HORA_EXECUCAO_ATM ){
	 			$vMsgErro	 = "Já existe uma atividade para esta data e hora!";
	 			$v_EXIBIR_ATM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}
	 }	
	 
	if($v_OK){
		$_SESSION['ATIVIDADES_RDM'][] = array($_SESSION['ID_ATIVIDADE_RDM']++,$v_SEQ_ITEM_CONFIGURACAO_ATM ,
					$v_NOM_ITEM_CONFIGURACAO_ATM,$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM,$v_DESCRICAO_ATIVIDADE_ATM,
					$v_DATA_EXECUCAO_ATM,$v_HORA_EXECUCAO_ATM,$v_SEQ_EQUIPE_TI_ATM);
		$v_SEQ_ITEM_CONFIGURACAO_ATM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATM = "";
		$v_DESCRICAO_ATIVIDADE_ATM = "";
		$v_DATA_EXECUCAO_ATM = "";
		$v_HORA_EXECUCAO_ATM = "";
		$v_SEQ_EQUIPE_TI_ATM = "";					
		$v_ACAO_ATM = "";		
	}	
					
				
}else if($v_ACAO_ATM == "ALTERAR_ATM"){
	$v_EXIBIR_ATM = "EXIBIR";
 	for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RDM'][$i][0] == $v_SEQ_ID_ATM){
	 		$v_SEQ_ITEM_CONFIGURACAO_ATM =$_SESSION['ATIVIDADES_RDM'][$i][1];
	 		$v_NOM_ITEM_CONFIGURACAO_ATM = $_SESSION['ATIVIDADES_RDM'][$i][2];
	 		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = $_SESSION['ATIVIDADES_RDM'][$i][3];
	 		$v_DESCRICAO_ATIVIDADE_ATM = $_SESSION['ATIVIDADES_RDM'][$i][4];
	 		$v_DATA_EXECUCAO_ATM =  $_SESSION['ATIVIDADES_RDM'][$i][5];
	 		$v_HORA_EXECUCAO_ATM = $_SESSION['ATIVIDADES_RDM'][$i][6];
	 		$v_SEQ_EQUIPE_TI_ATM = $_SESSION['ATIVIDADES_RDM'][$i][7];	 		
	 		$v_NUM_MATRICULA_RECURSO_ATM = $_SESSION['ATIVIDADES_RDM'][$i][8]; 
			
	 		break;
	 	}
	 }		
}else if($v_ACAO_ATM == "CONF_ALTERAR_ATM"){
	//validar resgistro
	$v_OK = true;
	for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RDM'][$i][0] != $v_SEQ_ID_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][1] == $v_SEQ_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][2] == $v_NOM_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][3] == $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][4] == $v_DESCRICAO_ATIVIDADE_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][5] == $v_DATA_EXECUCAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][6] == $v_HORA_EXECUCAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][7] == $v_SEQ_EQUIPE_TI_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][8] == $v_NUM_MATRICULA_RECURSO_ATM){
	 			$vMsgErro		 = "Atividade já cadastrada!";
	 			$v_EXIBIR_ATM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RDM'][$i][0] != $v_SEQ_ID_ATM &&
	 			  $_SESSION['ATIVIDADES_RDM'][$i][5] == $v_DATA_EXECUCAO_ATM &&
	 			  $_SESSION['ATIVIDADES_RDM'][$i][6] == $v_HORA_EXECUCAO_ATM ){
	 			$vMsgErro	 = "Já existe uma atividade para esta data e hora!";
	 			$v_EXIBIR_ATM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}
	 }	
	 if($v_OK){
		for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){ 		 
		 	if ($_SESSION['ATIVIDADES_RDM'][$i][0] == $v_SEQ_ID_ATM){
		 		$_SESSION['ATIVIDADES_RDM'][$i][1] = $v_SEQ_ITEM_CONFIGURACAO_ATM;
		 		$_SESSION['ATIVIDADES_RDM'][$i][2] = $v_NOM_ITEM_CONFIGURACAO_ATM;
		 		$_SESSION['ATIVIDADES_RDM'][$i][3] = $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM;
		 		$_SESSION['ATIVIDADES_RDM'][$i][4] = $v_DESCRICAO_ATIVIDADE_ATM;
		 		$_SESSION['ATIVIDADES_RDM'][$i][5] = $v_DATA_EXECUCAO_ATM;
		 		$_SESSION['ATIVIDADES_RDM'][$i][6] = $v_HORA_EXECUCAO_ATM;
		 		$_SESSION['ATIVIDADES_RDM'][$i][7] = $v_SEQ_EQUIPE_TI_ATM; 
		 		$_SESSION['ATIVIDADES_RDM'][$i][8] = $v_NUM_MATRICULA_RECURSO_ATM;
		 		break;
		 	}
		 }	
		$v_SEQ_ID_ATM = "";		
		$v_SEQ_ITEM_CONFIGURACAO_ATM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = "";
		$v_DESCRICAO_ATIVIDADE_ATM = "";
		$v_DATA_EXECUCAO_ATM = "";
		$v_HORA_EXECUCAO_ATM = "";
		$v_SEQ_EQUIPE_TI_ATM = "";					
		$v_ACAO_ATM = "";	
		$v_NUM_MATRICULA_RECURSO_ATM ="";
	 }
}else if($v_ACAO_ATM == "EXCLUIR_ATM"){
	for ($i = 0; $i <= count($_SESSION['ID_ATIVIDADE_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RDM'][$i][0] == $v_SEQ_ID_ATM){
	 		unset($_SESSION['ATIVIDADES_RDM'][$i]);			 
	 		break;
	 	}
	 }		
	 $v_ACAO_ATM = "";	
	 $v_SEQ_ID_ATM = "";	
}

//$_SESSION['ATIVIDADES_RDM'] = $atividadesRDM;

// Mostrar ou não os parâmetros
//if($flag == ""){ // Mostrar parâmetros
//	$MaisAtividadesRDM = "style=\"display: none;\" ";
//	$MenosAtividadesRDM = "";
//	$tabelaAtividadesRDM = "";
//}else{ // Não mostrar parâmetros
//	$MaisAtividadesRDM = "style=\"display: none;\" ";
//	$MenosAtividadesRDM = "";
//	$tabelaAtividadesRDM = "style=\"display: none;\" ";
//}

if($v_EXIBIR_ATM == "EXIBIR"){//mostrar
	$MaisAtividadesRDM = "style=\"display: none;\" ";
	$MenosAtividadesRDM = "";
	$tabelaAtividadesRDM = " ";
}else{//não mostrar
	$MaisAtividadesRDM = "style=\"display: none;\" ";
	$MenosAtividadesRDM = "style=\"display: none;\" ";
	$tabelaAtividadesRDM = "style=\"display: none;\" ";
}

 

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisAtividadesRDM\" $MaisAtividadesRDM cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormularioColspanDestaque("Alterar atividades da RDM  ", 2);
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MenosAtividadesRDM\" $MenosAtividadesRDM cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
//$pagina->LinhaCampoFormularioColspanDestaque("Adicionar atividades da RDM <a href=\"javascript: fExibirAtividadesRDM();\"><img border=\"0\" src=\"imagens/menos.jpg\"></a>", 2);
$pagina->LinhaCampoFormularioColspanDestaque("Alterar atividades da RDM  ", 2);
$pagina->FechaTabelaPadrao();

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaAtividadesRDM\" $tabelaAtividadesRDM");

print $pagina->CampoHidden("v_ACAO_ATM", $v_ACAO_ATM);
print $pagina->CampoHidden("v_SEQ_ID_ATM", $v_SEQ_ID_ATM);
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO_ATM", $v_SEQ_ITEM_CONFIGURACAO_ATM);
print $pagina->CampoHidden("v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM", $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM);

$pagina->LinhaCampoFormulario("Item configuracao:", "right", "S", 
									   $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO_ATM", "N", "" , "60", "60", "$v_NOM_ITEM_CONFIGURACAO_ATM", "readonly").
									   $pagina->ButtonProcuraItemConfiguracao("v_NOM_ITEM_CONFIGURACAO_ATM", "v_SEQ_ITEM_CONFIGURACAO_ATM","v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM")
									, "left", "id=".$pagina->GetIdTable());
// Descição do chamado
$pagina->LinhaCampoFormulario("Descrição:", "right", "S",
								  $pagina->CampoTextArea("v_DESCRICAO_ATIVIDADE_ATM", "N", "Descrição", "100", "5", "$v_DESCRICAO_ATIVIDADE_ATM", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
								  , "left", "id=".$pagina->GetIdTable());	

$pagina->LinhaCampoFormulario("Data/Hora de execução:", "right", "S",
			$pagina->CampoData("v_DATA_EXECUCAO_ATM", "N", "Data de execução ", $v_DATA_EXECUCAO_ATM,"")
			." ". $pagina->CampoHora("v_HORA_EXECUCAO_ATM", "N", "Hora de execução ", $v_HORA_EXECUCAO_ATM,"")			 
			, "left", "id=".$pagina->GetIdTable());



require_once 'include/PHP/class/class.equipe_ti.php';
$equipe_ti = new equipe_ti();

$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI");
$vItemTodosEquipe = "S";

if($v_ACAO_ATM == "ALTERAR_ATM"){
	for($i=0;$i<count($aItemOptionEquipe);$i++){
		if($aItemOptionEquipe[$i][0]==$v_SEQ_EQUIPE_TI_ATM){
			$aItemOptionEquipe[$i][1]="Selected";
		}
	}
}

$pagina->LinhaCampoFormulario("Executor (Equipe):", "right", "S",	
									$pagina->CampoSelect("v_SEQ_EQUIPE_TI_ATM", "N", "Equipe", "S", $aItemOptionEquipe, "Escolha", "do_CarregarComboProfissional();", "combo_profissional")							 
								   //$pagina->CampoSelect("v_SEQ_EQUIPE_TI_ATM", "N", "Executor", $vItemTodosEquipe, $aItemOptionEquipe, "Escolha", "do_CarregarComboProfissional();", "combo_equipe")
								  , "left", "id=".$pagina->GetIdTable());	

$aItemOptionProfissional = Array();
//$aItemOptionProfissional[] = array("", "", "Escolha");

if($v_SEQ_EQUIPE_TI_ATM != ""){	
	require_once 'include/PHP/class/class.recurso_ti.php';	
	$recurso_ti = new recurso_ti();
	$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI_ATM);	 
	//$aItemOptionProfissional = $recurso_ti->combo("NOME",$v_NUM_MATRICULA_RECURSO_ATM);
	$aItemOptionProfissional = $recurso_ti->comboExecutordeMudancas("NOME",$v_NUM_MATRICULA_RECURSO_ATM);	
}

								  
$pagina->LinhaCampoFormulario("Executor (profissional):", "right", "S",								 
								  $pagina->CampoSelect("v_NUM_MATRICULA_RECURSO_ATM", "N", "Profissional", "S", $aItemOptionProfissional, "Escolha", "", "combo_profissional")
								  , "left", "id=".$pagina->GetIdTable());									  
								  
								  
								  
if($v_ACAO_ATM == "ALTERAR_ATM"){
$pagina->LinhaCampoFormularioColspan("center",
		$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormAtividadesRDM('CONF_ALTERAR_ATM'); ", " Salvar Atividade ")
		." ".$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fCancelarAtividadesRDM(); ", " Cancelar ")
		, "2");

}else{
	$pagina->LinhaCampoFormularioColspan("center",
			$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormAtividadesRDM('INCLUIR_ATM'); ", " Salvar Atividade ")
			." ".$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fCancelarAtividadesRDM(); ", " Cancelar ")
			, "2");
}

$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");
$pagina->FechaTabelaPadrao();



// Inicio do grid de atividades
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Ordem", "");
$header[] = array("Item", "");
$header[] = array("Descrição", "");
$header[] = array("Data/Hora de execução", "");
$header[] = array("Equipe", "");
$header[] = array("Profissional", "");

if(count($_SESSION['ATIVIDADES_RDM']) == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro de atividade de RDM informado", count($header));
}else{
	//print "ANTES DA ORDENACAO <br>";
	//print_r($_SESSION['ATIVIDADES_RDM']);
	//ORDENAR ARRAY PELA DATA HORA
	if(count($_SESSION['ATIVIDADES_RDM'])>1){
		foreach ($_SESSION['ATIVIDADES_RDM'] as $key => $row) {
		    $data[$key] = $row[5];
		    $hora[$key] = $row[6];
		}
		array_multisort($data, SORT_ASC, $hora, SORT_ASC, $_SESSION['ATIVIDADES_RDM']);
	}
	//print "<br>DEPOIS DA ORDENACAO <br>";
	//print_r($_SESSION['ATIVIDADES_RDM']);
	
	$corpo = array();
	//$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Atividades da RDM", $header);
	 for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){
	 		$valor ="<a href=\"javascript:document.form.v_ACAO_ATM.value='ALTERAR_ATM';
		document.form.v_SEQ_ID_ATM.value=".$_SESSION['ATIVIDADES_RDM'][$i][0].";document.form.submit();\">
		<img src=\"imagens/alterar.gif\" alt=\"Alterar\" border=\"0\"></a>";
		
		//$valor.="<a href=\"#\" onclick=\"fExcluirATM(".$_SESSION['ATIVIDADES_RDM'][$i][0].");\"><img src=\"imagens/excluir.gif\" alt=\"Excluir\" border=\"0\"></a>";
	
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("center", "campo", $i+1);
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RDM'][$i][2]);
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RDM'][$i][4]);
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RDM'][$i][5]." ".$_SESSION['ATIVIDADES_RDM'][$i][6]);
		
		$executor= new equipe_ti();		 
		$executor->select($_SESSION['ATIVIDADES_RDM'][$i][7]);
		$corpo[] = array("left", "campo", $executor->NOM_EQUIPE_TI);
		
		$empregados = new empregados();
		$empregados->select($_SESSION['ATIVIDADES_RDM'][$i][8]);
		
		$corpo[] = array("left", "campo", $empregados->NOME);
	
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
} 

$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");



// ============================================================================================================
// FORMULARIO DE ATIVIDADES DE ROLLBACK
// ============================================================================================================

$v_EXIBIR_ATRBM = "";
//$vMsgErro = "";
// inicia a sessão
//session_start();
 
if (!isset($_SESSION['ID_ATIVIDADE_RB_RDM']) && !isset($_SESSION['ATIVIDADES_RB_RDM'])){
	//print "inicialização da sessão <br>";
  	$_SESSION['ID_ATIVIDADE_RB_RDM'] = 0;
 	$_SESSION['ATIVIDADES_RB_RDM'] = array();
}else if (isset($_SESSION['ID_ATIVIDADE_RB_RDM']) && isset($_SESSION['ATIVIDADES_RB_RDM'])){
	//print "recuperou atividades da sessao <br>";
	//print "Qtde: ".$_SESSION['ID_ATIVIDADE_RDM']."<br>";	
	//print "ID: "+ $_SESSION['ID_ATIVIDADE_RDM'] ."<br>";	 
}


//print "v_ACAO_ATRBM: ".$v_ACAO_ATRBM."<br>";
if($v_ACAO_ATRBM == "INCLUIR_ATRBM"){
	//validar resgistro
	$v_OK = true;
	for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RB_RDM'][$i][1] == $v_SEQ_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][2] == $v_NOM_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][3] == $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][4] == $v_DESCRICAO_ATIVIDADE_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][5] == $v_ORDEM_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][6] == $v_SEQ_EQUIPE_TI_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][7] == $v_NUM_MATRICULA_RECURSO_ATRBM){
	 			$vMsgErro		 = "Atividade já cadastrada!";
	 			$v_EXIBIR_ATRBM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RB_RDM'][$i][5] == $v_ORDEM_ATRBM){
	 			$vMsgErro	 = "Já existe uma atividade para esta ordem!";
	 			$v_EXIBIR_ATRBM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}
	 }	
	 
	if($v_OK){
		$_SESSION['ATIVIDADES_RB_RDM'][] = array($_SESSION['ID_ATIVIDADE_RB_RDM']++,$v_SEQ_ITEM_CONFIGURACAO_ATRBM ,
					$v_NOM_ITEM_CONFIGURACAO_ATRBM,$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM,$v_DESCRICAO_ATIVIDADE_ATRBM,$v_ORDEM_ATRBM,$v_SEQ_EQUIPE_TI_ATRBM,$v_NUM_MATRICULA_RECURSO_ATRBM);
		$v_SEQ_ITEM_CONFIGURACAO_ATRBM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATRBM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = "";
		$v_DESCRICAO_ATIVIDADE_ATRBM = "";
		$v_ORDEM_ATRBM = "";		 
		$v_SEQ_EQUIPE_TI_ATRBM = "";					
		$v_ACAO_ATRBM = "";		
		$v_NUM_MATRICULA_RECURSO_ATRBM = "";
	}	
					
				
}else if($v_ACAO_ATRBM == "ALTERAR_ATRBM"){
	$v_EXIBIR_ATRBM = "EXIBIR";
 	for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RB_RDM'][$i][0] == $v_SEQ_ID_ATRBM){
	 		$v_SEQ_ITEM_CONFIGURACAO_ATRBM = $_SESSION['ATIVIDADES_RB_RDM'][$i][1];
	 		$v_NOM_ITEM_CONFIGURACAO_ATRBM = $_SESSION['ATIVIDADES_RB_RDM'][$i][2];
	 		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = $_SESSION['ATIVIDADES_RB_RDM'][$i][3];
	 		$v_DESCRICAO_ATIVIDADE_ATRBM = $_SESSION['ATIVIDADES_RB_RDM'][$i][4];
	 		$v_ORDEM_ATRBM =  $_SESSION['ATIVIDADES_RB_RDM'][$i][5];	 		
	 		$v_SEQ_EQUIPE_TI_ATRBM = $_SESSION['ATIVIDADES_RB_RDM'][$i][6];	 
	 		$v_NUM_MATRICULA_RECURSO_ATRBM = $_SESSION['ATIVIDADES_RB_RDM'][$i][7];		 
			
	 		break;
	 	}
	 }		
}else if($v_ACAO_ATRBM == "CONF_ALTERAR_ATRBM"){
	//validar resgistro
	$v_OK = true;
	for ($i = 0; $i <= count($_SESSION['ATIVIDADES_RB_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RB_RDM'][$i][0] != $v_SEQ_ID_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][1] == $v_SEQ_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][2] == $v_NOM_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][3] == $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][4] == $v_DESCRICAO_ATIVIDADE_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][5] == $v_ORDEM_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][6] == $v_SEQ_EQUIPE_TI_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][7] == $v_NUM_MATRICULA_RECURSO_ATRBM){
	 			$vMsgErro		 = "Atividade já cadastrada!";
	 			$v_EXIBIR_ATRBM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RB_RDM'][$i][0] != $v_SEQ_ID_ATRBM &&
	 			  $_SESSION['ATIVIDADES_RB_RDM'][$i][5] == $v_ORDEM_ATRBM  ){
	 			$vMsgErro	 = "Já existe uma atividade para esta ordem!";
	 			$v_EXIBIR_ATRBM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}
	 }	
	 if($v_OK){
		for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){ 		 
		 	if ($_SESSION['ATIVIDADES_RB_RDM'][$i][0] == $v_SEQ_ID_ATRBM){
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][1] = $v_SEQ_ITEM_CONFIGURACAO_ATRBM;
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][2] = $v_NOM_ITEM_CONFIGURACAO_ATRBM;
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][3] = $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM;
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][4] = $v_DESCRICAO_ATIVIDADE_ATRBM;
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][5] = $v_ORDEM_ATRBM;		 		
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][6] = $v_SEQ_EQUIPE_TI_ATRBM; 
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][7] = $v_NUM_MATRICULA_RECURSO_ATRBM;
		 		break;
		 	}
		 }		
		$v_SEQ_ID_ATRBM = "";
		$v_SEQ_ITEM_CONFIGURACAO_ATRBM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATRBM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = "";
		$v_DESCRICAO_ATIVIDADE_ATRBM = "";
		$v_ORDEM_ATRBM = "";		 
		$v_SEQ_EQUIPE_TI_ATRBM = "";	
		$v_NUM_MATRICULA_RECURSO_ATRBM = "";				
		$v_ACAO_ATRBM = "";	
	 }
}else if($v_ACAO_ATRBM == "EXCLUIR_ATRBM"){
	for ($i = 0; $i < count($_SESSION['ID_ATIVIDADE_RB_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RB_RDM'][$i][0] == $v_SEQ_ID_ATRBM){
	 		unset($_SESSION['ATIVIDADES_RB_RDM'][$i]);			 
	 		break;
	 	}
	 }		
	 $v_ACAO_ATM = "";	
	 $v_SEQ_ID_ATM = "";	
}
 

if($v_EXIBIR_ATRBM == "EXIBIR"){//mostrar
	$MaisAtividadesRBRDM = "style=\"display: none;\" ";
	$MenosAtividadesRBRDM = "";
	$tabelaAtividadesRBRDM = " ";
}else{//não mostrar
	$MaisAtividadesRBRDM = "style=\"display: none;\" ";
	$MenosAtividadesRBRDM = "style=\"display: none;\" ";
	$tabelaAtividadesRBRDM = "style=\"display: none;\" ";
}

 

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisAtividadesRBRDM\" $MaisAtividadesRBRDM cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
//$pagina->LinhaCampoFormularioColspanDestaque("Adicionar atividades de rollback da RDM <a href=\"javascript: fExibirAtividadesRBRDM();\"><img border=\"0\" src=\"imagens/mais.jpg\"></a>", 2);
$pagina->LinhaCampoFormularioColspanDestaque("Alterar atividades de rollback da RDM  ", 2);
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MenosAtividadesRBRDM\" $MenosAtividadesRBRDM cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
//$pagina->LinhaCampoFormularioColspanDestaque("Adicionar atividades de rollback da RDM <a href=\"javascript: fExibirAtividadesRBRDM();\"><img border=\"0\" src=\"imagens/menos.jpg\"></a>", 2);
$pagina->LinhaCampoFormularioColspanDestaque("Alterar atividades de rollback da RDM  ", 2);
$pagina->FechaTabelaPadrao();

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaAtividadesRBRDM\" $tabelaAtividadesRBRDM");

print $pagina->CampoHidden("v_ACAO_ATRBM", $v_ACAO_ATRBM);
print $pagina->CampoHidden("v_SEQ_ID_ATRBM", $v_SEQ_ID_ATRBM);
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO_ATRBM", $v_SEQ_ITEM_CONFIGURACAO_ATRBM);
print $pagina->CampoHidden("v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM", $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM);

$pagina->LinhaCampoFormulario("Item configuracao:", "right", "S", 
									   $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO_ATRBM", "N", "" , "60", "60", "$v_NOM_ITEM_CONFIGURACAO_ATRBM", "readonly").
									   $pagina->ButtonProcuraItemConfiguracao("v_NOM_ITEM_CONFIGURACAO_ATRBM", "v_SEQ_ITEM_CONFIGURACAO_ATRBM","v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM")
									, "left", "id=".$pagina->GetIdTable());
// Descição do chamado
$pagina->LinhaCampoFormulario("Descrição:", "right", "S",
								  $pagina->CampoTextArea("v_DESCRICAO_ATIVIDADE_ATRBM", "N", "Descrição", "100", "5", "$v_DESCRICAO_ATIVIDADE_ATRBM", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
								  , "left", "id=".$pagina->GetIdTable());	

//$pagina->LinhaCampoFormulario("Data/Hora de execução:", "right", "S",
//			$pagina->CampoData("v_DATA_EXECUCAO_ATRBM", "N", "Data de execução ", $v_DATA_EXECUCAO_ATRBM,"")
//			." ". $pagina->CampoHora("v_HORA_EXECUCAO_ATRBM", "N", "Hora de execução ", $v_HORA_EXECUCAO_ATRBM,"")			 
//			, "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Ordem da atividade:", "right", "S", $pagina->CampoInt("v_ORDEM_ATRBM", "N", "Ordem da atividade", "3", "$v_ORDEM_ATRBM", ""), "left");

//
//require_once 'include/PHP/class/class.equipe_ti.php';
//$equipe_ti = new equipe_ti();
//
//$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI");
//$vItemTodosEquipe = "S";
// 
if($v_ACAO_ATRBM == "ALTERAR_ATRBM"){ 
	for($i=0;$i<count($aItemOptionEquipe);$i++){		 
		if($aItemOptionEquipe[$i][0]==$v_SEQ_EQUIPE_TI_ATRBM){
			$aItemOptionEquipe[$i][1]="Selected";
		}
	}
}
//
//$pagina->LinhaCampoFormulario("Executor:", "right", "S",								 
//								  $pagina->CampoSelect("v_SEQ_EQUIPE_TI_ATRBM", "N", "Executor", $vItemTodosEquipe, $aItemOptionEquipe, "Escolha", "", "combo_equipe")
//								  , "left", "id=".$pagina->GetIdTable());	
//								  

$pagina->LinhaCampoFormulario("Executor (Equipe):", "right", "S",	
									$pagina->CampoSelect("v_SEQ_EQUIPE_TI_ATRBM", "N", "Equipe", "S", $aItemOptionEquipe, "Escolha", "do_CarregarComboProfissionalRB();", "combo_profissional_rb")							 
								   //$pagina->CampoSelect("v_SEQ_EQUIPE_TI_ATM", "N", "Executor", $vItemTodosEquipe, $aItemOptionEquipe, "Escolha", "do_CarregarComboProfissional();", "combo_equipe")
								  , "left", "id=".$pagina->GetIdTable());	

$aItemOptionProfissional = Array();
//$aItemOptionProfissional[] = array("", "", "Escolha");

if($v_SEQ_EQUIPE_TI_ATRBM != ""){	
	require_once 'include/PHP/class/class.recurso_ti.php';	
	$recurso_ti = new recurso_ti();
	$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI_ATRBM);	 
	//$aItemOptionProfissional = $recurso_ti->combo("NOME",$v_NUM_MATRICULA_RECURSO_ATRBM);
	$aItemOptionProfissional = $recurso_ti->comboExecutordeMudancas("NOME",$v_NUM_MATRICULA_RECURSO_ATRBM);	
}

								  
$pagina->LinhaCampoFormulario("Executor (profissional):", "right", "S",								 
								  $pagina->CampoSelect("v_NUM_MATRICULA_RECURSO_ATRBM", "N", "Profissional", "S", $aItemOptionProfissional, "Escolha", "", "combo_profissional_rb")
								  , "left", "id=".$pagina->GetIdTable());									  
								  
								  
															  

if($v_ACAO_ATRBM == "ALTERAR_ATRBM"){
$pagina->LinhaCampoFormularioColspan("center",
		$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormAtividadesRBRDM('CONF_ALTERAR_ATRBM'); ", " Salvar Atividade ")
		." ".$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fCancelarAtividadesRBRDM(); ", " Cancelar ")
		, "2");

}else{
	$pagina->LinhaCampoFormularioColspan("center",
			$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormAtividadesRBRDM('INCLUIR_ATRBM'); ", " Salvar Atividade ")
			." ".$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fCancelarAtividadesRBRDM(); ", " Cancelar ")
			, "2");
}

$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");
$pagina->FechaTabelaPadrao();



// Inicio do grid de atividades
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Ordem", "");
$header[] = array("Item", "");
$header[] = array("Descrição", "");
//$header[] = array("Data/Hora de execução", "");
$header[] = array("Equipe", "");
$header[] = array("Profissinal", "");

if(count($_SESSION['ATIVIDADES_RB_RDM']) == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro de atividade de Rollback da RDM informado", count($header));
}else{
	//print "ANTES DA ORDENACAO <br>";
	//print_r($_SESSION['ATIVIDADES_RDM']);
	//ORDENAR ARRAY PELA DATA HORA
	foreach ($_SESSION['ATIVIDADES_RB_RDM'] as $key => $row) {
	    $ordem[$key] = $row[5];	     
	}
	array_multisort($ordem, SORT_ASC,  $_SESSION['ATIVIDADES_RB_RDM']);
	//print "<br>DEPOIS DA ORDENACAO <br>";
	//print_r($_SESSION['ATIVIDADES_RDM']);
	
	$corpo = array();
	//$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Atividades de Rollback da RDM", $header);
	 for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){
	 		$valor ="<a href=\"javascript:document.form.v_ACAO_ATRBM.value='ALTERAR_ATRBM';
		document.form.v_SEQ_ID_ATRBM.value=".$_SESSION['ATIVIDADES_RB_RDM'][$i][0].";document.form.submit();\">
		<img src=\"imagens/alterar.gif\" alt=\"Alterar\" border=\"0\"></a>";
		
		//$valor.="<a href=\"#\" onclick=\"fExcluirATRBM(".$_SESSION['ATIVIDADES_RB_RDM'][$i][0].");\"><img src=\"imagens/excluir.gif\" alt=\"Excluir\" border=\"0\"></a>";
	
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RB_RDM'][$i][5]);
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RB_RDM'][$i][2]);
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RB_RDM'][$i][4]);
		//$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RB_RDM'][$i][4]." ".$_SESSION['ATIVIDADES_RB_RDM'][$i][5]);
		$executor= new equipe_ti();		 
		$executor->select( $_SESSION['ATIVIDADES_RB_RDM'][$i][6]);
		$corpo[] = array("left", "campo", $executor->NOM_EQUIPE_TI);
		
		$empregados = new empregados();
		$empregados->select($_SESSION['ATIVIDADES_RB_RDM'][$i][7]);
		
		$corpo[] = array("left", "campo", $empregados->NOME);
		
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}

$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");

$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
$pagina->LinhaCampoFormularioColspanDestaque("&nbsp;", 2);
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
$pagina->LinhaCampoFormularioColspan("center", 
$pagina->CampoButton("if(fValidaForm()){document.form.v_ACAO.value='SALVAR';return true;}else{return false;}", " Salvar ")
." &nbsp;&nbsp;".$pagina->CampoButton("if(confirm('Confirma o envio da RDM para execução?')){document.form.v_ACAO.value='ENVIAR';return true;}else{return false;}", " Enviar para execução")
."&nbsp;&nbsp;<input type=\"button\" id=\"campo_texto\"     value=\"Reprovar\"   border=\"0\" onClick=\"javascript:document.location.href='RDMReprovarPlanejamento.php?v_SEQ_RDM=$v_SEQ_RDM';\" />"

,"2");

if($vMsgErro!=""){
	$pagina->ScriptAlert($vMsgErro);
}

$pagina->MontaRodape();