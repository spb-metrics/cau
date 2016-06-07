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
require 'include/PHP/class/class.destino_triagem.php';
$pagina = new Pagina();
$banco = new destino_triagem();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Destino de Triagem"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Destino_triagemPesquisa.php", "", "Pesquisa"),
					   array("Destino_triagemCadastro.php", "tabact", "Adicionar"));
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");

	// Montar a combo da tabela equipe_ti
	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$pagina->LinhaCampoFormulario("Equipe ti:", "right", "S", $pagina->CampoSelect("v_SEQ_EQUIPE_TI", "S", "Equipe ti", "S", $equipe_ti->combo(2, $banco->SEQ_EQUIPE_TI)), "left");

	require_once 'include/PHP/class/class.dependencias.php';
	$dependencias = new dependencias();
	$pagina->LinhaCampoFormulario("Dependência:", "right", "S", $pagina->CampoSelect("v_COD_DEPENDENCIA", "S", "Equipe ti", "S", $dependencias->combo(2, $banco->COD_DEPENDENCIA)), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro

	$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
	$banco->insert();
	// Código inserido: $banco->
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Destino_triagemPesquisa.php");
	}
}
?>
