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
$RDM = new rdm();
$situacaoRDM = new situacao_rdm(); 


$pagina->ForcaAutenticacao();


// ============================================================================================================
// CAREGAR A TELA COM OS DADOS DA RDM
// ============================================================================================================
	

if($v_ACAO == "REPROVAR_PLANEJAMENTO"){
		$RDMEmail = new rdm();
		$RDMEmail->setSEQ_RDM($v_SEQ_RDM);
		$RDMEmail->select($v_SEQ_RDM);
		
		$util = new util();
		$dataHoraAtual = $util->GetlocalTimeStamp();		 
		 	 
		
		$RDM->setSITUACAO_ATUAL($situacaoRDM->PLANEJAMENTO_REPROVADO);
		$RDM->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);
		$RDM->updateSituacao($v_SEQ_RDM);
		
		$situacaoRDM->setSEQ_RDM($v_SEQ_RDM);
		$situacaoRDM->setSITUACAO($situacaoRDM->PLANEJAMENTO_REPROVADO);
		$situacaoRDM->setDATA_HORA($dataHoraAtual);	
		$situacaoRDM->setOBSERVACAO($v_OBSERVACAO);	
		$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
		$situacaoRDM->insert();	
		
		// Enviar e-mail para o solicitante
		require_once 'include/PHP/class/class.rdm_email.php';
		$rdm_email = new rdm_email($pagina,$RDMEmail);
		$rdm_email->sendEmailRDMReprovada($v_OBSERVACAO);	
		
		//$pagina->redirectTo("RDMPlanejamento.php?v_SEQ_RDM=".$v_SEQ_RDM);
		$pagina->redirectTo("RDMDetalhe.php?v_SEQ_RDM=".$v_SEQ_RDM);
	 
}else{
	
	$pagina->SettituloCabecalho("Reprovar Planejamento da RDM"); // Indica o título do cabeçalho da página
		
	$RDM = new rdm(); 
	$situacaoRDM = new situacao_rdm();	
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
	
	// Inicio do formulário
	$pagina->MontaCabecalho();
	 
	 
	if($v_SEQ_RDM != ""  ){
		
		
		$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		
		$pagina->LinhaCampoFormularioColspanDestaque(" ", 2);
		
		$pagina->LinhaCampoFormulario("Observações:", "right", "S",  
	 	$pagina->CampoTextArea("v_OBSERVACAO", "S", "Observações", "99", "3", "$v_OBSERVACAO", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
		, "left", "id=".$pagina->GetIdTable());
		$pagina->FechaTabelaPadrao();
		
		$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
		$pagina->LinhaCampoFormularioColspan("center", 
		$pagina->CampoButton("if(fValidaForm()){if(confirm('Deseja Reprovar o planejamento da RDM?')){document.form.v_ACAO.value='REPROVAR_PLANEJAMENTO';return true;}else{return false;}}else{return false;}", " Reprovar ")
			 ."&nbsp;".	 
		"<input type=\"button\" id=\"campo_texto\"     value=\"Voltar\"   border=\"0\" onClick=\"javascript:document.location.href='RDMPlanejamento.php?v_SEQ_RDM=$v_SEQ_RDM';\" />"	
		
	
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
	print $pagina->CampoHidden("v_ACAO_ATM", "REPROVAR_PLANEJAMENTO_ATM");
	print $pagina->CampoHidden("v_SEQ_ID_ATM", $v_SEQ_ID_ATM); 
	$pagina->MontaRodape(); 

	
}
