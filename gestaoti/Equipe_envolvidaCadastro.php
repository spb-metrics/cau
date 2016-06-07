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
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.equipe_envolvida.php';
//require 'include/PHP/class/class.empregados.php';
require 'include/PHP/class/class.empregados.oracle.php';
$pagina = new Pagina();
$empregados = new empregados();
$banco = new equipe_envolvida();
if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de  Equipe_envolvida"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Item_configuracaoAlocacao.php?flag=1&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO", "", "Pesquisa"),
		 			    array("#", "tabact", "Adicionar") );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formul�rio
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Matricula do Profissional:", "right", "S",
								  $pagina->CampoTexto("v_NUM_MATRICULA_RECURSO", "S", "Matr�cula do Profissional" , "11", "11", $v_NUM_MATRICULA_RECURSO, "").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_RECURSO", "TI")
								  , "left");


	print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", "");
	print $pagina->CampoHidden("v_SEQ_TIPO_ITEM_CONFIGURACAO", "");
	$pagina->LinhaCampoFormulario("Ativo de TI:", "right", "S",
				$pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO", "N", "" , "40", "40", $v_NOM_ITEM_CONFIGURACAO, "readonly").
				$pagina->ButtonProcuraItemConfiguracao("v_NOM_ITEM_CONFIGURACAO", "v_SEQ_ITEM_CONFIGURACAO", "v_SEQ_TIPO_ITEM_CONFIGURACAO", 2)
				, "left", "", "30%", "70%");

	$pagina->LinhaCampoFormulario("Aloca�ao:", "right", "S", $pagina->CampoTexto("v_VAL_PERCENT_ALOCACAO", "S", "Valor de Percent alocacao", "2", "2", ""), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro
	$banco->setNUM_MATRICULA_RECURSO($empregados->GetNumeroMatricula($v_NUM_MATRICULA_RECURSO));
	$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
	$banco->setVAL_PERCENT_ALOCACAO($v_VAL_PERCENT_ALOCACAO);
	$banco->insert();
	// C�digo inserido: $banco->SEQ_ITEM_CONFIGURACAO
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Item_configuracaoAlocacao.php?flag=1&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO");
	}
}
?>
