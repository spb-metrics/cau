<?php
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
function iif($Condicao, $Sim, $Nao){
		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
}
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.rdm.php';
require_once 'include/PHP/class/class.situacao_rdm.php';
require_once 'include/PHP/class/class.util.php';

$pagina = new Pagina();
$RDM = new rdm();
$situacaoRDM = new situacao_rdm();


// Configuração da págína
$pagina->SettituloCabecalho("Calendário de Mudanças (PFM)"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();
$pagina->LinhaVazia(1);
print $pagina->CampoHidden("flag", "1");
// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================
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

// Mostrar ou não os parâmetros
//if($flag == ""){ // Mostrar parâmetros
//	$MaisParametros = "style=\"display: none;\" ";
//	$MenosParametros = "";
//	$tabelaParametros = "";
//}else{ // Não mostrar parâmetros
//	$MaisParametros = "style=\"display: none;\" ";
//	$MenosParametros = "";
//	$tabelaParametros = "style=\"display: none;\" ";
//}
//$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisParametros\" $MaisParametros cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
//$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"imagens/mais.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Exibir Filtros de Pesquisa</a>", "left","", "3%");
//$pagina->FechaTabelaPadrao();
//
//$pagina->AbreTabelaPadrao("center", "100%", "id=\"MenosParametros\" $MenosParametros cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
//$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"imagens/menos.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Esconder Filtros de Pesquisa</a>", "left","","3%");
//$pagina->FechaTabelaPadrao();
//
//// Inicio da tabela de parâmetros
//$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaParametros\" $tabelaParametros");
//$pagina->LinhaCampoFormulario("Nº da RDM:", "right", "N", $pagina->CampoInt("v_SEQ_RDM", "N", "Nº da RDM", "9", $v_SEQ_RDM), "left", "id=".$pagina->GetIdTable(), "20%");
//
//$pagina->LinhaCampoFormulario("Mat. solicitante:", "right", "N",
//								  $pagina->CampoTexto("v_NUM_MATRICULA_SOLICITANTE", "N", "Matrícula do solicitante" , "10", "10", $v_NUM_MATRICULA_SOLICITANTE, "").
//								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_SOLICITANTE")
//								  , "left", "id=".$pagina->GetIdTable());
//
//$pagina->LinhaCampoFormulario("Título:", "right", "N", $pagina->CampoTexto("v_TITULO", "N", "Título", "80", "80", "$v_TITULO"), "left", "id=".$pagina->GetIdTable());
//
//$pagina->LinhaCampoFormulario("Responsável checklist:", "right", "N", $pagina->CampoTexto("v_NOME_RESP_CHECKLIST", "N", "Responsável checklist", "80", "80", "$v_NOME_RESP_CHECKLIST"), "left", "id=".$pagina->GetIdTable());
//								  
// 
//
//
////$aTipo = Array();
////$aTipo[0] = array($RDM->NORMAL, iif($v_SEQ_TIPO_RDM==$RDM->NORMAL, "Selected", ""), $RDM->DSC_NORMAL);
////$aTipo[1] = array($RDM->EMERGENCIAL, iif($v_SEQ_TIPO_RDM==$RDM->EMERGENCIAL, "Selected", ""), $RDM->DSC_EMERGENCIAL);
//
//$aTipo = $RDM->comboTipo($v_SEQ_TIPO_RDM);
//$pagina->LinhaCampoFormulario("Tipo:", "right", "N", $pagina->CampoSelect("v_SEQ_TIPO_RDM", "N", "Tipo", "S", $aTipo), "left", "id=".$pagina->GetIdTable());
//								  


//$aSituacao = Array();
//$aSituacao[0] = array($situacaoRDM->CRIADA, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->CRIADA, "Selected", ""), $situacaoRDM->DSC_CRIADA);
//$aSituacao[1] = array($situacaoRDM->AGUARDANDO_APROVACAO, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->AGUARDANDO_APROVACAO, "Selected", ""), $situacaoRDM->DSC_AGUARDANDO_APROVACAO);
//$aSituacao[2] = array($situacaoRDM->REPROVADA, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->APROVADA, "Selected", ""), $situacaoRDM->DSC_APROVADA);
//$aSituacao[3] = array($situacaoRDM->REPROVADA, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->REPROVADA, "Selected", ""), $situacaoRDM->DSC_REPROVADA);
//$aSituacao[4] = array($situacaoRDM->EM_PLANEJAMENTO, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->EM_PLANEJAMENTO, "Selected", ""), $situacaoRDM->DSC_EM_PLANEJAMENTO);
//$aSituacao[5] = array($situacaoRDM->PLANEJAMENTO_REPROVADO, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->PLANEJAMENTO_REPROVADO, "Selected", ""), $situacaoRDM->DSC_PLANEJAMENTO_REPROVADO);
//$aSituacao[6] = array($situacaoRDM->AGUARADANDO_EXECUCAO, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->AGUARADANDO_EXECUCAO, "Selected", ""), $situacaoRDM->DSC_AGUARADANDO_EXECUCAO);		
//$aSituacao[7] = array($situacaoRDM->EM_EXECUCAO, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->EM_EXECUCAO, "Selected", ""), $situacaoRDM->DSC_EM_EXECUCAO);
//$aSituacao[8] = array($situacaoRDM->FALHA_NA_EXECUCAO, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->FALHA_NA_EXECUCAO, "Selected", ""), $situacaoRDM->DSC_FALHA_NA_EXECUCAO);
//$aSituacao[9] = array($situacaoRDM->AGUARDANDO_VALIDACAO, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->AGUARDANDO_VALIDACAO, "Selected", ""), $situacaoRDM->DSC_AGUARDANDO_VALIDACAO);
//$aSituacao[10]= array($situacaoRDM->FINALIZADA, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->FINALIZADA, "Selected", ""), $situacaoRDM->DSC_FINALIZADA);
//$aSituacao[11]= array($situacaoRDM->CANCELADA, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->CANCELADA, "Selected", ""), $situacaoRDM->DSC_CANCELADA);
//$aSituacao[12]= array($situacaoRDM->SUSPENSA, iif($v_SEQ_SITUACAO_RDM==$situacaoRDM->SUSPENSA, "Selected", ""), $situacaoRDM->DSC_SUSPENSA);
//		
//
//$aSituacao = $situacaoRDM->combo($v_SEQ_SITUACAO_RDM);
//
//$pagina->LinhaCampoFormulario("Situação:", "right", "N", $pagina->CampoSelect("v_SEQ_SITUACAO_RDM", "N", "Situação", "S", $aSituacao), "left", "id=".$pagina->GetIdTable());					  
//
//$pagina->LinhaCampoFormulario("Data de Abertura:", "right", "N",
//			"de ".$pagina->CampoData("v_DTH_ABERTURA", "N", " de Abertura", $v_DTH_ABERTURA)
//			." a ".$pagina->CampoData("v_DTH_ABERTURA_FINAL", "N", " de Abertura", $v_DTH_ABERTURA_FINAL)
//			, "left", "id=".$pagina->GetIdTable());
//
//$pagina->LinhaCampoFormulario("Data prevista Execução:", "right", "N",
//			"de ".$pagina->CampoData("v_DATA_HORA_PREVISTA_EXECUCAO", "N", " de Abertura", $v_DATA_HORA_PREVISTA_EXECUCAO)
//			." a ".$pagina->CampoData("v_DATA_HORA_PREVISTA_EXECUCAO_FINAL", "N", " de Abertura", $v_DATA_HORA_PREVISTA_EXECUCAO_FINAL)
//			, "left", "id=".$pagina->GetIdTable());
//			
//$pagina->LinhaCampoFormulario("Data de início Execução:", "right", "N",
//			"de ".$pagina->CampoData("v_DTH_EXECUCAO", "N", " de Abertura", $v_DTH_EXECUCAO)
//			." a ".$pagina->CampoData("v_DTH_EXECUCAO_FINAL", "N", " de Abertura", $v_DTH_EXECUCAO_FINAL)
//			, "left", "id=".$pagina->GetIdTable());
//
//$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
//$pagina->FechaTabelaPadrao();

// =======================================================================================================================
// PESQUISAR RDMs
// =======================================================================================================================
$flag = "1";
if($flag == "1"){
// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	//$header[] = array("&nbsp;", "5%");
	$header[] = array("Número", "");
	$header[] = array("Título", "");
	$header[] = array("Justificativa", "");
	$header[] = array("Tipo", "");
	//$header[] = array("Situação", "");	
	$header[] = array("Prevista execução", "");
	//$header[] = array("Resposável Checklist", "");
	
	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	//$RDM->setSEQ_RDM($v_SEQ_RDM); 
	//$RDM->setTITULO($v_TITULO);
	//$RDM->setNOME_RESP_CHECKLIST($v_NOME_RESP_CHECKLIST);
	
	// Preenchendo a RDM com os campos do formulário
	//require_once 'include/PHP/class/class.empregados.oracle.php';
	//$empregados = new empregados();
	//$v_NUM_MATRICULA_SOLICITANTE = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_SOLICITANTE);
	//$RDM->setNUM_MATRICULA_SOLICITANTE($v_NUM_MATRICULA_SOLICITANTE);
	
	$RDM->setTIPO($v_SEQ_TIPO_RDM);
	//$RDM->setSITUACAO_ATUAL($situacaoRDM->APROVADA);
	//$RDM->setDATA_HORA_ABERTURA($v_DTH_ABERTURA);
	//$RDM->setDATA_HORA_ABERTURA_FINAL($v_DTH_EXECUCAO_FINAL);
	$util = new util();
	$dataHoraAtual = $util->GetlocalTimeStamp();
		
	$RDM->setDATA_HORA_PREVISTA_EXECUCAO($dataHoraAtual);
	//$RDM->setDATA_HORA_PREVISTA_EXECUCAO_FINAL($v_DATA_HORA_PREVISTA_EXECUCAO_FINAL);
	//$RDM->setDATA_HORA_INICO_EXECUCAO($v_DTH_EXECUCAO);
	//$RDM->setDATA_HORA_FIM_EXECUCAO($v_DTH_EXECUCAO_FINAL);
	
	$RDM->selectPFM("data_hora_prevista_execucao", $vNumPagina, 10);
	
	$SQL_EXPORT = $RDM->SQL_EXPORT;
	$Rows = $RDM->database->rows;
	
	if($RDM->database->rows > 0){
		$corpo = array();
		$pagina->fQuantidadeRegistros($RDM->rowCount, $RDM->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("RDMs programadas para excução", $header);
		$vLink = "?flag=1";
		$vLink .="&v_SEQ_RDM_PESQUISA=$v_SEQ_RDM";
		$vLink .="&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE";
		$vLink .="&v_TITULO=$v_TITULO";
		$vLink .="&v_NOME_RESP_CHECKLIST=$v_NOME_RESP_CHECKLIST";
		$vLink .="&v_SEQ_SITUACAO_RDM=$v_SEQ_SITUACAO_RDM";
		$vLink .="&v_DTH_ABERTURA=$v_DTH_ABERTURA";
		$vLink .="&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL";
		$vLink .="&v_DATA_HORA_PREVISTA_EXECUCAO=$v_DATA_HORA_PREVISTA_EXECUCAO";
		$vLink .="&v_DATA_HORA_PREVISTA_EXECUCAO_FINAL=$v_DATA_HORA_PREVISTA_EXECUCAO_FINAL";
		$vLink .="&v_DTH_EXECUCAO=$v_DTH_EXECUCAO";
		$vLink .="&v_DTH_EXECUCAO_FINAL=$v_DTH_EXECUCAO_FINAL";		
		
		while ($row = pg_fetch_array($RDM->database->result)){
			
			//$valor = $pagina->BotaoAlteraGridPesquisa("RDMAlteracao.php?v_SEQ_RDM=".$row["seq_rdm"]."&v_ACAO=ALTERAR");
			//$valor = $pagina->BotaoExcluiGridPesquisa("v_SEQ_RDM", $row["seq_rdm"]);
			
			// Número
			//$corpo[] = array("right", "campo", $valor);
				
			// Número
			$corpo[] = array("right", "campo", $row["seq_rdm"]);			
			// Título
			$corpo[] = array("left", "campo",$row["titulo"]);
			// Justificativa
			$corpo[] = array("left", "campo",$row["justificativa"]);
			// Tipo
			$corpo[] = array("left", "campo", $RDM->getTipoDescricao($row["tipo"]));
			// Situação
			//$corpo[] = array("left", "campo", $situacaoRDM->getDescricao($row["situacao_atual"]));
			// Abertura
			$corpo[] = array("center", "campo", $row["data_hora_prevista_execucao"]);
			// Resposável Checklist
			//$corpo[] = array("left", "campo", $row["nome_resp_checklist"]);		
			
			$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='RDMDetalhe.php".$vLink."&vNumPagina=$vNumPagina&v_SEQ_RDM=".$row["seq_rdm"]."';\"");
			$corpo = "";	 
		
		}
		
		$pagina->FechaTabelaPadrao();
		
		if($Rows > 0){
			$pagina->fMontarExportacao("RDMProgramacaoDecorator.php",$SQL_EXPORT,"RDM_CALENDARIO");
		}
		$pagina->fMontaPaginacao($RDM->rowCount, $RDM->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_RDM=$v_SEQ_RDM");
	 		
	}else{
		$pagina->LinhaColspan("center", "Não existem RDMS programadas para os próximos dias ", "2", "header");
		$pagina->LinhaColspan("left", "Nenhuma RDM encontrada", "2", "campo");
		$pagina->FechaTabelaPadrao();
	}
	 
	

}
$pagina->MontaRodape();