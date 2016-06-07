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
require_once 'include/PHP/class/class.prioridade_chamado.php';
require_once 'include/PHP/class/class.atribuicao_chamado.php';
require_once 'include/PHP/class/class.time_sheet.php';
require_once 'include/PHP/class/class.tipo_ocorrencia.php';
$destino_triagem = new destino_triagem();
$tipo_chamado = new tipo_chamado();
$subtipo_chamado = new subtipo_chamado();
$situacao_chamado = new situacao_chamado();
$pagina = new Pagina();
$banco = new chamado();
$empregados = new empregados();
$prioridade_chamado = new prioridade_chamado();
$atribuicao_chamado = new atribuicao_chamado();
$time_sheet = new time_sheet();
$pagina->ForcaAutenticacao();
$pagina->lightbox = 1;
// Configuração da págína
$pagina->SettituloCabecalho("Atendimento de Chamados - ".$_SESSION["NOM_EQUIPE_TI"]); // Indica o título do cabeçalho da página
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

	//var intervalo = window.setInterval("AtualizarPagina()", 60000);

	// Reload da página a cada 30 segundos
	function AtualizarPagina(){
		//window.location.reload(true);
	}

	function PararAtualizacao(intervalo){
		//alert("0");
		//window.clearInterval(intervalo);
		//alert("1");
	}



</script>
<?
/*
// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$aItemOption = Array();
$aItemOption[] = array("Aguardando_Atendimento", $pagina->iif($v_EXIBIR == "Aguardando_Atendimento","Selected", ""), "Chamados Aguardando Atendimento");
$aItemOption[] = array("MEUS_CHAMADOS", $pagina->iif($v_EXIBIR == "MEUS_CHAMADOS","Selected", ""), "Chamados sob minha responsabilidade ");
$aItemOption[] = array("AGENDADOS", $pagina->iif($v_EXIBIR == "AGENDADOS","Selected", ""), "Chamados Agendados");
$aItemOption[] = array("EQUIPE", $pagina->iif($v_EXIBIR == "EQUIPE","Selected", ""), "Chamados da Minha Equipe");
$aItemOption[] = array("ATRASADOS", $pagina->iif($v_EXIBIR == "ATRASADOS","Selected", ""), "Chamados Atrasados");
$aItemOption[] = array("EMDIA", $pagina->iif($v_EXIBIR == "EMDIA","Selected", ""), "Chamados Em Dia");
$aItemOption[] = array("RISCO", $pagina->iif($v_EXIBIR == "RISCO","Selected", ""), "Chamados com Risco de Atraso");
$aItemOption[] = array("SEMSLA", $pagina->iif($v_EXIBIR == "SEMSLA","Selected", ""), "Chamados sem SLA Estipulado");
if($_SESSION["FLG_LIDER_EQUIPE"] == "S"){
	$aItemOption[] = array("AAPROVACAO", $pagina->iif($v_EXIBIR == "AAPROVACAO","Selected", ""), "Chamados aguardando aprovação");
}

// Verificar se a equipe possui lista de priorização de chamados
if($_SESSION["NUM_MATRICULA_PRIORIZADOR"] != ""){
	$pagina->LinhaCampoFormulario("Exibir:", "right", "N",
				$pagina->CampoSelect("v_EXIBIR", "N", "", "S", $aItemOption, "Todos", "document.form.submit();").
				"&nbsp;&nbsp;|&nbsp;&nbsp;".
				"<a href=\"ChamadoPrioridade.php\">Ver lista de prioridades</a>"
				, "left", "", "5%");
}else{
	$pagina->LinhaCampoFormulario("Exibir:", "right", "N",
				$pagina->CampoSelect("v_EXIBIR", "N", "", "S", $aItemOption, "Todos", "document.form.submit();")
				, "left", "", "5%");
}

$pagina->FechaTabelaPadrao();

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


$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
*/
// Configuração de triagem
$vDestinoTriagem = $destino_triagem->BuscarDependenciasEquipe($_SESSION["SEQ_EQUIPE_TI"]);

$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

