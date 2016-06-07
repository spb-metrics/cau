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

$pagina = new Pagina();
$pagina->ForcaAutenticacao();
$RDM = new rdm(); 
$situacaoRDM = new situacao_rdm();		
	 
	
if($v_SEQ_RDM != "" && $v_ACAO==""){
	$RDM->select($v_SEQ_RDM);
}else if($v_SEQ_RDM != "" && $v_ACAO=="ANEXAR"){ 
	$RDM->select($v_SEQ_RDM);
	// INCLUIR ANEXOS DA RDM
	//require_once 'include/PHP/class/class.anexo_rdm.php';
	// Arquivo 1
	if($v_NOM_ARQUIVO_ORIGINAL_1 != ""){
		if($_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['size'] > 0){
			if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['size']/2048) <= 2048){
				// Inserir registro
				$anexo_rdm = new anexo_rdm();
				$anexo_rdm->setSEQ_RDM($RDM->SEQ_RDM);
				$anexo_rdm->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['name']);
				$anexo_rdm->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$anexo_rdm->setEXTENCAO_ARQUIVO(substr($_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['name'], strlen($_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['name'])-3, 3));
				$anexo_rdm->insert();
				if(!$pagina->MandaArquivo($anexo_rdm->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
					$vMsgErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 1.";
				}
			}else{
				$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['name'].", por exceder o tamanho de 2 Mb.";
			}
		}else{
			$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['name'].". Arquivo não existe.";
		}
	}
	
	// Arquivo 2
	if($v_NOM_ARQUIVO_ORIGINAL_2 != ""){
		if($_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['size'] > 0){
			if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['size']/2048) <= 2048){
				// Inserir registro
				$anexo_rdm = new anexo_rdm();
				$anexo_rdm->setSEQ_RDM($RDM->SEQ_RDM);
				$anexo_rdm->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['name']);
				$anexo_rdm->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$anexo_rdm->setEXTENCAO_ARQUIVO(substr($_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['name'], strlen($_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['name'])-3, 3));
				$anexo_rdm->insert();
				if(!$pagina->MandaArquivo($anexo_rdm->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
					$vMsgErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 2.";
				}
			}else{
				$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['name'].", por exceder o tamanho de 2 Mb.";
			}
		}else{
			$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['name'].". Arquivo não existe.";
		}
	}	
	
	// Arquivo 3
	if($v_NOM_ARQUIVO_ORIGINAL_3 != ""){
		if($_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['size'] > 0){
			if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['size']/2048) <= 2048){
				// Inserir registro
				$anexo_rdm = new anexo_rdm();
				$anexo_rdm->setSEQ_RDM($RDM->SEQ_RDM);
				$anexo_rdm->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['name']);
				$anexo_rdm->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$anexo_rdm->setEXTENCAO_ARQUIVO(substr($_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['name'], strlen($_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['name'])-3, 3));
				$anexo_rdm->insert();
				if(!$pagina->MandaArquivo($anexo_rdm->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
					$vMsgErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 3.";
				}
			}else{
				$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['name'].", por exceder o tamanho de 2 Mb.";
			}
		}else{
			$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['name'].". Arquivo não existe.";
		}
	}
	// Arquivo 4
	if($v_NOM_ARQUIVO_ORIGINAL_4 != ""){
		if($_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['size'] > 0){
			if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['size']/2048) <= 2048){
				// Inserir registro
				$anexo_rdm = new anexo_rdm();
				$anexo_rdm->setSEQ_RDM($RDM->SEQ_RDM);
				$anexo_rdm->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['name']);
				$anexo_rdm->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$anexo_rdm->setEXTENCAO_ARQUIVO(substr($_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['name'], strlen($_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['name'])-3, 3));
				$anexo_rdm->insert();
				if(!$pagina->MandaArquivo($anexo_rdm->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
					$vMsgErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 4.";
				}
			}else{
				$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['name'].", por exceder o tamanho de 2 Mb.";
			}
		}else{
			$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['name'].". Arquivo não existe.";
		}
	}
	// Arquivo 5
	if($v_NOM_ARQUIVO_ORIGINAL_5 != ""){
		if($_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['size'] > 0){
			if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['size']/2048) <= 2048){
				// Inserir registro
				$anexo_rdm = new anexo_rdm();
				$anexo_rdm->setSEQ_RDM($RDM->SEQ_RDM);
				$anexo_rdm->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['name']);
				$anexo_rdm->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$anexo_rdm->setEXTENCAO_ARQUIVO(substr($_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['name'], strlen($_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['name'])-3, 3));
				$anexo_rdm->insert();
				if(!$pagina->MandaArquivo($anexo_rdm->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
					$vMsgErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 5.";
				}
			}else{
				$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['name'].", por exceder o tamanho de 2 Mb.";
			}
		}else{
			$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['name'].". Arquivo não existe.";
		}
	}
	// Arquivo 6
	if($v_NOM_ARQUIVO_ORIGINAL_6 != ""){
		if($_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['size'] > 0){
			if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['size']/2048) <= 2048){
				// Inserir registro
				$anexo_rdm = new anexo_rdm();
				$anexo_rdm->setSEQ_RDM($RDM->SEQ_RDM);
				$anexo_rdm->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['name']);
				$anexo_rdm->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$anexo_rdm->setEXTENCAO_ARQUIVO(substr($_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['name'], strlen($_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['name'])-3, 3));
				$anexo_rdm->insert();
				if(!$pagina->MandaArquivo($anexo_rdm->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
					$vMsgErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 6.";
				}
			}else{
				$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['name'].", por exceder o tamanho de 2 Mb.";
			}
		}else{
			$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['name'].". Arquivo não existe.";
		}
	}
	// Arquivo 7
	if($v_NOM_ARQUIVO_ORIGINAL_7 != ""){
		if($_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['size'] > 0){
			if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['size']/2048) <= 2048){
				// Inserir registro
				$anexo_rdm = new anexo_rdm();
				$anexo_rdm->setSEQ_RDM($RDM->SEQ_RDM);
				$anexo_rdm->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['name']);
				$anexo_rdm->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$anexo_rdm->setEXTENCAO_ARQUIVO(substr($_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['name'], strlen($_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['name'])-3, 3));
				$anexo_rdm->insert();
				if(!$pagina->MandaArquivo($anexo_rdm->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
					$vMsgErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 7.";
				}
			}else{
				$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['name'].", por exceder o tamanho de 2 Mb.";
			}
		}else{
			$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['name'].". Arquivo não existe.";
		}
	}
	// Arquivo 8
	if($v_NOM_ARQUIVO_ORIGINAL_8 != ""){
		if($_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['size'] > 0){
			if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['size']/2048) <= 2048){
				// Inserir registro
				$anexo_rdm = new anexo_rdm();
				$anexo_rdm->setSEQ_RDM($RDM->SEQ_RDM);
				$anexo_rdm->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['name']);
				$anexo_rdm->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$anexo_rdm->setEXTENCAO_ARQUIVO(substr($_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['name'], strlen($_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['name'])-3, 3));
				$anexo_rdm->insert();
				if(!$pagina->MandaArquivo($anexo_rdm->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
					$vMsgErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 7.";
				}
			}else{
				$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['name'].", por exceder o tamanho de 2 Mb.";
			}
		}else{
			$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['name'].". Arquivo não existe.";
		}
	}
	// Arquivo 9
	if($v_NOM_ARQUIVO_ORIGINAL_9 != ""){
		if($_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['size'] > 0){
			if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['size']/2048) <= 2048){
				// Inserir registro
				$anexo_rdm = new anexo_rdm();
				$anexo_rdm->setSEQ_RDM($RDM->SEQ_RDM);
				$anexo_rdm->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['name']);
				$anexo_rdm->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$anexo_rdm->setEXTENCAO_ARQUIVO(substr($_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['name'], strlen($_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['name'])-3, 3));
				$anexo_rdm->insert();
				if(!$pagina->MandaArquivo($anexo_rdm->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
					$vMsgErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 7.";
				}
			}else{
				$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['name'].", por exceder o tamanho de 2 Mb.";
			}
		}else{
			$vMsgErro .= "RDM cadastrada, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['name'].". Arquivo não existe.";
		}
	}
			
	if($vMsgErro ==""){
	  	//$pagina->redirectTo("RDMAnexos.php?v_SEQ_RDM=$RDM->SEQ_RDM");
	  	$pagina->redirectTo("RDMDetalhe.php?v_SEQ_RDM=$RDM->SEQ_RDM");
	}
}else{
	$pagina->redirectTo("RDMPesquisa.php");
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
	
	$pagina->SettituloCabecalho("RDM Anexos - Importação"); // Indica o título do cabeçalho da página
	// Inicio do formulário
	$pagina->method = "post";
	$pagina->MontaCabecalho(1);
	print $pagina->CampoHidden("flag", "1");	 
	print $pagina->CampoHidden("v_SEQ_RDM", $v_SEQ_RDM);

// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================

?>

<script language="javascript">
 

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

	 function ExcluirArquivo($ID){
		document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).value = "";
		document.getElementById("file"+$ID).style.display = "none";
		document.getElementById("Newfile"+$ID).style.display = "none";
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

	
	 
</script>
<?	
	
	
// ============================================================================================================
//	ANEXOS
// ============================================================================================================
$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");

$pagina->LinhaCampoFormularioColspanDestaque("Selecione seus arquivos", 2);
$pagina->LinhaCampoFormulario("Anexo(s):", "right", "S",
								  $pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_1", "S", "Anexo(s)", "40","$v_NOM_ARQUIVO_ORIGINAL_1").
								  "
								  <span id=\"Newfile1\">
								  	<a href=\"javascript: AnexaNovoArquivo(1)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file2\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_2", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(2)\">Excluir</a>
								  </span>
								  <span id=\"Newfile2\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(2)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file3\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_3", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(3)\">Excluir</a>
								  </span>
								  <span id=\"Newfile3\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(3)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file4\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_4", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(4)\">Excluir</a>
								  </span>
								  <span id=\"Newfile4\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(4)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file5\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_5", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(5)\">Excluir</a>
								  </span>
								  <span id=\"Newfile5\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(5)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file6\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_6", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(6)\">Excluir</a>
								  </span>
								  <span id=\"Newfile6\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(6)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file7\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_7", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(7)\">Excluir</a>
								  </span>
								  <span id=\"Newfile7\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(7)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file8\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_8", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(8)\">Excluir</a>
								  </span>
								  <span id=\"Newfile8\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(8)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file9\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_9", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(9)\">Excluir</a>
								  </span>
								  "
								  , "left", "id=".$pagina->GetIdTable());
	$pagina->FechaTabelaPadrao();
	
	$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
	$pagina->LinhaCampoFormularioColspanDestaque("&nbsp;", 2);
	$pagina->FechaTabelaPadrao();

	$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
	$pagina->LinhaCampoFormularioColspan("center", 
	$pagina->CampoButton("if(fValidaForm()){document.form.v_ACAO.value='ANEXAR';return true;}else{return false;}", " Anexar Arquivos ") 
	,"2");
	$pagina->FechaTabelaPadrao();		
	
	
	

	$v_ACAO ="";
	print $pagina->CampoHidden("v_ACAO", $v_ACAO);
	if($vMsgErro!=""){
		$pagina->ScriptAlert($vMsgErro);
	}
	 
	
	$pagina->MontaRodape();
	

?>
