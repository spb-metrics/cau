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
require 'include/PHP/class/class.subtipo_chamado.php';
require_once 'include/PHP/class/class.tipo_chamado.php'; 
require 'include/PHP/class/class.central_atendimento.php';

// ============================================================================================================
// Configurações AJAX
// ============================================================================================================
require_once 'include/PHP/class/class.Sajax.php';
$Sajax = new Sajax();

function CarregarComboTipoChamado($v_SEQ_CENTRAL_ATENDIMENTO){
	if($v_SEQ_CENTRAL_ATENDIMENTO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.tipo_chamado.php';
		$pagina = new Pagina();
		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
		return $pagina->AjaxFormataArrayCombo($tipo_chamado->combo2("DSC_TIPO_CHAMADO"));
	}else{
		return "";
	}
}

$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export("CarregarComboTipoChamado");
$Sajax->sajax_handle_client_request();

$pagina = new Pagina();
$banco = new subtipo_chamado();
$tipo_chamado = new tipo_chamado();
$central_atendimento = new central_atendimento();
 
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Subclasse de Chamado"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Subtipo_chamadoPesquisa.php", "", "Pesquisa"),
						array("Subtipo_chamadoCadastro.php", "tabact", "Adicionar"));
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	
	$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];
// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================
?>
<script language="javascript">
	<?
	$Sajax->sajax_show_javascript();
	?>
	// Chamada
	function do_CarregarComboTipoChamado() {
		x_CarregarComboTipoChamado(document.form.v_SEQ_CENTRAL_ATENDIMENTO.value, retorno_CarregarComboTipoChamado);
	}
	// Retorno
	function retorno_CarregarComboTipoChamado(val) {
		fEncheComboBox(val, document.form.v_SEQ_TIPO_CHAMADO);		 
	}
</script>
<?

	$pagina->AbreTabelaPadrao("center", "85%");
	//$pagina->LinhaCampoFormulario("Central de Atendimento:", "right", "S", $pagina->CampoSelect("v_SEQ_CENTRAL_ATENDIMENTO", "S", "Central de Atendimento", "S", $central_atendimento->combo(2,$v_SEQ_CENTRAL_ATENDIMENTO), "Escolha", "do_CarregarComboTipoChamado()"), "left", "v_SEQ_CENTRAL_ATENDIMENTO", "30%", "70%");
	require_once 'include/PHP/class/class.tipo_chamado.php';
	$tipo_chamado = new tipo_chamado();
	$tipo_chamado->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO); 
	
	$pagina->LinhaCampoFormulario("Classe de Chamado:", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_CHAMADO", "S", "Tipo chamado", "S", $tipo_chamado->combo2(2, $v_SEQ_TIPO_CHAMADO)), "left");
	$pagina->LinhaCampoFormulario("Descrição:", "right", "S", $pagina->CampoTexto("v_DSC_SUBTIPO_CHAMADO", "S", "Descrição", "60", "60", ""), "left");
	$pagina->LinhaCampoFormulario("Atendimento externo?", "right", "S", $pagina->CampoSelect("v_FLG_ATENDIMENTO_EXTERNO", "S", "Indicador de Atendimento externo", "S", $pagina->comboSimNao("")), "left");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Pesquisar o tipo de chamado
	$tipo_chamado->select($v_SEQ_TIPO_CHAMADO);
	if($tipo_chamado->FLG_ATENDIMENTO_EXTERNO == "N" && $v_FLG_ATENDIMENTO_EXTERNO == "S"){
		$pagina->mensagem("<br><br><br><br>Não é possível cadastrar a subclasse como 'Atendimento Externo' pois a classe de Chamado informado é de atendimento interno<br><br>Clique <a href='Subtipo_chamadoCadastro.php'>aqui</a> para retornar");
	}else{
		// Verificar se já existem um registro com a mesma descrição
		$banco->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
		$banco->setDSC_SUBTIPO_CHAMADO($v_DSC_SUBTIPO_CHAMADO);
		$banco->selectParam();
		if($banco->database->rows == 0){
			// Incluir regstro
			$banco->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
			$banco->setDSC_SUBTIPO_CHAMADO($v_DSC_SUBTIPO_CHAMADO);
			$banco->setFLG_ATENDIMENTO_EXTERNO($v_FLG_ATENDIMENTO_EXTERNO);
			$banco->insert();
			// Código inserido: $banco->SEQ_SUBTIPO_CHAMADO
			if($banco->error != ""){
				$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
			}else{
				$pagina->redirectTo("Subtipo_chamadoPesquisa.php?v_SEQ_SUBTIPO_CHAMADO=$banco->SEQ_SUBTIPO_CHAMADO");
			}
		}else{
			$pagina->mensagem("Registro não incluído. Já existe uma subclasse com a mesma descrição, cadastrado para o tipo de chamado selecionado.");
		}
	}
}
?>
