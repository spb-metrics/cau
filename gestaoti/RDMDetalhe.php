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
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

if($v_SEQ_RDM != ""){
	require_once 'include/PHP/class/class.rdm.php';
	require_once 'include/PHP/class/class.situacao_rdm.php';
	require 'include/PHP/class/class.servidor.php';
	require 'include/PHP/class/class.item_configuracao.php';
	$RDM = new rdm();
	$situacaoRDM = new situacao_rdm();	
		
	if($v_ACAO=="ENVIAR"){
		require_once 'include/PHP/class/class.util.php';
		$util = new util();
		$dataHoraAtual = $util->GetlocalTimeStamp(); 
		
		$RDMEnviar = new rdm();
		$situacaoRDMEnviar = new situacao_rdm();	
		
		$RDMEnviar->setSEQ_RDM($v_SEQ_RDM);
		$RDMEnviar->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);
		$RDMEnviar->setSITUACAO_ATUAL($situacaoRDMEnviar->AGUARDANDO_APROVACAO);		
		
		$situacaoRDMEnviar->setSEQ_RDM($v_SEQ_RDM);	
		$situacaoRDMEnviar->setSITUACAO($situacaoRDMEnviar->AGUARDANDO_APROVACAO);
		$situacaoRDMEnviar->setDATA_HORA($dataHoraAtual);	
		$situacaoRDMEnviar->setOBSERVACAO("Enviada para aprovação");	
		$situacaoRDMEnviar->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
		
		$RDMEnviar->updateSituacao($v_SEQ_RDM);
		$situacaoRDMEnviar->insert();	
		$v_ACAO ="";
		
		$RDMEmail = new rdm();
		$RDMEmail->select($v_SEQ_RDM); 
		// Enviar e-mail para o solicitante
		require_once 'include/PHP/class/class.rdm_email.php';
		$rdm_email = new rdm_email($pagina,$RDMEmail);
		$rdm_email->sendEmailRDMEnviadaParaAprovacao();		
		
	}
	
	$vLink = "?flag=1";
	$vLink .="&v_SEQ_RDM_PESQUISA=$v_SEQ_RDM_PESQUISA";
	$vLink .="&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE";
	$vLink .="&v_TITULO=$v_TITULO";
	$vLink .="&v_NOME_RESP_CHECKLIST=$v_NOME_RESP_CHECKLIST";
	$vLink .="&v_SEQ_SITUACAO_RDM=$v_SEQ_SITUACAO_RDM";
	$vLink .="&v_DTH_ABERTURA=$v_DTH_ABERTURA";
	$vLink .="&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL";
	$vLink .="&v_DATA_HORA_PREVISTA_EXECUCAO=$v_DATA_HORA_PREVISTA_EXECUCAO";
	$vLink .="&v_DATA_HORA_PREVISTA_EXECUCAO_FINAL=$v_DATA_HORA_PREVISTA_EXECUCAO_FINAL";
	$vLink .="&v_DTH_EXECUCAO=$v_DTH_EXECUCAO";
	$vLink .="&v_DTH_EXECUCAO_FINAL=$v_DTH_EXECUCAO_FINAL";	
	
	$pagina->SettituloCabecalho("Detalhamento da RDM"); // Indica o título do cabeçalho da página
	
	// pesquisa
	$RDM->select($v_SEQ_RDM);
	
	// Itens das abas	
	$aItemAba = Array();
	$aItemAba[] = array("RDMPesquisar.php?".$vLink."&vNumPagina=$vNumPagina", "", "Pesquisar");
	$aItemAba[] = array("#", "tabact", "Detalhes");
	
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
	
	//$aItemAba[] = array("RDMAnexos.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Anexos");
	 
	$pagina->SetaItemAba($aItemAba);

	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");	
	
	print $pagina->CampoHidden("v_SEQ_RDM", $v_SEQ_RDM);
	print $pagina->CampoHidden("v_ACAO", $v_ACAO);
		
	if(($RDM->NUM_MATRICULA_SOLICITANTE == $_SESSION["NUM_MATRICULA_RECURSO"])&&
		($RDM->getSITUACAO_ATUAL()==$situacaoRDM->CRIADA || 
		 $RDM->getSITUACAO_ATUAL()==$situacaoRDM->REPROVADA || 
		 $RDM->getSITUACAO_ATUAL()==$situacaoRDM->PLANEJAMENTO_REPROVADO)){
			$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");	
			$pagina->LinhaCampoFormularioColspanDestaque("Aprovação", 2);
			$pagina->LinhaCampoFormulario("RDM não enviada para aprovação:", "right", "N", 
			$pagina->CampoButton("document.form.v_ACAO.value='ENVIAR';", " Enviar "), "left", "id=".$pagina->GetIdTable(),"23%","");
			$pagina->FechaTabelaPadrao();
	}
	
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");	
	$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais", 2);
	 
	
	$pagina->LinhaCampoFormulario("Número:", "right", "N", "$v_SEQ_RDM", "left", "id=".$pagina->GetIdTable(),"23%","");
	$pagina->LinhaCampoFormulario("Tipo:", "right", "N", $RDM->getTipoDescricao($RDM->TIPO), "left", "id=".$pagina->GetIdTable(),"23%","");
	$pagina->LinhaCampoFormulario("Situação Atual:", "right", "N", $situacaoRDM->getDescricao($RDM->SITUACAO_ATUAL), "left", "id=".$pagina->GetIdTable(),"23%","");
	
	$pagina->LinhaCampoFormulario("Título:", "right", "N", "$RDM->TITULO", "left", "id=".$pagina->GetIdTable(),"","");
	$pagina->LinhaCampoFormulario("Justificativa:", "right", "N", "$RDM->JUSTIFICATIVA", "left", "id=".$pagina->GetIdTable(),"","");
	$pagina->LinhaCampoFormulario("Impacto de não executar:", "right", "N", "$RDM->IMPACTO_NAO_EXECUTAR", "left", "id=".$pagina->GetIdTable(),"","");
	$pagina->LinhaCampoFormulario("Data/Hora de Abertura:", "right", "N", "$RDM->DATA_HORA_ABERTURA", "left", "id=".$pagina->GetIdTable(),"","");
	$pagina->LinhaCampoFormulario("Data/Hora prevista execução:", "right", "N", "$RDM->DATA_HORA_PREVISTA_EXECUCAO", "left", "id=".$pagina->GetIdTable(),"","");
	$pagina->LinhaCampoFormulario("Data/Hora da última atualização:", "right", "N", "$RDM->DATA_HORA_ULTIMA_ATUALIZACAO", "left", "id=".$pagina->GetIdTable(),"","");
	
	$pagina->LinhaCampoFormulario("Responsável pelo checklist:", "right", "N", 
	$RDM->NOME_RESP_CHECKLIST." <br> ". $RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".
	$RDM->NUMERO_TELEFONE_RESP_CHECKLIST." <br>".$RDM->EMAIL_RESP_CHECKLIST
	, "left", "id=".$pagina->GetIdTable(),"","");
	 
// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================
?>
<script language="javascript">
	 

		// =======================================================================
		// Configuração das TABS
		// =======================================================================
		function fMostra(id, idTab){
			//alert("fMostra = "+validarSaida);
			//validarSaida = false;
			document.getElementById("tabelaMeusDados").style.display = "none";
			document.getElementById("tabMeusDados").attributes["class"].value = "";

			document.getElementById("tabelaAtividadeRDM").style.display = "none";
			document.getElementById("tabAtividadeRDM").attributes["class"].value = "";

			document.getElementById("tabelaAtividadeRBRDM").style.display = "none";
			document.getElementById("tabAtividadeRBRDM").attributes["class"].value = "";

			document.getElementById("tabelaWorkFlow").style.display = "none";
			document.getElementById("tabWorkFlow").attributes["class"].value = "";

			document.getElementById("tabelaAnexos").style.display = "none";
			document.getElementById("tabAnexos").attributes["class"].value = "";

			document.getElementById("tabelaChamados").style.display = "none";
			document.getElementById("tabChamados").attributes["class"].value = ""; 
			
			document.getElementById(id).style.display = "block";
			document.getElementById(idTab).attributes["class"].value = "tabact";

			//validarSaida = true;
		}

		// =======================================================================
		// Controle de Saída da Página
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
			//alert("Exit = "+validarSaida);
			if(validarSaida) {
				if(document.form.v_FLG_ATENDIMENTO_INICIADO.value == 1){
					// default warning message
					var msg = "Tem certeza que deseja sair da tela de atendimento antes de parar o atendimento do chamado?";

					// set event
					if (!e) { e = window.event; }
					if (e) { e.returnValue = msg; }
					// return warning message
					return msg;
				}
			}
		}

		// Initialise
		//addEvent(window, 'load', addListeners, false);
		// <input type="button" class="button" value="Save" onclick="removeEvent(window, 'beforeunload', exitAlert, false); location.href='../list/overview.asp'" />


