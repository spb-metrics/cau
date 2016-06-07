<?php
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
require_once '../gestaoti/include/PHP/class/class.pagina.php';

function Encerramento_Efetivo($v_DTH_ENCERRAMENTO_EFETIVO, $v_DTH_ENCERRAMENTO_PREVISAO){
	require_once '../gestaoti/include/PHP/class/class.pagina.php';
	require_once '../gestaoti/include/PHP/class/dateObj.class.php';
	$myDate = new dateObj();
	$pagina = new Pagina();
	if($v_DTH_ENCERRAMENTO_EFETIVO != ""){
		$vSegundosDiferenca = $pagina->dateDiffHourPlus(str_replace("-","/",$v_DTH_ENCERRAMENTO_EFETIVO), $v_DTH_ENCERRAMENTO_PREVISAO);
		if($vSegundosDiferenca < 0){ // Chamado em atraso
			$vTempoRestante = $pagina->secondsToTime($vSegundosDiferenca*-1);
			return "<font color=red>".$v_DTH_ENCERRAMENTO_EFETIVO." - Encerrado com atraso de <b>$vTempoRestante</b></font>";
		}else{
			return "<font color=green>".$v_DTH_ENCERRAMENTO_EFETIVO." - Encerrado dentro do prazo</font>";
		}
	}else{
		$vTempoRestante = $pagina->FormatarData($myDate->diff($v_DTH_ENCERRAMENTO_PREVISAO, 'all'));
		if($pagina->dateDiffHour($v_DTH_ENCERRAMENTO_PREVISAO) < 0){ // Chamado em atraso
			return "<font color=red>Chamado não encerrado - Em atraso de <b>$vTempoRestante</b></font>";
		}else{
			return "<font color=green>Chamado não encerrado - O tempo estimado para o encerramento é de <b>$vTempoRestante</b></font>";
		}
	}
}

function Inicio_Efetivo($v_DTH_INICIO_EFETIVO, $v_DTH_INICIO_PREVISAO){
	require_once '../gestaoti/include/PHP/class/class.pagina.php';
	require_once '../gestaoti/include/PHP/class/dateObj.class.php';
	$myDate = new dateObj();
	$pagina = new Pagina();
	if($v_DTH_INICIO_EFETIVO != ""){
		$vSegundosDiferenca = $pagina->dateDiffHourPlus(str_replace("-","/",$v_DTH_INICIO_EFETIVO), str_replace("-","/",$v_DTH_INICIO_PREVISAO));
		if($vSegundosDiferenca < 0){ // Chamado em atraso
			$vTempoRestante = $pagina->secondsToTime($vSegundosDiferenca*-1);
			return "<font color=red>".$v_DTH_INICIO_EFETIVO." - Iniciado com atraso de <b>$vTempoRestante</b></font>";
		}else{
			return "<font color=green>".$v_DTH_INICIO_EFETIVO." - Iniciado dentro do prazo</font>";
		}
	}else{
		$vTempoRestante = $pagina->FormatarData($myDate->diff($v_DTH_INICIO_PREVISAO, 'all'));
		if($pagina->dateDiffHour($v_DTH_INICIO_PREVISAO) < 0){ // Chamado em atraso
			return "<font color=red>Chamado não iniciado - Em atraso de <b>$vTempoRestante</b></font>";
		}else{
			return "<font color=green>Chamado não iniciado - O tempo estimado para o início do atendimento é de <b>$vTempoRestante</b></font>";
		}
	}
}

