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
	// Realizar o cadastro do chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$mensagemErro = "";
		if($v_TXT_HISTORICO == ""){
		 	$vErroCampos .= "Preencha o campo observa��o. ";
		}

		if($vErroCampos == ""){
			require_once '../gestaoti/include/PHP/class/class.chamado.php';
			require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
			require_once '../gestaoti/include/PHP/class/class.atividade_chamado.php';
			$chamado = new chamado();
			$chamado->select($v_SEQ_CHAMADO);
			$situacao_chamado = new situacao_chamado();

			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Encerrada;

			// Atualizar atribui��o
			require_once '../gestaoti/include/PHP/class/class.atribuicao_chamado.php';
			$atribuicao_chamado = new atribuicao_chamado();
			$atribuicao_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Encerrada);
			$atribuicao_chamado->AtualizarSituacao();

			// Atualizar encerramento efetivo
			$atribuicao_chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO();

			// Alterar situa��o do chamado
			$chamado->AtualizaSituacao($v_SEQ_CHAMADO, $v_SEQ_SITUACAO_CHAMADO);

			// Alterar data de encerramento efetivo
			$chamado->AtualizaDTH_ENCERRAMENTO_EFETIVO($v_SEQ_CHAMADO);

			// Incluir hist�rico
			require_once '../gestaoti/include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
			$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
			$historico_chamado->setTXT_HISTORICO($v_TXT_HISTORICO);
			$historico_chamado->insert();

			// Redirecionar para a p�gina de avalia��o
			$pagina->redirectTo("ChamadoAcompanhar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
		}
	}
	// ============================================================================================================
	// In�cio da p�gina
	// ============================================================================================================

	// ============================================================================================================
	// Configura��o da p�g�na
	// ============================================================================================================
	$pagina->SettituloCabecalho("Encerrar atendimento do chamado"); // Indica o t�tulo do cabe�alho da p�gina
	$pagina->method = "post";

	$aItemAba = Array( array("ChamadoDetalhesCEA.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO", "", "Detalhes"),
					   array("", "tabact", "Encerrar")
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
			return confirm("Esta a��o encerrar� o atendimento do chamado. \n Confirma a a��o?");
		}

	</script>
	<?
	if($mensagemErro != ""){
		$pagina->ScriptAlert($mensagemErro);
	}

	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Observa��es sobre o encerramento", 2);

	// Descri��o
	$pagina->LinhaCampoFormulario("Observa��o:", "right", "S",
									  $pagina->CampoTextArea("v_TXT_HISTORICO", "S", "Observa��o", "99", "3", "", "onkeyup=\"ContaCaracteres(900, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">900</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormularioColspan("center", "<hr><div align=left><font color=red>*</font> - Preenchimento obrigat�rio</div>", "2");
	$pagina->LinhaCampoFormularioColspan("center",
				$pagina->CampoButton("return fValidaFormLocal(); ", " Salvar ")
				, "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	$pagina->ScriptAlert("Selecione o chamado.");
	$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
}
?>