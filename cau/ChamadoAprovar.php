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
	// Reprovar o chamado
	// ============================================================================================================
	if($flag == "2"){
		if($v_TXT_JUSTIFICATIVA == ""){
		 	$vErroCampos = "Preencha o campo observa��o. ";
		}
		if($vErroCampos == ""){
			require_once '../gestaoti/include/PHP/class/class.chamado.php';
			require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
			require_once '../gestaoti/include/PHP/class/class.atividade_chamado.php';
			$chamado = new chamado();
			$situacao_chamado = new situacao_chamado();

			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Cancelado;

			// Alterar status para em andamento
			$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $v_SEQ_SITUACAO_CHAMADO);

			// Atualizar atribui��es
			require_once '../gestaoti/include/PHP/class/class.atribuicao_chamado.php';
			$atribuicao_chamado = new atribuicao_chamado();
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Encerrada);
			$atribuicao_chamado->AtualizarSituacao();

			// Incluir hist�rico
			$v_TXT_HISTORICO = "Chamado reprovado pelo gestor com a seguinte justificativa: $v_TXT_JUSTIFICATIVA";
			require_once '../gestaoti/include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
			$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
			$historico_chamado->setTXT_HISTORICO($v_TXT_HISTORICO);
			$historico_chamado->insert();
			
			/*
			// Adicionar registro de aprova��o
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
			*/
			// Redirecionar para a p�gina de atendimento
			
			require_once '../gestaoti/include/PHP/class/class.aprovacao_chamado_superior.php';
				
			$aprovacao_chamado_superior = new aprovacao_chamado_superior();
			$aprovacao_chamado_superior->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$aprovacao_chamado_superior->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$aprovacao_chamado_superior->insert();
			
			$pagina->redirectTo("principal.php");
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
			 	$vErroCampos .= "Preencha o campo observa��o. ";
			}
		}
		/*
		if($v_DAT_PREVISTA == ""){
		 	$vErroCampos .= "Preencha a data prevista. ";
		}
		if($v_HOR_PREVISTA == ""){
		 	$vErroCampos .= "Preencha a hora prevista. ";
		}
		*/

		if($vErroCampos == ""){
			require_once '../gestaoti/include/PHP/class/class.chamado.php';
			require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
			require_once '../gestaoti/include/PHP/class/class.atividade_chamado.php';
			$chamado = new chamado();
			$chamado->select($v_SEQ_CHAMADO);
			
			$situacao_chamado = new situacao_chamado();
			
			$atividade_chamado = new atividade_chamado();
			$atividade_chamado->select($chamado->SEQ_ATIVIDADE_CHAMADO);
			
			//NOVA PARTE: APROVACAO DE ACHAMADO  
			/*
			if($chamado->FLG_DESTINACAO_CHAMADO == "1" || $chamado->FLG_DESTINACAO_CHAMADO == "" || $chamado->FLG_DESTINACAO_CHAMADO == null){ // TRIAGEM				 
				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Triagem;
				$v_NUM_PRIORIDADE_FILA = "";
				$v_SEQ_ITEM_CONFIGURACAO = "";
				$qtdMinutosEspera = $chamado->CalcularTempoEspera();
			}else 
                        */
                        if($atividade_chamado->QTD_MIN_SLA_ATENDIMENTO == ""){ // N�o tempos SLA - Fluxo de aprova��o
				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Planejamento;
				$qtdMinutosEspera = 1;
			}else{ // Temos SLA - Fluxo normal de atendimento
				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Atendimento;
			}
			 
			// Alterar da DTH_INICIO_PREVISAO
		    $chamado->setDTH_INICIO_PREVISAO($pagina->add_minutos($qtdMinutosEspera,false,"Y-m-d H:i:s"));			
			$chamado->atualizarDataInicioPrevisto($v_SEQ_CHAMADO);
			
		    // Alterar status
			$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $v_SEQ_SITUACAO_CHAMADO);

			// Atualizar atribui��es
			require_once '../gestaoti/include/PHP/class/class.atribuicao_chamado.php';
			$atribuicao_chamado = new atribuicao_chamado();
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);			 
			$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
			$atribuicao_chamado->AtualizarSituacao();
			
			//NOVA PARTE: APROVACAO DE ACHAMADO
			
			/*
			if($v_SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Planejamento){
				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Atendimento;
				$v_TXT_HISTORICO = "Chamado aprovado. Data prevista para t�rmino: $v_DAT_PREVISTA $v_HOR_PREVISTA:00";

				// Alterar status para em andamento
				$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $v_SEQ_SITUACAO_CHAMADO);

				// Atualizar atribui��es
				require_once 'include/PHP/class/class.atribuicao_chamado.php';
				$atribuicao_chamado = new atribuicao_chamado();
				$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
				$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
				$atribuicao_chamado->AtualizarSituacao();
			}else{
				$v_TXT_HISTORICO = "Chamado reprogramado. Nova data prevista para t�rmino: $v_DAT_PREVISTA $v_HOR_PREVISTA:00";
			}
			*/
			
			$v_TXT_HISTORICO = "Chamado aprovado pelo gestor com a seguinte justificativa: ".$v_TXT_JUSTIFICATIVA ;
			// Incluir hist�rico
			require_once '../gestaoti/include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
			$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
			$historico_chamado->setTXT_HISTORICO($v_TXT_HISTORICO);
			$historico_chamado->insert();

			/*
			// Adicionar registro de aprova��o
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

			// Alterar as etapas j� existentes
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
					// Pegar vari�veis
					$aAux = split("\|", $a_ETAPA_CHAMADO[$i]);
					$v_NOM_ETAPA_CHAMADO = $aAux[0];
					$v_DTH_INICIO_PREVISTO = $aAux[1];
					$v_DTH_FIM_PREVISTO = $aAux[2];

					// Setar vari�veis
					$etapa_chamado->setNOM_ETAPA_CHAMADO($v_NOM_ETAPA_CHAMADO);
					$etapa_chamado->setDTH_INICIO_PREVISTO($pagina->ConvDataAMD($v_DTH_INICIO_PREVISTO)." ".substr($v_DTH_INICIO_PREVISTO, 11, 5));
					$etapa_chamado->setDTH_FIM_PREVISTO($pagina->ConvDataAMD($v_DTH_FIM_PREVISTO)." ".substr($v_DTH_FIM_PREVISTO, 11, 5));
					$etapa_chamado->insert();
				}
			}
			*/
			
			require_once '../gestaoti/include/PHP/class/class.aprovacao_chamado_superior.php';
				
			$aprovacao_chamado_superior = new aprovacao_chamado_superior();
			$aprovacao_chamado_superior->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$aprovacao_chamado_superior->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$aprovacao_chamado_superior->insert();
			
			// Redirecionar para a p�gina de atendimento
			$pagina->redirectTo("principal.php");
		}
	}
	// ============================================================================================================
	// In�cio da p�gina
	// ============================================================================================================
	/*
	// Verificar se o profissional possui um lan�amento no Time Sheet em aberto para o chamado
	require_once 'include/PHP/class/class.time_sheet.php';
	$time_sheet = new time_sheet();
	$v_FLG_ATENDIMENTO_INICIADO = $time_sheet->VerificarInicioAtividadeChamado($v_SEQ_CHAMADO, $_SESSION["NUM_MATRICULA_RECURSO"]);
	if($v_FLG_ATENDIMENTO_INICIADO != "1"){
		// Redirecionar o profissional para a tela de atendimento
		$pagina->ScriptAlert("Inicie o atendimento do chamado antes de realizar uma a��o.");
		$pagina->redirectToJS("ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
	}
	*/
	// ============================================================================================================
	// Configura��o da p�g�na
	// ============================================================================================================

	require_once '../gestaoti/include/PHP/class/class.chamado.php';
	require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
	require_once '../gestaoti/include/PHP/class/class.etapa_chamado.php';
	$situacao_chamado = new situacao_chamado();
	$banco = new chamado();
	$banco->select($v_SEQ_CHAMADO);

	$pagina->SettituloCabecalho("Aprovar/Reprovar chamado"); // Indica o t�tulo do cabe�alho da p�gina
	$pagina->cea = 1;
	$pagina->method = "post";
	$ObservacaoObrigatorio = "S";
	
	// Abas em comum para todas as situa��es
	$aItemAba = Array(); 
	//$aItemAba[] = array("ChamadoAprovacaoPesquisa.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO", "", "Lista");				 
	$aItemAba[] = array("ChamadoAprovacaoDetalhe.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO", "", "Detalhes");		
	$aItemAba[] = array("#", "tabact", "Aprovar/Reprovar");		
		 

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
		// Controlar a sa�da �s a��es do chamado
		// =======================================================================
		function AcessarAcao(vDestino){
			validarSaida = false;
			window.location.href = vDestino;
		}

		function fValidaFormLocal(){
			
			if(document.form.v_TXT_JUSTIFICATIVA.value == ""){
			 	alert("Preencha o campo observa��o");
			 	return false;
			}
			

			return confirm("Confirma a aprova��o do chamado?");
		}

		function fReprovar(){
			if(document.form.v_TXT_JUSTIFICATIVA.value == ""){
			 	alert("Preencha o campo observa��o");
			 	return false;
			}
			document.form.flag.value = "2";
			return confirm("Esta a��o encerrar� o atendimento do chamado. \n Confirma a a��o?");
		}
 
	</script>
	<?
	if($mensagemErro != ""){
		$pagina->ScriptAlert($mensagemErro);
	}

	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	 

	// Descri��o
	$pagina->LinhaCampoFormulario("Observa��o:", "right", $ObservacaoObrigatorio,
									  $pagina->CampoTextArea("v_TXT_JUSTIFICATIVA", $ObservacaoObrigatorio, "Observa��o", "80", "3", "", "onkeyup=\"ContaCaracteres(900, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">900</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormularioColspan("center", "<div align=left><font color=red>*</font> - Preenchimento obrigat�rio</div>", "2");

	$pagina->FechaTabelaPadrao();
	$pagina->LinhaVazia(1);  

	//=================================================================================================================================
	print "<hr>";
	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	//if($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Planejamento){ // Primeira vez - O cancelamento pode ser realizado
	if(true){	
	$pagina->LinhaCampoFormularioColspan("center",
					$pagina->CampoButton("return fValidaFormLocal(); ", " Aprovar ")."&nbsp;".
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