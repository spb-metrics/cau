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
require_once 'include/PHP/class/class.rdm_template.php';
 
require_once 'include/PHP/class/class.util.php';

$pagina = new Pagina();
$RDM_TEMPLATE = new rdm_template();
  
 

// Configura��o da p�g�na
$pagina->SettituloCabecalho("Pesquisa de Template de RDMs"); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->flagMenu = 0;
$pagina->flagTopo = 0;
$pagina->flagMenu = 0;

$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_ACAO", $v_ACAO);
print $pagina->CampoHidden("v_SEQ_RDM_FECHAR", $v_SEQ_RDM_FECHAR);

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
if($flag == ""){ // Mostrar par�metros
	$MaisParametros = "style=\"display: none;\" ";
	$MenosParametros = "";
	$tabelaParametros = "";
}else{ // N�o mostrar par�metros
	$MaisParametros = "style=\"display: none;\" ";
	$MenosParametros = "";
	$tabelaParametros = "style=\"display: none;\" ";
}

print $pagina->CampoHidden("vNomeFuncaoRetorno", $vNomeFuncaoRetorno);

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisParametros\" $MaisParametros cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"imagens/mais.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Exibir Filtros de Pesquisa</a>", "left","", "3%");
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MenosParametros\" $MenosParametros cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"imagens/menos.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Esconder Filtros de Pesquisa</a>", "left","","3%");
$pagina->FechaTabelaPadrao();

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaParametros\" $tabelaParametros");
  
$pagina->LinhaCampoFormulario("N� do Template de RDM:", "right", "N", $pagina->CampoInt("v_SEQ_RDM_TEMPLATE", "N", "N� do Template de RDM", "9", $v_SEQ_RDM_TEMPLATE), "left", "id=".$pagina->GetIdTable(), "20%");

$pagina->LinhaCampoFormulario("T�tulo:", "right", "N", $pagina->CampoTexto("v_TITULO", "N", "T�tulo", "80", "80", "$v_TITULO"), "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Justificativa:", "right", "N", $pagina->CampoTexto("v_JUSTIFICATIVA", "N", "Justificativa", "80", "80", "$v_JUSTIFICATIVA"), "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Impacto de n�o executar:", "right", "N", $pagina->CampoTexto("v_IMPACTO", "N", "Impacto de n�o executar", "80", "80", "$v_IMPACTO"), "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Respons�vel checklist:", "right", "N", $pagina->CampoTexto("v_NOME_RESP_CHECKLIST", "N", "Respons�vel checklist", "80", "80", "$v_NOME_RESP_CHECKLIST"), "left", "id=".$pagina->GetIdTable());
 

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();

// =======================================================================================================================
// PESQUISAR RDMs
// =======================================================================================================================
if($flag == "1"){
// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("&nbsp;", "3%");
	$header[] = array("N�mero do Template", "");
	$header[] = array("T�tulo", "");
	$header[] = array("Justificativa", "");
	$header[] = array("Impacto de n�o executar", "");	 
	$header[] = array("Respos�vel Checklist", "");
	
	// Setar vari�veis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	
	$RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($v_SEQ_RDM_TEMPLATE); 
	$RDM_TEMPLATE->setTITULO($v_TITULO);
	$RDM_TEMPLATE->setJUSTIFICATIVA($v_JUSTIFICATIVA);
	$RDM_TEMPLATE->setIMPACTO_NAO_EXECUTAR($v_IMPACTO);
	$RDM_TEMPLATE->setNOME_RESP_CHECKLIST($v_NOME_RESP_CHECKLIST);	
	 
	
	$RDM_TEMPLATE->selectParam("titulo", $vNumPagina, 10);
	
	if($RDM_TEMPLATE->database->rows > 0){
		$corpo = array();
		$pagina->fQuantidadeRegistros($RDM_TEMPLATE->rowCount, $RDM_TEMPLATE->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Templates de RDMs encontrados para os par�metros informados", $header);
		$vLink = "?flag=1";
		$vLink .="&v_SEQ_RDM_TEMPLATE=$v_SEQ_RDM_TEMPLATE";		 
		$vLink .="&v_TITULO=$v_TITULO";
		$vLink .="&v_JUSTIFICATIVA=$v_JUSTIFICATIVA";
		$vLink .="&v_IMPACTO=$v_IMPACTO";
		$vLink .="&v_NOME_RESP_CHECKLIST=$v_NOME_RESP_CHECKLIST"; 
		$valor ="";
		while ($row = pg_fetch_array($RDM_TEMPLATE->database->result)){
			
			$valor = $pagina->ButtonRetornaValorTemplateRDM($vNomeFuncaoRetorno, $row["seq_rdm_template"]);
			
			$corpo[] = array("center", "campo", $valor);
			 
			$valor ="";
			
			$Numero ="";
			
			//$Numero .="<a href=\"#\"  title=\"Detalhe da RDM de N� ". $row["seq_rdm"]."\" onclick=\"location.href='RDMDetalhe.php".$vLink."&vNumPagina=$vNumPagina&v_SEQ_RDM=".$row["seq_rdm"]."';\"> ";
			$Numero .= $row["seq_rdm_template"];
			//$Numero .="</a>";
			
			
			// N�mero
			//$corpo[] = array("right", "campo", $row["seq_rdm"]);
			$corpo[] = array("right", "campo", $Numero);			
			
			// T�tulo
			$corpo[] = array("left", "campo",$row["titulo"]);
			
			// justificativa
			$corpo[] = array("left", "campo",$row["justificativa"]);
			
			// impacto_nao_executar
			$corpo[] = array("left", "campo",$row["impacto_nao_executar"]);
		 
			// Respos�vel Checklist
			$corpo[] = array("left", "campo", $row["nome_resp_checklist"]);		
			
			//$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='RDMDetalhe.php".$vLink."&vNumPagina=$vNumPagina&v_SEQ_RDM=".$row["seq_rdm"]."';\"");
			$pagina->LinhaTabelaResultado($corpo, "", "");
			$corpo = "";	 
		
		}
		
		$pagina->FechaTabelaPadrao();
		$pagina->fMontaPaginacao($RDM_TEMPLATE->rowCount, $RDM_TEMPLATE->vQtdRegistros, $vNumPagina, $PHP_SELF."$vLink");
	 		
	}else{
		$pagina->LinhaColspan("center", "Template de RDMs encontradaos para os par�metros informados", "2", "header");
		$pagina->LinhaColspan("left", "Nenhum Template de RDM encontrado", "2", "campo");
		$pagina->FechaTabelaPadrao();
	}
	 
	

}
$pagina->MontaRodape();