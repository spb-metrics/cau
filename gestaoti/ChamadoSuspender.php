<?
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
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

if($v_SEQ_CHAMADO != ""){
	// ============================================================================================================
	// Realizar o suspen��o do chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$camposValidados = 1;
		$mensagemErro = "";
		if($v_SEQ_MOTIVO_SUSPENCAO == ""){
		 	$vErroCampos = "Preencha o campo motivo de suspen��o. ";
		}
		if($v_TXT_HISTORICO == ""){
		 	$vErroCampos .= "Preencha o campo observa��o. ";
		}

		if($vErroCampos == ""){
			require_once 'include/PHP/class/class.chamado.php';
			require_once 'include/PHP/class/class.situacao_chamado.php';
			$chamado = new chamado();
			$situacao_chamado = new situacao_chamado();

			// Alterar situa��o do chamado
			$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $situacao_chamado->COD_Suspenca);

			// Incluir hist�rico
			require_once 'include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Suspenca);
			$historico_chamado->setSEQ_MOTIVO_SUSPENCAO($v_SEQ_MOTIVO_SUSPENCAO);
			$historico_chamado->setTXT_HISTORICO($v_TXT_HISTORICO);
			$historico_chamado->insert();

			// Encerrar time_sheet
			require_once 'include/PHP/class/class.time_sheet.php';
			$time_sheet = new time_sheet();
			$time_sheet->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$time_sheet->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$time_sheet->FinalizarTarefa();

			// Atualizar atribui��es
			require_once 'include/PHP/class/class.atribuicao_chamado.php';
			$atribuicao_chamado = new atribuicao_chamado();
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Suspenca);
			$atribuicao_chamado->AtualizarSituacao();

			// -------------------------------------------------------------------------------------
			// Replicar altera��es para os chamados filhos
			require_once 'include/PHP/class/class.vinculo_chamado.php';
			$vinculo_chamado = new vinculo_chamado();
			$vinculo_chamado->setSEQ_CHAMADO_MASTER($v_SEQ_CHAMADO);
			$vinculo_chamado->selectParam();
			if($vinculo_chamado->database->rows > 0){
				while ($row = pg_fetch_array($vinculo_chamado->database->result)){
					// Alterar situa��o do chamado
					$chamado->AtualizaSituacao($row["seq_chamado_filho"], $situacao_chamado->COD_Suspenca);

					// Incluir hist�rico
					$historico_chamado = new historico_chamado();
					$historico_chamado->setSEQ_CHAMADO($row["seq_chamado_filho"]);
					$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$historico_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Suspenca);
					$historico_chamado->setSEQ_MOTIVO_SUSPENCAO($v_SEQ_MOTIVO_SUSPENCAO);
					$historico_chamado->setTXT_HISTORICO($v_TXT_HISTORICO);
					$historico_chamado->insert();

					// Atualizar atribui��es
					$atribuicao_chamado = new atribuicao_chamado();
					$atribuicao_chamado->setSEQ_CHAMADO($row["seq_chamado_filho"]);
					$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Suspenca);
					$atribuicao_chamado->AtualizarSituacao();
				}
			}
			// -------------------------------------------------------------------------------------

			// Enviar e-mail
			require_once 'include/PHP/class/class.phpmailer.php';
			$mail = new PHPMailer();
			$mail->From     = $pagina->EmailRemetente;
			$mail->FromName = $pagina->remetenteEmailCEA;
			$mail->Sender   = $pagina->EmailRemetente;
			$mail->Subject  = "CAU - Notifica��o de Suspen��o de Chamado";
			$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
			if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
		        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
		    } else {
		        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
		    }
			require_once 'include/PHP/class/class.chamado.php';
		    $chamadoEmail = new chamado();
		    $chamadoEmail->email($v_SEQ_CHAMADO);

		    require_once '../gestaoti/include/PHP/class/class.motivo_suspencao.php';
			$motivo_suspencao = new motivo_suspencao();
			$motivo_suspencao->select($v_SEQ_MOTIVO_SUSPENCAO);

			$v_DS_CORPO ="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
						    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
						<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
						<head>
							<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
							<link href=\"".$pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
						</head>
						<body>
						<div align=\"left\">
								Prezado(a) senhor(a),<br>
								<br>
							   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Acaba de ser suspenso, no CAU, o atendimento do chamado n� ".$v_SEQ_CHAMADO.". Seguem abaixo os dados do chamado.
					   <br>
					   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
					   <br>&nbsp;&nbsp;- Solicita��o: <b>".$chamadoEmail->TXT_CHAMADO."</b>
					   <br>&nbsp;&nbsp;- Cliente: <b>".$chamadoEmail->NOM_CLIENTE."</b>
					   <br>&nbsp;&nbsp;- Motivo da suspen��o: <b>".$motivo_suspencao->DSC_MOTIVO_SUSPENCAO."</b>
					   <br>&nbsp;&nbsp;- Observa��o: <b>".$v_TXT_HISTORICO."</b>
					   <br>
					   <br>Para maiores informa��es acesse o Gest�o TI na sua �rea de atendimento.
					   <br>Esta e uma mensagem autom&aacute;tica, favor n&atilde;o responder.
					   <br>---
					   <br>CAU - Central de Atendimento ao Usu�rio
					   <br>".$pagina->enderecoGestaoTI."
					   </body>
					   </html>";

			// Buscar contato do l�der e do substituto
			require_once 'include/PHP/class/class.equipe_ti.php';
			$equipe_ti = new equipe_ti();
			$equipe_ti->EmailLiderSubstituto($_SESSION["SEQ_EQUIPE_TI"]);
			// Ao l�der
			if($equipe_ti->DSC_EMAIL_LIDER != ""){
				$mail->AddAddress($equipe_ti->DSC_EMAIL_LIDER, $equipe_ti->NOM_LIDER);
			}

			// Buscar e-mail do colaborador
			require_once 'include/PHP/class/class.empregados.oracle.php';
			$empregados = new empregados();
			$empregados->GetNomeEmail($_SESSION["NUM_MATRICULA_RECURSO"]);
			// Adicionar
			$mail->AddAddress($empregados->DES_EMAIL, $empregados->NOME);

			// Adicionar e-mail do cliente
			$mail->AddAddress($chamadoEmail->EMAIL_CLIENTE, $chamadoEmail->NOM_CLIENTE);

			// Enviar
			$mail->Body    = str_replace("<NOM_DETINATARIO>", $empregados->NOME, $v_DS_CORPO);
			$mail->AltBody = str_replace("<NOM_DETINATARIO>", $empregados->NOME, $v_DS_CORPO);
			$mail->Send();
			$mail->ClearAddresses();


			// -------------------------------------------------------------------------------------
			// Enviar e-mail para os clientes dos chamados filhos
			require_once 'include/PHP/class/class.vinculo_chamado.php';
			$vinculo_chamado = new vinculo_chamado();
			$vinculo_chamado->setSEQ_CHAMADO_MASTER($v_SEQ_CHAMADO);
			$vinculo_chamado->selectParam();
			if($vinculo_chamado->database->rows > 0){
				while ($row = pg_fetch_array($vinculo_chamado->database->result)){

					// Enviar e-mail
					$mail = new PHPMailer();
					$mail->From     = $pagina->EmailRemetente;
					$mail->FromName = $pagina->remetenteEmailCEA;
					$mail->Sender   = $pagina->EmailRemetente;
					$mail->Subject  = "CAU - Notifica��o de Suspens�o de Chamado";
					$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
					if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
				        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
				    } else {
				        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
				    }

				    $chamadoEmail = new chamado();
				    $chamadoEmail->email($row["seq_chamado_filho"]);

					$v_DS_CORPO ="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
								    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
								<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
								<head>
									<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
									<link href=\"".$pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
								</head>
								<body>
								<div align=\"left\">
										Prezado(a) senhor(a),<br>
										<br>
									   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Acaba de ser suspenso, no CAU, o atendimento do chamado n� ".$row["seq_chamado_filho"].". Seguem abaixo os dados do chamado.
							   <br>
							   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
							   <br>&nbsp;&nbsp;- Solicita��o: <b>".$chamadoEmail->TXT_CHAMADO."</b>
							   <br>&nbsp;&nbsp;- Cliente: <b>".$chamadoEmail->NOM_CLIENTE."</b>
							   <br>&nbsp;&nbsp;- Motivo da suspen��o: <b>".$motivo_suspencao->DSC_MOTIVO_SUSPENCAO."</b>
							   <br>&nbsp;&nbsp;- Observa��o: <b>".$v_TXT_HISTORICO."</b>
							   <br>
							   <br>Para maiores informa��es acesse o Gest�o TI na sua �rea de atendimento.
							   <br>Esta e uma mensagem autom&aacute;tica, favor n&atilde;o responder.
							   <br>---
							   <br>CAU - Central de Atendimento ao Usu�rio
							   <br>".$pagina->enderecoGestaoTI."
							   </body>
							   </html>";
					// Adicionar e-mail do cliente
					$mail->AddAddress($chamadoEmail->EMAIL_CLIENTE, $chamadoEmail->NOM_CLIENTE);

					// Enviar
					$mail->Body    = str_replace("<NOM_DETINATARIO>", $empregados->NOME, $v_DS_CORPO);
					$mail->AltBody = str_replace("<NOM_DETINATARIO>", $empregados->NOME, $v_DS_CORPO);
					$mail->Send();
					$mail->ClearAddresses();
				}
			}
			// -------------------------------------------------------------------------------------


			// Redirecionar para a p�gina de atendimento
			$pagina->redirectTo("ChamadoAtendimentoPesquisa.php");
		}
	}
	// ============================================================================================================
	// In�cio da p�gina
	// ============================================================================================================

	// Verificar se o profissional possui um lan�amento no Time Sheet em aberto para o chamado
	require_once 'include/PHP/class/class.time_sheet.php';
	$time_sheet = new time_sheet();
	$v_FLG_ATENDIMENTO_INICIADO = $time_sheet->VerificarInicioAtividadeChamado($v_SEQ_CHAMADO, $_SESSION["NUM_MATRICULA_RECURSO"]);
	if($v_FLG_ATENDIMENTO_INICIADO != "1"){
		// Redirecionar o profissional para a tela de atendimento
		$pagina->ScriptAlert("Inicie o atendimento do chamado antes de realizar uma a��o.");
		$pagina->redirectToJS("ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
	}

	// ============================================================================================================
	// Configura��o da p�g�na
	// ============================================================================================================
	$pagina->SettituloCabecalho("Suspender atendimento do chamado"); // Indica o t�tulo do cabe�alho da p�gina
	$pagina->cea = 1;
	$pagina->method = "post";

	require_once 'include/PHP/class/class.chamado.php';
	$banco = new chamado();
	$banco->select($v_SEQ_CHAMADO);

	require_once 'include/PHP/class/class.situacao_chamado.php';
	$situacao_chamado = new situacao_chamado();

	require_once 'include/PHP/class/class.tipo_ocorrencia.php';
	$tipo_ocorrencia = new tipo_ocorrencia();

	$aItemAba = Array();
	$aItemAba[] = array("#", "", "Detalhes", "onclick=\"AcessarAcao('ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Alterar", "onclick=\"AcessarAcao('ChamadoAlterar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Atribuir", "onclick=\"AcessarAcao('ChamadoAtribuir.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Atendimento", "onclick=\"AcessarAcao('ChamadoRegistroAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "tabact", "Suspender", "onclick=\"AcessarAcao('ChamadoSuspender.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
	$aItemAba[] = array("#", "", "Cancelar", "onclick=\"AcessarAcao('ChamadoCancelar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
	$aItemAba[] = array("#", "", "Devolver 1� n�vel", "onclick=\"AcessarAcao('ChamadoDevolver1Nivel.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");

	// Se for poss�vel realizar o contigenciamento do chamado
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

	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_CHAMADO", $v_SEQ_CHAMADO);

	// ============================================================================================================
	// Configura��es AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		// =======================================================================
		// Controlar a sa�da �s a��es do chamado
		// =======================================================================
		function AcessarAcao(vDestino){
			validarSaida = false;
			window.location.href = vDestino;
		}

		function fValidaFormLocal(){
			 if(document.form.v_SEQ_MOTIVO_SUSPENCAO.value == ""){
			 	alert("Selecione o motivo da suspen��o");
			 	return false;
			 }
			 if(document.form.v_TXT_HISTORICO.value == ""){
			 	alert("Preencha o campo observa��o");
			 	return false;
			 }
			return confirm("Esta a��o suspender� o atendimento da OS, finalizar� o seu lan�amento no Time Sheet e retornar� para a tela de atendimento. \n Confirma a a��o?");
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
	$pagina->LinhaCampoFormularioColspanDestaque("Motivo da Suspens�o", 2);

	// Montar a combo da tabela motivo_suspencao
	require_once '../gestaoti/include/PHP/class/class.motivo_suspencao.php';
	$motivo_suspencao = new motivo_suspencao();
	$pagina->LinhaCampoFormulario("Motivo de suspens�o:", "right", "S", $pagina->CampoSelect("v_SEQ_MOTIVO_SUSPENCAO", "S", "Motivo de suspen��o", "S", $motivo_suspencao->combo("DSC_MOTIVO_SUSPENCAO"), "Escolha"), "left", "id=".$pagina->GetIdTable(), "20%");

	// Descri��o
	$pagina->LinhaCampoFormulario("Observa��o:", "right", "S",
                                      $pagina->CampoTextArea("v_TXT_HISTORICO", "S", "Observa��o", "99", "9", "", "onkeyup=\"ContaCaracteres(5000, this, document.getElementById('conta_caracteres'))\"").
                                      "<br><span id=\"conta_caracteres\">5000</span> Caracteres restantes"
                                      , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormularioColspan("center", "<hr><div align=left><font color=red>*</font> - Preenchimento obrigat�rio</div>", "2");
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