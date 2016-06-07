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
	// Reprovar o chamado
	// ============================================================================================================
	if($flag == "2"){
		if($v_TXT_JUSTIFICATIVA == ""){
		 	$vErroCampos = "Preencha o campo observação. ";
		}
		if($vErroCampos == ""){
			require_once 'include/PHP/class/class.chamado.php';
			require_once 'include/PHP/class/class.situacao_chamado.php';
			require_once 'include/PHP/class/class.atividade_chamado.php';
			$chamado = new chamado();
			$situacao_chamado = new situacao_chamado();

			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Encerrada;

			// Alterar status para em andamento
			$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $v_SEQ_SITUACAO_CHAMADO);

			// Atualizar atribuições
			require_once 'include/PHP/class/class.atribuicao_chamado.php';
			$atribuicao_chamado = new atribuicao_chamado();
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
			$atribuicao_chamado->AtualizarSituacao();

			// Incluir histórico
			$v_TXT_HISTORICO = "Chamado reprovado com a seguinte justificativa: $v_TXT_JUSTIFICATIVA";
			require_once 'include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
			$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
			$historico_chamado->setTXT_HISTORICO($v_TXT_HISTORICO);
			$historico_chamado->insert();

			// Adicionar registro de aprovação
			require_once 'include/PHP/class/class.aprovacao_chamado.php';
			$aprovacao_chamado = new aprovacao_chamado();
			$aprovacao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$aprovacao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			require_once 'include/PHP/class/class.util.php';
			$util = new util();
			$dataHoraAtual = $util->GetlocalTimeStamp();
			//$aprovacao_chamado->setDTH_PREVISTA($v_DAT_PREVISTA." ".$v_HOR_PREVISTA.":00");
			$aprovacao_chamado->setDTH_PREVISTA($dataHoraAtual);
			$aprovacao_chamado->setTXT_JUSTIFICATIVA($v_TXT_JUSTIFICATIVA);
			$aprovacao_chamado->insert();

			// Encerrar time_sheet
			require_once 'include/PHP/class/class.time_sheet.php';
			$time_sheet = new time_sheet();
			$time_sheet->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$time_sheet->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$time_sheet->FinalizarTarefa();

			// Redirecionar para a página de atendimento
			$pagina->redirectTo("ChamadoAtendimentoPesquisa.php");
		}
		$flag = "";
	}
	// ============================================================================================================
	// Aprovar o chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$mensagemErro = "";
		if($ObservacaoObrigatorio == "S"){
			if($v_TXT_JUSTIFICATIVA == ""){
			 	$vErroCampos .= "Preencha o campo observação. ";
			}
		}
		if($v_DAT_PREVISTA == ""){
		 	$vErroCampos .= "Preencha a data prevista. ";
		}
		if($v_HOR_PREVISTA == ""){
		 	$vErroCampos .= "Preencha a hora prevista. ";
		}

		if($vErroCampos == ""){
			require_once 'include/PHP/class/class.chamado.php';
			require_once 'include/PHP/class/class.situacao_chamado.php';
			require_once 'include/PHP/class/class.atividade_chamado.php';
			$chamado = new chamado();
			$situacao_chamado = new situacao_chamado();

			if($v_SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Planejamento){
				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Atendimento;
				$v_TXT_HISTORICO = "Chamado aprovado. Data prevista para término: $v_DAT_PREVISTA $v_HOR_PREVISTA:00";

				// Alterar status para em andamento
				$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $v_SEQ_SITUACAO_CHAMADO);

				// Atualizar atribuições
				require_once 'include/PHP/class/class.atribuicao_chamado.php';
				$atribuicao_chamado = new atribuicao_chamado();
				$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
				$atribuicao_chamado->AtualizarSituacao();
			}else{
				$v_TXT_HISTORICO = "Chamado reprogramado. Nova data prevista para término: $v_DAT_PREVISTA $v_HOR_PREVISTA:00";
			}

			// Incluir histórico
			require_once 'include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
			$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
			$historico_chamado->setTXT_HISTORICO($v_TXT_HISTORICO);
			$historico_chamado->insert();

			// Adicionar registro de aprovação
			require_once 'include/PHP/class/class.aprovacao_chamado.php';
			$aprovacao_chamado = new aprovacao_chamado();
			$aprovacao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$aprovacao_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$aprovacao_chamado->setDTH_PREVISTA($pagina->ConvDataAMD($v_DAT_PREVISTA)." ".$v_HOR_PREVISTA.":00");
			$aprovacao_chamado->setTXT_JUSTIFICATIVA($v_TXT_JUSTIFICATIVA);
			$aprovacao_chamado->insert();

			// Excluir as etapas selecionadas
			require_once 'include/PHP/class/class.etapa_chamado.php';
			$etapa_chamado = new etapa_chamado();
			for($i=0; $i<count($EXCLUIR_SEQ_ETAPA);$i++){
				$etapa_chamado->delete($EXCLUIR_SEQ_ETAPA[$i]);
			}

			// Alterar as etapas já existentes
			$etapa_chamado = new etapa_chamado();
			$etapa_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$etapa_chamado->selectParam("DTH_INICIO_PREVISTO");
			if($etapa_chamado->database->rows > 0){
				while ($row = pg_fetch_array($etapa_chamado->database->result)){
					if($_POST["v_NOM_ETAPA_CHAMADO_".$row["seq_etapa_chamado"]] != ""){
						$etapa_chamadoUpdate = new etapa_chamado();
						$etapa_chamadoUpdate->setNOM_ETAPA_CHAMADO($_POST["v_NOM_ETAPA_CHAMADO_".$row["seq_etapa_chamado"]]);
						$etapa_chamadoUpdate->setDTH_INICIO_PREVISTO($pagina->ConvDataAMD($_POST["v_DTH_INICIO_PREVISTO_".$row["seq_etapa_chamado"]])." ".$_POST["v_HOR_INICIO_PREVISTO_".$row["seq_etapa_chamado"]]);
						$etapa_chamadoUpdate->setDTH_FIM_PREVISTO($pagina->ConvDataAMD($_POST["v_DTH_FIM_PREVISTO_".$row["seq_etapa_chamado"]])." ".$_POST["v_HOR_FIM_PREVISTO_".$row["seq_etapa_chamado"]]);
						$etapa_chamadoUpdate->update($row["seq_etapa_chamado"]);
					}
				}
			}

			// Inserir etapas
			if(trim($aEtapaChamado) != ""){
				$a_ETAPA_CHAMADO = split(";", $aEtapaChamado);
				$etapa_chamado = new etapa_chamado();
				$etapa_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				for ($i = 0; $i < count($a_ETAPA_CHAMADO); $i++){
					// Pegar variáveis
					$aAux = split("\|", $a_ETAPA_CHAMADO[$i]);
					$v_NOM_ETAPA_CHAMADO = $aAux[0];
					$v_DTH_INICIO_PREVISTO = $aAux[1];
					$v_DTH_FIM_PREVISTO = $aAux[2];

					// Setar variáveis
					$etapa_chamado->setNOM_ETAPA_CHAMADO($v_NOM_ETAPA_CHAMADO);
					$etapa_chamado->setDTH_INICIO_PREVISTO($pagina->ConvDataAMD($v_DTH_INICIO_PREVISTO)." ".substr($v_DTH_INICIO_PREVISTO, 11, 5));
					$etapa_chamado->setDTH_FIM_PREVISTO($pagina->ConvDataAMD($v_DTH_FIM_PREVISTO)." ".substr($v_DTH_FIM_PREVISTO, 11, 5));
					$etapa_chamado->insert();
				}
			}
			
			//PARA RESOLVER OPROBLEMA DE FEcHAR O CHAADO DUAS VEZES
			// Encerrar time_sheet
			require_once 'include/PHP/class/class.time_sheet.php';
			$time_sheet = new time_sheet();
			$time_sheet->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$time_sheet->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$time_sheet->FinalizarTarefa();

			// Redirecionar para a página de atendimento
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

	require_once 'include/PHP/class/class.chamado.php';
	require_once 'include/PHP/class/class.situacao_chamado.php';
	require_once 'include/PHP/class/class.etapa_chamado.php';
	$situacao_chamado = new situacao_chamado();
	$banco = new chamado();
	$banco->select($v_SEQ_CHAMADO);

	$pagina->SettituloCabecalho("Planejar chamado"); // Indica o título do cabeçalho da página
	$pagina->method = "post";
	$ObservacaoObrigatorio = "S";
	if($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Planejamento){
		$ObservacaoObrigatorio = "N";
		$aItemAba = Array( array("#", "", "Detalhes", "onclick=\"AcessarAcao('ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
		 			       array("#", "tabact", "Planejar", "onclick=\"AcessarAcao('ChamadoAprovar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
						 );
	}elseif($_SESSION["FLG_LIDER_EQUIPE"] == "S"){ // Mostrar a opção Reprogramar
		$aItemAba = Array( array("#", "", "Detalhes", "onclick=\"AcessarAcao('ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
		 			       array("#", "", "Alterar", "onclick=\"AcessarAcao('ChamadoAlterar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
		 			       array("#", "", "Atribuir", "onclick=\"AcessarAcao('ChamadoAtribuir.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
		 			       array("#", "", "Atendimento", "onclick=\"AcessarAcao('ChamadoRegistroAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\""),
					       array("#", "", "Suspender", "onclick=\"AcessarAcao('ChamadoSuspender.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\""),
					       array("#", "", "Cancelar", "onclick=\"AcessarAcao('ChamadoCancelar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\""),
					       array("#", "", "Devolver 1º nível", "onclick=\"AcessarAcao('ChamadoDevolver1Nivel.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\""),
					       array("#", "tabact", "Reprogramar", "onclick=\"AcessarAcao('ChamadoPlanejar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\""),
					       array("#", "", "Encerrar", "onclick=\"AcessarAcao('ChamadoEncerramento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"")
						 );
	}

	$pagina->SetaItemAba($aItemAba);
	$pagina->estiloTabBar = "tabbarMenor";
	//$pagina->flagScriptCalendario = 0;

	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_CHAMADO", $v_SEQ_CHAMADO);
	print $pagina->CampoHidden("ObservacaoObrigatorio", $ObservacaoObrigatorio);
	print $pagina->CampoHidden("v_SEQ_SITUACAO_CHAMADO", $banco->SEQ_SITUACAO_CHAMADO);
	print $pagina->CampoHidden("v_DAT_ATUAL", date("d/m/Y"));

	// ============================================================================================================
	?>
	<script language="javascript">
		var aEtapaChamado = new Array();
		// =======================================================================
		// Controlar a saída às ações do chamado
		// =======================================================================
		function AcessarAcao(vDestino){
			validarSaida = false;
			window.location.href = vDestino;
		}

		function fValidaFormLocal(){
			if(document.form.v_DAT_PREVISTA.value == ""){
			 	alert("Preencha o campo Data prevista");
			 	return false;
			}
			if(!comparaDatas(document.form.v_DAT_ATUAL, document.form.v_DAT_PREVISTA)){
				alert("A data prevista não pode ser menor que a data atual.");
			 	return false;
			}
			if(document.form.v_HOR_PREVISTA.value == ""){
			 	alert("Preencha o campo hora prevista");
			 	return false;
			}
			<?
			    if($ObservacaoObrigatorio == "S"){ ?>
					if(document.form.v_TXT_JUSTIFICATIVA.value == ""){
					 	alert("Preencha o campo observação");
					 	return false;
					}
			<?  }
			// Validar campos das etapas
			$etapa_chamado = new etapa_chamado();
			$etapa_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$etapa_chamado->selectParam("DTH_INICIO_PREVISTO");
			if($etapa_chamado->database->rows > 0){
				while ($row = pg_fetch_array($etapa_chamado->database->result)){
					?>
					// ====================================================================================================================
					if(document.form.v_NOM_ETAPA_CHAMADO_<?=$row["seq_etapa_chamado"]?>.value == ""){
						alert("Todos os campos de etapas já cadastradas devem permanecer preenchidos");
						document.form.v_NOM_ETAPA_CHAMADO_<?=$row["seq_etapa_chamado"]?>.focus();
						return false;
					}
					if(document.form.v_DTH_INICIO_PREVISTO_<?=$row["seq_etapa_chamado"]?>.value == ""){
						alert("Todos os campos de datas e horas já cadastradas devem permanecer preenchidos");
						document.form.v_DTH_INICIO_PREVISTO_<?=$row["seq_etapa_chamado"]?>.focus();
						return false;
					}
					if(document.form.v_HOR_INICIO_PREVISTO_<?=$row["seq_etapa_chamado"]?>.value == ""){
						alert("Todos os campos de datas e horas já cadastradas devem permanecer preenchidos");
						document.form.v_HOR_INICIO_PREVISTO_<?=$row["seq_etapa_chamado"]?>.focus();
						return false;
					}
					if(document.form.v_DTH_FIM_PREVISTO_<?=$row["seq_etapa_chamado"]?>.value == ""){
						alert("Todos os campos de datas e horas já cadastradas devem permanecer preenchidos");
						document.form.v_DTH_FIM_PREVISTO_<?=$row["seq_etapa_chamado"]?>.focus();
						return false;
					}
					if(document.form.v_HOR_FIM_PREVISTO_<?=$row["seq_etapa_chamado"]?>.value == ""){
						alert("Todos os campos de datas e horas já cadastradas devem permanecer preenchidos");
						document.form.v_HOR_FIM_PREVISTO_<?=$row["seq_etapa_chamado"]?>.focus();
						return false;
					}
					// Validar datas
					if(!comparaDatasHora(document.form.v_DTH_INICIO_PREVISTO_<?=$row["seq_etapa_chamado"]?>.value+" "+document.form.v_HOR_INICIO_PREVISTO_<?=$row["seq_etapa_chamado"]?>.value, document.form.v_DTH_FIM_PREVISTO_<?=$row["seq_etapa_chamado"]?>.value+" "+document.form.v_HOR_FIM_PREVISTO_<?=$row["seq_etapa_chamado"]?>.value)){
						alert("A data final deve ser posterior a data inicial, na etapa "+document.form.v_NOM_ETAPA_CHAMADO_<?=$row["seq_etapa_chamado"]?>.value);
						return false;
					}
					// Validar se a data final é posterior a entrega do chamado
					if(!comparaDatasHora(document.form.v_DTH_FIM_PREVISTO_<?=$row["seq_etapa_chamado"]?>.value+" "+document.form.v_HOR_FIM_PREVISTO_<?=$row["seq_etapa_chamado"]?>.value, document.form.v_DAT_PREVISTA.value+" "+document.form.v_HOR_PREVISTA.value)){
						alert("A data final da etapa "+document.form.v_NOM_ETAPA_CHAMADO_<?=$row["seq_etapa_chamado"]?>.value+" não pode ser maior que a Data de encerramento previsto para o chamado. ");
						document.form.v_DTH_FIM_PREVISTO_<?=$row["seq_etapa_chamado"]?>.focus();
						return false;
					}
					// ====================================================================================================================
					<?
				}
			}
			?>

			// Array de etapas
			FormatarArrayInsercao(aEtapaChamado, document.form.aEtapaChamado);

			return confirm("Confirma a previsão de término?");
		}

		function fReprovar(){
			if(document.form.v_TXT_JUSTIFICATIVA.value == ""){
			 	alert("Preencha o campo observação");
			 	return false;
			}
			document.form.flag.value = "2";
			return confirm("Esta ação encerrará o atendimento da OS, finalizará o seu lançamento no Time Sheet e retornará para a tela de atendimento. \n Confirma a ação?");
		}

		function fAdicionaEtapaChamado(){
			if(document.form.v_NOM_ETAPA_CHAMADO.value == ""){
				alert("Preencha o campo Etapa");
				document.form.v_NOM_ETAPA_CHAMADO.focus();
				return;
			}
			if(document.form.v_DTH_INICIO_PREVISTO.value == ""){
				alert("Preencha o campo data de início previsto");
				document.form.v_DTH_INICIO_PREVISTO.focus();
				return;
			}
			if(document.form.v_HOR_INICIO_PREVISTO.value == ""){
				alert("Preencha o campo hora de início previsto");
				document.form.v_HOR_INICIO_PREVISTO.focus();
				return;
			}
			if(document.form.v_DTH_FIM_PREVISTO.value == ""){
				alert("Preencha o campo data de encerramento previsto");
				document.form.v_DTH_FIM_PREVISTO.focus();
				return;
			}
			if(document.form.v_HOR_DTH_FIM_PREVISTO.value == ""){
				alert("Preencha o campo hora de encerramento previsto");
				document.form.v_HOR_DTH_FIM_PREVISTO.focus();
				return;
			}

			if(!comparaDatasHora(document.form.v_DTH_INICIO_PREVISTO.value+" "+document.form.v_HOR_INICIO_PREVISTO.value, document.form.v_DTH_FIM_PREVISTO.value+" "+document.form.v_HOR_DTH_FIM_PREVISTO.value)){
				alert("A data final deve ser posterior a data inicial");
				return;
			}
			if(document.form.v_DAT_PREVISTA.value == ""){
				alert("Preencha o campo data prevista");
			 	document.form.v_DAT_PREVISTA.focus();
			 	return false;
			}
			if(document.form.v_HOR_PREVISTA.value == ""){
			 	alert("Preencha o campo hora prevista");
			 	document.form.v_HOR_PREVISTA.focus();
			 	return false;
			}
			if(!comparaDatas(document.form.v_DAT_ATUAL, document.form.v_DAT_PREVISTA)){
				alert("A data prevista não pode ser menor que a data atual.");
			 	return false;
			}
			// Verificar se a data final não está passando da data final prevista
			if(!comparaDatasHora(document.form.v_DTH_FIM_PREVISTO.value+" "+document.form.v_HOR_DTH_FIM_PREVISTO.value, document.form.v_DAT_PREVISTA.value+" "+document.form.v_HOR_PREVISTA.value)){
				alert("A data final da etapa não pode ser maior que a Data de encerramento previsto para o chamado. ");
				document.form.v_DTH_FIM_PREVISTO.focus();
				return;
			}

			v_NOM_ETAPA_CHAMADO = document.form.v_NOM_ETAPA_CHAMADO.value;
			v_DTH_INICIO_PREVISTO = document.form.v_DTH_INICIO_PREVISTO.value+" "+document.form.v_HOR_INICIO_PREVISTO.value;
			v_DTH_FIM_PREVISTO = document.form.v_DTH_FIM_PREVISTO.value+" "+document.form.v_HOR_DTH_FIM_PREVISTO.value;
			// Verificar se é item único
			if(InserirItemArray(aEtapaChamado, v_NOM_ETAPA_CHAMADO+"|"+v_DTH_INICIO_PREVISTO+"|"+v_DTH_FIM_PREVISTO) == true){
				valor = v_NOM_ETAPA_CHAMADO;
				valor1 = v_DTH_INICIO_PREVISTO;
				valor2 = v_DTH_FIM_PREVISTO;

				var tabela = document.getElementById("historico");

				// Primeiro testamos se a primeira linha nao eh a mensagem "Sem dados a serem exibidos"
				if(tabela.rows.length>1){
					if(tabela.rows[1].cells[0].innerHTML=="Sem dados a serem exibidos")
						tabela.deleteRow(1); // se for apagamos
				}

				proxLinha = tabela.rows.length; // pega o total de linhas da tabela para acrescentar a nova
				var linha = tabela.insertRow(proxLinha); // Insere uma nova linha
				var coluna1 = linha.insertCell(0);
				var coluna2 = linha.insertCell(1);
				var coluna3 = linha.insertCell(2);
				var colunaCancela = linha.insertCell(3);

				setRowIndex(aEtapaChamado, v_NOM_ETAPA_CHAMADO+"|"+v_DTH_INICIO_PREVISTO+"|"+v_DTH_FIM_PREVISTO, linha.rowIndex);
				//Abaixo inserimos o conteudo nas colunas criadas
				coluna1.innerHTML=valor;
				coluna1.setAttribute("align", "left");
				coluna2.innerHTML=valor1;
				coluna2.setAttribute("align", "center");
				coluna3.innerHTML=valor2;
				coluna3.setAttribute("align", "center");
				colunaCancela.innerHTML="<span onclick='fRetiraEtapaChamado("+linha.rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
			}else{
				alert("Etapa já incluída.");
			}

			return false;
		}

		function fRetiraEtapaChamado(id){
			var tabela = document.getElementById("historico");
			if(confirm("Tem certeza que deseja retirar a etapa?")) {
				aEtapaChamado = ExcluirItemArray(aEtapaChamado, id);
				tabela.deleteRow(id);
				if(tabela.rows.length<2){
					var linha = tabela.insertRow(1); // Insere uma nova linha
					var coluna = linha.insertCell(0); // Insere uma coluna na linha
					coluna.setAttribute("cols", 4); // Define colspan = 2
					coluna.innerHTML="Sem dados a serem exibidos"; // Texto informativo
				}else{
					// Refazer a coluna de exclusão da linha
					for(i=1;i<tabela.rows.length;i++){
							tabela.rows[i].cells[tabela.rows[i].cells.length - 1].innerHTML="<span onclick='fRetiraEtapaChamado("+tabela.rows[i].rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
               		}
	            }
			}
		}
	</script>
	<?
	if($mensagemErro != ""){
		$pagina->ScriptAlert($mensagemErro);
	}

	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Previsão de término", 2);

	$pagina->LinhaCampoFormulario("Data de encerramento previsto:", "right", "S",
									  $pagina->CampoData("v_DAT_PREVISTA", "S", "Data de término previsto", substr($banco->DTH_ENCERRAMENTO_PREVISAO,0, 10))
									  , "left", "id=".$pagina->GetIdTable(),"27%");

	$pagina->LinhaCampoFormulario("Hora de encerramento prevista:", "right", "S",
									  $pagina->CampoHora("v_HOR_PREVISTA", "S", "Hora de término prevista", substr($banco->DTH_ENCERRAMENTO_PREVISAO,11, 5))
									  , "left", "id=".$pagina->GetIdTable());

	// Descrição
	$pagina->LinhaCampoFormulario("Observação:", "right", $ObservacaoObrigatorio,
									  $pagina->CampoTextArea("v_TXT_JUSTIFICATIVA", $ObservacaoObrigatorio, "Observação", "99", "9", "", "onkeyup=\"ContaCaracteres(5000, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">5000</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormularioColspan("center", "<div align=left><font color=red>*</font> - Preenchimento obrigatório</div>", "2");

	$pagina->FechaTabelaPadrao();
	$pagina->LinhaVazia(1);
	//================================================================================================================================
	// Planejamento de etapas
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Planejamento de etapas - Não obrigatório", 2);

	//=================================================================================================================================
	// Montar formulário
	$pagina->LinhaColspan("center", "Adicionar etapas", 2, "header");
	print $pagina->CampoHidden("aEtapaChamado", "");
	$tabela = array();

	$header = array();
	$header[] = array("Etapa", "center", "40%", "header");
	$header[] = array("Início Previsto", "center", "25%", "header");
	$header[] = array("Encerramento Previsto", "center", "25%", "header");
	$header[] = array("&nbsp;", "center", "", "header");
	$tabela[] = $header;

	$header = array();
	$header[] = array($pagina->CampoTexto("v_NOM_ETAPA_CHAMADO", "N", "", "50", "60", ""), "center", "", "", "middle");
	$header[] = array("Data:".$pagina->CampoData("v_DTH_INICIO_PREVISTO", "N", "", "").
					  "&nbsp;&nbsp;Hora:".$pagina->CampoHora("v_HOR_INICIO_PREVISTO", "N", "", "")
						, "center", "", "", "middle");
	$header[] = array($pagina->CampoData("v_DTH_FIM_PREVISTO", "N", "", "").
					  "&nbsp;&nbsp;Hora:".$pagina->CampoHora("v_HOR_DTH_FIM_PREVISTO", "N", "", "")
						, "center", "", "", "middle");
	$header[] = array($pagina->CampoButton("return fAdicionaEtapaChamado();", "Adicionar", "button"), "center", "", "", "middle");
	$tabela[] = $header;
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%"), 2);

	$header = array();
	$header[] = array("Etapa", "center", "40%");
	$header[] = array("Início Previsto", "center", "25%");
	$header[] = array("Encerramento Previsto", "center", "25%");
	$header[] = array("  Excluir  ", "center", "");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->TabelaDinâmica("historico", $header, "100%"), 2);
	$header = "";

	$pagina->FechaTabelaPadrao();

	//=================================================================================================================================
	$pagina->LinhaVazia(1);
	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaColspan("center", "Etapas já adicionadas", 5, "header");
	$etapa_chamado = new etapa_chamado();
	$etapa_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$etapa_chamado->selectParam("DTH_INICIO_PREVISTO");
	if($etapa_chamado->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhuma etapa registrada", 2);
	}else{
		$tabela = array();
		$header = array();
		$header[] = array("Excluir", "center", "5%", "header");
		$header[] = array("Etapa", "center", "26%", "header");
		$header[] = array("Início previsto", "center", "20%", "header");
		$header[] = array("Início efetivo", "center", "17%", "header");
		$header[] = array("Encerramento Previsto", "center", "20%", "header");
		$header[] = array("Encerramento Efetivo", "center", "17%", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($etapa_chamado->database->result)){
			$header = array();
			$header[] = array($pagina->CampoCheckboxSimples("EXCLUIR_SEQ_ETAPA[]", $row["seq_etapa_chamado"], "", ""), "center", "", "");
			$header[] = array($pagina->CampoTexto("v_NOM_ETAPA_CHAMADO_".$row["seq_etapa_chamado"], "N", "", "50", "60", $row["nom_etapa_chamado"]), "left", "", "");
			$header[] = array("Data:".$pagina->CampoData("v_DTH_INICIO_PREVISTO_".$row["seq_etapa_chamado"], "N", "", substr($row["dth_inicio_previsto"],0,10)).
					          "&nbsp;&nbsp;Hora:".$pagina->CampoHora("v_HOR_INICIO_PREVISTO_".$row["seq_etapa_chamado"], "N", "", substr($row["dth_inicio_previsto"],11,5))
							, "center", "", "");
			$header[] = array($row["dth_inicio_efetivo"], "center", "", "campo");
			$header[] = array("Data:".$pagina->CampoData("v_DTH_FIM_PREVISTO_".$row["seq_etapa_chamado"], "N", "", substr($row["dth_fim_previsto"],0,10)).
					          "&nbsp;&nbsp;Hora:".$pagina->CampoHora("v_HOR_FIM_PREVISTO_".$row["seq_etapa_chamado"], "N", "", substr($row["dth_fim_previsto"],11,5))
							, "center", "", "");
			$header[] = array($row["dth_fim_efetivo"], "center", "", "campo");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
	}
	$pagina->FechaTabelaPadrao();

	//=================================================================================================================================
	print "<hr>";
	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	//if($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Planejamento){ // Primeira vez - O cancelamento pode ser realizado
	if(true){	
	$pagina->LinhaCampoFormularioColspan("center",
					$pagina->CampoButton("return fValidaFormLocal(); ", " Confirmar planejamento ")."&nbsp;".
					$pagina->CampoButton("return fReprovar(); ", " Reprovar ")
					, "2");
	}else{
		$pagina->LinhaCampoFormularioColspan("center",$pagina->CampoButton("return fValidaFormLocal(); ", " Aprovar ")	, "2");
	}

	$pagina->MontaRodape();
}else{
	$pagina->ScriptAlert("Selecione o chamado.");
	$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
}
?>