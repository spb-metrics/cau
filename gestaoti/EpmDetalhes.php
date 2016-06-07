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
require 'include/PHP/class/class.epm.projects.php';

$pagina = new Pagina();
$banco = new projetos();

// Carregando detalhes de Sistemas de Informa��o
if($v_COD_PROJETO != ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Detalhes do Projeto de TI"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("EpmPesquisa.php", "", "Pesquisa"),
					   array("#", "tabact", "Detalhes")
					 );
	$pagina->SetaItemAba($aItemAba);
	// pesquisa
	$banco->select($v_COD_PROJETO);

	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_COD_PROJETO", $v_COD_PROJETO);
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informa��es Gerais", 2);

	$pagina->LinhaCampoFormulario("Nome:", "right", "N", $banco->NOM_PROJETO, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("�rea Executante:", "right", "N", $banco->NOM_EXECUTENTE, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("�rea Cliente:", "right", "N", $banco->SIG_DEMANDANTE, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("L�der:", "right", "N", $banco->NOM_LIDER, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Nome reduzido:", "right", "N", $banco->NOM_PROJETO_REDUZIDO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Tipo:", "right", "N", $banco->TIP_PROJETO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Status:", "right", "N", $banco->STA_PROJETO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Sinalizador de Atraso:", "right", "N", $banco->FLG_ATRASO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Sinalizador de Andamento:", "right", "N", $banco->FLG_ANDAMENTO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Data da �ltima publica��o:", "right", "N", $banco->DAT_ULTIMA_PUBLICACAO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Dura��o:", "right", "N", $banco->QTD_DURACAO, "left", "id=".$pagina->GetIdTable());

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
	$aItemAba = Array(
			array("javascript: fMostra('tabelaAtividades','tabAlocacao')", "tabact", "&nbsp;Atividades&nbsp;", "tabAtividades"),
 			     );
	$pagina->SetaItemAba($aItemAba);
	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");

	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAlocacao cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Tarefas do Projeto", 2);
	$vConteudo="<iframe src=\"EpmPesquisaAtividades.php?v_COD_PROJETO=".$v_COD_PROJETO."\" width=\"100%\" height=\"300\" scrolling=\"auto\" frameborder=\"0\"></iframe>	";
	$pagina->LinhaCampoFormularioColspanDestaque($vConteudo, 2);
	$pagina->FechaTabelaPadrao();

}else{
	$pagina->mensagem("Selecione um projeto");
}

?>