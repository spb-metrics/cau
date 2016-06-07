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
require 'include/PHP/class/class.correcao_time_sheet.php';
$pagina = new Pagina();
$banco = new correcao_time_sheet();
// Configuração da págína

if($_SESSION["FLG_LIDER_EQUIPE"] != "S"){
	$pagina->ScriptAlert("Usuário não é líder de equipe ou substituto. Acesso não permitido.");
	$pagina->redirectToJS("Time_sheetPesquisa.php");
}

$pagina->SettituloCabecalho("Solcitações de Correção de Time Sheet Processadas"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Correcao_time_sheetPesquisa.php", "", "Pendentes"),
				   array("Correcao_time_sheetPesquisaProcessados.php", "tabact", "Processados") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_TIME_SHEET", "");

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("Profissional", "20%");
$header[] = array("Chamado", "7%");
$header[] = array("Início registrado", "10%");
$header[] = array("Término registrado", "10%");
$header[] = array("Início Solicitado", "10%");
$header[] = array("Término Solicitado", "10%");
$header[] = array("Justificativa", "");
$header[] = array("Análise", "10%");

// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
//$banco->setSEQ_TIME_SHEET($v_SEQ_TIME_SHEET);
//$banco->setDTH_INICIO_CORRECAO($v_DTH_INICIO_CORRECAO);
//$banco->setDTH_FIM_CORRECAO($v_DTH_FIM_CORRECAO);
//$banco->setTXT_JUSTIFICATIVA_CORRECAO($v_TXT_JUSTIFICATIVA_CORRECAO);
//$banco->setFLG_APROVADO($v_FLG_APROVADO);
$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
$banco->setNUM_MATRICULA_APROVADOR("NOT NULL");
$banco->selectParam("SEQ_TIME_SHEET", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhuma solicitação processada.", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Solicitações de correção processadas", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$corpo[] = array("left", "campo", $row["NOM_COLABORADOR"]);
		$corpo[] = array("right", "campo", "<a href=\"ChamadoDetalhe.php?v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."\">".$row["SEQ_CHAMADO"]."</a>");
		$corpo[] = array("center", "campo", $row["DTH_INICIO"]);
		$corpo[] = array("center", "campo", $row["DTH_FIM"]);
		$corpo[] = array("center", "campo", $row["DTH_INICIO_CORRECAO"]);
		$corpo[] = array("center", "campo", $row["DTH_FIM_CORRECAO"]);
		$corpo[] = array("left", "campo", $row["TXT_JUSTIFICATIVA_CORRECAO"]);
		$corpo[] = array("left", "campo", $pagina->iif($row["FLG_APROVADO"]=="S","Aprovado","Reprovado"));
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_TIME_SHEET=$v_SEQ_TIME_SHEET&v_DTH_INICIO_CORRECAO=$v_DTH_INICIO_CORRECAO&v_DTH_FIM_CORRECAO=$v_DTH_FIM_CORRECAO&v_TXT_JUSTIFICATIVA_CORRECAO=$v_TXT_JUSTIFICATIVA_CORRECAO&v_FLG_APROVADO=$v_FLG_APROVADO&v_NUM_MATRICULA_APROVADOR=$v_NUM_MATRICULA_APROVADOR");
$pagina->MontaRodape();
?>
