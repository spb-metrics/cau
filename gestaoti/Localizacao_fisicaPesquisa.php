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
require 'include/PHP/class/class.localizacao_fisica.php';
$pagina = new Pagina();
$banco = new localizacao_fisica();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa de Localizacao fisica"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Localizacao_fisicaPesquisa.php", "tabact", "Pesquisa"),
				   array("Localizacao_fisicaCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_LOCALIZACAO_FISICA);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_LOCALIZACAO_FISICA = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_LOCALIZACAO_FISICA", "");

/* Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "85%");


// Montar a combo da tabela edificacao
require_once 'include/PHP/class/class.edificacao.php';
$edificacao = new edificacao();
$pagina->LinhaCampoFormulario("Edificação:", "right", "N", $pagina->CampoSelect("v_SEQ_EDIFICACAO", "N", "Edificacao infraero", "S", $edificacao->combo("NO_DEPENDENCIA, NOM_EDIFICACAO", $v_SEQ_EDIFICACAO)), "left");
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
	$header[] = array("Edificação", "30%");
	$header[] = array("Nome", "");

	// Setar variáveis
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
		$pagina->LinhaHeaderTabelaResultado("Localizações físicas", $header);
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
