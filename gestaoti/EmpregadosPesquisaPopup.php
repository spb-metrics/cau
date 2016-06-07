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
//require 'include/PHP/class/class.empregados.php';
require 'include/PHP/class/class.empregados.oracle.php';
$pagina = new Pagina();
$banco = new empregados();

$pagina->flagScriptCalendario = 0;
$pagina->flagMenu = 0;
$pagina->flagTopo = 0;
$pagina->flagMenu = 0;


// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Empregados"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_NUM_MATRICULA_RECURSO);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_NUM_MATRICULA_RECURSO = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("vCampoOrigem", $vCampoOrigem);
print $pagina->CampoHidden("v_NUM_MATRICULA_RECURSO", "");
print $pagina->CampoHidden("v_CAMPO_NOME", $v_CAMPO_NOME);

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");
//$pagina->LinhaCampoFormulario(" de Numero matricula:", "right", "N", $pagina->CampoTexto("v_NUM_MATRICULA_RECURSO", "N", " de Numero matricula", "9)", "9)", ""), "left");
//$pagina->LinhaCampoFormulario("Login de Rede:", "right", "N", $pagina->CampoTexto("v_NOM_LOGIN_REDE", "N", "Nome de Login rede", "30", "30", ""), "left");
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOME", "N", "Nome de ", "60", "60", $v_NOME), "left");
//$pagina->LinhaCampoFormulario("Nome de _abreviado:", "right", "N", $pagina->CampoTexto("v_NOME_ABREVIADO", "N", "Nome de _abreviado", "30", "30", ""), "left");
//$pagina->LinhaCampoFormulario("Nome de _guerra:", "right", "N", $pagina->CampoTexto("v_NOME_GUERRA", "N", "Nome de _guerra", "10", "10", ""), "left");
$pagina->LinhaCampoFormulario("Depend�ncia:", "right", "N", $pagina->CampoTexto("v_DEP_SIGLA", "N", " de Sigla", "10", "10", $v_DEP_SIGLA), "left");
$pagina->LinhaCampoFormulario("Lota��o:", "right", "N", $pagina->CampoTexto("v_UOR_SIGLA", "N", "Unidade Organizacional de Sigla", "10", "10", $v_UOR_SIGLA), "left");
//$pagina->LinhaCampoFormulario("Descri��o de Email:", "right", "N", $pagina->CampoTexto("v_DES_EMAIL", "N", "Descri��o de Email", "60", "60", ""), "left");
//$pagina->LinhaCampoFormulario("N�mero de Ddd:", "right", "N", $pagina->CampoTexto("v_NUM_DDD", "N", "N�mero de Ddd", "3", "3", ""), "left");
//$pagina->LinhaCampoFormulario("N�mero de Telefone:", "right", "N", $pagina->CampoTexto("v_NUM_TELEFONE", "N", "N�mero de Telefone", "20", "20", ""), "left");
//$pagina->LinhaCampoFormulario("N�mero de Voip:", "right", "N", $pagina->CampoTexto("v_NUM_VOIP", "N", "N�mero de Voip", "10", "10", ""), "left");
//$pagina->LinhaCampoFormulario("Descri��o de Atatus:", "right", "N", $pagina->CampoTexto("v_DES_ATATUS", "N", "Descri��o de Atatus", "10", "10", ""), "left");
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
$header[] = array("Depend�ncia", "");
$header[] = array("Lota��o", "");
$header[] = array("Email", "");
$header[] = array("DDD", "10%");
//$header[] = array("N�mero de Telefone", "");
$header[] = array("VOIP", "10%");
//$header[] = array("Descri��o de Atatus", "");

// Setar vari�veis
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
	$banco->setDES_ATATUS($v_DES_ATATUS);
	$banco->selectParam("NOME", $vNumPagina);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Empregados encontrados para os par�mentos pesquisados", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			if($v_CAMPO_NOME == ""){
				$valor = $pagina->ButtonRetornaValorPopUp($vCampoOrigem, $row["NOM_LOGIN_REDE"]);
			}else{
				$valor = $pagina->ButtonRetornaValorPopUpNomeCodigo($v_CAMPO_NOME, $row["NOME"], $vCampoOrigem, $row["NOM_LOGIN_REDE"]);
			}
			$corpo[] = array("center", "campo", $valor);
			$corpo[] = array("left", "campo", $row["NOM_LOGIN_REDE"]);
			$corpo[] = array("left", "campo", $row["NOME"]);
	//		$corpo[] = array("left", "campo", $row["NOME_ABREVIADO"]);
	//		$corpo[] = array("left", "campo", $row["NOME_GUERRA"]);
			$corpo[] = array("left", "campo", $row["DEP_SIGLA"]);
			$corpo[] = array("left", "campo", $row["UOR_SIGLA"]);
			$corpo[] = array("left", "campo", $row["DES_EMAIL"]);
			$corpo[] = array("left", "campo", $row["NUM_DDD"]);
	//		$corpo[] = array("left", "campo", $row["NUM_TELEFONE"]);
			$corpo[] = array("left", "campo", $row["NUM_VOIP"]);
	//		$corpo[] = array("left", "campo", $row["DES_ATATUS"]);
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_NOM_LOGIN_REDE=$v_NOM_LOGIN_REDE&v_NOME=$v_NOME&v_NOME_ABREVIADO=$v_NOME_ABREVIADO&v_NOME_GUERRA=$v_NOME_GUERRA&v_DEP_SIGLA=$v_DEP_SIGLA&v_UOR_SIGLA=$v_UOR_SIGLA&v_DES_EMAIL=$v_DES_EMAIL&v_NUM_DDD=$v_NUM_DDD&v_NUM_TELEFONE=$v_NUM_TELEFONE&v_NUM_VOIP=$v_NUM_VOIP&v_DES_ATATUS=$v_DES_ATATUS&vCampoOrigem=$vCampoOrigem");
}
$pagina->MontaRodape();
?>
