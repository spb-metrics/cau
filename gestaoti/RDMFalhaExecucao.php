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
$RDM = new rdm();
$situacaoRDM = new situacao_rdm();		 
$atividadeRDM = new atividade_rdm();
$atividadeRBRDM = new atividade_rb_rdm();
$atividadeRDM->select($v_SEQ_ID_ATM);

$pagina->ForcaAutenticacao();


// ============================================================================================================
// CAREGAR A TELA COM OS DADOS DA RDM
// ============================================================================================================
	

if($atividadeRDM->getSITUACAO()==$atividadeRDM->FALHA_NA_EXECUCAO){			
	$pagina->redirectTo("RDMExecutar.php?vMsgErro=Atividade j� finalizada com falha na execu��o!&v_SEQ_RDM=".$v_SEQ_RDM);
	//$pagina->redirectTo("RDMExecutar.php?v_SEQ_RDM=".$v_SEQ_RDM);
}else if($atividadeRDM->getSITUACAO()!=$atividadeRDM->EM_EXECUCAO){			
	$pagina->redirectTo("RDMExecutar.php?vMsgErro=Atividade n�o est� em execu��o!&v_SEQ_RDM=".$v_SEQ_RDM);
	//$pagina->redirectTo("RDMExecutar.php?v_SEQ_RDM=".$v_SEQ_RDM);
}else if($v_ACAO == "FALHA_NA_EXECUCAO"){
		$util = new util();
		$dataHoraAtual = $util->GetlocalTimeStamp();	
		$atividadeRDM->setDATA_HORA_FIM_EXECUCAO($dataHoraAtual);	 
		$atividadeRDM->updateFalhaExecucao($v_SEQ_ID_ATM);	
			 
		$RDM->setSITUACAO_ATUAL($situacaoRDM->FALHA_NA_EXECUCAO);
		$RDM->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);
		$RDM->updateSituacao($v_SEQ_RDM);
		
		$situacaoRDM->setSEQ_RDM($v_SEQ_RDM);
		$situacaoRDM->setSITUACAO($situacaoRDM->FALHA_NA_EXECUCAO);
		$situacaoRDM->setDATA_HORA($dataHoraAtual);	
		$situacaoRDM->setOBSERVACAO($v_OBSERVACAO);	
		$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
		$situacaoRDM->insert();	
		
		//$pagina->redirectTo("RDMExecutar.php?v_SEQ_RDM=".$v_SEQ_RDM);
		$pagina->redirectTo("RDMExecutarRollBack.php?v_SEQ_RDM=".$v_SEQ_RDM);
	 
}else{
	
	$pagina->SettituloCabecalho("Falha na Execu��o da RDM"); // Indica o t�tulo do cabe�alho da p�gina
		
	// pesquisa
	$RDM->select($v_SEQ_RDM);
	
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
		$aItemAba[] = array("RDMPlanejamento.php?v_SEQ_RDM=".$v_SEQ_RDM."&v_ACAO=PLANEJAR", "", "Planejar"); 
	}	

	if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->AGUARADANDO_EXECUCAO ||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->EM_EXECUCAO||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->PARADA ||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->SUSPENSA ||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->FALHA_NA_VALIDACAO ) &&
	($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"]) ||
	 $pagina->isExecutorDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"]))){
			$aItemAba[] = array("#" , "tabact", "Executar");
	}		

	if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->EXECUTADA) &&
	($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"]) ||
	 $pagina->isExecutorDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"]))){			
		$aItemAba[] = array("RDMValidacao.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Validar");
	 }
	 
	//$aItemAba[] = array("RDMAnexos.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Anexos");
	$pagina->SetaItemAba($aItemAba); 
	
	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	 
	 
	if($v_SEQ_RDM != "" && $v_SEQ_ID_ATM != ""  ){
		
		
		$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		
		$pagina->LinhaCampoFormularioColspanDestaque(" ", 2);
		
		$pagina->LinhaCampoFormulario("Observa��es:", "right", "S",  
	 	$pagina->CampoTextArea("v_OBSERVACAO", "S", "Observa��es", "99", "3", "$v_OBSERVACAO", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
		, "left", "id=".$pagina->GetIdTable());
		$pagina->FechaTabelaPadrao();
		
		$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
		$pagina->LinhaCampoFormularioColspan("center", 
		$pagina->CampoButton("if(fValidaForm()){if(confirm('Confirma a falha na execu��o da RDM?')){document.form.v_ACAO.value='FALHA_NA_EXECUCAO';return true;}else{return false;}}else{return false;}", " Salvar ")
		."&nbsp;".	 
		"<input type=\"button\" id=\"campo_texto\"     value=\"Voltar\"   border=\"0\" onClick=\"javascript:document.location.href='RDMExecutar.php?v_SEQ_RDM=$v_SEQ_RDM';\" />"	
//		$pagina->CampoButton("javascript:document.location.href='RDMExecutar.php?v_SEQ_RDM=$v_SEQ_RDM'", " Voltar ")
	
	,"2");
	$pagina->FechaTabelaPadrao();
		$pagina->FechaTabelaPadrao();	
	
	}
	
	if($vMsgErro!=""){
		$pagina->ScriptAlert($vMsgErro);
	}
	
	 	
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_RDM", $v_SEQ_RDM);
	print $pagina->CampoHidden("v_ACAO", $v_ACAO);
	print $pagina->CampoHidden("v_ACAO_ATM", "FALHA_NA_EXECUCAO_ATM");
	print $pagina->CampoHidden("v_SEQ_ID_ATM", $v_SEQ_ID_ATM); 
	$pagina->MontaRodape(); 

	
}
