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
require 'include/PHP/class/class.tipo_hardware.php';
$pagina = new Pagina();
$banco = new tipo_hardware();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Tipo de Hardware"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
				   array("Tipo_hardwareCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_TIPO_HARDWARE);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_TIPO_HARDWARE = ""; 
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_TIPO_HARDWARE", "");
/*
$pagina->AbreTabelaPadrao("center", "85%");
$pagina->LinhaCampoFormulario(" de _tipo hardware:", "right", "N", $pagina->CampoTexto("v_SEQ_TIPO_HARDWARE", "N", " de _tipo hardware", "9)", "9)", ""), "left"); 

$pagina->LinhaCampoFormulario(" de _tipo hardware:", "right", "N", $pagina->CampoTexto("v_NOM_TIPO_HARDWARE", "N", " de _tipo hardware", "60", "60", ""), "left"); 

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);
*/
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Nome", ""); 

// Setar variáveis 
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_TIPO_HARDWARE($v_SEQ_TIPO_HARDWARE);
$banco->setNOM_TIPO_HARDWARE($v_NOM_TIPO_HARDWARE);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Tipo hardwares encontrados para os parâmentos pesquisados", $header);
	while ($row = mysql_fetch_array($banco->database->result, MYSQL_BOTH)){ 
		$valor = $pagina->BotaoAlteraGridPesquisa("Tipo_hardwareAlteracao.php?v_SEQ_TIPO_HARDWARE=".$row["SEQ_TIPO_HARDWARE"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_TIPO_HARDWARE", $row["SEQ_TIPO_HARDWARE"]); 
		$corpo[] = array("center", "campo", $valor); 
		$corpo[] = array("left", "campo", $row["NOM_TIPO_HARDWARE"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_TIPO_HARDWARE=$v_SEQ_TIPO_HARDWARE&v_NOM_TIPO_HARDWARE=$v_NOM_TIPO_HARDWARE");
$pagina->MontaRodape(); 
?>
