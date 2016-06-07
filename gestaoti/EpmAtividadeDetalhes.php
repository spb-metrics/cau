<?
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
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.epm.atividades.php';

$pagina = new Pagina();
$banco = new atividades();

// Carregando detalhes de Sistemas de Informa��o
if($v_COD_PROJETO != ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Detalhes da Atividade do Projeto de TI"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("EpmPesquisa.php", "", "Pesquisa"),
					   array("EpmDetalhes.php?v_COD_PROJETO=$v_COD_PROJETO", "", "Detalhes"),
					   array("#", "tabact", "Atividade")
					 );
	$pagina->SetaItemAba($aItemAba);
	// pesquisa
	$banco->select($v_SEQ_ATIVIDADE, $v_COD_PROJETO);

	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informa��es Gerais", 2);

	$pagina->LinhaCampoFormulario("Nome:", "right", "N", $banco->COD_ATIVIDADE." ".$banco->NOM_ATIVIDADE, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("% Completa:", "right", "N", $banco->PER_COMPLETA, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("% F�sica Completa:", "right", "N", $banco->PER_FISICA_COMPLETA, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Predecessoras:", "right", "N", $banco->Predecessoras, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Sucessoras:", "right", "N", $banco->Sucessoras, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Dura��o:", "right", "N", $banco->QTD_DURACAO."h", "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("In�cio Previsto:", "right", "N", $banco->DAT_INICIO_PREVISTA, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Final Previsto:", "right", "N", $banco->DAT_FINAL_PREVISTA, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Dura��o Restante:", "right", "N", $banco->QTD_DURACAO_RESTANTE."h", "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("In�cio Cedo:", "right", "N", $banco->DAT_INICIO_CEDO, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Final Cedo:", "right", "N", $banco->DAT_FINAL_CEDO, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("In�cio Tarde:", "right", "N", $banco->DAT_INICIO_TARDE, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Final Tarde:", "right", "N", $banco->DAT_FINAL_TARDE, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Free Slack:", "right", "N", $banco->QTD_FREE_SLACK, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("In�cio Real:", "right", "N", $banco->DAT_INICIO_REAL, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Final Real:", "right", "N", $banco->DAT_FINAL_REAL, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Dura��o Real:", "right", "N", $banco->QTD_DURACAO_REAL."h", "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("In�cio Baseline:", "right", "N", $banco->DAT_INICIO_BASELINE, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Final Baseline:", "right", "N", $banco->DAT_FINAL_BASELINE, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Dura��o Baseline:", "right", "N", $banco->QTD_DURACAO_BASELINE."h", "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Cria��o:", "right", "N", $banco->DAT_CRIACAO, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("� marco?", "right", "N", $pagina->iif($banco->FLG_MARCO==0,"N�o","Sim"), "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Nome dos Recursos:", "right", "N", $banco->NOM_RECURSOS, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("�rea respons�vel:", "right", "N", $banco->SIG_AREA_RECURSO, "left", "id=".$pagina->GetIdTable(),"30%","");
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