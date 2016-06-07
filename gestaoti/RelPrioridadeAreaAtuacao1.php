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
// =======================================================================
// P�gina de pesquisa de exclus�o de registros da tabela Item_configuracao
// P�gina gerada pelo sistema GeraPHP - 14/03/2008 09:21:56
// =======================================================================
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.item_configuracao.php';
require 'include/PHP/class/class.unidades_organizacionais.php';
require 'include/PHP/class/class.prioridade.php';
$PRIORIDADE = new PRIORIDADE();
$pagina = new Pagina();
$banco = new item_configuracao();
$item_configuracao = new item_configuracao();
$unidades_organizacionais = new unidades_organizacionais();
// Configura��o da p�g�na
$pagina->SettituloCabecalho("Prioridades dos Projetos de Sistemas de Informa��o"); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");

if($flag == "1"){
	$v_COD_UOR = $unidades_organizacionais->GetUorCodigo($v_UOR_SIGLA);
	if($v_COD_UOR == ""){
		$pagina->ScriptAlert("Unidade Organizacional Inv�lida");
		$flag = "";
	}
}

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("TI Regional:", "right", "S",
								   $pagina->CampoTexto("v_UOR_SIGLA", "S", "TI Regional", "10", "10", $pagina->iif($v_UOR_SIGLA=="", "TISI",$v_UOR_SIGLA)).
								  $pagina->ButtonProcuraUorg("v_UOR_SIGLA", "TI")
								  , "left");
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
if($flag == "1"){
	$pagina->LinhaVazia(1);
	$pagina->AbreTabelaResultado("center", "100%");

	$header[] = array("Prioridade/Total", "35%");

	$banco->selectAreasAtuacao($v_UOR_SIGLA);
	while ($rowAreaAtuacao = oci_fetch_array($banco->database->result, OCI_BOTH)){
		$header[] = array($rowAreaAtuacao[0], "");
	}
	$header[] = array("Total", "");

	$pagina->LinhaHeaderTabelaResultado("Distribui��o de Prioridade para os Projetos da $v_UOR_SIGLA", $header);
	$PRIORIDADE->selectParam("2");
	while ($row = oci_fetch_array($PRIORIDADE->database->result, OCI_BOTH)){
		$corpo[] = array("center", "campo", $row["NOM_PRIORIDADE"]);

		$valorTotal = 0;
		$item_configuracao->selectAreasAtuacao($v_UOR_SIGLA);
		while ($rowAreaAtuacao = oci_fetch_array($item_configuracao->database->result, OCI_BOTH)){
			$valor = $banco->selectQuantidadeItensPorAreaAtuacao($rowAreaAtuacao[0], $row[0], $v_UOR_SIGLA);
			$valorTotal += $valor;
			//print "<br>area '$rowAreaAtuacao[0]' - Criticidade '$row[0]' -  valor '$valor' ";
			$corpo[] = array("right", "campo", $valor);
		}
		$corpo[] = array("right", "campo", $valorTotal);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
	$pagina->FechaTabelaPadrao();
	$pagina->LinhaVazia(1);
	print $pagina->Imagem("RelPrioridadeAreaAtuacao.php?v_UOR_SIGLA=$v_UOR_SIGLA");
}
$pagina->MontaRodape();
?>
