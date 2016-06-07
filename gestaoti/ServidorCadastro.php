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
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Servidor"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
		 			    array("#", "tabact", "Adicionar") );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");

	$pagina->LinhaCampoFormulario("Patrimônio:", "right", "S", $pagina->CampoTexto("v_NUM_PATRIMONIO", "S", "Patrimônio", "15", "15", ""), "left");
	$pagina->LinhaCampoFormulario("IP:", "right", "S", $pagina->CampoTexto("v_NUM_IP", "S", "Número de Ip", "15", "15", ""), "left");
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
	$pagina->LinhaCampoFormulario("Modelo:", "right", "N", $pagina->CampoTexto("v_NOM_MODELO", "N", "Nome de Modelo", "60", "60", ""), "left");
	$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $pagina->CampoTexto("v_DSC_SERVIDOR", "N", "Descrição", "50", "50", ""), "left");
	$pagina->LinhaCampoFormulario("Localização:", "right", "N", $pagina->CampoTexto("v_DSC_LOCALIZACAO", "N", "Descrição de Localizacao", "50", "50", ""), "left");
	$pagina->LinhaCampoFormulario("Processadores:", "right", "N", $pagina->CampoTexto("v_DSC_PROCESSADOR", "N", "Descrição de Processadores", "60", "60", ""), "left");
	$pagina->LinhaCampoFormulario("Observação:", "right", "N", $pagina->CampoTexto("v_TXT_OBSERVACAO", "N", "Descrição de Observacao", "50", "50", ""), "left");
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
	// Código inserido: $banco->SEQ_SERVIDOR
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Item_configuracaoPesquisa.php?flag=1&v_SEQ_SERVIDOR=$banco->SEQ_SERVIDOR");
	}
}
?>
