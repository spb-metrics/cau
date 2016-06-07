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
require 'include/PHP/class/class.equipe_envolvida.php';
//require 'include/PHP/class/class.empregados.php';
require 'include/PHP/class/class.empregados.oracle.php';
$pagina = new Pagina();
$empregados = new empregados();
$banco = new equipe_envolvida();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alteração de Alocação de Profissionail"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Item_configuracaoAlocacao.php?flag=1&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO", "", "Pesquisa"),
		 			    array("#", "tabact", "Adicionar") );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Matricula do Profissional:", "right", "S",
								  $pagina->CampoTexto("v_NUM_MATRICULA_RECURSO", "S", "Matrícula do Profissional" , "11", "11", $v_NUM_MATRICULA_RECURSO, "readonly")
								  , "left");

	print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", $v_SEQ_ITEM_CONFIGURACAO);
	$pagina->LinhaCampoFormulario("Item do Parque Tecnológico:", "right", "S",
							  	  $v_NOM_ITEM_CONFIGURACAO
							  , "left");

	$pagina->LinhaCampoFormulario("Alocaçao:", "right", "S", $pagina->CampoTexto("v_VAL_PERCENT_ALOCACAO", "S", "Valor de Percent alocacao", "2", "2", $v_VAL_PERCENT_ALOCACAO), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro
	$banco->setVAL_PERCENT_ALOCACAO($v_VAL_PERCENT_ALOCACAO);
	$banco->update($empregados->GetNumeroMatricula($v_NUM_MATRICULA_RECURSO), $v_SEQ_ITEM_CONFIGURACAO);
	// Código inserido: $banco->SEQ_ITEM_CONFIGURACAO
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Item_configuracaoAlocacao.php?flag=1&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO");
	}
}
?>