if($_SESSION["FLG_LIDER_EQUIPE"] == "S"){
	$v_EXIBIR == "AAPROVACAO";
}
// =======================================================================================================================
// Chamados aguardando planejamento
// =======================================================================================================================
if($v_EXIBIR == "" || $v_EXIBIR == "AAPROVACAO"){
	if($_SESSION["FLG_LIDER_EQUIPE"] == "S"){
		// Inicio do grid de resultados
		$pagina->AbreTabelaResultado("center", "100%");
		$header = array();
		$header[] = array("Prioridade", "10%");
		$header[] = array("Chamado", "10%");
		$header[] = array($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO"), "30%");
		$header[] = array("Solicitação", "");
		$header[] = array("Abertura", "10%");

		// Setar variáveis
		$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Planejamento);
		$banco->setTXT_CHAMADO($v_TXT_CHAMADO);
		$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
		$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);
		$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
		$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
		$banco->AtenderChamadosDistinct("DTH_ABERTURA");
		if($banco->database->rows > 0){
			$corpo = array();

			$tabela = array();
			$campo = array();
			$campo[] = array($pagina->BotaoImprimir("ChamadoAtendimentoPesquisaImprimir.php?v_EXIBIR=AAPROVACAO","onclick=\"PararAtualizacao();\""), "left", "3%", "header");
			$campo[] = array("<a onmousedown=\"PararAtualizacao(intervalo);\"  onclick=\"PararAtualizacao(intervalo);\" class=\"lbOn\" href=\"ChamadoAtendimentoPesquisaImprimir.php?v_EXIBIR=AAPROVACAO\">Imprimir</a>", "left", "3%", "header", "middle");
			$campo[] = array("Chamados aguardando planejamento	", "center", "93%", "header", "middle");
			$tabela[] = $campo;

			$corpo = array();
			$pagina->LinhaHeaderTabelaResultado($pagina->Tabela($tabela, "100%"), $header);

			while ($row = pg_fetch_array($banco->database->result)){
				// Prioridade
				$prioridade_chamado = new $prioridade_chamado();
				$prioridade_chamado->select($row["seq_prioridade_chamado"]);
				$corpo[] = array("left", "campo", $prioridade_chamado->DSC_PRIORIDADE_CHAMADO);

				// Chamado
				$corpo[] = array("right", "campo", $row["seq_chamado"]);

				// Atividade
				$subtipo_chamado = new subtipo_chamado();
				$subtipo_chamado->select($row["seq_subtipo_chamado"]);
				$tipo_chamado = new tipo_chamado();
				$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
				$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);

				// Solicitação
				$corpo[] = array("left", "campo", "de: <b>".$empregados->GetNomeEmpregado($row["num_matricula_solicitante"])."</b><br>".
												  $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

				// Abertura
				$corpo[] = array("center", "campo", $row["dth_abertura"]);

				$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoAtendimento.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
				$corpo = "";
			}
		}else{
			$pagina->LinhaColspan("center", "Chamados aguardando planejamento", "2", "header");
			$pagina->LinhaColspan("left", "Nenhum chamado encontrado", "2", "campo");
		}
		$pagina->FechaTabelaPadrao();
		$pagina->fMontarExportacao("ChamadoAguardandoAprovacaoDecorator.php",$banco->SQL_EXPORT,"CHAMADOS_AGUARDANDO_APROVACAO");
		print "<hr>";
	}
}

