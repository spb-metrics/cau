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
require 'include/PHP/class/class.motivo_suspencao.php';
$pagina = new Pagina();
$banco = new motivo_suspencao();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterção de Motivo de Suspensão de Chamado"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Motivo_suspencaoPesquisa.php", "", "Pesquisa"),
						array("Motivo_suspencaoCadastro.php", "", "Adicionar"),
		 			    array("Motivo_suspencaoAlteracao.php", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_MOTIVO_SUSPENCAO);

	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("v_SEQ_MOTIVO_SUSPENCAO", $v_SEQ_MOTIVO_SUSPENCAO);
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Descrição:", "right", "S", $pagina->CampoTexto("v_DSC_MOTIVO_SUSPENCAO", "S", "Descrição", "60", "60", "$banco->DSC_MOTIVO_SUSPENCAO"), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Alterar regstro

	$banco->setDSC_MOTIVO_SUSPENCAO($v_DSC_MOTIVO_SUSPENCAO);
	$banco->update($v_SEQ_MOTIVO_SUSPENCAO);
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Motivo_suspencaoPesquisa.php?v_SEQ_MOTIVO_SUSPENCAO=$v_SEQ_MOTIVO_SUSPENCAO");
	}
}
?>
