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
require 'include/PHP/class/class.feriado.php';
$pagina = new Pagina();
$banco = new feriado();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alteração de feriado"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("FeriadoPesquisa.php", "", "Pesquisa"),
                           array("FeriadoCadastro.php", "", "Adicionar"),
		 	   array("#", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();
        
        $banco->select($v_SEQ_FERIADO);
        
	print $pagina->CampoHidden("v_SEQ_FERIADO", $v_SEQ_FERIADO);
        print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_FERIADO", "S", "Nome", "60", "60", $banco->NOM_FERIADO), "left");
        $pagina->LinhaCampoFormulario("Data:", "right", "S", $pagina->CampoData("v_DTH_FERIADO", "S", "Nome", $pagina->ConvDataDMA($banco->DTH_FERIADO,"/")), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro

	$banco->setNOM_FERIADO($v_NOM_FERIADO);
        $banco->setDTH_FERIADO($pagina->ConvDataAMD($v_DTH_FERIADO));
	$banco->update($v_SEQ_FERIADO);
	// Código inserido: $banco->SEQ_FERIADO
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("FeriadoPesquisa.php?v_SEQ_FERIADO=$banco->SEQ_FERIADO");
	}
}
?>
