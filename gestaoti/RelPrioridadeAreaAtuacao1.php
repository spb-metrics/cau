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
// =======================================================================
// Página de pesquisa de exclusão de registros da tabela Item_configuracao
// Página gerada pelo sistema GeraPHP - 14/03/2008 09:21:56
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
// Configuração da págína
$pagina->SettituloCabecalho("Prioridades dos Projetos de Sistemas de Informação"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");

if($flag == "1"){
	$v_COD_UOR = $unidades_organizacionais->GetUorCodigo($v_UOR_SIGLA);
	if($v_COD_UOR == ""){
		$pagina->ScriptAlert("Unidade Organizacional Inválida");
		$flag = "";
	}
}

// Inicio da tabela de parâmetros
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

	$pagina->LinhaHeaderTabelaResultado("Distribuição de Prioridade para os Projetos da $v_UOR_SIGLA", $header);
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
