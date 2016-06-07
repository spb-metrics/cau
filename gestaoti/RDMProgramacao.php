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


// Configura��o da p�g�na
$pagina->SettituloCabecalho("Calend�rio de Mudan�as (PFM)"); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->MontaCabecalho();
$pagina->LinhaVazia(1);
print $pagina->CampoHidden("flag", "1");
// ============================================================================================================
// Configura��es AJAX JAVASCRIPTS
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

// Mostrar ou n�o os par�metros
//if($flag == ""){ // Mostrar par�metros
//	$MaisParametros = "style=\"display: none;\" ";
//	$MenosParametros = "";
//	$tabelaParametros = "";
//}else{ // N�o mostrar par�metros
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
//// Inicio da tabela de par�metros
//$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaParametros\" $tabelaParametros");
//$pagina->LinhaCampoFormulario("N� da RDM:", "right", "N", $pagina->CampoInt("v_SEQ_RDM", "N", "N� da RDM", "9", $v_SEQ_RDM), "left", "id=".$pagina->GetIdTable(), "20%");
//
//$pagina->LinhaCampoFormulario("Mat. solicitante:", "right", "N",
//								  $pagina->CampoTexto("v_NUM_MATRICULA_SOLICITANTE", "N", "Matr�cula do solicitante" , "10", "10", $v_NUM_MATRICULA_SOLICITANTE, "").
//								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_SOLICITANTE")
//								  , "left", "id=".$pagina->GetIdTable());
//
//$pagina->LinhaCampoFormulario("T�tulo:", "right", "N", $pagina->CampoTexto("v_TITULO", "N", "T�tulo", "80", "80", "$v_TITULO"), "left", "id=".$pagina->GetIdTable());
//
//$pagina->LinhaCampoFormulario("Respons�vel checklist:", "right", "N", $pagina->CampoTexto("v_NOME_RESP_CHECKLIST", "N", "Respons�vel checklist", "80", "80", "$v_NOME_RESP_CHECKLIST"), "left", "id=".$pagina->GetIdTable());
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
//$pagina->LinhaCampoFormulario("Situa��o:", "right", "N", $pagina->CampoSelect("v_SEQ_SITUACAO_RDM", "N", "Situa��o", "S", $aSituacao), "left", "id=".$pagina->GetIdTable());					  
//
//$pagina->LinhaCampoFormulario("Data de Abertura:", "right", "N",
//			"de ".$pagina->CampoData("v_DTH_ABERTURA", "N", " de Abertura", $v_DTH_ABERTURA)
//			." a ".$pagina->CampoData("v_DTH_ABERTURA_FINAL", "N", " de Abertura", $v_DTH_ABERTURA_FINAL)
//			, "left", "id=".$pagina->GetIdTable());
//
//$pagina->LinhaCampoFormulario("Data prevista Execu��o:", "right", "N",
//			"de ".$pagina->CampoData("v_DATA_HORA_PREVISTA_EXECUCAO", "N", " de Abertura", $v_DATA_HORA_PREVISTA_EXECUCAO)
//			." a ".$pagina->CampoData("v_DATA_HORA_PREVISTA_EXECUCAO_FINAL", "N", " de Abertura", $v_DATA_HORA_PREVISTA_EXECUCAO_FINAL)
//			, "left", "id=".$pagina->GetIdTable());
//			
//$pagina->LinhaCampoFormulario("Data de in�cio Execu��o:", "right", "N",
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
	$header[] = array("N�mero", "");
	$header[] = array("T�tulo", "");
	$header[] = array("Justificativa", "");
	$header[] = array("Tipo", "");
	//$header[] = array("Situa��o", "");	
	$header[] = array("Prevista execu��o", "");
	//$header[] = array("Respos�vel Checklist", "");
	
	// Setar vari�veis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	//$RDM->setSEQ_RDM($v_SEQ_RDM); 
	//$RDM->setTITULO($v_TITULO);
	//$RDM->setNOME_RESP_CHECKLIST($v_NOME_RESP_CHECKLIST);
	
	// Preenchendo a RDM com os campos do formul�rio
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
		$pagina->LinhaHeaderTabelaResultado("RDMs programadas para excu��o", $header);
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
			
			// N�mero
			//$corpo[] = array("right", "campo", $valor);
				
			// N�mero
			$corpo[] = array("right", "campo", $row["seq_rdm"]);			
			// T�tulo
			$corpo[] = array("left", "campo",$row["titulo"]);
			// Justificativa
			$corpo[] = array("left", "campo",$row["justificativa"]);
			// Tipo
			$corpo[] = array("left", "campo", $RDM->getTipoDescricao($row["tipo"]));
			// Situa��o
			//$corpo[] = array("left", "campo", $situacaoRDM->getDescricao($row["situacao_atual"]));
			// Abertura
			$corpo[] = array("center", "campo", $row["data_hora_prevista_execucao"]);
			// Respos�vel Checklist
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
		$pagina->LinhaColspan("center", "N�o existem RDMS programadas para os pr�ximos dias ", "2", "header");
		$pagina->LinhaColspan("left", "Nenhuma RDM encontrada", "2", "campo");
		$pagina->FechaTabelaPadrao();
	}
	 
	

}
$pagina->MontaRodape();