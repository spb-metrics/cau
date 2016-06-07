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
if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Servidor"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
		 			    array("#", "tabact", "Adicionar") );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formul�rio
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");

	$pagina->LinhaCampoFormulario("Patrim�nio:", "right", "S", $pagina->CampoTexto("v_NUM_PATRIMONIO", "S", "Patrim�nio", "15", "15", ""), "left");
	$pagina->LinhaCampoFormulario("IP:", "right", "S", $pagina->CampoTexto("v_NUM_IP", "S", "N�mero de Ip", "15", "15", ""), "left");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_SERVIDOR", "S", "Nome", "60", "60", ""), "left");

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
	$pagina->LinhaCampoFormulario("Modelo:", "right", "N", $pagina->CampoTexto("v_NOM_MODELO", "N", "Nome de Modelo", "60", "60", ""), "left");
	$pagina->LinhaCampoFormulario("Descri��o:", "right", "N", $pagina->CampoTexto("v_DSC_SERVIDOR", "N", "Descri��o", "50", "50", ""), "left");
	$pagina->LinhaCampoFormulario("Localiza��o:", "right", "N", $pagina->CampoTexto("v_DSC_LOCALIZACAO", "N", "Descri��o de Localizacao", "50", "50", ""), "left");
	$pagina->LinhaCampoFormulario("Processadores:", "right", "N", $pagina->CampoTexto("v_DSC_PROCESSADOR", "N", "Descri��o de Processadores", "60", "60", ""), "left");
	$pagina->LinhaCampoFormulario("Observa��o:", "right", "N", $pagina->CampoTexto("v_TXT_OBSERVACAO", "N", "Descri��o de Observacao", "50", "50", ""), "left");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro

	$banco->setSEQ_SISTEMA_OPERACIONAL($v_SEQ_SISTEMA_OPERACIONAL);
	$banco->setSEQ_MARCA_HARDWARE($v_SEQ_MARCA_HARDWARE);
	$banco->setNUM_PATRIMONIO($v_NUM_PATRIMONIO);
	$banco->setNUM_IP($v_NUM_IP);
	$banco->setNOM_SERVIDOR($v_NOM_SERVIDOR);
	$banco->setNOM_MODELO($v_NOM_MODELO);
	$banco->setDSC_SERVIDOR($v_DSC_SERVIDOR);
	$banco->setDSC_LOCALIZACAO($v_DSC_LOCALIZACAO);
	$banco->setDSC_PROCESSADOR($v_DSC_PROCESSADOR);
	$banco->setTXT_OBSERVACAO($v_TXT_OBSERVACAO);
	$banco->setDAT_CRIACAO(date("Y-m-d"));
	$banco->setDAT_ALTERACAO(date("Y-m-d"));
	$banco->insert();
	// C�digo inserido: $banco->SEQ_SERVIDOR
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Item_configuracaoPesquisa.php?flag=1&v_SEQ_SERVIDOR=$banco->SEQ_SERVIDOR");
	}
}
?>
