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
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.chamado.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.subtipo_chamado.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.destino_triagem.php';
 
$destino_triagem = new destino_triagem();
$tipo_chamado = new tipo_chamado();
$subtipo_chamado = new subtipo_chamado();
$situacao_chamado = new situacao_chamado();
$pagina = new Pagina();
$banco = new chamado();
$empregados = new empregados();
// Configuração da págína
$pagina->SettituloCabecalho("Aprovação de Chamados"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();
$pagina->LinhaVazia(1);
// Inicio da tabela de parâmetros
?>
<script language="javascript">
	function fExibirParametros(){
		if(document.getElementById("tabelaParametros").style.display == "none"){
			document.getElementById("tabelaParametros").style.display = "block";
			document.getElementById("MaisParametros").style.display = "none";
			document.getElementById("MenosParametros").style.display = "block";
		}else{
			document.getElementById("tabelaParametros").style.display = "none";
			document.getElementById("MaisParametros").style.display = "block";
			document.getElementById("MenosParametros").style.display = "none";
		}
	}

	// Reload da página a cada 30 segundos
	function AtualizarPagina(){
		window.location.reload(true);
	}
	var intervalo = window.setInterval("AtualizarPagina()", 30000);
</script>
<?
/*
// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisParametros\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"imagens/mais.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Exibir Filtros de Pesquisa</a>", "left","", "3%");
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MenosParametros\" style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"imagens/menos.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Esconder Filtros de Pesquisa</a>", "left","","3%");
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "85%", "id=\"tabelaParametros\" style=\"display: none;\" ");

//$pagina->LinhaCampoFormulario("Situacao chamado:", "right", "N", $pagina->CampoSelect("v_SEQ_SITUACAO_CHAMADO", "N", "Situacao chamado", "S", $situacao_chamado->combo(2, $v_SEQ_SITUACAO_CHAMADO)), "left");
$pagina->LinhaCampoFormulario("Solicitação:", "right", "N", $pagina->CampoTexto("v_TXT_CHAMADO", "N", "Descrição", "60", "60", $v_TXT_CHAMADO), "left");
$pagina->LinhaCampoFormulario("Data de Abertura:", "right", "N",
			"de ".$pagina->CampoData("v_DTH_ABERTURA", "N", " de Abertura", $v_DTH_ABERTURA)
			." a ".$pagina->CampoData("v_DTH_ABERTURA_FINAL", "N", " de Abertura", $v_DTH_ABERTURA_FINAL)
			, "left");

/*
// Montar a combo da tabela item_configuracao
require_once 'include/PHP/class/class.item_configuracao.php';
$item_configuracao = new item_configuracao();
$pagina->LinhaCampoFormulario("Item configuracao:", "right", "N", $pagina->CampoSelect("v_SEQ_ITEM_CONFIGURACAO", "N", "Item configuracao", "S", $item_configuracao->combo(2, $v_SEQ_ITEM_CONFIGURACAO)), "left");
*/

//$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
//$pagina->FechaTabelaPadrao();

// Configuração de triagem
//$vDestinoTriagem = $destino_triagem->BuscarDependenciasEquipe($_SESSION["SEQ_EQUIPE_TI"]);


$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];
// =======================================================================================================================
// Em atraso
// =======================================================================================================================
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("Chamado", "5%");
$header[] = array("Solicitante", "20%");
$header[] = array("Atividade", "20%");
$header[] = array("Solicitação", "30%");
$header[] = array("Abertura", "10%");
//$header[] = array("Previsão", "10%");
//$header[] = array("SLA", "5%");

// Setar variáveis
//$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
//$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
//$banco->setNUM_MATRICULA_SOLICITANTE($_SESSION["NUM_MATRICULA_RECURSO"]);
//$banco->setNUM_MATRICULA_CONTATO($_SESSION["NUM_MATRICULA_RECURSO"]);
//$banco->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
//$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
//$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
//$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Aprovacao);
$banco->setTXT_CHAMADO($v_TXT_CHAMADO);
$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);
$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
$banco->setNUM_MATRICULA_APROVADOR_ATIVIDADE($_SESSION["NUM_MATRICULA_RECURSO"]);


