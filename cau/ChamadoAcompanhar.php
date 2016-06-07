<?php
/*
Copyright 2011 da EMBRATUR
�Este arquivo � parte do programa CAU - Central de Atendimento ao Usu�rio
�O CAU � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela 
 Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
�Este programa � distribu�do na esperan�a que possa ser� �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer� 
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
�Observe no diret�rio gestaoti/install/ a c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licensa_uso.htm". 
 Se preferir acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software 
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA� 02110-1301, USA
*/
require_once '../gestaoti/include/PHP/class/class.pagina.php';
require_once '../gestaoti/include/PHP/class/class.chamado.php';
require_once '../gestaoti/include/PHP/class/class.atividade_chamado.php';
require_once '../gestaoti/include/PHP/class/class.subtipo_chamado.php';

$pagina = new Pagina();
$banco = new chamado();
$atividade_chamado = new atividade_chamado();
$subtipo_chamado = new subtipo_chamado();

// Configura��o da p�g�na
$pagina->cea = 1;
$pagina->SettituloCabecalho("Acompanhar Chamados"); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->MontaCabecalho();

// Inicio da tabela de par�metros

?>
<script language="javascript">
	function fExibirParametros(){
		if(document.getElementById("tabelaParametros").style.display == "none"){
			document.getElementById("tabelaParametros").style.display = "block";
			document.getElementById("MaisParametros").style.display = "none";
			document.getElementById("MenosParametros").style.display = "block";
		}else{
			document.getElementById("tabelaParametros").style.display = "none";
			document.getElementById("MaisParametros").style.display = "block";
			document.getElementById("MenosParametros").style.display = "none";
		}
	}
</script>
<?

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisParametros\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"../gestaoti/imagens/mais.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Exibir Filtros de Pesquisa</a>", "left","", "3%");
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MenosParametros\" style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"../gestaoti/imagens/menos.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Esconder Filtros de Pesquisa</a>", "left","","3%");
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "85%", "id=\"tabelaParametros\" style=\"display: none;\" ");
// Montar a combo da tabela situacao_chamado
require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
$situacao_chamado = new situacao_chamado();
$pagina->LinhaCampoFormulario("Situa��o:", "right", "N", $pagina->CampoSelect("v_SEQ_SITUACAO_CHAMADO", "N", "Situacao chamado", "S", $situacao_chamado->combo(2, $v_SEQ_SITUACAO_CHAMADO)), "left");
$pagina->LinhaCampoFormulario("Solicita��o:", "right", "N", $pagina->CampoTexto("v_TXT_CHAMADO", "N", "Descri��o", "60", "60", $v_TXT_CHAMADO), "left");
$pagina->LinhaCampoFormulario("Data de Abertura:", "right", "N",
			"de ".$pagina->CampoData("v_DTH_ABERTURA", "N", " de Abertura", $v_DTH_ABERTURA)
			." a ".$pagina->CampoData("v_DTH_ABERTURA_FINAL", "N", " de Abertura", $v_DTH_ABERTURA_FINAL)
			, "left");
$pagina->LinhaCampoFormulario("Data de T�rmino:", "right", "N",
			"de ".$pagina->CampoData("v_DTH_ENCERRAMENTO_EFETIVO", "N", " de Encerramento efetivo", $v_DTH_ENCERRAMENTO_EFETIVO)
			." a ".$pagina->CampoData("v_DTH_ENCERRAMENTO_EFETIVO_FINAL", "N", " de Encerramento efetivo", $v_DTH_ENCERRAMENTO_EFETIVO_FINAL)
			, "left");

/*
// Montar a combo da tabela item_configuracao
require_once 'include/PHP/class/class.item_configuracao.php';
$item_configuracao = new item_configuracao();
$pagina->LinhaCampoFormulario("Item configuracao:", "right", "N", $pagina->CampoSelect("v_SEQ_ITEM_CONFIGURACAO", "N", "Item configuracao", "S", $item_configuracao->combo(2, $v_SEQ_ITEM_CONFIGURACAO)), "left");
*/

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();

//$header[] = array("", "5%"); 
$header[] = array("Chamado", "10%");
$header[] = array("Descri��o", "26%");
$header[] = array("Situa��o", "15%");
$header[] = array("Abertura", "16%");
$header[] = array("Previs�o T�rmino", "16%");
$header[] = array("T�rmino Efetivo", "16%");

// Setar vari�veis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
//$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
$banco->setNUM_MATRICULA_SOLICITANTE($_SESSION["NUM_MATRICULA_RECURSO"]);
$banco->setNUM_MATRICULA_CONTATO($_SESSION["NUM_MATRICULA_RECURSO"]);
$banco->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
$banco->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
$banco->setTXT_CHAMADO($v_TXT_CHAMADO);
$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);
$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);

$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
$banco->AcompanharChamados($vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Hist�ricos dos Meus Chamados ", $header);
	while ($row = pg_fetch_array($banco->database->result)){
	
		$atividade_chamado->select($row["seq_atividade_chamado"]);
		$subtipo_chamado->select($atividade_chamado->SEQ_SUBTIPO_CHAMADO);
		/*
		if($atividade_chamado->SEQ_TIPO_OCORRENCIA == $pagina->SEQ_TIPO_OCORRENCIA_SOLICITACAO && $subtipo_chamado->SEQ_TIPO_CHAMADO ==$pagina->SEQ_CLASSE_CHAMADO_TRANSPORTE ){
			//$pagina->LinhaColspan("center", "<br><a target=\"XXX\" href=\"RelatorioRequisicaoTransporteParaServicoPopup.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO\">Clique aqui para imprimir a Requisi��o de Transporte. </a>", "2", "");
			$corpo[] = array("center", "campo", "<a  title=\"Imprimir Requisi��o de Transporte\" target=\"XXX\" href=\"RelatorioRequisicaoTransporteParaServicoPopup.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."\">Requisi��o de Transporte</a>");
		}else{
			$corpo[] = array("center", "campo", "&nbsp;");
		}
		*/

//		$corpo[] = array("right", "campo", $row["seq_chamado"]);
		$corpo[] = array("right", "campo","<a  title=\"Detalhar chamado\" href=\"ChamadoDetalhesCEA.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."\">".$row["seq_chamado"]."</a>");
		
		$corpo[] = array("left", "campo", strlen($row["txt_chamado"])>200?substr($row["txt_chamado"],0,200)."...":$row["txt_chamado"]);
		//$corpo[] = array("left", "campo", $row["dsc_atividade_chamado"]);

		// Buscar dados da tabela externa
		require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
		$situacao_chamado = new situacao_chamado();
		$situacao_chamado->select($row["seq_situacao_chamado"]);
		$corpo[] = array("left", "campo", $situacao_chamado->DSC_SITUACAO_CHAMADO);

		$corpo[] = array("center", "campo", $row["dth_abertura"]);

		$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
		//$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"]);
		$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

		$corpo[] = array("center", "campo", $v_DTH_ENCERRAMENTO_PREVISAO);
		$corpo[] = array("center", "campo", $row["dth_encerramento_efetivo"]);
		//$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoDetalhesCEA.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
		$pagina->LinhaTabelaResultado($corpo, "", " ");
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_CHAMADO=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_TRIAGEM_EFETIVA=$v_DTH_TRIAGEM_EFETIVA&v_DTH_INICIO_PREVISAO=$v_DTH_INICIO_PREVISAO&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_AGENDAMENTO=$v_DTH_AGENDAMENTO&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO&v_NUM_PRIORIDADE_FILA=$v_NUM_PRIORIDADE_FILA");
$pagina->MontaRodape();
?>
