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
require 'include/PHP/class/class.unidade_organizacional.php';
$pagina = new Pagina();
$banco = new unidade_organizacional();

$pagina->flagScriptCalendario = 0;
$pagina->flagMenu = 0;
$pagina->flagTopo = 0;

// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Unidades Organizacionais"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
//$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
//				   array("Unidades_organizacionaisCadastro.php", "", "Adicionar") );
//$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_COD_UOR", "");
print $pagina->CampoHidden("vCampoOrigem", $vCampoOrigem);
print $pagina->CampoHidden("v_NUM_MATRICULA_RECURSO", "");

// Inicio da tabela de par�metros
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

	// Setar vari�veis
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
		$pagina->LinhaHeaderTabelaResultado("Unidades organizacionaiss encontrados para os par�mentos pesquisados", $header);
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