// =======================================================================================================================
// Chamados sob minha responsabilidade
// =======================================================================================================================
if($v_EXIBIR == "" || $v_EXIBIR == "MEUS_CHAMADOS" || $v_EXIBIR == "ATRASADOS" || $v_EXIBIR == "EMDIA" || $v_EXIBIR == "RISCO" || $v_EXIBIR == "SEMSLA"){
	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Prioridade", "6%");
	$header[] = array($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA"), "6%");
	$header[] = array("Chamado", "6%");
	$header[] = array($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO"), "20%");
	$header[] = array("Solicitação", "20%");
	$header[] = array("Situação", "15%");
	$header[] = array("Abertura", "10%");
	$header[] = array("Vencimento", "10%");
	$header[] = array("SLA", "5%");

	// Setar variáveis
	$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->CODS_EM_ANDAMENTO);
	$banco->setTXT_CHAMADO($v_TXT_CHAMADO);
	$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
	$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);
	$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$banco->setNUM_MATRICULA_EXECUTOR($_SESSION["NUM_MATRICULA_RECURSO"]);
	$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	/*
	if($v_EXIBIR == "ATRASADOS"){
		$banco->setCOD_SLA_ATENDIMENTO(-1);
	}
	if($v_EXIBIR == "EMDIA"){
		$banco->setCOD_SLA_ATENDIMENTO(1);
	}
	if($v_EXIBIR == "RISCO"){
		$banco->setCOD_SLA_ATENDIMENTO(0);
	}
	if($v_EXIBIR == "SEMSLA"){
		$banco->setCOD_SLA_ATENDIMENTO("NULL");
	}
	*/
	$banco->AtenderChamados("DTH_ABERTURA");
	
	$SQL_EXPORT = $banco->SQL_EXPORT;
	$Rows = $banco->database->rows;
	
	if($banco->database->rows > 0){

		$tabela = array();
		$campo = array();
		$campo[] = array($pagina->BotaoImprimir("ChamadoAtendimentoPesquisaImprimir.php?v_EXIBIR=MEUS_CHAMADOS","onclick=\"PararAtualizacao();\""), "left", "3%", "header");
		$campo[] = array("<a onmousedown=\"PararAtualizacao(intervalo);\"  onclick=\"PararAtualizacao(intervalo);\" class=\"lbOn\" href=\"ChamadoAtendimentoPesquisaImprimir.php?v_EXIBIR=MEUS_CHAMADOS\">Imprimir</a>", "left", "3%", "header", "middle");
		$campo[] = array("Chamados sob minha responsabilidade", "center", "93%", "header", "middle");
		$tabela[] = $campo;

		$corpo = array();
		$pagina->LinhaHeaderTabelaResultado($pagina->Tabela($tabela, "100%"), $header);

		while ($row = pg_fetch_array($banco->database->result)){
			// Prioridade
			$prioridade_chamado = new $prioridade_chamado();
			$prioridade_chamado->select($row["seq_prioridade_chamado"]);
			$corpo[] = array("left", "campo", $prioridade_chamado->DSC_PRIORIDADE_CHAMADO);

			// Tipo
			$tipo_ocorrencia = new tipo_ocorrencia();
			$tipo_ocorrencia->select($row["seq_tipo_ocorrencia"]);
			$corpo[] = array("left", "campo", strlen($tipo_ocorrencia->NOM_TIPO_OCORRENCIA)<15?$tipo_ocorrencia->NOM_TIPO_OCORRENCIA:substr($tipo_ocorrencia->NOM_TIPO_OCORRENCIA, 0, strpos($tipo_ocorrencia->NOM_TIPO_OCORRENCIA, " ")));

			// Verificar se o chamado está em atendimento no momento
			$v_FLG_ATENDIMENTO_INICIADO = $time_sheet->VerificarInicioAtividadeChamado($row["seq_chamado"], $_SESSION["NUM_MATRICULA_RECURSO"]);

			// Chamado
			if($v_FLG_ATENDIMENTO_INICIADO == "1"){ // Em Atendimento
				$corpo[] = array("right", "campo", "<font color=red>".$row["seq_chamado"]."</font>");
			}else{
				$corpo[] = array("right", "campo", $row["seq_chamado"]);
			}

			// Atividade
			$subtipo_chamado = new subtipo_chamado();
			$subtipo_chamado->select($row["seq_subtipo_chamado"]);
			$tipo_chamado = new tipo_chamado();
			$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
			$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);

			// Solicitação
			$corpo[] = array("left", "campo", "de: <b>".$empregados->GetNomeEmpregado($row["num_matricula_solicitante"])."</b><br>".
											  $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

			// Situação
			$situacao_chamado = new situacao_chamado();
			$situacao_chamado->select($row["seq_situacao_chamado"]);
			$corpo[] = array("left", "campo", $situacao_chamado->DSC_SITUACAO_CHAMADO);

			// abertura
			$corpo[] = array("center", "campo", $row["dth_abertura"]);

			// Recuperar dados do SLA
			$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
			//$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"]);
			$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

			// Vencimento
			$corpo[] = array("center", "campo", "<font color=".$pagina->CorSLA($v_COD_SLA_ATENDIMENTO).">".$v_DTH_ENCERRAMENTO_PREVISAO."</font>");

			// SLA
			$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));

			$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoAtendimento.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
			$corpo = "";
		}
		
	}else{
		$pagina->LinhaColspan("center", "Chamados sob minha responsabilidade", "2", "header");
		$pagina->LinhaColspan("left", "Nenhum chamado encontrado", "2", "campo");
	}
	
	$pagina->FechaTabelaPadrao();
}
if($Rows > 0){		
	$Parametros = "?v_TXT_CHAMADO=".$v_TXT_CHAMADO."&v_DTH_ABERTURA=".$v_DTH_ABERTURA."&v_DTH_ABERTURA_FINAL=".$v_DTH_ABERTURA_FINAL;
	$pagina->fMontarExportacao("ChamadoSobMinhaResponsabilidadeDecorator.php".$Parametros,$SQL_EXPORT,"CHAMADOS_SOB_MINHA_REPONSABILIDADE");
}
print "<hr>";

