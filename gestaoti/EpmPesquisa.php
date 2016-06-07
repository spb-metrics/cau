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
require 'include/PHP/class/class.epm.projects.php';

$pagina = new Pagina();
$banco = new projetos();

// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Projetos de TI - EPM"); // Indica o título do cabeçalho da página
// Itens das abas
//$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
//			);
//$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_COD_PROJETO", "");

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

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisParametros\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"imagens/mais.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Exibir Parâmetros</a>", "left","", "5%");
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MenosParametros\" style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"imagens/menos.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Esconder Parâmetros</a>", "left","","5%");
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "85%", "id=\"tabelaParametros\" style=\"display: none;\" ");
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_PROJETO", "N", "Nome", "60", "60", $v_NOM_PROJETO), "left","","30%");
$pagina->LinhaCampoFormulario("Área Executante:", "right", "N",
								  $pagina->CampoTexto("v_SIG_EXECUTENTE", "N", "Unidade Organizacional Executante", "10", "10", $v_SIG_EXECUTENTE, "").
								  $pagina->ButtonProcuraUorg("v_SIG_EXECUTENTE", "TI") , "left");

$pagina->LinhaCampoFormulario("Área Cliente:", "right", "N",
								  $pagina->CampoTexto("v_SIG_DEMANDANTE", "N", "Unidade Organizacional cliente", "10", "10", $v_SIG_DEMANDANTE, "").
								  $pagina->ButtonProcuraUorg("v_SIG_DEMANDANTE", "") , "left");

$pagina->LinhaCampoFormulario("Líder:", "right", "N", $pagina->CampoTexto("v_NOM_LIDER", "N", "Nome", "60", "60", $v_NOM_LIDER), "left","","30%");

$v_FLG_ANDAMENTO = $pagina->iif($v_FLG_ANDAMENTO == "","Em Andamento",$v_FLG_ANDAMENTO);
$aItemOption = Array();
$aItemOption[] = array("Concluido", $pagina->iif($v_FLG_ANDAMENTO == "Concluido", "selected",""), "Concluido");
$aItemOption[] = array("Em Andamento", $pagina->iif($v_FLG_ANDAMENTO == "Em Andamento", "selected",""), "Em Andamento");
$aItemOption[] = array("Interrompido", $pagina->iif($v_FLG_ANDAMENTO == "Interrompido", "selected",""), "Interrompido");
$aItemOption[] = array("Suspenso", $pagina->iif($v_FLG_ANDAMENTO == "Suspenso", "selected",""), "Suspenso");
$pagina->LinhaCampoFormulario("Sinalizador de Andamento", "right", "N", $pagina->CampoSelect("v_FLG_ANDAMENTO", "N", "", "S", $aItemOption), "left");

$aItemOption = Array();
$aItemOption[] = array("Linha de Base Inexistente", $pagina->iif($v_STA_PROJETO == "Linha de Base Inexistente", "selected",""), "Linha de Base Inexistente");
$aItemOption[] = array("Projeto em Dia", $pagina->iif($v_STA_PROJETO == "Projeto em Dia", "selected",""), "Projeto em Dia");
$aItemOption[] = array("Projeto Atrasado", $pagina->iif($v_STA_PROJETO == "Projeto Atrasado", "selected",""), "Projeto Atrasado");
$pagina->LinhaCampoFormulario("Status", "right", "N", $pagina->CampoSelect("v_STA_PROJETO", "N", "", "S", $aItemOption), "left");

$aItemOption = Array();
$aItemOption[] = array("Linha de Base Inexistente", $pagina->iif($v_FLG_ATRASO == "Linha de Base Inexistente", "selected",""), "Linha de Base Inexistente");
$aItemOption[] = array("Alterado para Data Posterior", $pagina->iif($v_FLG_ATRASO == "Alterado para Data Posterior", "selected",""), "Alterado para Data Posterior");
$aItemOption[] = array("Sem Alteracao na Data de Termino Prevista", $pagina->iif($v_FLG_ATRASO == "Sem Alteracao na Data de Termino Prevista", "selected",""), "Sem Alteracao na Data de Termino Prevista");
$pagina->LinhaCampoFormulario("Sinalizador de Atraso", "right", "N", $pagina->CampoSelect("v_FLG_ATRASO", "N", "", "S", $aItemOption), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");

$pagina->FechaTabelaPadrao();

// ==========================================================================================================================
// Início dos resultados
// ==========================================================================================================================
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("Nome", "34%");
$header[] = array("Exec.", "7%");
$header[] = array("Cliente", "7%");
$header[] = array("Status", "15%");
$header[] = array("Sinal. Atraso", "15%");
$header[] = array("Sinal. Andamento", "15%");

// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);

$banco->setCOD_PROJETO($v_COD_PROJETO);
$banco->setNOM_PROJETO($v_NOM_PROJETO);
if($v_SIG_EXECUTENTE == ""){
	$banco->setSIG_EXECUTENTE("TI");
}else{
	$banco->setSIG_EXECUTENTE($v_SIG_EXECUTENTE);
}

$banco->setSIG_DEMANDANTE($v_SIG_DEMANDANTE);
$banco->setNOM_LIDER($v_NOM_LIDER);
$banco->setNOM_PROJETO_REDUZIDO($v_NOM_PROJETO_REDUZIDO);
$banco->setTIP_PROJETO($v_TIP_PROJETO);
$banco->setSTA_PROJETO($v_STA_PROJETO);
$banco->setFLG_ATRASO($v_FLG_ATRASO);
$banco->setFLG_ANDAMENTO($v_FLG_ANDAMENTO);
$banco->selectParam("PROJ_NAME", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum projeto encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Projetos encontrados para os parâmentos pesquisados", $header);
	$cont = 0;
	while ($row = odbc_fetch_array($banco->database->result)){
		$corpo[] = array("left", "campo", $row["NOM_PROJETO"]);
		$corpo[] = array("left", "campo", $row["NOM_EXECUTENTE"]);
		$corpo[] = array("left", "campo", $row["SIG_DEMANDANTE"]);
		$corpo[] = array("left", "campo", $row["STA_PROJETO"]);
		$corpo[] = array("left", "campo", $row["FLG_ATRASO"]);
		$corpo[] = array("left", "campo", $row["FLG_ANDAMENTO"]);
		$pagina->LinhaTabelaResultado($corpo, $cont, "style=\"cursor: pointer;\" onclick=\"location.href='EpmDetalhes.php?v_COD_PROJETO=".$row["COD_PROJETO"]."';\"");
		$corpo = "";
		$cont++;
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?v_COD_PROJETO=$v_COD_PROJETO&v_NOM_PROJETO=$v_NOM_PROJETO&v_SIG_EXECUTENTE=$v_SIG_EXECUTENTE&v_SIG_DEMANDANTE=$v_SIG_DEMANDANTE&v_NOM_LIDER=$v_NOM_LIDER&v_NOM_PROJETO_REDUZIDO=$v_NOM_PROJETO_REDUZIDO&v_TIP_PROJETO=$v_TIP_PROJETO&v_STA_PROJETO=$v_STA_PROJETO&v_FLG_ATRASO=$v_FLG_ATRASO&v_FLG_ANDAMENTO=$v_FLG_ANDAMENTO");

$pagina->MontaRodape();
?>