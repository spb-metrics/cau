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
require 'include/PHP/class/class.servidor.php';
$pagina = new Pagina();
$banco = new servidor();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Servidor"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
				   array("ServidorCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_SERVIDOR);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_SERVIDOR = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_SERVIDOR", "");

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");

// Buscar dados da tabela externa
require_once 'include/PHP/class/class.sistema_operacional.php';
$sistema_operacional = new sistema_operacional();
$aItemOption = Array();

$sistema_operacional->selectParam(2);
$cont = 0;
while ($row = pg_fetch_array($sistema_operacional->database->result)){
	$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_SISTEMA_OPERACIONAL == $row[0],"Selected", ""), $row[1]);
	$cont++;
}
// Adicionar combo no formul�rio
$pagina->LinhaCampoFormulario("Sistema operacional:", "right", "N", $pagina->CampoSelect("v_SEQ_SISTEMA_OPERACIONAL", "N", "Sistema operacional", "S", $aItemOption), "left");

// Buscar dados da tabela externa
require_once 'include/PHP/class/class.marca_hardware.php';
$marca_hardware = new marca_hardware();
$aItemOption = Array();

$marca_hardware->selectParam(2);
$cont = 0;
while ($row = pg_fetch_array($marca_hardware->database->result)){
	$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_MARCA_HARDWARE == $row[0],"Selected", ""), $row[1]);
	$cont++;
}
// Adicionar combo no formul�rio
$pagina->LinhaCampoFormulario("Marca:", "right", "N", $pagina->CampoSelect("v_SEQ_MARCA_HARDWARE", "N", "Marca hardware", "S", $aItemOption), "left");
$pagina->LinhaCampoFormulario("N� Patrim�nio:", "right", "N", $pagina->CampoTexto("v_NUM_PATRIMONIO", "N", "N�mero de Bem", "15", "15", $v_NUM_PATRIMONIO), "left");
$pagina->LinhaCampoFormulario("IP:", "right", "N", $pagina->CampoTexto("v_NUM_IP", "N", "N�mero de Ip", "15", "15", $v_NUM_IP), "left");
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_SERVIDOR", "N", "Nome", "60", "60", $v_NOM_SERVIDOR), "left");
/*
$pagina->LinhaCampoFormulario("Nome de Modelo:", "right", "N", $pagina->CampoTexto("v_NOM_MODELO", "N", "Nome de Modelo", "60", "60", ""), "left");
$pagina->LinhaCampoFormulario("Descri��o:", "right", "N", $pagina->CampoTexto("v_DSC_SERVIDOR", "N", "Descri��o", "50", "50", ""), "left");
$pagina->LinhaCampoFormulario("Descri��o de Localizacao:", "right", "N", $pagina->CampoTexto("v_DSC_LOCALIZACAO", "N", "Descri��o de Localizacao", "50", "50", ""), "left");
$pagina->LinhaCampoFormulario("Descri��o de Processadores:", "right", "N", $pagina->CampoTexto("v_DSC_PROCESSADOR", "N", "Descri��o de Processadores", "60", "60", ""), "left");
$pagina->LinhaCampoFormulario("Descri��o de Observacao:", "right", "N", $pagina->CampoTexto("v_TXT_OBSERVACAO", "N", "Descri��o de Observacao", "50", "50", ""), "left");
$pagina->LinhaCampoFormulario("Data de Criacao:", "right", "N",
			"de ".$pagina->CampoData("v_DAT_CRIACAO", "N", "Data de Criacao", "")
			." a ".$pagina->CampoData("v_DAT_CRIACAO_FINAL", "N", "Data de Criacao", "")
			, "left");

$pagina->LinhaCampoFormulario("Data de Alteracao:", "right", "N",
			"de ".$pagina->CampoData("v_DAT_ALTERACAO", "N", "Data de Alteracao", "")
			." a ".$pagina->CampoData("v_DAT_ALTERACAO_FINAL", "N", "Data de Alteracao", "")
			, "left");
*/
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Filtrar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);

// Inicio do grid de resultados
?>
<iframe src="ServidorPesquisa1.php?v_SEQ_SISTEMA_OPERACIONAL=<?=$v_SEQ_SISTEMA_OPERACIONAL?>&v_SEQ_MARCA_HARDWARE=<?=$v_SEQ_MARCA_HARDWARE?>&v_NUM_PATRIMONIO=<?=$v_NUM_PATRIMONIO?>&v_NUM_IP=<?=$v_NUM_IP?>&v_NOM_SERVIDOR=<?=$v_NOM_SERVIDOR?>" width="100%" height="600" scrolling="auto" frameborder="0"></iframe>
<?
$pagina->MontaRodape();
?>
