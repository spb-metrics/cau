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
require 'include/PHP/class/class.destino_triagem.php';
$pagina = new Pagina();
$banco = new destino_triagem();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa de Destinos de Triagem"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("Destino_triagemPesquisa.php", "tabact", "Pesquisa"),
				   array("Destino_triagemCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_EQUIPE_TI, $v_COD_DEPENDENCIA);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_EQUIPE_TI = "";
	$v_COD_DEPENDENCIA = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_", "");

/* Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");


	// Montar a combo da tabela equipe_ti
	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$pagina->LinhaCampoFormulario("Equipe ti:", "right", "N", $pagina->CampoSelect("v_SEQ_EQUIPE_TI", "N", "Equipe ti", "S", $equipe_ti->combo(2, $v_SEQ_EQUIPE_TI)), "left");
$pagina->LinhaCampoFormulario("Dependencia:", "right", "N", $pagina->CampoTexto("v_COD_DEPENDENCIA", "N", "Dependencia", "9)", "9)", ""), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);
*/
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Equipe de TI", "");
$header[] = array("Dependencia", "");

// Setar vari�veis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Configura��es sobre Destino de Triagems de Chamados", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoExcluiGridPesquisa1("Destino_triagemPesquisa.php?flag=2&v_SEQ_EQUIPE_TI=".$row["SEQ_EQUIPE_TI"]."&v_COD_DEPENDENCIA=".$row["COD_DEPENDENCIA"]);
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["NOM_EQUIPE_TI"]);
		$corpo[] = array("left", "campo", $row["SG_DEPENDENCIA"]." - ".$row["NO_DEPENDENCIA"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA");
$pagina->MontaRodape();
?>
