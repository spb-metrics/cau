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
require 'include/PHP/class/class.sistema_operacional.php';
require 'include/PHP/class/class.marca_hardware.php';
require 'include/PHP/class/class.equipe_servidor.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
$empregados = new empregados();
$pagina = new Pagina();
$banco = new servidor();
$sistema_operacional = new sistema_operacional();
$equipe_servidor = new equipe_servidor();
$marca_hardware = new marca_hardware();

$pagina->flagTopo = 0;
$pagina->flagMenu = 0;
$pagina->tituloCabecalho = "";
$pagina->MontaCabecalho();

// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_SERVIDOR);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_SERVIDOR = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_SERVIDOR", "");

$pagina->AbreTabelaResultado("center", "300%");
$header = array();
$header[] = array("&nbsp;", "70");
$header[] = array("Patrim�nio", "5%");
$header[] = array("IP", "5%");
$header[] = array("Nome", "5%");
$header[] = array("Equipe", "10%");
$header[] = array("Sistema operacional", "10%");
$header[] = array("Marca hardware", "10%");
$header[] = array("Modelo", "10%");
$header[] = array("Descri��o", "10%");
$header[] = array("Localiza��o", "10%");
$header[] = array("Processadores", "10%");
$header[] = array("Observa��o", "15%");
$header[] = array("Cria��o", "5%");
$header[] = array("Altera��o", "5%");

// Setar vari�veis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_SERVIDOR($v_SEQ_SERVIDOR);
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
$banco->setDAT_CRIACAO($v_DAT_CRIACAO);
$banco->setDAT_ALTERACAO($v_DAT_ALTERACAO);
$banco->selectParam("NOM_SERVIDOR", $vNumPagina, 20);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", count($header));
	$pagina->FechaTabelaPadrao();
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Servidores TIST-1", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisaIframe("ServidorAlteracao.php?v_SEQ_SERVIDOR=".$row["SEQ_SERVIDOR"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_SERVIDOR", $row["SEQ_SERVIDOR"]);
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("center", "campo", $row["NUM_PATRIMONIO"]);
		$corpo[] = array("center", "campo", $row["NUM_IP"]);
		$corpo[] = array("left", "campo", $row["NOM_SERVIDOR"]);

		$equipe_servidor->setSEQ_SERVIDOR($row["SEQ_SERVIDOR"]);
		$equipe_servidor->selectParam("", $vNumPagina, 20);
		if($banco->database->rows == 0){
			$equipe = "";
			while ($rowEquipe = pg_fetch_array($equipe_servidor->database->result)){
				$equipe .= $empregados->GetNomeEmpregado($rowEquipe["NUM_MATRICULA_RECURSO"]).", ";
			}
			$corpo[] = array("left", "campo", $equipe);
		}else{
			$corpo[] = array("left", "campo", "&nbsp;");
		}

		if($row["SEQ_SISTEMA_OPERACIONAL"] != ""){
			$sistema_operacional->select($row["SEQ_SISTEMA_OPERACIONAL"]);
			$corpo[] = array("left", "campo", $sistema_operacional->NOM_SISTEMA_OPERACIONAL);
		}else{
			$corpo[] = array("left", "campo", "&nbsp;");
		}
		if($row["SEQ_MARCA_HARDWARE"] != ""){
			$marca_hardware->select($row["SEQ_MARCA_HARDWARE"]);
			$corpo[] = array("left", "campo", $marca_hardware->NOM_MARCA_HARDWARE);
		}else{
			$corpo[] = array("left", "campo", "&nbsp;");
		}

		$corpo[] = array("left", "campo", $row["NOM_MODELO"]);
		$corpo[] = array("left", "campo", $row["DSC_SERVIDOR"]);
		$corpo[] = array("left", "campo", $row["DSC_LOCALIZACAO"]);
		$corpo[] = array("left", "campo", $row["DSC_PROCESSADOR"]);
		$corpo[] = array("left", "campo", $row["TXT_OBSERVACAO"]);
		$corpo[] = array("left", "campo", $pagina->ConvDataDMA($row["DAT_CRIACAO"],"/"));
		$corpo[] = array("left", "campo", $pagina->ConvDataDMA($row["DAT_ALTERACAO"],"/"));
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
	$pagina->FechaTabelaPadrao();
	$pagina->LinhaCampoFormularioColspan("left", $pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_SERVIDOR=$v_SEQ_SERVIDOR&v_SEQ_SISTEMA_OPERACIONAL=$v_SEQ_SISTEMA_OPERACIONAL&v_SEQ_MARCA_HARDWARE=$v_SEQ_MARCA_HARDWARE&v_NUM_PATRIMONIO=$v_NUM_PATRIMONIO&v_NUM_IP=$v_NUM_IP&v_NOM_SERVIDOR=$v_NOM_SERVIDOR&v_NOM_MODELO=$v_NOM_MODELO&v_DSC_SERVIDOR=$v_DSC_SERVIDOR&v_DSC_LOCALIZACAO=$v_DSC_LOCALIZACAO&v_DSC_PROCESSADOR=$v_DSC_PROCESSADOR&v_TXT_OBSERVACAO=$v_TXT_OBSERVACAO&v_DAT_CRIACAO=$v_DAT_CRIACAO&v_DAT_ALTERACAO=$v_DAT_ALTERACAO"), 20);
}

?>
