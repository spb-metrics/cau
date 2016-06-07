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
require_once 'include/PHP/class/class.pagina.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();
if($v_SEQ_CHAMADO != ""){
	require_once 'include/PHP/class/class.chamado.php';
	require_once 'include/PHP/class/class.situacao_chamado.php';
	require_once 'include/PHP/class/class.time_sheet.php';
	require_once 'include/PHP/class/class.atribuicao_chamado.php';
	require_once 'include/PHP/class/class.historico_chamado.php';
	
	require_once 'include/PHP/class/class.subtipo_chamado.php';
	require_once 'include/PHP/class/class.atividade_chamado.php';
	$banco = new chamado();
	$situacao_chamado = new situacao_chamado();
	$atividade_chamado_aux = new atividade_chamado();
	
	$subtipo_chamado_aux = new subtipo_chamado();
	// ============================================================================================================
	// Configura��es AJAX
	// ============================================================================================================
	require_once 'include/PHP/class/class.Sajax.php';
	$Sajax = new Sajax();

	function Inicio_Efetivo($v_DTH_INICIO_EFETIVO, $v_DTH_INICIO_PREVISAO){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/dateObj.class.php';
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
				return "<font color=red>Chamado n�o iniciado - Em atraso de <b>$vTempoRestante</b></font>";
			}else{
				return "<font color=green>Chamado n�o iniciado - O tempo estimado para o in�cio do atendimento &eacute; de <b>$vTempoRestante</b></font>";
			}
		}
	}

	function Encerramento_Efetivo($v_DTH_ENCERRAMENTO_EFETIVO, $v_DTH_ENCERRAMENTO_PREVISAO){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/dateObj.class.php';
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
				return "<font color=red>Chamado n&atilde;o encerrado - Em atraso de <b>$vTempoRestante</b></font>";
			}else{
				return "<font color=green>Chamado n&atilde;o encerrado - O tempo estimado para o encerramento &eacute; de <b>$vTempoRestante</b></font>";
			}
		}
	}

	function Carrega_DTH_INICIO_EFETIVO($v_SEQ_CHAMADO){
		if($v_SEQ_CHAMADO != ""){
			require_once 'include/PHP/class/class.chamado.php';
			$chamado = new chamado();
			$chamado->select($v_SEQ_CHAMADO);
			return $chamado->DTH_INICIO_EFETIVO;
		}else{
			return "";
		}
	}

	function ContagemRegressiva($v_DTH_ENCERRAMENTO_EFETIVO, $v_DTH_ENCERRAMENTO_PREVISAO){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/dateObj.class.php';
		$myDate = new dateObj();
		$pagina = new Pagina();
		if($v_DTH_ENCERRAMENTO_EFETIVO != ""){
			$vSegundosDiferenca = $pagina->dateDiffHourPlus(str_replace("-","/",$v_DTH_ENCERRAMENTO_EFETIVO), $v_DTH_ENCERRAMENTO_PREVISAO);
			if($vSegundosDiferenca < 0){ // Chamado em atraso
				$vTempoRestante = $pagina->secondsToTime($vSegundosDiferenca*-1);
				return "<font color=red>Encerrado com atraso</font>";
			}else{
				return "<font color=green>Encerrado dentro do prazo</font>";
			}
		}else{
			$vTempoRestante = $pagina->FormatarDataResumido($myDate->diff($v_DTH_ENCERRAMENTO_PREVISAO, 'all'));
			if($pagina->dateDiffHour($v_DTH_ENCERRAMENTO_PREVISAO) < 0){ // Chamado em atraso
				return "<font color=red><b>-$vTempoRestante</b></font>";
			}else{
				return "<font color=green><b>$vTempoRestante</b></font>";
			}
		}
	}

	$Sajax->sajax_init();
	$Sajax->sajax_debug_mode = 0;
	$Sajax->sajax_export("Inicio_Efetivo", "Encerramento_Efetivo", "Carrega_DTH_INICIO_EFETIVO", "ContagemRegressiva");
	$Sajax->sajax_handle_client_request();
	// ============================================================================================================
	// Fim das Configura��es AJAX
	// ============================================================================================================

	$pagina->SettituloCabecalho("Detalhamento do Chamado"); // Indica o t�tulo do cabe�alho da p�gina

	// pesquisa
	$banco->select($v_SEQ_CHAMADO);

	// Adicionar registro de acesso
	require_once 'include/PHP/class/class.historico_acesso_chamado.php';
	$historico_acesso_chamado = new historico_acesso_chamado();
	$historico_acesso_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$historico_acesso_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$historico_acesso_chamado->insert();

	$pagina->flagScriptCalendario = 0;

	// Itens das abas
	$aItemAba = Array( array("ChamadoPesquisa.php?flag=1&v_SEQ_CHAMADO=$v_SEQ_CHAMADO_PESQUISA&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_TIPO_OCORRENCIA=$v_SEQ_TIPO_OCORRENCIA&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL&vNumPagina=$vNumPagina", "", "Pesquisa"),
	 			       array("#", "tabact", "Detalhes")
					 );

	$pagina->SetaItemAba($aItemAba);

	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_DTH_INICIO_EFETIVO", $banco->DTH_INICIO_EFETIVO);

	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informa��es Gerais ".$pagina->BotaoImprimir("ChamadoAtendimentoPesquisaImprimirPDF.php?imprimir[]=".$banco->SEQ_CHAMADO,"TARGET=XXX" ), 2);

	$pagina->LinhaCampoFormulario("N�mero:", "right", "N", $banco->SEQ_CHAMADO, "left", "id=".$pagina->GetIdTable(),"23%","");

	require_once 'include/PHP/class/class.subtipo_chamado.php';
	$subtipo_chamado = new subtipo_chamado();
	$subtipo_chamado->select($banco->SEQ_SUBTIPO_CHAMADO);

	require_once 'include/PHP/class/class.tipo_ocorrencia.php';
	$tipo_ocorrencia = new tipo_ocorrencia();
	$tipo_ocorrencia->select($banco->SEQ_TIPO_OCORRENCIA);
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").":", "right", "N", $tipo_ocorrencia->NOM_TIPO_OCORRENCIA, "left", "id=".$pagina->GetIdTable());

	require_once 'include/PHP/class/class.tipo_chamado.php';
	$tipo_chamado = new tipo_chamado();
	$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", "right", "N", $tipo_chamado->DSC_TIPO_CHAMADO, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").":", "right", "N", $subtipo_chamado->DSC_SUBTIPO_CHAMADO, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").":", "right", "N", $banco->DSC_ATIVIDADE_CHAMADO, "left", "id=".$pagina->GetIdTable());

	$situacao_chamado->select($banco->SEQ_SITUACAO_CHAMADO);
	$pagina->LinhaCampoFormulario("Situa��o:", "right", "N", "<span id=\"situacao\">".$situacao_chamado->DSC_SITUACAO_CHAMADO."</span>", "left", "id=".$pagina->GetIdTable());
	if($banco->SEQ_MOTIVO_CANCELAMENTO != ""){
		require_once 'include/PHP/class/class.motivo_cancelamento.php';
		$motivo_cancelamento = new motivo_cancelamento();
		$motivo_cancelamento->select($banco->SEQ_MOTIVO_CANCELAMENTO);
		$pagina->LinhaCampoFormulario("Motivo de cancelamento:", "right", "N", $motivo_cancelamento->DSC_MOTIVO_CANCELAMENTO, "left", "id=".$pagina->GetIdTable());
	}
	$pagina->LinhaCampoFormulario("Solicita��o:", "right", "N", str_replace(chr(13), "<br>",$banco->TXT_CHAMADO), "left", "id=".$pagina->GetIdTable());

	if($banco->SEQ_ITEM_CONFIGURACAO != ""){
		require_once 'include/PHP/class/class.item_configuracao.php';
		$item_configuracao = new item_configuracao();
		$item_configuracao->select($banco->SEQ_ITEM_CONFIGURACAO);
		$pagina->LinhaCampoFormulario("Sistema de Informa��o:", "right", "N", $item_configuracao->NOM_ITEM_CONFIGURACAO, "left", "id=".$pagina->GetIdTable());
	}

	if($banco->SEQ_ACAO_CONTINGENCIAMENTO != ""){
		require_once 'include/PHP/class/class.acao_contingenciamento.php';
		$acao_contingenciamento = new acao_contingenciamento();
		$acao_contingenciamento->select($banco->SEQ_ACAO_CONTINGENCIAMENTO);
		$pagina->LinhaCampoFormulario("A��o de contingenciamento:", "right", "N", $acao_contingenciamento->NOM_ACAO_CONTINGENCIAMENTO, "left", "id=".$pagina->GetIdTable());
	}

	// Exibir o texto de resolu��o
	if($banco->TXT_RESOLUCAO != ""){
		$pagina->LinhaCampoFormulario("Informa��es sobre a solu��o:", "right", "N", str_replace(chr(13), "<br>",$banco->TXT_RESOLUCAO), "left", "id=".$pagina->GetIdTable());
	}

	// Exibir o texto de contingenciamento caso exista
	if($banco->TXT_CONTINGENCIAMENTO != ""){
		$pagina->LinhaCampoFormulario("Observa��o sobre o contingenciamento:", "right", "N", str_replace(chr(13), "<br>",$banco->TXT_CONTINGENCIAMENTO), "left", "id=".$pagina->GetIdTable());
	}

	// Exibir a causa raiz, caso exista
	if($banco->TXT_CAUSA_RAIZ != ""){
		$pagina->LinhaCampoFormulario("Causa raiz:", "right", "N", str_replace(chr(13), "<br>",$banco->TXT_CAUSA_RAIZ), "left", "id=".$pagina->GetIdTable());
	}

	// AVALIA�OES DO CLIENTE
	if($banco->SEQ_AVALIACAO_ATENDIMENTO != ""){
		$pagina->LinhaColspan("center", "Avalia��o do cliente sobre o atendimento recebido", 2, "header");

		$pagina->LinhaCampoFormulario("Solicita��o Atendida?", "right", "N", $banco->FLG_SOLICITACAO_ATENDIDA=="S"?"Sim":"N�o", "left", "id=".$pagina->GetIdTable(), "30%");

		// Avalia��o do Atendimento
		require_once '../gestaoti/include/PHP/class/class.avaliacao_atendimento.php';
		$avaliacao_atendimento = new avaliacao_atendimento();

		$avaliacao_atendimento->select($banco->SEQ_AVALIACAO_ATENDIMENTO);
		$pagina->LinhaCampoFormulario("Satisfa��o com a solu��o apresentada:", "right", "N", $avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO, "left", "id=".$pagina->GetIdTable());

		if($banco->SEQ_AVALIACAO_CONHECIMENTO_TECNICO != ""){
			$avaliacao_atendimento->select($banco->SEQ_AVALIACAO_CONHECIMENTO_TECNICO);
			$pagina->LinhaCampoFormulario("Satisfa��o com o conhecimento t�cnico do prestador de servi�o:", "right", "N", $avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO, "left", "id=".$pagina->GetIdTable());
		}

		if($banco->SEQ_AVALIACAO_POSTURA != ""){
			$avaliacao_atendimento->select($banco->SEQ_AVALIACAO_POSTURA);
			$pagina->LinhaCampoFormulario("Satisfa��o com a postura e cordialidade do prestador de servi�o:", "right", "N", $avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO, "left", "id=".$pagina->GetIdTable());
		}

		if($banco->SEQ_AVALIACAO_TEMPO_ESPERA != ""){
			$avaliacao_atendimento->select($banco->SEQ_AVALIACAO_TEMPO_ESPERA);
			$pagina->LinhaCampoFormulario("Satisfa��o com o tempo de espera para atendimento:", "right", "N", $avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO, "left", "id=".$pagina->GetIdTable());
		}

		if($banco->SEQ_AVALIACAO_TEMPO_SOLUCAO != ""){
			$avaliacao_atendimento->select($banco->SEQ_AVALIACAO_TEMPO_SOLUCAO);
			$pagina->LinhaCampoFormulario("Satisfa��o com o tempo de solu��o:", "right", "N", $avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO, "left", "id=".$pagina->GetIdTable());
		}

		$pagina->LinhaCampoFormulario("Observa��o do cliente sobre a avalia��o:", "right", "N", $banco->TXT_AVALIACAO, "left", "id=".$pagina->GetIdTable());
	}
	
  $atividade_chamado_aux->select($banco->SEQ_ATIVIDADE_CHAMADO);
   $subtipo_chamado_aux->select($atividade_chamado_aux->SEQ_SUBTIPO_CHAMADO);
   
   if($atividade_chamado_aux->SEQ_TIPO_OCORRENCIA == $pagina->SEQ_TIPO_OCORRENCIA_SOLICITACAO && $subtipo_chamado_aux->SEQ_TIPO_CHAMADO ==$pagina->SEQ_CLASSE_CHAMADO_TRANSPORTE ){
   		$pagina->LinhaCampoFormulario("Requisi��o de Transporte:", "right", "N", 
   		"<a  title=\"Imprimir Requisi��o de Transporte\" target=\"XXX\" href=\"RelatorioRequisicaoTransporteParaServicoPopup.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO."\">Clique aqui para imprimir</a>"
   		, "left", "id=".$pagina->GetIdTable());
   }
   
   	if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){
	   
	   	// APROVADOR
	  	require_once '../gestaoti/include/PHP/class/class.aprovacao_chamado_superior.php';				
		$aprovacao_chamado_superior = new aprovacao_chamado_superior(); 
		$aprovacao_chamado_superior->selectByIdChamado($banco->SEQ_CHAMADO);
		
		if($aprovacao_chamado_superior->SEQ_APROVACAO_CHAMADO_SUPERIOR){
			
			require_once 'include/PHP/class/class.empregados.oracle.php';
			$empregados = new empregados();
			$empregados->select($aprovacao_chamado_superior->NUM_MATRICULA);
			
			$pagina->LinhaCampoFormulario("Respons�vel pela aprova��o:", "right", "N", 
	   		$empregados->NOME	, "left", "id=".$pagina->GetIdTable());
		}
	}
	

	// Identificar se o chamado possui etapas programadas
	require_once 'include/PHP/class/class.etapa_chamado.php';
	$etapa_chamado = new etapa_chamado();
	$etapa_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$etapa_chamado->selectParam("DTH_INICIO_PREVISTO");

	// ============================================================================================================
	// Configura��es AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		<?
		$Sajax->sajax_show_javascript();
		?>
		// Chamada
		function do_ControlarAtividadeTimeSheet() {
			// Alterar a mensagem de atendimento
			window.MsgAtendimento.innerHTML = "carregando....";
			// Executar lan�amento
			x_ControlarAtividadeTimeSheet(<?=$banco->SEQ_CHAMADO?>, document.form.v_FLG_ATENDIMENTO_INICIADO.value, <?=$banco->SEQ_SITUACAO_CHAMADO?>, retorno_ControlarAtividadeTimeSheet);
		}
		// Retorno
		function retorno_ControlarAtividadeTimeSheet(val) {
			if(val != ""){
				// Alterar flg hidden
				document.form.v_FLG_ATENDIMENTO_INICIADO.value = val;
				// Alterar a mensagem de atendimento e Alterar label do bot�o
				if(document.form.v_FLG_ATENDIMENTO_INICIADO.value == 1){
					document.form.atividade.value = " Parar ";
					window.MsgAtendimento.innerHTML = "Clique aqui para parar o <b>atendimento</b>";
				}else{
					document.form.atividade.value = " Iniciar ";
					window.MsgAtendimento.innerHTML = "Clique aqui para iniciar o <b>atendimento</b>";
				}
				// Atualizar situa��o
				window.situacao.innerHTML = "Em Andamento";
				// Recarregar iframe do time sheet e do hist�rico
				document.getElementById('IframeHistorico').contentWindow.location.reload(true);
				document.getElementById('profissionais').contentWindow.location.reload(true);
				document.getElementById('time_sheet').contentWindow.location.reload(true);
				// Atualiza Data de in�cio efetivo
				do_Carrega_DTH_INICIO_EFETIVO();
			}else{
				alert("Erro ao lan�ar hora");
				if(document.form.v_FLG_ATENDIMENTO_INICIADO.value == 1){
					window.MsgAtendimento.innerHTML = "Clique aqui para parar o <b>atendimento</b>";
				}else{
					window.MsgAtendimento.innerHTML = "Clique aqui para iniciar o <b>atendimento</b>";
				}
			}
		}
		// Chamada
		function do_Inicio_Efetivo(v_DTH_INICIO_EFETIVO, v_DTH_INICIO_PREVISAO) {
			x_Inicio_Efetivo(v_DTH_INICIO_EFETIVO, v_DTH_INICIO_PREVISAO, retorno_Inicio_Efetivo);
		}
		// Retorno
		function retorno_Inicio_Efetivo(val) {
			//document.getElementById('SPAN_Inicio_Efetivo').innerHTML = val;
		}
		// Chamada
		function do_Encerramento_Efetivo(v_DTH_ENCERRAMENTO_EFETIVO, v_DTH_ENCERRAMENTO_PREVISAO) {
			x_Encerramento_Efetivo(v_DTH_ENCERRAMENTO_EFETIVO, v_DTH_ENCERRAMENTO_PREVISAO, retorno_Encerramento_Efetivo);
		}
		// Retorno
		function retorno_Encerramento_Efetivo(val) {
			document.getElementById('SPAN_Encerramento_Efetivo').innerHTML = val;
		}
		// Chamada
		function do_Carrega_DTH_INICIO_EFETIVO() {
			x_Carrega_DTH_INICIO_EFETIVO(<?=$banco->SEQ_CHAMADO?>, retorno_Carrega_DTH_INICIO_EFETIVO);
		}
		// Retorno
		function retorno_Carrega_DTH_INICIO_EFETIVO(val) {
			document.form.v_DTH_INICIO_EFETIVO.value = val;
		}
		// Chamada
		function do_ContagemRegressiva(v_DTH_ENCERRAMENTO_EFETIVO, v_DTH_ENCERRAMENTO_PREVISAO) {
			x_ContagemRegressiva(v_DTH_ENCERRAMENTO_EFETIVO, v_DTH_ENCERRAMENTO_PREVISAO, retorno_ContagemRegressiva);
		}
		// Retorno
		function retorno_ContagemRegressiva(val) {
			document.getElementById('contagem_regressiva').innerHTML = val;
		}

		// ==================================================== FIM AJAX ==================================================

		// =======================================================================
		// Controle dos rel�gios decrescentes
		// =======================================================================
		function CarregaInicioEfetivo(){
			do_Inicio_Efetivo(document.form.v_DTH_INICIO_EFETIVO.value, '<?=$banco->DTH_INICIO_PREVISAO?>');
		}

		function CarregaEncerramentoEfetivo(){
			do_Encerramento_Efetivo('<?=$banco->DTH_ENCERRAMENTO_EFETIVO?>', '<?=$banco->DTH_ENCERRAMENTO_PREVISAO?>');
			do_ContagemRegressiva('<?=$banco->DTH_ENCERRAMENTO_EFETIVO?>', '<?=$banco->DTH_ENCERRAMENTO_PREVISAO?>');
		}

		CarregaInicioEfetivo();
		CarregaEncerramentoEfetivo();

		//var intervaloInicioEfetivo = window.setInterval("CarregaInicioEfetivo()", 1000);
		//clearInterval(intervaloInicioEfetivo);
		//var intervaloEncerramentoEfetivo = window.setInterval("CarregaEncerramentoEfetivo()", 1000);

		// =======================================================================
		// Controlar a sa�da �s a��es do chamado
		// =======================================================================
		function AcessarAcao(vDestino){
			if(document.form.v_FLG_ATENDIMENTO_INICIADO.value == 1){
				validarSaida = false;
				window.location.href = vDestino;
			}else{
				validarSaida = true;
				alert("Inicie o atendimento antes de realizar qualquer a��o.");
			}
		}

		// =======================================================================
		// Configura��o das TABS
		// =======================================================================
		function fMostra(id, idTab){
			//alert("fMostra = "+validarSaida);
			validarSaida = false;
                        
			document.getElementById("tabelaSLA").style.display = "none";
			document.getElementById("tabSLA").attributes["class"].value = "";

			document.getElementById("tabelaMeusDados").style.display = "none";
			document.getElementById("tabMeusDados").attributes["class"].value = "";

			document.getElementById("tabelaHistorico").style.display = "none";
			document.getElementById("tabHistorico").attributes["class"].value = "";

			document.getElementById("tabelaAcessos").style.display = "none";
			document.getElementById("tabAcessos").attributes["class"].value = "";

			document.getElementById("tabelaProfissionais").style.display = "none";
			document.getElementById("tabProfissionais").attributes["class"].value = "";

			document.getElementById("tabelaTimeSheet").style.display = "none";
			document.getElementById("tabTimeSheet").attributes["class"].value = "";

			document.getElementById("tabelaAtendimento").style.display = "none";
			document.getElementById("tabAtendimento").attributes["class"].value = "";
                         
                        <? if($pagina->flg_usar_funcionalidades_patrimonio == "S"){ ?>
                            document.getElementById("tabelaPatrimonio").style.display = "none";
                            document.getElementById("tabPatrimonio").attributes["class"].value = "";
                        <? } ?>
                        
			document.getElementById("tabelaAnexos").style.display = "none";
			document.getElementById("tabAnexos").attributes["class"].value = "";
                        
                        <? if($atividade_chamado_aux->getFLG_EXIGE_APROVACAO() == "1"){ ?>
                            document.getElementById("tabelaAprovadores").style.display = "none";
                            document.getElementById("tabAprovadores").attributes["class"].value = "";
                        <? } ?>
			<? if($banco->QTD_MIN_SLA_ATENDIMENTO == ""){ ?>
					document.getElementById("tabelaPrevisao").style.display = "none";
					document.getElementById("tabPrevisao").attributes["class"].value = "";
			<? } ?>
			<? if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){ ?>
					document.getElementById("tabelaVinculo").style.display = "none";
					document.getElementById("tabVinculo").attributes["class"].value = "";
			<? } ?>
			<? if($etapa_chamado->database->rows > 0){ ?>
					document.getElementById("tabelaEtapas").style.display = "none";
					document.getElementById("tabEtapas").attributes["class"].value = "";
			<? } ?>

			document.getElementById(id).style.display = "block";
			document.getElementById(idTab).attributes["class"].value = "tabact";
                        
			validarSaida = true;
		}

		// =======================================================================
		// Controle de Sa�da da P�gina
		// =======================================================================
		// Gest�o de Eventos
		// Cross browser event handling for IE 5+, NS6+ and Gecko
		function addEvent(elm, evType, fn, useCapture){
			if (elm.addEventListener){
				// Gecko
				elm.addEventListener(evType, fn, useCapture);
				return true;
			}
			else if (elm.attachEvent){
				// Internet Explorer
				var r = elm.attachEvent('on' + evType, fn);
				return r;
			}else{
				// nutscrape?
				elm['on' + evType] = fn;
			}
		}

		function removeEvent(elm, evType, fn, useCapture){
            if (elm.removeEventListener) {
                // Gecko
                elm.removeEventListener(evType, fn, useCapture);
                return true;
            }
            else
                if (elm.attachEvent) {
                    // Internet Explorer
                    var r = elm.detachEvent('on' + evType, fn);
                    return r;
                }
                else {
                    // FF, NS etc..
                    elm['on' + evType] = '';
                }
        }

		// Add Listeners
		function addListeners(e){
			// Before unload listener
			addEvent(window, 'beforeunload', exitAlert, false);
		}
		// Flag de valida��o da sa�da do fomul�rio
		var validarSaida = true;
		// Exit Alert
		function exitAlert(e){
			//alert("Exit = "+validarSaida);
			if(validarSaida) {
				if(document.form.v_FLG_ATENDIMENTO_INICIADO.value == 1){
					// default warning message
					var msg = "Tem certeza que deseja sair da tela de atendimento antes de parar o atendimento do chamado?";

					// set event
					if (!e) { e = window.event; }
					if (e) { e.returnValue = msg; }
					// return warning message
					return msg;
				}
			}
		}

		// Initialise
		//addEvent(window, 'load', addListeners, false);
		// <input type="button" class="button" value="Save" onclick="removeEvent(window, 'beforeunload', exitAlert, false); location.href='../list/overview.asp'" />


	</script>