// =======================================================================================================================
// Chamados em atendimento na minha equipe
// =======================================================================================================================
if($v_EXIBIR == "" || $v_EXIBIR == "EQUIPE" || $v_EXIBIR == "ATRASADOS" || $v_EXIBIR == "EMDIA" || $v_EXIBIR == "RISCO" || $v_EXIBIR == "SEMSLA"){
	// Inicio do grid de resultados
	$banco = new chamado();
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Prioridade", "6%");
	$header[] = array($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA"), "6%");
	$header[] = array("Chamado", "6%");
	$header[] = array($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO"), "15%");
	$header[] = array("Solicitação", "15%");
	$header[] = array("Situação", "10%");
	$header[] = array("Profissional(is)", "15%");
	$header[] = array("Abertura", "10%");
	$header[] = array("Vencimento", "10%");
	$header[] = array("SLA", "5%");

	// Setar variáveis
	//$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	//$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	//$banco->setNUM_MATRICULA_SOLICITANTE($_SESSION["NUM_MATRICULA_RECURSO"]);
	//$banco->setNUM_MATRICULA_CONTATO($_SESSION["NUM_MATRICULA_RECURSO"]);
	//$banco->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
	//$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
	//$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
	//$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
	//$banco->setPESQUISA_ATENDIMENTO("ATRASO");
	$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->CODS_EM_ANDAMENTO);
	$banco->setTXT_CHAMADO($v_TXT_CHAMADO);
	$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
	$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);
	$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$banco->setNUM_MATRICULA_NAO_EXECUTOR($_SESSION["NUM_MATRICULA_RECURSO"]);
	$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	
	if($v_EXIBIR == "ATRASADOS"){
		$banco->setCOD_SLA_ATENDIMENTO(-1);
	}
	if($v_EXIBIR == "EMDIA"){
		$banco->setCOD_SLA_ATENDIMENTO(1);
	}
	if($v_EXIBIR == "RISCO"){
		$banco->setCOD_SLA_ATENDIMENTO(0);
	}
	if($v_EXIBIR == "SEMSLA"){
		$banco->setCOD_SLA_ATENDIMENTO("NULL");
	}
	$banco->AtenderChamadosDistinct("DTH_ABERTURA");
	
	$SQL_EXPORT = $banco->SQL_EXPORT;
	$Rows = $banco->database->rows;
	
	
	if($banco->database->rows > 0){
		$corpo = array();

		$tabela = array();
		$campo = array();
		$campo[] = array($pagina->BotaoImprimir("ChamadoAtendimentoPesquisaImprimir.php?v_EXIBIR=EQUIPE","onclick=\"PararAtualizacao();\""), "left", "3%", "header");
		$campo[] = array("<a onmousedown=\"PararAtualizacao(intervalo);\"  onclick=\"PararAtualizacao(intervalo);\" class=\"lbOn\" href=\"ChamadoAtendimentoPesquisaImprimir.php?v_EXIBIR=EQUIPE\">Imprimir</a>", "left", "3%", "header", "middle");
		$campo[] = array("Chamados em atendimento na minha equipe", "center", "93%", "header", "middle");
		$tabela[] = $campo;

		$corpo = array();
		$pagina->LinhaHeaderTabelaResultado($pagina->Tabela($tabela, "100%"), $header);

		while ($row = pg_fetch_array($banco->database->result)){
			// Prioridade
			$prioridade_chamado = new $prioridade_chamado();
			$prioridade_chamado->select($row["seq_prioridade_chamado"]);
			$corpo[] = array("left", "campo", $prioridade_chamado->DSC_PRIORIDADE_CHAMADO);

			// Tipo
			$tipo_ocorrencia = new tipo_ocorrencia();
			$tipo_ocorrencia->select($row["seq_tipo_ocorrencia"]);
			$corpo[] = array("left", "campo", strlen($tipo_ocorrencia->NOM_TIPO_OCORRENCIA)<15?$tipo_ocorrencia->NOM_TIPO_OCORRENCIA:substr($tipo_ocorrencia->NOM_TIPO_OCORRENCIA, 0, strpos($tipo_ocorrencia->NOM_TIPO_OCORRENCIA, " ")));


			// Chamado
			$corpo[] = array("right", "campo", $row["seq_chamado"]);

			// Atividade
			$subtipo_chamado = new subtipo_chamado();
			$subtipo_chamado->select($row["seq_subtipo_chamado"]);
			$tipo_chamado = new tipo_chamado();
			$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
			$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);

			// Solicitação
			$corpo[] = array("left", "campo", "de: <b>".$empregados->GetNomeEmpregado($row["num_matricula_solicitante"])."</b><br>".
											  $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

			// Situação
			$situacao_chamado->select($row["seq_situacao_chamado"]);
			$corpo[] = array("left", "campo", $situacao_chamado->DSC_SITUACAO_CHAMADO);

			// Profissional
			$corpo[] = array("left", "campo", $atribuicao_chamado->EquipeAtendimento($row["seq_chamado"]));

			// Abertura
			$corpo[] = array("center", "campo", $row["dth_abertura"]);

			// Recuperar dados do SLA
			$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
			//$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"]);
			$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

			// Vencimento
			$corpo[] = array("center", "campo", "<font color=".$pagina->CorSLA($v_COD_SLA_ATENDIMENTO).">".$v_DTH_ENCERRAMENTO_PREVISAO."</font>");

			// SLA
			$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));

			$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoAtendimento.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
			$corpo = "";
		}
	}else{
		$pagina->LinhaColspan("center", "Chamados em atendimento na minha equipe", "2", "header");
		$pagina->LinhaColspan("left", "Nenhum chamado encontrado", "2", "campo");
	}
	$pagina->FechaTabelaPadrao();
}

