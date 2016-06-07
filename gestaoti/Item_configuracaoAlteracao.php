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
require 'include/PHP/class/class.item_configuracao_software.php';
//require 'include/PHP/class/class.empregados.php';
require 'include/PHP/class/class.empregados.oracle.php';
//require 'include/PHP/class/class.unidades_organizacionais.php';
$pagina = new Pagina();
$banco = new item_configuracao();
$item_configuracao = new item_configuracao();
$empregados = new empregados();
//$unidades_organizacionais = new unidades_organizacionais();
$item_configuracao_software = new item_configuracao_software();

if($flag == ""){

	// ============================================================================================================
	// Configurações AJAX
	// ============================================================================================================
	require 'include/PHP/class/class.Sajax.php';
	$Sajax = new Sajax();

	function CarregarComboEquipe($v_COD_DEPENDENCIA){
		if($v_COD_DEPENDENCIA != ""){
			require_once 'include/PHP/class/class.pagina.php';
			require_once 'include/PHP/class/class.equipe_ti.php';
			$pagina = new Pagina();
			$equipe_ti = new equipe_ti();
			$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
			return $pagina->AjaxFormataArrayCombo($equipe_ti->combo("NOM_EQUIPE_TI"));
		}else{
			return "";
		}
	}

	$Sajax->sajax_init();
	$Sajax->sajax_debug_mode = 0;
	$Sajax->sajax_export("CarregarComboEquipe");
	$Sajax->sajax_handle_client_request();

	// ============================================================================================================
	// Configuração da págína
	// ============================================================================================================

	$pagina->setMethod("post");
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Item do Parque Tecnológico"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
		 			    array("#", "tabact", "Alteração") );
	$pagina->SetaItemAba($aItemAba);
	// pesquisa
	$banco->select($v_SEQ_ITEM_CONFIGURACAO);

	// Inicio do formulário
	$pagina->MontaCabecalho();

	// ============================================================================================================
	// Configurações AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		<?
		$Sajax->sajax_show_javascript();
		?>
		// Chamada
		function do_CarregarComboEquipe() {
			x_CarregarComboEquipe("", retorno_CarregarComboEquipe);
		}
		// Retorno
		function retorno_CarregarComboEquipe(val) {
			fEncheComboBox(val, document.form.v_SEQ_EQUIPE_TI);
		}
	</script>
	<?

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", $banco->SEQ_ITEM_CONFIGURACAO);
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais", 2);

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.tipo_item_configuracao.php';
	$tipo_item_configuracao = new tipo_item_configuracao();
	$tipo_item_configuracao->select($banco->SEQ_TIPO_ITEM_CONFIGURACAO);

	// Adicionar combo no formulário
	print $pagina->CampoHidden("v_SEQ_TIPO_ITEM_CONFIGURACAO", $banco->SEQ_TIPO_ITEM_CONFIGURACAO);
	$pagina->LinhaCampoFormulario("Tipo:", "right", "S", $tipo_item_configuracao->NOM_TIPO_ITEM_CONFIGURACAO, "left", "v_SEQ_TIPO_ITEM_CONFIGURACAO", "30%", "70%");

	$pagina->LinhaCampoFormulario("Sigla:", "right", "N", $pagina->CampoTexto("v_SIG_ITEM_CONFIGURACAO", "N", "", "30", "30", $banco->SIG_ITEM_CONFIGURACAO), "left");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO", "S", "Nome", "60", "60", $banco->NOM_ITEM_CONFIGURACAO), "left");

	// Montar a combo da tabela
//	require 'include/PHP/class/class.dependencias.php';
//	$dependencias = new dependencias();

	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$equipe_ti->select($banco->SEQ_EQUIPE_TI);
//	$v_COD_DEPENDENCIA = $equipe_ti->COD_DEPENDENCIA;
	$equipe_ti = new equipe_ti();
