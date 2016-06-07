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
require_once 'include/PHP/class/class.pagina.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

if($v_SEQ_CHAMADO != ""){
	// ============================================================================================================
	// Realizar o suspenção do chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$camposValidados = 1;
		$mensagemErro = "";

		if($v_SEQ_ACAO_CONTINGENCIAMENTO == ""){
		 	$vErroCampos .= "Preencha o campo ação de contingenciamento. ";
		}
		if($v_TXT_CONTINGENCIAMENTO == ""){
		 	$vErroCampos .= "Preencha o campo observação. ";
		}

		if($vErroCampos == ""){
			require_once 'include/PHP/class/class.chamado.php';
			require_once 'include/PHP/class/class.situacao_chamado.php';
			$chamado = new chamado();
			$situacao_chamado = new situacao_chamado();

			// Alterar situação do chamado
			$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $situacao_chamado->COD_Contingenciado);

			// Atualizar texto do contingenciamento
			$chamado->AtualizaContingenciamento($v_SEQ_CHAMADO, $v_SEQ_ACAO_CONTINGENCIAMENTO, $v_TXT_CONTINGENCIAMENTO);

			// Incluir histórico
			require_once 'include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Contingenciado);
			$historico_chamado->setTXT_HISTORICO($v_TXT_CONTINGENCIAMENTO);
			$historico_chamado->insert();

			// Atualizar atribuições
			require_once 'include/PHP/class/class.atribuicao_chamado.php';
			$atribuicao_chamado = new atribuicao_chamado();
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Contingenciado);
			$atribuicao_chamado->AtualizarSituacao();

			// -------------------------------------------------------------------------------------
			// Replicar alterações para os chamados filhos
			require_once 'include/PHP/class/class.vinculo_chamado.php';
			$vinculo_chamado = new vinculo_chamado();
			$vinculo_chamado->setSEQ_CHAMADO_MASTER($v_SEQ_CHAMADO);
			$vinculo_chamado->selectParam();
			if($vinculo_chamado->database->rows > 0){
				while ($row = pg_fetch_array($vinculo_chamado->database->result)){
					// Alterar situação do chamado
					$chamado->AtualizaSituacao($row["seq_chamado_filho"], $situacao_chamado->COD_Contingenciado);

					// Atualizar texto do contingenciamento
					$chamado->AtualizaContingenciamento($row["seq_chamado_filho"], $v_SEQ_ACAO_CONTINGENCIAMENTO, $v_TXT_CONTINGENCIAMENTO);

					// Incluir histórico
					$historico_chamado = new historico_chamado();
					$historico_chamado->setSEQ_CHAMADO($row["seq_chamado_filho"]);
					$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$historico_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Contingenciado);
					$historico_chamado->setTXT_HISTORICO($v_TXT_CONTINGENCIAMENTO);
					$historico_chamado->insert();

					// Atualizar atribuições
					$atribuicao_chamado = new atribuicao_chamado();
					$atribuicao_chamado->setSEQ_CHAMADO($row["seq_chamado_filho"]);
					$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Contingenciado);
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
			$mail->Subject  = "CAU - Notificação de Contingenciamento de Incidente";
			$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
			if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
		        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
		    } else {
		        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
		    }
			require_once 'include/PHP/class/class.chamado.php';
		    $chamadoEmail = new chamado();
		    $chamadoEmail->email($v_SEQ_CHAMADO);

		    require_once 'include/PHP/class/class.acao_contingenciamento.php';
			$acao_contingenciamento = new acao_contingenciamento();
			$acao_contingenciamento->select($v_SEQ_ACAO_CONTINGENCIAMENTO);

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
							   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O problema relatado no chamado nº ".$v_SEQ_CHAMADO." foi resolvido de forma paleativa. Favor verificar e caso o problema persista reabra o chamado ou entre em contato com nossa central de atendimento. Seguem abaixo os dados do chamado.
					   <br>
					   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
					   <br>&nbsp;&nbsp;- Solicitação: <b>".$chamadoEmail->TXT_CHAMADO."</b>
					   <br>&nbsp;&nbsp;- Cliente: <b>".$chamadoEmail->NOM_CLIENTE."</b>
					   <br>&nbsp;&nbsp;- Ação de contingenciamento: <b>".$acao_contingenciamento->NOM_ACAO_CONTINGENCIAMENTO."</b>
					   <br>&nbsp;&nbsp;- Observação sobre o contingenciamento: <b>".$v_TXT_CONTINGENCIAMENTO."</b>
					   <br>
					   <br>Para maiores informações acesse o CAU.
					   <br>Esta é uma mensagem autom&aacute;tica, favor n&atilde;o responder.
					   <br>---
					   <br>Central de Atendimento ao Usuário - ".$pagina->nom_area_ti."
					   <br>55 61 3429 <b>7872</b>
					   <br>".$pagina->enderecoCEA."</div>
					   </body>
					   </html>";

			// Buscar contato do líder e do substituto
			require_once 'include/PHP/class/class.equipe_ti.php';
			$equipe_ti = new equipe_ti();
			$equipe_ti->EmailLiderSubstituto($_SESSION["SEQ_EQUIPE_TI"]);
			// Ao líder
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
			// Replicar alterações para os chamados filhos
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
					$mail->Subject  = "CAU - Notificação de Contingenciamento de Incidente";
					$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
					if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
				        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
				    } else {
				        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
				    }
					require_once 'include/PHP/class/class.chamado.php';
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
									   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O problema relatado no chamado nº ".$v_SEQ_CHAMADO." foi resolvido de forma paleativa. Favor verificar e caso o problema persista reabra o chamado ou entre em contato com nossa central de atendimento. Seguem abaixo os dados do chamado.
							   <br>
							   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
							   <br>&nbsp;&nbsp;- Solicitação: <b>".$chamadoEmail->TXT_CHAMADO."</b>
							   <br>&nbsp;&nbsp;- Cliente: <b>".$chamadoEmail->NOM_CLIENTE."</b>
							   <br>&nbsp;&nbsp;- Ação de contingenciamento: <b>".$acao_contingenciamento->NOM_ACAO_CONTINGENCIAMENTO."</b>
							   <br>&nbsp;&nbsp;- Observação sobre o contingenciamento: <b>".$v_TXT_CONTINGENCIAMENTO."</b>
							   <br>
							   <br>Para maiores informações acesse o CAU.
							   <br>Esta é uma mensagem autom&aacute;tica, favor n&atilde;o responder.
							   <br>---
							   <br>Central de Atendimento ao Usuário - ".$pagina->nom_area_ti."
							   <br>55 61 3429 <b>7872</b>
							   <br>".$pagina->enderecoCEA."</div>
							   </body>
							   </html>";

					// Buscar contato do líder e do substituto
					require_once 'include/PHP/class/class.equipe_ti.php';
					$equipe_ti = new equipe_ti();
					$equipe_ti->EmailLiderSubstituto($_SESSION["SEQ_EQUIPE_TI"]);
					// Ao líder
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
				}
			}
			// -------------------------------------------------------------------------------------

			// Redirecionar para a página de atendimento
			$pagina->redirectTo("ChamadoAtendimento.php?v_SEQ_CHAMADO=".$v_SEQ_CHAMADO);
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
	$pagina->SettituloCabecalho("Contingenciar incidente"); // Indica o título do cabeçalho da página
	$pagina->cea = 1;
	$pagina->method = "post";

	$aItemAba = Array( array("#", "", "Detalhes", "onclick=\"AcessarAcao('ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
	 			       array("#", "", "Alterar", "onclick=\"AcessarAcao('ChamadoAlterar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
	 			       array("#", "", "Atribuir", "onclick=\"AcessarAcao('ChamadoAtribuir.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
	 			       array("#", "", "Atendimento", "onclick=\"AcessarAcao('ChamadoRegistroAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
				       array("#", "", "Suspender", "onclick=\"AcessarAcao('ChamadoSuspender.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\""),
				       array("#", "", "Cancelar", "onclick=\"AcessarAcao('ChamadoCancelar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\""),
				       array("#", "", "Devolver 1º nível", "onclick=\"AcessarAcao('ChamadoDevolver1Nivel.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\""),
				       array("#", "tabact", "Contingenciar", "onclick=\"AcessarAcao('ChamadoContingenciar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\""),
				       array("#", "", "Vincular", "onclick=\"AcessarAcao('ChamadoVincular.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
				       array("#", "", "Encerrar", "onclick=\"AcessarAcao('ChamadoEncerramento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"")
					 );

	$pagina->SetaItemAba($aItemAba);
	$pagina->estiloTabBar = "tabbarMenor";
	$pagina->flagScriptCalendario = 0;

	$pagina->MontaCabecalho();

	require_once 'include/PHP/class/class.chamado.php';
	$banco = new chamado();
	$banco->select($v_SEQ_CHAMADO);

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
			 if(document.form.v_SEQ_ACAO_CONTINGENCIAMENTO.value == ""){
			 	alert("Preencha o campo ação de contingenciamento");
			 	return false;
			 }
			 if(document.form.v_TXT_CONTINGENCIAMENTO.value == ""){
			 	alert("Preencha o campo observação");
			 	return false;
			 }
			return confirm("Esta ação contingenciará o atendimento do chamado e retornará para a tela de atendimento. \n Confirma a ação?");
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
	$pagina->LinhaCampoFormularioColspanDestaque("Ação de Contingenciamento", 2);

	// Montar a combo da tabela motivo_suspencao
	require_once '../gestaoti/include/PHP/class/class.acao_contingenciamento.php';
	$acao_contingenciamento = new acao_contingenciamento();
	$pagina->LinhaCampoFormulario("Ação de contingenciamento:", "right", "S", $pagina->CampoSelect("v_SEQ_ACAO_CONTINGENCIAMENTO", "S", "Ação de contingenciamento", "S", $acao_contingenciamento->combo("NOM_ACAO_CONTINGENCIAMENTO", $banco->SEQ_ACAO_CONTINGENCIAMENTO), "Escolha"), "left", "id=".$pagina->GetIdTable(), "20%");

	// Descrição
	$pagina->LinhaCampoFormulario("Descreva a ação tomada para o contigenciamento do incidente:", "right", "S",
									  $pagina->CampoTextArea("v_TXT_CONTINGENCIAMENTO", "S", "Ação de Contingenciamento", "99", "3", $banco->TXT_CONTINGENCIAMENTO, "onkeyup=\"ContaCaracteres(900, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">900</span> Caracteres restantes"
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