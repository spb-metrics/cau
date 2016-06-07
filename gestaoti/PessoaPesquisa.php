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
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.unidade_organizacional.php';
$pagina = new Pagina();
$banco = new empregados();
$unidade_organizacional = new unidade_organizacional();

// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Pessoal do SISP"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("PessoaPesquisa.php", "tabact", "Pesquisa"),
                   array("PessoaCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
$pagina->LinhaVazia(1);
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_PESSOA);
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
//$pagina->LinhaCampoFormulario("Diretoria:", "right", "N", $pagina->CampoTexto("v_DEP_SIGLA", "N", " de Sigla", "10", "10", $v_DEP_SIGLA), "left");
$pagina->LinhaCampoFormulario("Unidade organizacional:", "right", "N", 
        $pagina->CampoSelect("v_SEQ_UNIDADE_ORGANIZACIONAL", "N", "", "S", $unidade_organizacional->combo(2, $v_SEQ_UNIDADE_ORGANIZACIONAL)), "left");
$pagina->LinhaCampoFormulario("E-mail:", "right", "N", $pagina->CampoTexto("v_DES_EMAIL", "N", "E-mail", "60", "60", ""), "left");
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
//$header[] = array("&nbsp;", "5%");
//$header[] = array("Login", "10%");
$header[] = array("Nome", "25%");
//$header[] = array("Nome de _abreviado", "");
//$header[] = array("Nome de _guerra", "");
//$header[] = array("Diretoria", "");
$header[] = array("Unidade Organizacional", "");
$header[] = array("Email", "");
$header[] = array("DDD", "10%");
$header[] = array("Telefone", "");
$header[] = array("Ramal", "10%");
//$header[] = array("Descri��o de Atatus", "");

// Setar vari�veis
if($flag == "1"){
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setNUM_MATRICULA_RECURSO($v_SEQ_PESSOA);
	$banco->setNOM_LOGIN_REDE($v_NOM_LOGIN_REDE);
	$banco->setNOME($v_NOME);
	$banco->setNOME_ABREVIADO($v_NOME_ABREVIADO);
	$banco->setNOME_GUERRA($v_NOME_GUERRA);
	$banco->setSEQ_UNIDADE_ORGANIZACIONAL($v_SEQ_UNIDADE_ORGANIZACIONAL);
	$banco->setUOR_SIGLA($v_SEQ_AREA_EXTERNA);
	$banco->setDES_EMAIL($v_DES_EMAIL);
	$banco->setNUM_DDD($v_NUM_DDD);
	$banco->setNUM_TELEFONE($v_NUM_TELEFONE);
	$banco->setNUM_VOIP($v_NUM_VOIP);
	$banco->setDES_STATUS($v_DES_STATUS);
	$banco->selectParam("NOME", $vNumPagina);
	//print "rows 1 = ".$banco->database->rows;
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Comlaboradores encontrados para os par�mentos pesquisados", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			if($v_CAMPO_NOME == ""){
				$valor = $pagina->ButtonRetornaValorPopUp($vCampoOrigem, $row["nom_login_rede"]);
			}else{
				$valor = $pagina->ButtonRetornaValorPopUpNomeCodigo($v_CAMPO_NOME, $row["nome"], $vCampoOrigem, $row["nom_login_rede"]);
			}
//			$valor .= $pagina->BotaoExcluiGridPesquisa("v_NUM_MATRICULA_RECURSO", $row["NUM_MATRICULA_RECURSO"]);
	//		$corpo[] = array("center", "campo", $valor);
	//		$corpo[] = array("left", "campo", $row["nom_login_rede"]);
			$corpo[] = array("left", "campo", $row["nome"]);
	//		$corpo[] = array("left", "campo", $row["NOME_ABREVIADO"]);
	//		$corpo[] = array("left", "campo", $row["NOME_GUERRA"]);
	//		$corpo[] = array("left", "campo", $row["dep_sigla"]);
                        $corpo[] = array("left", "campo", $row["dep_sigla"]);
                        
			$corpo[] = array("left", "campo", $row["des_email"]);
			$corpo[] = array("left", "campo", $row["num_ddd"]);
			$corpo[] = array("left", "campo", $row["num_telefone"]);
			$corpo[] = array("left", "campo", $row["num_voip"]);
	//		$corpo[] = array("left", "campo", $row["DES_ATATUS"]);
			//$pagina->LinhaTabelaResultado($corpo);
                        $pagina->LinhaTabelaResultado($corpo, $cont, "style=\"cursor: pointer;\" onclick=\"location.href='PessoaDetalhes.php?v_SEQ_PESSOA=".$row["seq_pessoa"]."';\"");
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_NOM_LOGIN_REDE=$v_NOM_LOGIN_REDE&v_NOME=$v_NOME&v_NOME_ABREVIADO=$v_NOME_ABREVIADO&v_NOME_GUERRA=$v_NOME_GUERRA&v_DEP_SIGLA=$v_DEP_SIGLA&v_UOR_SIGLA=$v_UOR_SIGLA&v_DES_EMAIL=$v_DES_EMAIL&v_NUM_DDD=$v_NUM_DDD&v_NUM_TELEFONE=$v_NUM_TELEFONE&v_NUM_VOIP=$v_NUM_VOIP&v_DES_ATATUS=$v_DES_ATATUS&vCampoOrigem=$vCampoOrigem");
}
$pagina->MontaRodape();
?>
