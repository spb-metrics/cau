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
require 'include/PHP/class/class.tipo_chamado.php';
$pagina = new Pagina();
$banco = new tipo_chamado();



if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alter��o de Classe de Chamados"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Tipo_chamadoPesquisa.php", "", "Pesquisa"),
				  array("Tipo_chamadoCadastro.php", "", "Adicionar"),
		 		  array("Tipo_chamadoAlteracao.php", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_TIPO_CHAMADO);

	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	
	
	print $pagina->CampoHidden("v_SEQ_TIPO_CHAMADO", $v_SEQ_TIPO_CHAMADO);
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Descri��o:", "right", "S", $pagina->CampoTexto("v_DSC_TIPO_CHAMADO", "S", "Descri��o", "60", "60", "$banco->DSC_TIPO_CHAMADO"), "left");
	$pagina->LinhaCampoFormulario("Atendimento externo?", "right", "S", $pagina->CampoSelect("v_FLG_ATENDIMENTO_EXTERNO", "S", "Indicador de Atendimento externo", "S", $pagina->comboSimNao($banco->FLG_ATENDIMENTO_EXTERNO)), "left");
	$pagina->LinhaCampoFormulario("Utilizado no SLA?", "right", "S", $pagina->CampoSelect("v_UTILIZADO_SLA", "S", "Indicador de utiliza��o no SLA", "S", $pagina->comboSimNao($banco->FLG_UTILIZADO_SLA)), "left");
									  
	// Montar a combo
	require 'include/PHP/class/class.central_atendimento.php';
	$central_atendimento = new central_atendimento(); 
	$pagina->LinhaCampoFormulario("Central de Atendimento:", "right", "S", $pagina->CampoSelect("v_SEQ_CENTRAL_ATENDIMENTO", "S", "Central de Atendimento", "S", $central_atendimento->combo(2,$banco->SEQ_CENTRAL_ATENDIMENTO)), "left", "v_SEQ_CENTRAL_ATENDIMENTO", "30%", "70%");
	
	
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	$pagina->autentica();
	
	// Alterar regstro
	//$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];
	
	$banco->setDSC_TIPO_CHAMADO($v_DSC_TIPO_CHAMADO);
	$banco->setFLG_ATENDIMENTO_EXTERNO($v_FLG_ATENDIMENTO_EXTERNO);
	$banco->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
	//$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	$banco->setFLG_UTILIZADO_SLA($v_UTILIZADO_SLA);
	$banco->update($v_SEQ_TIPO_CHAMADO);
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Tipo_chamadoPesquisa.php?v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO");
	}
}
?>
