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
require 'include/PHP/class/class.item_configuracao.php';
require 'include/PHP/class/class.patrimonio_ti.ativos.php';
require 'include/PHP/class/class.patrimonio_ti.catalogo.php';
require 'include/PHP/class/class.unidades_organizacionais.php';
require 'include/PHP/class/class.empregados.oracle.php';
$pagina = new Pagina();
$pagina->flagScriptCalendario = 0;
$pagina->flagMenu = 0;
$pagina->flagTopo = 0;
$pagina->flagMenu = 0;
$banco = new item_configuracao();
$bancoPatrimonio = new ativos();
$patrimonioCategoria = new catalogo();
$unidades_organizacionais = new unidades_organizacionais();
$empregados = new empregados();
// Configuração da págína
$pagina->SettituloCabecalho("Pesquisa Parque Tecnológico"); // Indica o título do cabeçalho da página

// Inicio do formulário
$pagina->MontaCabecalho();

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", "");

?>
<script language="javascript">
		function fMostraCampos(){
			if(document.form.v_SEQ_TIPO_ITEM_CONFIGURACAO.value == "1"){ // Hardware e Infra
				// Software
				document.getElementById("v_SIG_ITEM_CONFIGURACAO").style.display = "none";
				document.getElementById("v_NOM_ITEM_CONFIGURACAO").style.display = "none";
				document.getElementById("vOrderBy").style.display = "none";
				document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "none";
				document.getElementById("v_NUM_MATRICULA_LIDER").style.display = "none";
				document.getElementById("v_NUM_MATRICULA_GESTOR").style.display = "none";
				document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "none";
				// Suporte
				document.getElementById("v_SEQ_SISTEMA_OPERACIONAL").style.display = "block";
				document.getElementById("v_SEQ_MARCA_HARDWARE").style.display = "block";
				document.getElementById("v_NUM_PATRIMONIO").style.display = "block";
				document.getElementById("v_NUM_IP").style.display = "block";
				document.getElementById("v_NOM_SERVIDOR").style.display = "block";
			}else{
				if(document.form.v_SEQ_TIPO_ITEM_CONFIGURACAO.value == "2"){ // Sistemas de informação
					// Suporte
					document.getElementById("v_SEQ_SISTEMA_OPERACIONAL").style.display = "none";
					document.getElementById("v_SEQ_MARCA_HARDWARE").style.display = "none";
					document.getElementById("v_NUM_PATRIMONIO").style.display = "none";
					document.getElementById("v_NUM_IP").style.display = "none";
					document.getElementById("v_NOM_SERVIDOR").style.display = "none";
					// Software
					document.getElementById("v_SIG_ITEM_CONFIGURACAO").style.display = "block";
					document.getElementById("v_NOM_ITEM_CONFIGURACAO").style.display = "block";
					document.getElementById("vOrderBy").style.display = "block";
					document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "block";
					document.getElementById("v_NUM_MATRICULA_LIDER").style.display = "block";
					document.getElementById("v_NUM_MATRICULA_GESTOR").style.display = "block";
					document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "block";
				}else{
					// Software
					document.getElementById("v_SIG_ITEM_CONFIGURACAO").style.display = "none";
					document.getElementById("v_NOM_ITEM_CONFIGURACAO").style.display = "none";
					document.getElementById("vOrderBy").style.display = "block";
					document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "none";
					document.getElementById("v_NUM_MATRICULA_LIDER").style.display = "none";
					document.getElementById("v_NUM_MATRICULA_GESTOR").style.display = "none";
					document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "none";
					// Suporte
					document.getElementById("v_SEQ_SISTEMA_OPERACIONAL").style.display = "none";
					document.getElementById("v_SEQ_MARCA_HARDWARE").style.display = "none";
					document.getElementById("v_NUM_PATRIMONIO").style.display = "none";
					document.getElementById("v_NUM_IP").style.display = "none";
					document.getElementById("v_NOM_SERVIDOR").style.display = "none";
				}
			}
		}
	</script>
<?

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("vCampoNome", $vCampoNome);
print $pagina->CampoHidden("vCampoCodigo", $vCampoCodigo);
print $pagina->CampoHidden("vCampoCodigoTipo", $vCampoCodigoTipo);
if($v_SEQ_TIPO_ITEM_CONFIGURACAO == ""){
	$v_SEQ_TIPO_ITEM_CONFIGURACAO = $vCampoCodigoTipo;
}

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "85%");

