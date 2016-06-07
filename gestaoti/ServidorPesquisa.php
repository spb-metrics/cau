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
require 'include/PHP/class/class.servidor.php';
$pagina = new Pagina();
$banco = new servidor();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Servidor"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
				   array("ServidorCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_SERVIDOR);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_SERVIDOR = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_SERVIDOR", "");

// Inicio da tabela de parâmetros
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
// Adicionar combo no formulário
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
// Adicionar combo no formulário
$pagina->LinhaCampoFormulario("Marca:", "right", "N", $pagina->CampoSelect("v_SEQ_MARCA_HARDWARE", "N", "Marca hardware", "S", $aItemOption), "left");
$pagina->LinhaCampoFormulario("Nº Patrimônio:", "right", "N", $pagina->CampoTexto("v_NUM_PATRIMONIO", "N", "Número de Bem", "15", "15", $v_NUM_PATRIMONIO), "left");
$pagina->LinhaCampoFormulario("IP:", "right", "N", $pagina->CampoTexto("v_NUM_IP", "N", "Número de Ip", "15", "15", $v_NUM_IP), "left");
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_SERVIDOR", "N", "Nome", "60", "60", $v_NOM_SERVIDOR), "left");
/*
$pagina->LinhaCampoFormulario("Nome de Modelo:", "right", "N", $pagina->CampoTexto("v_NOM_MODELO", "N", "Nome de Modelo", "60", "60", ""), "left");
$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $pagina->CampoTexto("v_DSC_SERVIDOR", "N", "Descrição", "50", "50", ""), "left");
$pagina->LinhaCampoFormulario("Descrição de Localizacao:", "right", "N", $pagina->CampoTexto("v_DSC_LOCALIZACAO", "N", "Descrição de Localizacao", "50", "50", ""), "left");
$pagina->LinhaCampoFormulario("Descrição de Processadores:", "right", "N", $pagina->CampoTexto("v_DSC_PROCESSADOR", "N", "Descrição de Processadores", "60", "60", ""), "left");
$pagina->LinhaCampoFormulario("Descrição de Observacao:", "right", "N", $pagina->CampoTexto("v_TXT_OBSERVACAO", "N", "Descrição de Observacao", "50", "50", ""), "left");
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