</script>	
<?	
	$aItemAba = Array();	
	$aItemAba[] = array("javascript:fMostra('tabelaMeusDados','tabMeusDados')", "", "&nbsp;Solicitante&nbsp;", "tabMeusDados", ""); 
	$aItemAba[] = array("javascript:fMostra('tabelaAtividadeRDM','tabAtividadeRDM')", "", "&nbsp;Atividades da RDM&nbsp;", "tabAtividadeRDM", "");
	$aItemAba[] = array("javascript:fMostra('tabelaAtividadeRBRDM','tabAtividadeRBRDM')", "", "&nbsp;Atividades de Rollback&nbsp;", "tabAtividadeRBRDM",  "");
	$aItemAba[] = array("javascript:fMostra('tabelaWorkFlow','tabWorkFlow')", "", "&nbsp;Histórico&nbsp;", "tabWorkFlow",  "");	
	$aItemAba[] = array("javascript:fMostra('tabelaAnexos','tabAnexos')", "", "&nbsp;Anexo(s)&nbsp;", "tabAnexos", "");
	$aItemAba[] = array("javascript:fMostra('tabelaChamados','tabChamados')", "", "&nbsp;Chamado(s) associado(s)&nbsp;", "tabChamados", "");
	 
	$pagina->SetaItemAba($aItemAba);

	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");
	
	//================================================================================================================================
	// Dados do Solicitante
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaMeusDados style=\"display: block;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações sobre o solicitante", 2);
	require_once 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();
	$empregados->select($RDM->NUM_MATRICULA_SOLICITANTE);

	$tabela = array();
	$header = array();
	// Nome
	$header = array();
	$header[] = array("Nome:", "center", "23%", "label");
	$header[] = array($empregados->NOME, "left", "", "campo");
	$tabela[] = $header;

	// Dependência
	$header = array();
	$header[] = array("Diretoria:", "center", "", "label");
	$header[] = array($empregados->DEP_SIGLA, "left", "", "campo");
	$tabela[] = $header;

	// Lotação
	$header = array();
	$header[] = array("Lotação:", "center", "", "label");
	$header[] = array($empregados->UOR_SIGLA, "left", "", "campo");
	$tabela[] = $header;

	// E-mail
	$header = array();
	$header[] = array("E-mail:", "center", "", "label");
	$header[] = array($empregados->DES_EMAIL, "left", "", "campo");
	$tabela[] = $header;

	// Matrícula
	$header = array();
	$header[] = array("Matrícula:", "center", "", "label");
	$header[] = array($empregados->NUM_MATRICULA_RECURSO, "left", "", "campo");
	$tabela[] = $header;

	// Ramal
	$header = array();
	$header[] = array("Ramal:", "center", "", "label");
	$header[] = array($empregados->NUM_DDD." ".$empregados->NUM_VOIP, "left", "", "campo");
	$tabela[] = $header; 
	
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	$pagina->FechaTabelaPadrao();
	//================================================================================================================================
	// Atividades da RDM
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAtividadeRDM style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Atividades da RDM", 2);
	
	require_once 'include/PHP/class/class.atividade_rdm.php';
	$atividadeRDM = new atividade_rdm();
	$atividadeRDM->setSEQ_RDM($RDM->SEQ_RDM);
	$atividadeRDM->selectParam("ORDEM");
	
	if($atividadeRDM->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhuma atividade encontrada.", 2);
	}else{
		$tabela = array();
		$header = array();
		$header[] = array("Ordem", "left", "", "header");
		$header[] = array("Descrição", "center", "", "header");
		$header[] = array("Item", "center", "", "header");
		$header[] = array("Responsável", "center", "", "header");
		$header[] = array("Situação", "center", "", "header");
		$header[] = array("Prevista  execução", "center", "", "header");
		$header[] = array("Início da execução", "center", "", "header");
		$header[] = array("Fim da execução", "center", "", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($atividadeRDM->database->result)){
			$header = array();
			$header[] = array($row["ordem"], "left", "", "");
			$header[] = array($row["descricao"], "left", "", "");
			
			if($row["seq_servidor"]!=""){
				// Servidores				 
				$servidor = new servidor();	
				$servidor->select($row["seq_servidor"]);	
				$header[] = array($servidor->NOM_SERVIDOR, "left", "", "");
			}else if($row["seq_item_configuracao"]!=""){
				// Sistemas de informação				
				$sistemas = new item_configuracao();				 
				$sistemas->select($row["seq_item_configuracao"]);	
				$header[] = array($sistemas->NOM_ITEM_CONFIGURACAO, "left", "", "");
			} 
			
			require_once 'include/PHP/class/class.equipe_ti.php';
			$executor= new equipe_ti();		 
			$executor->select($row["seq_equipe_ti"]);
			//$header[] = array($executor->NOM_EQUIPE_TI, "left", "", "");

			$empregados = new empregados();
			$empregados->select($row["num_matricula_recurso"]);
		
			//$corpo[] = array("left", "campo", $empregados->NOME);
			$header[] = array($executor->NOM_EQUIPE_TI."<br>".$empregados->NOME, "left", "", "");
			
			//	situação
			$header[] = array($atividadeRDM->getDescricaoSituacaoAtividade($row["situacao"]), "left", "", "");
			
			$header[] = array($row["data_hora_prevista_execucao"], "left", "", "");
			$header[] = array($row["data_hora_inicio_execucao"], "left", "", "");
			$header[] = array($row["data_hora_fim_execucao"], "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
	
	}
	
	$pagina->FechaTabelaPadrao();
	
	//================================================================================================================================
	// Atividades de Rollback da RDM
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAtividadeRBRDM style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Atividades de RollBack da RDM", 2);
	require_once 'include/PHP/class/class.atividade_rb_rdm.php';	
	$atividadeRBRDM = new atividade_rb_rdm();
	
	$atividadeRBRDM->setSEQ_RDM($RDM->SEQ_RDM);
	$atividadeRBRDM->selectParam("ORDEM");
	
	if($atividadeRBRDM->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhuma atividade encontrada.", 2);
	}else{
		$tabela = array();
		$header = array();
		$header[] = array("Ordem", "left", "", "header");
		$header[] = array("Descrição", "center", "", "header");
		$header[] = array("Item", "center", "", "header");
		$header[] = array("Responsável", "center", "", "header");
		$header[] = array("Situação", "center", "", "header");		 
		$header[] = array("início da execução", "center", "", "header");
		$header[] = array("fim da execução", "center", "", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($atividadeRBRDM->database->result)){
			$header = array();
			$header[] = array($row["ordem"], "left", "", "");
			$header[] = array($row["descricao"], "left", "", "");
			
			if($row["seq_servidor"]!=""){
				// Servidores				 
				$servidor = new servidor();	
				$servidor->select($row["seq_servidor"]);	
				$header[] = array($servidor->NOM_SERVIDOR, "left", "", "");
			}else if($row["seq_item_configuracao"]!=""){
				// Sistemas de informação				
				$sistemas = new item_configuracao();				 
				$sistemas->select($row["seq_item_configuracao"]);	
				$header[] = array($sistemas->NOM_ITEM_CONFIGURACAO, "left", "", "");
			} 
			
			require_once 'include/PHP/class/class.equipe_ti.php';
			$executor= new equipe_ti();		 
			$executor->select($row["seq_equipe_ti"]);
			//$header[] = array($executor->NOM_EQUIPE_TI, "left", "", "");
			
			$empregados = new empregados();
			$empregados->select($row["num_matricula_recurso"]);
		
			//$corpo[] = array("left", "campo", $empregados->NOME);
			$header[] = array($executor->NOM_EQUIPE_TI."<br>".$empregados->NOME, "left", "", "");
			

			//	situação
			$header[] = array($atividadeRBRDM->getDescricaoSituacaoAtividadeRB($row["situacao"]), "left", "", "");
						 
			$header[] = array($row["data_hora_inicio_execucao"], "left", "", "");
			$header[] = array($row["data_hora_fim_execucao"], "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
	
	}
	$pagina->FechaTabelaPadrao();
	
	//================================================================================================================================
	// WorkFlow  da RDM
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaWorkFlow style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("WorkFlow da  RDM", 2);
	$situacaoRDM->setSEQ_RDM($RDM->SEQ_RDM);
	$situacaoRDM->selectParam("data_hora");
	
	if($situacaoRDM->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhuma atividade encontrada.", 2);
	}else{
		$tabela = array();
		$header = array();
		 
		$header[] = array("Situação", "center", "", "header");
		$header[] = array("Data/hora ", "center", "", "header");
		$header[] = array("Observação", "center", "", "header");
		$header[] = array("Responsável", "center", "", "header");
		 
		$tabela[] = $header;

		while ($row = pg_fetch_array($situacaoRDM->database->result)){
			$header = array();
			//	situação
			$header[] = array($situacaoRDM->getDescricao($row["situacao"]), "left", "", ""); 
			$header[] = array($row["data_hora"], "left", "", "");			
			$header[] = array($row["observacao"], "left", "", ""); 
			require_once 'include/PHP/class/class.empregados.oracle.php';
			$empregados = new empregados();
			$empregados->select($row["num_matricula_recurso"] );	 
			
			$header[] = array($empregados->NOME, "left", "", ""); 
						
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
	
	}
	$pagina->FechaTabelaPadrao();
	
	//================================================================================================================================
	// Anexo
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAnexos style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Arquivos anexados a RDM", 2);

	require_once 'include/PHP/class/class.anexo_rdm.php';
	$anexo_rdm = new anexo_rdm();
	$anexo_rdm->setSEQ_RDM($RDM->SEQ_RDM);
	$anexo_rdm->selectParam("NOM_ARQUIVO_ORIGINAL");
	if($anexo_rdm->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum arquivo anexado.", 2);
	}else{
		$tabela = array();
		$header = array();
		$header[] = array("Arquivo", "left", "45%", "header");
		$header[] = array("Data", "center", "17%", "header");
		$header[] = array("Responsável", "center", "", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($anexo_rdm->database->result)){
			$header = array();
			$header[] = array("<a target=\"_blank\" href=\"../cau/anexos/".$row["nom_arquivo_sistema"]."\">".$row["nom_arquivo_original"]."</a>", "left", "", "");
			$header[] = array($row["dth_anexo"], "left", "", "");
			$header[] = array($row["nom_colaborador"], "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
	}
	
	//$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
	$pagina->LinhaCampoFormularioColspanDestaque("<a href=\"RDMAnexosImportacao.php?v_SEQ_RDM=".$v_SEQ_RDM."\">Anexar mais arquivos</a>", 2);
	//$pagina->FechaTabelaPadrao();
	
	$pagina->FechaTabelaPadrao();
	
	//================================================================================================================================
	// Chamados associados
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaChamados style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Chamados associados a RDM", 2);

	require_once 'include/PHP/class/class.chamado_rdm.php';
	$chamado_rdm = new chamado_rdm();
	$chamado_rdm->setSEQ_RDM($RDM->SEQ_RDM);
	$chamado_rdm->selectParam("SEQ_CHAMADO");
	if($chamado_rdm->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum chamado associado.", 2);
	}else{
		$tabela = array();
		$header = array();
		//$header[] = array("Número do Chamado", "left", "45%", "header");
		$header[] = array("Número do Chamado", "left", "15%", "header");
		$header[] = array("Atividade","left" ,"40%", "header");
		$header[] = array("Descrição","left","40%", "header");
		
		//$header[] = array("Data", "center", "17%", "header");
		//$header[] = array("Responsável", "center", "", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($chamado_rdm->database->result)){
			$header = array();
			$header[] = array($row["seq_chamado"], "left", "", "");
			$header[] = array($row["dsc_atividade_chamado"], "left", "", "");
			$header[] = array($row["txt_chamado"], "left", "", "");
			//$header[] = array($row["dth_anexo"], "left", "", "");
			//$header[] = array($row["nom_colaborador"], "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
	}
	
	//$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
	//$pagina->LinhaCampoFormularioColspanDestaque("<a href=\"RDMAnexosImportacao.php?v_SEQ_RDM=".$v_SEQ_RDM."\">Anexar mais arquivos</a>", 2);
	//$pagina->FechaTabelaPadrao();
	
	$pagina->FechaTabelaPadrao();
	
	$pagina->FechaTabelaPadrao();	
	$pagina->MontaRodape();
}else{
	$pagina->redirectTo("RDMPesquisar.php");
}