//	$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);

	$pagina->LinhaCampoFormulario("Equipe Responsável:", "right", "S",
//								$pagina->CampoSelect("v_COD_DEPENDENCIA", "N", "Dependencia", "S", $dependencias->comboSimplesEquipe("DEP_SIGLA", $v_COD_DEPENDENCIA), "Escolha", "do_CarregarComboEquipe()")."&nbsp;".
								$pagina->CampoSelect("v_SEQ_EQUIPE_TI", "S", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI", $banco->SEQ_EQUIPE_TI), "Escolha")
								, "left");

	$pagina->LinhaCampoFormulario("Matricula do Gestor:", "right", "S",
								  $pagina->CampoTexto("v_NUM_MATRICULA_GESTOR", "S", "Matrícula do Gestor" , "10", "10", $empregados->GetNomLoginRedeMatricula($banco->NUM_MATRICULA_GESTOR), "readonly").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_GESTOR")
								  , "left");

	$pagina->LinhaCampoFormulario("Matricula do Líder:", "right", "S",
								  $pagina->CampoTexto("v_NUM_MATRICULA_LIDER", "S", "Matrícula do Líder" , "11", "11", $empregados->GetNomLoginRedeMatricula($banco->NUM_MATRICULA_LIDER), "readonly").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_LIDER", "TI")
								  , "left");

	$pagina->LinhaCampoFormulario("Unidade organizacional Gestora:", "right", "S",
								  $pagina->CampoTexto("v_COD_UOR_AREA_GESTORA", "S", "Unidade Organizacional responsavel", "10", "10", $banco->COD_UOR_AREA_GESTORA, "readonly").
								  $pagina->ButtonProcuraUorg("v_COD_UOR_AREA_GESTORA", "")
								  , "left");

	$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $pagina->CampoTextArea("v_TXT_ITEM_CONFIGURACAO", "N", "Descrição", "59", "2", $banco->TXT_ITEM_CONFIGURACAO), "left");


	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.tipo_disponibilidade.php';
	$tipo_disponibilidade = new tipo_disponibilidade();
	$aItemOption = Array();

	$tipo_disponibilidade->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($tipo_disponibilidade->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($banco->SEQ_TIPO_DISPONIBILIDADE == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Disponibilidade necessária:", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_DISPONIBILIDADE", "S", "Tipo disponibilidade", "S", $aItemOption), "left");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.prioridade.php';
	$PRIORIDADE = new PRIORIDADE();
	$aItemOption = Array();

	$PRIORIDADE->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($PRIORIDADE->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($banco->SEQ_PRIORIDADE == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Prioridade:", "right", "S", $pagina->CampoSelect("v_SEQ_PRIORIDADE", "S", "Tipo criticidade", "S", $aItemOption), "left");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.criticidade.php';
	$criticidade = new criticidade();
	$aItemOption = Array();

	$criticidade->selectParam(1);
	$cont = 0;
	while ($row = pg_fetch_array($criticidade->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($banco->SEQ_CRITICIDADE == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Criticidade:", "right", "S", $pagina->CampoSelect("v_SEQ_CRITICIDADE", "S", "Tipo criticidade", "S", $aItemOption), "left");

	?>
	<script language="javascript">
		function fMostra(id, idTab){
			document.getElementById("tabelaSistemaInformacao").style.display = "none";
			document.getElementById("tabSistemaInformacao").attributes["class"].value = "";

			document.getElementById("tabelaEquipe").style.display = "none";
			document.getElementById("tabEquipe").attributes["class"].value = "";

			document.getElementById("tabelaDependencias").style.display = "none";
			document.getElementById("tabDependencias").attributes["class"].value = "";

			document.getElementById("tabelaAreasInternas").style.display = "none";
			document.getElementById("tabAreasInternas").attributes["class"].value = "";

			document.getElementById("tabelaAreasExternas").style.display = "none";
			document.getElementById("tabAreasExternas").attributes["class"].value = "";

			document.getElementById("tabelaHistorico").style.display = "none";
			document.getElementById("tabHistorico").attributes["class"].value = "";

		//	document.getElementById("tabelaOS").style.display = "none";
		//	document.getElementById("tabOS").attributes["class"].value = "";

			document.getElementById("tabelaInoperancia").style.display = "none";
			document.getElementById("tabInoperancia").attributes["class"].value = "";

			document.getElementById(id).style.display = "block";
			document.getElementById(idTab).attributes["class"].value = "tabact";

		}
	</script>
	<?

	$aItemAba = Array(
			//array("javascript: fMostra('tabelaGeral', 'tabGeral')", "tabact", "Dados Gerais", "tabGeral"),
			array("javascript: fMostra('tabelaSistemaInformacao','tabSistemaInformacao')", "tabact", "&nbsp;Sistema de Informação&nbsp;", "tabSistemaInformacao"),
			array("javascript: fMostra('tabelaEquipe','tabEquipe')", "", "&nbsp;Equipe Envolvida&nbsp;", "tabEquipe"),
			array("javascript: fMostra('tabelaDependencias','tabDependencias')", "", "&nbsp;Depêndencias&nbsp;", "tabDependencias"),
			array("javascript: fMostra('tabelaAreasInternas','tabAreasInternas')", "", "&nbsp;Áreas Internas&nbsp;", "tabAreasInternas"),
			array("javascript: fMostra('tabelaAreasExternas','tabAreasExternas')", "", "&nbsp;Áreas Externas&nbsp;", "tabAreasExternas"),
			array("javascript: fMostra('tabelaHistorico','tabHistorico')", "", "&nbsp;Histórico&nbsp;", "tabHistorico"),
		//	array("javascript: fMostra('tabelaOS','tabOS')", "", "&nbsp;O.S.&nbsp;","tabOS"),
			array("javascript: fMostra('tabelaInoperancia','tabInoperancia')", "", "&nbsp;Inoperancia&nbsp;","tabInoperancia")
		 			     );
	$pagina->SetaItemAba($aItemAba);
	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");

	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaSistemaInformacao cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");


	//================================================================================================================================
	//================================================================================================================================
	$item_configuracao_software->select($banco->SEQ_ITEM_CONFIGURACAO);
	$pagina->LinhaCampoFormularioColspanDestaque("Sistema de Informação", 2);
	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.tipo_software.php';
	$tipo_software = new tipo_software();
	$aItemOption = Array();

	$tipo_software->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($tipo_software->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($item_configuracao_software->SEQ_TIPO_SOFTWARE == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Tipo:", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_SOFTWARE", "S", "Tipo", "S", $aItemOption), "left");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.status_software.php';
	$status_software = new status_software();
	$aItemOption = Array();

	$status_software->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($status_software->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($item_configuracao_software->SEQ_STATUS_SOFTWARE == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Status:", "right", "S", $pagina->CampoSelect("v_SEQ_STATUS_SOFTWARE", "S", "Status software", "S", $aItemOption), "left");

	$aItemOption = Array();
	$aItemOption[] = array("S", $pagina->iif($item_configuracao_software->FLG_EM_MANUTENCAO == "S", "selected",""), "Sim - Interno");
	$aItemOption[] = array("E", $pagina->iif($item_configuracao_software->FLG_EM_MANUTENCAO == "E", "selected",""), "Sim - Externo");
	$aItemOption[] = array("N", $pagina->iif($item_configuracao_software->FLG_EM_MANUTENCAO == "N", "selected",""), "Não");

	$pagina->LinhaCampoFormulario("Em manutenção?", "right", "S", $pagina->CampoSelect("v_FLG_EM_MANUTENCAO", "S", "Em manutenção?", "S", $aItemOption), "left");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.frequencia_manutencao.php';
	$frequencia_manutencao = new frequencia_manutencao();
	$aItemOption = Array();

	$frequencia_manutencao->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($frequencia_manutencao->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($item_configuracao_software->SEQ_FREQUENCIA_MANUTENCAO == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	$pagina->LinhaCampoFormulario("Frequência de Manutenção", "right", "N", $pagina->CampoSelect("v_SEQ_FREQUENCIA_MANUTENCAO", "N", "Frequência de Manutenção", "S", $aItemOption), "left");

	$aItemOption = Array();
	$aItemOption[] = array("P", $pagina->iif($item_configuracao_software->FLG_TAMANHO == "P", "selected",""), "Pequeno");
	$aItemOption[] = array("M", $pagina->iif($item_configuracao_software->FLG_TAMANHO == "M", "selected",""), "Médio");
	$aItemOption[] = array("G", $pagina->iif($item_configuracao_software->FLG_TAMANHO == "G", "selected",""), "Grande");

	$pagina->LinhaCampoFormulario("Tamanho", "right", "S", $pagina->CampoSelect("v_FLG_TAMANHO", "S", "Tamanho", "S", $aItemOption), "left");


	print $pagina->CampoHidden("v_FLG_PETI", $item_configuracao_software->FLG_PETI);
	print $pagina->CampoHidden("v_NUM_ITEM_PETI", $item_configuracao_software->NUM_ITEM_PETI);
/*
	$aItemOption = Array();
	$aItemOption[] = array("S", $pagina->iif($item_configuracao_software->FLG_PETI == "S", "selected",""), "Sim");
	$aItemOption[] = array("N", $pagina->iif($item_configuracao_software->FLG_PETI == "N", "selected",""), "Não");
	$pagina->LinhaCampoFormulario("Está no Peti?", "right", "S", $pagina->CampoSelect("v_FLG_PETI", "S", "Está no PETI?", "S", $aItemOption), "left");

	$pagina->LinhaCampoFormulario("Número Peti:", "right", "N", $pagina->CampoTexto("v_NUM_ITEM_PETI", "N", "Número de Item peti", "20", "20", $item_configuracao_software->NUM_ITEM_PETI), "left");
*/
	$aItemOption = Array();
	$aItemOption[] = array("S", $pagina->iif($item_configuracao_software->FLG_DESCONTINUADO == "S", "selected",""), "Sim");
	$aItemOption[] = array("N", $pagina->iif($item_configuracao_software->FLG_DESCONTINUADO == "N", "selected",""), "Não");
	$pagina->LinhaCampoFormulario("Será Descontinuado?", "right", "S", $pagina->CampoSelect("v_FLG_DESCONTINUADO", "S", "Será descontinuado?", "S", $aItemOption), "left");

	$aItemOption = Array();
	$aItemOption[] = array("S", $pagina->iif($item_configuracao_software->FLG_SISTEMA_WEB == "S", "selected",""), "Sim");
	$aItemOption[] = array("N", $pagina->iif($item_configuracao_software->FLG_SISTEMA_WEB == "N", "selected",""), "Não");
	$pagina->LinhaCampoFormulario("Sistema web?", "right", "S", $pagina->CampoSelect("v_FLG_SISTEMA_WEB", "S", "Sistema WEB?", "S", $aItemOption), "left");

	$pagina->LinhaCampoFormulario("Localização da documentacao:", "right", "N", $pagina->CampoTexto("v_DSC_LOCALIZACAO_DOCUMENTACAO", "N", "Descrição de Localizacao documentacao", "60", "200", $item_configuracao_software->DSC_LOCALIZACAO_DOCUMENTACAO), "left");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.banco_de_dados.php';
	require_once 'include/PHP/class/class.software_banco_de_dados.php';
	$banco_de_dados = new banco_de_dados();
	$software_banco_de_dados = new software_banco_de_dados();
	$aItemOption = Array();

	$banco_de_dados->selectParam(2);
	$cont = 0;
	$software_banco_de_dados->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
	while ($row = pg_fetch_array($banco_de_dados->database->result)){
		$software_banco_de_dados->setSEQ_BANCO_DE_DADOS($row[0]);
		$software_banco_de_dados->selectParam();
		if($software_banco_de_dados->database->rows > 0){
			$aItemOption[$cont] = array($row[0], "checked", $row[1]);
		}else{
			$aItemOption[$cont] = array($row[0], "", $row[1]);
		}
		$cont++;
	}
	$pagina->LinhaCampoFormulario("Bancos de dados utilizados:", "right", "N", $pagina->CampoCheckbox($aItemOption, "v_SEQ_BANCO_DE_DADOS[]"), "left");

	$pagina->LinhaCampoFormulario("Tamanho do software:", "right", "N", $pagina->CampoTexto("v_VAL_TAMANHO_SOFTWARE", "N", "Valor de Tamanho software", "6", "6", $item_configuracao_software->VAL_TAMANHO_SOFTWARE), "left");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.unidade_medida_software.php';
	$unidade_medida_software = new unidade_medida_software();
	$aItemOption = Array();

	$unidade_medida_software->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($unidade_medida_software->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($item_configuracao_software->SEQ_UNIDADE_MEDIDA_SOFTWARE == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	$pagina->LinhaCampoFormulario("Unidade de Medida:", "right", "N", $pagina->CampoSelect("v_SEQ_UNIDADE_MEDIDA_SOFTWARE", "N", "", "S", $aItemOption), "left");

	$pagina->LinhaCampoFormulario("Valor de Aquisição:", "right", "N", $pagina->CampoTexto("v_VAL_AQUISICAO_SOFTWARE", "N", "Valor de Aquisicao", "6", "6", $item_configuracao_software->VAL_AQUISICAO_SOFTWARE), "left");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.linguagem_programacao.php';
	require_once 'include/PHP/class/class.software_linguagem_programacao.php';
	$linguagem_programacao = new linguagem_programacao();
	$software_linguagem_programacao = new software_linguagem_programacao();
	$aItemOption = Array();

	$linguagem_programacao->selectParam(2);
	$cont = 0;
	$software_linguagem_programacao->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
	while ($row = pg_fetch_array($linguagem_programacao->database->result)){
		$software_linguagem_programacao->setSEQ_LINGUAGEM_PROGRAMACAO($row[0]);
		$software_linguagem_programacao->selectParam();
		if($software_linguagem_programacao->database->rows > 0){
			$aItemOption[$cont] = array($row[0], "checked", $row[1]);
		}else{
			$aItemOption[$cont] = array($row[0], "", $row[1]);
		}
		$cont++;
	}
	$pagina->LinhaCampoFormulario("Linguagens de Programação:", "right", "N", $pagina->CampoCheckbox($aItemOption, "v_SEQ_LINGUAGEM_PROGRAMACAO[]"), "left");
	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaEquipe style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Equipe Envolvida", 2);

	?>
	<script language="javascript">
		// Declara arrays de controle das tabelas 1-N
		var aEquipe = new Array();
		var aEquipeAux = new Array();
		var aItemRelacionamento = new Array();
		var aUorSiglaEnvolvida = new Array();
		var aUorSiglaEnvolvidaAux = new Array();
		var aAreaExterna = new Array();
		var aAreaExternaAux = new Array();
		var aHistorico = new Array();
		var aInoperancia = new Array();

		function fAdicionaItemEquipe(v_NOM_LOGIN_REDE_EQUIPE, v_QTD_HORA_ALOCADA, v_NOME_EMPREGADO){
			if(v_NOM_LOGIN_REDE_EQUIPE != "" && v_QTD_HORA_ALOCADA != ""){
				if(InserirItemArray(aEquipeAux, v_NOM_LOGIN_REDE_EQUIPE) == true){
					InserirItemArray(aEquipe, v_NOM_LOGIN_REDE_EQUIPE+"|"+v_QTD_HORA_ALOCADA);


					valor1 = v_NOM_LOGIN_REDE_EQUIPE;
					valor2 = v_NOME_EMPREGADO;
					valor3 = v_QTD_HORA_ALOCADA;

					var tabela = document.getElementById("equipe");

					// Primeiro testamos se a primeira linha nao eh a mensagem "Sem dados..."
					if(tabela.rows.length>1){
						if(tabela.rows[1].cells[0].innerHTML=="Sem dados a serem exibidos")
							tabela.deleteRow(1); // se for apagamos
					}

					proxLinha = tabela.rows.length; // pega o total de linhas da tabela para acrescentar a nova
					var linha = tabela.insertRow(proxLinha); // Insere uma nova linha
					var coluna1 = linha.insertCell(0);
					var coluna2 = linha.insertCell(1);
					var coluna3 = linha.insertCell(2);
					var colunaCancela = linha.insertCell(3);

					setRowIndex(aEquipeAux, v_NOM_LOGIN_REDE_EQUIPE, linha.rowIndex);
					setRowIndex(aEquipe, v_NOM_LOGIN_REDE_EQUIPE+"|"+v_QTD_HORA_ALOCADA, linha.rowIndex);

					//Abaixo inserimos o conteudo nas colunas criadas
					coluna1.innerHTML=valor1;
					coluna1.setAttribute("align", "left");
					coluna2.innerHTML=valor2;
					coluna2.setAttribute("align", "left");
					coluna3.innerHTML=valor3;
					colunaCancela.innerHTML="<span onclick='fRetiraItemEquipe("+linha.rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
				}else{
					alert("Colaborador já incluído");
				}
			}else{
				alert("Os campos Matricula colaborador e Quantidade de horas semanais são obrigatórios");
			}
			return false;
		}
		function fRetiraItemEquipe(id){
			var tabela = document.getElementById("equipe");
			if(confirm("Tem certeza que deseja retirar a linha?")) {
				aEquipeAux = ExcluirItemArray(aEquipeAux, id);
				aEquipe = ExcluirItemArray(aEquipe, id);
				tabela.deleteRow(id);
				if(tabela.rows.length<2){
					var linha = tabela.insertRow(1); // Insere uma nova linha
					var coluna = linha.insertCell(0); // Insere uma coluna na linha
					coluna.setAttribute("colspan", 4); // Define colspan = 2
					coluna.innerHTML="Sem dados a serem exibidos"; // Texto informativo
				}else{
					// Refazer a coluna de exclusão da linha
					for(i=1;i<tabela.rows.length;i++){
						tabela.rows[i].cells[tabela.rows[i].cells.length - 1].innerHTML="<span onclick='fRetiraItemEquipe("+tabela.rows[i].rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
               		}
	            }
			}
		}
	</script>
	<?
	print $pagina->CampoHidden("aEquipe", "");
	print $pagina->CampoHidden("v_NOME_EMPREGADO", "");

	$tabela = array();
	$header = array();
	$header[] = array("Matricula colaborador", "center", "", "header");
	$header[] = array("Qd. Horas semanais", "center", "", "header");
	$header[] = array("&nbsp;", "center", "5%", "header");
	$tabela[] = $header;
	$header = array();
	$header[] = array($pagina->CampoTexto("v_NOM_LOGIN_REDE_EQUIPE", "N", "" , "10", "10", "", "readonly").
					  $pagina->ButtonProcuraRecursoTi("v_NOM_LOGIN_REDE_EQUIPE", "", "v_NOME_EMPREGADO"), "center", "", "");
	$header[] = array($pagina->CampoTexto("v_QTD_HORA_ALOCADA", "N", "" , "3", "3", ""), "center", "", "");
	$header[] = array($pagina->CampoButton("return fAdicionaItemEquipe(document.form.v_NOM_LOGIN_REDE_EQUIPE.value, document.form.v_QTD_HORA_ALOCADA.value, document.form.v_NOME_EMPREGADO.value);", "Adicionar", "button"), "center", "", "");
	$tabela[] = $header;
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%"), 2);
	$header = array();

	$header[] = array("Login", "center", "20%");
	$header[] = array("Nome", "center", "45%");
	$header[] = array("Alocação", "center", "20%");
	$header[] = array("  Excluir  ", "center", "15%");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->TabelaDinâmica("equipe", $header, "100%"), 2);
	$header = "";
	$pagina->LinhaCampoFormularioColspan("center", "&nbsp;", 2);
	?>
	<script language="javascript">
		<?
		// Inserir equipe existente em banco de dados na tela
		require_once 'include/PHP/class/class.equipe_envolvida.php';
		//require_once 'include/PHP/class/class.empregados.php';
		$empregados = new empregados();
		$equipe_envolvida = new equipe_envolvida();
		$equipe_envolvida->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
		$equipe_envolvida->selectParam();
		if($equipe_envolvida->database->rows > 0){
			while ($row = pg_fetch_array($equipe_envolvida->database->result)){
				$empregados->select($row["num_matricula_recurso"]);
				?>fAdicionaItemEquipe('<?=$empregados->NOM_LOGIN_REDE?>', '<?=$row["qtd_hora_alocada"]?>', '<?=$empregados->NOME?>');
				<?
			}
		}

		?>
	</script>
	<?
	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaDependencias style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");

	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Dependências", 2);

	?>
	<script language="javascript">
	// ==================================================================================================================
	// ITEM RELACIONAMENTO ==============================================================================================
		function fAdicionaItemRelacionamento(v_NOM_ITEM_CONFIGURACAO_RELACIONAMENTO, v_SEQ_TIPO_RELAC_ITEM_CONFIG, v_SEQ_ITEM_CONFIGURACAO_RELACIONAMENTO, v_NOM_TIPO_RELAC_ITEM_CONFIG, v_SEQ_TIPO_ITEM_CONFIGURACAO_RELACIONAMENTO){
			if(v_NOM_ITEM_CONFIGURACAO_RELACIONAMENTO != "" && v_SEQ_TIPO_RELAC_ITEM_CONFIG != ""){
				if(InserirItemArray(aItemRelacionamento, v_SEQ_ITEM_CONFIGURACAO_RELACIONAMENTO+"|"+v_SEQ_TIPO_RELAC_ITEM_CONFIG+"|"+v_SEQ_TIPO_ITEM_CONFIGURACAO_RELACIONAMENTO) == true){
					valor1 = v_NOM_ITEM_CONFIGURACAO_RELACIONAMENTO;

					if(v_NOM_TIPO_RELAC_ITEM_CONFIG != ""){
						valor2 = v_NOM_TIPO_RELAC_ITEM_CONFIG;
					}else{
						for(i=0; i<document.form.v_SEQ_TIPO_RELAC_ITEM_CONFIG.length; i++){
							if(document.form.v_SEQ_TIPO_RELAC_ITEM_CONFIG(i).value == document.form.v_SEQ_TIPO_RELAC_ITEM_CONFIG.value){
								valor2 = document.form.v_SEQ_TIPO_RELAC_ITEM_CONFIG(i).text;
							}
						}
					}

					var tabela = document.getElementById("ItemConfRelacionamento");

					// Primeiro testamos se a primeira linha nao eh a mensagem "Sem dados..."
					if(tabela.rows.length>1){
						if(tabela.rows[1].cells[0].innerHTML=="Sem dados a serem exibidos")
							tabela.deleteRow(1); // se for apagamos
					}

					proxLinha = tabela.rows.length; // pega o total de linhas da tabela para acrescentar a nova
					var linha = tabela.insertRow(proxLinha); // Insere uma nova linha
					var coluna1 = linha.insertCell(0);
					var coluna2 = linha.insertCell(1);
					var colunaCancela = linha.insertCell(2);

					setRowIndex(aItemRelacionamento, v_SEQ_ITEM_CONFIGURACAO_RELACIONAMENTO+"|"+v_SEQ_TIPO_RELAC_ITEM_CONFIG+"|"+v_SEQ_TIPO_ITEM_CONFIGURACAO_RELACIONAMENTO, linha.rowIndex);

					//Abaixo inserimos o conteudo nas colunas criadas
					coluna1.innerHTML=valor1;
					coluna1.setAttribute("align", "left");
					coluna2.innerHTML=valor2;
					colunaCancela.innerHTML="<span onclick='fRetiraItemRelacionamento("+linha.rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
				}else{
					alert("Item já incluído");
				}
			}else{
				alert("Os campos Item e Tipo de Dependência são obrigatórios");
			}
			return false;
		}
		function fRetiraItemRelacionamento(id){
			var tabela = document.getElementById("ItemConfRelacionamento");
			if(confirm("Tem certeza que deseja retirar a linha?")) {
				aRelacionamentos = ExcluirItemArray(aItemRelacionamento, id);
				tabela.deleteRow(id);
				if(tabela.rows.length<2){
					var linha = tabela.insertRow(1); // Insere uma nova linha
					var coluna = linha.insertCell(0); // Insere uma coluna na linha
					coluna.setAttribute("cols", 2); // Define colspan = 2
					coluna.innerHTML="Sem dados a serem exibidos"; // Texto informativo
				}else{
					// Refazer a coluna de exclusão da linha
					for(i=1;i<tabela.rows.length;i++){
							tabela.rows[i].cells[tabela.rows[i].cells.length - 1].innerHTML="<span onclick='fRetiraItemRelacionamento("+tabela.rows[i].rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
               		}
	            }
			}
		}
	</script>
	<?
	print $pagina->CampoHidden("aItemRelacionamento", "");
	print $pagina->CampoHidden("v_SEQ_TIPO_ITEM_CONFIGURACAO_RELACIONAMENTO", "");
	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.tipo_relacionamento_item_configuracao.php';
	$tipo_relacionamento_item_configuracao = new tipo_relacionamento_item_configuracao();
	$aItemOption = Array();

	$tipo_relacionamento_item_configuracao->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($tipo_relacionamento_item_configuracao->database->result)){
		$aItemOption[$cont] = array($row[0], "", $row[1]);
		$cont++;
	}
	print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO_RELACIONAMENTO", "");

	$tabela = array();
	$header = array();
	$header[] = array("Item", "center", "", "header");
	$header[] = array("Tipo de Dependência", "center", "", "header");
	$header[] = array("&nbsp;", "center", "5%", "header");
	$tabela[] = $header;
	$header = array();
	$header[] = array($pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO_RELACIONAMENTO", "N", "" , "40", "40", "", "readonly").
					  $pagina->ButtonProcuraItemConfiguracao("v_NOM_ITEM_CONFIGURACAO_RELACIONAMENTO", "v_SEQ_ITEM_CONFIGURACAO_RELACIONAMENTO", "v_SEQ_TIPO_ITEM_CONFIGURACAO_RELACIONAMENTO"), "center", "", "");
	$header[] = array( $pagina->CampoSelect("v_SEQ_TIPO_RELAC_ITEM_CONFIG", "N", "", "S", $aItemOption), "center", "", "");
	$header[] = array($pagina->CampoButton("return fAdicionaItemRelacionamento(document.form.v_NOM_ITEM_CONFIGURACAO_RELACIONAMENTO.value, document.form.v_SEQ_TIPO_RELAC_ITEM_CONFIG.value, document.form.v_SEQ_ITEM_CONFIGURACAO_RELACIONAMENTO.value, '', document.form.v_SEQ_TIPO_ITEM_CONFIGURACAO_RELACIONAMENTO.value);", "Adicionar", "button"), "center", "", "");
	$tabela[] = $header;
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%"), 2);
	$header = array();

	$header[] = array("Item", "center", "45%");
	$header[] = array("Relacionamento", "center", "40%");
	$header[] = array("  Excluir  ", "center", "15%");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->TabelaDinâmica("ItemConfRelacionamento", $header, "100%"), 2);
	$header = "";
	$pagina->LinhaCampoFormularioColspan("center", "&nbsp;", 2);
	?>
	<script language="javascript">
		<?
		// Inserir equipe existente em banco de dados na tela
		require_once 'include/PHP/class/class.relacionamento_item_configuracao.php';
		require_once 'include/PHP/class/class.tipo_relacionamento_item_configuracao.php';
		require_once 'include/PHP/class/class.servidor.php';
		$tipo_relacionamento_item_configuracao = new tipo_relacionamento_item_configuracao();
		$relacionamento_item_configuracao = new relacionamento_item_configuracao();
		$servidor = new servidor();
		$relacionamento_item_configuracao->setSEQ_ITEM_CONFIGURACAO_PAI($banco->SEQ_ITEM_CONFIGURACAO);
		$relacionamento_item_configuracao->selectParam();
		if($relacionamento_item_configuracao->database->rows > 0){
			while ($row = pg_fetch_array($relacionamento_item_configuracao->database->result)){

				$tipo_relacionamento_item_configuracao->select($row["seq_tipo_relac_item_config"]);

				if($row["seq_item_configuracao_filho"] != ""){
					$item_configuracao->select($row["seq_item_configuracao_filho"]);
					$vNome = $item_configuracao->NOM_ITEM_CONFIGURACAO;
					$vCodigo = $row["seq_item_configuracao_filho"];
					$vCodigoTipo = 2;
				}elseif($row["seq_servidor"] != ""){
					$servidor->select($row["seq_servidor"]);
					$vNome = $servidor->NOM_SERVIDOR;
					$vCodigo = $row["seq_servidor"];
					$vCodigoTipo = 1;
				}

				?>fAdicionaItemRelacionamento('<?=$vNome?>', '<?=$row["seq_tipo_relac_item_config"]?>', '<?=$vCodigo?>', '<?=$tipo_relacionamento_item_configuracao->NOM_TIPO_RELAC_ITEM_CONFIG?>', '<?=$vCodigoTipo?>');

				<?
			}
		}

		?>
	</script>
	<?
	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAreasInternas style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Áreas Internas Envolvidas", 2);

	?>
	<script language="javascript">
	// ==================================================================================================================
	// AREA ENVOLVIDA ===================================================================================================
		function fAdicionaAreaEnvolvida(v_UOR_SIGLA_RELACIONAMENTO, v_NOM_LOGIN_REDE_AREA_ENVOLVIDA){
			if(v_UOR_SIGLA_RELACIONAMENTO != ""){
				if(InserirItemArray(aUorSiglaEnvolvidaAux, v_UOR_SIGLA_RELACIONAMENTO) == true){
					InserirItemArray(aUorSiglaEnvolvida, v_UOR_SIGLA_RELACIONAMENTO+"|"+v_NOM_LOGIN_REDE_AREA_ENVOLVIDA)

					valor1 = v_UOR_SIGLA_RELACIONAMENTO;
					valor2 = v_NOM_LOGIN_REDE_AREA_ENVOLVIDA;

					var tabela = document.getElementById("area_envolvida");

					// Primeiro testamos se a primeira linha nao eh a mensagem "Sem dados..."
					if(tabela.rows.length>1){
						if(tabela.rows[1].cells[0].innerHTML=="Sem dados a serem exibidos")
							tabela.deleteRow(1); // se for apagamos
					}


					proxLinha = tabela.rows.length; // pega o total de linhas da tabela para acrescentar a nova
					var linha = tabela.insertRow(proxLinha); // Insere uma nova linha
					var coluna1 = linha.insertCell(0);
					var coluna2 = linha.insertCell(1);
					var colunaCancela = linha.insertCell(2);

					setRowIndex(aUorSiglaEnvolvidaAux, v_UOR_SIGLA_RELACIONAMENTO, linha.rowIndex);
					setRowIndex(aUorSiglaEnvolvida, v_UOR_SIGLA_RELACIONAMENTO+"|"+v_NOM_LOGIN_REDE_AREA_ENVOLVIDA, linha.rowIndex);

					//Abaixo inserimos o conteudo nas colunas criadas
					coluna1.innerHTML=valor1;
					coluna1.setAttribute("align", "left");
					coluna2.innerHTML=valor2;
					colunaCancela.innerHTML="<span onclick='fRetiraAreaEnvolvida("+linha.rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
				}else{
					alert("Área já incluída");
				}
			}else{
				alert("O campo Unidade Organizacional é obrigatório");
			}
			return false;
		}
		function fRetiraAreaEnvolvida(id){
			var tabela = document.getElementById("area_envolvida");
			if(confirm("Tem certeza que deseja retirar a linha?")) {
				aUorSiglaEnvolvidaAux = ExcluirItemArray(aUorSiglaEnvolvidaAux, id);
				aUorSiglaEnvolvida = ExcluirItemArray(aUorSiglaEnvolvida, id);
				tabela.deleteRow(id);
				if(tabela.rows.length<2){
					var linha = tabela.insertRow(1); // Insere uma nova linha
					var coluna = linha.insertCell(0); // Insere uma coluna na linha
					coluna.setAttribute("cols", 3); // Define colspan = 2
					coluna.innerHTML="Sem dados a serem exibidos"; // Texto informativo
				}else{
					// Refazer a coluna de exclusão da linha
					for(i=1;i<tabela.rows.length;i++){
							tabela.rows[i].cells[tabela.rows[i].cells.length - 1].innerHTML="<span onclick='fRetiraAreaEnvolvida("+tabela.rows[i].rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
               		}
	            }
			}
		}
	</script>
	<?

	print $pagina->CampoHidden("aUorSiglaEnvolvida", "");

	$tabela = array();
	$header = array();
	$header[] = array("Unidade Organizacional", "center", "", "header");
	$header[] = array("Contato", "center", "", "header");
	$header[] = array("&nbsp;", "center", "5%", "header");
	$tabela[] = $header;
	$header = array();
	$header[] = array($pagina->CampoTexto("v_UOR_SIGLA_RELACIONAMENTO", "N", "" , "11", "20", "", "readonly").
					  $pagina->ButtonProcuraUorg("v_UOR_SIGLA_RELACIONAMENTO"), "center", "", "");
	$header[] = array($pagina->CampoTexto("v_NOM_LOGIN_REDE_AREA_ENVOLVIDA", "N", "" , "11", "20", "", "readonly").
					  $pagina->ButtonProcuraEmpregado("v_NOM_LOGIN_REDE_AREA_ENVOLVIDA").
					  $pagina->ButtonLimpar("v_NOM_LOGIN_REDE_AREA_ENVOLVIDA"), "center", "", "");
	$header[] = array($pagina->CampoButton("return fAdicionaAreaEnvolvida(document.form.v_UOR_SIGLA_RELACIONAMENTO.value, document.form.v_NOM_LOGIN_REDE_AREA_ENVOLVIDA.value);", "Adicionar", "button"), "center", "", "");
	$tabela[] = $header;
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%"), 2);
	$header = array();

	$header[] = array("Área", "center", "45%");
	$header[] = array("Contato", "center", "40%");
	$header[] = array("  Excluir  ", "center", "15%");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->TabelaDinâmica("area_envolvida", $header, "100%"), 2);
	$header = "";
	$pagina->LinhaCampoFormularioColspan("center", "&nbsp;", 2);

	?>
	<script language="javascript">
		<?
		// Inserir equipe existente em banco de dados na tela
		require_once 'include/PHP/class/class.area_envolvida.php';
		$area_envolvida = new area_envolvida();
		$area_envolvida->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
		$area_envolvida->selectParam();
		if($area_envolvida->database->rows > 0){
			while ($row = pg_fetch_array($area_envolvida->database->result)){
				?>fAdicionaAreaEnvolvida('<?=$row["cod_uor"]?>', '<?=$pagina->iif($row["num_matricula_gestor"]=="","",$empregados->GetNomLoginRedeMatricula($row["num_matricula_gestor"]))?>');
				<?
			}
		}

		?>
	</script>

	<?
	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAreasExternas style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Áreas Externas Envolvidas", 2);

	?>
	<script language="javascript">
	// ==================================================================================================================
	// AREA EXTERNA =====================================================================================================
		function fAdicionaAreaExternaEnvolvida(v_SEQ_AREA_EXTERNA, v_NOM_CONTATO, v_NUM_TELEFONE, v_NOM_AREA_EXTERNA){
			if(v_SEQ_AREA_EXTERNA != ""){
				if(InserirItemArray(aAreaExternaAux, v_SEQ_AREA_EXTERNA) == true){

					InserirItemArray(aAreaExterna, v_SEQ_AREA_EXTERNA+"|"+v_NOM_CONTATO+"|"+v_NUM_TELEFONE);

					valor = "";
					if(v_NOM_AREA_EXTERNA != ""){
						valor = v_NOM_AREA_EXTERNA;
					}else{
						for(i=0; i<document.form.v_SEQ_AREA_EXTERNA.length; i++){
							if(document.form.v_SEQ_AREA_EXTERNA(i).value == v_SEQ_AREA_EXTERNA){

								valor = document.form.v_SEQ_AREA_EXTERNA(i).text;
							}
						}
					}

					valor1 = valor;
					valor2 = v_NOM_CONTATO;
					valor3 = v_NUM_TELEFONE;

					var tabela = document.getElementById("area_externa");

					// Primeiro testamos se a primeira linha nao eh a mensagem "Sem dados..."
					if(tabela.rows.length>1){
						if(tabela.rows[1].cells[0].innerHTML=="Sem dados a serem exibidos")
							tabela.deleteRow(1); // se for apagamos
					}

					proxLinha = tabela.rows.length; // pega o total de linhas da tabela para acrescentar a nova
					var linha = tabela.insertRow(proxLinha); // Insere uma nova linha
					var coluna1 = linha.insertCell(0);
					var coluna2 = linha.insertCell(1);
					var coluna3 = linha.insertCell(2);
					var colunaCancela = linha.insertCell(3);

					setRowIndex(aAreaExternaAux, v_SEQ_AREA_EXTERNA, linha.rowIndex);
					setRowIndex(aAreaExterna, v_SEQ_AREA_EXTERNA+"|"+v_NOM_CONTATO+"|"+v_NUM_TELEFONE, linha.rowIndex);

					//Abaixo inserimos o conteudo nas colunas criadas
					coluna1.innerHTML=valor1;
					coluna1.setAttribute("align", "left");
					coluna2.innerHTML=valor2;
					coluna2.setAttribute("align", "left");
					coluna3.innerHTML=valor3;
					colunaCancela.innerHTML="<span onclick='fRetiraAreaExternaEnvolvida("+linha.rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
				}else{
					alert("Área já incluída");
				}
			}else{
				alert("Selecione a área externa");
			}
			return false;
		}
		function fRetiraAreaExternaEnvolvida(id){
			var tabela = document.getElementById("area_externa");
			if(confirm("Tem certeza que deseja retirar a linha?")) {
				aAreaExternaAux = ExcluirItemArray(aAreaExternaAux, id);
				aAreaExterna = ExcluirItemArray(aAreaExterna, id);
				tabela.deleteRow(id);
				if(tabela.rows.length<2){
					var linha = tabela.insertRow(1); // Insere uma nova linha
					var coluna = linha.insertCell(0); // Insere uma coluna na linha
					coluna.setAttribute("cols", 4); // Define colspan = 2
					coluna.innerHTML="Sem dados a serem exibidos"; // Texto informativo
				}else{
					// Refazer a coluna de exclusão da linha
					for(i=1;i<tabela.rows.length;i++){
							tabela.rows[i].cells[tabela.rows[i].cells.length - 1].innerHTML="<span onclick='fRetiraAreaExternaEnvolvida("+tabela.rows[i].rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
               		}
	            }
			}
		}
	</script>
	<?
	print $pagina->CampoHidden("aAreaExterna", "");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.area_externa.php';
	$area_externa = new area_externa();
	$aItemOption = Array();

	$area_externa->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($area_externa->database->result)){
		$aItemOption[$cont] = array($row[0], "", $row[1]);
		$cont++;
	}

	$tabela = array();
	$header = array();
	$header[] = array("Area", "center", "", "header");
	$header[] = array("Nome Contato", "center", "", "header");
	$header[] = array("Telefone", "center", "", "header");
	$header[] = array("&nbsp;", "center", "5%", "header");
	$tabela[] = $header;
	$header = array();
	$header[] = array($pagina->CampoSelect("v_SEQ_AREA_EXTERNA", "N", "", "S", $aItemOption), "center", "", "");
	$header[] = array($pagina->CampoTexto("v_NOM_CONTATO", "N", "" , "10", "10", ""), "center", "", "");
	$header[] = array( $pagina->CampoTexto("v_NUM_TELEFONE", "N", "" , "10", "10", ""), "center", "", "");
	$header[] = array($pagina->CampoButton("return fAdicionaAreaExternaEnvolvida(document.form.v_SEQ_AREA_EXTERNA.value, document.form.v_NOM_CONTATO.value, document.form.v_NUM_TELEFONE.value, '');", "Adicionar", "button"), "center", "", "");
	$tabela[] = $header;
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%"), 2);
	$header = array();

	$header[] = array("Área", "center", "40%");
	$header[] = array("Contato", "center", "30%");
	$header[] = array("Telefone", "center", "15%");
	$header[] = array("  Excluir  ", "center", "15%");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->TabelaDinâmica("area_externa", $header, "100%"), 2);
	$header = "";

	?>
	<script language="javascript">
		<?
		// Inserir equipe existente em banco de dados na tela
		require_once 'include/PHP/class/class.area_externa_envolvida.php';
		require_once 'include/PHP/class/class.area_externa.php';
		$area_externa = new area_externa();
		$area_externa_envolvida = new area_externa_envolvida();
		$area_externa_envolvida->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
		$area_externa_envolvida->selectParam();
		if($area_externa_envolvida->database->rows > 0){
			while ($row = pg_fetch_array($area_externa_envolvida->database->result)){
				$area_externa->select($row["seq_area_externa"]);
				?>fAdicionaAreaExternaEnvolvida('<?=$row["seq_area_externa"]?>', '<?=$row["nom_contato"]?>', '<?=$row["num_telefone"]?>', '<?=$area_externa->NOM_AREA_EXTERNA?>');
				<?
			}
		}

		?>
	</script>
	<?
	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaHistorico style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Registro Histórico", 2);

	?>
	<script language="javascript">
	// ==================================================================================================================
	// REGISTRO HITÓRICO ================================================================================================
		function fAdicionaHistorico(v_DSC_FASE_ITEM_CONFIGURACAO, v_SEQ_FASE_PROJETO, v_TXT_OBSERVACAO_FASE, v_DAT_INICIO_FASE_PROJETO, v_DAT_FIM_FASE_PROJETO){
			if(v_DSC_FASE_ITEM_CONFIGURACAO != "" && v_SEQ_FASE_PROJETO  != "" &&  v_DSC_FASE_ITEM_CONFIGURACAO  != "" && v_DAT_INICIO_FASE_PROJETO != "" && v_DAT_FIM_FASE_PROJETO != ""){
				if(InserirItemArray(aHistorico, v_DSC_FASE_ITEM_CONFIGURACAO+"|"+v_SEQ_FASE_PROJETO+"|"+v_TXT_OBSERVACAO_FASE+"|"+v_DAT_INICIO_FASE_PROJETO+"|"+v_DAT_FIM_FASE_PROJETO) == true){
					valor = v_DSC_FASE_ITEM_CONFIGURACAO;
					<?
					// Buscar dados da tabela externa
					require_once 'include/PHP/class/class.fase_projeto.php';
					$fase_projeto = new fase_projeto();
					$aItemOption = Array();

					$fase_projeto->selectParam(2);
					$cont = 0;
					while ($row = pg_fetch_array($fase_projeto->database->result)){
						?>
						if(v_SEQ_FASE_PROJETO == "<?=$row[0]?>"){
							valor1 = "<?=$row[1]?>";
						}

						<?
					}
					?>
					valor2 = v_TXT_OBSERVACAO_FASE;
					valor3 = v_DAT_INICIO_FASE_PROJETO;
					valor4 = v_DAT_FIM_FASE_PROJETO;

					var tabela = document.getElementById("historico");

					// Primeiro testamos se a primeira linha nao eh a mensagem "Sem dados..."
					if(tabela.rows.length>1){
						if(tabela.rows[1].cells[0].innerHTML=="Sem dados a serem exibidos")
							tabela.deleteRow(1); // se for apagamos
					}

					proxLinha = tabela.rows.length; // pega o total de linhas da tabela para acrescentar a nova
					var linha = tabela.insertRow(proxLinha); // Insere uma nova linha
					var coluna1 = linha.insertCell(0);
					var coluna2 = linha.insertCell(1);
					var coluna3 = linha.insertCell(2);
					var coluna4 = linha.insertCell(3);
					var coluna5 = linha.insertCell(4);
					var colunaCancela = linha.insertCell(5);

					setRowIndex(aHistorico, v_DSC_FASE_ITEM_CONFIGURACAO+"|"+v_SEQ_FASE_PROJETO+"|"+v_TXT_OBSERVACAO_FASE+"|"+v_DAT_INICIO_FASE_PROJETO+"|"+v_DAT_FIM_FASE_PROJETO, linha.rowIndex);

					//Abaixo inserimos o conteudo nas colunas criadas
					coluna1.innerHTML=valor;
					coluna1.setAttribute("align", "left");
					coluna2.innerHTML=valor1;
					coluna2.setAttribute("align", "left");
					coluna3.innerHTML=valor2;
					coluna3.setAttribute("align", "left");
					coluna4.innerHTML=valor3;
					coluna4.setAttribute("align", "center");
					coluna5.innerHTML=valor4;
					coluna5.setAttribute("align", "center");
					colunaCancela.innerHTML="<span onclick='fRetiraHistorico("+linha.rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
				}else{
					alert("Histórico já incluído.");
				}
			}else{
				alert("Os campos Título, fase e descrição são obrigatórios");
			}
			return false;
		}
		function fRetiraHistorico(id){
			var tabela = document.getElementById("historico");
			if(confirm("Tem certeza que deseja retirar a linha?")) {
				aHistorico = ExcluirItemArray(aHistorico, id);
				tabela.deleteRow(id);
				if(tabela.rows.length<2){
					var linha = tabela.insertRow(1); // Insere uma nova linha
					var coluna = linha.insertCell(0); // Insere uma coluna na linha
					coluna.setAttribute("cols", 4); // Define colspan = 2
					coluna.innerHTML="Sem dados a serem exibidos"; // Texto informativo
				}else{
					// Refazer a coluna de exclusão da linha
					for(i=1;i<tabela.rows.length;i++){
							tabela.rows[i].cells[tabela.rows[i].cells.length - 1].innerHTML="<span onclick='fRetiraHistorico("+tabela.rows[i].rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
               		}
	            }
			}
		}
	</script>
	<?
	print $pagina->CampoHidden("aHistorico", "");
	$tabela = array();
	$header = array();
	$header[] = array("Titulo", "center", "", "header");
	$header[] = array("Fase", "center", "", "header");
	$header[] = array("Descrição", "center", "", "header");
	$header[] = array("Início", "center", "", "header");
	$header[] = array("Fim", "center", "", "header");
	$header[] = array("&nbsp;", "center", "", "header");
	$tabela[] = $header;

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.fase_projeto.php';
	$fase_projeto = new fase_projeto();
	$aItemOption = Array();

	$fase_projeto->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($fase_projeto->database->result)){
		$aItemOption[$cont] = array($row[0], "", $row[1]);
		$cont++;
	}

	$header = array();
	$header[] = array($pagina->CampoTexto("v_DSC_FASE_ITEM_CONFIGURACAO", "N", "Nome de Titulo", "15", "60", "$banco->DSC_FASE_ITEM_CONFIGURACAO"), "center", "", "");
	$header[] = array($pagina->CampoSelect("v_SEQ_FASE_PROJETO", "N", "Fase", "S", $aItemOption), "center", "", "");
	$header[] = array($pagina->CampoTextArea("v_TXT_OBSERVACAO_FASE", "N", "Descrição", "15", "2", "$banco->TXT_FASE_ITEM_CONFIGURACAO"), "center", "", "");
	$header[] = array($pagina->CampoData("v_DAT_INICIO_FASE_PROJETO", "N", "", ""), "center", "", $banco->DAT_INICIO_FASE_PROJETO);
	$header[] = array($pagina->CampoData("v_DAT_FIM_FASE_PROJETO", "N", "", ""), "center", "", $banco->DAT_FIM_FASE_PROJETO);
	$header[] = array($pagina->CampoButton("return fAdicionaHistorico(document.form.v_DSC_FASE_ITEM_CONFIGURACAO.value, document.form.v_SEQ_FASE_PROJETO.value, document.form.v_TXT_OBSERVACAO_FASE.value, document.form.v_DAT_INICIO_FASE_PROJETO.value, document.form.v_DAT_FIM_FASE_PROJETO.value);", "Adicionar", "button"), "center", "", "");
	$tabela[] = $header;
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%"), 2);

	$header = array();
	$header[] = array("Título", "center", "25%");
	$header[] = array("Fase", "center", "20%");
	$header[] = array("Descrição", "center", "30%");
	$header[] = array("Início", "center", "10%");
	$header[] = array("Fim", "center", "10%");
	$header[] = array("  Excluir  ", "center", "5%");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->TabelaDinâmica("historico", $header, "100%"), 2);
	$header = "";

	?>
	<script language="javascript">
		<?
		// Inserir histórico existente no banco de dados, na tela
		require_once 'include/PHP/class/class.fase_item_configuracao.php';
		$fase_item_configuracao = new fase_item_configuracao();
		$fase_item_configuracao->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
		$fase_item_configuracao->selectParam();
		if($fase_item_configuracao->database->rows > 0){
			while ($row = pg_fetch_array($fase_item_configuracao->database->result)){
				?>fAdicionaHistorico('<?=$row["dsc_fase_item_configuracao"]?>', '<?=$row["seq_fase_projeto"]?>', '<?=$row["txt_fase_item_configuracao"]?>', '<?=$pagina->ConvDataDMA($row["dat_inicio_fase_projeto"],"/")?>', '<?=$pagina->ConvDataDMA($row["dat_fim_fase_projeto"],"/")?>');
				<?
			}
		}

		?>
	</script>
		<?
	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaOS style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	// O.S. ==============================================================================================
	$header = array();
	$tabela = array();
	$header[] = array("Ordens de Serviços", "left", "", "");
	$header[] = array("<a href=\"Ordem_servicoPesquisa.php?v_SEQ_ITEM_CONFIGURACAO=".$banco->SEQ_ITEM_CONFIGURACAO."\">Manipular Dados</a>", "right", "", "");
	$tabela[] = $header;
	$pagina->LinhaCampoFormularioColspanDestaque($pagina->Tabela($tabela, "100%"), 2);

	$tabela = array();
	$header = array();
	$header[] = array("Fornecedor", "center", "30%", "header");
	$header[] = array("Nº da O.S.", "center", "15%", "header");
	$header[] = array("Valor", "center", "10%", "header");
	$header[] = array("Entrega", "center", "10%", "header");
	$header[] = array("Descrição", "center", "35%", "header");
	$tabela[] = $header;

	require_once 'include/PHP/class/class.ordem_servico.php';
	$ordem_servico = new ordem_servico();
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$ordem_servico->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
	$ordem_servico->selectParam("DAT_ENTREGA", $vNumPagina);
	if($ordem_servico->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhuma O.S. vinculada", count($header));
	}else{
		$corpo = array();
		while ($row = pg_fetch_array($ordem_servico->database->result)){
			require_once 'include/PHP/class/class.fornecedor.php';
			$fornecedor = new fornecedor();
			$fornecedor->select($row["num_cpf_cgc"]);
			$corpo[] = array($fornecedor->NOM_FORNECEDOR, "Left", "",  "");
			$corpo[] = array($row["num_ordem_servico"], "left", "", "");
			$corpo[] = array(number_format($row["val_pagamento"],2), "right", "campo", "");
			$corpo[] = array($pagina->ConvDataDMA($row["dat_entrega"],"/"), "center", "campo", "");
			$corpo[] = array($row["dsc_ordem_servico"], "left", "campo", "");
			$tabela[] = $corpo;
			$corpo = "";
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%"), 2);
	}

	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaInoperancia style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Registro de Inoperâncias", 2);

	?>
	<script language="javascript">
	// ==================================================================================================================
	// REGISTRO DE INOPERÂNCIA===========================================================================================
		function fAdicionaInoperancia(v_DAT_INICIO, v_HOR_INICIO, v_DES_MOTIVO, v_DAT_FIM, v_HOR_FIM, v_DES_SOLUCAO){
			if(v_DAT_INICIO != "" && v_HOR_INICIO != "" && v_DES_MOTIVO != "" && v_DAT_FIM != "" && v_HOR_FIM != "" && v_DES_SOLUCAO != ""){
				if(InserirItemArray(aInoperancia,v_DAT_INICIO+" "+v_HOR_INICIO+"|"+v_DES_MOTIVO+"|"+ v_DAT_FIM+" "+v_HOR_FIM+"|"+ v_DES_SOLUCAO) == true){
					valor = v_DAT_INICIO+" "+v_HOR_INICIO;
					valor1 = v_DES_MOTIVO;
					valor2 = v_DAT_FIM+" "+v_HOR_FIM;
					valor3 = v_DES_SOLUCAO;

					var tabela = document.getElementById("table_inoperancia");

					// Primeiro testamos se a primeira linha nao eh a mensagem "Sem dados..."
					if(tabela.rows.length>1){
						if(tabela.rows[1].cells[0].innerHTML=="Sem dados a serem exibidos")
							tabela.deleteRow(1); // se for apagamos
					}

					proxLinha = tabela.rows.length; // pega o total de linhas da tabela para acrescentar a nova
					var linha = tabela.insertRow(proxLinha); // Insere uma nova linha
					var coluna1 = linha.insertCell(0);
					var coluna2 = linha.insertCell(1);
					var coluna3 = linha.insertCell(2);
					var coluna4 = linha.insertCell(3);
					var colunaCancela = linha.insertCell(4);

					setRowIndex(aInoperancia, v_DAT_INICIO+" "+v_HOR_INICIO+"|"+v_DES_MOTIVO+"|"+ v_DAT_FIM+" "+v_HOR_FIM+"|"+ v_DES_SOLUCAO, linha.rowIndex);

					//Abaixo inserimos o conteudo nas colunas criadas
					coluna1.innerHTML=valor;
					coluna1.setAttribute("align", "center");
					coluna2.innerHTML=valor1;
					coluna2.setAttribute("align", "left");
					coluna3.innerHTML=valor2;
					coluna3.setAttribute("align", "center");
					coluna4.innerHTML=valor3;
					coluna4.setAttribute("align", "left");
					colunaCancela.innerHTML="<span onclick='fRetiraInoperancia("+linha.rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
				}else{
					alert("Inoperância já incluída.");
				}
			}else{
				alert("O preenchimento de todos os campos é obrigatório");
			}
			return false;
		}
		function fRetiraInoperancia(id){
			var tabela = document.getElementById("table_inoperancia");
			if(confirm("Tem certeza que deseja retirar a linha?")) {
				aHistorico = ExcluirItemArray(aInoperancia, id);
				tabela.deleteRow(id);
				if(tabela.rows.length<2){
					var linha = tabela.insertRow(1); // Insere uma nova linha
					var coluna = linha.insertCell(0); // Insere uma coluna na linha
					coluna.setAttribute("cols", 4); // Define colspan = 2
					coluna.innerHTML="Sem dados a serem exibidos"; // Texto informativo
				}else{
					// Refazer a coluna de exclusão da linha
					for(i=1;i<tabela.rows.length;i++){
							tabela.rows[i].cells[tabela.rows[i].cells.length - 1].innerHTML="<span onclick='fRetiraHistorico("+tabela.rows[i].rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
               		}
	            }
			}
		}
	</script>
	<?

	print $pagina->CampoHidden("aInoperancia", "");
	$tabela = array();
	$header = array();
	$header[] = array("Dt Inicio", "center", "17%", "header");
	$header[] = array("Hr Inicio", "center", "10%", "header");
	$header[] = array("Motivo", "center", "20%", "header");
	$header[] = array("Dt Fim", "center", "17%", "header");
	$header[] = array("Hr Fim", "center", "10%", "header");
	$header[] = array("Solução", "center", "20%", "header");
	$header[] = array("&nbsp;", "center", "10%", "header");
	$tabela[] = $header;

	$header = array();
	$header[] = array($pagina->CampoData("v_DAT_INICIO", "N", "", ""), "center", "", "");
	$header[] = array($pagina->CampoTexto("v_HOR_INICIO", "N", "", "5", "5", ""), "center", "", "");
	$header[] = array($pagina->CampoTextArea("v_DES_MOTIVO", "N", "", "19", "2", ""), "center", "", "");
	$header[] = array($pagina->CampoData("v_DAT_FIM", "N", "", ""), "center", "", "");
	$header[] = array($pagina->CampoTexto("v_HOR_FIM", "N", "", "5", "5", ""), "center", "", "");
	$header[] = array($pagina->CampoTextArea("v_DES_SOLUCAO", "N", "", "19", "2", ""), "center", "", "");
	$header[] = array($pagina->CampoButton("return fAdicionaInoperancia(document.form.v_DAT_INICIO.value, document.form.v_HOR_INICIO.value, document.form.v_DES_MOTIVO.value, document.form.v_DAT_FIM.value, document.form.v_HOR_FIM.value, document.form.v_DES_SOLUCAO.value);", "Adicionar", "button"), "center", "", "");
	$tabela[] = $header;
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%"), 2);

	$header = array();
	$header[] = array("Data e Hora inicio", "center", "18%");
	$header[] = array("Motivo", "center", "30%");
	$header[] = array("Data e Hora fim", "center", "15%");
	$header[] = array("Solução", "center", "30%");
	$header[] = array(" Excluir ", "center", "5%");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->TabelaDinâmica("table_inoperancia", $header, "100%"), 2);
	$header = "";

	?>
	<script language="javascript">

		<?
		// Inserir histórico existente no banco de dados, na tela
		require_once 'include/PHP/class/class.inoperancia.php';
		$inoperancia = new inoperancia();
		$inoperancia->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
		$inoperancia->selectParam();
		if($inoperancia->database->rows > 0){
			while ($row = pg_fetch_array($inoperancia->database->result)){
				?>fAdicionaInoperancia('<?=$pagina->ConvDataDMA($row["dat_inicio"])?>', '<?=substr($row["dat_inicio"],11,5)?>', '<?=$row["des_motivo"]?>', '<?=$pagina->ConvDataDMA($row["dat_fim"])?>', '<?=substr($row["dat_fim"],11,5)?>', '<?=$row["des_solucao"]?>');
				<?
			}
		}

		?>
	</script>
		<?
	$pagina->FechaTabelaPadrao();
	?>
	<script language="javascript">
		function fValidaFormLocal(){
			FormatarArrayInsercao(aEquipe, document.form.aEquipe);
			FormatarArrayInsercao(aItemRelacionamento, document.form.aItemRelacionamento);
			FormatarArrayInsercao(aUorSiglaEnvolvida, document.form.aUorSiglaEnvolvida);
			FormatarArrayInsercao(aAreaExterna, document.form.aAreaExterna);
			FormatarArrayInsercao(aHistorico, document.form.aHistorico);
			FormatarArrayInsercao(aInoperancia, document.form.aInoperancia);
			return true;
		}
	</script>
	<?

	$pagina->FechaTabelaPadrao();
	$pagina->LinhaVazia(2);
	print $pagina->CampoButton("return fValidaForm() && fValidaFormLocal();", " Salvar ");
	$pagina->MontaRodape();


}else{
	// Alterar regstro

	$banco->setSEQ_TIPO_ITEM_CONFIGURACAO($v_SEQ_TIPO_ITEM_CONFIGURACAO);
	$banco->setSEQ_SERVICO($v_SEQ_SERVICO);
	$banco->setNUM_MATRICULA_GESTOR($empregados->GetNumeroMatricula($v_NUM_MATRICULA_GESTOR));
	$banco->setNUM_MATRICULA_LIDER($empregados->GetNumeroMatricula($v_NUM_MATRICULA_LIDER));
	$banco->setSIG_ITEM_CONFIGURACAO($v_SIG_ITEM_CONFIGURACAO);
	$banco->setNOM_ITEM_CONFIGURACAO($v_NOM_ITEM_CONFIGURACAO);
	$banco->setCOD_UOR_AREA_GESTORA($v_COD_UOR_AREA_GESTORA);
	$banco->setTXT_ITEM_CONFIGURACAO($v_TXT_ITEM_CONFIGURACAO);
	$banco->setSEQ_TIPO_DISPONIBILIDADE($v_SEQ_TIPO_DISPONIBILIDADE);
	$banco->setSEQ_PRIORIDADE($v_SEQ_PRIORIDADE);
	$banco->setSEQ_CRITICIDADE($v_SEQ_CRITICIDADE);
	$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$banco->update($v_SEQ_ITEM_CONFIGURACAO);
	$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
	if($banco->error != ""){
		$pagina->mensagem("Registro não alterado. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		// Inserir dados do sistema de informação
		$item_configuracao_software->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
		$item_configuracao_software->selectParam();

		$item_configuracao_software->setSEQ_TIPO_SOFTWARE($v_SEQ_TIPO_SOFTWARE);
		$item_configuracao_software->setSEQ_STATUS_SOFTWARE($v_SEQ_STATUS_SOFTWARE);
		$item_configuracao_software->setFLG_EM_MANUTENCAO($v_FLG_EM_MANUTENCAO);
		$item_configuracao_software->setFLG_PETI($pagina->iif($v_FLG_PETI=="","N",$v_FLG_PETI));
		$item_configuracao_software->setNUM_ITEM_PETI($v_NUM_ITEM_PETI);
		$item_configuracao_software->setFLG_DESCONTINUADO($v_FLG_DESCONTINUADO);
		$item_configuracao_software->setFLG_SISTEMA_WEB($v_FLG_SISTEMA_WEB);
		$item_configuracao_software->setDSC_LOCALIZACAO_DOCUMENTACAO($v_DSC_LOCALIZACAO_DOCUMENTACAO);
		$item_configuracao_software->setVAL_TAMANHO_SOFTWARE($v_VAL_TAMANHO_SOFTWARE);
		$item_configuracao_software->setSEQ_UNIDADE_MEDIDA_SOFTWARE($v_SEQ_UNIDADE_MEDIDA_SOFTWARE);
		$item_configuracao_software->setVAL_AQUISICAO($v_VAL_AQUISICAO);
		$item_configuracao_software->setFLG_TAMANHO($v_FLG_TAMANHO);
		$item_configuracao_software->setSEQ_FREQUENCIA_MANUTENCAO($v_SEQ_FREQUENCIA_MANUTENCAO);
		if($item_configuracao_software->database->rows == 0){
			$item_configuracao_software->insert();
		}else{
			$item_configuracao_software->update($banco->SEQ_ITEM_CONFIGURACAO);
		}
		// Inserir linguagens de programação
		require_once 'include/PHP/class/class.software_linguagem_programacao.php';
		$software_linguagem_programacao = new software_linguagem_programacao();
		$software_linguagem_programacao->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
		$software_linguagem_programacao->delete($banco->SEQ_ITEM_CONFIGURACAO);
		for ($i = 0; $i < count($v_SEQ_LINGUAGEM_PROGRAMACAO); $i++){
			$software_linguagem_programacao->setSEQ_LINGUAGEM_PROGRAMACAO($v_SEQ_LINGUAGEM_PROGRAMACAO[$i]);
			$software_linguagem_programacao->insert();
		}

		// inserir bancos de dados
		require_once 'include/PHP/class/class.software_banco_de_dados.php';
		$software_banco_de_dados = new software_banco_de_dados();
		$software_banco_de_dados->delete($banco->SEQ_ITEM_CONFIGURACAO);
		$software_banco_de_dados->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
		for ($i = 0; $i < count($v_SEQ_BANCO_DE_DADOS); $i++){
			$software_banco_de_dados->setSEQ_BANCO_DE_DADOS($v_SEQ_BANCO_DE_DADOS[$i]);
			$software_banco_de_dados->insert();
		}


		// Inserir equipe
		require_once 'include/PHP/class/class.equipe_envolvida.php';
		$equipe_envolvida = new equipe_envolvida();
		$equipe_envolvida->delete1($banco->SEQ_ITEM_CONFIGURACAO);
		if(trim($aEquipe) != ""){
			$a_EQUIPE_ENVOLVIDA = split(";", $aEquipe);

			$equipe_envolvida->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
			for ($i = 0; $i < count($a_EQUIPE_ENVOLVIDA); $i++){
			// Pegar variáveis
				$aAux = split("\|", $a_EQUIPE_ENVOLVIDA[$i]);
				$v_NUM_MATRICULA_RECURSO = $aAux[0];
				$v_QTD_HORA_ALOCADA = $aAux[1];
				// Setar variáveis
				$equipe_envolvida->setNUM_MATRICULA_RECURSO($empregados->GetNumeroMatricula($v_NUM_MATRICULA_RECURSO));
				$equipe_envolvida->setQTD_HORA_ALOCADA($v_QTD_HORA_ALOCADA);
				$equipe_envolvida->insert();
			}
		}

		// inserir dependências
		require_once 'include/PHP/class/class.relacionamento_item_configuracao.php';
		$relacionamento_item_configuracao = new relacionamento_item_configuracao();
		$relacionamento_item_configuracao->deleteAll($banco->SEQ_ITEM_CONFIGURACAO);
		if(trim($aItemRelacionamento) != ""){
			$a_RELACIONAMENTO_ITEM_CONFIGURACAO = split(";", $aItemRelacionamento);
			$relacionamento_item_configuracao->setSEQ_ITEM_CONFIGURACAO_PAI($banco->SEQ_ITEM_CONFIGURACAO);
			for ($i = 0; $i < count($a_RELACIONAMENTO_ITEM_CONFIGURACAO); $i++){
				// Pegar variáveis
				$aAux = split("\|", $a_RELACIONAMENTO_ITEM_CONFIGURACAO[$i]);
				$v_SEQ_ITEM_CONFIGURACAO_FILHO = $aAux[0];
				$v_SEQ_TIPO_RELAC_ITEM_CONFIG = $aAux[1];
				$v_SEQ_TIPO_ITEM_CONFIGURACAO_RELACIONAMENTO = $aAux[2]; // Se for 1 - Servidor, Se for 2 - Sistema de Informação
				// Setar variáveis
				$relacionamento_item_configuracao->setSEQ_TIPO_RELAC_ITEM_CONFIG($v_SEQ_TIPO_RELAC_ITEM_CONFIG);

				if($v_SEQ_TIPO_ITEM_CONFIGURACAO_RELACIONAMENTO == "1"){
					$relacionamento_item_configuracao->setSEQ_ITEM_CONFIGURACAO_FILHO("");
					$relacionamento_item_configuracao->setSEQ_SERVIDOR($v_SEQ_ITEM_CONFIGURACAO_FILHO);
				}elseif($v_SEQ_TIPO_ITEM_CONFIGURACAO_RELACIONAMENTO == "2"){
					$relacionamento_item_configuracao->setSEQ_ITEM_CONFIGURACAO_FILHO($v_SEQ_ITEM_CONFIGURACAO_FILHO);
					$relacionamento_item_configuracao->setSEQ_SERVIDOR("");
				}
				$relacionamento_item_configuracao->insert();
			}
		}

		// inserir áreas envolvidas
		require_once 'include/PHP/class/class.area_envolvida.php';
		$area_envolvida = new area_envolvida();
		$area_envolvida->delete($banco->SEQ_ITEM_CONFIGURACAO);
		if(trim($aUorSiglaEnvolvida) != ""){
			$a_AREA_ENVOLVIDA = split(";", $aUorSiglaEnvolvida);
			$area_envolvida->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
			for ($i = 0; $i < count($a_AREA_ENVOLVIDA); $i++){
				// Pegar variáveis
				$aAux = split("\|", $a_AREA_ENVOLVIDA[$i]);
				$v_COD_UOR = $aAux[0];
				$v_NUM_MATRICULA_GESTOR = $aAux[1];
				// Setar variáveis
				$area_envolvida->setCOD_UOR($v_COD_UOR);
				$area_envolvida->setNUM_MATRICULA_GESTOR($empregados->GetNumeroMatricula($v_NUM_MATRICULA_GESTOR));
				$area_envolvida->insert();
			}
		}

		// inserir area externa
		require_once 'include/PHP/class/class.area_externa_envolvida.php';
		$area_externa_envolvida = new area_externa_envolvida();
		$area_externa_envolvida->delete($banco->SEQ_ITEM_CONFIGURACAO);
		if(trim($aAreaExterna) != ""){
			$a_AREA_EXTERNA = split(";", $aAreaExterna);
			$area_externa_envolvida->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
			for ($i = 0; $i < count($a_AREA_EXTERNA); $i++){
				// Pegar variáveis
				$aAux = split("\|", $a_AREA_EXTERNA[$i]);
				$v_SEQ_AREA_EXTERNA = $aAux[0];
				$v_NOM_CONTATO = $aAux[1];
				$v_NUM_TELEFONE = $aAux[2];
				// Setar variáveis
				$area_externa_envolvida->setSEQ_AREA_EXTERNA($v_SEQ_AREA_EXTERNA);
				$area_externa_envolvida->setNOM_CONTATO($v_NOM_CONTATO);
				$area_externa_envolvida->setNUM_TELEFONE($v_NUM_TELEFONE);
				$area_externa_envolvida->insert();
			}
		}

		// Inserir histórico
		require_once 'include/PHP/class/class.fase_item_configuracao.php';
		$fase_item_configuracao = new fase_item_configuracao();
		$fase_item_configuracao->delete($banco->SEQ_ITEM_CONFIGURACAO);
		if(trim($aHistorico) != ""){
			$a_HISTORICO = split(";", $aHistorico);
			$fase_item_configuracao->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
			for ($i = 0; $i < count($a_HISTORICO); $i++){
				// Pegar variáveis
				$aAux = split("\|", $a_HISTORICO[$i]);
				$v_DSC_FASE_ITEM_CONFIGURACAO = $aAux[0];
				$v_SEQ_FASE_PROJETO = $aAux[1];
				$v_TXT_OBSERVACAO_FASE = $aAux[2];
				$v_DAT_INICIO_FASE_PROJETO = $aAux[3];
				$v_DAT_FIM_FASE_PROJETO = $aAux[4];

				// Setar variáveis
				$fase_item_configuracao->setDSC_FASE_ITEM_CONFIGURACAO($v_DSC_FASE_ITEM_CONFIGURACAO);
				$fase_item_configuracao->setSEQ_FASE_PROJETO($v_SEQ_FASE_PROJETO);
				$fase_item_configuracao->setTXT_OBSERVACAO_FASE($v_TXT_OBSERVACAO_FASE);
				$fase_item_configuracao->setDAT_INICIO_FASE_PROJETO($pagina->ConvDataAMD($v_DAT_INICIO_FASE_PROJETO));
				$fase_item_configuracao->setDAT_FIM_FASE_PROJETO($pagina->ConvDataAMD($v_DAT_FIM_FASE_PROJETO));
				$fase_item_configuracao->insert();
			}
		}

		// Inserir inoperância
		require_once 'include/PHP/class/class.inoperancia.php';
		$inoperancia = new inoperancia();
		$inoperancia->delete($banco->SEQ_ITEM_CONFIGURACAO);
		if(trim($aInoperancia) != ""){
			$a_INOPERANCIA = split(";", $aInoperancia);
			$inoperancia->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
			for ($i = 0; $i < count($a_INOPERANCIA); $i++){
				// Pegar variáveis
				$aAux = split("\|", $a_INOPERANCIA[$i]);
				$v_DAT_INICIO = $aAux[0];
				$v_DES_MOTIVO = $aAux[1];
				$v_DAT_FIM = $aAux[2];
				$v_DES_SOLUCAO = $aAux[3];
				// Setar variáveis
				$inoperancia->setDTH_INICIO($pagina->ConvDataAMD($v_DAT_INICIO)." ".substr($v_DAT_INICIO,11,5).":00");
				$inoperancia->setTXT_MOTIVO($v_DES_MOTIVO);
				$inoperancia->setDTH_FIM($pagina->ConvDataAMD($v_DAT_FIM)." ".substr($v_DAT_FIM,11,5).":00");
				$inoperancia->setTXT_SOLUCAO($v_DES_SOLUCAO);
				$inoperancia->insert();
			}
		}

		$pagina->redirectTo("Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=$banco->SEQ_ITEM_CONFIGURACAO");
	}
}
?>
