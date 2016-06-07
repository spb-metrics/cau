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
require_once 'include/PHP/class/class.util.php';

$pagina = new Pagina();
$pagina->ForcaAutenticacao();

if($v_SEQ_RDM != ""){
// =======================================================================================================================
// ATUALIZAR STATUS
// =======================================================================================================================
	if($flag == "1"){
		$RDMEmail = new rdm();
		$RDMEmail->setSEQ_RDM($v_SEQ_RDM);
		//$RDMEmail->select($v_SEQ_RDM);
		
		$util = new util();
		$dataHoraAtual = $util->GetlocalTimeStamp(); 
		
		$situacaoRDM = new situacao_rdm();	
		$situacaoRDM->setSEQ_RDM($v_SEQ_RDM);
		
		if($v_ACAO=="APROVAR"){		 
			$situacaoRDM->setSITUACAO($situacaoRDM->APROVADA);
		}else if($v_ACAO=="REPROVAR"){
			$situacaoRDM->setSITUACAO($situacaoRDM->REPROVADA);
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
		
		if($v_ACAO=="APROVAR"){		
			// pesquisa
			$RDM->select($v_SEQ_RDM); 
		  	// Enviar e-mail para o solicitante
			require_once 'include/PHP/class/class.rdm_email.php';
			$rdm_email = new rdm_email($pagina,$RDM);
			$rdm_email->sendEmailRDMAprovada($v_OBSERVACAO);		
		
		}else if($v_ACAO=="REPROVAR"){
			// pesquisa
			$RDMEmail->select($v_SEQ_RDM);
			// Enviar e-mail para o solicitante
			require_once 'include/PHP/class/class.rdm_email.php';
			$rdm_email = new rdm_email($pagina,$RDMEmail);
			$rdm_email->sendEmailRDMReprovada($v_OBSERVACAO);		
		
		}
		
		$pagina->redirectTo("RDMDetalhe.php?v_SEQ_RDM=".$v_SEQ_RDM);
	}
	
	$pagina->SettituloCabecalho("Aprovar/Reprovar RDM"); // Indica o título do cabeçalho da página
	
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
		$aItemAba[] = array("#" , "tabact", "Aprovar/Reprovar");
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
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->SUSPENSA  ||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->FALHA_NA_VALIDACAO) &&
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
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_RDM", $v_SEQ_RDM);
	print $pagina->CampoHidden("v_ACAO", $v_ACAO);
	
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	
	$pagina->LinhaCampoFormularioColspanDestaque(" ", 2);
	
	$pagina->LinhaCampoFormulario("Observações:", "right", "N",  
 	$pagina->CampoTextArea("v_OBSERVACAO", "S", "Observações", "99", "3", "$v_OBSERVACAO", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
	, "left", "id=".$pagina->GetIdTable());
	$pagina->FechaTabelaPadrao();
	
	$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
	$pagina->LinhaCampoFormularioColspan("center", 
	$pagina->CampoButton("if(fValidaForm()){if(confirm('Deseja reprovar a RDM?')){document.form.v_ACAO.value='REPROVAR';return true;}else{return false;}}else{return false;}", " Reprovar ")
	." &nbsp;&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("if(confirm('Deseja aprovar a RDM?')){document.form.v_ACAO.value='APROVAR';return true;}else{return false;}", " Aprovar ")

,"2");
$pagina->FechaTabelaPadrao();
	$pagina->FechaTabelaPadrao();	
	$pagina->MontaRodape();

}else{
	$pagina->redirectTo("RDMPesquisa.php");
}