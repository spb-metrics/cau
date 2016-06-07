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
		 	$vErroCampos .= "Preencha o campo observação. ";
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

			// Verificar se a atividade é de atendimento externo
			if($chamado->FLG_ATENDIMENTO_EXTERNO == "N"){ // Para atendimento interno, o encerramento é direto
				$v_MSG_FINAL = "Para maiores informações acesse o CAU.";
				$v_ENDERECO_SITE = $pagina->enderecoGestaoTI;
				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Encerrada;
			}elseif($chamado->FLG_ATENDIMENTO_EXTERNO == "S"){ // Para atendimento externo, o chamado é encaminhado para avaliação
				$v_MSG_FINAL = "Por favor, acesse o CAU e avalie o nosso atendimento, sua opinião é muito importante.
								<br><br>
								Caso a sua solicitação não tenha sido resolvida o chamado porderá ser reaberto por meio do formulário de avaliação. ";
				$v_ENDERECO_SITE = $pagina->enderecoCEA;
				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Avaliacao;
			}

			// Atualizar texto de solução
			if($chamado->TXT_RESOLUCAO == ""){
				$chamado->AtualizaTxtSolucao($v_SEQ_CHAMADO, "Por ".$_SESSION["NOME"]." em ".date("d/m/Y H:i").chr(13).$v_TXT_HISTORICO);
			}else{
				$chamado->AtualizaTxtSolucao($v_SEQ_CHAMADO, $chamado->TXT_RESOLUCAO.chr(13).chr(13)."Por ".$_SESSION["NOME"]." em ".date("d/m/Y H:i").chr(13).$v_TXT_HISTORICO);
			}

			// Atualizar atribuição
			$atribuicao_chamado = new atribuicao_chamado();
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			// Buscar atribuição
			$atribuicao_chamado->selectMatricula();

			// Informar a nova situação
			$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Encerrada);
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->AtualizarSituacao();

			// Atualizar encerramento efetivo
			$atribuicao_chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO();

			// ==================== Encerrar atribuições da equipe =================
			if($v_ENCERRA_EQUIPE == "S"){
				// Buscar atribuições da equipe
				$atribuicao_chamado = new atribuicao_chamado();
				$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$atribuicao_chamado->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
				$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$atribuicao_chamado->SelectEncerramentoEquipe();
				if($atribuicao_chamado->database->rows > 0){
					while ($row = pg_fetch_array($atribuicao_chamado->database->result)){
						// Atualizar atribuição
						$atribuicao_chamado1 = new atribuicao_chamado();
						$atribuicao_chamado1->setSEQ_CHAMADO($v_SEQ_CHAMADO);
						$atribuicao_chamado1->setNUM_MATRICULA($row["num_matricula"]);
						$atribuicao_chamado1->selectMatricula();

						// Informar a nova situação
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

			// Verificar se existem outras atribuições com a situação diferente
			$atribuicao_chamado = new atribuicao_chamado();
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Encerrada);
			$vRetorno = $atribuicao_chamado->AnalisarEncerramento();

			if($vRetorno == 0){ // Não existem outras equipes/profissionais em atendimento
				// Alterar situação do chamado
				$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $v_SEQ_SITUACAO_CHAMADO);

				// Alterar data de encerramento efetivo
				$chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO($v_SEQ_CHAMADO);

				// Atualizar causa raiz
				if($v_TXT_CAUSA_RAIZ != ""){
					$chamado->AtualizaTxtCausaRaiz($v_SEQ_CHAMADO, $v_TXT_CAUSA_RAIZ);
				}

				// Incluir histórico
				require_once 'include/PHP/class/class.historico_chamado.php';
				$historico_chamado = new historico_chamado();
				$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
				$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
				$historico_chamado->setTXT_HISTORICO("Encerramento de atribuição (".$_SESSION["NOM_EQUIPE_TI"]."): ".$v_TXT_HISTORICO);
				$historico_chamado->insert();

				// Atualizar fila - Caso seja um chamado de manutenção em sistema de informação, aberto por um cliente
				//if($chamado->NUM_PRIORIDADE_FILA != ""){
				//	$chamado->RetirarChamadoFila($chamado->SEQ_CHAMADO, $_SESSION["SEQ_EQUIPE_TI"], $chamado->NUM_PRIORIDADE_FILA, 2);
				//}

				// Enviar e-mail ao solicitante informando o encerramento do chamado
				require_once 'include/PHP/class/class.phpmailer.php';
				$mail = new PHPMailer();
				$mail->From     = $pagina->EmailRemetente;
				$mail->FromName = $pagina->remetenteEmailCEA;
				$mail->Sender   = $pagina->EmailRemetente;
				$mail->Subject  = "CAU - Notificação de encerramento de chamado";
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
                                                                    Prezado(a) usuário(a) ".$chamadoEmail->NOM_CLIENTE.",<br><br>
                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seu chamado, de Nº ".$v_SEQ_CHAMADO.", acaba de ter o seu atendimento técnico encerrado. Seguem as informações do chamado:
                                               <br>
                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
                                               <br>&nbsp;&nbsp;- Solicitação: <b>".$chamadoEmail->TXT_CHAMADO."</b>
                                               <br>&nbsp;&nbsp;- Data e hora de abertura: <b>".$chamadoEmail->DTH_ABERTURA."</b>
                                               <br>&nbsp;&nbsp;- Data e hora do encerramento: <b>".$chamadoEmail->DTH_ENCERRAMENTO_EFETIVO."</b>
                                               <br>&nbsp;&nbsp;- Observação(ões) de Encerramento: <b>".$chamadoEmail->TXT_RESOLUCAO."</b>
                                               ".$v_TXT_CAUSA_RAIZ_EMAIL."
                                               <br>
                                               <br>".$v_MSG_FINAL."
                                               <br>
                                               <br>A Central de Atendimento ao Usuário agradece o seu contato.
                                                       Em caso de problemas ou solicitações, estamos à disposição para atendê-lo.
                                               <br>
                                               <br>Esta é uma mensagem automática, favor não responder.
                                               <br>---
                                               <br>CAU - Central de Atendimento ao Usuário - ".$pagina->nom_area_ti."
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

					// Verificar se a atividade é de atendimento externo
					if($chamado->FLG_ATENDIMENTO_EXTERNO == "N"){ // Para atendimento interno, o encerramento é direto
						$v_MSG_FINAL = "Para maiores informações acesse o CAU.";
						$v_ENDERECO_SITE = $pagina->enderecoGestaoTI;
						$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Encerrada;
					}elseif($chamado->FLG_ATENDIMENTO_EXTERNO == "S"){ // Para atendimento externo, o chamado é encaminhado para avaliação
						$v_MSG_FINAL = "Por favor, acesse o CAU e avalie o nosso atendimento, sua opinião é muito importante.
										<br><br>
										Caso a sua solicitação não tenha sido resolvida o chamado porderá ser reaberto por meio do formulário de avaliação. ";
						$v_ENDERECO_SITE = $pagina->enderecoCEA;
						$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Avaliacao;
					}

					// Atualizar atribuição
					$atribuicao_chamado = new atribuicao_chamado();
					$atribuicao_chamado->setSEQ_CHAMADO($row["seq_chamado_filho"]);
					$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					// Buscar atribuição
					$atribuicao_chamado->selectMatricula();

					// Informar a nova situação
					$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Encerrada);
					$atribuicao_chamado->AtualizarSituacao();

					// Atualizar encerramento efetivo
					$atribuicao_chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO();

					// Verificar se existem outras atribuições com a situação diferente
					$atribuicao_chamado = new atribuicao_chamado();
					$atribuicao_chamado->setSEQ_CHAMADO($row["seq_chamado_filho"]);
					$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Encerrada);
					$vRetorno = $atribuicao_chamado->AnalisarEncerramento();
					if($vRetorno == 0){ // Não existem outras equipes/profissionais em atendimento
						// Alterar situação do chamado
						$chamado->AtualizaSituacao($row["seq_chamado_filho"], $v_SEQ_SITUACAO_CHAMADO);

						// Alterar data de encerramento efetivo
						$chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO($row["seq_chamado_filho"]);

						// Atualizar causa raiz
						if($v_TXT_CAUSA_RAIZ != ""){
							$chamado->AtualizaTxtCausaRaiz($row["seq_chamado_filho"], $v_TXT_CAUSA_RAIZ);
						}

						// Incluir histórico
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
						$mail->Subject  = "CAU - Notificação de encerramento de chamado";
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
													Prezado(a) usuário(a) ".$chamadoEmail->NOM_CLIENTE.",<br><br>
										   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seu chamado, de Nº ".$row["seq_chamado_filho"].", acaba de ter o seu atendimento técnico encerrado. Seguem as informações do chamado:
										   <br>
										   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
										   <br>&nbsp;&nbsp;- Solicitação: <b>".$chamadoEmail->TXT_CHAMADO."</b>
										   <br>&nbsp;&nbsp;- Data e hora de abertura: <b>".$chamadoEmail->DTH_ABERTURA."</b>
										   <br>&nbsp;&nbsp;- Data e hora do encerramento: <b>".$chamadoEmail->DTH_ENCERRAMENTO_EFETIVO."</b>
										   <br>&nbsp;&nbsp;- Observação(ões) de Encerramento: <b>".$chamadoEmail->TXT_RESOLUCAO."</b>
										   ".$v_TXT_CAUSA_RAIZ_EMAIL."
										   <br>
										   <br>".$v_MSG_FINAL."
										   <br>
										   <br>A Central de Atendimento ao Usuário agradece o seu contato.
										   	   Em caso de problemas ou solicitações, estamos à disposição para atendê-lo.
										   <br>
										   <br>Esta é uma mensagem automática, favor não responder.
										   <br>---
										   <br>CAU - Central de Atendimento ao Usuário - ".$pagina->nom_area_ti."
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

			// Redirecionar para a página de atendimento
			$pagina->redirectTo("ChamadoAtendimentoPesquisa.php");
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
	$pagina->SettituloCabecalho("Encerrar atendimento do chamado"); // Indica o título do cabeçalho da página
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
	$aItemAba[] = array("#", "", "Devolver 1º nível", "onclick=\"AcessarAcao('ChamadoDevolver1Nivel.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");

	// Se for possível realizar o contigenciamento do chamado
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
			 if(document.form.v_TXT_HISTORICO.value == ""){
			 	alert("Preencha o campo observação");
			 	return false;
			 }
			 <? if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){ ?>
					if(document.form.v_TXT_CAUSA_RAIZ.value == ""){
					 	alert("Preencha o campo causa raiz");
					 	return false;
					 }
			<?  } ?>

			 if(confirm("Esta ação encerrará o atendimento do chamado, finalizará o seu lançamento no Time Sheet e retornará para a tela de atendimento. \n Confirma a ação?") == true){
			 	document.form.enviar.disabled = true;
			 	document.form.submit();
			 }else{
			 	return false;
			 }
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
	$pagina->LinhaCampoFormularioColspanDestaque("Observações sobre o encerramento", 2);

	// Descrição
	if($banco->TXT_RESOLUCAO != ""){
		$pagina->LinhaCampoFormulario("Informações de encerramento já registradas no chamado:", "right", "S",
									  str_replace(chr(13), "<br>",$banco->TXT_RESOLUCAO)
									  , "left", "id=".$pagina->GetIdTable());
	}


	if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
		$label = "Descreva a correção definitiva aplicada:";
	}else{
		$label = "Observação:";
	}
	$pagina->LinhaCampoFormulario($label, "right", "S",
                                      $pagina->CampoTextArea("v_TXT_HISTORICO", "S", "Observação", "99", "6", "", "onkeyup=\"ContaCaracteres(2000, this, document.getElementById('conta_caracteres'))\"").
                                      "<br><span id=\"conta_caracteres\">2000</span> Caracteres restantes"
                                      , "left", "id=".$pagina->GetIdTable());

	// ==============================================================================================================
	// Gestão de Problemas
	// Caso o chamado seja incidente, o campo causa raiz é obrigatório
	if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
		$pagina->LinhaCampoFormulario("Causa raiz:", "right", "S",
                                              $pagina->CampoTextArea("v_TXT_CAUSA_RAIZ", "S", "Causa raiz", "99", "6", $banco->TXT_CAUSA_RAIZ, "onkeyup=\"ContaCaracteres(2000, this, document.getElementById('conta_caracteres2'))\"").
                                              "<br><span id=\"conta_caracteres2\">2000</span> Caracteres restantes"
                                              , "left", "id=".$pagina->GetIdTable());
	}
	// ==============================================================================================================
	// Checkbox para fechar as demais atribuições da equipe, caso existam
	$atribuicao_chamado = new atribuicao_chamado();
	$atribuicao_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$atribuicao_chamado->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	if($atribuicao_chamado->AnalisarEncerramentoEquipe() == 1){
		$pagina->LinhaCampoFormularioColspan("left", $pagina->CampoCheckboxSimples("v_ENCERRA_EQUIPE", "S", "", "Encerrar também as atribuições dos meus companheiros de equipe ao chamado"), "2");
	}

	// ==============================================================================================================

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