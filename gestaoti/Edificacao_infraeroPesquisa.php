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
require 'include/PHP/class/class.edificacao.php';
$pagina = new Pagina();
$banco = new edificacao();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa de Edifica��es"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("Edificacao_infraeroPesquisa.php", "tabact", "Pesquisa"),
				   array("Edificacao_infraeroCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_EDIFICACAO);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_EDIFICACAO = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_EDIFICACAO", "");

/* Inicio da tabela de par�metros */
//$pagina->AbreTabelaPadrao("center", "85%");

//$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_EDIFICACAO", "N", "Nome", "60", "60", ""), "left");

// Montar a combo
//require 'include/PHP/class/class.dependencias.php';
//$dependencias = new dependencias();
//$pagina->LinhaCampoFormulario("Depend�ncia:", "right", "S", $pagina->CampoSelect("v_COD_DEPENDENCIA", "S", "Depend�ncia", "N", $dependencias->combo(2, $_SESSION["COD_DEPENDENCIA"])), "left", "v_COD_DEPENDENCIA", "30%", "70%");

//$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
//$pagina->FechaTabelaPadrao();
//if($flag == "1"){
//	$pagina->LinhaVazia(1);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("&nbsp;", "7%");
	//$header[] = array("Dependencia", "20%");
	$header[] = array("Nome", "");


	// Setar vari�veis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setSEQ_EDIFICACAO($v_SEQ_EDIFICACAO);
	$banco->setNOM_EDIFICACAO($v_NOM_EDIFICACAO);
	//$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
	$banco->selectParam("2", $vNumPagina);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Edifica��es encontradas para os par�mentos pesquisados", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			$valor = $pagina->BotaoAlteraGridPesquisa("Edificacao_infraeroAlteracao.php?v_SEQ_EDIFICACAO=".$row["seq_edificacao"]."");
			$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_EDIFICACAO", $row["seq_edificacao"]);
			$valor .= $pagina->BotaoLupa("Localizacao_fisicaPesquisa.php?flag=1&v_SEQ_EDIFICACAO=".$row["seq_edificacao"], "Ver locais f�sicos");
			$corpo[] = array("center", "campo", $valor);
			//$corpo[] = array("center", "campo", $dependencias->GetSiglaDependencia($row["COD_DEPENDENCIA"]));
			$corpo[] = array("left", "campo", $row["nom_edificacao"]);
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_NOM_EDIFICACAO=$v_NOM_EDIFICACAO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA");
//}
$pagina->MontaRodape();
?>
