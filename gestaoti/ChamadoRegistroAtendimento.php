<?
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
require 'include/PHP/class/class.pagina.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

if($v_SEQ_CHAMADO != ""){
	// ============================================================================================================
	// Realizar o cadastro do chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$camposValidados = 1;
		$mensagemErro = "";
		if($v_TXT_ATENDIMENTO_CHAMADO == ""){
		 	$vErroCampos = "Preencha o campo descrição histórica do atendimento";
		}

		if($vErroCampos == ""){
			require_once 'include/PHP/class/class.atendimento_chamado.php';
			$atendimento_chamado = new atendimento_chamado();
			$atendimento_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atendimento_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$atendimento_chamado->setTXT_ATENDIMENTO_CHAMADO($v_TXT_ATENDIMENTO_CHAMADO);
			$atendimento_chamado->insert();

			// Redirecionar para a página de confirmação
			$pagina->redirectTo("ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
		}
	}
	// ============================================================================================================
	// Início da página
	// ============================================================================================================

	// Verificar se o profissional possui um lançamento no Time Sheet em aberto para o chamado
	require_once 'include/PHP/class/class.time_sheet.php';
	$time_sheet = new time_sheet();
	$v_FLG_ATENDIMENTO_INICIADO = $time_sheet->VerificarInicioAtividadeChamado($v_SEQ_CHAMADO, $_SESSION["NUM_MATRICULA_RECURSO"]);
	if($v_FLG_ATENDIMENTO_INICIADO != "1"){
		// Redirecionar o profissional para a tela de atendimento
		$pagina->ScriptAlert("Inicie o atendimento do chamado antes de realizar uma ação.");
		$pagina->redirectToJS("ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
	}

	// ============================================================================================================
	// Configuração da págína
	// ============================================================================================================
	$pagina->SettituloCabecalho("Histórico de Acontecimentos"); // Indica o título do cabeçalho da página
	$pagina->cea = 1;
	$pagina->method = "post";

	require_once 'include/PHP/class/class.tipo_ocorrencia.php';
	$tipo_ocorrencia = new tipo_ocorrencia();

	require_once 'include/PHP/class/class.chamado.php';
	$banco = new chamado();
	$banco->select($v_SEQ_CHAMADO);

	require_once 'include/PHP/class/class.situacao_chamado.php';
	$situacao_chamado = new situacao_chamado();

	$aItemAba = Array();
	$aItemAba[] = array("#", "", "Detalhes", "onclick=\"AcessarAcao('ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Alterar", "onclick=\"AcessarAcao('ChamadoAlterar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Atribuir", "onclick=\"AcessarAcao('ChamadoAtribuir.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "tabact", "Atendimento", "onclick=\"AcessarAcao('ChamadoRegistroAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Suspender", "onclick=\"AcessarAcao('ChamadoSuspender.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
	$aItemAba[] = array("#", "", "Cancelar", "onclick=\"AcessarAcao('ChamadoCancelar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
	$aItemAba[] = array("#", "", "Devolver 1º nível", "onclick=\"AcessarAcao('ChamadoDevolver1Nivel.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");

	// Se for possível realizar o contigenciamento do chamado
	if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
		if($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Atendimento || $banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Em_Andamento || $banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Suspenca ){
			$aItemAba[] = array("#", "", "Contingenciar", "onclick=\"AcessarAcao('ChamadoContingenciar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
		}
		$aItemAba[] = array("#", "", "Vincular", "onclick=\"AcessarAcao('ChamadoVincular.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	}

	// Adicionar a aba Encerrar caso tenha o prazo do chamado definido
	if($banco->DTH_ENCERRAMENTO_PREVISAO != ""){
		$aItemAba[] = array("#", "", "Encerrar", "onclick=\"AcessarAcao('ChamadoEncerramento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	}

	$pagina->SetaItemAba($aItemAba);
	$pagina->estiloTabBar = "tabbarMenor";
	$pagina->flagScriptCalendario = 0;

	$pagina->MontaCabecalho(1);

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_CHAMADO", $v_SEQ_CHAMADO);

	// ============================================================================================================
	// Configurações AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		// =======================================================================
		// Controlar a saída às ações do chamado
		// =======================================================================
		function AcessarAcao(vDestino){
			validarSaida = false;
			window.location.href = vDestino;
		}

		function fValidaFormLocal(){
			 if(document.form.v_TXT_ATENDIMENTO_CHAMADO.value == ""){
			 	alert("Preencha o campo descrição histórica do atendimento");
			 	return false;
			 }
			return true;
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
				// default warning message
				var msg = "Tem certeza que deseja sair da tela de atendimento antes de parar o atendimento do chamado?";

				// set event
				if (!e) { e = window.event; }
				if (e) { e.returnValue = msg; }
				// return warning message
				return msg;
			}
		}

		// Initialise
		addEvent(window, 'load', addListeners, false);
	</script>
	<?
	if($mensagemErro != ""){
		$pagina->ScriptAlert($mensagemErro);
	}

	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Registros histórico de acontecimentos", 2);

	// Descrição
	$pagina->LinhaCampoFormulario("Descrição do histórico de atendimento:", "right", "S",
									  $pagina->CampoTextArea("v_TXT_ATENDIMENTO_CHAMADO", "S", "Descrição histórica do atendimento", "99", "9", "", "onkeyup=\"ContaCaracteres(5000, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">5000</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormularioColspan("center", "<hr><div align=left><font color=red>*</font> - Preenchimento obrigatório</div>", "2");

	$pagina->LinhaCampoFormularioColspan("center",
				$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormLocal(); ", " Salvar ")
				, "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	$pagina->ScriptAlert("Selecione o chamado.");
	$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
}
?>