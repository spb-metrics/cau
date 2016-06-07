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
require_once 'include/PHP/class/class.time_sheet.php';
require_once 'include/PHP/class/class.atribuicao_chamado.php';
require_once 'include/PHP/class/class.item_configuracao.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

require_once 'include/PHP/class/class.chamado.php';
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.tipo_ocorrencia.php';

require_once 'include/PHP/class/class.subtipo_chamado.php';
require_once 'include/PHP/class/class.atividade_chamado.php';

$tipo_ocorrencia = new tipo_ocorrencia();
$tipo_chamado = new tipo_chamado();
$banco = new chamado();
$situacao_chamado = new situacao_chamado();
$atividade_chamado_aux = new atividade_chamado();
$subtipo_chamado_aux = new subtipo_chamado();

$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

// ============================================================================================================
// Realizar o atendimento do chamado
// ============================================================================================================
if($flag == "1"){
	// Validar campos
	$vErroCampos = "";
	require_once 'include/PHP/class/class.subtipo_chamado.php';
	require_once 'include/PHP/class/class.tipo_chamado.php';
	require_once 'include/PHP/class/class.atividade_chamado.php';
	$subtipo_chamado = new subtipo_chamado();
	$tipo_chamado = new tipo_chamado();
	$atividade_chamado = new atividade_chamado();

	if($v_SEQ_TIPO_CHAMADO == ""){
		$vErroCampos = "Preencha o campo Tipo de Chamado. ";
	}
	if($v_SEQ_SUBTIPO_CHAMADO == ""){
		$vErroCampos .= "Preencha o campo Subtipo de Chamado. ";
	}
	if($v_SEQ_ATIVIDADE_CHAMADO == ""){
		$vErroCampos .= "Preencha o campo Atividade. ";
	}
	if($v_SEQ_PRIORIDADE_CHAMADO == ""){
		$vErroCampos .= "Preencha o campo Prioridade. ";
	}
	if($v_TXT_RESOLUCAO == ""){
		$vErroCampos .= "Preencha o campo Descrição do atendimento de 1o nível. ";
	}

	// Se for chamado improcedênte
	if($v_SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_IMPROCEDENTE){
		if($v_TXT_HISTORICO == ""){
			$vErroCampos .= "Preencha o campo Justificativa da Improcedência. ";
		}
	}else{
	//	if($v_LISTA_ATRIBUICAO == ""){
	//	 	$vErroCampos .= "Atribua o chamado a pelo menos uma equipe";
	//	}
	}

	if($v_SEQ_TIPO_CHAMADO == $tipo_chamado->COD_TIPO_SISTEMAS_INFORMACAO){
		if($v_SEQ_ITEM_CONFIGURACAO == ""){
			$camposValidados = 0;
			$mensagemErro .= $pagina->iif($mensagemErro=="","Sistema de informação", ", Sistema de informação");
		}
	}else{
		$v_SEQ_ITEM_CONFIGURACAO = "";
	}

	if($vErroCampos == ""){
		// Lançar finalização da atividade no timesheet
		$time_sheet = new time_sheet();
		$time_sheet->setSEQ_CHAMADO($v_SEQ_CHAMADO);
		$time_sheet->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
		$time_sheet->FinalizarTarefa();

		//==================================================================
		// Atualizar chamado
		// Se o chamado for improcedente ou se o atendimento de primeiro nível resolveu o problema
		if( $v_SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_IMPROCEDENTE  || $v_LISTA_ATRIBUICAO    == ""){
			$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Avaliacao);
		}else{			
                    // Se exige aprovação - Aguardando aprovação
                    // Se SLA não é definido - Aguardando planejamento
                    // Se SLA definido - Aguardando Atendimento
                    
                        // Buscar o fluxo pela configuração da atividade
			$atividade_chamado->select($v_SEQ_ATIVIDADE_CHAMADO);
                    
                        if($atividade_chamado->FLG_EXIGE_APROVACAO == "1"){
                                $banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Aprovacao);
                        }elseif($atividade_chamado->QTD_MIN_SLA_ATENDIMENTO == ""){
				$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Planejamento);
			}else{
				$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Atendimento);
			}
			 
		}
		$banco->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		$banco->setSEQ_ATIVIDADE_CHAMADO($v_SEQ_ATIVIDADE_CHAMADO);
		$banco->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
		$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
		$banco->triagem($v_SEQ_CHAMADO);

		//==================================================================
		// Incluir atribuição
		$atribuicao_chamado = new atribuicao_chamado();
		$atribuicao_chamado->TXT_ATIVIDADE = "Realizar atendimento em 1º nível";
		$atribuicao_chamado->AtualizarAtribuicao($v_SEQ_CHAMADO, $_SESSION["SEQ_EQUIPE_TI"], $_SESSION["NUM_MATRICULA_RECURSO"], $situacao_chamado->COD_Encerrada, "", 1);

		// Atualizar inicio efetivo
		$atribuicao_chamado->AtualizaDTH_INICIO_EFETIVO_TRIAGEM($v_DTH_INICIO_EFETIVO);

		// Atualizar encerramento efetivo
		$atribuicao_chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO();

		//==================================================================
		// Atualizar texto de solução
		$banco->AtualizaTxtSolucao($v_SEQ_CHAMADO, "Por ".$_SESSION["NOME"]." em ".date("d/m/Y H:i").chr(13).$v_TXT_RESOLUCAO);

		//==================================================================
		// Se o chamado for improcedênte
		if($v_SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_IMPROCEDENTE){
			// Inserir no histórico
			require_once 'include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Avaliacao);
			//$historico_chamado->setSEQ_MOTIVO_SUSPENCAO($v_SEQ_MOTIVO_SUSPENCAO);
			$historico_chamado->setTXT_HISTORICO($v_TXT_HISTORICO);
			$historico_chamado->insert();
		}else{
			// Concluir o atendimento no primeiro nível
			if($v_LISTA_ATRIBUICAO == ""){
				//==================================================================
				// Inserir no histórico
				require_once 'include/PHP/class/class.historico_chamado.php';
				$historico_chamado = new historico_chamado();
				$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$historico_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Avaliacao);
				$historico_chamado->setTXT_HISTORICO("Solução do chamado em 1º nível de atendimento: ".$v_TXT_RESOLUCAO);
				$historico_chamado->insert();

				//==================================================================
				// Alterar data de encerramento efetivo
				$banco->AtualizaDTH_ENCERRAMENTO_EFETIVO($v_SEQ_CHAMADO);

				//==================================================================
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
								   <br>".$v_ENDERECO_SITE."
								   </div>
								   </body>
								   </html>";
				$mail->AddAddress($chamadoEmail->EMAIL_CLIENTE, $chamadoEmail->NOM_CLIENTE);
				$mail->Body    = $v_DS_CORPO;
				$mail->AltBody = $v_DS_CORPO;
				$mail->Send();
				$mail->ClearAddresses();

			}else{ // Inserir atribuições
				// Encaminhar para o segundo nível
				$v_TXT_HISTORICO = "Chamado encaminhado para ";

				require_once 'include/PHP/class/class.equipe_ti.php';
				$equipe_ti = new equipe_ti();
				$aLISTA_ATRIBUICAO = split("$&", $v_LISTA_ATRIBUICAO);
				for($i=0; $i<count($aLISTA_ATRIBUICAO);$i++){
					//print $aLISTA_ATRIBUICAO[$i];
					$aValores = explode("|", $aLISTA_ATRIBUICAO[$i]);
					//print $aValores;
					$v_SEQ_EQUIPE_TI 		 = $aValores[0];
					$v_NUM_MATRICULA 		 = $aValores[1];
					$v_TXT_ATIVIDADE 		 = $aValores[2];
					$v_SEQ_EQUIPE_ATRIBUICAO = $aValores[3];
					// Pegar o nome da equipe
					$equipe_ti->select($v_SEQ_EQUIPE_TI);
					$v_TXT_HISTORICO .= $equipe_ti->NOM_EQUIPE_TI.",";

					$atribuicao_chamado = new atribuicao_chamado();
					$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
					$atribuicao_chamado->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
					
					
					//NOVA PARTE APROVACAO DE CHAMADO
					$chamado = new chamado();
					$chamado->select($v_SEQ_CHAMADO);
					
					require_once 'include/PHP/class/class.atividade_chamado.php';
					$atividade_chamado = new atividade_chamado();
					$atividade_chamado->select($chamado->SEQ_ATIVIDADE_CHAMADO);
					
					if($atividade_chamado->QTD_MIN_SLA_ATENDIMENTO == ""){ // Não tempos SLA - Fluxo de aprovação						 
						$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Planejamento);
					}else{ // Temos SLA - Fluxo normal de atendimento
						$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Atendimento);
					}
					
					//NOVA PARTE APROVACAO DE CHAMADO
					
					//$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Atendimento);
					$atribuicao_chamado->setNUM_MATRICULA($v_NUM_MATRICULA);
					$atribuicao_chamado->setSEQ_EQUIPE_ATRIBUICAO($v_SEQ_EQUIPE_ATRIBUICAO);
					$atribuicao_chamado->setTXT_ATIVIDADE($v_TXT_ATIVIDADE);
					$atribuicao_chamado->insert();

					// Enviar e-mail para o profissional atribuído
					if($v_NUM_MATRICULA != ""){
						require_once 'include/PHP/class/class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->From     = $pagina->EmailRemetente;
						$mail->FromName = $pagina->remetenteEmailCEA;
						$mail->Sender   = $pagina->EmailRemetente;
						$mail->Subject  = "CAU - Notificação de Atribuição Chamado";
						$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
						if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
					        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
					    } else {
					        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
					    }
						require_once 'include/PHP/class/class.chamado.php';
					    $chamadoEmail = new chamado();
					    $chamadoEmail->email($v_SEQ_CHAMADO);
						$v_DS_CORPO ="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
									    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
									<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
									<head>
										<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
										<link href=\"".$pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
									</head>
									<body>
									<div align=\"left\">
											<NOM_DETINATARIO>,<br>
								   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;acaba de ser aberto, no CAU, o chamado nº ".$v_SEQ_CHAMADO.", atribuído você. Seguem abaixo os dados do chamado.
								   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
								   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
								   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
								   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
								   <br>&nbsp;&nbsp;- Solicitação: <b>".$chamadoEmail->TXT_CHAMADO."</b>
								   <br>&nbsp;&nbsp;- Cliente: <b>".$chamadoEmail->NOM_CLIENTE."</b>
								   <br>
								   <br>Para maiores informações acesse o CAU na sua área de atendimento.
								   <br>Esta e uma mensagem autom&aacute;tica, favor n&atilde;o responder.
								   <br>---
								   <br>CAU - Central de Atendimento ao Usuário
								   <br>".$pagina->enderecoGestaoTI."
								   </div>
								   </body>
								   </html>";

						// Buscar contato do líder e do substituto
						require_once 'include/PHP/class/class.equipe_ti.php';
						$equipe_ti = new equipe_ti();
						$equipe_ti->EmailLiderSubstituto($v_SEQ_EQUIPE_TI);
						// Ao líder
						if($equipe_ti->DSC_EMAIL_LIDER != ""){
							$mail->AddAddress($equipe_ti->DSC_EMAIL_LIDER, $equipe_ti->NOM_LIDER);
						}

						// Buscar e-mail do colaborador
						require_once 'include/PHP/class/class.empregados.oracle.php';
						$empregados = new empregados();
						$empregados->GetNomeEmail($v_NUM_MATRICULA);
						// Adicionar
						$mail->AddAddress($empregados->DES_EMAIL, $empregados->NOME);
						// Enviar
						$mail->Body    = str_replace("<NOM_DETINATARIO>", $empregados->NOME, $v_DS_CORPO);
                                                $mail->AltBody = str_replace("<NOM_DETINATARIO>", $empregados->NOME, $v_DS_CORPO);
                                                $mail->Send();
						$mail->ClearAddresses();
					}else{ // Enviar e-mail para o líder da equipe
						require_once 'include/PHP/class/class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->From     = $pagina->EmailRemetente;
						$mail->FromName = $pagina->remetenteEmailCEA;
						$mail->Sender   = $pagina->EmailRemetente;
						$mail->Subject  = "CAU - Notificação de Atribuição Chamado";
						$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
						if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
					        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
					    } else {
					        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
					    }

					    $chamadoEmail = new chamado();
					    $chamadoEmail->email($v_SEQ_CHAMADO);
						$v_DS_CORPO ="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
									    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
									<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
									<head>
										<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
										<link href=\"".$pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
									</head>
									<body>
									<div align=\"left\">
											<NOM_DETINATARIO>,<br>
								   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;acaba de ser aberto, no CAU, o chamado nº ".$v_SEQ_CHAMADO.", atribuído para a sua equipe. Seguem abaixo os dados do chamado.
								   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
								   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
								   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
								   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
								   <br>&nbsp;&nbsp;- Solicitação: <b>".$chamadoEmail->TXT_CHAMADO."</b>
								   <br>&nbsp;&nbsp;- Cliente: <b>".$chamadoEmail->NOM_CLIENTE."</b>
								   <br>
								   <br>Para maiores informações acesse o CAU na sua área de atendimento.
								   <br>Esta e uma mensagem autom&aacute;tica, favor n&atilde;o responder.
								   <br>---
								   <br>CAU - Central de Atendimento ao Usuário
								   <br>".$pagina->enderecoGestaoTI."
								   </div>
								   </body>
								   </html>";

						// Buscar contatos do líder e do substituto
						require_once 'include/PHP/class/class.equipe_ti.php';
						$equipe_ti = new equipe_ti();
						$equipe_ti->EmailLiderSubstituto($v_SEQ_EQUIPE_TI);
						// Ao líder
						if($equipe_ti->DSC_EMAIL_LIDER != ""){
							$mail->AddAddress($equipe_ti->DSC_EMAIL_LIDER, $equipe_ti->NOM_LIDER);
							$mail->Body    = str_replace("<NOM_DETINATARIO>", $equipe_ti->NOM_LIDER, $v_DS_CORPO);
			    			$mail->AltBody = str_replace("<NOM_DETINATARIO>", $equipe_ti->NOM_LIDER, $v_DS_CORPO);
							$mail->Send();
							$mail->ClearAddresses();
						}

						// Ao Substituto
						if($equipe_ti->DSC_EMAIL_SUBSTITUTO != ""){
							$mail->AddAddress($equipe_ti->DSC_EMAIL_SUBSTITUTO, $equipe_ti->NOM_SUBSTITUTO);
							$mail->Body    = str_replace("<NOM_DETINATARIO>", $equipe_ti->NOM_SUBSTITUTO, $v_DS_CORPO);
			    			$mail->AltBody = str_replace("<NOM_DETINATARIO>", $equipe_ti->NOM_SUBSTITUTO, $v_DS_CORPO);
			    			$mail->Send();
							$mail->ClearAddresses();
						}
					}
				}
				$v_TXT_HISTORICO = substr($v_TXT_HISTORICO,0,$v_TXT_HISTORICO-1);

				// Inserir no histórico
				require_once 'include/PHP/class/class.historico_chamado.php';
				$historico_chamado = new historico_chamado();
				$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$historico_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Atendimento);
				//$historico_chamado->setSEQ_MOTIVO_SUSPENCAO($v_SEQ_MOTIVO_SUSPENCAO);
				$historico_chamado->setTXT_HISTORICO($v_TXT_HISTORICO);
				$historico_chamado->insert();
			}
		}

		// Retornar para a tela de triagem
		$pagina->redirectTo("ChamadoTriagemPesquisa.php");
	}else{
		$flag = "";
	}
}

