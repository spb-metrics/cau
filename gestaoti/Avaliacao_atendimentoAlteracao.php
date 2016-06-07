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
require 'include/PHP/class/class.avaliacao_atendimento.php';
$pagina = new Pagina();
$banco = new avaliacao_atendimento();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterção de Tipo de Avaliação Atendimento"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Avaliacao_atendimentoPesquisa.php", "", "Pesquisa"),
						array("Avaliacao_atendimentoCadastro.php", "", "Adicionar"),
		 			    array("Avaliacao_atendimentoAlteracao.php", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_AVALIACAO_ATENDIMENTO);

	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("v_SEQ_AVALIACAO_ATENDIMENTO", $v_SEQ_AVALIACAO_ATENDIMENTO);
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_AVALIACAO_ATENDIMENTO", "S", "Nome", "30", "30", "$banco->NOM_AVALIACAO_ATENDIMENTO"), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Alterar regstro

	$banco->setNOM_AVALIACAO_ATENDIMENTO($v_NOM_AVALIACAO_ATENDIMENTO);
	$banco->update($v_SEQ_AVALIACAO_ATENDIMENTO);
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Avaliacao_atendimentoPesquisa.php?v_SEQ_AVALIACAO_ATENDIMENTO=$v_SEQ_AVALIACAO_ATENDIMENTO");
	}
}
?>