//CHAMADOS PENDENTES DE APROVAÇÃO

require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
$empregados= new empregados(1);
$funcADM = $empregados->GetFuncaoAdministrativaByLogin($_SESSION["NOM_LOGIN_REDE"]);
$aprovacao = new chamado();	
		
if($aprovacao->aprovadorDeChamados($funcADM)){  
	
	$empregados->SelectCoordenacaoUnidadeSobMnhaResponsabilidade($_SESSION["NUM_MATRICULA_RECURSO"]);
	$COOR =   Array();
	$UOR =   Array();
	
	if($empregados->database->rows != 0){ 
	 	$i = 0;
	 	while ($row = pg_fetch_array($empregados->database->result)){
	 		$COOR[$i] = $row["coor_id"];
	 		$UOR[$i] = $row["uor_id"]; 
			$i++;
	 	}
	}
	
	if($_SESSION["UOR_ID"] == $pagina->COD_UNIDADE_GABINETE_PRESIDENCIA){
		$count = count($UOR);
		$UOR[$count] = $pagina->COD_UNIDADE_PRESIDENCIA; 
	}
}
//$banco->setPESQUISA_TRIAGEM("ATRASO");
//$banco->setCOD_DEPENDENCIA($vDestinoTriagem);


//CHAMADOS PENDENTES DE APROVAÇÃO

//require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
//$empregados= new empregados(1);
//$funcADM = $empregados->GetFuncaoAdministrativaByLogin($_SESSION["NOM_LOGIN_REDE"]);
//$aprovacao = new chamado();	

//if($aprovacao->aprovadorDeChamados($funcADM)){ 
//	if($_SESSION["COOR_ID"]!= null && $_SESSION["COOR_ID"]!= ""){
//		$banco->setCOOR_ID($_SESSION["COOR_ID"]);
//	}
//	if($_SESSION["UOR_ID"]!= null && $_SESSION["UOR_ID"]!= ""){
//		$banco->setUOR_ID($_SESSION["UOR_ID"]);
//	}
//}

$banco->selectChamadosAguardandoAprovacao("DTH_ABERTURA");

$SQL_EXPORT = $banco->SQL_EXPORT;
$Rows = $banco->database->rows;
	
if($banco->database->rows > 0){
	$corpo = array();
	//$pagina->LinhaHeaderTabelaResultado("<font color=red>Chamados Atrasados</font>", $header);
	$pagina->LinhaHeaderTabelaResultado("Chamados aguardando aprovação", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$corpo[] = array("right", "campo", $row["seq_chamado"]);
		$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_solicitante"]));

		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->select($row["seq_subtipo_chamado"]);
		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);

		$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);
		$corpo[] = array("left", "campo", $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

		// Recuperar dados do SLA
		$v_DTH_TRIAGEM_PREVISAOO = $banco->fGetDTH_TRIAGEM_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_triagem"]==""?30:$row["qtd_min_sla_triagem"], $row["flg_forma_medicao_tempo"]==""?"U":$row["flg_forma_medicao_tempo"]);
		// $v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_triagem"]==""?60:$row["qtd_min_sla_triagem"], $row["flg_forma_medicao_tempo"]==""?"U":$row["flg_forma_medicao_tempo"]);
		$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_TRIAGEM_PREVISAOO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);


		$corpo[] = array("center", "campo", $row["dth_abertura"]);
		//$corpo[] = array("center", "campo", $v_DTH_TRIAGEM_PREVISAOO);
		//$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));
		$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoAprovacaoDetalhe.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
		$corpo = "";
	}
}else{
	$pagina->LinhaHeaderTabelaResultado("Chamados Aguardando aprovação", $header);
	$pagina->LinhaCampoFormularioColspan("left", "Nenhum chamado atrasado", "2");
}

