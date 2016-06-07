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
require_once '../gestaoti/include/PHP/class/class.pagina.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

if($v_SEQ_CHAMADO != ""){
	// ============================================================================================================
	// Realizar a avalia��o do chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$mensagemErro = "";
		if($v_FLG_SOLICITACAO_ATENDIDA == ""){
		 	$vErroCampos .= "Preencha o campo Solicita��o Atendida. ";
		}else{
		 	if($v_FLG_SOLICITACAO_ATENDIDA == "N"){
		 		if($v_FLG_REABRIR_CHAMADO == "" && $flagNaoReabrirChamado != "1"){
		 			$vErroCampos .= "Preencha o campo Deseja Reabrir o Chamado? ";
		 		}
		 	}else{
				$v_FLG_REABRIR_CHAMADO = "";
			}
		}
		if($v_SEQ_AVALIACAO_ATENDIMENTO == ""){
		 	$vErroCampos .= "Preencha o campo Satisfa��o com a solu��o apresentada. ";
		}
		if($v_SEQ_AVALIACAO_CONHECIMENTO_TECNICO == ""){
		 	$vErroCampos .= "Preencha o campo Satisfa��o com o conhecimento t�cnico do prestador de servi�o. ";
		}
		if($v_SEQ_AVALIACAO_POSTURA == ""){
		 	$vErroCampos .= "Preencha o campo Satisfa��o com a postura e cordialidade do prestador de servi�o. ";
		}
		if($v_SEQ_AVALIACAO_TEMPO_ESPERA == ""){
		 	$vErroCampos .= "Preencha o campo Satisfa��o com o tempo de espera para atendimento. ";
		}
		if($v_SEQ_AVALIACAO_TEMPO_SOLUCAO == ""){
		 	$vErroCampos .= "Preencha o campo Satisfa��o com o tempo de atendimento. ";
		}

		if($v_TXT_AVALIACAO == "" && $v_FLG_SOLICITACAO_ATENDIDA == "N"){
		 	$vErroCampos .= "Preencha o campo observa��o. ";
		}

		if($vErroCampos == ""){
			require_once '../gestaoti/include/PHP/class/class.chamado.php';
			require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
			require_once '../gestaoti/include/PHP/class/class.atividade_chamado.php';
			$chamado = new chamado();
			$chamado->select($v_SEQ_CHAMADO);
			$situacao_chamado = new situacao_chamado();

			if($v_FLG_SOLICITACAO_ATENDIDA == "S"){
				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Encerrada;
			}else{
				if($v_FLG_REABRIR_CHAMADO == "S"){ // Reabrir chamado
		 			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Atendimento;
		 		}else{
				 	$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Encerrada;
				}
			}

			// Alterar situa��o do chamado
			$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $v_SEQ_SITUACAO_CHAMADO);

			// Avaliar Chamado
			$chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$chamado->setSEQ_AVALIACAO_ATENDIMENTO($v_SEQ_AVALIACAO_ATENDIMENTO);
			$chamado->setSEQ_AVALIACAO_CONHECIMENTO_TECNICO($v_SEQ_AVALIACAO_CONHECIMENTO_TECNICO);
			$chamado->setSEQ_AVALIACAO_POSTURA($v_SEQ_AVALIACAO_POSTURA);
			$chamado->setSEQ_AVALIACAO_TEMPO_ESPERA($v_SEQ_AVALIACAO_TEMPO_ESPERA);
			$chamado->setSEQ_AVALIACAO_TEMPO_SOLUCAO($v_SEQ_AVALIACAO_TEMPO_SOLUCAO);
			$chamado->setNUM_MATRICULA_AVALIADOR($_SESSION["NUM_MATRICULA_RECURSO"]);
			$chamado->setFLG_SOLICITACAO_ATENDIDA($v_FLG_SOLICITACAO_ATENDIDA);
			$chamado->setTXT_AVALIACAO($v_TXT_AVALIACAO);
			$chamado->AvaliarChamado();

			// Atualizar atribui��es - caso o chamado esteja sendo reaberto
			if($v_FLG_REABRIR_CHAMADO == "S"){
				// Atualizar a data de encerramento efetivo do chamado
				$chamado->ReabrirChamado($v_SEQ_CHAMADO);

	 			require_once '../gestaoti/include/PHP/class/class.atribuicao_chamado.php';
				$atribuicao_chamado = new atribuicao_chamado();
				$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Atendimento);
				$atribuicao_chamado->ReabrirChamado();
	 		}

			// Incluir hist�rico
			//require_once '../gestaoti/include/PHP/class/class.avaliacao_atendimento.php';
			//$avaliacao_atendimento = new avaliacao_atendimento();
			//$avaliacao_atendimento->select($v_SEQ_AVALIACAO_ATENDIMENTO);
			if($v_FLG_SOLICITACAO_ATENDIDA == "S"){
				//$vMensagemHistorico = "Chamado avaliado pelo cliente como Atendido - ".$avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO.". ";
				$vMensagemHistorico = "Chamado avaliado pelo cliente como Atendido. ";
			}else{
				//$vMensagemHistorico = "Chamado avaliado pelo cliente como N�o Atendido - ".$avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO.". ";
				$vMensagemHistorico = "Chamado avaliado pelo cliente como N�o Atendido. ";
			}
			if($v_FLG_REABRIR_CHAMADO == "S"){
				$vMensagemHistorico .= "Chamado reaberto pelo cliente. ";
			}
			if($v_TXT_AVALIACAO != ""){
				$vMensagemHistorico .= "Observa��es do cliente: ". $v_TXT_AVALIACAO;
			}


			require_once '../gestaoti/include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
			$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
			$historico_chamado->setTXT_HISTORICO($vMensagemHistorico);
			$historico_chamado->insert();

			// Redirecionar para a p�gina de avalia��o
			$pagina->ScriptAlert("Avalia��o registrada com sucesso.");
			$pagina->redirectToJS("ChamadoDetalhesCEA.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
		}
	}
	// ============================================================================================================
	// In�cio da p�gina
	// ============================================================================================================

	// ============================================================================================================
	// Configura��o da p�g�na
	// ============================================================================================================
	$pagina->SettituloCabecalho("Avaliar o atendimento do chamado"); // Indica o t�tulo do cabe�alho da p�gina
	$pagina->method = "post";

	$aItemAba = Array( array("ChamadoDetalhesCEA.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO", "", "Detalhes"),
					   array("", "tabact", "Avaliar")
					);

	$pagina->SetaItemAba($aItemAba);
	$pagina->estiloTabBar = "tabbarMenor";
	$pagina->flagScriptCalendario = 0;
	$pagina->cea = 1;
	$pagina->MontaCabecalho();

	require_once '../gestaoti/include/PHP/class/class.chamado.php';
	$banco = new chamado();

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_CHAMADO", $v_SEQ_CHAMADO);
	if($vErroCampos != ""){
		$pagina->ScriptAlert($vErroCampos);
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
			validarSaida = false;
			window.location.href = vDestino;
		}

		function fValidaFormLocal(){
			 if(document.form.v_FLG_SOLICITACAO_ATENDIDA.value == ""){
			 	alert("Preencha o campo Solicita��o Atendida");
			 	return false;
			 }else{
			 	<?
				// Implementa��o da regra que permite reabrir chamados apenas at� 5 dias �teis ap�s o fechamento pela TI
				// Buscar data da �ltima situa��o do chamado
				require_once '../gestaoti/include/PHP/class/class.historico_chamado.php';
				$historico_chamado = new historico_chamado();
				$v_DTH_HISTORICO = $historico_chamado->GetDTHUltimaSituacao($v_SEQ_CHAMADO);

				// Acrescentar X dias �teis
				$vAdd = $pagina->parametro->GetValorParametro("QTD_MIN_REABERTURA");
				$v_DTH_HISTORICO = $pagina->add_minutos_uteis($vAdd, $v_DTH_HISTORICO, $banco->HoraInicioExpediente, $banco->HoraInicioIntervalo, $banco->HoraFimIntervalo, $banco->HoraFimExpediente, $banco->aDtFeriado);

				// Verificar se a data atual � maior
				$diffAtual = $pagina->dateDiffHourPlus(date("d/m/Y H:i:s"), $v_DTH_HISTORICO);
				if($diffAtual > 0){
				 ?>
			 	if(document.form.v_FLG_SOLICITACAO_ATENDIDA.value == "N"){
			 		if(document.form.v_FLG_REABRIR_CHAMADO.value == ""){
			 			alert("Preencha o campo Deseja Reabrir o Chamado?");
			 			return false;
			 		}
			 	}
				<?
				}
				?>
			 }
			 if(document.form.v_SEQ_AVALIACAO_ATENDIMENTO.value == ""){
			 	alert("Preencha o campo Satisfa��o com a solu��o apresentada.");
			 	return false;
			 }
			 if(document.form.v_SEQ_AVALIACAO_CONHECIMENTO_TECNICO.value == ""){
			 	alert("Preencha o campo Satisfa��o com o conhecimento t�cnico do prestador de servi�o.");
			 	return false;
			 }
			 if(document.form.v_SEQ_AVALIACAO_POSTURA.value == ""){
			 	alert("Preencha o campo Satisfa��o com a postura e cordialidade do prestador de servi�o.");
			 	return false;
			 }
			 if(document.form.v_SEQ_AVALIACAO_TEMPO_ESPERA.value == ""){
			 	alert("Preencha o campo Satisfa��o com o tempo de espera para atendimento.");
			 	return false;
			 }
			 if(document.form.v_SEQ_AVALIACAO_TEMPO_SOLUCAO.value == ""){
			 	alert("Preencha o campo Satisfa��o com o tempo de atendimento.");
			 	return false;
			 }

			 if(document.form.v_TXT_AVALIACAO.value == "" && document.form.v_FLG_SOLICITACAO_ATENDIDA.value == "N"){
			 	alert("Preencha o campo observa��o");
			 	return false;
			 }
			return confirm("Confirma a avalia��o?");
		}
		/**
		 *
		 * @access public
		 * @return void
		 **/
		function fValidaSolicitacaoAtendida(){
			<?
			if($diffAtual > 0){
				?>
				if(document.form.v_FLG_SOLICITACAO_ATENDIDA.value == "N"){
					document.getElementById("reabrir_chamado").style.display = "block";
					document.getElementById("obrigatorio").style.display = "block";
					document.getElementById("nao_obrigatorio").style.display = "none";
				}else{
					document.getElementById("reabrir_chamado").style.display = "none";
					document.getElementById("obrigatorio").style.display = "none";
					document.getElementById("nao_obrigatorio").style.display = "block";
				}
				<?
			}
			?>
		}
	</script>
	<?
	if($mensagemErro != ""){
		$pagina->ScriptAlert($mensagemErro);
	}

	if($diffAtual > 0){
		print $pagina->CampoHidden("flagNaoReabrirChamado", "");
	}else{
		print $pagina->CampoHidden("flagNaoReabrirChamado", "1");
	}

	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Avalia��o sobre o atendimento recebido", 2);

	// Solicita��o Atendida?
	$aItemOption = Array();
	$aItemOption[] = array("S", "", "Sim");
	$aItemOption[] = array("N", "", "N�o");
	$pagina->LinhaCampoFormulario("Solicita��o Atendida?", "right", "S",
									  $pagina->CampoSelect("v_FLG_SOLICITACAO_ATENDIDA", "N", "Localiza��o F�sica", "S", $aItemOption, "Escolha", "fValidaSolicitacaoAtendida()")
									  , "left", "id=".$pagina->GetIdTable(), "30%");

	// Avalia��o do Atendimento
	require_once '../gestaoti/include/PHP/class/class.avaliacao_atendimento.php';
	$avaliacao_atendimento = new avaliacao_atendimento();
	$pagina->LinhaCampoFormulario("Satisfa��o com a solu��o apresentada:", "right", "S", $pagina->CampoSelect("v_SEQ_AVALIACAO_ATENDIMENTO", "S", "", "S", $avaliacao_atendimento->combo(1), "Escolha"), "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Satisfa��o com o conhecimento t�cnico do prestador de servi�o:", "right", "S", $pagina->CampoSelect("v_SEQ_AVALIACAO_CONHECIMENTO_TECNICO", "S", "", "S", $avaliacao_atendimento->combo(1), "Escolha"), "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Satisfa��o com a postura e cordialidade do prestador de servi�o:", "right", "S", $pagina->CampoSelect("v_SEQ_AVALIACAO_POSTURA", "S", "", "S", $avaliacao_atendimento->combo(1), "Escolha"), "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Satisfa��o com o tempo de espera para atendimento:", "right", "S", $pagina->CampoSelect("v_SEQ_AVALIACAO_TEMPO_ESPERA", "S", "", "S", $avaliacao_atendimento->combo(1), "Escolha"), "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Satisfa��o com o tempo de solu��o:", "right", "S", $pagina->CampoSelect("v_SEQ_AVALIACAO_TEMPO_SOLUCAO", "S", "", "S", $avaliacao_atendimento->combo(1), "Escolha"), "left", "id=".$pagina->GetIdTable());

	// Descri��o
	$pagina->LinhaCampoFormulario("<span id=\"obrigatorio\" style=\"display: none;\"><font color=red>* </font>Observa��o:</span>
								   <span id=\"nao_obrigatorio\">Observa��o:</span>
								  ", "right", "N",
									  $pagina->CampoTextArea("v_TXT_AVALIACAO", "N", "Observa��o", "99", "9", "", "onkeyup=\"ContaCaracteres(5000, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">5000</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable());

	// Solicita��o Atendida?
	$aItemOption = Array();
	$aItemOption[] = array("S", "", "Sim");
	$aItemOption[] = array("N", "", "N�o");
	$pagina->LinhaCampoFormulario("Deseja reabrir o chamado?", "right", "S",
									  $pagina->CampoSelect("v_FLG_REABRIR_CHAMADO", "N", "", "S", $aItemOption, "Escolha", "")
									  , "left", " id=\"reabrir_chamado\" style=\"display: none;\" class=".$pagina->GetIdTable(), "30%");

	$pagina->LinhaCampoFormularioColspan("center", "<hr><div align=left><font color=red>*</font> - Preenchimento obrigat�rio</div>", "2");
	$pagina->LinhaCampoFormularioColspan("center",
				$pagina->CampoButton("return fValidaFormLocal(); ", " Salvar ")
				, "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	$pagina->ScriptAlert("Selecione o chamado.");
	$pagina->redirectToJS("ChamadoAcompanhar.php");
}
?>