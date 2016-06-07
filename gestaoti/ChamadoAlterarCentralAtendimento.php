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
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

if($v_SEQ_CHAMADO != ""){
	// ============================================================================================================
	// Realizar o cadastro do chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$camposValidados = 1;

		require_once 'include/PHP/class/class.subtipo_chamado.php';
		require_once 'include/PHP/class/class.tipo_chamado.php';
		$subtipo_chamado = new subtipo_chamado();
		$tipo_chamado = new tipo_chamado();

		$mensagemErro = "";
		if($v_SEQ_CENTRAL_ATENDIMENTO == ""){
			$camposValidados = 0;
			$mensagemErro = "Central de Atendimento";
		}
		 
		if($camposValidados == 1){
			// Alterar chamado
			require_once 'include/PHP/class/class.chamado.php';
			$chamado = new chamado();
			$chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO); 
			
 
			if($v_SEQ_CENTRAL_ATENDIMENTO == $pagina->SEQ_CENTRAL_ATIVIDADES_AUXILIARES){
				// Pegar atividade default
				require_once '../gestaoti/include/PHP/class/class.parametro.php';
				$parametro = new parametro();
				if($v_SEQ_TIPO_OCORRENCIA == $parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_INCIDENTE")){
					$v_SEQ_ATIVIDADE_CHAMADO = $parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRIAGEM_INCIDENTE_CAA");
				}elseif($v_SEQ_TIPO_OCORRENCIA == $parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_SOLICITACAO")){
					$v_SEQ_ATIVIDADE_CHAMADO = $parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRIAGEM_SOLICITACAO_CAA");
				}elseif($v_SEQ_TIPO_OCORRENCIA == $parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_DUVIDA")){
					$v_SEQ_ATIVIDADE_CHAMADO = $parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRIAGEM_DUVIDA_CAA");
				}else{
					$v_SEQ_ATIVIDADE_CHAMADO = $parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRIAGEM_INCIDENTE_CAA");
				}
			}else {
				// Pegar atividade default
				require_once '../gestaoti/include/PHP/class/class.parametro.php';
				$parametro = new parametro();
				if($v_SEQ_TIPO_OCORRENCIA == $parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_INCIDENTE")){
					$v_SEQ_ATIVIDADE_CHAMADO = $parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRIAGEM_INCIDENTE");
				}elseif($v_SEQ_TIPO_OCORRENCIA == $parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_SOLICITACAO")){
					$v_SEQ_ATIVIDADE_CHAMADO = $parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRIAGEM_SOLICITACAO");
				}elseif($v_SEQ_TIPO_OCORRENCIA == $parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_DUVIDA")){
					$v_SEQ_ATIVIDADE_CHAMADO = $parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRIAGEM_DUVIDA");
				}else{
					$v_SEQ_ATIVIDADE_CHAMADO = $parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRIAGEM_INCIDENTE");
				}
			}
 			
			$chamado->setSEQ_ATIVIDADE_CHAMADO($v_SEQ_ATIVIDADE_CHAMADO);
			$chamado->encaminharChamado($v_SEQ_CHAMADO);
			
			// Redirecionar para a página de confirmação
			$pagina->redirectTo("ChamadoTriagemPesquisa.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");

		}else{
			$mensagemErro = "Os seguintes campos são obrigatórios: ".$mensagemErro;
		}
	}
	// ============================================================================================================
	// Início da página
	// ============================================================================================================
	require_once 'include/PHP/class/class.chamado.php';
	require_once 'include/PHP/class/class.situacao_chamado.php';
	require_once 'include/PHP/class/class.tipo_chamado.php';
	require_once 'include/PHP/class/class.tipo_ocorrencia.php';
	$tipo_ocorrencia = new tipo_ocorrencia();
	$tipo_chamado = new tipo_chamado();
	$banco = new chamado();
	$situacao_chamado = new situacao_chamado();
	// Verificar se o profissional possui um lançamento no Time Sheet em aberto para o chamado
	//require_once 'include/PHP/class/class.time_sheet.php';
	//$time_sheet = new time_sheet();
	//$v_FLG_ATENDIMENTO_INICIADO = $time_sheet->VerificarInicioAtividadeChamado($v_SEQ_CHAMADO, $_SESSION["NUM_MATRICULA_RECURSO"]);
	//if($v_FLG_ATENDIMENTO_INICIADO != "1"){
		// Redirecionar o profissional para a tela de atendimento
	//	$pagina->ScriptAlert("Inicie o atendimento do chamado antes de realizar uma ação.");
	//	$pagina->redirectToJS("ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
	//}

	// ============================================================================================================
	// Configurações AJAX
	// ============================================================================================================
	require_once 'include/PHP/class/class.Sajax.php';
	$Sajax = new Sajax();

	function CarregarComboTipoChamado($v_SEQ_TIPO_OCORRENCIA,$v_SEQ_CENTRAL_ATENDIMENTO){
		if($v_SEQ_TIPO_OCORRENCIA != ""){
			require_once 'include/PHP/class/class.pagina.php';
			require_once 'include/PHP/class/class.tipo_chamado.php';
			$pagina = new Pagina();
			$tipo_chamado = new tipo_chamado();
			$tipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
			$tipo_chamado->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
			return $pagina->AjaxFormataArrayCombo($tipo_chamado->combo("DSC_TIPO_CHAMADO"));
		}else{
			return "";
		}
	}

	function CarregarComboSubtipoChamado($v_SEQ_TIPO_CHAMADO, $v_SEQ_TIPO_OCORRENCIA){
		if($v_SEQ_TIPO_CHAMADO != ""){
			require_once 'include/PHP/class/class.pagina.php';
			require_once 'include/PHP/class/class.subtipo_chamado.php';
			$pagina = new Pagina();
			$subtipo_chamado = new subtipo_chamado();
			$subtipo_chamado->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
			$subtipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
			//$subtipo_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
			return $pagina->AjaxFormataArrayCombo($subtipo_chamado->combo("DSC_SUBTIPO_CHAMADO"));
		}else{
			return "";
		}
	}

	function CarregarComboAtividade($v_SEQ_SUBTIPO_CHAMADO, $v_SEQ_TIPO_OCORRENCIA){
		if($v_SEQ_SUBTIPO_CHAMADO != ""){
			require_once 'include/PHP/class/class.pagina.php';
			require_once 'include/PHP/class/class.atividade_chamado.php';
			$pagina = new Pagina();
			$atividade_chamado = new atividade_chamado();
			$atividade_chamado->setSEQ_SUBTIPO_CHAMADO($v_SEQ_SUBTIPO_CHAMADO);
			$atividade_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
			//$atividade_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
			return $pagina->AjaxFormataArrayCombo($atividade_chamado->combo("DSC_ATIVIDADE_CHAMADO"));
		}else{
			return "";
		}
	}

	function CarregarComboEdificacao($v_COD_DEPENDENCIA){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.edificacao.php';
		$pagina = new Pagina();
		$edificacao = new edificacao();
		$edificacao->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
		return $pagina->AjaxFormataArrayCombo($edificacao->comboSimples("NOM_EDIFICACAO"));
	}

	function CarregarComboLocalFisico($v_SEQ_EDIFICACAO){
		if($v_SEQ_EDIFICACAO != ""){
			require_once 'include/PHP/class/class.pagina.php';
			require_once 'include/PHP/class/class.localizacao_fisica.php';
			$pagina = new Pagina();
			$localizacao_fisica = new localizacao_fisica();
			$localizacao_fisica->setSEQ_EDIFICACAO($v_SEQ_EDIFICACAO);
			return $pagina->AjaxFormataArrayCombo($localizacao_fisica->combo("NOM_LOCALIZACAO_FISICA"));
		}else{
			return "";
		}
	}

	function ValidarPessoaContato($v_NUM_MATRICULA_CONTATO){
		if($v_NUM_MATRICULA_CONTATO != ""){
			require_once 'include/PHP/class/class.pagina.php';
			require_once 'include/PHP/class/class.empregados.oracle.php';
			$pagina = new Pagina();
			$empregados = new empregados();
			$primeiraLetra = substr(strtoupper($v_NUM_MATRICULA_CONTATO), 0, 1);
			if(!is_numeric($primeiraLetra)){
				$v_NUM_MATRICULA_CONTATO = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_CONTATO);
			}
			$empregados->select($v_NUM_MATRICULA_CONTATO);
			if($empregados->NOME != ""){
				return $v_NUM_MATRICULA_CONTATO."|".$empregados->NOME."|".$empregados->NUM_DDD."-".$empregados->NUM_VOIP;
			}else{
				return "";
			}
		}else{
			return "";
		}
	}

	function ValidarPatrimonio($v_NUM_PATRIMONIO){
		require_once 'include/PHP/class/class.patrimonio_ti.ativos.php';
		$ativos = new ativos();
		$ativos->select($v_NUM_PATRIMONIO);
		if($ativos->NUM_PATRIMONIO != ""){
			return $ativos->NOM_BEM."|".$ativos->NOM_MODELO."|".$ativos->DSC_LOCALIZACAO."|".$v_NUM_PATRIMONIO;
		}else{
			return "";
		}
	}

	function CarregarComboSistemaInformacao(){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.item_configuracao.php';
		$pagina = new Pagina();
		$item_configuracao = new item_configuracao();
		//$item_configuracao->setNUM_MATRICULA_GESTOR($_SESSION["NUM_MATRICULA_RECURSO"]);
		return $pagina->AjaxFormataArrayCombo($item_configuracao->combo("SIG_ITEM_CONFIGURACAO"));
	}

	function BuscarInfoAtividade($v_SEQ_ATIVIDADE_CHAMADO){
		if($v_SEQ_ATIVIDADE_CHAMADO == ""){
			return "Selecione a atividade";
		}else{
			require_once 'include/PHP/class/class.atividade_chamado.php';
			require_once 'include/PHP/class/class.tipo_ocorrencia.php';

			$atividade_chamado = new atividade_chamado();
			$tipo_ocorrencia = new tipo_ocorrencia();
			$atividade_chamado->select($v_SEQ_ATIVIDADE_CHAMADO);
			$vInfoAtividade = "";
			if($atividade_chamado->QTD_MIN_SLA_ATENDIMENTO == ""){
				$vInfoAtividade = "Medição de tempo: Horas Planejadas. ";
			}else{
				if($atividade_chamado->FLG_FORMA_MEDICAO_TEMPO == "C"){
					$vInfoAtividade = "Medição de tempo: Horas Corridas. ";
				}else{
					$vInfoAtividade = "Medição de tempo: Horas Úteis. ";
				}
				if($tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE == $atividade_chamado->SEQ_TIPO_OCORRENCIA){
					$vInfoAtividade .= "Tempo de Contingenciamento Máximo: ".$atividade_chamado->QTD_MIN_SLA_ATENDIMENTO." min. Solução Definitiva: ".$atividade_chamado->QTD_MIN_SLA_SOLUCAO_FINAL." min. ";
				}else{
					$vInfoAtividade .= "Tempo de Atendimento Máximo: ".$atividade_chamado->QTD_MIN_SLA_ATENDIMENTO." min. ";
				}
			}

			if($atividade_chamado->FLG_ATENDIMENTO_EXTERNO == "S"){
				$vInfoAtividade .= "Atendimento Externo.";
			}else{
				$vInfoAtividade .= "Atividade Interna.";
			}

			return rawurlencode($vInfoAtividade);
		}
	}

	$Sajax->sajax_init();
	$Sajax->sajax_debug_mode = 0;
	$Sajax->sajax_export("CarregarComboSubtipoChamado", "CarregarComboAtividade", "CarregarComboEdificacao", "CarregarComboLocalFisico", "ValidarPessoaContato", "ValidarPatrimonio", "CarregarComboTipoChamado", "CarregarComboSistemaInformacao", "BuscarInfoAtividade");
	$Sajax->sajax_handle_client_request();

	// ============================================================================================================
	// Configuração da págína
	// ============================================================================================================
	$pagina->SettituloCabecalho("Encaminhar Chamado para outra central de atendimento"); // Indica o título do cabeçalho da página
	$pagina->cea = 1;
	$pagina->method = "post";

	require_once 'include/PHP/class/class.chamado.php';
	$banco = new chamado();
	$banco->select($v_SEQ_CHAMADO);

	require_once 'include/PHP/class/class.situacao_chamado.php';
	$situacao_chamado = new situacao_chamado();

	require_once 'include/PHP/class/class.tipo_ocorrencia.php';
	$tipo_ocorrencia = new tipo_ocorrencia();

	$aItemAba = Array();
	$aItemAba = Array();
	//$aItemAba[] = array("#", "tabact", "Atendimento de 1º nível", "onclick=\"AcessarAcao('ChamadoAlterar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("ChamadoTriagem.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO", "", "Atendimento de 1º nível");
	$aItemAba[] = array("#", "tabact", "Encaminhar Chamado");
	$pagina->SetaItemAba($aItemAba);
	$pagina->estiloTabBar = "tabbarMenor";
	$pagina->flagScriptCalendario = 0;
	  

	$pagina->MontaCabecalho(1);

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_CHAMADO", $banco->SEQ_CHAMADO);
	print $pagina->CampoHidden("SEQ_CENTRAL_ATENDIMENTO", $_SEQ_CENTRAL_ATENDIMENTO);	 
	print $pagina->CampoHidden("v_SEQ_TIPO_OCORRENCIA", $banco->SEQ_TIPO_OCORRENCIA);
	

	// ============================================================================================================
	// Configurações AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		<?
		$Sajax->sajax_show_javascript();
		?>
		// Chamada
		function do_CarregarComboTipoChamado() {
			x_CarregarComboTipoChamado(document.form.v_SEQ_TIPO_OCORRENCIA.value,document.form.SEQ_CENTRAL_ATENDIMENTO.value, retorno_CarregarComboTipoChamado);
		}
		// Retorno
		function retorno_CarregarComboTipoChamado(val) {
			fEncheComboBox(val, document.form.v_SEQ_TIPO_CHAMADO);
			do_CarregarComboSubtipoChamado();
		}

		// Chamada
		function do_CarregarComboSubtipoChamado() {
			if(document.form.v_SEQ_TIPO_CHAMADO.value == "<?=$tipo_chamado->COD_TIPO_SISTEMAS_INFORMACAO?>"){
				document.getElementById("combo_sistema").style.display = "block";
				do_CarregarComboSistemaInformacao();
			}else{
				document.getElementById("combo_sistema").style.display = "none";
			}
			x_CarregarComboSubtipoChamado(document.form.v_SEQ_TIPO_CHAMADO.value, document.form.v_SEQ_TIPO_OCORRENCIA.value, retorno_CarregarComboSubtipoChamado);
		}
		// Retorno
		function retorno_CarregarComboSubtipoChamado(val) {
			fEncheComboBox(val, document.form.v_SEQ_SUBTIPO_CHAMADO);
			do_CarregarComboAtividade();
		}
		// Chamada
		function do_CarregarComboAtividade() {
			x_CarregarComboAtividade(document.form.v_SEQ_SUBTIPO_CHAMADO.value, document.form.v_SEQ_TIPO_OCORRENCIA.value, retorno_CarregarComboAtividade);
		}
		// Retorno
		function retorno_CarregarComboAtividade(val) {
			fEncheComboBox(val, document.form.v_SEQ_ATIVIDADE_CHAMADO);
		}

		// Chamada
		function do_BuscarInfoAtividade() {
			x_BuscarInfoAtividade(document.form.v_SEQ_ATIVIDADE_CHAMADO.value, retorno_BuscarInfoAtividade);
		}
		// Retorno
		function retorno_BuscarInfoAtividade(val) {
			document.getElementById('info_atividade').innerHTML = url_decode(val);
		}

		// Chamada
		function do_CarregarComboEdificacao() {
			x_CarregarComboEdificacao(document.form.v_COD_DEPENDENCIA.value, retorno_CarregarComboEdificacao);
		}
		// Retorno
		function retorno_CarregarComboEdificacao(val) {
			fEncheComboBox(val, document.form.v_SEQ_EDIFICACAO);
		}
		// Chamada
		function do_CarregarComboLocalFisico() {
			x_CarregarComboLocalFisico(document.form.v_SEQ_EDIFICACAO.value, retorno_CarregarComboLocalFisico);
		}
		// Retorno
		function retorno_CarregarComboLocalFisico(val) {
			fEncheComboBox(val, document.form.v_SEQ_LOCALIZACAO_FISICA);
		}
		// Chamada
		function do_ValidarPessoaContato() {
			if(document.form.v_NUM_MATRICULA_CONTATO.value != ""){
				window.dados_pessoa_contato.innerHTML = "carregando....";
				v_NUM_MATRICULA_CONTATO = document.form.v_NUM_MATRICULA_CONTATO.value.replace(/A-Z/i, '');
				v_NUM_MATRICULA_CONTATO = v_NUM_MATRICULA_CONTATO.replace( /[^0-9\.]/, '' );
				x_ValidarPessoaContato(v_NUM_MATRICULA_CONTATO, retorno_ValidarPessoaContato);
			}
		}
		// Retorno
		function retorno_ValidarPessoaContato(val) {
			// Separar os valores retornados
			if(val != ""){
				//  $v_NUM_MATRICULA_CONTATO."|".$empregados->NOME."|".$empregados->NUM_DDD."-".$empregados->NUM_VOIP;
				v_NUM_MATRICULA_CONTATO = val.substr(0, val.indexOf("|"));
				StringRestante = val.substr(val.indexOf("|")+1, val.length);
				v_NOME = StringRestante.substr(0, StringRestante.indexOf("|"));
				StringRestante = StringRestante.substr(StringRestante.indexOf("|")+1, StringRestante.length);
				v_TELEFONE = StringRestante;
				// Adicionar resultado ao formulário
				document.form.v_NUM_MATRICULA_CONTATO_REAL.value = v_NUM_MATRICULA_CONTATO;
				window.dados_pessoa_contato.innerHTML = "Nome: <b>"+v_NOME+"</b> - Ramal: <b>"+v_TELEFONE+"</b>";
			}else{
				alert("Pessoa não encontrada. Clique na imagem de lupa para efetuar uma pesquisa.");
				window.dados_pessoa_contato.innerHTML = "Preencha este campo caso o atendimento não seja direcionado a pessoa autenticada no sistema.";
				document.form.v_NUM_MATRICULA_CONTATO.value = "";
			}
		}
		// Chamada
		function do_ValidarPatrimonio(){
			if(document.form.v_NUM_PATRIMONIO.value != ""){
				if(!VerificarExistenciaValorCombo(document.form.v_PATRIMONIOS, document.form.v_NUM_PATRIMONIO.value)){
					document.getElementById("dados_patrimonio").innerHTML = "carregando....";
					x_ValidarPatrimonio(document.form.v_NUM_PATRIMONIO.value, retorno_ValidarPatrimonio);
				}else{
					alert("Patrimônio já adionado ao chamado");
				}
			}
		}
		// Retorno
		function retorno_ValidarPatrimonio(val) {
			// Separar os valores retornados
			document.getElementById("dados_patrimonio").innerHTML = "Preencha este campo com o número existente na plaqueta de patrimônio.";
			if(val != ""){
				//  $ativos->NOM_BEM."|".$ativos->NOM_MODELO."|".$ativos->NOM_DETENTOR
				v_NOM_BEM = val.substr(0, val.indexOf("|"));
				StringRestante = val.substr(val.indexOf("|")+1, val.length);
				v_NOM_MODELO = StringRestante.substr(0, StringRestante.indexOf("|"));
				StringRestante = StringRestante.substr(StringRestante.indexOf("|")+1, StringRestante.length);
				v_NOM_DETENTOR = StringRestante.substr(0, StringRestante.indexOf("|"));
				StringRestante = StringRestante.substr(StringRestante.indexOf("|")+1, StringRestante.length);
				v_NUM_PATRIMONIO = StringRestante;
				// Adicionar resultado ao formulário
				document.getElementById("comboPatrimonio").style.display = "block";
				ValorCombo = v_NUM_PATRIMONIO+" - "+v_NOM_BEM+" - Local: "+v_NOM_DETENTOR;
				fAdicionaValorCombo(v_NUM_PATRIMONIO, ValorCombo, document.form.v_PATRIMONIOS);
				document.form.v_NUM_PATRIMONIO.value = "";
			}else{
				alert("Patrimônio não encontrado.");
				document.getElementById("v_NUM_PATRIMONIO").value = "";
			}
		}
		// Chamada
		function do_CarregarComboSistemaInformacao() {
			x_CarregarComboSistemaInformacao(retorno_CarregarComboSistemaInformacao);
		}
		// Retorno
		function retorno_CarregarComboSistemaInformacao(val) {
			fEncheComboBox(val, document.form.v_SEQ_ITEM_CONFIGURACAO);
			<?
			if($banco->SEQ_ITEM_CONFIGURACAO != ""){
				?>
				document.form.v_SEQ_ITEM_CONFIGURACAO.value = "<?=$banco->SEQ_ITEM_CONFIGURACAO?>";
				<?
			}
			?>
		}
		// ==================================================== FIM AJAX =====================================

		// =======================================================================
		// Controlar a saída às ações do chamado
		// =======================================================================
		function AcessarAcao(vDestino){
			validarSaida = false;
			window.location.href = vDestino;
		}

		function ExcluirPatrimonio(){
			retorno = fExcluirValorCombo(document.form.v_PATRIMONIOS);
			if(document.form.v_PATRIMONIOS.options.length == 0){
				document.getElementById("comboPatrimonio").style.display = "none";
			}
		}

		function AnexaNovoArquivo($ID){
			if(document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).value != ""){
				document.getElementById("Newfile"+$ID).style.display = "none";
				$novo = $ID + 1;
				document.getElementById("file"+$novo).style.display = "block";
				document.getElementById("Newfile"+$novo).style.display = "block";
			}else{
				alert("É necessário anexar um arquivo antes de adionar um novo.");
				document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).focus();
			}

		}

		function ExcluirArquivo($ID){
			document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).value = "";
			document.getElementById("file"+$ID).style.display = "none";
			document.getElementById("Newfile"+$ID).style.display = "none";
		}

		function fValidaFormLocal(){
		 
			// Validar campos
			if(document.form.v_SEQ_CENTRAL_ATENDIMENTO.value == ""){
				alert("Preencha o campo Central de Atendimento");
				document.form.v_SEQ_CENTRAL_ATENDIMENTO.focus();
				return false;
			}
			 

			return true;
		}

		// =======================================================================
		// Controle de Saída da Página
		// =======================================================================
		// Gestão de Eventos
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
		// Flag de validação da saída do fomulário
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
	<style>
		#combo_multiple {
			font-family: Verdana;
			width: 615px;
			size: 3;
			font-size: 10px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
	}
	#CampoSelect {
		font-family: Verdana;
		width: 400px;
		font-size: 10px;
		color: #000000;
		border-color: #000000;
		border-style: double;
		border-width: 1px;
		background-color: #f6f5f5;
	}
	</style>
	<?
	if($mensagemErro != ""){
		$pagina->ScriptAlert($mensagemErro);
	}

	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	//$pagina->LinhaCampoFormularioColspanDestaque("Dados do chamado", 2);
	$pagina->LinhaCampoFormularioColspanDestaque("Dados do chamado", 2);
	// ============================================================================================================
	// Dados do chamado
	// ============================================================================================================
	 
	$pagina->LinhaCampoFormulario("Número:", "right", "N", $banco->SEQ_CHAMADO, "left", "id=".$pagina->GetIdTable(), "20%");
	
	// Montar a combo da tabela tipo_chamado
	$tipo_ocorrencia->FLG_EXIBE_IMPROCEDENTE = 1;
	$tipo_ocorrencia->select($banco->SEQ_TIPO_OCORRENCIA);
	//$pagina->LinhaCampoFormulario("Tipo:", "right", "S",  , "Escolha", "do_CarregarComboTipoChamado()", "CampoSelect"), "left", "id=".$pagina->GetIdTable(), "20%");
	$pagina->LinhaCampoFormulario("Tipo:", "right", "N", $tipo_ocorrencia->NOM_TIPO_OCORRENCIA, "left", "id=".$pagina->GetIdTable());
	
	//$banco->SEQ_TIPO_OCORRENCIA
	//$pagina->LinhaCampoFormulario("Tipo:", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_OCORRENCIA", "S", "Classe", "S", $tipo_ocorrencia->combo(1, $banco->SEQ_TIPO_OCORRENCIA), "Escolha", "do_CarregarComboTipoChamado()", "CampoSelect"), "left", "id=".$pagina->GetIdTable(), "20%");
 
	require_once '../gestaoti/include/PHP/class/class.central_atendimento.php';
	// Montar a combo da tabela central_atendimento
	$central_atendimento = new central_atendimento(); 
	$central_atendimento->SEQ_CENTRAL_ATENDIMENTO_REMOVER = $_SEQ_CENTRAL_ATENDIMENTO;
	$pagina->LinhaCampoFormulario("Central de Atendimento:", "right", "S", $pagina->CampoSelect("v_SEQ_CENTRAL_ATENDIMENTO", "S", "Central de Atendimento", "S", $central_atendimento->combo(2,$v_SEQ_CENTRAL_ATENDIMENTO), "Escolha", "do_CarregarComboTipoChamado()"), "left", "id=".$pagina->GetIdTable(), "30%", "70%");
		
	
	// Descição do chamado
	$pagina->LinhaCampoFormulario("Solicitação:", "right", "N", $banco->TXT_CHAMADO, "left", "id=".$pagina->GetIdTable());

	 
	//$pagina->LinhaCampoFormularioColspan("center", "<hr><div align=left><font color=red>*</font> - Preenchimento obrigatório</div>", "2");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormLocal(); ", " Enviar "), "2");
	$pagina->FechaTabelaPadrao();
	?>
	<script language="javascript">
	// Inicializar campo matrícula da pessoa de contato
		<?
		if($banco->NUM_MATRICULA_CONTATO != ""){
			?>
			do_ValidarPessoaContato();
			<?
		}
		?>
	</script>
	<?
	$pagina->MontaRodape();
}else{
	$pagina->ScriptAlert("Selecione o chamado.");
	$pagina->redirectToJS("ChamadoTriagemPesquisa.php");
}
?>