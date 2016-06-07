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
require 'include/PHP/class/class.atividade_chamado.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.subtipo_chamado.php';
require_once 'include/PHP/class/class.tipo_ocorrencia.php';
require_once	 'include/PHP/class/class.central_atendimento.php';
$pagina = new Pagina();
$banco = new atividade_chamado();
$subtipo_chamado = new subtipo_chamado();
// ================== Configurações AJAX ==========================
require 'include/PHP/class/class.Sajax.php';
$Sajax = new Sajax();

function CarregarComboTipoChamado($v_SEQ_TIPO_OCORRENCIA,$v_SEQ_CENTRAL_ATENDIMENTO){
	if($v_SEQ_TIPO_OCORRENCIA != "" || $v_SEQ_CENTRAL_ATENDIMENTO != "" ){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.tipo_chamado.php';
		$pagina = new Pagina();
		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		$tipo_chamado->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
		return $pagina->AjaxFormataArrayCombo($tipo_chamado->combo("DSC_TIPO_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboSubtipoChamado($v_SEQ_TIPO_CHAMADO){
	if($v_SEQ_TIPO_CHAMADO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.subtipo_chamado.php';
		$pagina = new Pagina();
		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
		
		return $pagina->AjaxFormataArrayCombo($subtipo_chamado->combo2("DSC_SUBTIPO_CHAMADO"));
	}else{
		return "";
	}
}

$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export("CarregarComboSubtipoChamado", "CarregarComboTipoChamado");
$Sajax->sajax_handle_client_request();
// ================================================================

// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Atividades de Chamados"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Atividade_chamadoPesquisa.php", "tabact", "Pesquisa"),
				   array("Atividade_chamadoCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_ATIVIDADE_CHAMADO);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_ATIVIDADE_CHAMADO = "";
}
// ================== Configurações AJAX JAVASCRIPTS ==========================
?>
<script language="javascript">
	<?
	$Sajax->sajax_show_javascript();
	?>
	 
	// Chamada
	function do_CarregarComboTipoChamado() {
	
		//if((document.form.v_SEQ_TIPO_OCORRENCIA.value != '') && (document.form.v_SEQ_CENTRAL_ATENDIMENTO.value != '') ){
			x_CarregarComboTipoChamado(document.form.v_SEQ_TIPO_OCORRENCIA.value, document.form.v_SEQ_CENTRAL_ATENDIMENTO.value,retorno_CarregarComboTipoChamado);
		//} 
	}
	// Retorno
	function retorno_CarregarComboTipoChamado(val) {
		fEncheComboBox(val, document.form.v_SEQ_TIPO_CHAMADO,document.form.v_SEQ_CENTRAL_ATENDIMENTO.value);
		do_CarregarComboSubtipoChamado();
	}
	// Chamada
	function do_CarregarComboSubtipoChamado() {
		x_CarregarComboSubtipoChamado(document.form.v_SEQ_TIPO_CHAMADO.value, retorno_CarregarComboSubtipoChamado);
	}
	// Retorno
	function retorno_CarregarComboSubtipoChamado(val) {
		fEncheComboBox(val, document.form.v_SEQ_SUBTIPO_CHAMADO);
	}
</script>
<style>
	#CampoSelect {
		font-family: Verdana;
		width: 480px;
		font-size: 10px;
		color: #000000;
		border-color: #000000;
		border-style: double;
		border-width: 1px;
		background-color: #f6f5f5;
	}
</style>

<?
// ===========================================================================
print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_ATIVIDADE_CHAMADO", "");

//$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

/* Inicio da tabela de parâmetros */
$pagina->AbreTabelaPadrao("center", "85%");


// Montar a combo da tabela central_atendimento
$central_atendimento = new central_atendimento(); 
$pagina->LinhaCampoFormulario("Central de Atendimento:", "right", "N", $pagina->CampoSelect("v_SEQ_CENTRAL_ATENDIMENTO", "N", "Central de Atendimento", "S", $central_atendimento->combo(2,$v_SEQ_CENTRAL_ATENDIMENTO), "Escolha", "do_CarregarComboTipoChamado()"), "left", "v_SEQ_CENTRAL_ATENDIMENTO", "30%", "70%");

// Montar a combo da tabela tipo_chamado
require_once 'include/PHP/class/class.tipo_ocorrencia.php';
$tipo_ocorrencia = new tipo_ocorrencia();
//$tipo_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").":", "right", "N", $pagina->CampoSelect("v_SEQ_TIPO_OCORRENCIA", "N", "Classe", "S", $tipo_ocorrencia->combo(1, $v_SEQ_TIPO_OCORRENCIA), "Escolha", "do_CarregarComboTipoChamado()", "CampoSelect"), "left", "", "20%");



// Montar a combo da tabela tipo_chamado
require_once 'include/PHP/class/class.tipo_chamado.php';
$tipo_chamado = new tipo_chamado();
if($v_SEQ_TIPO_OCORRENCIA != "" || $_SEQ_CENTRAL_ATENDIMENTO!=""){
	//$subtipo_chamado->select($v_SEQ_SUBTIPO_OCORRENCIA);
	//$v_SEQ_TIPO_CHAMADO = $subtipo_chamado->SEQ_TIPO_CHAMADO;
	$tipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
	$tipo_chamado->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", "right", "N", $pagina->CampoSelect("v_SEQ_TIPO_CHAMADO", "N", "Tipo chamado", "S", $tipo_chamado->combo2(2, $v_SEQ_TIPO_CHAMADO), "Escolha", "do_CarregarComboSubtipoChamado()"), "left");
}else{
	$aItemOption[] = array("", "", "Selecione o tipo de chamado");
	$tipo_chamado = new tipo_chamado();
	//$tipo_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", "right", "N", $pagina->CampoSelect("v_SEQ_TIPO_CHAMADO", "N", "Classe", "N", $aItemOption, "Escolha", "do_CarregarComboSubtipoChamado()", "CampoSelect"), "left", "", "20%");
}

// Montar a combo da tabela subtipo_chamado
if($v_SEQ_TIPO_CHAMADO != ""){
	$subtipo_chamado = new subtipo_chamado();
	$subtipo_chamado->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
	$subtipo_chamado->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	$aItemOption = $subtipo_chamado->combo2("2", $v_SEQ_SUBTIPO_CHAMADO);
}else{
	$aItemOption = Array();
	$aItemOption[] = array("", "", "Selecione o tipo de chamado");
}
$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").":", "right", "N", $pagina->CampoSelect("v_SEQ_SUBTIPO_CHAMADO", "N", "Subtipo chamado", "N", $aItemOption, "", "", "CampoSelect"), "left");

$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $pagina->CampoTexto("v_DSC_ATIVIDADE_CHAMADO", "N", "Descrição", "60", "60", ""), "left");
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "4%");
$header[] = array($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA"), "12%");
$header[] = array($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO"), "14%");
$header[] = array($pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO"), "14%");
$header[] = array("Descrição", "15%");
$header[] = array("OLA Triagem", "7%");
$header[] = array("SLA Conting.", "8%");
$header[] = array("SLA Solução", "8%");
$header[] = array("Tempo em", "8%");
//$header[] = array("Externo?", "8%");
$header[] = array("Equipe", "10%");
$header[] = array("Exige Aprovação?", "5%");

// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
$banco->setSEQ_ATIVIDADE_CHAMADO($v_SEQ_ATIVIDADE_CHAMADO);
$banco->setSEQ_SUBTIPO_CHAMADO($v_SEQ_SUBTIPO_CHAMADO);
$banco->setDSC_ATIVIDADE_CHAMADO($v_DSC_ATIVIDADE_CHAMADO);
$banco->setQTD_MIN_SLA_TRIAGEM($v_QTD_MIN_SLA_TRIAGEM);
$banco->setQTD_MIN_SLA_ATENDIMENTO($v_QTD_MIN_SLA_ATENDIMENTO);
$banco->setFLG_ATENDIMENTO_EXTERNO($v_FLG_ATENDIMENTO_EXTERNO);
$banco->setFLG_FORMA_MEDICAO_TEMPO($v_FLG_FORMA_MEDICAO_TEMPO);
//$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
$banco->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
$banco->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Atividades encontradas para os parâmentos pesquisados", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisa("Atividade_chamadoAlteracao.php?v_SEQ_ATIVIDADE_CHAMADO=".$row["seq_atividade_chamado"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_ATIVIDADE_CHAMADO", $row["seq_atividade_chamado"]);
		$corpo[] = array("center", "campo", $valor);

		// Buscar dados da tabela externa
		$tipo_ocorrencia = new tipo_ocorrencia();
		$tipo_ocorrencia->select($row["seq_tipo_ocorrencia"]);
		$corpo[] = array("left", "campo", $tipo_ocorrencia->NOM_TIPO_OCORRENCIA);

		// Buscar dados da tabela externa
		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->select($row["seq_subtipo_chamado"]);

		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);


		$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO);
		$corpo[] = array("left", "campo", $subtipo_chamado->DSC_SUBTIPO_CHAMADO);

		$corpo[] = array("left", "campo", $row["dsc_atividade_chamado"]);
		$corpo[] = array("right", "campo", $row["qtd_min_sla_triagem"]);
		$corpo[] = array("right", "campo", $row["qtd_min_sla_solucao_final"]==""&&$row["qtd_min_sla_atendimento"]!=""?"":$row["qtd_min_sla_atendimento"]);
		$corpo[] = array("right", "campo", $row["qtd_min_sla_solucao_final"]!=""?$row["qtd_min_sla_solucao_final"]:$row["qtd_min_sla_atendimento"]);
		$corpo[] = array("left", "campo", $row["qtd_min_sla_atendimento"]!=""?$pagina->iif($row["flg_forma_medicao_tempo"]=="U","Horas Úteis","Horas Corridas"):"Horas planejadas");