if($Rows > 0){		
	$pagina->LinhaColspan("center", $pagina->fMontarExportacao("ChamadoTriagemPesquisaDecorator.php",$SQL_EXPORT,"CHAMADOS_PRIMERIO_NIVEL",true), count($header),"");
	
}
$pagina->FechaTabelaPadrao();
/*
$pagina->LinhaVazia(2);
// =======================================================================================================================
// Com risco de atraso
// =======================================================================================================================
$banco = new chamado();
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("Chamado", "10%");
$header[] = array("Solicitante", "20%");
$header[] = array("Atividade", "20%");
$header[] = array("Solicitação", "30%");
$header[] = array("Abertura", "10%");
$header[] = array("SLA", "10%");

// Setar variáveis
//$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
//$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
//$banco->setNUM_MATRICULA_SOLICITANTE($_SESSION["NUM_MATRICULA_RECURSO"]);
//$banco->setNUM_MATRICULA_CONTATO($_SESSION["NUM_MATRICULA_RECURSO"]);
//$banco->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
//$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
//$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
//$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Triagem);
$banco->setTXT_CHAMADO($v_TXT_CHAMADO);
$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);
$banco->setPESQUISA_TRIAGEM("RISCOATRASO");
$banco->setCOD_DEPENDENCIA($vDestinoTriagem);
$banco->selectParam("DTH_ABERTURA");
if($banco->database->rows > 0){
	$corpo = array();
	$pagina->LinhaHeaderTabelaResultado("<font color=yellow>Chamados Com Risco de Atraso - 3/4 do tempo transcorrido</font>", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$corpo[] = array("right", "campo", $row["seq_chamado"]);
		$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_solicitante"]));

		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->select($row["seq_subtipo_chamado"]);
		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);

		$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);
		$corpo[] = array("left", "campo", $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

		$corpo[] = array("center", "campo", $row["dth_abertura"]);
		$corpo[] = array("center", "campo","<font color=yellow>". $row["dth_triagem_previsao"]."</font>");
		$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoTriagem.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
		$corpo = "";
	}
}else{
	$pagina->LinhaHeaderTabelaResultado("<font color=yellow>Chamados Com Risco de Atraso - 3/4 do tempo transcorrido</font>", $header);
	$pagina->LinhaCampoFormularioColspan("left", "Nenhum chamado com risco de atraso", "2");
}
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(2);
// =======================================================================================================================
// Em dia
// =======================================================================================================================
$banco = new chamado();
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("Chamado", "10%");
$header[] = array("Solicitante", "20%");
$header[] = array("Atividade", "20%");
$header[] = array("Solicitação", "30%");
$header[] = array("Abertura", "10%");
$header[] = array("SLA", "10%");

// Setar variáveis
//$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
//$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
//$banco->setNUM_MATRICULA_SOLICITANTE($_SESSION["NUM_MATRICULA_RECURSO"]);
//$banco->setNUM_MATRICULA_CONTATO($_SESSION["NUM_MATRICULA_RECURSO"]);
//$banco->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
//$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
//$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
//$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Triagem);
$banco->setTXT_CHAMADO($v_TXT_CHAMADO);
$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);
$banco->setPESQUISA_TRIAGEM("EMDIA");
$banco->setCOD_DEPENDENCIA($vDestinoTriagem);
$banco->selectParam("DTH_ABERTURA");
if($banco->database->rows > 0){
	$corpo = array();
	$pagina->LinhaHeaderTabelaResultado("<font color=green>Chamados Em Dia</font>", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$corpo[] = array("right", "campo", $row["seq_chamado"]);
		$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_solicitante"]));

		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->select($row["seq_subtipo_chamado"]);
		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);

		$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);
		$corpo[] = array("left", "campo", $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

		$corpo[] = array("center", "campo", $row["dth_abertura"]);
		$corpo[] = array("center", "campo", "<font color=green>".$row["dth_triagem_previsao"]."</font>");
		$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoTriagem.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
		$corpo = "";
	}
}else{
	$pagina->LinhaHeaderTabelaResultado("<font color=green>Chamados Em Dia</font>", $header);
	$pagina->LinhaCampoFormularioColspan("left", "Nenhum chamado em dia", "2");
}
$pagina->FechaTabelaPadrao();
*/
$pagina->MontaRodape();
?>
