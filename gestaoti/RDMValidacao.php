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
require_once 'include/PHP/class/class.util.php';
require_once 'include/PHP/class/class.atividade_rdm.php';

$pagina = new Pagina();
$pagina->ForcaAutenticacao();

if($v_SEQ_RDM != ""){
// =======================================================================================================================
// ATUALIZAR STATUS
// =======================================================================================================================
	if($flag == "1"){
		$util = new util();
		$dataHoraAtual = $util->GetlocalTimeStamp(); 
		
		$situacaoRDM = new situacao_rdm();	
		$situacaoRDM->setSEQ_RDM($v_SEQ_RDM);
		
		if($v_ACAO=="FINALIZAR"){		 
			$situacaoRDM->setSITUACAO($situacaoRDM->FINALIZADA_COM_SUCESSO);
		}else if($v_ACAO=="FALHAR"){
			$situacaoRDM->setSITUACAO($situacaoRDM->FALHA_NA_VALIDACAO);			
			$atividadeRDM = new atividade_rdm();
			$atividadeRDM->updateReabrirByRDM($v_SEQ_RDM);	
		}else if($v_ACAO=="ROLLBACK"){
			$situacaoRDM->setSITUACAO($situacaoRDM->EXECUTANDO_ROLL_BACK);
		}
		$situacaoRDM->setDATA_HORA($dataHoraAtual);	
		$situacaoRDM->setOBSERVACAO($v_OBSERVACAO);	
		$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
		
		$RDM = new rdm();
		$RDM->setSEQ_RDM($v_SEQ_RDM);
		$RDM->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);
		$RDM->setSITUACAO_ATUAL($situacaoRDM->getSITUACAO());
		 
		$RDM->updateSituacao($v_SEQ_RDM);
		$situacaoRDM->insert();		
		
	 	if($v_ACAO=="FALHAR"){
			$pagina->redirectTo("RDMExecutar.php?v_SEQ_RDM=".$v_SEQ_RDM);
		}else if($v_ACAO=="ROLLBACK"){
			$pagina->redirectTo("RDMExecutarRollBack.php?v_SEQ_RDM=".$v_SEQ_RDM);
		}else {
			// Enviar e-mail para o solicitante
			$RDMEmail = new rdm();
			$RDMEmail->setSEQ_RDM($v_SEQ_RDM);
			$RDMEmail->select($v_SEQ_RDM); 
			require_once 'include/PHP/class/class.rdm_email.php';
			
			$rdm_email = new rdm_email($pagina,$RDMEmail);
			$rdm_email->sendEmailRDMFinalizacao();	
			
			$pagina->redirectTo("RDMDetalhe.php?v_SEQ_RDM=".$v_SEQ_RDM);
		}
	}
	
	$pagina->SettituloCabecalho("RDM - Valida��o P�s-implementa��o"); // Indica o t�tulo do cabe�alho da p�gina
	
	$RDM = new rdm(); 
	$situacaoRDM = new situacao_rdm();	
	
	// pesquisa
	$RDM->select($v_SEQ_RDM);
	
	
	// Itens das abas
	$aItemAba = Array();
	$aItemAba[] = array("RDMPesquisar.php?".$vLink."&vNumPagina=$vNumPagina", "", "Pesquisar");
	$aItemAba[] = array("RDMDetalhe.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Detalhes");
		
	//if(!($RDM->getSITUACAO_ATUAL()!=$situacaoRDM->CRIADA)){
	if(($RDM->NUM_MATRICULA_SOLICITANTE == $_SESSION["NUM_MATRICULA_RECURSO"])&&
		($RDM->getSITUACAO_ATUAL()==$situacaoRDM->CRIADA || 
		 $RDM->getSITUACAO_ATUAL()==$situacaoRDM->REPROVADA || 
		 $RDM->getSITUACAO_ATUAL()==$situacaoRDM->PLANEJAMENTO_REPROVADO)){
		$aItemAba[] = array("RDMAlteracao.php?v_SEQ_RDM=".$v_SEQ_RDM."&v_ACAO=ALTERAR", "", "Alterar");
	}
	
	$APROVAR = false;
	if($RDM->getSITUACAO_ATUAL()==$situacaoRDM->AGUARDANDO_APROVACAO){		 
		if($RDM->getTIPO()==$RDM->NORMAL){
			if($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
				$pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
				$pagina->isCoordenadorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
				$pagina->isGerenteDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"])){
				$APROVAR = true;
			}
		}else if($RDM->getTIPO()==$RDM->EMERGENCIAL){			
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
			$aItemAba[] = array("RDMExecutar.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Executar");
	}
	
	if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->EXECUTADA) &&
	($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"]) ||
	 $pagina->isExecutorDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"]))){
		$aItemAba[] = array("#" , "tabact", "Validar");	
	 }

	//$aItemAba[] = array("RDMAnexos.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Anexos");
	$pagina->SetaItemAba($aItemAba);
	
	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_RDM", $v_SEQ_RDM);
	print $pagina->CampoHidden("v_ACAO", $v_ACAO);
	
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	
	$pagina->LinhaCampoFormularioColspanDestaque(" Entrar em contato com respons�vel pela valida��o: <br> 
	<center>".$RDM->getNOME_RESP_CHECKLIST()." - ".$RDM->getDDD_TELEFONE_RESP_CHECKLIST()." - ".$RDM->getNUMERO_TELEFONE_RESP_CHECKLIST()."</center>", 2);
	
	$pagina->LinhaCampoFormulario("Observa��es:", "right", "S",  
 	$pagina->CampoTextArea("v_OBSERVACAO", "S", "Observa��es", "99", "3", "$v_OBSERVACAO", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
	, "left", "id=".$pagina->GetIdTable());
	$pagina->FechaTabelaPadrao();
	
	$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
	$pagina->LinhaCampoFormularioColspan("center", 
	$pagina->CampoButton("if(fValidaForm()){if(confirm('A RDM ter� seu estado alterado para falha na valida��o e a execu��o da RDM deve ser executada novamente.  Confirma ?')){document.form.v_ACAO.value='FALHAR';return true;}else{return false;}}else{return false;}", " Falha na Execu��o ")
	." &nbsp;&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("if(confirm('Confirma finaliza��o a RDM?')){document.form.v_ACAO.value='FINALIZAR';return true;}else{return false;}", " Finalizar RDM ")
	." &nbsp;&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("if(fValidaForm()){if(confirm('Confirma a execu��o de rollback a RDM?')){document.form.v_ACAO.value='ROLLBACK';return true;}else{return false;}}else{return false;}", " Executar RollBack da RDM ")

	,"2");
$pagina->FechaTabelaPadrao();
	$pagina->FechaTabelaPadrao();	
	$pagina->MontaRodape();

}else{
	$pagina->redirectTo("RDMPesquisa.php");
}