// ============================================================================================================
// Realizar o cancelamento do atendimento
// ============================================================================================================
if($flag == "2"){
	// Lançar finalização da atividade no timesheet
	$time_sheet = new time_sheet();
	$time_sheet->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$time_sheet->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$time_sheet->FinalizarTarefa();

	// Retornar para a tela de triagem
	$pagina->redirectTo("ChamadoTriagemPesquisa.php");
}

// ============================================================================================================
// Realizar o cancelamento do chamado
// ============================================================================================================
if($flag == "3"){
	require_once 'include/PHP/class/class.chamado.php';
	require_once 'include/PHP/class/class.situacao_chamado.php';
	require_once 'include/PHP/class/class.atividade_chamado.php';
	$chamado = new chamado();
	$situacao_chamado = new situacao_chamado();

	//==================================================================
	// Atualizar chamado
	$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Cancelado);
	$banco->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
	$banco->setSEQ_ATIVIDADE_CHAMADO($v_SEQ_ATIVIDADE_CHAMADO);
	$banco->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
	$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
	$banco->triagem($v_SEQ_CHAMADO);

	//==================================================================
	// Incluir atribuição
	$atribuicao_chamado = new atribuicao_chamado();
	$atribuicao_chamado->TXT_ATIVIDADE = "Realizar atendimento em 1º nível";
	$atribuicao_chamado->AtualizarAtribuicao($v_SEQ_CHAMADO, $_SESSION["SEQ_EQUIPE_TI"], $_SESSION["NUM_MATRICULA_RECURSO"], $situacao_chamado->COD_Encerrada, "", 1);

	// Atualizar inicio efetivo
	$atribuicao_chamado->AtualizaDTH_INICIO_EFETIVO_TRIAGEM($v_DTH_INICIO_EFETIVO);

	// Atualizar encerramento efetivo
	$atribuicao_chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO();

	//==================================================================
	// Atualizar texto de solução
	$banco->AtualizaTxtSolucao($v_SEQ_CHAMADO, "Por ".$_SESSION["NOME"]." em ".date("d/m/Y H:i").chr(13).$v_TXT_RESOLUCAO);

	$v_ENDERECO_SITE = $pagina->enderecoCEA;
	$v_MSG_FINAL = "Para maiores informações acesse o CAU.";

	// Alterar data de encerramento efetivo
	$chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO($v_SEQ_CHAMADO);

	// Alterar motivo de cancelamento
	$chamado->AtualizaMotivoCancelamento($v_SEQ_CHAMADO,$v_SEQ_MOTIVO_CANCELAMENTO);

	// Incluir histórico
	require_once 'include/PHP/class/class.historico_chamado.php';
	$historico_chamado = new historico_chamado();
	$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$historico_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Cancelado);
	$historico_chamado->setTXT_HISTORICO($v_TXT_RESOLUCAO);
	$historico_chamado->insert();

	// Encerrar time_sheet
	require_once 'include/PHP/class/class.time_sheet.php';
	$time_sheet = new time_sheet();
	$time_sheet->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$time_sheet->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$time_sheet->FinalizarTarefa();

	// Enviar e-mail
	require_once 'include/PHP/class/class.phpmailer.php';
	$mail = new PHPMailer();
	$mail->From     = $pagina->EmailRemetente;
	$mail->FromName = $pagina->remetenteEmailCEA;
	$mail->Sender   = $pagina->EmailRemetente;
	$mail->Subject  = "CAU - Notificação de Cancelamento de Chamado";
	$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
	if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
    } else {
        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
    }
	require_once 'include/PHP/class/class.chamado.php';
    $chamadoEmail = new chamado();
    $chamadoEmail->email($v_SEQ_CHAMADO);

    require_once 'include/PHP/class/class.motivo_cancelamento.php';
    $motivo_cancelamento = new motivo_cancelamento();
    $motivo_cancelamento->select($v_SEQ_MOTIVO_CANCELAMENTO);

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
					   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Acaba de ser cancelado, no CAU, o atendimento do chamado nº ".$v_SEQ_CHAMADO.". Seguem abaixo os dados do chamado.
			   <br>
                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
			   <br>&nbsp;&nbsp;- Solicitação: <b>".$chamadoEmail->TXT_CHAMADO."</b>
			   <br>&nbsp;&nbsp;- Cliente: <b>".$chamadoEmail->NOM_CLIENTE."</b>
			   <br>&nbsp;&nbsp;- Motivo do cancelamento: <b>".$motivo_cancelamento->DSC_MOTIVO_CANCELAMENTO."</b>
			   <br>&nbsp;&nbsp;- Detalhamento do motivo do cancelamento: <b>".$v_TXT_RESOLUCAO."</b>
			   <br>
			   <br>Para maiores informações acesse o Gestão TI na sua área de atendimento.
			   <br>Esta e uma mensagem autom&aacute;tica, favor n&atilde;o responder.
			   <br>---
			   <br>CAU - Central de Atendimento ao Usuário
			   <br>".$v_ENDERECO_SITE."
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

	// Redirecionar para a página de atendimento
	$pagina->redirectTo("ChamadoTriagemPesquisa.php");
}

