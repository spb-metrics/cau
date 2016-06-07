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
require 'include/PHP/class/class.tipo_chamado.php';
$pagina = new Pagina();
$banco = new tipo_chamado();



if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterção de Classe de Chamados"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Tipo_chamadoPesquisa.php", "", "Pesquisa"),
				  array("Tipo_chamadoCadastro.php", "", "Adicionar"),
		 		  array("Tipo_chamadoAlteracao.php", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_TIPO_CHAMADO);

	// Inicio do formulário
	$pagina->MontaCabecalho();
	
	
	print $pagina->CampoHidden("v_SEQ_TIPO_CHAMADO", $v_SEQ_TIPO_CHAMADO);
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Descrição:", "right", "S", $pagina->CampoTexto("v_DSC_TIPO_CHAMADO", "S", "Descrição", "60", "60", "$banco->DSC_TIPO_CHAMADO"), "left");
	$pagina->LinhaCampoFormulario("Atendimento externo?", "right", "S", $pagina->CampoSelect("v_FLG_ATENDIMENTO_EXTERNO", "S", "Indicador de Atendimento externo", "S", $pagina->comboSimNao($banco->FLG_ATENDIMENTO_EXTERNO)), "left");
	$pagina->LinhaCampoFormulario("Utilizado no SLA?", "right", "S", $pagina->CampoSelect("v_UTILIZADO_SLA", "S", "Indicador de utilização no SLA", "S", $pagina->comboSimNao($banco->FLG_UTILIZADO_SLA)), "left");
									  
	// Montar a combo
	require 'include/PHP/class/class.central_atendimento.php';
	$central_atendimento = new central_atendimento(); 
	$pagina->LinhaCampoFormulario("Central de Atendimento:", "right", "S", $pagina->CampoSelect("v_SEQ_CENTRAL_ATENDIMENTO", "S", "Central de Atendimento", "S", $central_atendimento->combo(2,$banco->SEQ_CENTRAL_ATENDIMENTO)), "left", "v_SEQ_CENTRAL_ATENDIMENTO", "30%", "70%");
	
	
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	$pagina->autentica();
	
	// Alterar regstro
	//$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];
	
	$banco->setDSC_TIPO_CHAMADO($v_DSC_TIPO_CHAMADO);
	$banco->setFLG_ATENDIMENTO_EXTERNO($v_FLG_ATENDIMENTO_EXTERNO);
	$banco->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
	//$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	$banco->setFLG_UTILIZADO_SLA($v_UTILIZADO_SLA);
	$banco->update($v_SEQ_TIPO_CHAMADO);
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Tipo_chamadoPesquisa.php?v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO");
	}
}
?>
