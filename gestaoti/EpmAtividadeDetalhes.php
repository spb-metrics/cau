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
require 'include/PHP/class/class.epm.atividades.php';

$pagina = new Pagina();
$banco = new atividades();

// Carregando detalhes de Sistemas de Informação
if($v_COD_PROJETO != ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Detalhes da Atividade do Projeto de TI"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("EpmPesquisa.php", "", "Pesquisa"),
					   array("EpmDetalhes.php?v_COD_PROJETO=$v_COD_PROJETO", "", "Detalhes"),
					   array("#", "tabact", "Atividade")
					 );
	$pagina->SetaItemAba($aItemAba);
	// pesquisa
	$banco->select($v_SEQ_ATIVIDADE, $v_COD_PROJETO);

	// Inicio do formulário
	$pagina->MontaCabecalho();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais", 2);

	$pagina->LinhaCampoFormulario("Nome:", "right", "N", $banco->COD_ATIVIDADE." ".$banco->NOM_ATIVIDADE, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("% Completa:", "right", "N", $banco->PER_COMPLETA, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("% Física Completa:", "right", "N", $banco->PER_FISICA_COMPLETA, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Predecessoras:", "right", "N", $banco->Predecessoras, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Sucessoras:", "right", "N", $banco->Sucessoras, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Duração:", "right", "N", $banco->QTD_DURACAO."h", "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Início Previsto:", "right", "N", $banco->DAT_INICIO_PREVISTA, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Final Previsto:", "right", "N", $banco->DAT_FINAL_PREVISTA, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Duração Restante:", "right", "N", $banco->QTD_DURACAO_RESTANTE."h", "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Início Cedo:", "right", "N", $banco->DAT_INICIO_CEDO, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Final Cedo:", "right", "N", $banco->DAT_FINAL_CEDO, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Início Tarde:", "right", "N", $banco->DAT_INICIO_TARDE, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Final Tarde:", "right", "N", $banco->DAT_FINAL_TARDE, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Free Slack:", "right", "N", $banco->QTD_FREE_SLACK, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Início Real:", "right", "N", $banco->DAT_INICIO_REAL, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Final Real:", "right", "N", $banco->DAT_FINAL_REAL, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Duração Real:", "right", "N", $banco->QTD_DURACAO_REAL."h", "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Início Baseline:", "right", "N", $banco->DAT_INICIO_BASELINE, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Final Baseline:", "right", "N", $banco->DAT_FINAL_BASELINE, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Duração Baseline:", "right", "N", $banco->QTD_DURACAO_BASELINE."h", "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Criação:", "right", "N", $banco->DAT_CRIACAO, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("É marco?", "right", "N", $pagina->iif($banco->FLG_MARCO==0,"Não","Sim"), "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Nome dos Recursos:", "right", "N", $banco->NOM_RECURSOS, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Área responsável:", "right", "N", $banco->SIG_AREA_RECURSO, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Schedule Performance Index - SPI:", "right", "N", $banco->SPI, "left", "id=".$pagina->GetIdTable(),"30%","");
	?>
	<script language="javascript">

		function fMostra(id, idTab){
	/*
			document.getElementById("tabelaAlocacao").style.display = "none";
			document.getElementById("tabAlocacao").attributes["class"].value = "";

			document.getElementById("tabelaEquipe").style.display = "none";
			document.getElementById("tabEquipe").attributes["class"].value = "";

			document.getElementById("tabelaItemConfiguracao").style.display = "none";
			document.getElementById("tabItemConfiguracao").attributes["class"].value = "";

			document.getElementById(id).style.display = "block";
			document.getElementById(idTab).attributes["class"].value = "tabact";
	*/
		}

	</script>
	<?
//	$aItemAba = Array(
//			array("javascript: fMostra('tabelaAtividades','tabAlocacao')", "tabact", "&nbsp;Atividades&nbsp;", "tabAtividades"),
// 			     );
//	$pagina->SetaItemAba($aItemAba);
//	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");
	$pagina->FechaTabelaPadrao();
/*
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAlocacao cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Tarefas do Projeto", 2);
	$vConteudo="<iframe src=\"EpmPesquisaAtividades.php?v_COD_PROJETO=".$v_COD_PROJETO."\" width=\"100%\" height=\"300\" scrolling=\"auto\" frameborder=\"0\"></iframe>	";
	$pagina->LinhaCampoFormularioColspanDestaque($vConteudo, 2);
	$pagina->FechaTabelaPadrao();
*/
}else{
	$pagina->mensagem("Selecione uma atividade");
}

?>