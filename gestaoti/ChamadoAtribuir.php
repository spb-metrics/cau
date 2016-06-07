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
require_once 'include/PHP/class/class.chamado.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

function ValidarAtribuicao($v_SEQ_CHAMADO, $v_SEQ_EQUIPE_TI, $v_NUM_MATRICULA){
	// Verificar se a atribui��o j� foi realizada
	require_once 'include/PHP/class/class.atribuicao_chamado.php';
	$atribuicao_chamado = new atribuicao_chamado();
	$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$atribuicao_chamado->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$atribuicao_chamado->setNUM_MATRICULA($v_NUM_MATRICULA);
	$atribuicao_chamado->ValidarAtribui��o();
	if($atribuicao_chamado->SEQ_ATRIBUICAO_CHAMADO == ""){
		return 0;
	}else{
		return 1;
	}
}

if($v_SEQ_CHAMADO != ""){
	// ============================================================================================================
	// Realizar o cadastro do chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$camposValidados = 1;
		$mensagemErro = "";
		if($v_LISTA_ATRIBUICAO == ""){
		 	$vErroCampos .= "Atribua o chamado a pelo menos uma equipe";
		}

		if($vErroCampos == ""){
			// Inserir atribui��es
			if($v_LISTA_ATRIBUICAO != ""){
				require_once 'include/PHP/class/class.atribuicao_chamado.php';
				require_once 'include/PHP/class/class.equipe_ti.php';
				require_once 'include/PHP/class/class.situacao_chamado.php';
				$situacao_chamado = new situacao_chamado();
				$equipe_ti = new equipe_ti();
				$aLISTA_ATRIBUICAO = explode("$&", $v_LISTA_ATRIBUICAO);
				//print "<br>COUNT = ".count($aLISTA_ATRIBUICAO);
				for($i=0; $i<count($aLISTA_ATRIBUICAO);$i++){
					//print "<br>$i - ".$aLISTA_ATRIBUICAO[$i];
					$aValores = explode("|", $aLISTA_ATRIBUICAO[$i]);
					//print $aValores;
					$v_SEQ_EQUIPE_TI         = $aValores[0];
					$v_NUM_MATRICULA         = $aValores[1];
					$v_TXT_ATIVIDADE         = $aValores[2];
					$v_SEQ_EQUIPE_ATRIBUICAO = $aValores[3];

					// Validar atribi��o para garantir integridade
					if(ValidarAtribuicao($v_SEQ_CHAMADO, $v_SEQ_EQUIPE_TI, $v_NUM_MATRICULA) == 0){
						//print "<br>$i - PASSOU<br>";
						// Pegar o nome da equipe
						$equipe_ti->select($v_SEQ_EQUIPE_TI);
						$v_TXT_HISTORICO .= $equipe_ti->NOM_EQUIPE_TI.",";

						$atribuicao_chamado = new atribuicao_chamado();

						/*
						$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
						$atribuicao_chamado->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
						$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Atendimento);
						$atribuicao_chamado->setNUM_MATRICULA($v_NUM_MATRICULA);
						$atribuicao_chamado->setSEQ_EQUIPE_ATRIBUICAO($v_SEQ_EQUIPE_ATRIBUICAO);
						$atribuicao_chamado->setTXT_ATIVIDADE($v_TXT_ATIVIDADE);
						$atribuicao_chamado->insert();
						*/
						$atribuicao_chamado->TXT_ATIVIDADE = $v_TXT_ATIVIDADE;
						$atribuicao_chamado->AtualizarAtribuicao($v_SEQ_CHAMADO, $v_SEQ_EQUIPE_TI, $v_NUM_MATRICULA, $situacao_chamado->COD_Aguardando_Atendimento, "", 1);

						// Identificar se o servidor de e-mail est� ativo
						require_once 'include/PHP/class/class.phpmailer.php';
						$mail = new PHPMailer();
						if($mail->smtpPing == 0){
							// Enviar e-mail para o profissional atribu�do
							if($v_NUM_MATRICULA != ""){
								$mail->From     = $pagina->EmailRemetente;
								$mail->FromName = $pagina->remetenteEmailCEA;
								$mail->Sender   = $pagina->EmailRemetente;
								$mail->Subject  = "CAU - Notifica��o de Atribui��o Chamado";
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
										   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;acaba de ser aberto, no CAU, o chamado n� ".$v_SEQ_CHAMADO.", atribu�do voc�. Seguem abaixo os dados do chamado.
										   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
										   <br>&nbsp;&nbsp;- Solicita��o: <b>".$chamadoEmail->TXT_CHAMADO."</b>
										   <br>&nbsp;&nbsp;- Cliente: <b>".$chamadoEmail->NOM_CLIENTE."</b>
										   <br>
										   <br>Para maiores informa��es acesse o CAU na sua �rea de atendimento.
										   <br>Esta e uma mensagem autom&aacute;tica, favor n&atilde;o responder.
										   <br>---
										   <br>CAU - Central de Atendimento ao Usu�rio
										   <br>".$pagina->enderecoGestaoTI."
										   </div>
										   </body>
										   </html>";

								// Buscar contato do l�der e do substituto
								require_once 'include/PHP/class/class.equipe_ti.php';
								$equipe_ti = new equipe_ti();
								$equipe_ti->EmailLiderSubstituto($v_SEQ_EQUIPE_TI);
								// Ao l�der
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
							}else{ // Enviar e-mail para o l�der da equipe
								$mail->From     = $pagina->EmailRemetente;
								$mail->FromName = $pagina->remetenteEmailCEA;
								$mail->Sender   = $pagina->EmailRemetente;
								$mail->Subject  = "CAU - Notifica��o de Atribui��o Chamado";
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
										   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;acaba de ser aberto, no CAU, o chamado n� ".$v_SEQ_CHAMADO.", atribu�do para a sua equipe. Seguem abaixo os dados do chamado.
										   <br>
										   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                                                                   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
										   <br>&nbsp;&nbsp;- Solicita��o: <b>".$chamadoEmail->TXT_CHAMADO."</b>
										   <br>&nbsp;&nbsp;- Cliente: <b>".$chamadoEmail->NOM_CLIENTE."</b>
										   <br>
										   <br>Para maiores informa��es acesse o Gest�o TI na sua �rea de atendimento.
										   <br>Esta e uma mensagem autom&aacute;tica, favor n&atilde;o responder.
										   <br>---
										   <br>CAU - Central de Atendimento ao Usu�rio
										   <br>".$pagina->enderecoGestaoTI."
										   </div>
										   </body>
										   </html>";

								// Buscar contatos do l�der e do substituto
								require_once 'include/PHP/class/class.equipe_ti.php';
								$equipe_ti = new equipe_ti();
								$equipe_ti->EmailLiderSubstituto($v_SEQ_EQUIPE_TI);
								// Ao l�der
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
						}else{ // N�o pingou - e-mail n�o ser� enviado
							// Emitir alerta ao usu�rio
							//$pagina->ScriptAlert("Servidor de e-mail fora do ar. A atribui��o foi realizada mas o e-mail de confirma��o n�o pode ser enviado.");
						}
					}
				}
			}

			// Redirecionar para a p�gina de confirma��o
			$pagina->redirectTo("ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
			//$pagina->redirectToJS("ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
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
	// Configura��es AJAX
	// ============================================================================================================
	require_once 'include/PHP/class/class.Sajax.php';
	$Sajax = new Sajax();

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

	$Sajax->sajax_init();
	$Sajax->sajax_debug_mode = 0;
	$Sajax->sajax_export("CarregarComboEquipe", "CarregarComboEquipeAtribuicao", "CarregarComboProfissional", "ValidarAtribuicao");
	$Sajax->sajax_handle_client_request();

	// ============================================================================================================
	// Configura��o da p�g�na
	// ============================================================================================================
	$pagina->SettituloCabecalho("Atribuir Chamado a outra Equipe/Profissional"); // Indica o t�tulo do cabe�alho da p�gina
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
	$aItemAba[] = array("#", "tabact", "Atribuir", "onclick=\"AcessarAcao('ChamadoAtribuir.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
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
	print $pagina->CampoHidden("SEQ_CENTRAL_ATENDIMENTO", $_SEQ_CENTRAL_ATENDIMENTO);
	// ============================================================================================================
	// Configura��es AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		<?
		$Sajax->sajax_show_javascript();
		?>
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
		function AtribuirEquipeProfissional(){
			/*
			if(document.form.v_COD_DEPENDENCIA.value == ""){
				alert("Selecione a depend�ncia da equipe de TI");
				document.form.v_COD_DEPENDENCIA.focus();
				return;
			}
			*/
			if(document.form.v_SEQ_EQUIPE_TI.value == ""){
				alert("Selecione a equipe de TI");
				document.form.v_SEQ_EQUIPE_TI.focus();
				return;
			}
			/*
			if(document.form.v_SEQ_EQUIPE_ATRIBUICAO.value == ""){
				alert("Informe a atividade que dever� ser desempenhada");
				document.form.v_SEQ_EQUIPE_ATRIBUICAO.focus();
				return;
			}
			*/
			if(document.form.v_TXT_ATIVIDADE.value == ""){
				alert("Descreva observa��es sobre as atividades a serem desempenhadas");
				document.form.v_TXT_ATIVIDADE.focus();
				return;
			}

			// VErificar se o registro j� foi inclu�do
			vCodigoValidacao = document.form.v_SEQ_EQUIPE_TI.value+"|"+document.form.v_NUM_MATRICULA_RECURSO.value;
			if(!VerificarExistenciaValorCombo(document.form.v_ATRIBUICAO_VALIDACAO, vCodigoValidacao)){
				// Validar atribui��o
				x_ValidarAtribuicao(<?=$v_SEQ_CHAMADO?>, document.form.v_SEQ_EQUIPE_TI.value, document.form.v_NUM_MATRICULA_RECURSO.value, retorno_ValidarAtribuicao);
			}else{
				alert("Atribui��o j� realizada");
			}
		}
		// Retorno
		function retorno_ValidarAtribuicao(val) {
			if(val == "0"){
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
				//	vText = vText + " - Atribui��o: "+GetTextItemCombo(document.form.v_SEQ_EQUIPE_ATRIBUICAO);
				//}
				vText = vText + " - Atividades: "+document.form.v_TXT_ATIVIDADE.value;
				if(!VerificarExistenciaValorCombo(document.form.v_ATRIBUICAO_VALIDACAO, vCodigoValidacao)){
					fAdicionaValorCombo(vCodigo, vText, document.form.v_ATRIBUICAO);
					fAdicionaValorCombo(vCodigoValidacao, vText, document.form.v_ATRIBUICAO_VALIDACAO);
				}else{
					alert("Atribui��o j� realizada");
				}
			}else{
				alert("Atribui��o j� realizada ao chamado anteriormente.");
			}

		}
		// ==================================================== FIM AJAX =====================================

		// =======================================================================
		// Controlar a sa�da �s a��es do chamado
		// =======================================================================
		function AcessarAcao(vDestino){
			validarSaida = false;
			window.location.href = vDestino;
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

		function fValidaFormLocal(){
			// Selecionar todos profissionais/equipes atribuidas
			vAtribuicao = "";
			for (i = 0; i < document.form.v_ATRIBUICAO.options.length; i++){
				vAtribuicao = vAtribuicao + document.form.v_ATRIBUICAO.options[i].value;
				if(i!=document.form.v_ATRIBUICAO.options.length-1){
					vAtribuicao = vAtribuicao+"$&";
				}
			 }
			 if(vAtribuicao == ""){
			 	alert("Atribua o chamado a pelo menos uma equipe");
			 	return false;
			 }else{
			 	document.form.v_LISTA_ATRIBUICAO.value=vAtribuicao;
			}
			return true;
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
	</style>
	<?
	if($mensagemErro != ""){
		$pagina->ScriptAlert($mensagemErro);
	}

	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Equipe(s) ou Profissional(is) Respons�vel(is)", 2);

	$tabela = array();
	$header = array();
	// Data de Abertura

	// Cabe�alho
	$header = array();
	$header[] = array("Equipe", "center", "50%", "header");
	$header[] = array("Atividades a serem desempenhadas", "left", "50%", "header");
	$header[] = array("&nbsp;", "left", "", "header");
	$tabela[] = $header;

	// Montar a combo da tabela tipo_chamado
//	require 'include/PHP/class/class.dependencias.php';
//	$dependencias = new dependencias();

	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$equipe_ti->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
//	$equipe_ti->setCOD_DEPENDENCIA($_SESSION["COD_DEPENDENCIA"]);

	$aItemOptionEquipeAtribuicao = Array();
	$aItemOptionEquipeAtribuicao[] = array("", "", "Atribui��o da equipe designada - Selecione a equipe");

	$aItemOptionProfissional = Array();
	$aItemOptionProfissional[] = array("", "", "Profissionais - Selecione a sua equipe");

	// Campos
	$header = array();
	$header[] = array(
//				$pagina->CampoSelect("v_COD_DEPENDENCIA", "N", "Dependencia", "S", $dependencias->comboSimplesEquipe("DEP_SIGLA", $_SESSION["COD_DEPENDENCIA"]), "Escolha", "do_CarregarComboEquipe()")."&nbsp;".
				$pagina->CampoSelect("v_SEQ_EQUIPE_TI", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Escolha", "do_CarregarComboProfissional();", "combo_equipe").
				"&nbsp;".
				$pagina->CampoSelect("v_NUM_MATRICULA_RECURSO", "N", "Profissional", "N", $aItemOptionProfissional, "Escolha", "", "combo_profissional")
					  , "center", "", "");
	$header[] = array(
//		$pagina->CampoSelect("v_SEQ_EQUIPE_ATRIBUICAO", "N", "Atribuicao da equipe", "N", $aItemOptionEquipeAtribuicao, "Escolha", "", "combo_equipe_atribuicao")."<br>".
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
	  	Nenhuma equipe ou profissional atribu�do.<br>Selecione uma equipe ou um profissional, especifique suas atividades e clique sobre o bot�o adicionar.
	  </span>
	  <span id=\"comboAtribuicao\" style=\"display: none\">
			<select id=\"combo_multiple\" name=\"v_ATRIBUICAO\" multiple></select>
			<div align=right>".$pagina->CampoButton("ExcluirAtribuicao()", "Excluir atribui��es selecionadas", "button")."</div>
			<select id=\"combo_multiple\" name=\"v_ATRIBUICAO_VALIDACAO\"  style=\"display: none\" multiple></select>
	  </span>
	"
	, 2);
	$pagina->LinhaCampoFormularioColspan("center",
				"<hr>".
				$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormLocal(); ", " Salvar ")
				, "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	$pagina->ScriptAlert("Selecione o chamado.");
	$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
}
?>