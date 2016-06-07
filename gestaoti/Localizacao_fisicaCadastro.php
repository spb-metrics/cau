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
require 'include/PHP/class/class.localizacao_fisica.php';
$pagina = new Pagina();
$banco = new localizacao_fisica();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Localização Física"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Localizacao_fisicaPesquisa.php", "", "Pesquisa"),
						array("Localizacao_fisicaCadastro.php", "tabact", "Adicionar"));
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");

	// Montar a combo da tabela edificacao
	require_once 'include/PHP/class/class.edificacao.php';
	$edificacao = new edificacao();
	$pagina->LinhaCampoFormulario("Edificação:", "right", "S", $pagina->CampoSelect("v_SEQ_EDIFICACAO", "S", "Edificacao infraero", "S", $edificacao->combo("NO_DEPENDENCIA, NOM_EDIFICACAO", $banco->SEQ_EDIFICACAO)), "left");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_LOCALIZACAO_FISICA", "S", "Nome", "60", "60", ""), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro

	$banco->setSEQ_EDIFICACAO($v_SEQ_EDIFICACAO);
	$banco->setNOM_LOCALIZACAO_FISICA($v_NOM_LOCALIZACAO_FISICA);
	$banco->insert();
	// Código inserido: $banco->SEQ_LOCALIZACAO_FISICA
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Localizacao_fisicaPesquisa.php?flag=1&v_SEQ_LOCALIZACAO_FISICA=$banco->SEQ_LOCALIZACAO_FISICA");
	}
}
?>