<?
	$aItemAba = Array();
	$aItemAba[] = array("javascript:fMostra('tabelaSLA','tabSLA')", "tabact", "&nbsp;SLA&nbsp;", "tabSLA", "onclick=\"validarSaida=false;\"");
	if($banco->QTD_MIN_SLA_ATENDIMENTO == ""){
		$aItemAba[] = array("javascript:fMostra('tabelaPrevisao','tabPrevisao')", "", "&nbsp;Previs�o&nbsp;", "tabPrevisao", "onclick=\"validarSaida=false;\"");
	}
	if($etapa_chamado->database->rows > 0){
		$aItemAba[] = array("javascript:fMostra('tabelaEtapas','tabEtapas')", "", "&nbsp;Etapas&nbsp;", "tabEtapas", "onclick=\"validarSaida=false;\"");
	}
	$aItemAba[] = array("javascript:fMostra('tabelaMeusDados','tabMeusDados')", "", "&nbsp;Solicitante&nbsp;", "tabMeusDados", "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaHistorico','tabHistorico')", "", "&nbsp;Hist�rico&nbsp;", "tabHistorico",  "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaAcessos','tabAcessos')", "", "&nbsp;Acessos&nbsp;", "tabAcessos",  "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaTimeSheet','tabTimeSheet')", "", "&nbsp;Time Sheet&nbsp;", "tabTimeSheet", "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaProfissionais','tabProfissionais')", "", "&nbsp;Profissionais&nbsp;", "tabProfissionais", "onclick=\"validarSaida=false;\"");
	$aItemAba[] = array("javascript:fMostra('tabelaAtendimento','tabAtendimento')", "", "&nbsp;Atendimento&nbsp;", "tabAtendimento", "onclick=\"validarSaida=false;\"");
	if($pagina->flg_usar_funcionalidades_patrimonio == "S"){
            $aItemAba[] = array("javascript:fMostra('tabelaPatrimonio','tabPatrimonio')", "", "&nbsp;Patrim�nio(s)&nbsp;", "tabPatrimonio", "onclick=\"validarSaida=false;\"");
        }
        $aItemAba[] = array("javascript:fMostra('tabelaAnexos','tabAnexos')", "", "&nbsp;Anexo(s)&nbsp;", "tabAnexos", "onclick=\"validarSaida=false;\"");
	if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
		$aItemAba[] = array("javascript:fMostra('tabelaVinculo','tabVinculo')", "", "&nbsp;V&iacute;nculos&nbsp;", "tabVinculo", "onclick=\"validarSaida=false;\"");
	}
	
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
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaSLA cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Gest�o de N�vel de Servi�o", 2);
	$tabela = array();

	require_once 'include/PHP/class/dateObj.class.php';
	$myDate = new dateObj();

	$header = array();
	// Data de Abertura
	$header = array();
	$header[] = array("Data de Abertura:", "center", "23%", "label");
	$header[] = array($banco->DTH_ABERTURA, "left", "", "campo");
	$tabela[] = $header;

	// ======================================================================================================
	// TRIAGEM
	if($banco->DTH_TRIAGEM_EFETIVA != ""){
		// C�lculo do tempo de triagem em minutos corridos
		if($banco->FLG_FORMA_MEDICAO_TEMPO == "C"){
			$v_DTH_TRIAGEM_PREVISAO = $pagina->add_minutos($banco->QTD_MIN_SLA_TRIAGEM, $banco->DTH_ABERTURA);
		}else{ // C�lculo do tempo em minutos �teis
			$v_DTH_TRIAGEM_PREVISAO = $pagina->add_minutos_uteis($banco->QTD_MIN_SLA_TRIAGEM, $banco->DTH_ABERTURA, $banco->HoraInicioExpediente, $banco->HoraInicioIntervalo, $banco->HoraFimIntervalo, $banco->HoraFimExpediente, $banco->aDtFeriado);
		}

		$header = array();
		$header[] = array("Previs�o de atendimento 1� n�vel:", "center", "", "label");
		$header[] = array($v_DTH_TRIAGEM_PREVISAO, "left", "", "campo");
		$tabela[] = $header;

		$vSegundosDiferenca = $pagina->dateDiffHourPlus(str_replace("-","/",$banco->DTH_TRIAGEM_EFETIVA), $v_DTH_TRIAGEM_PREVISAO);
		if($vSegundosDiferenca < 0){ // Chamado em atraso
			$vTempoRestante = $pagina->secondsToTime($vSegundosDiferenca*-1);
			$vTempoRestante = "<font color=red>Encerrado com atraso de $vTempoRestante</font>";
		}else{
			$vTempoRestante = "<font color=green>Encerrado dentro do prazo</font>";
		}

		// Triagem Efetiva
		$header = array();
		$header[] = array("Conclus�o do atendimento 1� n�vel:", "center", "", "label");
		$header[] = array("".$banco->DTH_TRIAGEM_EFETIVA." - ".$vTempoRestante, "left", "", "campo");
		$tabela[] = $header;

	}

	// ===================================================================================================
	// Data de in�cio efetivo
	$header = array();
	$header[] = array("Data de in�cio:", "center", "23%", "label");
	if($banco->DTH_INICIO_EFETIVO != ""){
		$header[] = array(str_replace("-","/",$banco->DTH_INICIO_EFETIVO), "left", "", "campo");
	}else{
		$header[] = array("Ainda n�o iniciado", "left", "", "campo");
	}
	$tabela[] = $header;

	// ===================================================================================================
	// Data de contigenciamento
	if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
		$historico_chamado = new historico_chamado();
		$v_DTH_CONTINGENCIAMENTO = $historico_chamado->GetDthContingenciamento($banco->SEQ_CHAMADO);
		if($v_DTH_CONTINGENCIAMENTO != ""){

			$v_DTH_CONTINGENCIAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($banco->DTH_ABERTURA, $banco->QTD_MIN_SLA_ATENDIMENTO, $banco->FLG_FORMA_MEDICAO_TEMPO, $banco->SEQ_CHAMADO, $banco->SEQ_TIPO_OCORRENCIA, $banco->QTD_MIN_SLA_ATENDIMENTO);

			$header = array();
			$header[] = array("Previs�o de Contingenciamento:", "center", "23%", "label");
			$header[] = array($v_DTH_CONTINGENCIAMENTO_PREVISAO, "left", "", "campo");
			$tabela[] = $header;

			$vSegundosDiferenca = $pagina->dateDiffHourPlus(str_replace("-","/",$v_DTH_CONTINGENCIAMENTO), $v_DTH_CONTINGENCIAMENTO_PREVISAO);
			if($vSegundosDiferenca < 0){ // Chamado em atraso
				$vTempoRestante = $pagina->secondsToTime($vSegundosDiferenca*-1);
				$vTempoRestante = "<font color=red>Contingenciado com atraso de $vTempoRestante</font>";
			}else{
				$vTempoRestante = "<font color=green>Contingenciado dentro do prazo</font>";
			}

			// Contingenciamento Efetivo
			$header = array();
			$header[] = array("Contingenciamento Efetivo:", "center", "", "label");
			$header[] = array("".$v_DTH_CONTINGENCIAMENTO." - ".$vTempoRestante, "left", "", "campo");
			$tabela[] = $header;
		}
	}

	// ====================================================================================================
	// Data de encerramento
	if($banco->DTH_ENCERRAMENTO_PREVISAO != ""){
		// Previs�o de encerramento
		$header = array();
		$header[] = array("Previs�o de encerramento:", "center", "23%", "label");
		$header[] = array(str_replace("-","/",$banco->DTH_ENCERRAMENTO_PREVISAO), "left", "", "campo");
		$tabela[] = $header;
		// Encerramento efetivo
		$header = array();
		$header[] = array("Encerramento Efetivo:", "center", "", "label");
		$header[] = array("<span id=\"SPAN_Encerramento_Efetivo\">".Encerramento_Efetivo($banco->DTH_ENCERRAMENTO_EFETIVO, $banco->DTH_ENCERRAMENTO_PREVISAO)."</span>", "left", "", "campo");
		$tabela[] = $header;
	}else{
		$tabela = array();
		$header = array();
		// Data de Abertura
		$header = array();
		$header[] = array("Prazo n�o estabelecido. Aguardando estimativa.", "left", "", "campo");
		$tabela[] = $header;
	}

	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Previs�o
	//================================================================================================================================
	if($banco->QTD_MIN_SLA_ATENDIMENTO == ""){ // Apenas para demandas de SLA P�s estabelecido
		$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaPrevisao style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		$pagina->LinhaCampoFormularioColspanDestaque("Planejamento de encerramento do chamado", 2);

		require_once 'include/PHP/class/class.aprovacao_chamado.php';
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
			$header[] = array("Observa��o", "center", "", "header");
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
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("IframeEtapas", "Etapa_chamadoPesquisaChamado.php?flagReadOnly=1&v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
		$pagina->FechaTabelaPadrao();
	}

	//================================================================================================================================
	// Dados do Solicitante
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaMeusDados style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informa��es sobre o solicitante", 2);
	require_once 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();
	$empregados->select($banco->NUM_MATRICULA_SOLICITANTE);

	$tabela = array();
	$header = array();
	// Nome
	$header = array();
	$header[] = array("Nome:", "center", "23%", "label");
	$header[] = array($empregados->NOME, "left", "", "campo");
	$tabela[] = $header;

	// Depend�ncia
	$header = array();
	$header[] = array("Diretoria:", "center", "", "label");
	$header[] = array($empregados->DEP_SIGLA, "left", "", "campo");
	$tabela[] = $header;

	// Lota��o
	$header = array();
	$header[] = array("Lota��o:", "center", "", "label");
	$header[] = array($empregados->UOR_SIGLA, "left", "", "campo");
	$tabela[] = $header;

	// E-mail
	$header = array();
	$header[] = array("E-mail:", "center", "", "label");
	$header[] = array($empregados->DES_EMAIL, "left", "", "campo");
	$tabela[] = $header;

	// Matr�cula
	$header = array();
	$header[] = array("Matr�cula:", "center", "", "label");
	$header[] = array($empregados->NUM_MATRICULA_RECURSO, "left", "", "campo");
	$tabela[] = $header;

	// Ramal
	$header = array();
	$header[] = array("Ramal:", "center", "", "label");
	$header[] = array($empregados->NUM_DDD." ".$empregados->NUM_VOIP, "left", "", "campo");
	$tabela[] = $header;

	// Localiza��o do cliente
	if($banco->SEQ_LOCALIZACAO_FISICA != ""){
		$header = array();
		require_once 'include/PHP/class/class.localizacao_fisica.php';
		$localizacao_fisica = new localizacao_fisica();
		$localizacao_fisica->select($banco->SEQ_LOCALIZACAO_FISICA);

		require_once 'include/PHP/class/class.edificacao.php';
		$edificacao = new edificacao();
		$edificacao->select($localizacao_fisica->SEQ_EDIFICACAO);

		require_once 'include/PHP/class/class.dependencias.php';
		$dependencias = new dependencias();
		$vSIG_DEPENDENCIA = $dependencias->GetSiglaDependencia($edificacao->COD_DEPENDENCIA);

		$header[] = array("Localiza��o:", "center", "", "label");
		$header[] = array($vSIG_DEPENDENCIA." - ".$edificacao->NOM_EDIFICACAO." - ".$localizacao_fisica->NOM_LOCALIZACAO_FISICA, "left", "", "campo");
		$tabela[] = $header;
	}

	// Pessoa de contato
	if($banco->NUM_MATRICULA_CADASTRANTE != ""){
		$empregados = new empregados();
		$empregados->select($banco->NUM_MATRICULA_CADASTRANTE);
		$header = array();
		$header[] = array("Chamado cadastrado por:", "center", "", "label");
		$header[] = array($empregados->NOME." - Ramal: ".$empregados->NUM_DDD."-".$empregados->NUM_VOIP, "left", "", "campo");
		$tabela[] = $header;
	}

	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Hist�rico
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaHistorico style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Hist�rico do Chamado", 2);
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("IframeHistorico", "Historico_chamadoPesquisaChamado.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Acessos
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaAcessos style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Registro de acessos �s informa��es do chamado", 2);
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("acessos", "Historico_acesso_chamadoPesquisaChamado.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Profissionais
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaProfissionais style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Equipes/Profissionais respons�veis pelo atendimento do chamado", 2);
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("profissionais", "Atribuicao_chamadoPesquisaAtendimento.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Time Sheet
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaTimeSheet style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Registro de horas trabalhadas no chamado", 2);
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Iframe("time_sheet", "Time_sheetPesquisaChamado.php?v_SEQ_CHAMADO=".$banco->SEQ_CHAMADO, $_SESSION["screenWidth"], "300", "no"), 2);
	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	// Atendimento
	//================================================================================================================================
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaAtendimento style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Registro de informa��es sobre o atendimento realizado ", 2);

	require_once 'include/PHP/class/class.atendimento_chamado.php';
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
		$header[] = array("Observa��o", "center", "", "header");
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
	// Patrim�nio
	//================================================================================================================================
        if($pagina->flg_usar_funcionalidades_patrimonio == "S"){
            $pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaPatrimonio style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
            $pagina->LinhaCampoFormularioColspanDestaque("Itens do patrim�nio da empresa envolvidos com o chamado ", 2);

            require_once 'include/PHP/class/class.patrimonio_chamado.php';
            $patrimonio_chamado = new patrimonio_chamado();
            $patrimonio_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
            $patrimonio_chamado->selectParam("NUM_PATRIMONIO");
            if($patrimonio_chamado->database->rows == 0){
                    $pagina->LinhaCampoFormularioColspan("left", "Nenhum patrim�nio informado.", 2);
            }else{
                    $tabela = array();
                    $header = array();
                    $header[] = array("N�mero", "center", "10%", "header");
                    $header[] = array("Descri��o", "center", "40%", "header");
    //		$header[] = array("Detentor", "center", "25%", "header");
                    $header[] = array("Localiza��o", "center", "", "header");
                    $tabela[] = $header;

                    require_once 'include/PHP/class/class.patrimonio_ti.ativos.php';
                    $ativos = new ativos();

                    while ($row = pg_fetch_array($patrimonio_chamado->database->result)){
                            $ativos = new ativos();
                            $ativos->select($row["num_patrimonio"]);
                            $header = array();
                            $header[] = array($row["num_patrimonio"], "center", "", "");
                            $header[] = array($ativos->NOM_BEM, "left", "", "");
    //			$header[] = array($ativos->NOM_DETENTOR, "left", "", "");
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

	require_once 'include/PHP/class/class.anexo_chamado.php';
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
		$header[] = array("Respons�vel", "center", "", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($anexo_chamado->database->result)){
			$header = array();
			$header[] = array("<a target=\"_blank\" href=\"../cau/anexos/".$row["nom_arquivo_sistema"]."\">".$row["nom_arquivo_original"]."</a>", "left", "", "");
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
			//$empregados->SelectAprvadoresByUnidade($empregados->UOR_ID);
		}
		
		if($empregados->database->rows == 0){
			$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
		}else{
			$tabela = array();
			$header = array();
			$header[] = array("Nome", "left", "30%", "header");
			$header[] = array("E-mail", "center", "30%", "header");
			$header[] = array("Fun��o Administrativa", "center", "30%", "header");
			 
			$tabela[] = $header;  
			 
	
			while ($row = pg_fetch_array($empregados->database->result)){				 
				$header = array();
				$header[] = array($row["nome"], "center", "", "");
				$header[] = array($row["des_email"], "left", "", ""); 
				$header[] = array($row["dsfuncaoadministrativa"], "left", "", "");
				$tabela[] = $header;
			}
			$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, $_SESSION["screenWidth"],"",true,"","","1","1"), 2);
		}
                 */
                
                $tabela = array();
                $header = array();
                $header[] = array("Nome", "left", "30%", "header");
                $header[] = array("E-mail", "center", "30%", "header");
                //$header[] = array("Fun��o Administrativa", "center", "30%", "header");

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
	

	//================================================================================================================================
	// V�nculo
	//================================================================================================================================
	require_once 'include/PHP/class/class.prioridade_chamado.php';
	$pagina->AbreTabelaPadrao("center", $_SESSION["screenWidth"], "id=tabelaVinculo style=\"display: none;\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" ");

	$header = array();
	$header[] = array("Prioridade", "10%");
	$header[] = array("Chamado", "10%");
	$header[] = array("Atividade", "20%");
	$header[] = array("Solicita��o", "20%");
	$header[] = array("Situa��o", "15%");
	$header[] = array("Abertura", "10%");
	$header[] = array("Vencimento", "10%");
	$header[] = array("SLA", "5%");

	$pagina->LinhaCampoFormularioColspanDestaque("Chamados vinculados a esse", count($header));

	// Setar vari�veis
//	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$v_SEQ_ATIVIDADE_CHAMADO = $banco->SEQ_ATIVIDADE_CHAMADO;
	$chamado = new chamado();
	$chamado->setSEQ_CHAMADO_MASTER($banco->SEQ_CHAMADO);
	$chamado->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$chamado->selectParam("DTH_ABERTURA DESC");
	if($chamado->database->rows > 0){
		$corpo = array();
		$pagina->LinhaHeaderTabelaResultado("", $header);
		while ($row = pg_fetch_array($chamado->database->result)){
			// Prioridade
			$prioridade_chamado = new prioridade_chamado();
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

			// Solicita��o
			$corpo[] = array("left", "campo", $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

			// Situa��o
			$situacao_chamado = new situacao_chamado();
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

			$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoDetalhe.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
			$corpo = "";
		}
		$pagina->FechaTabelaPadrao();
	}else{
		$pagina->LinhaColspan("left", "Nenhum chamado vinculado", "2", "campo");
		$pagina->FechaTabelaPadrao();
	}

	$pagina->MontaRodape();
}else{
	$pagina->redirectTo("ChamadoPesquisa.php");
}

?>