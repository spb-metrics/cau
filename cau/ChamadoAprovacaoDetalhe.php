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
require '../gestaoti/include/PHP/class/class.pagina.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

if($v_SEQ_CHAMADO != ""){
	require_once '../gestaoti/include/PHP/class/class.chamado.php';
	require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
	//require_once '../gestaoti/include/PHP/class/class.time_sheet.php';
	require_once '../gestaoti/include/PHP/class/class.atribuicao_chamado.php';
	require_once '../gestaoti/include/PHP/class/class.tipo_ocorrencia.php';
	$banco = new chamado();
	$situacao_chamado = new situacao_chamado();
	//$time_sheet = new time_sheet();
	$atribuicao_chamado = new atribuicao_chamado();
	$tipo_ocorrencia = new tipo_ocorrencia();
 

	// ============================================================================================================
	// Configura��es AJAX
	// ============================================================================================================
	 	// ============================================================================================================
	// Fim das Configura��es AJAX
	// ============================================================================================================

	$pagina->SettituloCabecalho("Aprova��o - Detalhamento do Chamado"); // Indica o t�tulo do cabe�alho da p�gina
	$pagina->cea = 1;
	// pesquisa
	$banco->select($v_SEQ_CHAMADO);
	 

	// Adicionar registro de acesso
	require_once '../gestaoti/include/PHP/class/class.historico_acesso_chamado.php';
	$historico_acesso_chamado = new historico_acesso_chamado();
	$historico_acesso_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$historico_acesso_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$historico_acesso_chamado->insert();
 
	// Abas em comum para todas as situa��es
	$aItemAba = Array();		 
	//$aItemAba[] = array("ChamadoAprovacaoPesquisa.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO", "", "Lista");		
	$aItemAba[] = array("#", "tabact", "Detalhes");
	
	 
	require_once '../gestaoti/include/PHP/class/class.atividade_chamado.php';
	$atividade_chamado = new atividade_chamado();
	$atividade_chamado->select($banco->SEQ_ATIVIDADE_CHAMADO);
	
	// Verificar se uma reprograma��o � poss�vel
	//if($atividade_chamado->NUM_MATRICULA_APROVADOR == $_SESSION["NUM_MATRICULA_RECURSO"] ||	   $atividade_chamado->NUM_MATRICULA_APROVADOR_SUBSTITUTO == $_SESSION["NUM_MATRICULA_RECURSO"]){			
		$aItemAba[] = array("ChamadoAprovar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO", "", "Aprovar/Reprovar");		
	//}
 	 

	$pagina->SetaItemAba($aItemAba);
	$pagina->estiloTabBar = "tabbarMenor";
	$pagina->flagScriptCalendario = 0;
	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_DTH_INICIO_EFETIVO", $banco->DTH_INICIO_EFETIVO); 

	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informa��es Gerais", 2);

	 
	$pagina->LinhaCampoFormulario("N�mero:", "right", "N", $banco->SEQ_CHAMADO, "left", "id=".$pagina->GetIdTable(),"23%","");
 

	$tipo_ocorrencia = new tipo_ocorrencia();
	$tipo_ocorrencia->select($banco->SEQ_TIPO_OCORRENCIA);
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").":", "right", "N", $tipo_ocorrencia->NOM_TIPO_OCORRENCIA, "left", "id=".$pagina->GetIdTable());

	require_once '../gestaoti/include/PHP/class/class.subtipo_chamado.php';
	$subtipo_chamado = new subtipo_chamado();
	$subtipo_chamado->select($banco->SEQ_SUBTIPO_CHAMADO);

	require_once '../gestaoti/include/PHP/class/class.tipo_chamado.php';
	$tipo_chamado = new tipo_chamado();
	$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", "right", "N", $tipo_chamado->DSC_TIPO_CHAMADO, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").":", "right", "N", $subtipo_chamado->DSC_SUBTIPO_CHAMADO, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").":", "right", "N", $banco->DSC_ATIVIDADE_CHAMADO, "left", "id=".$pagina->GetIdTable());

	if($banco->SEQ_ITEM_CONFIGURACAO != ""){ // Mostrar o sistema de informa��o e a prioridade estabelecida pelo cliente
		require_once '../gestaoti/include/PHP/class/class.item_configuracao.php';
		$item_configuracao = new item_configuracao();
		$item_configuracao->select($banco->SEQ_ITEM_CONFIGURACAO);
		$pagina->LinhaCampoFormulario("Sistema de Informa��o:", "right", "N", $item_configuracao->SIG_ITEM_CONFIGURACAO." - ".$item_configuracao->NOM_ITEM_CONFIGURACAO, "left", "id=".$pagina->GetIdTable());
		$pagina->LinhaCampoFormulario("Prioridade na Fila:", "right", "N", $banco->NUM_PRIORIDADE_FILA, "left", "id=".$pagina->GetIdTable());
	}

	$situacao_chamado->select($banco->SEQ_SITUACAO_CHAMADO);
	$pagina->LinhaCampoFormulario("Situa��o:", "right", "N", "<span id=\"situacao\">".$situacao_chamado->DSC_SITUACAO_CHAMADO."</span>", "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Solicita��o:", "right", "N", str_replace(chr(13), "<br>",$banco->TXT_CHAMADO), "left", "id=".$pagina->GetIdTable());

	//SOLICIACAO DE CELULAR
	if($banco->DT_INICIO_UTILIZACAO_APARELHO != "" && $banco->DT_INICIO_UTILIZACAO_APARELHO != null){
		
		//FORMATAR DATA
		$array_teste = split(" ",$banco->DT_INICIO_UTILIZACAO_APARELHO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		 
		
		$DT_INICIO_UTILIZACAO_APARELHO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		//FORMATAR DATA
		$array_teste = split(" ",$banco->DT_FIM_UTILIZACAO_APARELHO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		
		$DT_FIM_UTILIZACAO_APARELHO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		$pagina->LinhaCampoFormulario("Per�odo de Utiliza��o do aparelho:", "right", "N", 
		date("d/m/y",$DT_INICIO_UTILIZACAO_APARELHO) ." � ".date("d/m/y",$DT_FIM_UTILIZACAO_APARELHO)
		, "left", "id=".$pagina->GetIdTable());
	}
	
	//SOLICIACAO DE AUDIORIO
	if($banco->QUANTIDADE_PESSOAS_EVENTO != "" && $banco->QUANTIDADE_PESSOAS_EVENTO != null){
		
		$pagina->LinhaCampoFormulario("Objetivo do Evento:", "right", "N", $banco->OBJETIVO_EVENTO, "left", "id=".$pagina->GetIdTable());
		
		//FORMATAR DATA
		$array_teste = split(" ",$banco->DTH_RESERVA_EVENTO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		  
		 
		$DTH_RESERVA_EVENTO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		$pagina->LinhaCampoFormulario("Data/Hora Reserva:", "right", "N", 
		date("d/m/y H:i:s",$DTH_RESERVA_EVENTO)  		, "left", "id=".$pagina->GetIdTable());
		
		$pagina->LinhaCampoFormulario("Quantidade de Pessoas:", "right", "N", $banco->QUANTIDADE_PESSOAS_EVENTO, "left", "id=".$pagina->GetIdTable());
		$pagina->LinhaCampoFormulario("Servi�os:", "right", "N", $banco->SERVICOS_EVENTO, "left", "id=".$pagina->GetIdTable());
		
	} 

	// ============================================================================================================
	// Configura��es AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		    

		// =======================================================================
		// Controlar a sa�da �s a��es do chamado
		// =======================================================================
		function AcessarAcao(vDestino){
			if(document.form.v_FLG_ATENDIMENTO_INICIADO.value == 1){
				validarSaida = false;
				window.location.href = vDestino;
			}else{
				validarSaida = true;
				alert("Inicie o atendimento antes de realizar qualquer a��o.");
			}
		}

		// =======================================================================
		// Configura��o das TABS
		// =======================================================================
		function fMostra(id, idTab){
			//alert("fMostra = "+validarSaida);
			validarSaida = false;
			document.getElementById("tabelaSLA").style.display = "none";
			document.getElementById("tabSLA").attributes["class"].value = "";

			document.getElementById("tabelaMeusDados").style.display = "none";
			document.getElementById("tabMeusDados").attributes["class"].value = "";

			document.getElementById("tabelaHistorico").style.display = "none";
			document.getElementById("tabHistorico").attributes["class"].value = "";

			document.getElementById("tabelaAcessos").style.display = "none";
			document.getElementById("tabAcessos").attributes["class"].value = "";

			document.getElementById("tabelaProfissionais").style.display = "none";
			document.getElementById("tabProfissionais").attributes["class"].value = "";

			document.getElementById("tabelaTimeSheet").style.display = "none";
			document.getElementById("tabTimeSheet").attributes["class"].value = "";

			document.getElementById("tabelaAtendimento").style.display = "none";
			document.getElementById("tabAtendimento").attributes["class"].value = "";
                        
                        <? if($pagina->flg_usar_funcionalidades_patrimonio == "S"){ ?>
                            document.getElementById("tabelaPatrimonio").style.display = "none";
                            document.getElementById("tabPatrimonio").attributes["class"].value = "";
                        <? } ?>
			
			document.getElementById("tabelaAnexos").style.display = "none";
			document.getElementById("tabAnexos").attributes["class"].value = "";

			<? if($banco->QTD_MIN_SLA_ATENDIMENTO == ""){ ?>
					document.getElementById("tabelaPrevisao").style.display = "none";
					document.getElementById("tabPrevisao").attributes["class"].value = "";
			<? } ?>
			<? if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){ ?>
					document.getElementById("tabelaVinculo").style.display = "none";
					document.getElementById("tabVinculo").attributes["class"].value = "";
			<? } ?>
			<? if($etapa_chamado->database->rows > 0){ ?>
					document.getElementById("tabelaEtapas").style.display = "none";
					document.getElementById("tabEtapas").attributes["class"].value = "";
			<? } ?>
			document.getElementById(id).style.display = "block";
			document.getElementById(idTab).attributes["class"].value = "tabact";

			validarSaida = true;
		}

		// =======================================================================
		// Controle de Sa�da da P�gina
		// =======================================================================
		// Gest�o de Eventos
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
		// Flag de valida��o da sa�da do fomul�rio
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
		addEvent(window, 'load', addListeners, false);
		// <input type="button" class="button" value="Save" onclick="removeEvent(window, 'beforeunload', exitAlert, false); location.href='../list/overview.asp'" />
	</script>
<?
	$aItemAba = Array();
	 
	$aItemAba[] = array("javascript:fMostra('tabelaMeusDados','tabMeusDados')", "", "&nbsp;Solicitante&nbsp;", "tabMeusDados", "onclick=\"validarSaida=false;\"");
	 
	$pagina->SetaItemAba($aItemAba);
	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");
	$pagina->FechaTabelaPadrao();
 
	//================================================================================================================================
	// Dados do Solicitante
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaMeusDados style=\"display: block;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informa��es sobre o solicitante", 2);
	require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();
	$empregados->select($banco->NUM_MATRICULA_SOLICITANTE);

	$tabela = array();
	$header = array();
	// Nome
	$header = array();
	$header[] = array("Nome:", "center", "23%", "label");
	$header[] = array($empregados->NOME, "left", "", "campo");
	$tabela[] = $header;

	// Depend�ncia
	$header = array();
	$header[] = array("Diretoria:", "center", "", "label");
	$header[] = array($empregados->DEP_SIGLA, "left", "", "campo");
	$tabela[] = $header;

	// Lota��o
	$header = array();
	$header[] = array("Lota��o:", "center", "", "label");
	$header[] = array($empregados->UOR_SIGLA, "left", "", "campo");
	$tabela[] = $header;

	// E-mail
	$header = array();
	$header[] = array("E-mail:", "center", "", "label");
	$header[] = array($empregados->DES_EMAIL, "left", "", "campo");
	$tabela[] = $header;

	// Matr�cula
	$header = array();
	$header[] = array("Matr�cula:", "center", "", "label");
	$header[] = array($empregados->NUM_MATRICULA_RECURSO, "left", "", "campo");
	$tabela[] = $header;

	// Ramal
	$header = array();
	$header[] = array("Ramal:", "center", "", "label");
	$header[] = array($empregados->NUM_DDD." ".$empregados->NUM_VOIP, "left", "", "campo");
	$tabela[] = $header;

	// Localiza��o do cliente
	if($banco->SEQ_LOCALIZACAO_FISICA != ""){
		$header = array();
		require_once '../gestaoti/include/PHP/class/class.localizacao_fisica.php';
		$localizacao_fisica = new localizacao_fisica();
		$localizacao_fisica->select($banco->SEQ_LOCALIZACAO_FISICA);

		require_once '../gestaoti/include/PHP/class/class.edificacao.php';
		$edificacao = new edificacao();
		$edificacao->select($localizacao_fisica->SEQ_EDIFICACAO);

		require_once '../gestaoti/include/PHP/class/class.dependencias.php';
		$dependencias = new dependencias();
		$vSIG_DEPENDENCIA = $dependencias->GetSiglaDependencia($edificacao->COD_DEPENDENCIA);

		$header[] = array("Localiza��o:", "center", "", "label");
		$header[] = array($vSIG_DEPENDENCIA." - ".$edificacao->NOM_EDIFICACAO." - ".$localizacao_fisica->NOM_LOCALIZACAO_FISICA, "left", "", "campo");
		$tabela[] = $header;
	}

	// Pessoa de contato
	if($banco->NUM_MATRICULA_CADASTRANTE != ""){
		$empregados = new empregados();
		$empregados->select($banco->NUM_MATRICULA_CADASTRANTE);
		$header = array();
		$header[] = array("Chamado cadastrado por:", "center", "", "label");
		$header[] = array($empregados->NOME." - Ramal: ".$empregados->NUM_DDD."-".$empregados->NUM_VOIP, "left", "", "campo");
		$tabela[] = $header;
	}

	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	$pagina->FechaTabelaPadrao();

	   

	$pagina->MontaRodape();
}else{
	$pagina->redirectTo("ChamadoAprovacaoPesquisa.php");
}

?>