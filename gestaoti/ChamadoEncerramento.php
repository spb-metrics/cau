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
require_once 'include/PHP/class/class.atribuicao_chamado.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

require_once 'include/PHP/class/class.tipo_ocorrencia.php';
$tipo_ocorrencia = new tipo_ocorrencia();

if($v_SEQ_CHAMADO != ""){
	// ============================================================================================================
	// Realizar o cadastro do chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$mensagemErro = "";
		if($v_TXT_HISTORICO == ""){
		 	$vErroCampos .= "Preencha o campo observa��o. ";
		}
		if($v_SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
			if($v_TXT_CAUSA_RAIZ == ""){
			 	$vErroCampos .= "Preencha o campo causa raiz. ";
			}
		}

		if($vErroCampos == ""){
			require_once 'include/PHP/class/class.chamado.php';
			require_once 'include/PHP/class/class.situacao_chamado.php';
			require_once 'include/PHP/class/class.atividade_chamado.php';
			$chamado = new chamado();
			$chamado->select($v_SEQ_CHAMADO);
			$situacao_chamado = new situacao_chamado();

			// Verificar se a atividade � de atendimento externo
			if($chamado->FLG_ATENDIMENTO_EXTERNO == "N"){ // Para atendimento interno, o encerramento � direto
				$v_MSG_FINAL = "Para maiores informa��es acesse o CAU.";
				$v_ENDERECO_SITE = $pagina->enderecoGestaoTI;
				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Encerrada;
			}elseif($chamado->FLG_ATENDIMENTO_EXTERNO == "S"){ // Para atendimento externo, o chamado � encaminhado para avalia��o
				$v_MSG_FINAL = "Por favor, acesse o CAU e avalie o nosso atendimento, sua opini�o � muito importante.
								<br><br>
								Caso a sua solicita��o n�o tenha sido resolvida o chamado porder� ser reaberto por meio do formul�rio de avalia��o. ";
				$v_ENDERECO_SITE = $pagina->enderecoCEA;
				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Avaliacao;
			}

			// Atualizar texto de solu��o
			if($chamado->TXT_RESOLUCAO == ""){
				$chamado->AtualizaTxtSolucao($v_SEQ_CHAMADO, "Por ".$_SESSION["NOME"]." em ".date("d/m/Y H:i").chr(13).$v_TXT_HISTORICO);
			}else{
				$chamado->AtualizaTxtSolucao($v_SEQ_CHAMADO, $chamado->TXT_RESOLUCAO.chr(13).chr(13)."Por ".$_SESSION["NOME"]." em ".date("d/m/Y H:i").chr(13).$v_TXT_HISTORICO);
			}

			// Atualizar atribui��o
			$atribuicao_chamado = new atribuicao_chamado();
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			// Buscar atribui��o
			$atribuicao_chamado->selectMatricula();

			// Informar a nova situa��o
			$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Encerrada);
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->AtualizarSituacao();

			// Atualizar encerramento efetivo
			$atribuicao_chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO();

			// ==================== Encerrar atribui��es da equipe =================
			if($v_ENCERRA_EQUIPE == "S"){
				// Buscar atribui��es da equipe
				$atribuicao_chamado = new atribuicao_chamado();
				$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$atribuicao_chamado->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
				$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$atribuicao_chamado->SelectEncerramentoEquipe();
				if($atribuicao_chamado->database->rows > 0){
					while ($row = pg_fetch_array($atribuicao_chamado->database->result)){
						// Atualizar atribui��o
						$atribuicao_chamado1 = new atribuicao_chamado();
						$atribuicao_chamado1->setSEQ_CHAMADO($v_SEQ_CHAMADO);
						$atribuicao_chamado1->setNUM_MATRICULA($row["num_matricula"]);
						$atribuicao_chamado1->selectMatricula();

						// Informar a nova situa��o
						$atribuicao_chamado1->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Encerrada);
						$atribuicao_chamado1->AtualizarSituacao();

						// Se a data de inicio for vazia, atualizar tb
						if($row["dth_inicio_efetivo"] == ""){
							$atribuicao_chamado1->AtualizaDTH_INICIO_EFETIVO();
						}

						// Atualizar encerramento efetivo
						$atribuicao_chamado1->AtualizaDTH_ENCERRAMENTO_EFETIVO();
					}
				}
			}
			// ===================================================================

			// Verificar se existem outras atribui��es com a situa��o diferente
			$atribuicao_chamado = new atribuicao_chamado();
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Encerrada);
			$vRetorno = $atribuicao_chamado->AnalisarEncerramento();

			if($vRetorno == 0){ // N�o existem outras equipes/profissionais em atendimento
				// Alterar situa��o do chamado
				$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $v_SEQ_SITUACAO_CHAMADO);

				// Alterar data de encerramento efetivo
				$chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO($v_SEQ_CHAMADO);

				// Atualizar causa raiz
				if($v_TXT_CAUSA_RAIZ != ""){
					$chamado->AtualizaTxtCausaRaiz($v_SEQ_CHAMADO, $v_TXT_CAUSA_RAIZ);
				}

				// Incluir hist�rico
				require_once 'include/PHP/class/class.historico_chamado.php';
				$historico_chamado = new historico_chamado();
				$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
				$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
				$historico_chamado->setTXT_HISTORICO("Encerramento de atribui��o (".$_SESSION["NOM_EQUIPE_TI"]."): ".$v_TXT_HISTORICO);
				$historico_chamado->insert();

				// Atualizar fila - Caso seja um chamado de manuten��o em sistema de informa��o, aberto por um cliente
				//if($chamado->NUM_PRIORIDADE_FILA != ""){
				//	$chamado->RetirarChamadoFila($chamado->SEQ_CHAMADO, $_SESSION["SEQ_EQUIPE_TI"], $chamado->NUM_PRIORIDADE_FILA, 2);
				//}

				// Enviar e-mail ao solicitante informando o encerramento do chamado
				require_once 'include/PHP/class/class.phpmailer.php';
				$mail = new PHPMailer();
				$mail->From     = $pagina->EmailRemetente;
				$mail->FromName = $pagina->remetenteEmailCEA;
				$mail->Sender   = $pagina->EmailRemetente;
				$mail->Subject  = "CAU - Notifica��o de encerramento de chamado";
				$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
				if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
			        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
			    } else {
			        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
			    }

			    $v_TXT_CAUSA_RAIZ_EMAIL = $v_TXT_CAUSA_RAIZ!=""?"<br>&nbsp;&nbsp;- Cauza raiz: <b>".$v_TXT_CAUSA_RAIZ."</b>":"";

			    $chamadoEmail = new chamado();
			    $chamadoEmail->email($v_SEQ_CHAMADO);
				$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
                                                        \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
                                                    <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
                                                    <head>
                                                            <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
                                                            <link href=\"".$pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
                                                    </head>
                                                    <body>
                                                    <div align=\"left\">
                                                                    Prezado(a) usu�rio(a) ".$chamadoEmail->NOM_CLIENTE.",<br><br>
                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seu chamado, de N� ".$v_SEQ_CHAMADO.", acaba de ter o seu atendimento t�cnico encerrado. Seguem as informa��es do chamado:
                                               <br>
                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
                                               <br>&nbsp;&nbsp;- Solicita��o: <b>".$chamadoEmail->TXT_CHAMADO."</b>
                                               <br>&nbsp;&nbsp;- Data e hora de abertura: <b>".$chamadoEmail->DTH_ABERTURA."</b>
                                               <br>&nbsp;&nbsp;- Data e hora do encerramento: <b>".$chamadoEmail->DTH_ENCERRAMENTO_EFETIVO."</b>
                                               <br>&nbsp;&nbsp;- Observa��o(�es) de Encerramento: <b>".$chamadoEmail->TXT_RESOLUCAO."</b>
                                               ".$v_TXT_CAUSA_RAIZ_EMAIL."
                                               <br>
                                               <br>".$v_MSG_FINAL."
                                               <br>
                                               <br>A Central de Atendimento ao Usu�rio agradece o seu contato.
                                                       Em caso de problemas ou solicita��es, estamos � disposi��o para atend�-lo.
                                               <br>
                                               <br>Esta � uma mensagem autom�tica, favor n�o responder.
                                               <br>---
                                               <br>CAU - Central de Atendimento ao Usu�rio - ".$pagina->nom_area_ti."
                                               <br>".$v_ENDERECO_SITE."
                                               </div>
                                               </body>
                                               </html>";
				$mail->AddAddress($chamadoEmail->EMAIL_CLIENTE, $chamadoEmail->NOM_CLIENTE);
				$mail->Body    = $v_DS_CORPO;
				$mail->AltBody = $v_DS_CORPO;
				$mail->Send();
				$mail->ClearAddresses();
			}

			// -------------------------------------------------------------------------------------
			// Replicar encerramento para os chamados filhos
			require_once 'include/PHP/class/class.vinculo_chamado.php';
			$vinculo_chamado = new vinculo_chamado();
			$vinculo_chamado->setSEQ_CHAMADO_MASTER($v_SEQ_CHAMADO);
			$vinculo_chamado->selectParam();
			if($vinculo_chamado->database->rows > 0){
				while ($row = pg_fetch_array($vinculo_chamado->database->result)){

					$chamado = new chamado();
					$chamado->select($row["seq_chamado_filho"]);
					$situacao_chamado = new situacao_chamado();

					// Verificar se a atividade � de atendimento externo
					if($chamado->FLG_ATENDIMENTO_EXTERNO == "N"){ // Para atendimento interno, o encerramento � direto
						$v_MSG_FINAL = "Para maiores informa��es acesse o CAU.";
						$v_ENDERECO_SITE = $pagina->enderecoGestaoTI;
						$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Encerrada;
					}elseif($chamado->FLG_ATENDIMENTO_EXTERNO == "S"){ // Para atendimento externo, o chamado � encaminhado para avalia��o
						$v_MSG_FINAL = "Por favor, acesse o CAU e avalie o nosso atendimento, sua opini�o � muito importante.
										<br><br>
										Caso a sua solicita��o n�o tenha sido resolvida o chamado porder� ser reaberto por meio do formul�rio de avalia��o. ";
						$v_ENDERECO_SITE = $pagina->enderecoCEA;
						$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Avaliacao;
					}

					// Atualizar atribui��o
					$atribuicao_chamado = new atribuicao_chamado();
					$atribuicao_chamado->setSEQ_CHAMADO($row["seq_chamado_filho"]);
					$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					// Buscar atribui��o
					$atribuicao_chamado->selectMatricula();

					// Informar a nova situa��o
					$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Encerrada);
					$atribuicao_chamado->AtualizarSituacao();

					// Atualizar encerramento efetivo
					$atribuicao_chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO();

					// Verificar se existem outras atribui��es com a situa��o diferente
					$atribuicao_chamado = new atribuicao_chamado();
					$atribuicao_chamado->setSEQ_CHAMADO($row["seq_chamado_filho"]);
					$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Encerrada);
					$vRetorno = $atribuicao_chamado->AnalisarEncerramento();
					if($vRetorno == 0){ // N�o existem outras equipes/profissionais em atendimento
						// Alterar situa��o do chamado
						$chamado->AtualizaSituacao($row["seq_chamado_filho"], $v_SEQ_SITUACAO_CHAMADO);

						// Alterar data de encerramento efetivo
						$chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO($row["seq_chamado_filho"]);

						// Atualizar causa raiz
						if($v_TXT_CAUSA_RAIZ != ""){
							$chamado->AtualizaTxtCausaRaiz($row["seq_chamado_filho"], $v_TXT_CAUSA_RAIZ);
						}

						// Incluir hist�rico
						require_once 'include/PHP/class/class.historico_chamado.php';
						$historico_chamado = new historico_chamado();
						$historico_chamado->setSEQ_CHAMADO($row["seq_chamado_filho"]);
						$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
						$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
						$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
						$historico_chamado->setTXT_HISTORICO($v_TXT_HISTORICO);
						$historico_chamado->insert();

						// Enviar e-mail ao solicitante informando o encerramento do chamado
						require_once 'include/PHP/class/class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->From     = $pagina->EmailRemetente;
						$mail->FromName = $pagina->remetenteEmailCEA;
						$mail->Sender   = $pagina->EmailRemetente;
						$mail->Subject  = "CAU - Notifica��o de encerramento de chamado";
						$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
						if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
					        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
					    } else {
					        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
					    }

					    $v_TXT_CAUSA_RAIZ_EMAIL = $v_TXT_CAUSA_RAIZ!=""?"<br>&nbsp;&nbsp;- Cauza raiz: <b>".$v_TXT_CAUSA_RAIZ."</b>":"";

					    $chamadoEmail = new chamado();
					    $chamadoEmail->email($row["seq_chamado_filho"]);
						$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
											    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
											<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
											<head>
												<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
												<link href=\"".$pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
											</head>
											<body>
											<div align=\"left\">
													Prezado(a) usu�rio(a) ".$chamadoEmail->NOM_CLIENTE.",<br><br>
										   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seu chamado, de N� ".$row["seq_chamado_filho"].", acaba de ter o seu atendimento t�cnico encerrado. Seguem as informa��es do chamado:
										   <br>
										   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
										   <br>&nbsp;&nbsp;- Solicita��o: <b>".$chamadoEmail->TXT_CHAMADO."</b>
										   <br>&nbsp;&nbsp;- Data e hora de abertura: <b>".$chamadoEmail->DTH_ABERTURA."</b>
										   <br>&nbsp;&nbsp;- Data e hora do encerramento: <b>".$chamadoEmail->DTH_ENCERRAMENTO_EFETIVO."</b>
										   <br>&nbsp;&nbsp;- Observa��o(�es) de Encerramento: <b>".$chamadoEmail->TXT_RESOLUCAO."</b>
										   ".$v_TXT_CAUSA_RAIZ_EMAIL."
										   <br>
										   <br>".$v_MSG_FINAL."
										   <br>
										   <br>A Central de Atendimento ao Usu�rio agradece o seu contato.
										   	   Em caso de problemas ou solicita��es, estamos � disposi��o para atend�-lo.
										   <br>
										   <br>Esta � uma mensagem autom�tica, favor n�o responder.
										   <br>---
										   <br>CAU - Central de Atendimento ao Usu�rio - ".$pagina->nom_area_ti."
										   <br>".$v_ENDERECO_SITE."
										   </div>
										   </body>
										   </html>";
						$mail->AddAddress($chamadoEmail->EMAIL_CLIENTE, $chamadoEmail->NOM_CLIENTE);
						$mail->Body    = $v_DS_CORPO;
						$mail->AltBody = $v_DS_CORPO;
						$mail->Send();
						$mail->ClearAddresses();
					}
				}
			}
			// -------------------------------------------------------------------------------------

			// Encerrar time_sheet
			require_once 'include/PHP/class/class.time_sheet.php';
			$time_sheet = new time_sheet();
			$time_sheet->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$time_sheet->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$time_sheet->FinalizarTarefa();

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
	$pagina->SettituloCabecalho("Encerrar atendimento do chamado"); // Indica o t�tulo do cabe�alho da p�gina
	$pagina->method = "post";

	require_once 'include/PHP/class/class.chamado.php';
	$banco = new chamado();
	$banco->select($v_SEQ_CHAMADO);

	require_once 'include/PHP/class/class.situacao_chamado.php';
	$situacao_chamado = new situacao_chamado();

	$aItemAba = Array();
	$aItemAba[] = array("#", "", "Detalhes", "onclick=\"AcessarAcao('ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Alterar", "onclick=\"AcessarAcao('ChamadoAlterar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Atribuir", "onclick=\"AcessarAcao('ChamadoAtribuir.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Atendimento", "onclick=\"AcessarAcao('ChamadoRegistroAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Suspender", "onclick=\"AcessarAcao('ChamadoSuspender.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
	$aItemAba[] = array("#", "", "Cancelar", "onclick=\"AcessarAcao('ChamadoCancelar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
	$aItemAba[] = array("#", "", "Devolver 1� n�vel", "onclick=\"AcessarAcao('ChamadoDevolver1Nivel.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");

	// Se for poss�vel realizar o contigenciamento do chamado
	if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
		if($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Atendimento || $banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Em_Andamento || $banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Suspenca ){
			$aItemAba[] = array("#", "", "Contingenciar", "onclick=\"AcessarAcao('ChamadoContingenciar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
		}
		$aItemAba[] = array("#", "", "Vincular", "onclick=\"AcessarAcao('ChamadoVincular.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	}

	$aItemAba[] = array("#", "tabact", "Encerrar", "onclick=\"AcessarAcao('ChamadoEncerramento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");

	$pagina->SetaItemAba($aItemAba);
	$pagina->estiloTabBar = "tabbarMenor";
	$pagina->flagScriptCalendario = 0;

	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_CHAMADO", $v_SEQ_CHAMADO);
	print $pagina->CampoHidden("v_SEQ_TIPO_OCORRENCIA", $banco->SEQ_TIPO_OCORRENCIA);

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
			 if(document.form.v_TXT_HISTORICO.value == ""){
			 	alert("Preencha o campo observa��o");
			 	return false;
			 }
			 <? if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){ ?>
					if(document.form.v_TXT_CAUSA_RAIZ.value == ""){
					 	alert("Preencha o campo causa raiz");
					 	return false;
					 }
			<?  } ?>

			 if(confirm("Esta a��o encerrar� o atendimento do chamado, finalizar� o seu lan�amento no Time Sheet e retornar� para a tela de atendimento. \n Confirma a a��o?") == true){
			 	document.form.enviar.disabled = true;
			 	document.form.submit();
			 }else{
			 	return false;
			 }
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
	$pagina->LinhaCampoFormularioColspanDestaque("Observa��es sobre o encerramento", 2);

	// Descri��o
	if($banco->TXT_RESOLUCAO != ""){
		$pagina->LinhaCampoFormulario("Informa��es de encerramento j� registradas no chamado:", "right", "S",
									  str_replace(chr(13), "<br>",$banco->TXT_RESOLUCAO)
									  , "left", "id=".$pagina->GetIdTable());
	}


	if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
		$label = "Descreva a corre��o definitiva aplicada:";
	}else{
		$label = "Observa��o:";
	}
	$pagina->LinhaCampoFormulario($label, "right", "S",
                                      $pagina->CampoTextArea("v_TXT_HISTORICO", "S", "Observa��o", "99", "6", "", "onkeyup=\"ContaCaracteres(2000, this, document.getElementById('conta_caracteres'))\"").
                                      "<br><span id=\"conta_caracteres\">2000</span> Caracteres restantes"
                                      , "left", "id=".$pagina->GetIdTable());

	// ==============================================================================================================
	// Gest�o de Problemas
	// Caso o chamado seja incidente, o campo causa raiz � obrigat�rio
	if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
		$pagina->LinhaCampoFormulario("Causa raiz:", "right", "S",
                                              $pagina->CampoTextArea("v_TXT_CAUSA_RAIZ", "S", "Causa raiz", "99", "6", $banco->TXT_CAUSA_RAIZ, "onkeyup=\"ContaCaracteres(2000, this, document.getElementById('conta_caracteres2'))\"").
                                              "<br><span id=\"conta_caracteres2\">2000</span> Caracteres restantes"
                                              , "left", "id=".$pagina->GetIdTable());
	}
	// ==============================================================================================================
	// Checkbox para fechar as demais atribui��es da equipe, caso existam
	$atribuicao_chamado = new atribuicao_chamado();
	$atribuicao_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$atribuicao_chamado->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	if($atribuicao_chamado->AnalisarEncerramentoEquipe() == 1){
		$pagina->LinhaCampoFormularioColspan("left", $pagina->CampoCheckboxSimples("v_ENCERRA_EQUIPE", "S", "", "Encerrar tamb�m as atribui��es dos meus companheiros de equipe ao chamado"), "2");
	}

	// ==============================================================================================================

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