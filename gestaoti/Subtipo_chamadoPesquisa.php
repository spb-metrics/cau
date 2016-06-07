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
//require 'include/PHP/class/class.central_atendimento.php';


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
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa de Subclasse de Chamado"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Subtipo_chamadoPesquisa.php", "tabact", "Pesquisa"),
				   array("Subtipo_chamadoCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_SUBTIPO_CHAMADO);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_SUBTIPO_CHAMADO = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_SUBTIPO_CHAMADO", "");

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


/* Inicio da tabela de parâmetros */
$pagina->AbreTabelaPadrao("center", "85%");
// Montar a combo da tabela tipo_chamado
require_once 'include/PHP/class/class.tipo_chamado.php';
$tipo_chamado = new tipo_chamado();

if($v_SEQ_TIPO_CHAMADO!=null && $v_SEQ_TIPO_CHAMADO!=""){
	$tipo_chamado->select($v_SEQ_TIPO_CHAMADO);
	//$v_SEQ_CENTRAL_ATENDIMENTO =  $tipo_chamado->SEQ_CENTRAL_ATENDIMENTO;
}

if($_SEQ_CENTRAL_ATENDIMENTO!=null && $_SEQ_CENTRAL_ATENDIMENTO!=""){
	$tipo_chamado->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO); 
}


// Montar a combo da tabela central_atendimento
//$central_atendimento = new central_atendimento(); 
//$pagina->LinhaCampoFormulario("Central de Atendimento:", "right", "N", $pagina->CampoSelect("v_SEQ_CENTRAL_ATENDIMENTO", "N", "Central de Atendimento", "S", $central_atendimento->combo(2,$v_SEQ_CENTRAL_ATENDIMENTO), "Escolha", "do_CarregarComboTipoChamado()"), "left", "v_SEQ_CENTRAL_ATENDIMENTO", "30%", "70%");

//if(($v_SEQ_TIPO_CHAMADO!=null && $v_SEQ_TIPO_CHAMADO!="")||($_SEQ_CENTRAL_ATENDIMENTO!=null && $_SEQ_CENTRAL_ATENDIMENTO!="")){
	$pagina->LinhaCampoFormulario("Classe de Chamado:", "right", "N", $pagina->CampoSelect("v_SEQ_TIPO_CHAMADO", "N", "Tipo chamado", "S", $tipo_chamado->combo2(2, $v_SEQ_TIPO_CHAMADO)), "left");
//}else{
	//$pagina->LinhaCampoFormulario("Classe de Chamado:", "right", "N", $pagina->CampoSelect("v_SEQ_TIPO_CHAMADO", "N", "Tipo chamado", "S",null), "left");
	
//}


$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $pagina->CampoTexto("v_DSC_SUBTIPO_CHAMADO", "N", "Descrição", "60", "60", ""), "left");
$pagina->LinhaCampoFormulario("Atendimento externo?", "right", "N", $pagina->CampoSelect("v_FLG_ATENDIMENTO_EXTERNO", "N", "Indicador de Atendimento externo", "S", $pagina->comboSimNao($banco->FLG_ATENDIMENTO_EXTERNO)), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();

$flag = "1";

if($flag == "1"){
	$pagina->LinhaVazia(1);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("&nbsp;", "5%");
	$header[] = array("Classe de Chamado", "25%");
	$header[] = array("Descrição", "");
	$header[] = array("Atendimento externo?", "20%");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setSEQ_SUBTIPO_CHAMADO($v_SEQ_SUBTIPO_CHAMADO);
	$banco->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
	$banco->setDSC_SUBTIPO_CHAMADO($v_DSC_SUBTIPO_CHAMADO);
	$banco->setFLG_ATENDIMENTO_EXTERNO($v_FLG_ATENDIMENTO_EXTERNO);
	$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	$banco->selectParam("2", $vNumPagina);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Subclasses de Chamados encontradas para os parâmentos pesquisados", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			$valor = $pagina->BotaoAlteraGridPesquisa("Subtipo_chamadoAlteracao.php?v_SEQ_SUBTIPO_CHAMADO=".$row["seq_subtipo_chamado"]."");
			$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_SUBTIPO_CHAMADO", $row["seq_subtipo_chamado"]);
			$valor .= $pagina->BotaoLupa("Atividade_chamadoPesquisa.php?flag=1&v_SEQ_SUBTIPO_CHAMADO=".$row["seq_subtipo_chamado"], "Ver Atividades Relacionadas");
			$corpo[] = array("center", "campo", $valor);
			// Buscar dados da tabela externa
			require_once 'include/PHP/class/class.tipo_chamado.php';
			$tipo_chamado = new tipo_chamado();
			$tipo_chamado->select($row["seq_tipo_chamado"]);
			$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO);
			$corpo[] = array("left", "campo", $row["dsc_subtipo_chamado"]);
			$corpo[] = array("left", "campo", $pagina->iif($row["flg_atendimento_externo"]=="S","Sim","Não"));
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_DSC_SUBTIPO_CHAMADO=$v_DSC_SUBTIPO_CHAMADO&v_FLG_ATENDIMENTO_EXTERNO=$v_FLG_ATENDIMENTO_EXTERNO");
}

$pagina->MontaRodape();
?>
