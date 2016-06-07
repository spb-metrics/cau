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
require 'include/PHP/class/class.localizacao_fisica.php';
$pagina = new Pagina();
$banco = new localizacao_fisica();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa de Localizacao fisica"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("Localizacao_fisicaPesquisa.php", "tabact", "Pesquisa"),
				   array("Localizacao_fisicaCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_LOCALIZACAO_FISICA);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_LOCALIZACAO_FISICA = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_LOCALIZACAO_FISICA", "");

/* Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");


// Montar a combo da tabela edificacao
require_once 'include/PHP/class/class.edificacao.php';
$edificacao = new edificacao();
$pagina->LinhaCampoFormulario("Edifica��o:", "right", "N", $pagina->CampoSelect("v_SEQ_EDIFICACAO", "N", "Edificacao infraero", "S", $edificacao->combo("NO_DEPENDENCIA, NOM_EDIFICACAO", $v_SEQ_EDIFICACAO)), "left");
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_LOCALIZACAO_FISICA", "N", "Nome", "60", "60", ""), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
*/
//if($flag == "1"){
//	$pagina->LinhaVazia(1);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("&nbsp;", "5%");
	$header[] = array("Edifica��o", "30%");
	$header[] = array("Nome", "");

	// Setar vari�veis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setSEQ_LOCALIZACAO_FISICA($v_SEQ_LOCALIZACAO_FISICA);
	$banco->setSEQ_EDIFICACAO($v_SEQ_EDIFICACAO);
	$banco->setNOM_LOCALIZACAO_FISICA($v_NOM_LOCALIZACAO_FISICA);
	$banco->selectParam("2, 3", $vNumPagina);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Localiza��es f�sicas", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			$valor = $pagina->BotaoAlteraGridPesquisa("Localizacao_fisicaAlteracao.php?v_SEQ_LOCALIZACAO_FISICA=".$row["seq_localizacao_fisica"]."");
			$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_LOCALIZACAO_FISICA", $row["seq_localizacao_fisica"]);
			$corpo[] = array("center", "campo", $valor);
			// Buscar dados da tabela externa
			require_once 'include/PHP/class/class.edificacao.php';
			$edificacao = new edificacao();
			$edificacao->select($row["seq_edificacao"]);
			$corpo[] = array("left", "campo", $edificacao->NOM_EDIFICACAO);
			$corpo[] = array("left", "campo", $row["nom_localizacao_fisica"]);
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_NOM_LOCALIZACAO_FISICA=$v_NOM_LOCALIZACAO_FISICA");
//}
$pagina->MontaRodape();
?>
