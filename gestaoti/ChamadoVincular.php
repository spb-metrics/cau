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
require_once 'include/PHP/class/class.chamado.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

if($v_SEQ_CHAMADO != ""){
	// ============================================================================================================
	// Realizar o cadastro do chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$camposValidados = 1;
		$mensagemErro = "";
		if(count($vinculo) == 0){
		 	$vErroCampos = "Selecione pelo menos um chamado para vincular";
		}

		if($vErroCampos == ""){
			require_once 'include/PHP/class/class.vinculo_chamado.php';
			$vinculo_chamado = new vinculo_chamado();
			$vinculo_chamado->setSEQ_CHAMADO_MASTER($v_SEQ_CHAMADO);
			$vinculo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);

			$chamado = new chamado();

			for($i=0; $i<count($vinculo);$i++){
				$vinculo_chamado->setSEQ_CHAMADO_FILHO($vinculo[$i]);
				$vinculo_chamado->insert();

				// Inserir no hist�ricos dos filhos
				$historico_chamado = new historico_chamado();
				$historico_chamado->setSEQ_CHAMADO($vinculo[$i]);
				$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
				$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
				$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
				$historico_chamado->setTXT_HISTORICO("Chamado vinculado ao chamado ".$v_SEQ_CHAMADO);
				$historico_chamado->insert();

				// Verificar se o chamado filho est� em situa��o diferente do pai
				$chamado->select($vinculo[$i]);
				if($chamado->SEQ_SITUACAO_CHAMADO != $v_SEQ_SITUACAO_CHAMADO){
					// Alterar Situa��o
					$chamado->AtualizaSituacao($vinculo[$i], $v_SEQ_SITUACAO_CHAMADO);

					// Incluir no hist�rico
					$historico_chamado = new historico_chamado();
					$historico_chamado->setSEQ_CHAMADO($vinculo[$i]);
					$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
					$historico_chamado->setSEQ_MOTIVO_SUSPENCAO("");
					$historico_chamado->setTXT_HISTORICO("Situa��o alterada automaticamente para se adequar ao chamado master ".$v_SEQ_CHAMADO);
					$historico_chamado->insert();
				}

				// Atualizar atribuicao
				require_once 'include/PHP/class/class.atribuicao_chamado.php';
				$atribuicao_chamado = new atribuicao_chamado();
				$atribuicao_chamado->setTXT_ATIVIDADE("Atribui��o registrada automaticamente pelo chamado master ".$v_SEQ_CHAMADO);
				$atribuicao_chamado->AtualizarAtribuicao($vinculo[$i], $_SESSION["SEQ_EQUIPE_TI"], $_SESSION["NUM_MATRICULA_RECURSO"], $v_SEQ_SITUACAO_CHAMADO, 1, 1);

				// Verificar se existem outras atribui��es com a situa��o diferente
				$atribuicao_chamado = new atribuicao_chamado();
				$atribuicao_chamado->setSEQ_CHAMADO($vinculo[$i]);
				$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO("CODS_EM_ANDAMENTO");
				$atribuicao_chamado->selectParam();
				if($atribuicao_chamado->database->rows > 0){
					while ($rowAtribuicao = pg_fetch_array($atribuicao_chamado->database->result)){
						$atribuicao_chamado->setTXT_ATIVIDADE("Atribui��o registrada automaticamente no momento da vincula��o com o chamado ".$vinculo[$i]);
						$atribuicao_chamado->AtualizarAtribuicao($v_SEQ_CHAMADO, $rowAtribuicao["seq_equipe_ti"], $rowAtribuicao["num_matricula"], $v_SEQ_SITUACAO_CHAMADO, 1, 1);
					}
				}
			}

			// Redirecionar para a p�gina de confirma��o
			$pagina->redirectTo("ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
		}
	}
	// ============================================================================================================
	// In�cio da p�gina
	// ============================================================================================================

	// Verificar se o profissional possui um lan�amento no Time Sheet em aberto para o chamado
	require_once 'include/PHP/class/class.time_sheet.php';
	$time_sheet = new time_sheet();
	$v_FLG_ATENDIMENTO_INICIADO = $time_sheet->VerificarInicioAtividadeChamado($v_SEQ_CHAMADO, $_SESSION["NUM_MATRICULA_RECURSO"]);
	if($v_FLG_ATENDIMENTO_INICIADO != "1"){
		// Redirecionar o profissional para a tela de atendimento
		$pagina->ScriptAlert("Inicie o atendimento do chamado antes de realizar uma a��o.");
		$pagina->redirectToJS("ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
	}

	// ============================================================================================================
	// Configura��o da p�g�na
	// ============================================================================================================
	$pagina->SettituloCabecalho("Hist�rico de Acontecimentos"); // Indica o t�tulo do cabe�alho da p�gina
	$pagina->cea = 1;
	$pagina->method = "post";

	require_once 'include/PHP/class/class.subtipo_chamado.php';
	require_once 'include/PHP/class/class.tipo_chamado.php';
	require_once 'include/PHP/class/class.prioridade_chamado.php';
	require_once 'include/PHP/class/class.tipo_ocorrencia.php';
	$tipo_ocorrencia = new tipo_ocorrencia();

	$banco = new chamado();
	$banco->select($v_SEQ_CHAMADO);

	require_once 'include/PHP/class/class.situacao_chamado.php';
	$situacao_chamado = new situacao_chamado();

	$aItemAba = Array();
	$aItemAba[] = array("#", "", "Detalhes", "onclick=\"AcessarAcao('ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Alterar", "onclick=\"AcessarAcao('ChamadoAlterar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Atribuir", "onclick=\"AcessarAcao('ChamadoAtribuir.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Atendimento", "onclick=\"AcessarAcao('ChamadoRegistroAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Suspender", "onclick=\"AcessarAcao('ChamadoSuspender.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
	$aItemAba[] = array("#", "", "Cancelar", "onclick=\"AcessarAcao('ChamadoCancelar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
	$aItemAba[] = array("#", "", "Devolver 1� n�vel", "onclick=\"AcessarAcao('ChamadoDevolver1Nivel.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");

	// Se for poss�vel realizar o contigenciamento do chamado
	if($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Atendimento || $banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Em_Andamento || $banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Suspenca ){
		$aItemAba[] = array("#", "", "Contingenciar", "onclick=\"AcessarAcao('ChamadoContingenciar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	}
	$aItemAba[] = array("#", "tabact", "Vincular", "onclick=\"AcessarAcao('ChamadoVincular.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");

	// Adicionar a aba Encerrar caso tenha o prazo do chamado definido
	if($banco->DTH_ENCERRAMENTO_PREVISAO != ""){
		$aItemAba[] = array("#", "", "Encerrar", "onclick=\"AcessarAcao('ChamadoEncerramento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	}

	$pagina->SetaItemAba($aItemAba);
	$pagina->estiloTabBar = "tabbarMenor";
	$pagina->flagScriptCalendario = 0;

	$pagina->MontaCabecalho(1);

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_CHAMADO", $v_SEQ_CHAMADO);
	print $pagina->CampoHidden("v_SEQ_SITUACAO_CHAMADO", $banco->SEQ_SITUACAO_CHAMADO);

	// ============================================================================================================
	// Configura��es AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		// =======================================================================
		// Controlar a sa�da �s a��es do chamado
		// =======================================================================
		function AcessarAcao(vDestino){
			validarSaida = false;
			window.location.href = vDestino;
		}

		function fValidaFormLocal(){
			var name = "vinculo[]";
			var frm = document.form;
			var aux = 0;
			// Verificar se algum assunto foi selecionado
			for(i=0; i < frm.length; i++){
		        //Verifica se o elemento do formul�rio corresponde a um checkbox e se � o checkbox desejado
		        if (frm.elements[i].type == "checkbox" &&  frm.elements[i].name == name ) {
	                //Verifica se o checkbox foi selecionado
	                if(frm.elements[i].checked) {
	                    return true;
	                }
		        }
		    }
		    alert("Selecione pelo menos um chamado.");
		    return false;
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
				// default warning message
				var msg = "Tem certeza que deseja sair da tela de atendimento antes de parar o atendimento do chamado?";

				// set event
				if (!e) { e = window.event; }
				if (e) { e.returnValue = msg; }
				// return warning message
				return msg;
			}
		}

		// Initialise
		addEvent(window, 'load', addListeners, false);
	</script>
	<?
	if($mensagemErro != ""){
		$pagina->ScriptAlert($mensagemErro);
	}

	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Vincular chamados de mesma origem", 2);

	// Descri��o
	$pagina->LinhaCampoFormulario("Porque vincular?", "right", "N",
								  "Para facilitar a gest�o sobre m�ltiplos incidentes ocorridos aparentenmente pela mesma causa raiz. <br>
								   Todos os chamados vinculados a um chamado master, ter�o suas situa��es alteradas automaticamente conforme o master. Cada chamado manter� o seu pr�prio SLA."
									  , "left", "id=".$pagina->GetIdTable(), "20%");
	$pagina->LinhaCampoFormulario("Regras para a vincula��o:", "right", "N",
								  "Apenas podem ser vinculados a este chamado os chamados que possuam a mesma classifica��o, que estejam atribu�os a sua equipe e que estejam em uma das seguintes situa��es: Aguardando atendimento, Em atendimento, Suspenso ou Contingenciado.
								  <br>ATEN��O: Ser�o replicados a este chamado todas as atribui��es existentes nos chamados vinculados.
								  "
									  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormularioColspan("center",
				$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormLocal(); ", " Vincular ")
				, "2");
	$pagina->FechaTabelaPadrao();

	print $pagina->LinhaVazia(1);
	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");

	$pagina->LinhaCampoFormularioColspanDestaque("Selecione os chamados que deseja vincular", 2);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("&nbsp;", "2%");
	$header[] = array("Prioridade", "10%");
	$header[] = array("Chamado", "10%");
	$header[] = array("Atividade", "20%");
	$header[] = array("Solicita��o", "20%");
	$header[] = array("Situa��o", "15%");
	$header[] = array("Abertura", "10%");
	$header[] = array("Vencimento", "10%");
	$header[] = array("SLA", "5%");

	// Setar vari�veis
//	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$v_SEQ_ATIVIDADE_CHAMADO = $banco->SEQ_ATIVIDADE_CHAMADO;
	$banco = new chamado();
	$banco->setSEQ_ATIVIDADE_CHAMADO($v_SEQ_ATIVIDADE_CHAMADO);
	$banco->setSEQ_TIPO_OCORRENCIA($tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE);
	$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->CODS_EM_ANDAMENTO);
	//$banco->setDTH_ABERTURA($banco->DTH_ABERTURA);
	$banco->setFLG_VINCULO($v_SEQ_CHAMADO);
	$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$banco->selectParam("DTH_ABERTURA DESC");
	if($banco->database->rows > 0){
		$corpo = array();
//		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Chamados pass�veis de vincula��o", $header);
		$vLink = "?flag=1&v_SEQ_CHAMADO_PESQUISA=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL";
		while ($row = pg_fetch_array($banco->database->result)){

			$corpo[] = array("left", "campo", $pagina->CampoCheckboxSimples("vinculo[]", $row["seq_chamado"], "", ""));

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

			$pagina->LinhaTabelaResultado($corpo, "");
			$corpo = "";
		}
		$pagina->FechaTabelaPadrao();
//		$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_CHAMADO=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL&v_SEQ_TIPO_OCORRENCIA=$v_SEQ_TIPO_OCORRENCIA");
	}else{
		$pagina->LinhaColspan("center", "Chamados pass�veis de vincula��o", "2", "header");
		$pagina->LinhaColspan("left", "Nenhum chamado encontrado", "2", "campo");
		$pagina->FechaTabelaPadrao();
	}


	$pagina->FechaTabelaPadrao();

	$pagina->MontaRodape();
}else{
	$pagina->ScriptAlert("Selecione o chamado.");
	$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
}
?>