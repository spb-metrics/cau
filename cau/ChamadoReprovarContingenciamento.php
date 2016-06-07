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
		if($v_TXT_HISTORICO == ""){
		 	$vErroCampos .= "Preencha o campo motivo. ";
		}

		if($vErroCampos == ""){
			require_once '../gestaoti/include/PHP/class/class.chamado.php';
			require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
			require_once '../gestaoti/include/PHP/class/class.atividade_chamado.php';
			$chamado = new chamado();
			$chamado->select($v_SEQ_CHAMADO);
			$situacao_chamado = new situacao_chamado();

			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Atendimento;

			// Alterar situação do chamado
			$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $v_SEQ_SITUACAO_CHAMADO);

			// Atualizar atribuições - caso o chamado esteja sendo reaberto
			require_once '../gestaoti/include/PHP/class/class.atribuicao_chamado.php';
			$atribuicao_chamado = new atribuicao_chamado();
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Atendimento);
			$atribuicao_chamado->ReabrirChamado();

			// Incluir histórico
			require_once '../gestaoti/include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
			$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
			$historico_chamado->setTXT_HISTORICO("Contingenciamento reprovado pelo cliente. Motivo descrito: ".$v_TXT_HISTORICO);
			$historico_chamado->insert();

			// Redirecionar para a página de avaliação
			$pagina->ScriptAlert("Reprovação registrada com sucesso.");
			$pagina->redirectToJS("ChamadoDetalhesCEA.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
		}
	}
	// ============================================================================================================
	// Início da página
	// ============================================================================================================

	// ============================================================================================================
	// Configuração da págína
	// ============================================================================================================
	$pagina->SettituloCabecalho("Reprovar contingenciamento do chamado"); // Indica o título do cabeçalho da página
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
			if(document.form.v_TXT_HISTORICO.value == ""){
			 	alert("Preencha o campo motivo");
			 	return false;
			}
			return confirm("Confirma a ação?");
		}

	</script>
	<?
	if($mensagemErro != ""){
		$pagina->ScriptAlert($mensagemErro);
	}

	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Reprovar contingenciamento do chamado", 2);

	// Descrição
	$pagina->LinhaCampoFormulario("<span id=\"obrigatorio\"><font color=red>* </font>Observação:</span>
								   <span id=\"nao_obrigatorio\">Motivo:</span>
								  ", "right", "N",
									  $pagina->CampoTextArea("v_TXT_HISTORICO", "N", "Observação", "95", "3", "", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable());


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