if($v_SEQ_CHAMADO != ""){
	require_once '../gestaoti/include/PHP/class/class.chamado.php';
	require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
	$pagina = new Pagina();
	$banco = new chamado();
	$situacao_chamado = new situacao_chamado();
	$pagina->cea = 1;
	$pagina->SettituloCabecalho("Detalhamento do Chamado"); // Indica o título do cabeçalho da página

	// pesquisa
	$banco->select($v_SEQ_CHAMADO);
	$pagina->forcaAutenticacao();
	// Adicionar registro de acesso
	require_once '../gestaoti/include/PHP/class/class.historico_acesso_chamado.php';
	$historico_acesso_chamado = new historico_acesso_chamado();
	$historico_acesso_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$historico_acesso_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$historico_acesso_chamado->insert();

	// Itens das abas
	$aItemAba = Array();
	$aItemAba[] = Array("#", "tabact", "Detalhes");

	// Aguardando avaliação
	if($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Avaliacao){
		$aItemAba[] = array("ChamadoAvaliar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO", "", "Avaliar");

	// Aguardando atendimento, aguardando triagem ou suspenço
	}elseif($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Atendimento || $banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Triagem || $banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Suspenca || $banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Aprovacao){
		$aItemAba[] = array("ChamadoEncerramento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO", "", "Encerrar");

	// Contingenciado
	}elseif($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Contingenciado){
		$aItemAba[] = array("ChamadoReprovarContingenciamento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO", "", "Não Contingenciado");

	}

	$pagina->SetaItemAba($aItemAba);

	// Inicio do formulário
	$pagina->MontaCabecalho();
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais", 2);

	$pagina->LinhaCampoFormulario("Número:", "right", "N", $banco->SEQ_CHAMADO, "left", "id=".$pagina->GetIdTable(),"23%","");

	require_once '../gestaoti/include/PHP/class/class.tipo_ocorrencia.php';
	$tipo_ocorrencia = new tipo_ocorrencia();
	$tipo_ocorrencia->select($banco->SEQ_TIPO_OCORRENCIA);
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").":", "right", "N", $tipo_ocorrencia->NOM_TIPO_OCORRENCIA, "left", "id=".$pagina->GetIdTable());

	require_once '../gestaoti/include/PHP/class/class.subtipo_chamado.php';
	$subtipo_chamado = new subtipo_chamado();
	$subtipo_chamado->select($banco->SEQ_SUBTIPO_CHAMADO);

	require_once '../gestaoti/include/PHP/class/class.tipo_chamado.php';
	$tipo_chamado = new tipo_chamado();
	$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", "right", "N", $tipo_chamado->DSC_TIPO_CHAMADO, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").":", "right", "N", $subtipo_chamado->DSC_SUBTIPO_CHAMADO, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").":", "right", "N", $banco->DSC_ATIVIDADE_CHAMADO, "left", "id=".$pagina->GetIdTable());

	if($banco->SEQ_ITEM_CONFIGURACAO != ""){ // Mostrar o sistema de informação e a prioridade estabelecida pelo cliente
		require_once '../gestaoti/include/PHP/class/class.item_configuracao.php';
		$item_configuracao = new item_configuracao();
		$item_configuracao->select($banco->SEQ_ITEM_CONFIGURACAO);
		$pagina->LinhaCampoFormulario("Sistema de Informação:", "right", "N", $item_configuracao->SIG_ITEM_CONFIGURACAO." - ".$item_configuracao->NOM_ITEM_CONFIGURACAO, "left", "id=".$pagina->GetIdTable());
		//$pagina->LinhaCampoFormulario("Prioridade na Fila:", "right", "N", $banco->NUM_PRIORIDADE_FILA, "left", "id=".$pagina->GetIdTable());
	}

	$situacao_chamado->select($banco->SEQ_SITUACAO_CHAMADO);
	$pagina->LinhaCampoFormulario("Situação:", "right", "N", $situacao_chamado->DSC_SITUACAO_CHAMADO, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $banco->TXT_CHAMADO, "left", "id=".$pagina->GetIdTable());

	require_once '../gestaoti/include/PHP/class/class.atividade_chamado.php';
	$atividade_chamado_aux = new atividade_chamado();
	$atividade_chamado_aux->select($banco->SEQ_ATIVIDADE_CHAMADO);
	 
	if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){
	   	 
	   	// APROVADOR
	  	require_once '../gestaoti/include/PHP/class/class.aprovacao_chamado_superior.php';				
		$aprovacao_chamado_superior = new aprovacao_chamado_superior(); 
		$aprovacao_chamado_superior->selectByIdChamado($banco->SEQ_CHAMADO);
		
		if($aprovacao_chamado_superior->SEQ_APROVACAO_CHAMADO_SUPERIOR){
			
			require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
			$empregados = new empregados();
			$empregados->select($aprovacao_chamado_superior->NUM_MATRICULA);
			
			$pagina->LinhaCampoFormulario("Responsável pela aprovação:", "right", "N", 
	   		$empregados->NOME	, "left", "id=".$pagina->GetIdTable());
		}
	}
	
	if($banco->SEQ_ACAO_CONTINGENCIAMENTO != ""){
		require_once '../gestaoti/include/PHP/class/class.acao_contingenciamento.php';
		$acao_contingenciamento = new acao_contingenciamento();
		$acao_contingenciamento->select($banco->SEQ_ACAO_CONTINGENCIAMENTO);
		$pagina->LinhaCampoFormulario("Ação de contingenciamento:", "right", "N", $acao_contingenciamento->NOM_ACAO_CONTINGENCIAMENTO, "left", "id=".$pagina->GetIdTable());
	}

	// Exibir o texto de contingenciamento caso exista
	if($banco->TXT_CONTINGENCIAMENTO != ""){
		$pagina->LinhaCampoFormulario("Observação sobre o contingenciamento:", "right", "N", str_replace(chr(13), "<br>",$banco->TXT_CONTINGENCIAMENTO), "left", "id=".$pagina->GetIdTable());
	}

	// Exibir a causa raiz, caso exista
	if($banco->TXT_CAUSA_RAIZ != ""){
		$pagina->LinhaCampoFormulario("Causa raiz:", "right", "N", str_replace(chr(13), "<br>",$banco->TXT_CAUSA_RAIZ), "left", "id=".$pagina->GetIdTable());
	}

	// Identificar se o chamado possui etapas programadas
	require_once '../gestaoti/include/PHP/class/class.etapa_chamado.php';
	$etapa_chamado = new etapa_chamado();
	$etapa_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$etapa_chamado->selectParam("DTH_INICIO_PREVISTO");

	?>
	<script language="javascript">
		function fMostra(id, idTab){
			//document.getElementById("tabelaSLA").style.display = "none";
			//document.getElementById("tabSLA").attributes["class"].value = "";

			document.getElementById("tabelaMeusDados").style.display = "none";
			document.getElementById("tabMeusDados").attributes["class"].value = "";

			document.getElementById("tabelaHistorico").style.display = "none";
			document.getElementById("tabHistorico").attributes["class"].value = "";

			<? if($pagina->flg_usar_funcionalidades_patrimonio == "S"){ ?>
				document.getElementById("tabelaPatrimonio").style.display = "none";
				document.getElementById("tabPatrimonio").attributes["class"].value = "";
			<? } ?>

			document.getElementById("tabelaAnexos").style.display = "none";
			document.getElementById("tabAnexos").attributes["class"].value = "";

			document.getElementById("tabelaAtendimento").style.display = "none";
			document.getElementById("tabAtendimento").attributes["class"].value = "";
			
			<? if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){	  ?>   
				document.getElementById("tabelaAprovadores").style.display = "none";
				document.getElementById("tabAprovadores").attributes["class"].value = "";
			<? } ?>
			
			<? if($banco->QTD_MIN_SLA_ATENDIMENTO == ""){ ?>
					document.getElementById("tabelaPrevisao").style.display = "none";
					document.getElementById("tabPrevisao").attributes["class"].value = "";
			<? } ?>
			<? if($etapa_chamado->database->rows > 0){ ?>
					document.getElementById("tabelaEtapas").style.display = "none";
					document.getElementById("tabEtapas").attributes["class"].value = "";
			<? } ?>

			document.getElementById(id).style.display = "block";
			document.getElementById(id).style.width = $_SESSION["screenWidth"];
			document.getElementById(idTab).attributes["class"].value = "tabact";
		}
	</script>
	<?

	$aItemAba = Array();
	//$aItemAba[] = array("javascript:fMostra('tabelaSLA','tabSLA')", "tabact", "&nbsp;SLA&nbsp;", "tabSLA", "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaMeusDados','tabMeusDados')", "tabact", "&nbsp;Solicitante&nbsp;", "tabMeusDados", "onclick=\"validarSaida=false;\"");
	if($banco->QTD_MIN_SLA_ATENDIMENTO == ""){
		$aItemAba[] = array("javascript:fMostra('tabelaPrevisao','tabPrevisao')", "", "&nbsp;Previsão&nbsp;", "tabPrevisao", "onclick=\"validarSaida=false;\"");
	}
	if($etapa_chamado->database->rows > 0){
		$aItemAba[] = array("javascript:fMostra('tabelaEtapas','tabEtapas')", "", "&nbsp;Etapas&nbsp;", "tabEtapas", "onclick=\"validarSaida=false;\"");
	}
	$aItemAba[] = array("javascript:fMostra('tabelaHistorico','tabHistorico')", "", "&nbsp;Histórico&nbsp;", "tabHistorico",  "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaAtendimento','tabAtendimento')", "", "&nbsp;Atendimento&nbsp;", "tabAtendimento", "onclick=\"validarSaida=false;\"");
	if($pagina->flg_usar_funcionalidades_patrimonio == "S"){
		$aItemAba[] = array("javascript:fMostra('tabelaPatrimonio','tabPatrimonio')", "", "&nbsp;Patrimônio(s)&nbsp;", "tabPatrimonio", "onclick=\"validarSaida=false;\"");
	}
    
	$aItemAba[] = array("javascript:fMostra('tabelaAnexos','tabAnexos')", "", "&nbsp;Anexo(s)&nbsp;", "tabAnexos", "onclick=\"validarSaida=false;\"");
	
	if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){	     
 		$aItemAba[] = array("javascript: fMostra('tabelaAprovadores','tabAprovadores')", "", "&nbsp;Quem pode Aprovar&nbsp;", "tabAprovadores", "onclick=\"validarSaida=false;\"");
 	}
 	
	$pagina->SetaItemAba($aItemAba);
	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// SLA
	//================================================================================================================================
	// Verifricar data de encerramento previsto
	if($banco->QTD_MIN_SLA_ATENDIMENTO != ""){
		$v_DTH_ENCERRAMENTO_PREVISAO = $banco->DTH_ENCERRAMENTO_PREVISAO;
	}else{
		require_once '../gestaoti/include/PHP/class/class.aprovacao_chamado.php';
		$aprovacao_chamado = new aprovacao_chamado();
		$aprovacao_chamado->GetUltimoAprovacao($banco->SEQ_CHAMADO);
		if($aprovacao_chamado->DTH_PREVISTA != ""){
			$v_DTH_ENCERRAMENTO_PREVISAO = $aprovacao_chamado->DTH_PREVISTA;
		}else{
			$v_DTH_ENCERRAMENTO_PREVISAO = "";
		}

	}