if($Rows > 0){		
	//$Parametros = "?v_TXT_CHAMADO=".$v_TXT_CHAMADO."&v_DTH_ABERTURA=".$v_DTH_ABERTURA."&v_DTH_ABERTURA_FINAL=".$v_DTH_ABERTURA_FINAL;
	$pagina->fMontarExportacao("ChamadoAtendimentoMinhaEquipeDecorator.php",$SQL_EXPORT,"CHAMADOS_EM_ATENDIMENTO_EQUIPE");
}
print "<hr>";

// =======================================================================================================================
// Chamados aguardando atendimento da minha equipe
// =======================================================================================================================
if($v_EXIBIR == "" || $v_EXIBIR == "Aguardando_Atendimento" || $v_EXIBIR == "ATRASADOS" || $v_EXIBIR == "EMDIA" || $v_EXIBIR == "RISCO" || $v_EXIBIR == "SEMSLA"){
	// Inicio do grid de resultados
	$banco = new chamado();
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Prioridade", "6%");
	$header[] = array($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA"), "6%");
	$header[] = array("Chamado", "6%");
	$header[] = array($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO"), "20%");
	$header[] = array("Solicitação", "20%");
	$header[] = array("Situação", "10%");
	$header[] = array("Abertura", "10%");
	$header[] = array("Vencimento", "10%");
	$header[] = array("SLA", "5%");

	// Setar variáveis
	//$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	//$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	//$banco->setNUM_MATRICULA_SOLICITANTE($_SESSION["NUM_MATRICULA_RECURSO"]);
	//$banco->setNUM_MATRICULA_CONTATO($_SESSION["NUM_MATRICULA_RECURSO"]);
	//$banco->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
	//$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
	//$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
	//$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
	//$banco->setPESQUISA_ATENDIMENTO("ATRASO");
	$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->CODS_EM_ANDAMENTO);
	$banco->setTXT_CHAMADO($v_TXT_CHAMADO);
	$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
	$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);
	$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$banco->setNUM_MATRICULA_NAO_EXECUTOR("NENHUM");
	$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	
	if($v_EXIBIR == "ATRASADOS"){
		$banco->setCOD_SLA_ATENDIMENTO(-1);
	}
	if($v_EXIBIR == "EMDIA"){
		$banco->setCOD_SLA_ATENDIMENTO(1);
	}
	if($v_EXIBIR == "RISCO"){
		$banco->setCOD_SLA_ATENDIMENTO(0);
	}
	if($v_EXIBIR == "SEMSLA"){
		$banco->setCOD_SLA_ATENDIMENTO("NULL");
	}
	
	$banco->AtenderChamados("DTH_ABERTURA");
	
	$SQL_EXPORT = $banco->SQL_EXPORT;
	$Rows = $banco->database->rows;
	
	if($banco->database->rows > 0){
		$corpo = array();

		$tabela = array();
		$campo = array();
		$campo[] = array($pagina->BotaoImprimir("ChamadoAtendimentoPesquisaImprimir.php?v_EXIBIR=Aguardando_Atendimento","onclick=\"PararAtualizacao();\""), "left", "3%", "header");
		$campo[] = array("<a onmousedown=\"PararAtualizacao(intervalo);\"  onclick=\"PararAtualizacao(intervalo);\" class=\"lbOn\" href=\"ChamadoAtendimentoPesquisaImprimir.php?v_EXIBIR=Aguardando_Atendimento\">Imprimir</a>", "left", "3%", "header", "middle");
		$campo[] = array("Chamados aguardando atendimento", "center", "93%", "header", "middle");
		$tabela[] = $campo;

		$corpo = array();
		$pagina->LinhaHeaderTabelaResultado($pagina->Tabela($tabela, "100%"), $header);

		$rs = $banco->database->result;
		while ($row = pg_fetch_array($rs)){
			// Prioridade
			$prioridade_chamado = new $prioridade_chamado();
			$prioridade_chamado->select($row["seq_prioridade_chamado"]);
			$corpo[] = array("left", "campo", $prioridade_chamado->DSC_PRIORIDADE_CHAMADO);

			// Tipo
			$tipo_ocorrencia = new tipo_ocorrencia();
			$tipo_ocorrencia->select($row["seq_tipo_ocorrencia"]);
			$corpo[] = array("left", "campo", strlen($tipo_ocorrencia->NOM_TIPO_OCORRENCIA)<15?$tipo_ocorrencia->NOM_TIPO_OCORRENCIA:substr($tipo_ocorrencia->NOM_TIPO_OCORRENCIA, 0, strpos($tipo_ocorrencia->NOM_TIPO_OCORRENCIA, " ")));

			// Chamado
			$corpo[] = array("right", "campo", $row["seq_chamado"]);

			// Atividade
			$subtipo_chamado = new subtipo_chamado();
			$subtipo_chamado->select($row["seq_subtipo_chamado"]);
			$tipo_chamado = new tipo_chamado();
			$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
			$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);

			// Solicitação
			$corpo[] = array("left", "campo", "de: <b>".$empregados->GetNomeEmpregado($row["num_matricula_solicitante"])."</b><br>".
											  $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

			// Situação
			$situacao_chamado->select($row["seq_situacao_chamado"]);
			$corpo[] = array("left", "campo", $situacao_chamado->DSC_SITUACAO_CHAMADO);

			// Abertura
			$corpo[] = array("center", "campo", $row["dth_abertura"]);

			// Recuperar dados do SLA
			$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
			//$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"]);
			$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

			// Vencimento
			$corpo[] = array("center", "campo", "<font color=".$pagina->CorSLA($v_COD_SLA_ATENDIMENTO).">".$v_DTH_ENCERRAMENTO_PREVISAO."</font>");

			// SLA
			$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));

			$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoAtendimento.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
			$corpo = "";
		}
	}else{
		$pagina->LinhaColspan("center", "Chamados aguardando atendimento", "2", "header");
		$pagina->LinhaColspan("left", "Nenhum chamado encontrado", "2", "campo");
	}
	
	$pagina->FechaTabelaPadrao();
}

if($Rows > 0){		
	//$Parametros = "?v_TXT_CHAMADO=".$v_TXT_CHAMADO."&v_DTH_ABERTURA=".$v_DTH_ABERTURA."&v_DTH_ABERTURA_FINAL=".$v_DTH_ABERTURA_FINAL;
	$pagina->fMontarExportacao("ChamadoAguardandoAtendimentoEquipeDecorator.php",$SQL_EXPORT,"CHAMADOS_AGUARDANDO_ATENDIMENTO_EQUIPE");
}


$pagina->MontaRodape();
?>
