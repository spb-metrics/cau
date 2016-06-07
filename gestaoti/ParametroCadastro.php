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
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.parametro.php';
$pagina = new Pagina();
$banco = new parametro();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Parâmetro do Sistema"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("ParametroPesquisa.php", "", "Pesquisa"),
		 			    array("#", "tabact", "Adicionar") );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Código:", "right", "S", $pagina->CampoTexto("v_COD_PARAMETRO", "S", "Nome", "30", "60", ""), "left");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_PARAMETRO", "S", "Nome", "60", "60", ""), "left");
	$pagina->LinhaCampoFormulario("Valor:", "right", "S", $pagina->CampoTexto("v_VAL_PARAMETRO", "S", "Nome", "30", "1500", ""), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro

	$banco->setCOD_PARAMETRO($v_COD_PARAMETRO);
	$banco->setNOM_PARAMETRO($v_NOM_PARAMETRO);
	$banco->setVAL_PARAMETRO($v_VAL_PARAMETRO);
	$banco->insert();
	// Código inserido: $banco->SEQ_PARAMETRO
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("ParametroPesquisa.php?v_SEQ_PARAMETRO=$banco->SEQ_PARAMETRO");
	}
}
?>
