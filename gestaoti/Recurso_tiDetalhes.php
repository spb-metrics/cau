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
require 'include/PHP/class/class.recurso_ti.php';
require 'include/PHP/class/class.area_atuacao.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.perfil_recurso_ti.php';
require_once 'include/PHP/class/class.perfil_acesso.php';
require_once 'include/PHP/class/class.area_atuacao.php';
require 'include/PHP/class/class.item_configuracao.php';
require 'include/PHP/class/class.unidade_organizacional.php';
require 'include/PHP/class/class.servidor.php';
require 'include/PHP/class/class.equipe_servidor.php';
require 'include/PHP/class/class.equipe_ti.php';
$equipe_ti = new equipe_ti();
$pagina = new Pagina();
$item_configuracao = new item_configuracao();
$unidade_organizacional = new unidade_organizacional();
// Carregando detalhes de Sistemas de Informação
if($v_NUM_MATRICULA_RECURSO != ""){
	$banco = new recurso_ti();
	$recurso_ti = new recurso_ti();
	$empregados = new empregados();
	$perfil_acesso = new perfil_acesso();
	$perfil_recurso_ti = new perfil_recurso_ti();
	$area_atuacao = new area_atuacao();
	$servidor = new servidor();
	$equipe_servidor = new equipe_servidor();
	$pagina->setMethod("post");

	// pesquisa
	$banco->select($v_NUM_MATRICULA_RECURSO);

	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Detalhes do Colaborador"); // Indica o título do cabeçalho da página
	// Itens das abas

	$pagina->ForcaAutenticacao();

	if($pagina->segurancaPerfilExclusao($_SESSION["SEQ_PERFIL_ACESSO"])){ // Apenas o adminsitrador
		$aItemAba = Array( array("Recurso_tiPesquisa.php", "", "Pesquisa"),
		 			   array("Recurso_tiCadastro.php", "", "Adicionar"),
					   array("Recurso_tiAlteracao.php?v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO", "", "Alterar"),
					   array("javascript: fDeletarPlus('v_NUM_MATRICULA_RECURSO', '$v_NUM_MATRICULA_RECURSO', 'Recurso_tiPesquisa.php');", "", "Excluir"),
					   array("#", "tabact", "Detalhes")
						 );
	}elseif($pagina->segurancaPerfilAlteracao($_SESSION["SEQ_PERFIL_ACESSO"])){ // Apenas os gestores
		// Liberar alteração apenas para o pessoal da própria equipe
		if($banco->SEQ_EQUIPE_TI == $_SESSION["SEQ_EQUIPE_TI"]){
			$aItemAba = Array( array("Recurso_tiPesquisa.php", "", "Pesquisa"),
			 			   array("Recurso_tiCadastro.php", "", "Adicionar"),
						   array("Recurso_tiAlteracao.php?v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO", "", "Alterar"),
						   array("#", "tabact", "Detalhes")
							 );
		}else{
				$aItemAba = Array( array("Recurso_tiPesquisa.php", "", "Pesquisa"),
			 			   array("Recurso_tiCadastro.php", "", "Adicionar"),
						   array("#", "tabact", "Detalhes")
							 );
		}
	}else{
		$aItemAba = Array( array("Recurso_tiPesquisa.php", "", "Pesquisa"),
					   array("#", "tabact", "Detalhes")
						 );
	}
	$pagina->SetaItemAba($aItemAba);

	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_NUM_MATRICULA_RECURSO", $v_NUM_MATRICULA_RECURSO);
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais", 2);

	$pagina->LinhaCampoFormulario("Nome:", "right", "N", $banco->NOME, "left", "id=".$pagina->GetIdTable(),"20%","");
	$pagina->LinhaCampoFormulario("Nome Abreviado:", "right", "N", $banco->NOME_ABREVIADO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Nome de Guerra:", "right", "N", $banco->NOME_GUERRA, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Login de rede:", "right", "N", $banco->NOM_LOGIN_REDE, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Diretoria:", "right", "N", $banco->DEP_SIGLA, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Lotação:", "right", "N", $banco->UOR_SIGLA, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("E-mail:", "right", "N", $banco->DES_EMAIL, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Telefone:", "right", "N", $banco->NUM_DDD." - ".$banco->NUM_TELEFONE, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("VOIP:", "right", "N", $banco->NUM_DDD." - ".$banco->NUM_VOIP, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Status:", "right", "N", $banco->DES_ATATUS=="t"?"Ativo":"Desativado", "left", "id=".$pagina->GetIdTable());

	$perfil_recurso_ti->select($banco->SEQ_PERFIL_RECURSO_TI);
	$pagina->LinhaCampoFormulario("Cargo/Função:", "right", "N", $perfil_recurso_ti->NOM_PERFIL_RECURSO_TI, "left", "id=".$pagina->GetIdTable());

	$perfil_recurso_ti->select($banco->SEQ_PERFIL_RECURSO_TI);
	$pagina->LinhaCampoFormulario("Equipe:", "right", "N", $equipe_ti->NOM_EQUIPE_TI, "left", "id=".$pagina->GetIdTable());
	
	/*TODO: NOVO PERFIL ACESSO*/
	
	//$perfil_acesso->select($banco->SEQ_PERFIL_ACESSO);
	///$pagina->LinhaCampoFormulario("Perfil de Acesso:", "right", "N", $perfil_acesso->NOM_PERFIL_ACESSO, "left", "id=".$pagina->GetIdTable());
	
	
	require_once 'include/PHP/class/class.recurso_ti_x_perfil_acesso.php';
	$recurso_ti_x_perfil_acesso = new recurso_ti_x_perfil_acesso();
	$recurso_ti_x_perfil_acesso->setNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
	$recurso_ti_x_perfil_acesso->selectParam();
	$vPerfis = "";
	if($recurso_ti_x_perfil_acesso->database->rows == 0){
		//require_once 'include/PHP/class/class.perfil_acesso.php';
		//$perfil_acesso = new perfil_acesso();
		//echo $banco->SEQ_PERFIL_ACESSO[0][0];
		$perfil_acesso->select($banco->SEQ_PERFIL_ACESSO[0][0]);
		$vPerfis = $perfil_acesso->NOM_PERFIL_ACESSO;			 
	}else{	
		$vcont = 1;
		while ($rowTipoUsuario = pg_fetch_array($recurso_ti_x_perfil_acesso->database->result)){			 
			require_once 'include/PHP/class/class.perfil_acesso.php';
			$perfil_acesso = new perfil_acesso();
			$perfil_acesso->select($rowTipoUsuario["seq_perfil_acesso"]);
			$vPerfis .= $perfil_acesso->NOM_PERFIL_ACESSO;
			if($recurso_ti_x_perfil_acesso->database->rows > $vcont){
				$vPerfis .= ", ";
			}
			$vcont++;
		}
	}
	
	$pagina->LinhaCampoFormulario("Perfil de Acesso:", "right", "N", $vPerfis, "left", "id=".$pagina->GetIdTable());
	/*TODO: NOVO PERFIL ACESSO*/
	
	if($banco->SEQ_AREA_ATUACAO != ""){
		$area_atuacao->select($banco->SEQ_AREA_ATUACAO);
		$pagina->LinhaCampoFormulario("Área de atuação:", "right", "N", $area_atuacao->NOM_AREA_ATUACAO, "left", "id=".$pagina->GetIdTable());
	}else{
		$pagina->LinhaCampoFormulario("Área de atuação:", "right", "N", "", "left", "id=".$pagina->GetIdTable());
	}

	?>
	<script>
		function fMostra(id, idTab){
			document.getElementById("tabelaAlocacao").style.display = "none";
			document.getElementById("tabAlocacao").attributes["class"].value = "";

			document.getElementById("tabelaItemConfiguracao").style.display = "none";
			document.getElementById("tabItemConfiguracao").attributes["class"].value = "";

			document.getElementById(id).style.display = "block";
			document.getElementById(idTab).attributes["class"].value = "tabact";
		}
	</script>
	<?
	$aItemAba = Array(
			array("javascript: fMostra('tabelaAlocacao','tabAlocacao')", "tabact", "&nbsp;Alocação&nbsp;", "tabAlocacao"),
			array("javascript: fMostra('tabelaItemConfiguracao','tabItemConfiguracao')", "", "&nbsp;Responsabilidade&nbsp;", "tabItemConfiguracao")
 			     );
	$pagina->SetaItemAba($aItemAba);
	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");

	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAlocacao cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Sistemas em que está alocado", 2);

	$item_configuracao->selectAlocacao($v_NUM_MATRICULA_RECURSO, "2", $vNumPagina);
	if($item_configuracao->database->rows > 0){
		$tabela = array();
		$header = array();
		//$header[] = array("&nbsp;", "center", "5%", "header");
		$header[] = array("Sigla", "center", "20%", "header");
		$header[] = array("Nome", "center", "40%", "header");
		$header[] = array("Área", "center", "7%", "header");
		$header[] = array("Líder", "center", "20%", "header");
		$header[] = array("Horas", "center", "10%", "header");
		$tabela[] = $header;
		while ($row = pg_fetch_array($item_configuracao->database->result)){
			$header = array();

			//$valor = $pagina->BotaoAlteraGridPesquisa("Equipe_envolvidaAlteracao.php?v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_SEQ_ITEM_CONFIGURACAO=".$row["SEQ_ITEM_CONFIGURACAO"]."&v_NOM_ITEM_CONFIGURACAO=".$row["NOM_ITEM_CONFIGURACAO"]."&v_VAL_PERCENT_ALOCACAO=".$row["VAL_PERCENT_ALOCACAO"]);
			//$valor .= $pagina->BotaoExcluiGridPesquisa1("item_configuracaoAlocacao.php?v_SEQ_ITEM_CONFIGURACAO=".$row["SEQ_ITEM_CONFIGURACAO"]."&v_NUM_MATRICULA_RECURSO=".$empregados->GetNumeroMatricula($v_NUM_MATRICULA_RECURSO)."&flag=2");

			//$header[] = array($valor, "center", "", "");
			$header[] = array($row["sig_item_configuracao"], "center", "", "");
			$header[] = array($row["nom_item_configuracao"], "center", "", "");
			$header[] = array($unidade_organizacional->GetUorSigla($row["cod_uor_area_gestora"]), "center", "", "");
			$header[] = array($empregados->GetNomeEmpregado($row["num_matricula_lider"]), "center", "", "");
			$header[] = array($row["qtd_hora_alocada"], "right", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	}else{
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
	}
	$pagina->LinhaCampoFormularioColspanDestaque("Servidores em que está alocado", 2);
	$equipe_servidor->setNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
	$equipe_servidor->selectParam();
	if($equipe_servidor->database->rows > 0){
		$tabela = array();
		$header = array();
		$header[] = array("Servidor", "center", "60%", "header");
		$header[] = array("Local", "center", "20%", "header");
		$header[] = array("Prioridade", "center", "20%", "header");
		$tabela[] = $header;
		while ($row = pg_fetch_array($equipe_servidor->database->result)){
			$header = array();
			$servidor->select($row["seq_servidor"]);
			$header[] = array($servidor->NOM_SERVIDOR, "center", "", "");
			$header[] = array($servidor->DSC_LOCALIZACAO, "center", "", "");
			$header[] = array($row["num_ordem"], "center", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	}else{
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
	}

	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaItemConfiguracao style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");

	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Sistemas de informação sob sua responsabilidade", 2);

	$item_configuracao = new item_configuracao();
	$item_configuracao->setNUM_MATRICULA_LIDER($v_NUM_MATRICULA_RECURSO);
	$item_configuracao->selectParam($pagina->iif($vOrderBy == "", "SIG_ITEM_CONFIGURACAO", $vOrderBy), $vNumPagina);
	if($item_configuracao->database->rows > 0){
		$tabela = array();
		$header = array();
		$header[] = array("&nbsp;", "center", "3%", "header");
		$header[] = array("Gestor", "center", "20%", "header");
		$header[] = array("Sigla", "center", "15%", "header");
		$header[] = array("Nome", "center", "25%", "header");
		$header[] = array("Área", "center", "10%", "header");
		$tabela[] = $header;
		while ($row = pg_fetch_array($item_configuracao->database->result)){
			$header = array();
			$valor = $pagina->BotaoLupa("Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=".$row["seq_item_configuracao"], "Detalhes de ".$row["nom_item_configuracao"]);
			$header[] = array($valor, "center", "", "");
			$header[] = array($empregados->GetNomeEmpregado($row["num_matricula_gestor"]), "left", "", "");
			$header[] = array($row["sig_item_configuracao"], "left", "", "");
			$header[] = array($row["nom_item_configuracao"], "left", "", "");
			$header[] = array($unidade_organizacional->GetUorSigla($row["cod_uor_area_gestora"]), "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	}else{
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
	}

	$pagina->FechaTabelaPadrao();

	$pagina->MontaRodape();
}else{
	$pagina->mensagem("Selecione um profissional");
}

?>