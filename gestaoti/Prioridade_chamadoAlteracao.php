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
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.prioridade_chamado.php';
$pagina = new Pagina();
$banco = new prioridade_chamado();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterção de Prioridade de Chamados"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Prioridade_chamadoPesquisa.php", "", "Pesquisa"),
						array("Prioridade_chamadoCadastro.php", "", "Adicionar"),
		 			    array("Prioridade_chamadoAlteracao.php", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_PRIORIDADE_CHAMADO);

	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("v_SEQ_PRIORIDADE_CHAMADO", $v_SEQ_PRIORIDADE_CHAMADO);
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Descrição:", "right", "S", $pagina->CampoTexto("v_DSC_PRIORIDADE_CHAMADO", "S", "Descrição", "60", "60", "$banco->DSC_PRIORIDADE_CHAMADO"), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Alterar regstro

	$banco->setDSC_PRIORIDADE_CHAMADO($v_DSC_PRIORIDADE_CHAMADO);
	$banco->update($v_SEQ_PRIORIDADE_CHAMADO);
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Prioridade_chamadoPesquisa.php?v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO");
	}
}
?>
