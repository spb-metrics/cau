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
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_SERVIDOR = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_SERVIDOR", "");

$pagina->AbreTabelaResultado("center", "300%");
$header = array();
$header[] = array("&nbsp;", "70");
$header[] = array("Patrimônio", "5%");
$header[] = array("IP", "5%");
$header[] = array("Nome", "5%");
$header[] = array("Equipe", "10%");
$header[] = array("Sistema operacional", "10%");
$header[] = array("Marca hardware", "10%");
$header[] = array("Modelo", "10%");
$header[] = array("Descrição", "10%");
$header[] = array("Localização", "10%");
$header[] = array("Processadores", "10%");
$header[] = array("Observação", "15%");
$header[] = array("Criação", "5%");
$header[] = array("Alteração", "5%");

// Setar variáveis
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
