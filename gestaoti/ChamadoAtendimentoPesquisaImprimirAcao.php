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
require_once 'include/PHP/class/class.chamado.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.subtipo_chamado.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.prioridade_chamado.php';
require_once 'include/PHP/class/class.atribuicao_chamado.php';
require_once 'include/PHP/class/class.time_sheet.php';
require_once 'include/PHP/class/class.tipo_ocorrencia.php';

$pagina = new Pagina();
//$pagina->SettituloCabecalho("Reportar erro"); // Indica o título do cabeçalho da página
$pagina->flagTopo = 0;
$pagina->flagMenu = 0;
$pagina->flagScriptCalendario = 0;
$pagina->lightbox = 0;
$pagina->target = "_blank";
$pagina->method = "post";
$pagina->action = "ChamadoAtendimentoPesquisaImprimirPDF.php";
$pagina->SettituloCabecalho("Selecione os chamados que deseja imprimir");
$pagina->MontaCabecalho();

$tipo_chamado = new tipo_chamado();
$subtipo_chamado = new subtipo_chamado();
$situacao_chamado = new situacao_chamado();
$pagina = new Pagina();
$banco = new chamado();
$empregados = new empregados();
$prioridade_chamado = new prioridade_chamado();
$atribuicao_chamado = new atribuicao_chamado();
$time_sheet = new time_sheet();
$pagina->ForcaAutenticacao();

?>
<script language="javascript">
	function fValidaFormLocal(){
		var name = "imprimir[]";
		var frm = document.form;
		var aux = 0;
		// Verificar se algum assunto foi selecionado
		for(i=0; i < frm.length; i++){
	        //Verifica se o elemento do formulário corresponde a um checkbox e se é o checkbox desejado
	        if (frm.elements[i].type == "checkbox" &&  frm.elements[i].name == name ) {
                //Verifica se o checkbox foi selecionado
                if(frm.elements[i].checked) {
                    return true;
                }
	        }
	    }
	    alert("Selecione pelo menos um chamado.");
	    return false;
	}
</script>
<?
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "2%");
$header[] = array($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA"), "6%");
$header[] = array("Chamado", "6%");
$header[] = array("Solicitação", "20%");
$header[] = array("Abertura", "10%");
$header[] = array("Vencimento", "10%");
$header[] = array("SLA", "5%");

// Setar variáveis
if($v_EXIBIR == "MEUS_CHAMADOS"){
	$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->CODS_EM_ANDAMENTO);
	$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$banco->setNUM_MATRICULA_EXECUTOR($_SESSION["NUM_MATRICULA_RECURSO"]);
	$banco->AtenderChamados("DTH_ABERTURA");
}elseif($v_EXIBIR == "EQUIPE"){
	$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->CODS_EM_ANDAMENTO);
	$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$banco->setNUM_MATRICULA_NAO_EXECUTOR($_SESSION["NUM_MATRICULA_RECURSO"]);
	$banco->AtenderChamadosDistinct("DTH_ABERTURA");
}elseif($v_EXIBIR == "Aguardando_Atendimento"){
	$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->CODS_EM_ANDAMENTO);
	$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$banco->setNUM_MATRICULA_NAO_EXECUTOR("NENHUM");
	$banco->AtenderChamados("DTH_ABERTURA");
}elseif($v_EXIBIR == "AAPROVACAO"){
	$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Planejamento);
	$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$banco->AtenderChamadosDistinct("DTH_ABERTURA");
}

if($banco->database->rows > 0){
	$corpo = array();
	$pagina->LinhaHeaderTabelaResultado("", $header);

	while ($row = pg_fetch_array($banco->database->result)){
		$corpo[] = array("left", "campo", $pagina->CampoCheckboxSimples("imprimir[]", $row["seq_chamado"], "", ""));

		// Tipo
		$tipo_ocorrencia = new tipo_ocorrencia();
		$tipo_ocorrencia->select($row["seq_tipo_ocorrencia"]);
		$corpo[] = array("left", "campo", strlen($tipo_ocorrencia->NOM_TIPO_OCORRENCIA)<15?$tipo_ocorrencia->NOM_TIPO_OCORRENCIA:substr($tipo_ocorrencia->NOM_TIPO_OCORRENCIA, 0, strpos($tipo_ocorrencia->NOM_TIPO_OCORRENCIA, " ")));

		// Verificar se o chamado está em atendimento no momento
		$v_FLG_ATENDIMENTO_INICIADO = $time_sheet->VerificarInicioAtividadeChamado($row["seq_chamado"], $_SESSION["NUM_MATRICULA_RECURSO"]);

		// Chamado
		if($v_FLG_ATENDIMENTO_INICIADO == "1"){ // Em Atendimento
			$corpo[] = array("right", "campo", "<font color=red>".$row["seq_chamado"]."</font>");
		}else{
			$corpo[] = array("right", "campo", $row["seq_chamado"]);
		}

		// Atividade
		//$subtipo_chamado = new subtipo_chamado();
		//$subtipo_chamado->select($row["seq_subtipo_chamado"]);
		//$tipo_chamado = new tipo_chamado();
		//$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
		//$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);

		// Solicitação
		$corpo[] = array("left", "campo", $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

		// Situação
		//$situacao_chamado = new situacao_chamado();
		//$situacao_chamado->select($row["seq_situacao_chamado"]);
		//$corpo[] = array("left", "campo", $situacao_chamado->DSC_SITUACAO_CHAMADO);

		// abertura
		$corpo[] = array("center", "campo", $row["dth_abertura"]);

		// Recuperar dados do SLA
		$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
		//$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"]);
		$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

		// Vencimento
		$corpo[] = array("center", "campo", "<font color=".$pagina->CorSLA($v_COD_SLA_ATENDIMENTO).">".$v_DTH_ENCERRAMENTO_PREVISAO."</font>");

		// SLA
		$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));

		$pagina->LinhaTabelaResultado($corpo, "", "");
		$corpo = "";
	}
}else{
	$pagina->LinhaColspan("center", "Chamados sob minha responsabilidade", "2", "header");
	$pagina->LinhaColspan("left", "Nenhum chamado encontrado", "2", "campo");
}
$pagina->FechaTabelaPadrao();
print "<hr>";
$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
$pagina->LinhaCampoFormularioColspan("center",
			$pagina->CampoButton("return fValidaFormLocal(); ", " Imprimir ")
			, "2");
$pagina->FechaTabelaPadrao();

$pagina->MontaRodape();
?>