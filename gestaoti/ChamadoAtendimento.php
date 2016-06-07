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
	require_once 'include/PHP/class/class.chamado.php';
	require_once 'include/PHP/class/class.situacao_chamado.php';
	require_once 'include/PHP/class/class.time_sheet.php';
	require_once 'include/PHP/class/class.atribuicao_chamado.php';
	require_once 'include/PHP/class/class.tipo_ocorrencia.php';
	
	require_once 'include/PHP/class/class.subtipo_chamado.php';
	require_once 'include/PHP/class/class.atividade_chamado.php'; 
	$atividade_chamado_aux = new atividade_chamado();
	$subtipo_chamado_aux = new subtipo_chamado();
	
	
	$banco = new chamado();
	$situacao_chamado = new situacao_chamado();
	$time_sheet = new time_sheet();
	$atribuicao_chamado = new atribuicao_chamado();
	$tipo_ocorrencia = new tipo_ocorrencia();

	// Verificar se o usuário solicitou a desvinculação do chamado
	if($acao == "desvincular"){
		$banco->select($v_SEQ_CHAMADO);
		if($banco->SEQ_CHAMADO_MASTER != ""){
			require_once 'include/PHP/class/class.vinculo_chamado.php';
			$vinculo_chamado = new vinculo_chamado();
			$vinculo_chamado->delete($banco->SEQ_CHAMADO_MASTER, $v_SEQ_CHAMADO);

			// Incluir no histórico
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($banco->SEQ_SITUACAO_CHAMADO);
			$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
			$historico_chamado->setTXT_HISTORICO("Desvinculação com o chamado ".$banco->SEQ_CHAMADO_MASTER." realizada. ");
			$historico_chamado->insert();

			$pagina->redirectTo("ChamadoAtendimento.php?v_SEQ_CHAMADO=".$v_SEQ_CHAMADO);
		}
	}

	// ============================================================================================================
	// Configurações AJAX
	// ============================================================================================================
	require 'include/PHP/class/class.Sajax.php';
	$Sajax = new Sajax();

	function ControlarAtividadeTimeSheet($v_SEQ_CHAMADO, $v_FLG_ATENDIMENTO_INICIADO, $v_SEQ_SITUACAO_CHAMADO, $v_SEQ_EQUIPE_ATRIBUICAO, $v_NUM_PRIORIDADE_FILA){
		if($_SESSION["NUM_MATRICULA_RECURSO"] != ""){
			require_once 'include/PHP/class/class.time_sheet.php';
			$time_sheet = new time_sheet();
			$time_sheet->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$time_sheet->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			if($v_FLG_ATENDIMENTO_INICIADO == 1){ // Atividade iniciada - PARAR
				$time_sheet->FinalizarTarefa();
				return "0";
			}elseif($v_FLG_ATENDIMENTO_INICIADO == 0){ // Atividade não iniciada - INICIAR
				// includes
				require_once 'include/PHP/class/class.chamado.php';
				require_once 'include/PHP/class/class.situacao_chamado.php';
				require_once 'include/PHP/class/class.vinculo_chamado.php';

				// Iniciar a tarefa
				$time_sheet->IniciarTarefa();

				$chamado = new chamado();
				$situacao_chamado = new situacao_chamado();

				// Alterar o início efetivo caso seja o início real do chamado
				$chamado->select($v_SEQ_CHAMADO);
				if($chamado->DTH_INICIO_EFETIVO == ""){
					$chamado->AtualizaDTH_INICIO_EFETIVO($v_SEQ_CHAMADO);
				}

				$chamado = new chamado();

				// Alterar status para em andamento - quando chamado não estiver aguardando aprovação
				if($v_SEQ_SITUACAO_CHAMADO != $situacao_chamado->COD_Aguardando_Planejamento){
				//if(true){

					// Se o chamado estiver contingenciado a situação não precisa voltar para em andamento
					if($v_SEQ_SITUACAO_CHAMADO != $situacao_chamado->COD_Contingenciado){
						// Atualizar Situação do chamado
						$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $situacao_chamado->COD_Em_Andamento);

						// Atualizar atribuicao
						require_once 'include/PHP/class/class.atribuicao_chamado.php';
						$atribuicao_chamado = new atribuicao_chamado();
						$atribuicao_chamado->AtualizarAtribuicao($v_SEQ_CHAMADO, $_SESSION["SEQ_EQUIPE_TI"], $_SESSION["NUM_MATRICULA_RECURSO"], $situacao_chamado->COD_Em_Andamento, $v_SEQ_EQUIPE_ATRIBUICAO);

						// Verificar se a atribuição possui data de início efetiva preenchida
						$atribuicao_chamado = new atribuicao_chamado();
						$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
						$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
						$atribuicao_chamado->selectMatricula();
						if($atribuicao_chamado->DTH_INICIO_EFETIVO == ""){
							$atribuicao_chamado->AtualizaDTH_INICIO_EFETIVO();
						}

						// Verificar se o último registro de histórico está em andamento
						require_once 'include/PHP/class/class.historico_chamado.php';
						$historico_chamado = new historico_chamado();
						if($historico_chamado->GetUltimaSituacao($v_SEQ_CHAMADO) != $situacao_chamado->COD_Em_Andamento){
							// Inserir novo registro de histórico - EM ANDAMENTO
							$historico_chamado = new historico_chamado();
							$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
							$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
							$historico_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Em_Andamento);
							$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
							$historico_chamado->setTXT_HISTORICO("");
							$historico_chamado->insert();

							// Atualizar fila - Caso seja um chamado de manutenção em sistema de informação, aberto por um cliente
							if($v_NUM_PRIORIDADE_FILA != ""){
								$chamado->RetirarChamadoFila($v_SEQ_CHAMADO, $_SESSION["SEQ_EQUIPE_TI"], $v_NUM_PRIORIDADE_FILA, 1);
							}
						}

						// -----------------------------------------------------------------------------------------------------------------
						// Replicar alterações para todos os chamados filhos
						$vinculo_chamado = new vinculo_chamado();
						$vinculo_chamado->setSEQ_CHAMADO_MASTER($v_SEQ_CHAMADO);
						$vinculo_chamado->selectParam();
						if($vinculo_chamado->database->rows > 0){
							while ($row = pg_fetch_array($vinculo_chamado->database->result)){
								// Alterar o início efetivo caso seja o início real do chamado
								$chamado->select($row["seq_chamado_filho"]);
								if($chamado->DTH_INICIO_EFETIVO == ""){
									$chamado->AtualizaDTH_INICIO_EFETIVO($row["seq_chamado_filho"]);
								}

								// Atualizar Situação do chamado
								$chamado->AtualizaSituacao($row["seq_chamado_filho"], $situacao_chamado->COD_Em_Andamento);

								// Atualizar atribuicao
								$atribuicao_chamado = new atribuicao_chamado();
								$atribuicao_chamado->AtualizarAtribuicao($row["seq_chamado_filho"], $_SESSION["SEQ_EQUIPE_TI"], $_SESSION["NUM_MATRICULA_RECURSO"], $situacao_chamado->COD_Em_Andamento, $v_SEQ_EQUIPE_ATRIBUICAO);

								// Verificar se a atribuição possui data de início efetiva preenchida
								$atribuicao_chamado = new atribuicao_chamado();
								$atribuicao_chamado->setSEQ_CHAMADO($row["seq_chamado_filho"]);
								$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
								$atribuicao_chamado->selectMatricula();
								if($atribuicao_chamado->DTH_INICIO_EFETIVO == ""){
									$atribuicao_chamado->AtualizaDTH_INICIO_EFETIVO();
								}

								// Verificar se o último registro de histórico está em andamento
								require_once 'include/PHP/class/class.historico_chamado.php';
								$historico_chamado = new historico_chamado();
								if($historico_chamado->GetUltimaSituacao($row["seq_chamado_filho"]) != $situacao_chamado->COD_Em_Andamento){
									// Inserir novo registro de histórico - EM ANDAMENTO
									$historico_chamado = new historico_chamado();
									$historico_chamado->setSEQ_CHAMADO($row["seq_chamado_filho"]);
									$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
									$historico_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Em_Andamento);
									$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
									$historico_chamado->setTXT_HISTORICO("");
									$historico_chamado->insert();
								}
							}
						}
						// Fim da replicação de dados aos filhos
						// -----------------------------------------------------------------------------------------------------------------
					} //if($v_SEQ_SITUACAO_CHAMADO != $situacao_chamado->COD_Contingenciado){
				} //if($v_SEQ_SITUACAO_CHAMADO != $situacao_chamado->COD_Aguardando_Planejamento){
				return "1";
			}
		}else{
			return "";
		}
	}

	function Inicio_Efetivo($v_DTH_INICIO_EFETIVO, $v_DTH_INICIO_PREVISAO){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/dateObj.class.php';
		$myDate = new dateObj();
		$pagina = new Pagina();
		if($v_DTH_INICIO_EFETIVO != ""){
			$vSegundosDiferenca = $pagina->dateDiffHourPlus(str_replace("-","/",$v_DTH_INICIO_EFETIVO), str_replace("-","/",$v_DTH_INICIO_PREVISAO));
			if($vSegundosDiferenca < 0){ // Chamado em atraso
				$vTempoRestante = $pagina->secondsToTime($vSegundosDiferenca*-1);
				return "<font color=red>".$v_DTH_INICIO_EFETIVO." - Iniciado com atraso de <b>$vTempoRestante</b></font>";
			}else{
				return "<font color=green>".$v_DTH_INICIO_EFETIVO." - Iniciado dentro do prazo</font>";
			}
		}else{
			$vTempoRestante = $pagina->FormatarData($myDate->diff($v_DTH_INICIO_PREVISAO, 'all'));
			if($pagina->dateDiffHour($v_DTH_INICIO_PREVISAO) < 0){ // Chamado em atraso
				return "<font color=red>Chamado n&atilde;o iniciado - Em atraso de <b>$vTempoRestante</b></font>";
			}else{
				return "<font color=green>Chamado n&atilde;o iniciado - O tempo estimado para o início do atendimento é de <b>$vTempoRestante</b></font>";
			}
		}
	}

	function Encerramento_Efetivo($v_DTH_ENCERRAMENTO_EFETIVO, $v_DTH_ENCERRAMENTO_PREVISAO){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/dateObj.class.php';
		$myDate = new dateObj();
		$pagina = new Pagina();
		if($v_DTH_ENCERRAMENTO_EFETIVO != ""){
			$vSegundosDiferenca = $pagina->dateDiffHourPlus(str_replace("-","/",$v_DTH_ENCERRAMENTO_EFETIVO), $v_DTH_ENCERRAMENTO_PREVISAO);
			if($vSegundosDiferenca < 0){ // Chamado em atraso
				$vTempoRestante = $pagina->secondsToTime($vSegundosDiferenca*-1);
				return "<font color=red>".$v_DTH_ENCERRAMENTO_EFETIVO." - Encerrado com atraso de <b>$vTempoRestante</b></font>";
			}else{
				return "<font color=green>".$v_DTH_ENCERRAMENTO_EFETIVO." - Encerrado dentro do prazo</font>";
			}
		}else{
			$vTempoRestante = $pagina->FormatarData($myDate->diff($v_DTH_ENCERRAMENTO_PREVISAO, 'all'));
			if($pagina->dateDiffHour($v_DTH_ENCERRAMENTO_PREVISAO) < 0){ // Chamado em atraso
				return "<font color=red>Chamado n&atilde;o encerrado - Em atraso de <b>$vTempoRestante</b></font>";
			}else{
				return "<font color=green>Chamado n&atilde;o encerrado - O tempo estimado para o encerramento é de <b>$vTempoRestante</b></font>";
			}
		}
	}

	function Carrega_DTH_INICIO_EFETIVO($v_SEQ_CHAMADO){
		if($v_SEQ_CHAMADO != ""){
			require_once 'include/PHP/class/class.chamado.php';
			$chamado = new chamado();
			$chamado->select($v_SEQ_CHAMADO);
			return $chamado->DTH_INICIO_EFETIVO;
		}else{
			return "";
		}
	}

	function ContagemRegressiva($v_DTH_ENCERRAMENTO_EFETIVO, $v_DTH_ENCERRAMENTO_PREVISAO, $v_FLG_ATENDIMENTO_INICIADO, $v_SEQ_CHAMADO){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/dateObj.class.php';
		$myDate = new dateObj();
		$pagina = new Pagina();

		// Verificar se é 12:00 ou 17:30
		if(date("H:i")== "12:01" || date("H:i")== "12:02" || date("H:i")== "12:03" ||
		   date("H:i")== "17:31" || date("H:i")== "17:32" || date("H:i")== "17:33"){
			// Verificar se a atividade está em fechada e o atendimento iniciado
			require_once 'include/PHP/class/class.time_sheet.php';
			$time_sheet = new time_sheet();
			if($v_FLG_ATENDIMENTO_INICIADO == 1 && $time_sheet->VerificarInicioAtividadeChamado($v_SEQ_CHAMADO, $_SESSION["NUM_MATRICULA_RECURSO"]) == 0){
				return "RELOAD";
			}
		}

		// Validar sessão do usuário
		if($_SESSION["SEQ_PERFIL_ACESSO"] == ""){
			return "RELOAD";
		}

		if($v_DTH_ENCERRAMENTO_EFETIVO != ""){
			$vSegundosDiferenca = $pagina->dateDiffHourPlus(str_replace("-","/",$v_DTH_ENCERRAMENTO_EFETIVO), $v_DTH_ENCERRAMENTO_PREVISAO);
			if($vSegundosDiferenca < 0){ // Chamado em atraso
				$vTempoRestante = $pagina->secondsToTime($vSegundosDiferenca*-1);
				return "<font color=red>Encerrado com atraso</font>";
			}else{
				return "<font color=green>Encerrado dentro do prazo</font>";
			}
		}else{
			if($v_DTH_ENCERRAMENTO_PREVISAO != ""){
				$vTempoRestante = $pagina->FormatarDataResumido($myDate->diff($v_DTH_ENCERRAMENTO_PREVISAO, 'all'));
				if($pagina->dateDiffHour($v_DTH_ENCERRAMENTO_PREVISAO) < 0){ // Chamado em atraso
					return "<font color=red><b>-$vTempoRestante</b></font>";
				}else{
					return "<font color=green><b>$vTempoRestante</b></font>";
				}
			}else{
				return "<font color=gray><b>SLA n&atilde;o estabelecido</b></font>";
			}

		}
	}

	$Sajax->sajax_init();
	$Sajax->sajax_debug_mode = 0;
	$Sajax->sajax_export("ControlarAtividadeTimeSheet", "Inicio_Efetivo", "Encerramento_Efetivo", "Carrega_DTH_INICIO_EFETIVO", "ContagemRegressiva");
	$Sajax->sajax_handle_client_request();
	// ============================================================================================================
	// Fim das Configurações AJAX
	// ============================================================================================================

	$pagina->SettituloCabecalho("Detalhamento do Chamado"); // Indica o título do cabeçalho da página

	// pesquisa
	$banco->select($v_SEQ_CHAMADO);
	$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$atribuicao_chamado->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$atribuicao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$atribuicao_chamado->PesquisaAtribuicao();
	// Verificar se houve atribuição do chamado para o profissional ou sua equipe
	if($atribuicao_chamado->SEQ_ATRIBUICAO_CHAMADO == ""){
		// Atendimento não pode continuar, não existe atribuição
		$pagina->ScriptAlert("Não existe atribuição deste chamado para sua equipe.");
		//$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}

	// Adicionar registro de acesso
	require_once 'include/PHP/class/class.historico_acesso_chamado.php';
	$historico_acesso_chamado = new historico_acesso_chamado();
	$historico_acesso_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$historico_acesso_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$historico_acesso_chamado->insert();

	// Itens das abas
	if($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Avaliacao){
		$aItemAba = Array( array("#", "tabact", "Detalhes"),
		 			       array("javascript: AcessarAcao('ChamadoReabrir.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')", "", "Reabrir")
						 );
	}elseif($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Encerrada){
		$aItemAba = Array( array("#", "tabact", "Detalhes")
						 );
	}elseif($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Planejamento){
		$aItemAba = Array( array("#", "tabact", "Detalhes"),
		 			       array("#", "", "Planejar", "onclick=\"AcessarAcao('ChamadoPlanejar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
						 );
	}else{
		// Abas em comum para todas as situações
		$aItemAba = Array();
		$aItemAba[] = array("#", "tabact", "Detalhes");
		$aItemAba[] = array("#", "", "Alterar", "onclick=\"AcessarAcao('ChamadoAlterar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
		$aItemAba[] = array("#", "", "Atribuir", "onclick=\"AcessarAcao('ChamadoAtribuir.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
		$aItemAba[] = array("#", "", "Atendimento", "onclick=\"AcessarAcao('ChamadoRegistroAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
		$aItemAba[] = array("#", "", "Suspender", "onclick=\"AcessarAcao('ChamadoSuspender.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
		$aItemAba[] = array("#", "", "Cancelar", "onclick=\"AcessarAcao('ChamadoCancelar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
		$aItemAba[] = array("#", "", "Devolver 1º nível", "onclick=\"AcessarAcao('ChamadoDevolver1Nivel.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");

		// Verificar se uma reprogramação é possível
		if($banco->QTD_MIN_SLA_ATENDIMENTO == ""){
			if($_SESSION["FLG_LIDER_EQUIPE"] == "S"){ // Mostrar a opção Reprogramar
				$aItemAba[] = array("#", "", "Reprogramar", "onclick=\"AcessarAcao('ChamadoPlanejar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
			}
		}

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
	}

	$pagina->SetaItemAba($aItemAba);
	$pagina->estiloTabBar = "tabbarMenor";
	$pagina->flagScriptCalendario = 0;
	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_DTH_INICIO_EFETIVO", $banco->DTH_INICIO_EFETIVO);

	// Verificar se o atendimento deste chamado já foi iniciado pelo usuário
	$v_FLG_ATENDIMENTO_INICIADO = $time_sheet->VerificarInicioAtividadeChamado($banco->SEQ_CHAMADO, $_SESSION["NUM_MATRICULA_RECURSO"]);
	print $pagina->CampoHidden("v_FLG_ATENDIMENTO_INICIADO", $v_FLG_ATENDIMENTO_INICIADO);
	if($v_FLG_ATENDIMENTO_INICIADO == "1"){
		$vMsgInicial = "<span id=\"MsgAtendimento\">Clique aqui para parar o <b>atendimento</b></span>";
		$vLabelButton = "Parar";
	}else{
		$vMsgInicial = "<span id=\"MsgAtendimento\">Clique aqui para iniciar o <b>atendimento</b></span>";
		$vLabelButton = "Iniciar";
	}

	// Verifricar data de encerramento previsto
	if($banco->QTD_MIN_SLA_ATENDIMENTO != ""){
		$v_DTH_ENCERRAMENTO_PREVISAO = $banco->DTH_ENCERRAMENTO_PREVISAO;
	}else{
		require_once 'include/PHP/class/class.aprovacao_chamado.php';
		$aprovacao_chamado = new aprovacao_chamado();
		$aprovacao_chamado->GetUltimoAprovacao($banco->SEQ_CHAMADO);
		if($aprovacao_chamado->DTH_PREVISTA != ""){
			$v_DTH_ENCERRAMENTO_PREVISAO = $aprovacao_chamado->DTH_PREVISTA;
		}else{
			$v_DTH_ENCERRAMENTO_PREVISAO = "";
		}

	}

	$tabela = array();
	$header = array();
	// Data de Abertura
	$header = array();
	$header[] = array("<div align=\"left\">".$vMsgInicial."</div>", "left", "40%", "label");
	$header[] = array("&nbsp;", "left", "3%", "");
	$header[] = array("Tempo restante:", "left", "20%", "label");
	$header[] = array("<div align=\"left\"><span id=\"contagem_regressiva\">".ContagemRegressiva($banco->DTH_ENCERRAMENTO_EFETIVO, $v_DTH_ENCERRAMENTO_PREVISAO, $v_FLG_ATENDIMENTO_INICIADO, $banco->SEQ_CHAMADO)."</span></div>", "left", "", "label");
	$tabela[] = $header;

	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Atendimento", 2);
	$pagina->LinhaCampoFormulario($pagina->CampoButton("do_ControlarAtividadeTimeSheet()",$vLabelButton, "button", "atividade", $banco->SEQ_CHAMADO_MASTER!=""?"disabled":"")
								  , "left"
								  , "N"
								  ,$pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true)
								  , "left"
								  , ""
								  ,"23%","");
	$pagina->FechaTabelaPadrao();

	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais " .$pagina->BotaoImprimir("ChamadoAtendimentoPesquisaImprimirPDF.php?imprimir[]=".$banco->SEQ_CHAMADO,"TARGET=XXX" ), 2);

	if($banco->DTH_ENCERRAMENTO_PREVISAO == ""){
		$pagina->LinhaCampoFormularioColspan("center", "<br><span id=campo><font color=red>O prazo de encerramento do chamado não foi estabelecido. Não será possível encerrá-lo até que o líder definia o prazo correspondente.</font></span><br><br>", 2);
	}

	$pagina->LinhaCampoFormulario("Número:", "right", "N", $banco->SEQ_CHAMADO, "left", "id=".$pagina->GetIdTable(),"23%","");

	if($banco->SEQ_CHAMADO_MASTER != ""){
		$pagina->LinhaCampoFormulario("Chamado master:", "right", "N", "
			<a href=\"ChamadoAtendimento.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO_MASTER."\">".$banco->SEQ_CHAMADO_MASTER." - Clique aqui para acessar. Qualquer ação só pode ser realizada no chamado master.</a>
			&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"javascript: DesvincularChamado();\"><font color=red>Clique aqui para Desvincular</font></a>
			", "left", "id=".$pagina->GetIdTable(),"23%","");
	}

	$tipo_ocorrencia = new tipo_ocorrencia();
	$tipo_ocorrencia->select($banco->SEQ_TIPO_OCORRENCIA);
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").":", "right", "N", $tipo_ocorrencia->NOM_TIPO_OCORRENCIA, "left", "id=".$pagina->GetIdTable());

	require_once 'include/PHP/class/class.subtipo_chamado.php';
	$subtipo_chamado = new subtipo_chamado();
	$subtipo_chamado->select($banco->SEQ_SUBTIPO_CHAMADO);

	require_once 'include/PHP/class/class.tipo_chamado.php';
	$tipo_chamado = new tipo_chamado();
	$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", "right", "N", $tipo_chamado->DSC_TIPO_CHAMADO, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").":", "right", "N", $subtipo_chamado->DSC_SUBTIPO_CHAMADO, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").":", "right", "N", $banco->DSC_ATIVIDADE_CHAMADO, "left", "id=".$pagina->GetIdTable());

	if($banco->SEQ_ITEM_CONFIGURACAO != ""){ // Mostrar o sistema de informação e a prioridade estabelecida pelo cliente
		require_once 'include/PHP/class/class.item_configuracao.php';
		$item_configuracao = new item_configuracao();
		$item_configuracao->select($banco->SEQ_ITEM_CONFIGURACAO);
		$pagina->LinhaCampoFormulario("Sistema de Informação:", "right", "N", $item_configuracao->SIG_ITEM_CONFIGURACAO." - ".$item_configuracao->NOM_ITEM_CONFIGURACAO, "left", "id=".$pagina->GetIdTable());
		$pagina->LinhaCampoFormulario("Prioridade na Fila:", "right", "N", $banco->NUM_PRIORIDADE_FILA, "left", "id=".$pagina->GetIdTable());
	}

	$situacao_chamado->select($banco->SEQ_SITUACAO_CHAMADO);
	$pagina->LinhaCampoFormulario("Situação:", "right", "N", "<span id=\"situacao\">".$situacao_chamado->DSC_SITUACAO_CHAMADO."</span>", "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Solicitação:", "right", "N", str_replace(chr(13), "<br>",$banco->TXT_CHAMADO), "left", "id=".$pagina->GetIdTable());

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
		date("d/m/y",$DT_INICIO_UTILIZACAO_APARELHO) ." à ".date("d/m/y",$DT_FIM_UTILIZACAO_APARELHO)
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
		$pagina->LinhaCampoFormulario("Serviços:", "right", "N", $banco->SERVICOS_EVENTO, "left", "id=".$pagina->GetIdTable());
		
	}
/*
	if($atribuicao_chamado->DSC_EQUIPE_ATRIBUICAO == ""){
		if($banco->SEQ_SITUACAO_CHAMADO != $situacao_chamado->COD_Aguardando_Planejamento){
			print $pagina->CampoHidden("v_FLG_EQUIPE_ATRIBUICAO", "N");
			require_once 'include/PHP/class/class.equipe_atribuicao.php';
			$equipe_atribuicao = new equipe_atribuicao();
			$equipe_atribuicao->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
			$pagina->LinhaCampoFormulario("Atribuição:", "right", "N",
					"<span id=\"text_equipe_atribuicao\"> ".
					$pagina->CampoSelect("v_SEQ_EQUIPE_ATRIBUICAO", "N", "Atribuicao da equipe", "S", $equipe_atribuicao->combo("DSC_EQUIPE_ATRIBUICAO"), "Escolha").
					"</span>"
					, "left", " id=".$pagina->GetIdTable());
		}else{
			print $pagina->CampoHidden("v_FLG_EQUIPE_ATRIBUICAO", "N");
			print $pagina->CampoHidden("v_SEQ_EQUIPE_ATRIBUICAO", "");
		}
	}else{
*/
		print $pagina->CampoHidden("v_FLG_EQUIPE_ATRIBUICAO", "S");
//		print $pagina->CampoHidden("v_SEQ_EQUIPE_ATRIBUICAO", $atribuicao_chamado->SEQ_EQUIPE_ATRIBUICAO);
		print $pagina->CampoHidden("v_SEQ_EQUIPE_ATRIBUICAO", "1");
//		$pagina->LinhaCampoFormulario("Atribuição:", "right", "N", $atribuicao_chamado->DSC_EQUIPE_ATRIBUICAO, "left", "id=".$pagina->GetIdTable());
//	}

	$pagina->LinhaCampoFormulario("Atividades:", "right", "N", str_replace(chr(13), "<br>",$atribuicao_chamado->TXT_ATIVIDADE), "left", "id=".$pagina->GetIdTable());

	if($banco->SEQ_ACAO_CONTINGENCIAMENTO != ""){
		require_once 'include/PHP/class/class.acao_contingenciamento.php';
		$acao_contingenciamento = new acao_contingenciamento();
		$acao_contingenciamento->select($banco->SEQ_ACAO_CONTINGENCIAMENTO);
		$pagina->LinhaCampoFormulario("Ação de contingenciamento:", "right", "N", $acao_contingenciamento->NOM_ACAO_CONTINGENCIAMENTO, "left", "id=".$pagina->GetIdTable());
	}

	// Exibir o texto de resolução
	if($banco->TXT_RESOLUCAO != ""){
		$pagina->LinhaCampoFormulario("Informações já registradas sobre a solução:", "right", "N", str_replace(chr(13), "<br>",$banco->TXT_RESOLUCAO), "left", "id=".$pagina->GetIdTable());
	}

	// Exibir o texto de contingenciamento caso exista
	if($banco->TXT_CONTINGENCIAMENTO != ""){
		$pagina->LinhaCampoFormulario("Observação sobre o contingenciamento:", "right", "N", str_replace(chr(13), "<br>",$banco->TXT_CONTINGENCIAMENTO), "left", "id=".$pagina->GetIdTable());
	}

	// Exibir a causa raiz, caso exista
	if($banco->TXT_CAUSA_RAIZ != ""){
		$pagina->LinhaCampoFormulario("Causa raiz:", "right", "N", str_replace(chr(13), "<br>",$banco->TXT_CAUSA_RAIZ), "left", "id=".$pagina->GetIdTable());
	}
	
	
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
			
			$pagina->LinhaCampoFormulario("Responsável pela aprovação:", "right", "N", 
	   		$empregados->NOME	, "left", "id=".$pagina->GetIdTable());
		}
	}

	// Identificar se o chamado possui etapas programadas
	require_once 'include/PHP/class/class.etapa_chamado.php';
	$etapa_chamado = new etapa_chamado();
	$etapa_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$etapa_chamado->selectParam("DTH_INICIO_PREVISTO");

	// ============================================================================================================
	// Configurações AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		<?
		$Sajax->sajax_show_javascript();
		?>
		// Chamada
		function do_ControlarAtividadeTimeSheet() {
			<? if($banco->SEQ_SITUACAO_CHAMADO != $situacao_chamado->COD_Aguardando_Planejamento){ ?>
					if(document.form.v_FLG_EQUIPE_ATRIBUICAO.value == "N" && document.form.v_SEQ_EQUIPE_ATRIBUICAO.value == "" && document.form.v_FLG_ATENDIMENTO_INICIADO.value == "0"){
						alert("Selecione a atribuição cabível ao seu atendimento do chamado.");
					}else{
						// Alterar a mensagem de atendimento
						//window.MsgAtendimento.innerHTML = "carregando....";
						document.getElementById('MsgAtendimento').innerHTML = "carregando....";
						// Executar lançamento
						x_ControlarAtividadeTimeSheet(<?=$banco->SEQ_CHAMADO?>, document.form.v_FLG_ATENDIMENTO_INICIADO.value, <?=$banco->SEQ_SITUACAO_CHAMADO?>, document.form.v_SEQ_EQUIPE_ATRIBUICAO.value, '<?=$banco->NUM_PRIORIDADE_FILA?>', retorno_ControlarAtividadeTimeSheet);
					}
			<? }else{ ?>
						// Alterar a mensagem de atendimento
						//window.MsgAtendimento.innerHTML = "carregando....";
						document.getElementById('MsgAtendimento').innerHTML = "carregando....";
						// Executar lançamento
						x_ControlarAtividadeTimeSheet(<?=$banco->SEQ_CHAMADO?>, document.form.v_FLG_ATENDIMENTO_INICIADO.value, <?=$banco->SEQ_SITUACAO_CHAMADO?>, document.form.v_SEQ_EQUIPE_ATRIBUICAO.value, '<?=$banco->NUM_PRIORIDADE_FILA?>', retorno_ControlarAtividadeTimeSheet);
			<? } ?>

		}
		// Retorno
		function retorno_ControlarAtividadeTimeSheet(val) {
			if(val != ""){
				// Alterar flg hidden
				document.form.v_FLG_ATENDIMENTO_INICIADO.value = val;
				// Alterar a mensagem de atendimento e Alterar label do botão
				if(document.form.v_FLG_ATENDIMENTO_INICIADO.value == "1"){
					document.form.atividade.value = " Parar ";
					//window.MsgAtendimento.innerHTML = "Clique aqui para parar o <b>atendimento</b>";
					document.getElementById('MsgAtendimento').innerHTML = "Clique aqui para parar o <b>atendimento</b>";
				}else{
					document.form.atividade.value = " Iniciar ";
					//window.MsgAtendimento.innerHTML = "Clique aqui para iniciar o <b>atendimento</b>";
					document.getElementById('MsgAtendimento').innerHTML = "Clique aqui para iniciar o <b>atendimento</b>";
				}
				// Atualizar equipe_atribuicao
				<? if($banco->SEQ_SITUACAO_CHAMADO != $situacao_chamado->COD_Aguardando_Planejamento){ ?>
					if(document.form.v_FLG_EQUIPE_ATRIBUICAO.value == "N" && document.form.v_SEQ_EQUIPE_ATRIBUICAO.value != ""){
						valorRetorno = "<input type=hidden name=v_SEQ_EQUIPE_ATRIBUICAO value="+document.form.v_SEQ_EQUIPE_ATRIBUICAO.value+">";
						valorRetorno = valorRetorno + GetTextItemCombo(document.form.v_SEQ_EQUIPE_ATRIBUICAO)
						//window.text_equipe_atribuicao.innerHTML = valorRetorno;
						document.getElementById('text_equipe_atribuicao').innerHTML = valorRetorno;
						document.form.v_FLG_EQUIPE_ATRIBUICAO.value = "S";
					}
				<? } ?>
				// Atualizar situação
				<? if($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Planejamento){ ?>
						document.getElementById('situacao').innerHTML = "Aguardando aprovação";
				<? }elseif($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Contingenciado){ ?>
						document.getElementById('situacao').innerHTML = "Contingenciado";
				<? }else{ ?>
						document.getElementById('situacao').innerHTML = "Em Andamento";
				<? } ?>
				// Recarregar iframes
				document.getElementById('IframeHistorico').contentWindow.location.reload(true);
				document.getElementById('profissionais').contentWindow.location.reload(true);
				document.getElementById('time_sheet').contentWindow.location.reload(true);
				document.getElementById('IframeVinculo').contentWindow.location.reload(true);
				// Atualiza Data de início efetivo
				do_Carrega_DTH_INICIO_EFETIVO();
			}else{
				alert("Sua sessão está expirada expirada. Por favor efetue logon novamente.");
				validarSaida=false;
				window.location.href = "index.php";
			}
		}
		// Chamada
		function do_Inicio_Efetivo(v_DTH_INICIO_EFETIVO, v_DTH_INICIO_PREVISAO) {
			x_Inicio_Efetivo(v_DTH_INICIO_EFETIVO, v_DTH_INICIO_PREVISAO, retorno_Inicio_Efetivo);
		}
		// Retorno
		function retorno_Inicio_Efetivo(val) {
			//document.getElementById('SPAN_Inicio_Efetivo').innerHTML = val;
		}
		// Chamada
		function do_Encerramento_Efetivo(v_DTH_ENCERRAMENTO_EFETIVO, v_DTH_ENCERRAMENTO_PREVISAO) {
			x_Encerramento_Efetivo(v_DTH_ENCERRAMENTO_EFETIVO, v_DTH_ENCERRAMENTO_PREVISAO, retorno_Encerramento_Efetivo);
		}
		// Retorno
		function retorno_Encerramento_Efetivo(val) {
			document.getElementById('SPAN_Encerramento_Efetivo').innerHTML = val;
		}
		// Chamada
		function do_Carrega_DTH_INICIO_EFETIVO() {
			x_Carrega_DTH_INICIO_EFETIVO(<?=$banco->SEQ_CHAMADO?>, retorno_Carrega_DTH_INICIO_EFETIVO);
		}
		// Retorno
		function retorno_Carrega_DTH_INICIO_EFETIVO(val) {
			document.form.v_DTH_INICIO_EFETIVO.value = val;
		}
		// Chamada
		function do_ContagemRegressiva(v_DTH_ENCERRAMENTO_EFETIVO, v_DTH_ENCERRAMENTO_PREVISAO) {
			x_ContagemRegressiva(v_DTH_ENCERRAMENTO_EFETIVO, v_DTH_ENCERRAMENTO_PREVISAO, document.form.v_FLG_ATENDIMENTO_INICIADO.value, <?=$banco->SEQ_CHAMADO?> , retorno_ContagemRegressiva);
		}
		// Retorno
		function retorno_ContagemRegressiva(val) {
			if(val == "RELOAD"){
				validarSaida=false;
				window.location.href = "<?=$PHP_SELF?>?v_SEQ_CHAMADO=<?=$banco->SEQ_CHAMADO?>&reloadTimeSheet=1";
			}else{
				document.getElementById('contagem_regressiva').innerHTML = val;
			}
		}

		// ==================================================== FIM AJAX ==================================================

		// =======================================================================
		// Controle dos relógios decrescentes
		// =======================================================================
		function CarregaInicioEfetivo(){
			do_Inicio_Efetivo(document.form.v_DTH_INICIO_EFETIVO.value, '<?=$banco->DTH_INICIO_PREVISAO?>');
		}

		function CarregaEncerramentoEfetivo(){
			do_Encerramento_Efetivo('<?=$banco->DTH_ENCERRAMENTO_EFETIVO?>', '<?=$v_DTH_ENCERRAMENTO_PREVISAO?>');
			do_ContagemRegressiva('<?=$banco->DTH_ENCERRAMENTO_EFETIVO?>', '<?=$v_DTH_ENCERRAMENTO_PREVISAO?>');
		}

		// Setar os cronometros a cada segundo
		<? if($Sajax->sajax_debug_mode == 0){ ?>
			var intervaloInicioEfetivo = window.setInterval("CarregaInicioEfetivo()", 60000);
			var intervaloEncerramentoEfetivo = window.setInterval("CarregaEncerramentoEfetivo()", 60000);
		<? } ?>

		// =======================================================================
		// Controlar a saída às ações do chamado
		// =======================================================================
		function AcessarAcao(vDestino){
			if(document.form.v_FLG_ATENDIMENTO_INICIADO.value == 1){
				validarSaida = false;
				window.location.href = vDestino;
			}else{
				validarSaida = true;
				alert("Inicie o atendimento antes de realizar qualquer ação.");
			}
		}

		// =======================================================================
		// Configuração das TABS
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

                        <? if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){	?>
                            document.getElementById("tabelaAprovadores").style.display = "none";
                            document.getElementById("tabAprovadores").attributes["class"].value = "";
                        <? } ?>
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

		// Desvincular o chamado do chamado master
		function DesvincularChamado(){
			if(confirm("Confirma a desvinculação do chamado <?=$banco->SEQ_CHAMADO?> do chamado <?=$banco->SEQ_CHAMADO_MASTER?> ?")){
				window.location.href="ChamadoAtendimento.php?v_SEQ_CHAMADO=<?=$banco->SEQ_CHAMADO?>&acao=desvincular";
			}
		}

		<?
		if($reloadTimeSheet == "1"){
		?>
			// Mensagem de Reload
			alert("Atividade foi automaticamente parada. Caso for continuar o trabalho, inicie novamente.");
		<?
		}
		?>

		// Initialise
		addEvent(window, 'load', addListeners, false);
		// <input type="button" class="button" value="Save" onclick="removeEvent(window, 'beforeunload', exitAlert, false); location.href='../list/overview.asp'" />
	</script>
<?
	$aItemAba = Array();
	$aItemAba[] = array("javascript:fMostra('tabelaSLA','tabSLA')", "tabact", "&nbsp;SLA&nbsp;", "tabSLA", "onclick=\"validarSaida=false;\"");
	if($banco->QTD_MIN_SLA_ATENDIMENTO == ""){
		$aItemAba[] = array("javascript:fMostra('tabelaPrevisao','tabPrevisao')", "", "&nbsp;Previsão&nbsp;", "tabPrevisao", "onclick=\"validarSaida=false;\"");
	}
	if($etapa_chamado->database->rows > 0){
		$aItemAba[] = array("javascript:fMostra('tabelaEtapas','tabEtapas')", "", "&nbsp;Etapas&nbsp;", "tabEtapas", "onclick=\"validarSaida=false;\"");
	}
	$aItemAba[] = array("javascript:fMostra('tabelaMeusDados','tabMeusDados')", "", "&nbsp;Solicitante&nbsp;", "tabMeusDados", "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaHistorico','tabHistorico')", "", "&nbsp;Histórico&nbsp;", "tabHistorico",  "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaAcessos','tabAcessos')", "", "&nbsp;Acessos&nbsp;", "tabAcessos",  "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaTimeSheet','tabTimeSheet')", "", "&nbsp;Time Sheet&nbsp;", "tabTimeSheet", "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaProfissionais','tabProfissionais')", "", "&nbsp;Profissionais&nbsp;", "tabProfissionais", "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaAtendimento','tabAtendimento')", "", "&nbsp;Atendimento&nbsp;", "tabAtendimento", "onclick=\"validarSaida=false;\"");
	if($pagina->flg_usar_funcionalidades_patrimonio == "S"){
            $aItemAba[] = array("javascript:fMostra('tabelaPatrimonio','tabPatrimonio')", "", "&nbsp;Patrimônio(s)&nbsp;", "tabPatrimonio", "onclick=\"validarSaida=false;\"");
        }
        $aItemAba[] = array("javascript:fMostra('tabelaAnexos','tabAnexos')", "", "&nbsp;Anexo(s)&nbsp;", "tabAnexos", "onclick=\"validarSaida=false;\"");
	if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
		$aItemAba[] = array("javascript:fMostra('tabelaVinculo','tabVinculo')", "", "&nbsp;V&iacute;nculos&nbsp;", "tabVinculo", "onclick=\"validarSaida=false;\"");
	}
	if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){	     
 		$aItemAba[] = array("javascript: fMostra('tabelaAprovadores','tabAprovadores')", "", "&nbsp;Quem pode Aprovar&nbsp;", "tabAprovadores", "onclick=\"validarSaida=false;\"");
 	}
	$pagina->SetaItemAba($aItemAba);
	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// SLA
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaSLA cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Gestão de Nível de Serviço", 2);
	$tabela = array();

	require_once 'include/PHP/class/dateObj.class.php';
	$myDate = new dateObj();

	$header = array();
	// Data de Abertura
	$header = array();
	$header[] = array("Data de Abertura:", "center", "23%", "label");
	$header[] = array($banco->DTH_ABERTURA, "left", "", "campo");
	$tabela[] = $header;

	// ======================================================================================================
	// TRIAGEM
	if($banco->DTH_TRIAGEM_EFETIVA != "" && $banco->QTD_MIN_SLA_TRIAGEM != ""){
		// Cálculo do tempo de triagem em minutos corridos
		if($banco->FLG_FORMA_MEDICAO_TEMPO == "C"){
			$v_DTH_TRIAGEM_PREVISAO = $pagina->add_minutos($banco->QTD_MIN_SLA_TRIAGEM, $banco->DTH_ABERTURA);
		}else{ // Cálculo do tempo em minutos úteis
			$v_DTH_TRIAGEM_PREVISAO = $pagina->add_minutos_uteis($banco->QTD_MIN_SLA_TRIAGEM, $banco->DTH_ABERTURA, $banco->HoraInicioExpediente, $banco->HoraInicioIntervalo, $banco->HoraFimIntervalo, $banco->HoraFimExpediente, $banco->aDtFeriado);
		}
		$header = array();
		$header[] = array("Previsão de Atend. 1º nível:", "center", "", "label");
		$header[] = array($v_DTH_TRIAGEM_PREVISAO, "left", "", "campo");
		$tabela[] = $header;

		$vSegundosDiferenca = $pagina->dateDiffHourPlus(str_replace("-","/",$banco->DTH_TRIAGEM_EFETIVA), $v_DTH_TRIAGEM_PREVISAO);
		if($vSegundosDiferenca < 0){ // Chamado em atraso
			$vTempoRestante = $pagina->secondsToTime($vSegundosDiferenca*-1);
			$vTempoRestante = "<font color=red>Atend. 1º nível com atraso de $vTempoRestante</font>";
		}else{
			$vTempoRestante = "<font color=green>Atend. 1º nível dentro do prazo</font>";
		}

		// Triagem Efetiva
		$header = array();
		$header[] = array("Atend. 1º nível - Efetiva:", "center", "", "label");
		$header[] = array("".$banco->DTH_TRIAGEM_EFETIVA." - ".$vTempoRestante, "left", "", "campo");
		$tabela[] = $header;

	}

	// ===================================================================================================
	// Data de início efetivo
	$header = array();
	$header[] = array("Data de início:", "center", "23%", "label");
	if($banco->DTH_INICIO_EFETIVO != ""){
		$header[] = array(str_replace("-","/",$banco->DTH_INICIO_EFETIVO), "left", "", "campo");
	}else{
		$header[] = array("Ainda n&atilde;o iniciado", "left", "", "campo");
	}
	$tabela[] = $header;

	// ===================================================================================================
	// Data de contigenciamento
	if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
		$historico_chamado = new historico_chamado();
		$v_DTH_CONTINGENCIAMENTO = $historico_chamado->GetDthContingenciamento($banco->SEQ_CHAMADO);
		if($v_DTH_CONTINGENCIAMENTO != ""){

			$v_DTH_CONTINGENCIAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($banco->DTH_ABERTURA, $banco->QTD_MIN_SLA_ATENDIMENTO, $banco->FLG_FORMA_MEDICAO_TEMPO, $banco->SEQ_CHAMADO, $banco->SEQ_TIPO_OCORRENCIA, $banco->QTD_MIN_SLA_ATENDIMENTO);

			$header = array();
			$header[] = array("Previsão de Contingenciamento:", "center", "23%", "label");
			$header[] = array($v_DTH_CONTINGENCIAMENTO_PREVISAO, "left", "", "campo");
			$tabela[] = $header;

			$vSegundosDiferenca = $pagina->dateDiffHourPlus(str_replace("-","/",$v_DTH_CONTINGENCIAMENTO), $v_DTH_CONTINGENCIAMENTO_PREVISAO);
			if($vSegundosDiferenca < 0){ // Chamado em atraso
				$vTempoRestante = $pagina->secondsToTime($vSegundosDiferenca*-1);
				$vTempoRestante = "<font color=red>Contingenciado com atraso de $vTempoRestante</font>";
			}else{
				$vTempoRestante = "<font color=green>Contingenciado dentro do prazo</font>";
			}

			// Contingenciamento Efetivo
			$header = array();
			$header[] = array("Contingenciamento Efetivo:", "center", "", "label");
			$header[] = array("".$v_DTH_CONTINGENCIAMENTO." - ".$vTempoRestante, "left", "", "campo");
			$tabela[] = $header;
		}
	}

	// ====================================================================================================
	// Data de encerramento
	if($banco->DTH_ENCERRAMENTO_PREVISAO != ""){
		// Previsão de encerramento
		$header = array();
		$header[] = array("Previsão de encerramento:", "center", "23%", "label");
		$header[] = array($banco->DTH_ENCERRAMENTO_PREVISAO, "left", "", "campo");
		$tabela[] = $header;
		// Encerramento efetivo
		$header = array();
		$header[] = array("Encerramento Efetivo:", "center", "", "label");
		$header[] = array("<span id=\"SPAN_Encerramento_Efetivo\">".Encerramento_Efetivo($banco->DTH_ENCERRAMENTO_EFETIVO, $banco->DTH_ENCERRAMENTO_PREVISAO)."</span>", "left", "", "campo");
		$tabela[] = $header;
	}else{
		$tabela = array();
		$header = array();
		// Data de Abertura
		$header = array();
		$header[] = array("Prazo n&atilde;o estabelecido. Aguardando estimativa.", "left", "", "campo");
		$tabela[] = $header;
	}

	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Previsão
	//================================================================================================================================
	if($banco->QTD_MIN_SLA_ATENDIMENTO == ""){ // Apenas para demandas de SLA Pós estabelecido
		$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaPrevisao style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		$pagina->LinhaCampoFormularioColspanDestaque("Planejamento de encerramento do chamado", 2);

		require_once 'include/PHP/class/class.aprovacao_chamado.php';
		$aprovacao_chamado = new aprovacao_chamado();
		$aprovacao_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
		$aprovacao_chamado->selectParam("DTH_APROVACAO DESC");
		if($aprovacao_chamado->database->rows == 0){
			$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
		}else{
			$tabela = array();
			$header = array();
			$header[] = array("Profissional", "center", "25%", "header");
			$header[] = array("Data Planejamento", "center", "17%", "header");
			$header[] = array("Data Prevista", "center", "17%", "header");
			$header[] = array("Observação", "center", "", "header");
			$tabela[] = $header;

			while ($row = pg_fetch_array($aprovacao_chamado->database->result)){
				$header = array();
				$header[] = array($row["nom_colaborador"], "left", "", "campo");
				$header[] = array($row["dth_aprovacao"], "center", "", "campo");
				$header[] = array($row["dth_prevista"], "center", "", "campo");
				$header[] = array($row["txt_justificativa"], "left", "", "campo");
				$tabela[] = $header;
			}
			$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
		}
		$pagina->FechaTabelaPadrao();
	}

	//================================================================================================================================
	// Etapas do chamado
	//================================================================================================================================
	if($etapa_chamado->database->rows > 0){
		$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaEtapas style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		$pagina->LinhaCampoFormularioColspanDestaque("Etapas do Chamado", 2);
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("IframeEtapas", "Etapa_chamadoPesquisaChamado.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
		$pagina->FechaTabelaPadrao();
	}

	//================================================================================================================================
	// Dados do Solicitante
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaMeusDados style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
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

	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Histórico
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaHistorico style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Histórico do Chamado", 2);
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("IframeHistorico", "Historico_chamadoPesquisaChamado.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Acessos
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaAcessos style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Registro de acessos às informações do chamado", 2);
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("acessos", "Historico_acesso_chamadoPesquisaChamado.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Profissionais
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaProfissionais style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Equipes/Profissionais responsáveis pelo atendimento do chamado", 2);
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("profissionais", "Atribuicao_chamadoPesquisaAtendimento.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Time Sheet
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaTimeSheet style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Registro de horas trabalhadas no chamado", 2);
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("time_sheet", "Time_sheetPesquisaChamado.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Atendimento
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaAtendimento style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Registro de informações sobre o atendimento realizado ", 2);

	require_once 'include/PHP/class/class.atendimento_chamado.php';
	$atendimento_chamado = new atendimento_chamado();
	$atendimento_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$atendimento_chamado->selectParam("DTH_ATENDIMENTO_CHAMADO DESC");
	if($atendimento_chamado->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
	}else{
		$tabela = array();
		$header = array();
		$header[] = array("Profissional", "center", "25%", "header");
		$header[] = array("Data", "center", "17%", "header");
		$header[] = array("Observação", "center", "", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($atendimento_chamado->database->result)){
			$header = array();
			$header[] = array($row["nom_colaborador"], "left", "", "");
			$header[] = array($row["dth_atendimento_chamado"], "center", "", "");
			$header[] = array($row["txt_atendimento_chamado"], "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
	}
	$pagina->FechaTabelaPadrao();


	//================================================================================================================================
	// Patrimônio
	//================================================================================================================================
        if($pagina->flg_usar_funcionalidades_patrimonio == "S"){
            $pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaPatrimonio style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
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
                            $ativos = new ativos();
                            $ativos->select($row["num_patrimonio"]);
                            $header = array();
                            $header[] = array($row["num_patrimonio"], "center", "", "");
                            $header[] = array($ativos->NOM_BEM, "left", "", "");
    //			$header[] = array($ativos->NOM_DETENTOR, "left", "", "");
                            $header[] = array($ativos->DSC_LOCALIZACAO, "left", "", "");
                            $tabela[] = $header;
                    }
                    $pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
            }
            $pagina->FechaTabelaPadrao();
        }
	//================================================================================================================================
	// Anexo
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaAnexos style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
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
			$header[] = array("<a target=\"_blank\"  href=\"../cau/anexos/".$row["nom_arquivo_sistema"]."\">".$row["nom_arquivo_original"]."</a>", "left", "", "");
			$header[] = array($row["dth_anexo"], "left", "", "");
			$header[] = array($row["nom_colaborador"], "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
	}

	$pagina->FechaTabelaPadrao();
	
	//================================================================================================================================
	// QUEM PODE APROVAR
	//================================================================================================================================
	if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){
		$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaAprovadores style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		$pagina->LinhaCampoFormularioColspanDestaque("Quem pode Aprovar", 2);
	
		require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
	   	$empregados = new empregados(1); 
	   	$empregados->select($banco->NUM_MATRICULA_SOLICITANTE); 
		
                /*
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
			$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
		}
                 * 
                 */
                
                $tabela = array();
                $header = array();
                $header[] = array("Nome", "left", "30%", "header");
                $header[] = array("E-mail", "center", "30%", "header");
                //$header[] = array("Função Administrativa", "center", "30%", "header");

                $tabela[] = $header;  
			 
                
                if($atividade_chamado_aux->NUM_MATRICULA_APROVADOR != ""){
                    require_once 'include/PHP/class/class.empregados.oracle.php';
                    $empregados = new empregados();
                    $header = array();
                    $empregados->GetNomeEmail($atividade_chamado_aux->NUM_MATRICULA_APROVADOR);
                    $header[] = array($empregados->NOME, "center", "", "");
                    $header[] = array($empregados->DES_EMAIL, "left", "", ""); 
                    //$header[] = array($row["dsfuncaoadministrativa"], "left", "", "");
                    $tabela[] = $header;
                }
                if($atividade_chamado_aux->NUM_MATRICULA_APROVADOR_SUBSTITUTO != ""){
                    require_once 'include/PHP/class/class.empregados.oracle.php';
                    $empregados = new empregados();
                    $header = array();
                    $empregados->GetNomeEmail($atividade_chamado_aux->NUM_MATRICULA_APROVADOR_SUBSTITUTO);
                    $header[] = array($empregados->NOME, "center", "", "");
                    $header[] = array($empregados->DES_EMAIL, "left", "", ""); 
                    //$header[] = array($row["dsfuncaoadministrativa"], "left", "", "");
                    $tabela[] = $header;
                }
                $pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
		$pagina->FechaTabelaPadrao();
	}

	//================================================================================================================================
	// Vínculo
	//================================================================================================================================
	require_once 'include/PHP/class/class.prioridade_chamado.php';
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaVinculo style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("IframeVinculo", "Vinculo_chamadoPesquisaChamado.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
	$pagina->FechaTabelaPadrao();

	$pagina->MontaRodape();
}else{
	$pagina->redirectTo("ChamadoAtendimentoPesquisa.php");
}

?>