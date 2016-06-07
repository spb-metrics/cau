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

$pagina = new Pagina();
$pagina->ForcaAutenticacao();
$RDM = new rdm(); 
$situacaoRDM = new situacao_rdm();		
	 
	
if($v_SEQ_RDM != ""){
	$RDM->select($v_SEQ_RDM);
}else{
	$pagina->redirectTo("RDMPesquisar.php");
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
		$aItemAba[] = array("RDMPlanejamento.php?v_SEQ_RDM=".$v_SEQ_RDM."&v_ACAO=PLANEJAR", "", "Planejar");
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
	 
	if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->EXECUTANDO_ROLL_BACK||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->FALHA_NA_EXECUCAO) &&
		($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"]) ||
		 $pagina->isExecutorDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"]))){			
			$aItemAba[] = array("RDMExecutarRollBack.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "RollBack");
			//$aItemAba[] = array("#" , "tabact", "RollBack");
	}
	
	$aItemAba[] = array("#", "tabact", "Anexos");
	 
	$pagina->SetaItemAba($aItemAba);
	
	$pagina->SettituloCabecalho("RDM Anexos"); // Indica o título do cabeçalho da página
	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_ACAO", $v_ACAO);
	print $pagina->CampoHidden("v_SEQ_RDM", $v_SEQ_RDM);
	
	$pagina->AbreTabelaResultado("center", "100%");
	
	$header = array();
	$header[] = array("Arquivo", "left", "45%", "header");
	$header[] = array("Data", "center", "17%", "header");
	$header[] = array("Responsável", "center", "", "header");
	
	require_once 'include/PHP/class/class.anexo_rdm.php';
	$anexo_rdm = new anexo_rdm();
	$anexo_rdm->setSEQ_RDM($RDM->SEQ_RDM);
	$anexo_rdm->selectParam("NOM_ARQUIVO_ORIGINAL");
	
	if($anexo_rdm->database->rows > 0){
		//$pagina->fQuantidadeRegistros($anexo_rdm->rowCount, $anexo_rdm->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Arquivos anexados ", $header);
		$corpo = array();
		while ($row = pg_fetch_array($anexo_rdm->database->result)){
			$corpo[] = array("left", "campo", "<a target=\"_blank\" href=\"../cau/anexos/".$row["nom_arquivo_sistema"]."\">".$row["nom_arquivo_original"]."</a>");
		 	$corpo[] = array("left", "campo", $row["dth_anexo"]);	
			$corpo[] = array("left", "campo", $row["nom_colaborador"]);	
			$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='RDMDetalhe.php".$vLink."&vNumPagina=$vNumPagina&v_SEQ_RDM=".$row["seq_rdm"]."';\"");
			$corpo = "";
				 
		 	//$header[] = array("<a target=\"_blank\" href=\"../cau/anexos/".$row["nom_arquivo_sistema"]."\">".$row["nom_arquivo_original"]."</a>", "left", "", "");
			//$header[] = array($row["dth_anexo"], "left", "", "");
			//$header[] = array($row["nom_colaborador"], "left", "", "");
			
		}
		$pagina->FechaTabelaPadrao();
		//$pagina->fMontaPaginacao($anexo_rdm->rowCount, $anexo_rdm->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_RDM=$v_SEQ_RDM");
	
	}else{
		$pagina->LinhaColspan("center", "Nenhum arquivo anexado.", "2", "header");
		$pagina->LinhaColspan("left", "Nenhum arquivo anexado.", "2", "campo");
		$pagina->FechaTabelaPadrao();
	}
	
	$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
	$pagina->LinhaCampoFormularioColspanDestaque("<a href=\"RDMAnexosImportacao.php?v_SEQ_RDM=".$v_SEQ_RDM."\">Anexar mais arquivos</a>", 2);
	$pagina->FechaTabelaPadrao();
	
	$pagina->MontaRodape();
	

?>
