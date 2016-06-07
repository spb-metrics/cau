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
require 'include/PHP/class/class.sistema_operacional.php';
$pagina = new Pagina();
$banco = new sistema_operacional();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterar Sistema Operacional"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Sistema_operacionalPesquisa.php", "", "Pesquisa"),
						array("Sistema_operacionalCadastro.php", "", "Adicionar"),
		 			    array("#", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_SISTEMA_OPERACIONAL);
	
	// Inicio do formulário
	$pagina->MontaCabecalho();
	
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_SISTEMA_OPERACIONAL", $banco->SEQ_SISTEMA_OPERACIONAL);
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_SISTEMA_OPERACIONAL", "N", "Nome", "60", "60", "$banco->NOM_SISTEMA_OPERACIONAL"), "left"); 

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape(); 
}else{
	// Alterar regstro

	$banco->setNOM_SISTEMA_OPERACIONAL($v_NOM_SISTEMA_OPERACIONAL);
	$banco->update($v_SEQ_SISTEMA_OPERACIONAL);
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Sistema_operacionalPesquisa.php?v_SEQ_SISTEMA_OPERACIONAL=$v_SEQ_SISTEMA_OPERACIONAL");
	}
}
?>
