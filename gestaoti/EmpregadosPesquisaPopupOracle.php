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
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.unidade_organizacional.php';

$pagina = new Pagina();
$banco = new empregados();
$unidade_organizacional = new unidade_organizacional();
$pagina->flagScriptCalendario = 0;
$pagina->flagMenu = 0;
$pagina->flagTopo = 0;
$pagina->flagMenu = 0;


// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Empregados"); // Indica o título do cabeçalho da página
// Itens das abas
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_NUM_MATRICULA_RECURSO);
	$pagina->ScriptAlert("Registro Excluído");
	$v_NUM_MATRICULA_RECURSO = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("vCampoOrigem", $vCampoOrigem);
print $pagina->CampoHidden("v_NUM_MATRICULA_RECURSO", "");
print $pagina->CampoHidden("v_CAMPO_NOME", $v_CAMPO_NOME);
$pagina->LinhaVazia(1);
// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "85%");
//$pagina->LinhaCampoFormulario(" de Numero matricula:", "right", "N", $pagina->CampoTexto("v_NUM_MATRICULA_RECURSO", "N", " de Numero matricula", "9)", "9)", ""), "left");
//$pagina->LinhaCampoFormulario("Login de Rede:", "right", "N", $pagina->CampoTexto("v_NOM_LOGIN_REDE", "N", "Nome de Login rede", "30", "30", ""), "left");
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOME", "N", "Nome de ", "60", "60", $v_NOME), "left");
//$pagina->LinhaCampoFormulario("Nome de _abreviado:", "right", "N", $pagina->CampoTexto("v_NOME_ABREVIADO", "N", "Nome de _abreviado", "30", "30", ""), "left");
//$pagina->LinhaCampoFormulario("Nome de _guerra:", "right", "N", $pagina->CampoTexto("v_NOME_GUERRA", "N", "Nome de _guerra", "10", "10", ""), "left");
//$pagina->LinhaCampoFormulario("Diretoria:", "right", "N", $pagina->CampoTexto("v_DEP_SIGLA", "N", " de Sigla", "10", "10", $v_DEP_SIGLA), "left");
//$pagina->LinhaCampoFormulario("Lotação:", "right", "N", $pagina->CampoTexto("v_UOR_SIGLA", "N", "Unidade Organizacional de Sigla", "10", "10", $v_UOR_SIGLA), "left");
//$pagina->LinhaCampoFormulario("Descrição de Email:", "right", "N", $pagina->CampoTexto("v_DES_EMAIL", "N", "Descrição de Email", "60", "60", ""), "left");
//$pagina->LinhaCampoFormulario("Número de Ddd:", "right", "N", $pagina->CampoTexto("v_NUM_DDD", "N", "Número de Ddd", "3", "3", ""), "left");
//$pagina->LinhaCampoFormulario("Número de Telefone:", "right", "N", $pagina->CampoTexto("v_NUM_TELEFONE", "N", "Número de Telefone", "20", "20", ""), "left");
//$pagina->LinhaCampoFormulario("Número de Voip:", "right", "N", $pagina->CampoTexto("v_NUM_VOIP", "N", "Número de Voip", "10", "10", ""), "left");
//$pagina->LinhaCampoFormulario("Descrição de Atatus:", "right", "N", $pagina->CampoTexto("v_DES_STATUS", "N", "Descrição de Atatus", "10", "10", ""), "left");
$pagina->LinhaCampoFormulario("Unidade organizacional:", "right", "N", $pagina->CampoSelect("v_SEQ_UNIDADE_ORGANIZACIONAL", "N", "Unidade", "S", $unidade_organizacional->combo("NOM_UNIDADE_ORGANIZACIONAL", $v_SEQ_UNIDADE_ORGANIZACIONAL)), "left");
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Login", "10%");
$header[] = array("Nome", "25%");
//$header[] = array("Nome de _abreviado", "");
//$header[] = array("Nome de _guerra", "");
$header[] = array("UnidadeOrganizacional", "");
//$header[] = array("Lotação", "");
$header[] = array("Email", "");
//$header[] = array("DDD", "10%");
//$header[] = array("Número de Telefone", "");
//$header[] = array("VOIP", "10%");
//$header[] = array("Descrição de Atatus", "");

// Setar variáveis
if($flag == "1"){
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
	$banco->setNOM_LOGIN_REDE($v_NOM_LOGIN_REDE);
	$banco->setNOME($v_NOME);
	$banco->setNOME_ABREVIADO($v_NOME_ABREVIADO);
	$banco->setNOME_GUERRA($v_NOME_GUERRA);
	$banco->setDEP_SIGLA($v_DEP_SIGLA);
	$banco->setUOR_SIGLA($v_UOR_SIGLA);
	$banco->setDES_EMAIL($v_DES_EMAIL);
	$banco->setNUM_DDD($v_NUM_DDD);
	$banco->setNUM_TELEFONE($v_NUM_TELEFONE);
	$banco->setNUM_VOIP($v_NUM_VOIP);
	$banco->setDES_STATUS($v_DES_STATUS);
	$banco->setSEQ_UNIDADE_ORGANIZACIONAL($v_SEQ_UNIDADE_ORGANIZACIONAL);
	$banco->selectParam("NOME", $vNumPagina);
	//print "rows 1 = ".$banco->database->rows;
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Empregados encontrados para os parâmentos pesquisados", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			if($v_CAMPO_NOME == ""){
				$valor = $pagina->ButtonRetornaValorPopUp($vCampoOrigem, $row["nom_login_rede"]);
			}else{
				$valor = $pagina->ButtonRetornaValorPopUpNomeCodigo($v_CAMPO_NOME, $row["nome"], $vCampoOrigem, $row["nom_login_rede"]);
			}
//			$valor .= $pagina->BotaoExcluiGridPesquisa("v_NUM_MATRICULA_RECURSO", $row["NUM_MATRICULA_RECURSO"]);
			$corpo[] = array("center", "campo", $valor);
			$corpo[] = array("left", "campo", $row["nom_login_rede"]);
			$corpo[] = array("left", "campo", $row["nome"]);
	//		$corpo[] = array("left", "campo", $row["NOME_ABREVIADO"]);
	//		$corpo[] = array("left", "campo", $row["NOME_GUERRA"]);
			$corpo[] = array("left", "campo", $row["dep_sigla"]);
			//$corpo[] = array("left", "campo", $row["uor_sigla"]);
			$corpo[] = array("left", "campo", $row["des_email"]);
	//		$corpo[] = array("left", "campo", $row["NUM_DDD"]);
	//		$corpo[] = array("left", "campo", $row["NUM_TELEFONE"]);
	//		$corpo[] = array("left", "campo", $row["NUM_VOIP"]);
	//		$corpo[] = array("left", "campo", $row["DES_STATUS"]);
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_NOM_LOGIN_REDE=$v_NOM_LOGIN_REDE&v_NOME=$v_NOME&v_NOME_ABREVIADO=$v_NOME_ABREVIADO&v_NOME_GUERRA=$v_NOME_GUERRA&v_DEP_SIGLA=$v_DEP_SIGLA&v_UOR_SIGLA=$v_UOR_SIGLA&v_DES_EMAIL=$v_DES_EMAIL&v_NUM_DDD=$v_NUM_DDD&v_NUM_TELEFONE=$v_NUM_TELEFONE&v_NUM_VOIP=$v_NUM_VOIP&v_DES_STATUS=$v_DES_STATUS&vCampoOrigem=$vCampoOrigem");
}
$pagina->MontaRodape();
?>
