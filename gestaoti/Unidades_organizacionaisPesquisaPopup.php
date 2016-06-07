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
require 'include/PHP/class/class.unidade_organizacional.php';
$pagina = new Pagina();
$banco = new unidade_organizacional();

$pagina->flagScriptCalendario = 0;
$pagina->flagMenu = 0;
$pagina->flagTopo = 0;

// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Unidades Organizacionais"); // Indica o título do cabeçalho da página
// Itens das abas
//$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
//				   array("Unidades_organizacionaisCadastro.php", "", "Adicionar") );
//$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_COD_UOR", "");
print $pagina->CampoHidden("vCampoOrigem", $vCampoOrigem);
print $pagina->CampoHidden("v_NUM_MATRICULA_RECURSO", "");

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_UNIDADE_ORGANIZACIONAL", "N", "Nome", "60", "60", $v_NOM_UNIDADE_ORGANIZACIONAL), "left");
$pagina->LinhaCampoFormulario("Sigla:", "right", "N", $pagina->CampoTexto("v_SGL_UNIDADE_ORGANIZACIONAL", "N", "Nome", "60", "60", $v_SGL_UNIDADE_ORGANIZACIONAL), "left");
$pagina->LinhaCampoFormulario("Unidade organizacional superior:", "right", "N", $pagina->CampoSelect("v_SEQ_UNIDADE_ORGANIZACIONAL_PAI", "N", "Unidade pai", "S", $banco->combo("NOM_UNIDADE_ORGANIZACIONAL", $v_SEQ_UNIDADE_ORGANIZACIONAL_PAI)), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);
//
// Inicio do grid de resultados
if($flag == "1"){
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("&nbsp;", "5%");
	$header[] = array("Nome", "");
	$header[] = array("Sigla", "");
	$header[] = array("Unidade Superior", "");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setNOM_UNIDADE_ORGANIZACIONAL($v_NOM_UNIDADE_ORGANIZACIONAL);
	$banco->setSGL_UNIDADE_ORGANIZACIONAL($v_SGL_UNIDADE_ORGANIZACIONAL);
	$banco->setSEQ_UNIDADE_ORGANIZACIONAL_PAI($v_SEQ_UNIDADE_ORGANIZACIONAL_PAI);
	$banco->selectParam("2", $vNumPagina);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Unidades organizacionaiss encontrados para os parâmentos pesquisados", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			$valor = $pagina->ButtonRetornaValorPopUp($vCampoOrigem, $row["seq_unidade_organizacional"]);
			$corpo[] = array("center", "campo", $valor);
			$corpo[] = array("left", "campo", $row["nom_unidade_organizacional"]);
			$corpo[] = array("left", "campo", $row["sgl_unidade_organizacional"]);
			if($row["seq_unidade_organizacional_pai"] != ""){
				$unidade_organizacional = new unidade_organizacional();
				$unidade_organizacional->select($row["seq_unidade_organizacional_pai"]);
				$corpo[] = array("left", "campo", $unidade_organizacional->NOM_UNIDADE_ORGANIZACIONAL);
			}else{
				$corpo[] = array("left", "campo", "---");
			}
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_COD_UOR=$v_COD_UOR&v_UOR_COD_UOR=$v_UOR_COD_UOR&v_UOR_NOME=$v_UOR_NOME&v_UOR_SIGLA=$v_UOR_SIGLA&v_UOR_DEP_CODIGO=$v_UOR_DEP_CODIGO&v_UOR_TIPO_UNIDAD_ORG=$v_UOR_TIPO_UNIDAD_ORG&v_UOR_NOME_ABREVIADO=$v_UOR_NOME_ABREVIADO&vCampoOrigem=$vCampoOrigem");
}
$pagina->MontaRodape();
?>
