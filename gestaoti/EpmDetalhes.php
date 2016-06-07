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

// Carregando detalhes de Sistemas de Informação
if($v_COD_PROJETO != ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Detalhes do Projeto de TI"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("EpmPesquisa.php", "", "Pesquisa"),
					   array("#", "tabact", "Detalhes")
					 );
	$pagina->SetaItemAba($aItemAba);
	// pesquisa
	$banco->select($v_COD_PROJETO);

	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_COD_PROJETO", $v_COD_PROJETO);
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais", 2);

	$pagina->LinhaCampoFormulario("Nome:", "right", "N", $banco->NOM_PROJETO, "left", "id=".$pagina->GetIdTable(),"30%","");
	$pagina->LinhaCampoFormulario("Área Executante:", "right", "N", $banco->NOM_EXECUTENTE, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Área Cliente:", "right", "N", $banco->SIG_DEMANDANTE, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Líder:", "right", "N", $banco->NOM_LIDER, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Nome reduzido:", "right", "N", $banco->NOM_PROJETO_REDUZIDO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Tipo:", "right", "N", $banco->TIP_PROJETO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Status:", "right", "N", $banco->STA_PROJETO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Sinalizador de Atraso:", "right", "N", $banco->FLG_ATRASO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Sinalizador de Andamento:", "right", "N", $banco->FLG_ANDAMENTO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Data da última publicação:", "right", "N", $banco->DAT_ULTIMA_PUBLICACAO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Duração:", "right", "N", $banco->QTD_DURACAO, "left", "id=".$pagina->GetIdTable());

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