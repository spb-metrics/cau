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
require_once 'include/PHP/class/class.item_configuracao.php';
require_once 'include/PHP/class/class.empregados.oracle.php';

$pagina = new Pagina();
$empregados = new empregados();
// Carregando detalhes de Sistemas de Informação
if($v_SEQ_ITEM_CONFIGURACAO != ""){
	require_once 'include/PHP/class/class.item_configuracao_software.php';
	require_once 'include/PHP/class/class.unidade_organizacional.php';

	$item_configuracao = new item_configuracao();
	$banco = new item_configuracao();
	$unidade_organizacional = new unidade_organizacional();
	$item_configuracao_software = new item_configuracao_software();

	$pagina->setMethod("post");
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Detalhes de Item do Parque Tecnológico"); // Indica o título do cabeçalho da página
	// Itens das abas
	$pagina->ForcaAutenticacao();
	if($pagina->segurancaPerfilExclusao($_SESSION["SEQ_PERFIL_ACESSO"])){ // Apenas o adminsitrador
		$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
		 			   array("Item_configuracaoCadastro1.php", "", "Adicionar"),
					   array("Item_configuracaoAlteracao.php?v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO", "", "Alterar"),
					   array("javascript: fDeletarPlus('v_SEQ_ITEM_CONFIGURACAO', '$v_SEQ_ITEM_CONFIGURACAO', 'Item_configuracaoPesquisa.php');", "", "Excluir"),
					   array("#", "tabact", "Detalhes")
						 );
	}elseif($pagina->segurancaPerfilAlteracao($_SESSION["SEQ_PERFIL_ACESSO"])){ // Apenas o adminsitrador
		$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
		 			   array("Item_configuracaoCadastro1.php", "", "Adicionar"),
					   array("Item_configuracaoAlteracao.php?v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO", "", "Alterar"),
					   array("#", "tabact", "Detalhes")
						 );
	}else{
		$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
					   array("#", "tabact", "Detalhes")
						 );
	}
	$pagina->SetaItemAba($aItemAba);
	// pesquisa
	$banco->select($v_SEQ_ITEM_CONFIGURACAO);

	// Inicio do formulário
	$pagina->MontaCabecalho();
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
	$pagina->LinhaCampoFormulario("Tipo:", "right", "N", $tipo_item_configuracao->NOM_TIPO_ITEM_CONFIGURACAO, "left", "id=".$pagina->GetIdTable(), "30%", "70%");

	$pagina->LinhaCampoFormulario("Sigla:", "right", "N", $banco->SIG_ITEM_CONFIGURACAO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Nome:", "right", "N", $banco->NOM_ITEM_CONFIGURACAO, "left", "id=".$pagina->GetIdTable());

	// Montar a combo da tabela equipe de ti
	require_once 'include/PHP/class/class.dependencias.php';
	$dependencias = new dependencias();

	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$equipe_ti->select($banco->SEQ_EQUIPE_TI);
	$v_COD_DEPENDENCIA = $equipe_ti->COD_DEPENDENCIA;

	$dependencias->select($v_COD_DEPENDENCIA);

	$pagina->LinhaCampoFormulario("Equipe Responsável:", "right", "N",
								$dependencias->DEP_SIGLA." - ".
								$equipe_ti->NOM_EQUIPE_TI
								, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Gestor:", "right", "N",
								   $empregados->GetNomeEmpregado($banco->NUM_MATRICULA_GESTOR)
								  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Matricula do Líder:", "right", "N",
								  $empregados->GetNomeEmpregado($banco->NUM_MATRICULA_LIDER)
								  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Unidade organizacional Gestora:", "right", "N",
								  $unidade_organizacional->GetUorNome($banco->COD_UOR_AREA_GESTORA)
								  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Descrição:", "right", "N", str_replace(chr(13),"<br>",$banco->TXT_ITEM_CONFIGURACAO), "left", "id=".$pagina->GetIdTable());


	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.tipo_disponibilidade.php';
	$tipo_disponibilidade = new tipo_disponibilidade();
	$tipo_disponibilidade->select($banco->SEQ_TIPO_DISPONIBILIDADE);
	$aItemOption = Array();
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Disponibilidade necessária:", "right", "N", $tipo_disponibilidade->NOM_TIPO_DISPONIBILIDADE, "left", "id=".$pagina->GetIdTable());

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.prioridade.php';
	$PRIORIDADE = new PRIORIDADE();
	$PRIORIDADE->select($banco->SEQ_PRIORIDADE);
	$pagina->LinhaCampoFormulario("Prioridade:", "right", "N", $PRIORIDADE->NOM_PRIORIDADE, "left", "id=".$pagina->GetIdTable());

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.criticidade.php';
	$criticidade = new criticidade();
	$criticidade->select($banco->SEQ_CRITICIDADE);
	$pagina->LinhaCampoFormulario("Criticidade:", "right", "N", $criticidade->NOM_CRITICIDADE, "left", "id=".$pagina->GetIdTable());

	//$pagina->LinhaCampoFormulario("<a target=\"_blank\" href=Item_configuracaoPDF.php?v_SEQ_ITEM_CONFIGURACAO=".$banco->SEQ_TIPO_ITEM_CONFIGURACAO."><img src='imagens/pdf.gif' border=0></a>", "right", "N", "<a target=\"_blank\" href=Item_configuracaoPDF.php?v_SEQ_ITEM_CONFIGURACAO=".$banco->SEQ_TIPO_ITEM_CONFIGURACAO.">Relatório em PDF</a>", "left", "id=".$pagina->GetIdTable());

	?>
	<script>
		function fMostra(id, idTab){
			//document.getElementById("tabelaGeral").style.display = "none";
			//document.getElementById("tabGeral").attributes["class"].value = "";
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
			//array("javascript: fMostra('tabelaOS','tabOS')", "", "&nbsp;O.S.&nbsp;","tabOS"),
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
	if($item_configuracao_software->SEQ_TIPO_SOFTWARE != ""){
		require_once 'include/PHP/class/class.tipo_software.php';
		$tipo_software = new tipo_software();
		$tipo_software->select($item_configuracao_software->SEQ_TIPO_SOFTWARE);
		$pagina->LinhaCampoFormulario("Tipo:", "right", "N", $tipo_software->NOM_TIPO_SOFTWARE, "left", "id=".$pagina->GetIdTable(),"30%" );
	}else{
		$pagina->LinhaCampoFormulario("Tipo:", "right", "N", "", "left", "id=".$pagina->GetIdTable(),"30%" );
	}

	// Buscar dados da tabela externa
	if($item_configuracao_software->SEQ_STATUS_SOFTWARE != ""){
		require_once 'include/PHP/class/class.status_software.php';
		$status_software = new status_software();
		$status_software->select($item_configuracao_software->SEQ_STATUS_SOFTWARE);
		$pagina->LinhaCampoFormulario("Status:", "right", "N", $status_software->NOM_STATUS_SOFTWARE, "left", "id=".$pagina->GetIdTable());
	}else{
		$pagina->LinhaCampoFormulario("Status:", "right", "N", "", "left", "id=".$pagina->GetIdTable());
	}

	if($item_configuracao_software->FLG_EM_MANUTENCAO == "S"){
		$v_FLG_EM_MANUTENCAO = "Sim - Interno";
	}elseif($item_configuracao_software->FLG_EM_MANUTENCAO == "E"){
		$v_FLG_EM_MANUTENCAO = "Sim - Interno";
	}elseif($item_configuracao_software->FLG_EM_MANUTENCAO == "N"){
		$v_FLG_EM_MANUTENCAO = "Não";
	}

	$pagina->LinhaCampoFormulario("Em manutenção?", "right", "N", $v_FLG_EM_MANUTENCAO, "left", "id=".$pagina->GetIdTable());

	if($item_configuracao_software->SEQ_FREQUENCIA_MANUTENCAO != ""){
		// Buscar dados da tabela externa
		require_once 'include/PHP/class/class.frequencia_manutencao.php';
		$frequencia_manutencao = new frequencia_manutencao();
		$frequencia_manutencao->select($item_configuracao_software->SEQ_FREQUENCIA_MANUTENCAO);
		$pagina->LinhaCampoFormulario("Frequência de Manutenção:", "right", "N", $frequencia_manutencao->NOM_FREQUENCIA_MANUTENCAO, "left", "id=".$pagina->GetIdTable());
	}else{
		$pagina->LinhaCampoFormulario("Frequência de Manutenção:", "right", "N", "Não definido", "left", "id=".$pagina->GetIdTable());
	}

	if($item_configuracao_software->FLG_TAMANHO == "P"){
		$FLG_TAMANHO = "Pequeno";
	}elseif($item_configuracao_software->FLG_TAMANHO == "M"){
		$FLG_TAMANHO = "Médio";
	}elseif($item_configuracao_software->FLG_TAMANHO == "G"){
		$FLG_TAMANHO = "Grande";
	}
	$pagina->LinhaCampoFormulario("Tamanho", "right", "N", $FLG_TAMANHO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Será Descontinuado?", "right", "N", $pagina->iif($item_configuracao_software->FLG_DESCONTINUADO == "S", "Sim","Não"), "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Sistema web?", "right", "N", $pagina->iif($item_configuracao_software->FLG_SISTEMA_WEB == "S", "Sim","Não"), "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Localização da documentação:", "right", "N", $item_configuracao_software->DSC_LOCALIZACAO_DOCUMENTACAO, "left", "id=".$pagina->GetIdTable());

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.banco_de_dados.php';
	require_once 'include/PHP/class/class.software_banco_de_dados.php';
	$banco_de_dados = new banco_de_dados();
	$software_banco_de_dados = new software_banco_de_dados();
	$software_banco_de_dados->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
	$software_banco_de_dados->selectParam();
	$cont = 0;
	$aux = "";
	while ($row = pg_fetch_array($software_banco_de_dados->database->result)){
		$banco_de_dados->select($row["seq_banco_de_dados"]);
		$aux .= $banco_de_dados->NOM_BANCO_DE_DADOS;
		if($cont < $software_banco_de_dados->database->rows){
			$aux .= ", ";
		}
		$cont++;
	}
	$pagina->LinhaCampoFormulario("Bancos de dados utilizados:", "right", "N", $aux, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Tamanho do software:", "right", "N", $item_configuracao_software->VAL_TAMANHO_SOFTWARE, "left", "id=".$pagina->GetIdTable());

	if($item_configuracao_software->SEQ_UNIDADE_MEDIDA_SOFTWARE != ""){
		// Buscar dados da tabela externa
		require_once 'include/PHP/class/class.unidade_medida_software.php';
		$unidade_medida_software = new unidade_medida_software();
		$unidade_medida_software->select($item_configuracao_software->SEQ_UNIDADE_MEDIDA_SOFTWARE);
		$pagina->LinhaCampoFormulario("Unidade de Medida:", "right", "N", $unidade_medida_software->NOM_UNIDADE_MEDIDA_SOFTWARE, "left", "id=".$pagina->GetIdTable());
	}else{
		$pagina->LinhaCampoFormulario("Unidade de Medida:", "right", "N", "", "left", "id=".$pagina->GetIdTable());
	}

	$pagina->LinhaCampoFormulario("Valor de Aquisição:", "right", "N", $item_configuracao_software->VAL_AQUISICAO_SOFTWARE, "left", "id=".$pagina->GetIdTable());

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.linguagem_programacao.php';
	require_once 'include/PHP/class/class.software_linguagem_programacao.php';
	$linguagem_programacao = new linguagem_programacao();
	$software_linguagem_programacao = new software_linguagem_programacao();
	$software_linguagem_programacao->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
	$software_linguagem_programacao->selectParam(2);
	$aux = "";
	$cont = 0;
	while ($row = pg_fetch_array($software_linguagem_programacao->database->result)){
		$linguagem_programacao->select($row["seq_linguagem_programacao"]);
		$aux .= $linguagem_programacao->NOM_LINGUAGEM_PROGRAMACAO;
		if($cont < $linguagem_programacao->database->rows){
			$aux .= ", ";
		}
		$cont++;
	}
	$pagina->LinhaCampoFormulario("Linguagens de Programação:", "right", "N", $aux, "left", "id=".$pagina->GetIdTable());
	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaEquipe style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Equipe Envolvida", 2);

	print $pagina->CampoHidden("aEquipe", "");
	print $pagina->CampoHidden("v_NOME_EMPREGADO", "");

	// Inserir equipe existente em banco de dados na tela
	require_once 'include/PHP/class/class.equipe_envolvida.php';
	//require_once 'include/PHP/class/class.empregados.php';
	$empregados = new empregados();
	$equipe_envolvida = new equipe_envolvida();
	$equipe_envolvida->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
	$equipe_envolvida->selectParam();
	if($equipe_envolvida->database->rows > 0){
		$tabela = array();
		$header = array();
		$header[] = array("Matrícula", "center", "20%", "header");
		$header[] = array("Nome", "center", "60%", "header");
		$header[] = array("Qd. Horas semanais", "center", "", "header");
		$tabela[] = $header;
		while ($row = pg_fetch_array($equipe_envolvida->database->result)){
			$header = array();
			$empregados->select($row["num_matricula_recurso"]);
			$header[] = array($empregados->NOM_LOGIN_REDE, "center", "", "");
			$header[] = array($empregados->NOME, "center", "", "");
			$header[] = array($row["qtd_hora_alocada"], "right", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	}else{
		$pagina->LinhaCampoFormularioColspan("left","Nenhuma equipe registrada", 2);
	}
	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaDependencias style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");

	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Dependências", 2);
	print $pagina->CampoHidden("aItemRelacionamento", "");
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
		$tabela = array();
		$header = array();
		$header[] = array("Tipo", "center", "", "header");
		$header[] = array("Item", "center", "", "header");
		$header[] = array("Dependência", "center", "", "header");
		$tabela[] = $header;
		while ($row = pg_fetch_array($relacionamento_item_configuracao->database->result)){
			$header = array();
			if($row["seq_item_configuracao_filho"] != ""){
				$item_configuracao->select($row["seq_item_configuracao_filho"]);
				$header[] = array("Sistema de Informação", "center", "", "");
				$header[] = array($item_configuracao->NOM_ITEM_CONFIGURACAO, "center", "", "");
			}elseif($row["seq_servidor"] != ""){
				$servidor->select($row["seq_servidor"]);
				$header[] = array("Servidor", "center", "", "");
				$header[] = array($servidor->NOM_SERVIDOR, "center", "", "");
			}

			$tipo_relacionamento_item_configuracao->select($row["seq_tipo_relac_item_config"]);
			$header[] = array($tipo_relacionamento_item_configuracao->NOM_TIPO_RELAC_ITEM_CONFIG, "center", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	}else{
		$pagina->LinhaCampoFormularioColspan("left","Nenhuma dependência registrada", 2);
	}
	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAreasInternas style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Áreas Internas Envolvidas", 2);
	print $pagina->CampoHidden("aUorSiglaEnvolvida", "");
	// Inserir equipe existente em banco de dados na tela
	require_once 'include/PHP/class/class.area_envolvida.php';
	$area_envolvida = new area_envolvida();
	$area_envolvida->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
	$area_envolvida->selectParam();
	if($area_envolvida->database->rows > 0){
		$tabela = array();
		$header = array();
		$header[] = array("Área", "center", "", "header");
		$header[] = array("Contato", "center", "", "header");
		$tabela[] = $header;
		while ($row = pg_fetch_array($area_envolvida->database->result)){
			$header = array();
			$header[] = array($unidade_organizacional->GetUorSigla($row["cod_uor"]), "center", "", "");
			$header[] = array($pagina->iif($row["num_matricula_gestor"]=="","",$empregados->GetNomLoginRedeMatricula($row["num_matricula_gestor"])), "center", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%", "", true), 2);
	}else{
		$pagina->LinhaCampoFormularioColspan("left","Nenhuma área interna envolvida", 2);
	}
	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAreasExternas style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Áreas Externas Envolvidas", 2);

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

	// Inserir equipe existente em banco de dados na tela
	require_once 'include/PHP/class/class.area_externa_envolvida.php';
	require_once 'include/PHP/class/class.area_externa.php';
	$area_externa = new area_externa();
	$area_externa_envolvida = new area_externa_envolvida();
	$area_externa_envolvida->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
	$area_externa_envolvida->selectParam();
	if($area_externa_envolvida->database->rows > 0){
		$tabela = array();
		$header = array();
		$header[] = array("Area", "center", "", "header");
		$header[] = array("Nome Contato", "center", "", "header");
		$header[] = array("Telefone", "center", "", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($area_externa_envolvida->database->result)){
			$area_externa->select($row["seq_area_externa"]);
			$header = array();
			$header[] = array($area_externa->NOM_AREA_EXTERNA, "center", "", "");
			$header[] = array($row["nom_contato"], "center", "", "");
			$header[] = array($row["num_telefone"], "center", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	}else{
		$pagina->LinhaCampoFormularioColspan("left","Nenhuma área externa envolvida", 2);
	}

	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaHistorico style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Registro Histórico", 2);
	print $pagina->CampoHidden("aHistorico", "");

	// Inserir histórico existente no banco de dados, na tela
	require_once 'include/PHP/class/class.fase_item_configuracao.php';
	$fase_item_configuracao = new fase_item_configuracao();
	$fase_item_configuracao->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
	$fase_item_configuracao->selectParam();
	if($fase_item_configuracao->database->rows > 0){
		$tabela = array();
		$header = array();
		$header[] = array("Titulo", "center", "", "header");
		$header[] = array("Fase", "center", "", "header");
		$header[] = array("Descrição", "center", "", "header");
		$header[] = array("Início", "center", "", "header");
		$header[] = array("Fim", "center", "", "header");
		$tabela[] = $header;
		while ($row = pg_fetch_array($fase_item_configuracao->database->result)){
			require_once 'include/PHP/class/class.fase_projeto.php';
			$fase_projeto = new fase_projeto();
			$fase_projeto->select($row["seq_fase_projeto"]);
			$valor1 = $fase_projeto->NOM_FASE_PROJETO;


			$header = array();
			$header[] = array($row["dsc_fase_item_configuracao"], "center", "", "");
			$header[] = array($valor1, "center", "", "");
			$header[] = array($row["txt_observacao_fase"], "center", "", "");
			$header[] = array($pagina->ConvDataDMA($row["dat_inicio_fase_projeto"],"/"), "center", "", "");
			$header[] = array($pagina->ConvDataDMA($row["dat_fim_fase_projeto"],"/"), "center", "", "");
			$tabela[] = $header;
			$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
		}
	}else{
		$pagina->LinhaCampoFormularioColspan("left","Nenhum registro de histórico existente", 2);
	}



	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaOS style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	// O.S. ==============================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Ordens de Serviços", 2);

	require_once 'include/PHP/class/class.ordem_servico.php';
	$ordem_servico = new ordem_servico();
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$ordem_servico->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
	$ordem_servico->selectParam("DAT_ENTREGA", $vNumPagina);
	if($ordem_servico->database->rows > 0){
		$tabela = array();
		$header = array();
		$header[] = array("Fornecedor", "center", "30%", "header");
		$header[] = array("Nº da O.S.", "center", "15%", "header");
		$header[] = array("Valor", "center", "10%", "header");
		$header[] = array("Entrega", "center", "10%", "header");
		$header[] = array("Descrição", "center", "35%", "header");
		$tabela[] = $header;
		$corpo = array();
		while ($row = pg_fetch_array($ordem_servico->database->result)){
			require_once 'include/PHP/class/class.fornecedor.php';
			$fornecedor = new fornecedor();
			$fornecedor->select($row["num_cpf_cgc"]);
			$corpo[] = array($fornecedor->NOM_FORNECEDOR, "center", "",  "");
			$corpo[] = array($row["num_ordem_servico"], "center", "", "");
			$corpo[] = array(number_format($row["val_pagamento"],2), "center", "campo", "");
			$corpo[] = array($pagina->ConvDataDMA($row["dat_entrega"],"/"), "center", "campo", "");
			$corpo[] = array($row["dsc_ordem_servico"], "center", "campo", "");
			$tabela[] = $corpo;
			$corpo = "";
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	}else{
		$pagina->LinhaCampoFormularioColspan("left", "Nenhuma O.S. vinculada", 2);
	}

	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaInoperancia style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Registro de Inoperâncias", 2);
	print $pagina->CampoHidden("aInoperancia", "");

	// Inserir histórico existente no banco de dados, na tela
	require_once 'include/PHP/class/class.inoperancia.php';
	$inoperancia = new inoperancia();
	$inoperancia->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
	$inoperancia->selectParam();
	if($inoperancia->database->rows > 0){
		$tabela = array();
		$header = array();
		$header[] = array("Inicio", "center", "17%", "header");
		$header[] = array("Motivo", "center", "20%", "header");
		$header[] = array("Fim", "center", "17%", "header");
		$header[] = array("Solução", "center", "20%", "header");
		$tabela[] = $header;
		while ($row = pg_fetch_array($inoperancia->database->result)){
			$header = array();
			$header[] = array($pagina->ConvDataDMA($row["dth_inicio"],"/")." ".substr($row["dth_inicio"],11,5), "center", "17%", "");
			$header[] = array($row["txt_motivo"], "center", "20%", "");
			$header[] = array($pagina->ConvDataDMA($row["dth_fim"],"/")." ".substr($row["dth_fim"],11,5), "center", "17%", "");
			$header[] = array($row["txt_solucao"], "center", "20%", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","", true), 2);
	}else{
		$pagina->LinhaCampoFormularioColspan("left","Nenhum registro de inoperância existente", 2);
	}
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}elseif($v_NUM_PATRIMONIO != ""){
	require_once 'include/PHP/class/class.patrimonio_ti.ativos.php';
	require_once 'include/PHP/class/class.chamado.php';
	require_once 'include/PHP/class/class.prioridade_chamado.php';
	require_once 'include/PHP/class/class.situacao_chamado.php';
	require_once 'include/PHP/class/class.subtipo_chamado.php';
	require_once 'include/PHP/class/class.tipo_chamado.php';
	//require_once 'include/PHP/class/class.patrimonio_ti.catalogo.php';
	$banco = new chamado();
	$pagina = new Pagina();
	$bancoPatrimonio = new ativos();
	$prioridade_chamado = new prioridade_chamado();
	$tipo_chamado = new tipo_chamado();
	$subtipo_chamado = new subtipo_chamado();
	$situacao_chamado = new situacao_chamado();
	//$patrimonioCategoria = new catalogo();
	$empregados = new empregados();
	// Configuração da págína
	$pagina->SettituloCabecalho("Detalhes de Item do Parque Tecnológico"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
//		 			   array("Item_configuracaoCadastro.php", "", "Adicionar"),
					   array("#", "tabact", "Detalhes")
						 );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();
	$bancoPatrimonio->select($v_NUM_PATRIMONIO);
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", $banco->SEQ_ITEM_CONFIGURACAO);

	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormulario("Emepresa:", "right", "N", $bancoPatrimonio->DES_REGIONAL, "left", "id=".$pagina->GetIdTable(), "30%", "70%");
	$pagina->LinhaCampoFormulario("Setor:", "right", "N", $bancoPatrimonio->DES_DEPENDENCIA." - ".$bancoPatrimonio->SIG_DEPENDENCIA, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Nº Patrimânio:", "right", "N", $bancoPatrimonio->NUM_PATRIMONIO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Nome:", "right", "N", $bancoPatrimonio->NOM_BEM, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Data de Aquisição:", "right", "N", $pagina->ConvDataDMA($bancoPatrimonio->DAT_AQUISICAO,"/"), "left", "id=".$pagina->GetIdTable());
//	$pagina->LinhaCampoFormulario("Modelo:", "right", "N", $bancoPatrimonio->NOM_MODELO, "left", "id=".$pagina->GetIdTable());
//	$pagina->LinhaCampoFormulario("Fabricante:", "right", "N", $bancoPatrimonio->NOM_FABRICANTE, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Nº de Série:", "right", "N", $bancoPatrimonio->NUM_SERIE, "left", "id=".$pagina->GetIdTable());
//	$pagina->LinhaCampoFormulario("Cor:", "right", "N", $bancoPatrimonio->NOM_COR, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Localização:", "right", "N", $bancoPatrimonio->DSC_LOCALIZACAO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Detentor:", "right", "N", $bancoPatrimonio->NOM_DETENTOR, "left", "id=".$pagina->GetIdTable());
//	$pagina->LinhaCampoFormulario("Setor:", "right", "N", $bancoPatrimonio->NOM_LOTACAO, "left", "id=".$pagina->GetIdTable());
//	$pagina->LinhaCampoFormulario("Status:", "right", "N", $bancoPatrimonio->NOM_STATUS, "left", "id=".$pagina->GetIdTable());
//	$pagina->LinhaCampoFormulario("Vida útil estimada:", "right", "N", $bancoPatrimonio->QTD_VIDA_UTIL_ESTIMADA, "left", "id=".$pagina->GetIdTable());
//	$pagina->LinhaCampoFormulario("Vida útil transcorrida:", "right", "N", $bancoPatrimonio->QTD_VIDA_UTIL_TRANSCORRIDA, "left", "id=".$pagina->GetIdTable());
//	$pagina->LinhaCampoFormulario("Valor de Aquisição:", "right", "N", "R$ ".$bancoPatrimonio->VAL_AQUISICAO, "left", "id=".$pagina->GetIdTable());
//	$pagina->LinhaCampoFormulario("Valor de depreciação acumulada:", "right", "N", $bancoPatrimonio->VAL_DEPRECIACAO_ACUMULADA, "left", "id=".$pagina->GetIdTable());


//	$aItemAba = Array(
//			array("", "tabact", "&nbsp;Chamados&nbsp;", "tabChamados"),
//		 			     );
//	$pagina->SetaItemAba($aItemAba);
//	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");

	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabChamados cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	//================================================================================================================================
	//================================================================================================================================

	$pagina->LinhaCampoFormularioColspanDestaque("Chamados", 2);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Prioridade", "10%");
	$header[] = array("Chamado", "10%");
	$header[] = array($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO"), "20%");
	$header[] = array("Solicitação", "20%");
	$header[] = array("Situação", "15%");
	$header[] = array("Abertura", "10%");
	$header[] = array("Vencimento", "10%");
	$header[] = array("SLA", "5%");

	// Setar variáveis
//	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);

	$banco->setNUM_PATRIMONIO((int)$v_NUM_PATRIMONIO);
	$banco->selectParam("DTH_ABERTURA DESC");
	if($banco->database->rows > 0){
		$corpo = array();
//		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Chamados relacionados com o patrimônio", $header);
		$vLink = "?flag=1&v_SEQ_CHAMADO_PESQUISA=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL";
		while ($row = pg_fetch_array($banco->database->result)){
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

			// Solicitação
			$corpo[] = array("left", "campo", $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

			// Situação
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

			$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoDetalhe.php".$vLink."&vNumPagina=$vNumPagina&v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
			$corpo = "";
		}
		$pagina->FechaTabelaPadrao();
//		$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_CHAMADO=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL&v_SEQ_TIPO_OCORRENCIA=$v_SEQ_TIPO_OCORRENCIA");
	}else{
		$pagina->LinhaColspan("center", "Chamados encontrados para os parâmetros informados", "2", "header");
		$pagina->LinhaColspan("left", "Nenhum chamado encontrado", "2", "campo");
		$pagina->FechaTabelaPadrao();
	}


	$pagina->FechaTabelaPadrao();
}else{
	print "Selecione o item";
}

?>