//		$corpo[] = array("left", "campo", $pagina->iif($row["flg_atendimento_externo"]=="S","Sim","Não"));

		// Buscar dados da tabela externa
		if($row["seq_equipe_ti"] != ""){
			require_once 'include/PHP/class/class.equipe_ti.php';
			$equipe_ti = new equipe_ti();
			$equipe_ti->select($row["seq_equipe_ti"]);
			$corpo[] = array("left", "campo", $equipe_ti->NOM_EQUIPE_TI);
		}else{
			$corpo[] = array("left", "campo", "&nbsp;");
		}


		$corpo[] = array("left", "campo",   $row["flg_exige_aprovacao"]=="1"?"Sim":"Não");

		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_DSC_ATIVIDADE_CHAMADO=$v_DSC_ATIVIDADE_CHAMADO&v_QTD_MIN_SLA_TRIAGEM=$v_QTD_MIN_SLA_TRIAGEM&v_QTD_MIN_SLA_ATENDIMENTO=$v_QTD_MIN_SLA_ATENDIMENTO&v_FLG_ATENDIMENTO_EXTERNO=$v_FLG_ATENDIMENTO_EXTERNO&v_SEQ_CENTRAL_ATENDIMENTO=$v_SEQ_CENTRAL_ATENDIMENTO");
$pagina->MontaRodape();
?>