// ============================================================================================================
// Configurações AJAX
// ============================================================================================================
require_once 'include/PHP/class/class.Sajax.php';
$Sajax = new Sajax();

function CarregarComboTipoChamado($v_SEQ_TIPO_OCORRENCIA,$v_SEQ_CENTRAL_ATENDIMENTO){
	if($v_SEQ_TIPO_OCORRENCIA != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.tipo_chamado.php';
		$pagina = new Pagina();
		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		$tipo_chamado->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
		return $pagina->AjaxFormataArrayCombo($tipo_chamado->combo("DSC_TIPO_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboSubtipoChamado($v_SEQ_TIPO_CHAMADO, $v_SEQ_TIPO_OCORRENCIA){
	if($v_SEQ_TIPO_CHAMADO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.subtipo_chamado.php';
		$pagina = new Pagina();
		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
		$subtipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		return $pagina->AjaxFormataArrayCombo($subtipo_chamado->combo("DSC_SUBTIPO_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboAtividade($v_SEQ_SUBTIPO_CHAMADO, $v_SEQ_TIPO_OCORRENCIA){
	if($v_SEQ_SUBTIPO_CHAMADO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.atividade_chamado.php';
		$pagina = new Pagina();
		$atividade_chamado = new atividade_chamado();
		$atividade_chamado->setSEQ_SUBTIPO_CHAMADO($v_SEQ_SUBTIPO_CHAMADO);
		$atividade_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		return $pagina->AjaxFormataArrayCombo($atividade_chamado->combo("DSC_ATIVIDADE_CHAMADO"));
	}else{
		return "";
	}
}

function ValidarPessoaContato($v_NUM_MATRICULA_CONTATO){
	if($v_NUM_MATRICULA_CONTATO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$pagina = new Pagina();
		$empregados = new empregados();
		$primeiraLetra = substr(strtoupper($v_NUM_MATRICULA_CONTATO), 0, 1);
		if(!is_numeric($primeiraLetra)){
			$v_NUM_MATRICULA_CONTATO = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_CONTATO);
		}
		$empregados->select($v_NUM_MATRICULA_CONTATO);
		if($empregados->NOME != ""){
			return $v_NUM_MATRICULA_CONTATO."|".$empregados->NOME."|".$empregados->NUM_DDD."-".$empregados->NUM_VOIP;
		}else{
			return "";
		}
	}else{
		return "";
	}
}

function CarregarComboEquipe($v_COD_DEPENDENCIA){
	if($v_COD_DEPENDENCIA != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.equipe_ti.php';
		$pagina = new Pagina();
		$equipe_ti = new equipe_ti();
		$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
		return $pagina->AjaxFormataArrayCombo($equipe_ti->combo("NOM_EQUIPE_TI"));
	}else{
		return "";
	}
}

function CarregarComboEquipeAtribuicao($v_SEQ_EQUIPE_TI){
	if($v_SEQ_EQUIPE_TI != "" && $v_SEQ_EQUIPE_TI == $_SESSION["SEQ_EQUIPE_TI"]){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.equipe_atribuicao.php';
		$pagina = new Pagina();
		$equipe_atribuicao = new equipe_atribuicao();
		$equipe_atribuicao->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
		return $pagina->AjaxFormataArrayCombo($equipe_atribuicao->combo("DSC_EQUIPE_ATRIBUICAO"));
	}else{
		return "";
	}
}

function CarregarComboProfissional($v_SEQ_EQUIPE_TI){
	//if($v_SEQ_EQUIPE_TI != "" && $v_SEQ_EQUIPE_TI == $_SESSION["SEQ_EQUIPE_TI"]){
	if($v_SEQ_EQUIPE_TI != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.recurso_ti.php';
		$pagina = new Pagina();
		$recurso_ti = new recurso_ti();
		$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
		return $pagina->AjaxFormataArrayCombo($recurso_ti->combo("NOME"));
	}else{
		return "";
	}
}

function FinalizarTarefa($v_SEQ_CHAMADO){
	require_once 'include/PHP/class/class.time_sheet.php';
	// Lançar finalização da atividade no timesheet
	$time_sheet = new time_sheet();
	$time_sheet->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$time_sheet->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$time_sheet->FinalizarTarefa();
}

// Função responsável por buscar dados gerais sobre a atividade selecionada
function BuscarAtribuicaoAutomatica($v_SEQ_ATIVIDADE_CHAMADO){
	$aRetorno = Array();
	if($v_SEQ_ATIVIDADE_CHAMADO == ""){
		$aRetorno[0] = "";
		$aRetorno[1] = "";
		$aRetorno[2] = "";
		$aRetorno[3] = "Selecione a atividade";

		return $aRetorno;
	}else{
		require_once 'include/PHP/class/class.atividade_chamado.php';
		require_once 'include/PHP/class/class.tipo_ocorrencia.php';

		$atividade_chamado = new atividade_chamado();
		$tipo_ocorrencia = new tipo_ocorrencia();
		$atividade_chamado->select($v_SEQ_ATIVIDADE_CHAMADO);
		if($atividade_chamado->SEQ_EQUIPE_TI != ""){
			// Buscar o nome da equipe
			require_once 'include/PHP/class/class.equipe_ti.php';
			$equipe_ti = new equipe_ti();
			$equipe_ti->select($atividade_chamado->SEQ_EQUIPE_TI);

			$aRetorno[0] = $atividade_chamado->SEQ_EQUIPE_TI;
			$aRetorno[1] = $equipe_ti->NOM_EQUIPE_TI;
			$aRetorno[2] = rawurlencode($atividade_chamado->TXT_ATIVIDADE);
		}else{
			$aRetorno[0] = "";
			$aRetorno[1] = "";
			$aRetorno[2] = "";
		}
		$vInfoAtividade = "";
		if($atividade_chamado->QTD_MIN_SLA_ATENDIMENTO == ""){
			$vInfoAtividade = "Medição de tempo: Horas Planejadas. ";
		}else{
			if($atividade_chamado->FLG_FORMA_MEDICAO_TEMPO == "C"){
				$vInfoAtividade = "Medição de tempo: Horas Corridas. ";
			}else{
				$vInfoAtividade = "Medição de tempo: Horas Úteis. ";
			}
			if($tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE == $atividade_chamado->SEQ_TIPO_OCORRENCIA){
				$vInfoAtividade .= "Tempo de Contingenciamento Máximo: ".$atividade_chamado->QTD_MIN_SLA_ATENDIMENTO." min. Solução Definitiva: ".$atividade_chamado->QTD_MIN_SLA_SOLUCAO_FINAL." min. ";
			}else{
				$vInfoAtividade .= "Tempo de Atendimento Máximo: ".$atividade_chamado->QTD_MIN_SLA_ATENDIMENTO." min. ";
			}
		}

		if($atividade_chamado->FLG_ATENDIMENTO_EXTERNO == "S"){
			$vInfoAtividade .= "Atendimento Externo.";
		}else{
			$vInfoAtividade .= "Atividade Interna.";
		}
                
                if($atividade_chamado->FLG_EXIGE_APROVACAO == "1"){
                        $vInfoAtividade .= " <font color=red><b>Exige Aprovação.</b></font>.";
                        if($atividade_chamado->NUM_MATRICULA_APROVADOR != ""){
                            require_once 'include/PHP/class/class.empregados.oracle.php';
                            $empregados = new empregados();
                            $vInfoAtividade .= " <b>Aprovador:</b> ".$empregados->GetNomeEmpregado($atividade_chamado->NUM_MATRICULA_APROVADOR).".";
                        }
                        if($atividade_chamado->NUM_MATRICULA_APROVADOR_SUBSTITUTO != ""){
                            require_once 'include/PHP/class/class.empregados.oracle.php';
                            $empregados = new empregados();
                            $vInfoAtividade .= " <b>Aprovador substituto:</b> ".$empregados->GetNomeEmpregado($atividade_chamado->NUM_MATRICULA_APROVADOR_SUBSTITUTO).".";
                        }
		}
                
		$aRetorno[3] = rawurlencode($vInfoAtividade);

		return $aRetorno;
	}
}

function CarregarComboSistemaInformacao(){
	require_once 'include/PHP/class/class.pagina.php';
	require_once 'include/PHP/class/class.item_configuracao.php';
	$pagina = new Pagina();
	$item_configuracao = new item_configuracao();
	//$item_configuracao->setNUM_MATRICULA_GESTOR($_SESSION["NUM_MATRICULA_RECURSO"]);
	return $pagina->AjaxFormataArrayCombo($item_configuracao->combo("SIG_ITEM_CONFIGURACAO"));
}

$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export("CarregarComboSubtipoChamado", "CarregarComboAtividade", "CarregarComboEquipe", "CarregarComboEquipeAtribuicao", "CarregarComboProfissional", "FinalizarTarefa", "BuscarAtribuicaoAutomatica", "CarregarComboTipoChamado", "CarregarComboSistemaInformacao");
$Sajax->sajax_handle_client_request();

if($v_SEQ_CHAMADO != ""){
	$pagina->SettituloCabecalho("Atendimento de 1º nível - ".$_SESSION["NOM_CENTRAL_ATENDIMENTO"]); // Indica o título do cabeçalho da página

	// pesquisa
	$banco->select($v_SEQ_CHAMADO);

	if($banco->SEQ_SITUACAO_CHAMADO <> $situacao_chamado->COD_Aguardando_Triagem){
		$pagina->redirectTo("ChamadoAtendimento.php");
	}

	// Adicionar registro de acesso
	require_once 'include/PHP/class/class.historico_acesso_chamado.php';
	$historico_acesso_chamado = new historico_acesso_chamado();
	$historico_acesso_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$historico_acesso_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$historico_acesso_chamado->insert();

	// Lançar timesheet do profissional de triagem
	$time_sheet = new time_sheet();
	$time_sheet->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$time_sheet->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$time_sheet->IniciarTarefa();
	
	$aItemAba = Array();
	$aItemAba[] = array("#", "tabact", "Atendimento de 1º nível", "onclick=\"AcessarAcao('ChamadoAlterar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	//$aItemAba[] = array("#", "", "Alterar Central de Atendimento", "onclick=\"AcessarAcao('ChamadoAlterarCentralAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("ChamadoAlterarCentralAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO", "", "Encaminhar Chamado");
	$pagina->SetaItemAba($aItemAba);
	$pagina->estiloTabBar = "tabbarMenor";
	$pagina->flagScriptCalendario = 0;
	
	
	// Inicio do formulário
	//$pagina->MontaCabecalho(0,"onunload=\"fSairPagina()\"");
	$pagina->MontaCabecalho(1);
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_CHAMADO", $v_SEQ_CHAMADO);
	print $pagina->CampoHidden("v_DTH_INICIO_EFETIVO", date("Y-m-d H:i:s"));
	print $pagina->CampoHidden("flagSair", "");
	print $pagina->CampoHidden("SEQ_CENTRAL_ATENDIMENTO", $_SEQ_CENTRAL_ATENDIMENTO);

	// ============================================================================================================
	// Configurações AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		<?
		$Sajax->sajax_show_javascript();
		?>
		// Chamada
		function do_CarregarComboTipoChamado() {
			x_CarregarComboTipoChamado(document.form.v_SEQ_TIPO_OCORRENCIA.value,document.form.SEQ_CENTRAL_ATENDIMENTO.value, retorno_CarregarComboTipoChamado);
		}
		// Retorno
		function retorno_CarregarComboTipoChamado(val) {
			fEncheComboBox(val, document.form.v_SEQ_TIPO_CHAMADO);
			do_CarregarComboSubtipoChamado();
		}
		// Chamada
		function do_CarregarComboSubtipoChamado() {
			if(document.form.v_SEQ_TIPO_CHAMADO.value == "<?=$tipo_chamado->COD_TIPO_SISTEMAS_INFORMACAO?>"){
				document.getElementById("combo_sistema").style.display = "block";
				do_CarregarComboSistemaInformacao();
			}else{
				document.getElementById("combo_sistema").style.display = "none";
			}

			x_CarregarComboSubtipoChamado(document.form.v_SEQ_TIPO_CHAMADO.value, document.form.v_SEQ_TIPO_OCORRENCIA.value, retorno_CarregarComboSubtipoChamado);
		}
		// Retorno
		function retorno_CarregarComboSubtipoChamado(val) {
			fEncheComboBox(val, document.form.v_SEQ_SUBTIPO_CHAMADO);
			do_CarregarComboAtividade();
		}
		// Chamada
		function do_CarregarComboAtividade() {
			x_CarregarComboAtividade(document.form.v_SEQ_SUBTIPO_CHAMADO.value, document.form.v_SEQ_TIPO_OCORRENCIA.value, retorno_CarregarComboAtividade);
		}
		// Retorno
		function retorno_CarregarComboAtividade(val) {
			fEncheComboBox(val, document.form.v_SEQ_ATIVIDADE_CHAMADO);
		}
		// Chamada
		function do_CarregarComboEquipe() {
			x_CarregarComboEquipe(document.form.v_COD_DEPENDENCIA.value, retorno_CarregarComboEquipe);
		}
		// Retorno
		function retorno_CarregarComboEquipe(val) {
			fEncheComboBox(val, document.form.v_SEQ_EQUIPE_TI);
		}
		// Chamada
		function do_CarregarComboEquipeAtribuicao() {
			//x_CarregarComboEquipeAtribuicao(document.form.v_SEQ_EQUIPE_TI.value, retorno_CarregarComboEquipeAtribuicao);
		}
		// Retorno
		function retorno_CarregarComboEquipeAtribuicao(val) {
			//fEncheComboBox(val, document.form.v_SEQ_EQUIPE_ATRIBUICAO);
		}
		// Chamada
		function do_CarregarComboProfissional() {
			//alert(document.form.v_SEQ_EQUIPE_TI.value);
			x_CarregarComboProfissional(document.form.v_SEQ_EQUIPE_TI.value, retorno_CarregarComboProfissional);
		}
		// Retorno
		function retorno_CarregarComboProfissional(val) {
			if(val == ""){
				fEncheComboBoxPlus("", document.form.v_NUM_MATRICULA_RECURSO, "Profissionais - Selecione a sua equipe");
			}else{
				fEncheComboBox(val, document.form.v_NUM_MATRICULA_RECURSO);
			}
		}
		// Chamada
		function do_FinalizarTarefa() {
			x_FinalizarTarefa(document.form.v_SEQ_CHAMADO.value, retorno_FinalizarTarefa);
		}
		// Retorno
		function retorno_FinalizarTarefa(val) {

		}
		// Chamada
		function do_BuscarAtribuicaoAutomatica() {
			// Excluir atribuições caso existam
			for (i=0; i < document.form.v_ATRIBUICAO.options.length; i++) {
				document.form.v_ATRIBUICAO.options[i].selected = true;
			}
			ExcluirAtribuicao();
			document.getElementById("comboAtribuicao").style.display = "none";
			document.getElementById("mensagem_start").style.display = "block";

			x_BuscarAtribuicaoAutomatica(document.form.v_SEQ_ATIVIDADE_CHAMADO.value, retorno_BuscarAtribuicaoAutomatica);
		}
		// Retorno
		function retorno_BuscarAtribuicaoAutomatica(val) {
			//if(val){
			//	AtribuirEquipeProfissionalAutomatico(val[0], val[1], val[2]);
			//}
			document.form.v_SEQ_EQUIPE_TI.value = val[0];
			document.form.v_NUM_MATRICULA_RECURSO.value = "";
			document.form.v_TXT_ATIVIDADE.value = url_decode(val[2]);
			document.getElementById('info_atividade').innerHTML = url_decode(val[3]);
		}
		// Chamada
		function do_CarregarComboSistemaInformacao() {
			x_CarregarComboSistemaInformacao(retorno_CarregarComboSistemaInformacao);
		}
		// Retorno
		function retorno_CarregarComboSistemaInformacao(val) {
			fEncheComboBox(val, document.form.v_SEQ_ITEM_CONFIGURACAO);
		}

		// ==================================================== FIM AJAX ==================================================
		function VerificaImprocedente() {
			// Verificar se é do tipo improcedente
			if(document.form.v_SEQ_TIPO_OCORRENCIA.value == "<?=$tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_IMPROCEDENTE?>"){
				// liberar justificativa para o encerramento do chamado
				document.getElementById("justificativa_improcedencia").style.display = "block";
			}else{
				document.getElementById("justificativa_improcedencia").style.display = "none";
				document.form.v_TXT_HISTORICO.value = "";
			}
		}

		function AtribuirEquipeProfissional(){
			//if(document.form.v_COD_DEPENDENCIA.value == ""){
			//	alert("Selecione a dependência da equipe de TI");
			//	document.form.v_COD_DEPENDENCIA.focus();
			//	return;
			//}
			if(document.form.v_SEQ_EQUIPE_TI.value == ""){
				alert("Selecione a equipe de TI");
				document.form.v_SEQ_EQUIPE_TI.focus();
				return;
			}
			if(document.form.v_TXT_ATIVIDADE.value == ""){
				alert("Informe as atividades a serem desempenhadas");
				document.form.v_TXT_ATIVIDADE.focus();
				return;
			}
			document.getElementById("comboAtribuicao").style.display = "block";
			document.getElementById("mensagem_start").style.display = "none";
			vCodigo = document.form.v_SEQ_EQUIPE_TI.value+"|"+document.form.v_NUM_MATRICULA_RECURSO.value+"|"+document.form.v_TXT_ATIVIDADE.value+"|";
			vCodigoValidacao = document.form.v_SEQ_EQUIPE_TI.value+"|"+document.form.v_NUM_MATRICULA_RECURSO.value;
			vText = "";
			//vText = "Dep.: "+GetTextItemCombo(document.form.v_COD_DEPENDENCIA);
			vText = vText + " - Equipe: "+GetTextItemCombo(document.form.v_SEQ_EQUIPE_TI);
			if(document.form.v_NUM_MATRICULA_RECURSO.value != ""){
				vText = vText + " - Proffissional: "+GetTextItemCombo(document.form.v_NUM_MATRICULA_RECURSO);
			}
			//if(document.form.v_SEQ_EQUIPE_ATRIBUICAO.value != ""){
			//	vText = vText + " - Atribuição: "+GetTextItemCombo(document.form.v_SEQ_EQUIPE_ATRIBUICAO);
			//}
			vText = vText + " - Atividades: "+document.form.v_TXT_ATIVIDADE.value;
			if(!VerificarExistenciaValorCombo(document.form.v_ATRIBUICAO_VALIDACAO, vCodigoValidacao)){
				fAdicionaValorCombo(vCodigo, vText, document.form.v_ATRIBUICAO);
				fAdicionaValorCombo(vCodigoValidacao, vText, document.form.v_ATRIBUICAO_VALIDACAO);
			}else{
				alert("Atribuição já realizada");
			}
		}

		function AtribuirEquipeProfissionalAutomatico(v_SEQ_EQUIPE_TI, v_NOM_EQUIPE_TI, v_TXT_ATIVIDADE){
			document.getElementById("comboAtribuicao").style.display = "block";
			document.getElementById("mensagem_start").style.display = "none";
			vCodigo = v_SEQ_EQUIPE_TI+"||"+v_TXT_ATIVIDADE+"|";
			vCodigoValidacao = v_SEQ_EQUIPE_TI+"|";
			vText = "";
			vText = vText + " - Equipe: "+v_NOM_EQUIPE_TI;
			vText = vText + " - Atividades: "+v_TXT_ATIVIDADE;
			if(!VerificarExistenciaValorCombo(document.form.v_ATRIBUICAO_VALIDACAO, vCodigoValidacao)){
				fAdicionaValorCombo(vCodigo, vText, document.form.v_ATRIBUICAO);
				fAdicionaValorCombo(vCodigoValidacao, vText, document.form.v_ATRIBUICAO_VALIDACAO);
			}
		}

		function ExcluirAtribuicao(){
			cont = 0;
			for (i=0; i < document.form.v_ATRIBUICAO.options.length; i++) {
				if(document.form.v_ATRIBUICAO.options[i].selected == true){
					document.form.v_ATRIBUICAO_VALIDACAO.options.remove(i-cont);
					cont++;
				}
			}
			retorno = fExcluirValorCombo(document.form.v_ATRIBUICAO);
			retorno = fExcluirValorCombo(document.form.v_ATRIBUICAO_VALIDACAO);
			if(document.form.v_ATRIBUICAO.options.length == 0){
				document.getElementById("comboAtribuicao").style.display = "none";
				document.getElementById("mensagem_start").style.display = "block";
			}
		}

		function fSairPagina(){
			if(document.form.flagSair.value != 1){
				if(confirm("Confirma o cancelamento da operação?")){
					fCancelarTriagem();
				}else{
					window.location.href="ChamadoTriagem.php?v_SEQ_CHAMADO=<?=$v_SEQ_CHAMADO?>";
				}
			}
		}

		function fCancelarTriagem(){
			document.form.flag.value = "2";
			document.form.submit();
		}

		function fValidaFormLocal(){
			// Validar campos
			if(document.form.v_SEQ_TIPO_OCORRENCIA.value == ""){
				alert("Preencha o campo Tipo");
				document.form.v_SEQ_TIPO_OCORRENCIA.focus();
				return false;
			}
			if(document.form.v_SEQ_TIPO_CHAMADO.value == ""){
				alert("Preencha o campo Classe");
				document.form.v_SEQ_TIPO_CHAMADO.focus();
				return false;
			}
			if(document.form.v_SEQ_SUBTIPO_CHAMADO.value == ""){
				alert("Preencha o campo Subclasse");
				document.form.v_SEQ_TIPO_CHAMADO.focus();
				return false;
			}
			if(document.form.v_SEQ_ATIVIDADE_CHAMADO.value == ""){
				alert("Preencha o campo Atividade");
				document.form.v_SEQ_ATIVIDADE_CHAMADO.focus();
				return false;
			}
			if(document.form.v_SEQ_PRIORIDADE_CHAMADO.value == ""){
				alert("Preencha o campo Prioridade");
				document.form.v_SEQ_PRIORIDADE_CHAMADO.focus();
				return false;
			}

			if(document.form.v_TXT_RESOLUCAO.value == ""){
				if(document.form.v_CANCELA_CHAMADO.checked == true){
					alert("Preencha o campo Descrição do motivo de cancelamento");
				}else{
					alert("Preencha o campo Detalhamento do atendimento 1º nível");
					validarSaida=false;
					fMostra('tabela1nivel','tab1nivel');
					return false;					
				}
				document.form.v_TXT_RESOLUCAO.focus();
				return false;
			}

			// Validar sistema de informação
			if(document.form.v_SEQ_TIPO_CHAMADO.value == "<?=$tipo_chamado->COD_TIPO_SISTEMAS_INFORMACAO?>"){
				if(document.form.v_SEQ_ITEM_CONFIGURACAO.value == ""){
					alert("Preencha o campo Sistema de Informação");
					document.form.v_SEQ_ITEM_CONFIGURACAO.focus();
					return false;
				}
			}

			if(document.form.v_CANCELA_CHAMADO.checked == true){
				if(document.form.v_SEQ_MOTIVO_CANCELAMENTO.value == ""){
					alert("Preencha o campo Motivo de cancelamento");
					return false;
				}
			}

			// Se for chamado improcedênte
			//if(document.form.v_SEQ_TIPO_OCORRENCIA.value == "<?=$tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_IMPROCEDENTE?>"){
			//	if(document.form.v_TXT_HISTORICO.value == ""){
			//		alert("Preencha o campo Justificativa da Improcedência");
			//		document.form.v_TXT_HISTORICO.focus();
			//		return false;
			//	}
			//}else{
				// Selecionar todos profissionais/equipes atribuidas
				vAtribuicao = "";
				for (i = 0; i < document.form.v_ATRIBUICAO.options.length; i++){
					vAtribuicao = vAtribuicao + document.form.v_ATRIBUICAO.options[i].value;
					if(i!=document.form.v_ATRIBUICAO.options.length-1){
						vAtribuicao = vAtribuicao+"$&";
					}
				 }

				 // Encerrar a validação
				if(document.form.v_CANCELA_CHAMADO.checked == true){
					 if(vAtribuicao != ""){
					 	alert("Não é possível cancelar o chamado tendo equipes de segundo nível atribuídas.");
					 	return false;
					 }else{
						 if(confirm("Confirma o cancelamento do chamado? \n Esta ação não poderá ser corrigida. O cliente receberá um e-mail informando sobre o cancelamento de sua solicitação.")==false){
							return false;
						 }
					 }
				}else{
					 if(vAtribuicao == ""){
					 	//alert("Atribua o chamado a pelo menos uma equipe");
					 	if(confirm("Confirma o encerramento do chamado em 1º nível, sem o encaminhamento para uma equipe de atendimento de 2º nível? \n Esta ação não poderá ser corrigida. ")==false){
							return false;
						}
					 }else{
					 	if(confirm("Confirma o encaminhamento do chamado para o 2º nível? \n Esta ação não poderá ser corrigida. ")==false){
							return false;
						}else{
					 		document.form.v_LISTA_ATRIBUICAO.value=vAtribuicao;
					 	}
					 }
				 }
			//}
			document.form.flagSair.value = "1";
			return true;
		}

		// =======================================================================
		// Configuração das TABS
		// =======================================================================
		function fMostra(id, idTab){
			validarSaida = false;

			document.getElementById("tabela1nivel").style.display = "none";
			document.getElementById("tab1nivel").attributes["class"].value = "";

			document.getElementById("tabelaTriagem").style.display = "none";
			document.getElementById("tabTriagem").attributes["class"].value = "";

			document.getElementById("tabelaSLA").style.display = "none";
			document.getElementById("tabSLA").attributes["class"].value = "";

			document.getElementById("tabelaMeusDados").style.display = "none";
			document.getElementById("tabMeusDados").attributes["class"].value = "";

			document.getElementById("tabelaHistorico").style.display = "none";
			document.getElementById("tabHistorico").attributes["class"].value = "";

			<? if($pagina->flg_usar_funcionalidades_patrimonio == "S"){ ?>
                            document.getElementById("tabelaPatrimonio").style.display = "none";
                            document.getElementById("tabPatrimonio").attributes["class"].value = "";
                        <? } ?>

			document.getElementById("tabelaAnexos").style.display = "none";
			document.getElementById("tabAnexos").attributes["class"].value = ""; 
			
			if(document.getElementById("tabelaAprovadores")!=null){
				document.getElementById("tabelaAprovadores").style.display = "none";
				document.getElementById("tabAprovadores").attributes["class"].value = "";
			}
			

			document.getElementById(id).style.display = "block";
			document.getElementById(idTab).attributes["class"].value = "tabact";
	
			if("tabelaTriagem" == id){	
				do_CarregarComboProfissional();
			}
			validarSaida = true;
		}

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
			if(validarSaida) {
				// default warning message
				var msg = "Tem certeza que deseja sair antes finalizar a triagem?";

				// set event
				if (!e) { e = window.event; }
				if (e) { e.returnValue = msg; }
				do_FinalizarTarefa();
				// return warning message
				return msg;
			}
		}

		function AcionaCancelamento(){
			if(document.form.v_CANCELA_CHAMADO.checked == true){
				document.form.flag.value = "3";
				document.getElementById("motivo_cancelamento").style.display = "block";
				document.getElementById('label_descricao').innerHTML = "Descrição do motivo de cancelamento:";
			}else{
				document.form.flag.value = "1";
				document.getElementById("motivo_cancelamento").style.display = "none";
				document.getElementById('label_descricao').innerHTML = "Detalhemento do atendimento 1º nível:";
			}
		}

		// Initialise
		addEvent(window, 'load', addListeners, false);
		// <input type="button" class="button" value="Save" onclick="removeEvent(window, 'beforeunload', exitAlert, false); location.href='../list/overview.asp'" />

	</script>
	<style>
		#combo_multiple {
			font-family: Verdana;
			width: 776px;
			size: 3;
			font-size: 10px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#combo_equipe {
			width: 270px;
			font-family: Verdana;
			font-size: 11px;
			color: #000000;
			border-color: #F0F0F0;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#combo_equipe_atribuicao {
			width: 345px;
			font-family: Verdana;
			font-size: 11px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#combo_profissional {
			width: 344px;
			font-family: Verdana;
			font-size: 11px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#CampoSelect {
			font-family: Verdana;
			width: 480px;
			font-size: 10px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}

	</style>
<?
	// Imprimir erro de preenchimento de campos
	if($vErroCampos != ""){
		$pagina->ScriptAlert($vErroCampos);
	}

	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais ".$pagina->BotaoImprimir("ChamadoAtendimentoPesquisaImprimirPDF.php?imprimir[]=".$banco->SEQ_CHAMADO,"TARGET=XXX" ), 2);

	$pagina->LinhaCampoFormulario("Número:", "right", "N", $banco->SEQ_CHAMADO, "left", "id=".$pagina->GetIdTable(),"23%","");

	// Montar a combo da tabela tipo_chamado
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").":", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_OCORRENCIA", "S", "Classe", "S", $tipo_ocorrencia->combo(1, $banco->SEQ_TIPO_OCORRENCIA), "Escolha", "VerificaImprocedente(); do_CarregarComboTipoChamado();", "CampoSelect"), "left", "id=".$pagina->GetIdTable(), "20%");

	// Montar a combo da tabela subtipo_chamado
	require_once 'include/PHP/class/class.subtipo_chamado.php';
	$subtipo_chamado = new subtipo_chamado();
	$subtipo_chamado->select($banco->SEQ_SUBTIPO_CHAMADO);
	$subtipo_chamado->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	

	$tipo_chamado = new tipo_chamado();
	$tipo_chamado->setSEQ_TIPO_OCORRENCIA($banco->SEQ_TIPO_OCORRENCIA);
	$tipo_chamado->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	// Montar a combo da tabela tipo_chamado
//	$tipo_chamado->setFLG_ATENDIMENTO_EXTERNO("S");
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_CHAMADO", "S", "Classe de Chamado", "S", $tipo_chamado->combo(2, $subtipo_chamado->SEQ_TIPO_CHAMADO), "Escolha", "do_CarregarComboSubtipoChamado()", "CampoSelect"), "left", "id=".$pagina->GetIdTable(), "20%");

	// Montar a combo da tabela subtipo_chamado
	$v_SEQ_TIPO_CHAMADO = $subtipo_chamado->SEQ_TIPO_CHAMADO;
	$subtipo_chamado = new subtipo_chamado();
	$subtipo_chamado->setSEQ_TIPO_OCORRENCIA($banco->SEQ_TIPO_OCORRENCIA);
	$subtipo_chamado->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$subtipo_chamado->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").":", "right", "S", $pagina->CampoSelect("v_SEQ_SUBTIPO_CHAMADO", "S", "Subclasse de Chamado", "N", $subtipo_chamado->combo("DSC_SUBTIPO_CHAMADO", $banco->SEQ_SUBTIPO_CHAMADO), "Escolha", "do_CarregarComboAtividade()", "CampoSelect"), "left", "id=".$pagina->GetIdTable());

	// Montar a combo da tabela atividade
	require_once 'include/PHP/class/class.atividade_chamado.php';
	$atividade_chamado = new atividade_chamado();
	$atividade_chamado->setSEQ_TIPO_OCORRENCIA($banco->SEQ_TIPO_OCORRENCIA);
	$atividade_chamado->setSEQ_SUBTIPO_CHAMADO($banco->SEQ_SUBTIPO_CHAMADO);
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").":", "right", "S",
	$pagina->CampoSelect("v_SEQ_ATIVIDADE_CHAMADO", "S", "Atividade", "N", $atividade_chamado->combo("DSC_ATIVIDADE_CHAMADO", $banco->SEQ_ATIVIDADE_CHAMADO), "Escolha", "do_BuscarAtribuicaoAutomatica()", "CampoSelect"), "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Informações sobre a ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO")." selecionada:", "right", "N","<span id=\"info_atividade\">Selecione a atividade</span>", "left", "id=".$pagina->GetIdTable());

	// Montar a combo da tabela prioridade
	require_once 'include/PHP/class/class.prioridade_chamado.php';
	$prioridade_chamado = new prioridade_chamado();
	$pagina->LinhaCampoFormulario("Prioridade:", "right", "S", $pagina->CampoSelect("v_SEQ_PRIORIDADE_CHAMADO", "S", "Prioridade", "N", $prioridade_chamado->combo("DSC_PRIORIDADE_CHAMADO", $banco->SEQ_PRIORIDADE_CHAMADO)), "left", "id=".$pagina->GetIdTable());

	$situacao_chamado->select($banco->SEQ_SITUACAO_CHAMADO);
	$pagina->LinhaCampoFormulario("Situação:", "right", "N", $situacao_chamado->DSC_SITUACAO_CHAMADO, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Solicitação:", "right", "N", $banco->TXT_CHAMADO, "left", "id=".$pagina->GetIdTable());
	
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
		
		$pagina->LinhaCampoFormulario("Período de Utilização do aparelho:", "right", "N", 
		date("d/m/Y",$DT_INICIO_UTILIZACAO_APARELHO) ." à ".date("d/m/Y",$DT_FIM_UTILIZACAO_APARELHO)
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
		date("d/m/Y H:i:s",$DTH_RESERVA_EVENTO)  		, "left", "id=".$pagina->GetIdTable());
		
		$pagina->LinhaCampoFormulario("Quantidade de Pessoas:", "right", "N", $banco->QUANTIDADE_PESSOAS_EVENTO, "left", "id=".$pagina->GetIdTable());
		$pagina->LinhaCampoFormulario("Serviços:", "right", "N", $banco->SERVICOS_EVENTO, "left", "id=".$pagina->GetIdTable());
		
	}
	

	// Montar a combo de Sistemas de Informação
	if($banco->SEQ_ITEM_CONFIGURACAO == ""){
		$aItemOption = Array();
		$aItemOption[] = array("", "", "Selecione o Sistema de Informação");
		$pagina->LinhaCampoFormulario("Sistema de Informação:", "right", "S", $pagina->CampoSelect("v_SEQ_ITEM_CONFIGURACAO", "S", "Sistema de Informação", "N", $aItemOption), "left", " style=\"display: none\" id=\"combo_sistema\" class=".$pagina->GetIdTable());
	}else{
		$item_configuracao = new item_configuracao();
		$pagina->LinhaCampoFormulario("Sistema de Informação:", "right", "S", $pagina->CampoSelect("v_SEQ_ITEM_CONFIGURACAO", "S", "Sistema de Informação", "N", $item_configuracao->combo("SIG_ITEM_CONFIGURACAO", $banco->SEQ_ITEM_CONFIGURACAO)), "left", " id=\"combo_sistema\" class=".$pagina->GetIdTable());
	}

	// Justificativa para o chamado improcedênte
	$pagina->LinhaCampoFormulario("Justificativa de Improcedência:", "right", "S",
								  $pagina->CampoTextArea("v_TXT_HISTORICO", "S", "Justificativa de improcedência", "99", "6", "", "onkeyup=\"ContaCaracteres(3000, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">3000</span> Caracteres restantes"
								  , "left", " style=\"display: none\" id=\"justificativa_improcedencia\"");
								  
   $atividade_chamado_aux->select($banco->SEQ_ATIVIDADE_CHAMADO);
   $subtipo_chamado_aux->select($atividade_chamado_aux->SEQ_SUBTIPO_CHAMADO);
   
   if($atividade_chamado_aux->SEQ_TIPO_OCORRENCIA == $pagina->SEQ_TIPO_OCORRENCIA_SOLICITACAO && $subtipo_chamado_aux->SEQ_TIPO_CHAMADO ==$pagina->SEQ_CLASSE_CHAMADO_TRANSPORTE ){
   		$pagina->LinhaCampoFormulario("Requisição de Transporte:", "right", "N", 
   		"<a  title=\"Imprimir Requisição de Transporte\" target=\"XXX\" href=\"RelatorioRequisicaoTransporteParaServicoPopup.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO."\">Clique aqui para imprimir</a>"
   		, "left", "id=".$pagina->GetIdTable());
   }
   
    
		
	if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){
	   	 
	   	// APROVADOR
	  	require_once '../gestaoti/include/PHP/class/class.aprovacao_chamado_superior.php';				
		$aprovacao_chamado_superior = new aprovacao_chamado_superior(); 
		$aprovacao_chamado_superior->selectByIdChamado($banco->SEQ_CHAMADO);
		
		if($aprovacao_chamado_superior->SEQ_APROVACAO_CHAMADO_SUPERIOR){
			
			require_once 'include/PHP/class/class.empregados.oracle.php';
			$empregados = new empregados();
			$empregados->select($aprovacao_chamado_superior->NUM_MATRICULA);
			
			$pagina->LinhaCampoFormulario("Responsável pela aprovação:", "right", "N", 	$empregados->NOME	, "left", "id=".$pagina->GetIdTable());
		}
	}
	
	$aItemAba = Array();
	$aItemAba[] = array("javascript: fMostra('tabela1nivel','tab1nivel')", "tabact", "&nbsp;1º nível&nbsp;", "tab1nivel", "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript: fMostra('tabelaTriagem','tabTriagem')", "", "&nbsp;2º nível&nbsp;", "tabTriagem", "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript: fMostra('tabelaSLA','tabSLA')", "", "&nbsp;SLA&nbsp;", "tabSLA", "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript: fMostra('tabelaMeusDados','tabMeusDados')", "", "&nbsp;Solicitante&nbsp;", "tabMeusDados", "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript: fMostra('tabelaHistorico','tabHistorico')", "", "&nbsp;Histórico&nbsp;", "tabHistorico", "onclick=\"validarSaida=false;\"");
	
        if($pagina->flg_usar_funcionalidades_patrimonio == "S"){ 
            $aItemAba[] = array("javascript: fMostra('tabelaPatrimonio','tabPatrimonio')", "", "&nbsp;Patrimônio(s)&nbsp;", "tabPatrimonio", "onclick=\"validarSaida=false;\"");
        }
        $aItemAba[] = array("javascript: fMostra('tabelaAnexos','tabAnexos')", "", "&nbsp;Anexo(s)&nbsp;", "tabAnexos", "onclick=\"validarSaida=false;\"");
 			     
 	if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){	     
 		$aItemAba[] = array("javascript: fMostra('tabelaAprovadores','tabAprovadores')", "", "&nbsp;Quem pode Aprovar&nbsp;", "tabAprovadores", "onclick=\"validarSaida=false;\"");
 	}
	$pagina->SetaItemAba($aItemAba);
	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Realizar atendimento no primeiro nível
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabela1nivel cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Detalhes do atendimento de 1º nível", 2);

	// Texto de resolução do chamado
	$pagina->LinhaCampoFormulario("Lembrete:", "right", "N",
								  "<br>Para encaminhar o chamado para o 2º nível é necessário indicar a equipe na aba \"2º nível\".
								   <br>Para solucionar o chamado no 1º nível, basta preencher o campo abaixo e salvar.
								   <br>Para cancelar o chamado, acione o checkbox abaixo e indique os respectivos motivos
								   <br><br> "
								  , "left", "", "20%");

	$pagina->LinhaCampoFormulario($pagina->CampoCheckboxSimples("v_CANCELA_CHAMADO", "S", "onclick=\"AcionaCancelamento()\"", ""), "right", "N", "Cancelar chamado" , "left", "");


	$pagina->LinhaCampoFormulario("<span id=label_descricao>Detalhemento do atendimento 1º nível:</span>", "right", "S",
								  $pagina->CampoTextArea("v_TXT_RESOLUCAO", "S", "Resolução do chamado", "99", "3", "", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_resolucao'))\"").
								  "<br><span id=\"conta_resolucao\">500</span> Caracteres restantes"
								  , "left", "");

	// Montar a combo da tabela motivo_cancelamento
	require_once '../gestaoti/include/PHP/class/class.motivo_cancelamento.php';
	$motivo_cancelamento = new motivo_cancelamento();
	$pagina->LinhaCampoFormulario("Motivo de Cancelamento:", "right", "S", $pagina->CampoSelect("v_SEQ_MOTIVO_CANCELAMENTO", "S", "Motivo de cancelamento", "S", $motivo_cancelamento->combo("DSC_MOTIVO_CANCELAMENTO"), "Escolha"), "left", " style=\"display: none\" id=\"motivo_cancelamento\"", "20%");


	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Encaminhar para o segundo nível
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaTriagem style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Encaminhar chamado ao atendimento de 2º nível", 2);

	$tabela = array();
	$header = array();
	// Data de Abertura

	// Cabeçalho
	$header = array();
	$header[] = array("Equipe", "center", "50%", "header");
	$header[] = array("Atividades a serem desempenhadas", "left", "50%", "header");
	$header[] = array("&nbsp;", "left", "", "header");
	$tabela[] = $header;

	// Montar a combo da tabela tipo_chamado
	//require 'include/PHP/class/class.dependencias.php';
	//$dependencias = new dependencias();

	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$equipe_ti->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO ;
	
	//$equipe_ti->setCOD_DEPENDENCIA($_SESSION["COD_DEPENDENCIA"]);
	//$equipe_ti->setCOD_DEPENDENCIA();

	$aItemOptionEquipeAtribuicao = Array();
	$aItemOptionEquipeAtribuicao[] = array("", "", "Atribuição da equipe designada - Selecione a equipe");

	$aItemOptionProfissional = Array();
	$aItemOptionProfissional[] = array("", "", "Profissionais - Selecione a sua equipe");

	// Campos
	$header = array();
	$header[] = array(
	//			$pagina->CampoSelect("v_COD_DEPENDENCIA", "N", "Dependencia", "S", $dependencias->comboSimplesEquipe("DEP_SIGLA", $_SESSION["COD_DEPENDENCIA"]), "Escolha", "do_CarregarComboEquipe()")."&nbsp;".
				"Equipe:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$pagina->CampoSelect("v_SEQ_EQUIPE_TI", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Escolha", "do_CarregarComboProfissional();", "combo_profissional")
				."<br>".
				"Profissional:".$pagina->CampoSelect("v_NUM_MATRICULA_RECURSO", "N", "Profissional", "N", $aItemOptionProfissional, "Escolha", "", "combo_profissional")
					  , "center", "", "");
	$header[] = array(
		//$pagina->CampoSelect("v_SEQ_EQUIPE_ATRIBUICAO", "N", "Atribuicao da equipe", "N", $aItemOptionEquipeAtribuicao, "Escolha", "", "combo_equipe_atribuicao")."<br>".
		$pagina->CampoTextArea("v_TXT_ATIVIDADE", "N", "center", 54, 1, "", "onkeyup=\"ContaCaracteres(900, this, document.getElementById('conta_caracteres_atividade'))\"").
								  "<br><span id=\"conta_caracteres_atividade\">900</span> Caracteres restantes"

		, "left", "", "");
	$header[] = array($pagina->CampoButton("AtribuirEquipeProfissional()", "Adicionar", "button"), "left", "", "");
	$tabela[] = $header;
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
	$pagina->LinhaCampoFormularioColspan("center", "<hr>", 2);

	// Combo
	print $pagina->CampoHidden("v_LISTA_ATRIBUICAO", "");
	$pagina->LinhaCampoFormularioColspan("center",
    "<span id=\"mensagem_start\">
	  	Nenhuma equipe ou profissional atribuído.<br>Selecione uma equipe ou um profissional, especifique suas atividades e clique sobre o botão adicionar.
	  </span>
	  <span id=\"comboAtribuicao\" style=\"display: none\">
			<select id=\"combo_multiple\" name=\"v_ATRIBUICAO\" multiple></select>
			<div align=right>".$pagina->CampoButton("ExcluirAtribuicao()", "Excluir atribuições selecionadas", "button")."</div>
			<select id=\"combo_multiple\" name=\"v_ATRIBUICAO_VALIDACAO\"  style=\"display: none\" multiple></select>
	  </span>
	"
	, 2);

	$pagina->LinhaCampoFormularioColspan("center", "", 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// SLA
	//================================================================================================================================
	require_once 'include/PHP/class/dateObj.class.php';
	$myDate = new dateObj();

	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaSLA  style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Gestão de Nível de Serviço", 2);
	$tabela = array();
	$header = array();
	// Data de Abertura
	$header = array();
	$header[] = array("Abertura:", "center", "23%", "label");
	$header[] = array($banco->DTH_ABERTURA, "left", "", "campo");
	$tabela[] = $header;

	// Previsão de encerramento
	if($banco->DTH_ENCERRAMENTO_PREVISAO != ""){
		$header = array();
		$header[] = array("Previsão de encerramento:", "center", "", "label");
		$header[] = array(str_replace("-","/",$banco->DTH_ENCERRAMENTO_PREVISAO), "left", "", "campo");
		$tabela[] = $header;
	}

	// Encerramento efetivo
	print $pagina->CampoHidden("v_DTH_ENCERRAMENTO_PREVISAO", $banco->DTH_ENCERRAMENTO_PREVISAO);
	if($banco->DTH_ENCERRAMENTO_PREVISAO != ""){
		$header = array();
		$header[] = array("Encerramento Efetivo:", "center", "", "label");
		if($banco->DTH_ENCERRAMENTO_EFETIVO != ""){
			$vSegundosDiferencaa = $pagina->dateDiffHourPlus(str_replace("-","/",$banco->DTH_ENCERRAMENTO_EFETIVO), $banco->DTH_ENCERRAMENTO_PREVISAO);
			if($vSegundosDiferencaa < 0){ // Chamado em atraso
				$vTempoRestante = $pagina->secondsToTime($vSegundosDiferencaa);
				$header[] = array("<font color=red>".$banco->DTH_ENCERRAMENTO_EFETIVO." - Encerrado com atraso de <b>$vTempoRestante</b></font>", "left", "", "campo");
			}else{
				$header[] = array("<font color=green>".$banco->DTH_ENCERRAMENTO_EFETIVO." - Encerrado dentro do prazo</font>", "left", "", "campo");
			}
		}else{
			$vTempoRestante = $pagina->FormatarData($myDate->diff($banco->DTH_ENCERRAMENTO_PREVISAO, 'all'));
			if($pagina->dateDiffHour($banco->DTH_ENCERRAMENTO_PREVISAO) < 0){ // Chamado em atraso
				$header[] = array("<font color=red>Chamado não encerrado - Em atraso de <b>$vTempoRestante</b></font>", "left", "", "campo");
			}else{
				$header[] = array("<font color=green>Chamado não encerrado - O tempo estimado para o encerramento é de <b>$vTempoRestante</b></font>", "left", "", "campo");
			}
		}
		$tabela[] = $header;
	}

	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Dados do Solicitante
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaMeusDados style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações sobre o solicitante", 2);
	require_once 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();
	$empregados->select($banco->NUM_MATRICULA_SOLICITANTE);

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

	// Localização do cliente
	if($banco->SEQ_LOCALIZACAO_FISICA != ""){
		$header = array();
		require_once 'include/PHP/class/class.localizacao_fisica.php';
		$localizacao_fisica = new localizacao_fisica();
		$localizacao_fisica->select($banco->SEQ_LOCALIZACAO_FISICA);

		require_once 'include/PHP/class/class.edificacao.php';
		$edificacao = new edificacao();
		$edificacao->select($localizacao_fisica->SEQ_EDIFICACAO);

		require_once 'include/PHP/class/class.dependencias.php';
		$dependencias = new dependencias();
		$vSIG_DEPENDENCIA = $dependencias->GetSiglaDependencia($edificacao->COD_DEPENDENCIA);

		$header[] = array("Localização:", "center", "", "label");
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
	//================================================================================================================================
	// Histórico
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaHistorico style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Histórico do Chamado", 2);

	require_once 'include/PHP/class/class.historico_chamado.php';
	$historico_chamado = new historico_chamado();

	$historico_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$historico_chamado->selectParam("DTH_HISTORICO");
	if($historico_chamado->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
	}else{
		$tabela = array();
		$header = array();
		$header[] = array("Data", "center", "17%", "header");
		$header[] = array("Situação", "center", "20%", "header");
		$header[] = array("Responsável", "center", "25%", "header");
		$header[] = array("Observação", "center", "", "header");
		$tabela[] = $header;

		require_once 'include/PHP/class/class.recurso_ti.php';
		$recurso_ti = new recurso_ti();

		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();

		while ($row = pg_fetch_array($historico_chamado->database->result)){
			if($row["seq_situacao_chamado"] == $situacao_chamado->COD_Aguardando_Triagem){
				$vResponsavel = $empregados->GetNomeEmpregado($row["num_matricula"]);
			}else{
				$recurso_ti->select($row["num_matricula"]);
				if($recurso_ti->NUM_MATRICULA_RECURSO == ""){
					$vResponsavel = $empregados->GetNomeEmpregado($row["num_matricula"]);
				}else{
					$vResponsavel = $recurso_ti->NOM_EQUIPE_TI;
				}
			}

			$header = array();
			$header[] = array($row["dth_historico"], "center", "", "");
			$header[] = array($row["dsc_situacao_chamado"], "left", "", "");
			$header[] = array($vresponsavel, "left", "25%", "");
			$header[] = array($row["txt_historico"], "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
	}
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Patrimônio
	//================================================================================================================================
        if($pagina->flg_usar_funcionalidades_patrimonio == "S"){
            $pagina->AbreTabelaPadrao("center", "100%", "id=tabelaPatrimonio style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
            $pagina->LinhaCampoFormularioColspanDestaque("Itens do patrimônio da empresa envolvidos com o chamado ", 2);

            require_once 'include/PHP/class/class.patrimonio_chamado.php';
            $patrimonio_chamado = new patrimonio_chamado();
            $patrimonio_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
            $patrimonio_chamado->selectParam("NUM_PATRIMONIO");
            if($patrimonio_chamado->database->rows == 0){
                    $pagina->LinhaCampoFormularioColspan("left", "Nenhum patrimônio informado.", 2);
            }else{
                    $tabela = array();
                    $header = array();
                    $header[] = array("Número", "center", "10%", "header");
                    $header[] = array("Descrição", "center", "40%", "header");
    //		$header[] = array("Detentor", "center", "25%", "header");
                    $header[] = array("Localização", "center", "", "header");
                    $tabela[] = $header;

                    require_once 'include/PHP/class/class.patrimonio_ti.ativos.php';
                    $ativos = new ativos();

                    while ($row = pg_fetch_array($patrimonio_chamado->database->result)){
                            $ativos->select($row["num_patrimonio"]);
                            $header = array();
                            $header[] = array($row["num_patrimonio"], "center", "", "");
                            $header[] = array($ativos->NOM_BEM, "left", "", "");
    //			$header[] = array($ativos->NOM_DETENTOR, "left", "", "");
                            $header[] = array($ativos->DSC_LOCALIZACAO, "left", "", "");
                            $tabela[] = $header;
                    }
                    $pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
            }
            $pagina->FechaTabelaPadrao();
        } 
	//================================================================================================================================
	// Anexo
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAnexos style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Arquivos anexados ao chamado", 2);

	require_once 'include/PHP/class/class.anexo_chamado.php';
	$anexo_chamado = new anexo_chamado();
	$anexo_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$anexo_chamado->selectParam("NOM_ARQUIVO_ORIGINAL");
	if($anexo_chamado->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum arquivo anexado.", 2);
	}else{
		$tabela = array();
		$header = array();
		$header[] = array("Arquivo", "left", "45%", "header");
		$header[] = array("Data", "center", "17%", "header");
		$header[] = array("Responsável", "center", "", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($anexo_chamado->database->result)){
			$header = array();
			$header[] = array("<a target=\"_blank\" href=\"../cau/anexos/".$row["nom_arquivo_sistema"]."\">".$row["nom_arquivo_original"]."</a>", "left", "", "");
			$header[] = array($row["dth_anexo"], "left", "", "");
			$header[] = array($row["nom_colaborador"], "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
	}

	$pagina->FechaTabelaPadrao();
	
	//================================================================================================================================
	// QUEM PODE APROVAR
	//================================================================================================================================
	if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){
		$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAprovadores style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		$pagina->LinhaCampoFormularioColspanDestaque("Quem pode Aprovar", 2);
	
		require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
	   	$empregados = new empregados(1); 
	   	$empregados->select($banco->NUM_MATRICULA_SOLICITANTE); 
		
	   	if($empregados->COOR_ID != null && $empregados->COOR_ID!= ""){ 
			$empregados->SelectAprvadoresByCoordenacao($empregados->COOR_ID);
		}else if($empregados->UOR_ID != null && $empregados->UOR_ID != ""){
			
			$UOR   = $empregados->UOR_ID; 
			
			if($empregados->UOR_ID == $pagina->COD_UNIDADE_PRESIDENCIA){
				$UOR   .=",".$pagina->COD_UNIDADE_GABINETE_PRESIDENCIA;
			}
			
			$empregados->SelectAprvadoresByUnidade($UOR);
			//$empregados->SelectAprvadoresByUnidade($empregados->UOR_ID);
		}
		
		if($empregados->database->rows == 0){
			$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
		}else{
			$tabela = array();
			$header = array();
			$header[] = array("Nome", "center", "30%", "header");
			$header[] = array("E-mail", "center", "30%", "header");
			$header[] = array("Função Administrativa", "center", "30%", "header");
			 
			$tabela[] = $header;  
			 
	
			while ($row = pg_fetch_array($empregados->database->result)){				 
				$header = array();
				$header[] = array($row["nome"], "left", "", "");
				$header[] = array($row["des_email"], "left", "", ""); 
				$header[] = array($row["dsfuncaoadministrativa"], "left", "", "");
				$tabela[] = $header;
			}
			$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
		}
		$pagina->FechaTabelaPadrao();
	}

	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspan("center",
				"<hr>".
				$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormLocal(); ", " Salvar Atendimento ")."&nbsp;".
				$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fCancelarTriagem();",  "       Voltar       ", "button")
				, "2");
	$pagina->FechaTabelaPadrao();
	
	
	?>
	<script>
		do_BuscarAtribuicaoAutomatica();
	</script>
	<?

	$pagina->MontaRodape();
}else{
	$pagina->redirectTo("ChamadoTriagemPesquisa.php");
}

?>