/*
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaSLA cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Gestão de Nível de Serviço", 2);
	$tabela = array();
	if($banco->QTD_MIN_SLA_ATENDIMENTO != ""){
		require_once '../gestaoti/include/PHP/class/dateObj.class.php';
		$myDate = new dateObj();
		$header = array();
		// Data de Abertura
		$header = array();
		$header[] = array("Abertura:", "center", "23%", "label");
		$header[] = array($banco->DTH_ABERTURA, "left", "", "campo");
		$tabela[] = $header;

		// Previsão de início
		$header = array();
		$header[] = array("Previsão de início:", "center", "", "label");
		$header[] = array(str_replace("-","/",$banco->DTH_INICIO_PREVISAO), "left", "", "campo");
		$tabela[] = $header;

		// Início Efetivo
		$header = array();
		$header[] = array("Início Efetivo:", "center", "", "label");
		$header[] = array("<span id=\"SPAN_Inicio_Efetivo\">".Inicio_Efetivo($banco->DTH_INICIO_EFETIVO, $banco->DTH_INICIO_PREVISAO)."</span>", "left", "", "campo");
		$tabela[] = $header;

		// Previsão de encerramento
		if($v_DTH_ENCERRAMENTO_PREVISAO != ""){
			$header = array();
			$header[] = array("Previsão de encerramento:", "center", "", "label");
			$header[] = array(str_replace("-","/",$v_DTH_ENCERRAMENTO_PREVISAO), "left", "", "campo");
			$tabela[] = $header;
		}

		// Encerramento efetivo
		$header = array();
		$header[] = array("Encerramento Efetivo:", "center", "", "label");
		$header[] = array("<span id=\"SPAN_Encerramento_Efetivo\">".Encerramento_Efetivo($banco->DTH_ENCERRAMENTO_EFETIVO, $v_DTH_ENCERRAMENTO_PREVISAO)."</span>", "left", "", "campo");
		$tabela[] = $header;
	}else{
		if($v_DTH_ENCERRAMENTO_PREVISAO != ""){
			// Previsão de encerramento
			$header = array();
			$header[] = array("Previsão de encerramento:", "center", "23%", "label");
			$header[] = array(str_replace("-","/",$v_DTH_ENCERRAMENTO_PREVISAO), "left", "", "campo");
			$tabela[] = $header;
			// Encerramento efetivo
			$header = array();
			$header[] = array("Encerramento Efetivo:", "center", "", "label");
			$header[] = array("<span id=\"SPAN_Encerramento_Efetivo\">".Encerramento_Efetivo($banco->DTH_ENCERRAMENTO_EFETIVO, $v_DTH_ENCERRAMENTO_PREVISAO)."</span>", "left", "", "campo");
			$tabela[] = $header;
		}else{
			$tabela = array();
			$header = array();
			// Data de Abertura
			$header = array();
			$header[] = array("Prazo não estabelecido. Aguardando estimativa.", "left", "", "campo");
			$tabela[] = $header;
		}
	}

	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true), 2);
	$pagina->FechaTabelaPadrao();
*/
	//================================================================================================================================
	// Meus dados
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaMeusDados cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações sobre o solicitante", 2);

	$tabela = array();
	$header = array();
	// Nome
	$header = array();
	$header[] = array("Nome:", "center", "23%", "label");
	$header[] = array($_SESSION["NOME"], "left", "", "campo");
	$tabela[] = $header;

	// Dependência
	/*
	$header = array();
	$header[] = array("Dependência:", "center", "", "label");
	$header[] = array($_SESSION["DEP_SIGLA"], "left", "", "campo");
	$tabela[] = $header;
	*/

	// Lotação
	$header = array();
	$header[] = array("Lotação:", "center", "", "label");
	$header[] = array($_SESSION["UOR_SIGLA"], "left", "", "campo");
	$tabela[] = $header;

	// E-mail
	$header = array();
	$header[] = array("E-mail:", "center", "", "label");
	$header[] = array($_SESSION["DES_EMAIL"], "left", "", "campo");
	$tabela[] = $header;

	// Matrícula
	//$header = array();
	//$header[] = array("Matrícula:", "center", "", "label");
	//$header[] = array($_SESSION["NUM_MATRICULA_RECURSO"], "left", "", "campo");
	//$tabela[] = $header;

	// Ramal
	$header = array();
	$header[] = array("Ramal:", "center", "", "label");
	$header[] = array($_SESSION["NUM_DDD"]." ".$_SESSION["NUM_VOIP"], "left", "", "campo");
	$tabela[] = $header;

	// Localização do cliente
	if($banco->SEQ_LOCALIZACAO_FISICA != ""){
		$header = array();
		require_once '../gestaoti/include/PHP/class/class.localizacao_fisica.php';
		$localizacao_fisica = new localizacao_fisica();
		$localizacao_fisica->select($banco->SEQ_LOCALIZACAO_FISICA);

		require_once '../gestaoti/include/PHP/class/class.edificacao.php';
		$edificacao = new edificacao();
		$edificacao->select($localizacao_fisica->SEQ_EDIFICACAO);

		require_once '../gestaoti/include/PHP/class/class.dependencias.php';
		$dependencias = new dependencias();
		$vSIG_DEPENDENCIA = $dependencias->GetSiglaDependencia($edificacao->COD_DEPENDENCIA);

		$header[] = array("Localização:", "center", "", "label");
		$header[] = array($vSIG_DEPENDENCIA." - ".$edificacao->NOM_EDIFICACAO." - ".$localizacao_fisica->NOM_LOCALIZACAO_FISICA, "left", "", "campo");
		$tabela[] = $header;
	}


	// Pessoa de contato
	if($banco->NUM_MATRICULA_CONTATO != ""){
		require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$empregados->select($banco->NUM_MATRICULA_CONTATO);
		$header = array();
		$header[] = array("Pessoa de contato:", "center", "", "label");
		$header[] = array($empregados->NOME." - Ramal: ".$empregados->NUM_DDD."-".$empregados->NUM_VOIP, "left", "", "campo");
		$tabela[] = $header;
	}

	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Previsão
	//================================================================================================================================
	if($banco->QTD_MIN_SLA_ATENDIMENTO == ""){ // Apenas para demandas de SLA Pós estabelecido
		$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaPrevisao style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		$pagina->LinhaCampoFormularioColspanDestaque("Planejamento de encerramento do chamado", 2);

		require_once '../gestaoti/include/PHP/class/class.aprovacao_chamado.php';
		$aprovacao_chamado = new aprovacao_chamado();
		$aprovacao_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
		$aprovacao_chamado->selectParam("DTH_APROVACAO DESC");
		if($aprovacao_chamado->database->rows == 0){
			$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
		}else{
			$tabela = array();
			$header = array();
			$header[] = array("Profissional", "center", "25%", "header");
			$header[] = array("Data Planejamento", "center", "17%", "header");
			$header[] = array("Data Prevista", "center", "17%", "header");
			$header[] = array("Observação", "center", "", "header");
			$tabela[] = $header;

			while ($row = pg_fetch_array($aprovacao_chamado->database->result)){
				$header = array();
				$header[] = array($row["nom_colaborador"], "left", "", "");
				$header[] = array($row["dth_aprovacao"], "center", "", "");
				$header[] = array($row["dth_prevista"], "center", "", "");
				$header[] = array($row["txt_justificativa"], "left", "", "");
				$tabela[] = $header;
			}
			$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
		}
		$pagina->FechaTabelaPadrao();
	}

	//================================================================================================================================
	// Etapas do chamado
	//================================================================================================================================
	if($etapa_chamado->database->rows > 0){
		$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaEtapas style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		$pagina->LinhaCampoFormularioColspanDestaque("Etapas do Chamado", 2);
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("IframeEtapas", "../gestaoti/Etapa_chamadoPesquisaChamado.php?flagReadOnly=1&v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
		$pagina->FechaTabelaPadrao();
	}

	//================================================================================================================================
	// Histórico
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaHistorico style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Histórico do Chamado", 2);

	require_once '../gestaoti/include/PHP/class/class.historico_chamado.php';
	$historico_chamado = new historico_chamado();

	$historico_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$historico_chamado->selectParam("DTH_HISTORICO");
	if($historico_chamado->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
	}else{
		$tabela = array();
		$header = array();
		$header[] = array("Data", "center", "17%", "header");
		$header[] = array("Situação", "center", "20%", "header");
		$header[] = array("Responsável", "center", "25%", "header");
		$header[] = array("Observação", "center", "", "header");
		$tabela[] = $header;

		require_once '../gestaoti/include/PHP/class/class.recurso_ti.php';
		$recurso_ti = new recurso_ti();

		require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();

		while ($row = pg_fetch_array($historico_chamado->database->result)){
			if($row["seq_situacao_chamado"] == $situacao_chamado->COD_Aguardando_Triagem){
				$vResponsavel = $empregados->GetNomeEmpregado($row["num_matricula"]);
			}else{
				$recurso_ti->select($row["num_matricula"]);
				if($recurso_ti->NUM_MATRICULA_RECURSO == ""){
					$vResponsavel = $empregados->GetNomeEmpregado($row["num_matricula"]);
				}else{
					$vResponsavel = $recurso_ti->NOM_EQUIPE_TI;
				}
			}

			$header = array();
			$header[] = array($row["dth_historico"], "center", "", "");
			$header[] = array($row["dsc_situacao_chamado"], "left", "", "");
			$header[] = array($vResponsavel, "left", "25%", "");
			$header[] = array($row["txt_historico"], "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
	}
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Atendimento
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaAtendimento style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Registro de informações sobre o atendimento realizado ", 2);

	require_once '../gestaoti/include/PHP/class/class.atendimento_chamado.php';
	$atendimento_chamado = new atendimento_chamado();
	$atendimento_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$atendimento_chamado->selectParam("DTH_ATENDIMENTO_CHAMADO DESC");
	if($atendimento_chamado->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
	}else{
		$tabela = array();
		$header = array();
		$header[] = array("Profissional", "center", "25%", "header");
		$header[] = array("Data", "center", "17%", "header");
		$header[] = array("Observação", "center", "", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($atendimento_chamado->database->result)){
			$header = array();
			$header[] = array($row["nom_colaborador"], "left", "", "");
			$header[] = array($row["dth_atendimento_chamado"], "center", "", "");
			$header[] = array($row["txt_atendimento_chamado"], "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
	}
	$pagina->FechaTabelaPadrao();


	//================================================================================================================================
	// Patrimônio
	//================================================================================================================================
        if($pagina->flg_usar_funcionalidades_patrimonio == "S"){
            $pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaPatrimonio style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
            $pagina->LinhaCampoFormularioColspanDestaque("Itens do patrimônio da empresa envolvidos com o chamado ", 2);

            require_once '../gestaoti/include/PHP/class/class.patrimonio_chamado.php';
            $patrimonio_chamado = new patrimonio_chamado();
            $patrimonio_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
            $patrimonio_chamado->selectParam("NUM_PATRIMONIO");
            if($patrimonio_chamado->database->rows == 0){
                    $pagina->LinhaCampoFormularioColspan("left", "Nenhum patrimônio informado.", 2);
            }else{
                    $tabela = array();
                    $header = array();
                    $header[] = array("Número", "center", "10%", "header");
                    $header[] = array("Descrição", "center", "40%", "header");
                    //$header[] = array("Detentor", "center", "25%", "header");
                    $header[] = array("Localização", "center", "", "header");
                    $tabela[] = $header;

                    require_once '../gestaoti/include/PHP/class/class.patrimonio_ti.ativos.php';
                    $ativos = new ativos();

                    while ($row = pg_fetch_array($patrimonio_chamado->database->result)){
                            $ativos->select($row["num_patrimonio"]);
                            $header = array();
                            $header[] = array($row["num_patrimonio"], "center", "", "");
                            $header[] = array($ativos->NOM_BEM, "left", "", "");
                    //	$header[] = array($ativos->NOM_DETENTOR, "left", "", "");
                            $header[] = array($ativos->DSC_LOCALIZACAO, "left", "", "");
                            $tabela[] = $header;
                    }
                    $pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
            }
            $pagina->FechaTabelaPadrao();
        }
	//================================================================================================================================
	// Anexo
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaAnexos style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Arquivos anexados ao chamado", 2);

	require_once '../gestaoti/include/PHP/class/class.anexo_chamado.php';
	$anexo_chamado = new anexo_chamado();
	$anexo_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$anexo_chamado->selectParam("NOM_ARQUIVO_ORIGINAL");
	if($anexo_chamado->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum arquivo anexado.", 2);
	}else{
		$tabela = array();
		$header = array();
		$header[] = array("Arquivo", "left", "45%", "header");
		$header[] = array("Data", "center", "17%", "header");
		$header[] = array("Responsável", "center", "", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($anexo_chamado->database->result)){
			$header = array();
			$header[] = array("<a target=\"_blank\" href=\"anexos/".$row["nom_arquivo_sistema"]."\">".$row["nom_arquivo_original"]."</a>", "left", "", "");
			$header[] = array($row["dth_anexo"], "left", "", "");
			$header[] = array($row["nom_colaborador"], "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
	}

	$pagina->FechaTabelaPadrao();
	
	//================================================================================================================================
	// QUEM PODE APROVAR
	//================================================================================================================================
	if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){
		$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaAprovadores style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		$pagina->LinhaCampoFormularioColspanDestaque("Quem pode Aprovar", 2);
	
		require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
	   	$empregados = new empregados(1); 
	   	$empregados->select($banco->NUM_MATRICULA_SOLICITANTE); 
		
                /*
	   	if($empregados->COOR_ID != null && $empregados->COOR_ID!= ""){ 
			$empregados->SelectAprvadoresByCoordenacao($empregados->COOR_ID);
		}else if($empregados->UOR_ID != null && $empregados->UOR_ID != ""){
			
			$UOR   = $empregados->UOR_ID; 
			
			if($empregados->UOR_ID == $pagina->COD_UNIDADE_PRESIDENCIA){
				$UOR   .=",".$pagina->COD_UNIDADE_GABINETE_PRESIDENCIA;
			}
			
			$empregados->SelectAprvadoresByUnidade($UOR);
		}
		
		if($empregados->database->rows == 0){
			$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
		}else{
			$tabela = array();
			$header = array();
			$header[] = array("Nome", "center", "30%", "header");
			$header[] = array("E-mail", "center", "30%", "header");
			$header[] = array("Função Administrativa", "center", "30%", "header");
			 
			$tabela[] = $header;  
			 
	
			while ($row = pg_fetch_array($empregados->database->result)){				 
				$header = array();
				$header[] = array($row["nome"], "left", "", "");
				$header[] = array($row["des_email"], "left", "", ""); 
				$header[] = array($row["dsfuncaoadministrativa"], "left", "", "");
				$tabela[] = $header;
			}
			$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
		}
                 * 
                 */
                
                 $tabela = array();
                $header = array();
                $header[] = array("Nome", "left", "30%", "header");
                $header[] = array("E-mail", "center", "30%", "header");
                //$header[] = array("Função Administrativa", "center", "30%", "header");

                $tabela[] = $header;  
			 
                
               if($atividade_chamado_aux->NUM_MATRICULA_APROVADOR != ""){
                    require_once 'include/PHP/class/class.empregados.oracle.php';
                    $empregados = new empregados();
                    $header = array();
                    $empregados->GetNomeEmail($atividade_chamado_aux->NUM_MATRICULA_APROVADOR);
                    $header[] = array($empregados->NOME, "center", "", "");
                    $header[] = array($empregados->DES_EMAIL, "left", "", ""); 
                    //$header[] = array($row["dsfuncaoadministrativa"], "left", "", "");
                    $tabela[] = $header;
                }
                if($atividade_chamado_aux->NUM_MATRICULA_APROVADOR_SUBSTITUTO != ""){
                    require_once 'include/PHP/class/class.empregados.oracle.php';
                    $empregados = new empregados();
                    $header = array();
                    $empregados->GetNomeEmail($atividade_chamado_aux->NUM_MATRICULA_APROVADOR_SUBSTITUTO);
                    $header[] = array($empregados->NOME, "center", "", "");
                    $header[] = array($empregados->DES_EMAIL, "left", "", ""); 
                    //$header[] = array($row["dsfuncaoadministrativa"], "left", "", "");
                    $tabela[] = $header;
                }
                $pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
		$pagina->FechaTabelaPadrao();
	}

	$pagina->MontaRodape();
}else{
	$pagina->redirectTo("ChamadoAcompanhar.php");
}

?>