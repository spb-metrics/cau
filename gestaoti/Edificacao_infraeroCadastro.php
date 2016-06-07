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
require 'include/PHP/class/class.edificacao.php';
$pagina = new Pagina();
$banco = new edificacao();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Edificação"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Edificacao_infraeroPesquisa.php", "", "Pesquisa"),
						array("Edificacao_infraeroCadastro.php", "tabact", "Adicionar"));
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_EDIFICACAO", "S", "Nome", "60", "60", ""), "left");

	/* Montar a combo
	require 'include/PHP/class/class.dependencias.php';
	$dependencias = new dependencias();
	$pagina->LinhaCampoFormulario("Dependência:", "right", "S", $pagina->CampoSelect("v_COD_DEPENDENCIA", "S", "Dependência", "N", $dependencias->combo(2, $_SESSION["COD_DEPENDENCIA"])), "left", "v_COD_DEPENDENCIA", "30%", "70%");
	*/

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{

	$banco->setNOM_EDIFICACAO($v_NOM_EDIFICACAO);
	$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
	$banco->selectParam();
	if($banco->database->rows == 0){
		// Incluir regstro
		$banco->setNOM_EDIFICACAO($v_NOM_EDIFICACAO);
		$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
		$banco->insert();
		// Código inserido: $banco->SEQ_EDIFICACAO
		if($banco->error != ""){
			$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
		}else{
			$pagina->redirectTo("Edificacao_infraeroPesquisa.php?flag=1&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA");
		}
	}else{
		$pagina->mensagem("Registro não incluído. Já existe uma edificação com o mesmo nome registrada para a dependência selecionada.");
	}
}
?>
