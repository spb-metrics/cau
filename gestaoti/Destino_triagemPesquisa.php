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
require 'include/PHP/class/class.destino_triagem.php';
$pagina = new Pagina();
$banco = new destino_triagem();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa de Destinos de Triagem"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Destino_triagemPesquisa.php", "tabact", "Pesquisa"),
				   array("Destino_triagemCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_EQUIPE_TI, $v_COD_DEPENDENCIA);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_EQUIPE_TI = "";
	$v_COD_DEPENDENCIA = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_", "");

/* Inicio da tabela de parâmetros
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

// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Configurações sobre Destino de Triagems de Chamados", $header);
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
