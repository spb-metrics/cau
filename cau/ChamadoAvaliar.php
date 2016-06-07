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
require_once '../gestaoti/include/PHP/class/class.pagina.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

if($v_SEQ_CHAMADO != ""){
	// ============================================================================================================
	// Realizar a avaliação do chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$mensagemErro = "";
		if($v_FLG_SOLICITACAO_ATENDIDA == ""){
		 	$vErroCampos .= "Preencha o campo Solicitação Atendida. ";
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
		 	$vErroCampos .= "Preencha o campo Satisfação com a solução apresentada. ";
		}
		if($v_SEQ_AVALIACAO_CONHECIMENTO_TECNICO == ""){
		 	$vErroCampos .= "Preencha o campo Satisfação com o conhecimento técnico do prestador de serviço. ";
		}
		if($v_SEQ_AVALIACAO_POSTURA == ""){
		 	$vErroCampos .= "Preencha o campo Satisfação com a postura e cordialidade do prestador de serviço. ";
		}
		if($v_SEQ_AVALIACAO_TEMPO_ESPERA == ""){
		 	$vErroCampos .= "Preencha o campo Satisfação com o tempo de espera para atendimento. ";
		}
		if($v_SEQ_AVALIACAO_TEMPO_SOLUCAO == ""){
		 	$vErroCampos .= "Preencha o campo Satisfação com o tempo de atendimento. ";
		}

		if($v_TXT_AVALIACAO == "" && $v_FLG_SOLICITACAO_ATENDIDA == "N"){
		 	$vErroCampos .= "Preencha o campo observação. ";
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

			// Alterar situação do chamado
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

			// Atualizar atribuições - caso o chamado esteja sendo reaberto
			if($v_FLG_REABRIR_CHAMADO == "S"){
				// Atualizar a data de encerramento efetivo do chamado
				$chamado->ReabrirChamado($v_SEQ_CHAMADO);

	 			require_once '../gestaoti/include/PHP/class/class.atribuicao_chamado.php';
				$atribuicao_chamado = new atribuicao_chamado();
				$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Atendimento);
				$atribuicao_chamado->ReabrirChamado();
	 		}

			// Incluir histórico
			//require_once '../gestaoti/include/PHP/class/class.avaliacao_atendimento.php';
			//$avaliacao_atendimento = new avaliacao_atendimento();
			//$avaliacao_atendimento->select($v_SEQ_AVALIACAO_ATENDIMENTO);
			if($v_FLG_SOLICITACAO_ATENDIDA == "S"){
				//$vMensagemHistorico = "Chamado avaliado pelo cliente como Atendido - ".$avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO.". ";
				$vMensagemHistorico = "Chamado avaliado pelo cliente como Atendido. ";
			}else{
				//$vMensagemHistorico = "Chamado avaliado pelo cliente como Não Atendido - ".$avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO.". ";
				$vMensagemHistorico = "Chamado avaliado pelo cliente como Não Atendido. ";
			}
			if($v_FLG_REABRIR_CHAMADO == "S"){
				$vMensagemHistorico .= "Chamado reaberto pelo cliente. ";
			}
			if($v_TXT_AVALIACAO != ""){
				$vMensagemHistorico .= "Observações do cliente: ". $v_TXT_AVALIACAO;
			}


			require_once '../gestaoti/include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
			$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
			$historico_chamado->setTXT_HISTORICO($vMensagemHistorico);
			$historico_chamado->insert();

			// Redirecionar para a página de avaliação
			$pagina->ScriptAlert("Avaliação registrada com sucesso.");
			$pagina->redirectToJS("ChamadoDetalhesCEA.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
		}
	}
	// ============================================================================================================
	// Início da página
	// ============================================================================================================

	// ============================================================================================================
	// Configuração da págína
	// ============================================================================================================
	$pagina->SettituloCabecalho("Avaliar o atendimento do chamado"); // Indica o título do cabeçalho da página
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
			 if(document.form.v_FLG_SOLICITACAO_ATENDIDA.value == ""){
			 	alert("Preencha o campo Solicitação Atendida");
			 	return false;
			 }else{
			 	<?
				// Implementação da regra que permite reabrir chamados apenas até 5 dias úteis após o fechamento pela TI
				// Buscar data da última situação do chamado
				require_once '../gestaoti/include/PHP/class/class.historico_chamado.php';
				$historico_chamado = new historico_chamado();
				$v_DTH_HISTORICO = $historico_chamado->GetDTHUltimaSituacao($v_SEQ_CHAMADO);

				// Acrescentar X dias úteis
				$vAdd = $pagina->parametro->GetValorParametro("QTD_MIN_REABERTURA");
				$v_DTH_HISTORICO = $pagina->add_minutos_uteis($vAdd, $v_DTH_HISTORICO, $banco->HoraInicioExpediente, $banco->HoraInicioIntervalo, $banco->HoraFimIntervalo, $banco->HoraFimExpediente, $banco->aDtFeriado);

				// Verificar se a data atual é maior
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
			 	alert("Preencha o campo Satisfação com a solução apresentada.");
			 	return false;
			 }
			 if(document.form.v_SEQ_AVALIACAO_CONHECIMENTO_TECNICO.value == ""){
			 	alert("Preencha o campo Satisfação com o conhecimento técnico do prestador de serviço.");
			 	return false;
			 }
			 if(document.form.v_SEQ_AVALIACAO_POSTURA.value == ""){
			 	alert("Preencha o campo Satisfação com a postura e cordialidade do prestador de serviço.");
			 	return false;
			 }
			 if(document.form.v_SEQ_AVALIACAO_TEMPO_ESPERA.value == ""){
			 	alert("Preencha o campo Satisfação com o tempo de espera para atendimento.");
			 	return false;
			 }
			 if(document.form.v_SEQ_AVALIACAO_TEMPO_SOLUCAO.value == ""){
			 	alert("Preencha o campo Satisfação com o tempo de atendimento.");
			 	return false;
			 }

			 if(document.form.v_TXT_AVALIACAO.value == "" && document.form.v_FLG_SOLICITACAO_ATENDIDA.value == "N"){
			 	alert("Preencha o campo observação");
			 	return false;
			 }
			return confirm("Confirma a avaliação?");
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
	$pagina->LinhaCampoFormularioColspanDestaque("Avaliação sobre o atendimento recebido", 2);

	// Solicitação Atendida?
	$aItemOption = Array();
	$aItemOption[] = array("S", "", "Sim");
	$aItemOption[] = array("N", "", "Não");
	$pagina->LinhaCampoFormulario("Solicitação Atendida?", "right", "S",
									  $pagina->CampoSelect("v_FLG_SOLICITACAO_ATENDIDA", "N", "Localização Física", "S", $aItemOption, "Escolha", "fValidaSolicitacaoAtendida()")
									  , "left", "id=".$pagina->GetIdTable(), "30%");

	// Avaliação do Atendimento
	require_once '../gestaoti/include/PHP/class/class.avaliacao_atendimento.php';
	$avaliacao_atendimento = new avaliacao_atendimento();
	$pagina->LinhaCampoFormulario("Satisfação com a solução apresentada:", "right", "S", $pagina->CampoSelect("v_SEQ_AVALIACAO_ATENDIMENTO", "S", "", "S", $avaliacao_atendimento->combo(1), "Escolha"), "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Satisfação com o conhecimento técnico do prestador de serviço:", "right", "S", $pagina->CampoSelect("v_SEQ_AVALIACAO_CONHECIMENTO_TECNICO", "S", "", "S", $avaliacao_atendimento->combo(1), "Escolha"), "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Satisfação com a postura e cordialidade do prestador de serviço:", "right", "S", $pagina->CampoSelect("v_SEQ_AVALIACAO_POSTURA", "S", "", "S", $avaliacao_atendimento->combo(1), "Escolha"), "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Satisfação com o tempo de espera para atendimento:", "right", "S", $pagina->CampoSelect("v_SEQ_AVALIACAO_TEMPO_ESPERA", "S", "", "S", $avaliacao_atendimento->combo(1), "Escolha"), "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Satisfação com o tempo de solução:", "right", "S", $pagina->CampoSelect("v_SEQ_AVALIACAO_TEMPO_SOLUCAO", "S", "", "S", $avaliacao_atendimento->combo(1), "Escolha"), "left", "id=".$pagina->GetIdTable());

	// Descrição
	$pagina->LinhaCampoFormulario("<span id=\"obrigatorio\" style=\"display: none;\"><font color=red>* </font>Observação:</span>
								   <span id=\"nao_obrigatorio\">Observação:</span>
								  ", "right", "N",
									  $pagina->CampoTextArea("v_TXT_AVALIACAO", "N", "Observação", "99", "9", "", "onkeyup=\"ContaCaracteres(5000, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">5000</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable());

	// Solicitação Atendida?
	$aItemOption = Array();
	$aItemOption[] = array("S", "", "Sim");
	$aItemOption[] = array("N", "", "Não");
	$pagina->LinhaCampoFormulario("Deseja reabrir o chamado?", "right", "S",
									  $pagina->CampoSelect("v_FLG_REABRIR_CHAMADO", "N", "", "S", $aItemOption, "Escolha", "")
									  , "left", " id=\"reabrir_chamado\" style=\"display: none;\" class=".$pagina->GetIdTable(), "30%");

	$pagina->LinhaCampoFormularioColspan("center", "<hr><div align=left><font color=red>*</font> - Preenchimento obrigatório</div>", "2");
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