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
require 'include/PHP/class/class.central_atendimento.php';
$pagina = new Pagina();
$banco = new central_atendimento();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterar Central de Atendimento"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Central_AtendimentoPesquisa.php", "", "Pesquisa"),
						array("Central_AtendimentoCadastro.php", "", "Adicionar"),
		 			    array("#", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_CENTRAL_ATENDIMENTO);
	
	// Inicio do formulário
	$pagina->MontaCabecalho();
	
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_CENTRAL_ATENDIMENTO", $banco->SEQ_CENTRAL_ATENDIMENTO);
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_CENTRAL_ATENDIMENTO", "S", "Nome", "60", "60", "$banco->NOM_CENTRAL_ATENDIMENTO"), "left"); 

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape(); 
}else{
	// Alterar regstro

	$banco->setNOM_CENTRAL_ATENDIMENTO($v_NOM_CENTRAL_ATENDIMENTO);
	$banco->update($v_SEQ_CENTRAL_ATENDIMENTO);
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Central_AtendimentoPesquisa.php?v_SEQ_CENTRAL_ATENDIMENTO=$v_SEQ_CENTRAL_ATENDIMENTO");
	}
}
?>