// Buscar dados da tabela externa

if($v_SEQ_TIPO_ITEM_CONFIGURACAO == "1"){
	$aItemOption[] = array(1, $pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO == 1,"Selected", ""), "Servidores");
}elseif($v_SEQ_TIPO_ITEM_CONFIGURACAO == "2"){
	$aItemOption[] = array(2, $pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO == 2,"Selected", ""), "Sistemas de Informação");
}else{
	$aItemOption[] = array(1, $pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO == 1,"Selected", ""), "Servidores");
	$aItemOption[] = array(2, $pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO == 2,"Selected", ""), "Sistemas de Informação");
}

$pagina->LinhaCampoFormulario("Pesquisar por:", "right", "S", $pagina->CampoSelectEvent("v_SEQ_TIPO_ITEM_CONFIGURACAO", "S", "Pesquisar por", "S", $aItemOption, "fMostraCampos();"), "left", "v_SEQ_TIPO_ITEM_CONFIGURACAO", "30%", "70%");

// ==========================================================================================================================
// Parâmetros - Servidores
// ==========================================================================================================================
// Matricula do detentor
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
$pagina->LinhaCampoFormulario("Sistema operacional:", "right", "N", $pagina->CampoSelect("v_SEQ_SISTEMA_OPERACIONAL", "N", "Sistema operacional", "S", $aItemOption), "left", "id='v_SEQ_SISTEMA_OPERACIONAL' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));

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
$pagina->LinhaCampoFormulario("Marca:", "right", "N", $pagina->CampoSelect("v_SEQ_MARCA_HARDWARE", "N", "Marca hardware", "S", $aItemOption), "left", "id='v_SEQ_MARCA_HARDWARE' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));
$pagina->LinhaCampoFormulario("Nº Patrimônio:", "right", "N", $pagina->CampoTexto("v_NUM_PATRIMONIO", "N", "Número de Bem", "15", "15", $v_NUM_PATRIMONIO), "left", "id='v_NUM_PATRIMONIO' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));
$pagina->LinhaCampoFormulario("IP:", "right", "N", $pagina->CampoTexto("v_NUM_IP", "N", "Número de Ip", "15", "15", $v_NUM_IP), "left", "id='v_NUM_IP' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_SERVIDOR", "N", "Nome", "60", "60", $v_NOM_SERVIDOR), "left", "id='v_NOM_SERVIDOR' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));

// ==========================================================================================================================
// Parâmetros - Sistema de Informação
$pagina->LinhaCampoFormulario("Matricula do Gestor:", "right", "N",
								  $pagina->CampoTexto("v_NUM_MATRICULA_GESTOR", "N", "Matrícula do Gestor" , "10", "10", $v_NUM_MATRICULA_GESTOR, "readonly").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_GESTOR")
								  , "left", "id='v_NUM_MATRICULA_GESTOR' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));

$pagina->LinhaCampoFormulario("Matricula do Líder:", "right", "N",
								  $pagina->CampoTexto("v_NUM_MATRICULA_LIDER", "N", "Matrícula do Líder" , "10", "10", $v_NUM_MATRICULA_LIDER, "readonly").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_LIDER", "TI")
								  , "left", "id='v_NUM_MATRICULA_LIDER' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));

$pagina->LinhaCampoFormulario("Área Gestora:", "right", "N",
								  $pagina->CampoTexto("v_COD_UOR_AREA_GESTORA", "N", "Unidade Organizacional de Codigo ti responsavel", "10", "10", $v_COD_UOR_AREA_GESTORA, "readonly").
								  $pagina->ButtonProcuraUorg("v_COD_UOR_AREA_GESTORA", "")
								  , "left", "id='v_COD_UOR_AREA_GESTORA' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));

$pagina->LinhaCampoFormulario("Sigla:", "right", "N", $pagina->CampoTexto("v_SIG_ITEM_CONFIGURACAO", "N", "", "30", "30", $v_SIG_ITEM_CONFIGURACAO), "left", "id='v_SIG_ITEM_CONFIGURACAO' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO", "N", "Nome", "60", "60", $v_NOM_ITEM_CONFIGURACAO), "left", "id='v_NOM_ITEM_CONFIGURACAO' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));

$aItemOption = Array();
$aItemOption[] = array("SIG_ITEM_CONFIGURACAO", $pagina->iif($vOrderBy == "SIG_ITEM_CONFIGURACAO","Selected", ""), "Sigla");
$aItemOption[] = array("NOM_ITEM_CONFIGURACAO", $pagina->iif($vOrderBy == "NOM_ITEM_CONFIGURACAO","Selected", ""), "Nome");
$aItemOption[] = array("SEQ_TIPO_ITEM_CONFIGURACAO", $pagina->iif($vOrderBy == "SEQ_TIPO_ITEM_CONFIGURACAO","Selected", ""), "Tipo");
$pagina->LinhaCampoFormulario("Ordenar lista por:", "right", "N", $pagina->CampoSelect("vOrderBy", "N", "", "N", $aItemOption), "left", "id='vOrderBy' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
if($v_SEQ_TIPO_ITEM_CONFIGURACAO == "2"){ // Sistemas de informação
	$pagina->LinhaVazia(1);
	$banco = new item_configuracao();
	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("&nbsp;", "3%");
	$header[] = array("Tipo", "10%");
	//$header[] = array("Servico", "");
	$header[] = array("Gestor", "20%");
	$header[] = array("Líder", "20%");
	$header[] = array("Sigla", "13%");
	$header[] = array("Nome", "25%");
	$header[] = array("Área", "7%");
	//$header[] = array("Descrição", "");
	//$header[] = array("Tipo disponibilidade", "");
	//$header[] = array("Tipo criticidade", "");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
	$banco->setSEQ_TIPO_ITEM_CONFIGURACAO($v_SEQ_TIPO_ITEM_CONFIGURACAO);
	$banco->setSEQ_SERVICO($v_SEQ_SERVICO);
	$banco->setNUM_MATRICULA_GESTOR($empregados->GetNumeroMatricula($v_NUM_MATRICULA_GESTOR));
	$banco->setNUM_MATRICULA_LIDER($empregados->GetNumeroMatricula($v_NUM_MATRICULA_LIDER));
	$banco->setSIG_ITEM_CONFIGURACAO($v_SIG_ITEM_CONFIGURACAO);
	$banco->setNOM_ITEM_CONFIGURACAO($v_NOM_ITEM_CONFIGURACAO);
	$banco->setCOD_UOR_AREA_GESTORA($unidades_organizacionais->GetUorCodigo($v_COD_UOR_AREA_GESTORA));
	$banco->setTXT_ITEM_CONFIGURACAO($v_TXT_ITEM_CONFIGURACAO);
	$banco->setSEQ_TIPO_DISPONIBILIDADE($v_SEQ_TIPO_DISPONIBILIDADE);
	$banco->setSEQ_PRIORIDADE($v_SEQ_PRIORIDADE);
	$banco->selectParam($pagina->iif($vOrderBy == "", "SIG_ITEM_CONFIGURACAO", $vOrderBy), $vNumPagina);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Item encontrados para os parâmentos pesquisados", $header);
		$cont = 0;

		while ($row = pg_fetch_array($banco->database->result)){
			$valor = $pagina->ButtonRetornaValorPopUpNomeCodigoCodigo($vCampoNome, $row["nom_item_configuracao"], $vCampoCodigo, $row["seq_item_configuracao"], $vCampoCodigoTipo, 2);
			//ButtonRetornaValorPopUpNomeCodigo($v_CAMPO_NOME, $row["NOME"], $vCampoOrigem, $row["NOM_LOGIN_REDE"]);
			$corpo[] = array("center", "campo", $valor);
			require_once 'include/PHP/class/class.tipo_item_configuracao.php';
			$tipo_item_configuracao = new tipo_item_configuracao();
			$tipo_item_configuracao->select($row["seq_tipo_item_configuracao"]);
			$corpo[] = array("left", "campo", $tipo_item_configuracao->NOM_TIPO_ITEM_CONFIGURACAO);
	//		$corpo[] = array("right", "campo", $row["SEQ_SERVICO"]);
			$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_gestor"]));
			$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_lider"]));
			$corpo[] = array("left", "campo", $row["sig_item_configuracao"]);
			$corpo[] = array("left", "campo", $row["nom_item_configuracao"]);
			$corpo[] = array("center", "campo", $unidades_organizacionais->Getuorsigla($row["cod_uor_area_gestora"]));
	//		$corpo[] = array("left", "campo", $row["TXT_ITEM_CONFIGURACAO"]);
	//		$corpo[] = array("right", "campo", $row["SEQ_TIPO_DISPONIBILIDADE"]);
	//		$corpo[] = array("right", "campo", $row["SEQ_PRIORIDADE"]);
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
			$cont++;
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO&v_SEQ_TIPO_ITEM_CONFIGURACAO=$v_SEQ_TIPO_ITEM_CONFIGURACAO&v_SEQ_SERVICO=$v_SEQ_SERVICO&v_NUM_MATRICULA_GESTOR=$v_NUM_MATRICULA_GESTOR&v_NUM_MATRICULA_LIDER=$v_NUM_MATRICULA_LIDER&v_SIG_ITEM_CONFIGURACAO=$v_SIG_ITEM_CONFIGURACAO&v_NOM_ITEM_CONFIGURACAO=$v_NOM_ITEM_CONFIGURACAO&v_COD_UOR_AREA_GESTORA=$v_COD_UOR_AREA_GESTORA&v_TXT_ITEM_CONFIGURACAO=$v_TXT_ITEM_CONFIGURACAO&v_SEQ_TIPO_DISPONIBILIDADE=$v_SEQ_TIPO_DISPONIBILIDADE&v_SEQ_PRIORIDADE=$v_SEQ_PRIORIDADE");

}elseif($v_SEQ_TIPO_ITEM_CONFIGURACAO == "1"){ // Harduware

	$pagina->LinhaVazia(1);

	require 'include/PHP/class/class.servidor.php';
	require 'include/PHP/class/class.equipe_servidor.php';
	$banco = new servidor();
	$sistema_operacional = new sistema_operacional();
	$equipe_servidor = new equipe_servidor();
	$marca_hardware = new marca_hardware();

	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("&nbsp;", "5%");
	$header[] = array("Patrimônio", "10%");
	$header[] = array("IP", "10%");
	$header[] = array("Nome", "15%");
	$header[] = array("Sist. Operacional", "20%");
	$header[] = array("Marca", "10%");
	$header[] = array("Modelo", "10%");
	$header[] = array("Descrição", "20%");

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
		$pagina->LinhaHeaderTabelaResultado("Servidores encontrados", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			$valor = $pagina->ButtonRetornaValorPopUpNomeCodigoCodigo($vCampoNome, $row["nom_servidor"], $vCampoCodigo, $row["seq_servidor"], $vCampoCodigoTipo, 1);
			$corpo[] = array("center", "campo", $valor);
			$corpo[] = array("center", "campo", $row["num_patrimonio"]);
			$corpo[] = array("center", "campo", $row["num_ip"]);
			$corpo[] = array("left", "campo", $row["nom_servidor"]);

			if($row["seq_sistema_operacional"] != ""){
				$sistema_operacional->select($row["seq_sistema_operacional"]);
				$corpo[] = array("left", "campo", $sistema_operacional->NOM_SISTEMA_OPERACIONAL);
			}else{
				$corpo[] = array("left", "campo", "&nbsp;");
			}
			if($row["seq_marca_hardware"] != ""){
				$marca_hardware->select($row["seq_marca_hardware"]);
				$corpo[] = array("left", "campo", $marca_hardware->NOM_MARCA_HARDWARE);
			}else{
				$corpo[] = array("left", "campo", "&nbsp;");
			}

			$corpo[] = array("left", "campo", $row["nom_modelo"]);
			$corpo[] = array("left", "campo", $row["dsc_servidor"]);

			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
		$pagina->FechaTabelaPadrao();
		$pagina->LinhaCampoFormularioColspan("left", $pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_SERVIDOR=$v_SEQ_SERVIDOR&v_SEQ_SISTEMA_OPERACIONAL=$v_SEQ_SISTEMA_OPERACIONAL&v_SEQ_MARCA_HARDWARE=$v_SEQ_MARCA_HARDWARE&v_NUM_PATRIMONIO=$v_NUM_PATRIMONIO&v_NUM_IP=$v_NUM_IP&v_NOM_SERVIDOR=$v_NOM_SERVIDOR&v_NOM_MODELO=$v_NOM_MODELO&v_DSC_SERVIDOR=$v_DSC_SERVIDOR&v_DSC_LOCALIZACAO=$v_DSC_LOCALIZACAO&v_DSC_PROCESSADOR=$v_DSC_PROCESSADOR&v_TXT_OBSERVACAO=$v_TXT_OBSERVACAO&v_DAT_CRIACAO=$v_DAT_CRIACAO&v_DAT_ALTERACAO=$v_DAT_ALTERACAO"), 20);
	}
}
$pagina->MontaRodape();
?>
