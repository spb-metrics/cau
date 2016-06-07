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
$pagina = new Pagina();
$banco = new item_configuracao();

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

	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Sistema de Informação"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
		 			    array("#", "tabact", "Adicionar") );
	$pagina->SetaItemAba($aItemAba);
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
			x_CarregarComboEquipe(document.form.v_COD_DEPENDENCIA.value, retorno_CarregarComboEquipe);
		}
		// Retorno
		function retorno_CarregarComboEquipe(val) {
			fEncheComboBox(val, document.form.v_SEQ_EQUIPE_TI);
		}
	</script>
	<?
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "100%");

	$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais", 2);

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.tipo_item_configuracao.php';
	$tipo_item_configuracao = new tipo_item_configuracao();
	$aItemOption = Array();
	$tipo_item_configuracao->setSEQ_TIPO_ITEM_CONFIGURACAO(2);
	$tipo_item_configuracao->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($tipo_item_configuracao->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Tipo:", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_ITEM_CONFIGURACAO", "S", "Tipo", "N", $aItemOption), "left", "v_SEQ_TIPO_ITEM_CONFIGURACAO", "30%", "70%");

	$pagina->LinhaCampoFormulario("Sigla:", "right", "N", $pagina->CampoTexto("v_SIG_ITEM_CONFIGURACAO", "N", "", "30", "30", ""), "left");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO", "S", "Nome", "60", "60", ""), "left");

	// Montar a combo da tabela tipo_chamado
	require 'include/PHP/class/class.dependencias.php';
	$dependencias = new dependencias();

	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
//	$equipe_ti->setCOD_DEPENDENCIA($_SESSION["COD_DEPENDENCIA"]);

	$pagina->LinhaCampoFormulario("Equipe Responsável:", "right", "S",
							//	$pagina->CampoSelect("v_COD_DEPENDENCIA", "N", "Dependencia", "S", $dependencias->comboSimplesEquipe("", $_SESSION["COD_DEPENDENCIA"]), "Escolha", "do_CarregarComboEquipe()")."&nbsp;".
								$pagina->CampoSelect("v_SEQ_EQUIPE_TI", "S", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Escolha")
								, "left");

	$pagina->LinhaCampoFormulario("Matricula do Líder:", "right", "S",
								  $pagina->CampoTexto("v_NUM_MATRICULA_LIDER", "S", "Matrícula do Líder" , "10", "10", "", "readonly").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_LIDER", "TI")
								  , "left");

	$pagina->LinhaCampoFormulario("Matricula do Gestor:", "right", "S",
								  $pagina->CampoTexto("v_NUM_MATRICULA_GESTOR", "S", "Matrícula do Gestor" , "10", "10", "", "readonly").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_GESTOR", "")
								  , "left");

	$pagina->LinhaCampoFormulario("Unidade Organizacional Gestora:", "right", "S",
								  $pagina->CampoTexto("v_COD_UOR_AREA_GESTORA", "S", "Unidade Organizacional responsavel", "10", "10", "", "readonly").
								  $pagina->ButtonProcuraUorg("v_COD_UOR_AREA_GESTORA", "")
								  , "left");

	$pagina->LinhaCampoFormulario("Descrição:", "right", "S", $pagina->CampoTextArea("v_TXT_ITEM_CONFIGURACAO", "S", "Descrição", "59", "2", ""), "left");


	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.tipo_disponibilidade.php';
	$tipo_disponibilidade = new tipo_disponibilidade();
	$aItemOption = Array();

	$tipo_disponibilidade->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($tipo_disponibilidade->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_TIPO_DISPONIBILIDADE == $row[0],"Selected", ""), $row[1]);
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
		$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_PRIORIDADE == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Prioridade:", "right", "S", $pagina->CampoSelect("v_SEQ_PRIORIDADE", "S", "Prioridade", "S", $aItemOption), "left");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.criticidade.php';
	$criticidade = new criticidade();
	$aItemOption = Array();

	$criticidade->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($criticidade->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_CRITICIDADE == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Criticidade:", "right", "S", $pagina->CampoSelect("v_SEQ_CRITICIDADE", "S", "Criticidade", "S", $aItemOption), "left");


	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm()", " Próximo Passo > "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro

	//require 'include/PHP/class/class.empregados.php';
	require 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();

	//require 'include/PHP/class/class.unidades_organizacionais.php';
	//$unidades_organizacionais = new unidades_organizacionais();

	$banco->setSEQ_TIPO_ITEM_CONFIGURACAO($v_SEQ_TIPO_ITEM_CONFIGURACAO);
	$banco->setSEQ_SERVICO($v_SEQ_SERVICO);
	$banco->setNUM_MATRICULA_GESTOR($empregados->GetNumeroMatricula($v_NUM_MATRICULA_GESTOR));
	$banco->setNUM_MATRICULA_LIDER($empregados->GetNumeroMatricula($v_NUM_MATRICULA_LIDER));
	$banco->setSIG_ITEM_CONFIGURACAO($v_SIG_ITEM_CONFIGURACAO);
	$banco->setNOM_ITEM_CONFIGURACAO($v_NOM_ITEM_CONFIGURACAO);
	$banco->setCOD_UOR_AREA_GESTORA($v_COD_UOR_AREA_GESTORA);
	$banco->setTXT_ITEM_CONFIGURACAO($v_TXT_ITEM_CONFIGURACAO);
	$banco->setSEQ_TIPO_DISPONIBILIDADE($v_SEQ_TIPO_DISPONIBILIDADE);
	$banco->setSEQ_CRITICIDADE($v_SEQ_CRITICIDADE);
	$banco->setSEQ_PRIORIDADE($v_SEQ_PRIORIDADE);
	$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$banco->insert();

	// Código inserido: $banco->SEQ_ITEM_CONFIGURACAO
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Item_configuracaoAlteracao.php?v_SEQ_ITEM_CONFIGURACAO=$banco->SEQ_ITEM_CONFIGURACAO");
	}
}
?>
