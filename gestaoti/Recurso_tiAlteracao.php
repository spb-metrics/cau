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
require_once 'include/PHP/class/class.recurso_ti.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
$pagina = new Pagina();
$banco = new recurso_ti();
$empregados = new empregados();
$pagina->ForcaAutenticacao();
// Seguran�a
if(!$pagina->segurancaPerfilAlteracao($_SESSION["SEQ_PERFIL_ACESSO"])){
	$pagina->redirectTo("Recurso_tiPesquisa.php");
}

$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterar Colaborador"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Recurso_tiPesquisa.php", "", "Pesquisa"),
					   array("Recurso_tiCadastro.php", "", "Adicionar"),
		 			   array("#", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_NUM_MATRICULA_RECURSO);

	// Inicio do formul�rio
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	print $pagina->CampoHidden("v_NUM_MATRICULA_RECURSO", $banco->NUM_MATRICULA_RECURSO);

	$pagina->LinhaCampoFormulario("Nome:", "right", "N", $banco->NOME, "left");
	$pagina->LinhaCampoFormulario("Login:", "right", "N", $banco->NOM_LOGIN_REDE, "left");
	$pagina->LinhaCampoFormulario("Depend�ncia:", "right", "N", $banco->DEP_SIGLA, "left");
	$pagina->LinhaCampoFormulario("Lota��o:", "right", "N", $banco->UOR_SIGLA, "left");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.perfil_recurso_ti.php';
	$perfil_recurso_ti = new perfil_recurso_ti();
	// Se for administrador tem acesso completo
	if($_SESSION["SEQ_PERFIL_ACESSO"] != $perfil_recurso_ti->SEQ_PERFIL_ACESSO_ADMINISTRADOR){
		$perfil_recurso_ti->setSEQ_PERFIL_RECURSO_TI_REMOVER($perfil_recurso_ti->SEQ_PERFIL_ACESSO_ADMINISTRADOR);
	}
	$aItemOption = Array();

	$perfil_recurso_ti->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($perfil_recurso_ti->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($banco->SEQ_PERFIL_RECURSO_TI == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formul�rio
	$pagina->LinhaCampoFormulario("Cargo/Fun��o:", "right", "S", $pagina->CampoSelect("v_SEQ_PERFIL_RECURSO_TI", "S", "Perfil recurso ti", "S", $aItemOption), "left");

	// Buscar dados da tabela externa
	/*
	require_once 'include/PHP/class/class.perfil_acesso.php';
	$perfil_acesso = new perfil_acesso();
	if($_SESSION["SEQ_PERFIL_ACESSO"] != $perfil_acesso->SEQ_PERFIL_ACESSO_ADMINISTRADOR){
		$perfil_acesso->setSEQ_PERFIL_ACESSO_REMOVER($perfil_acesso->SEQ_PERFIL_ACESSO_ADMINISTRADOR);
	}
	$aItemOption = Array();

	$perfil_acesso->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($perfil_acesso->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($banco->SEQ_PERFIL_ACESSO == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formul�rio
	$pagina->LinhaCampoFormulario("Perfil:", "right", "S", $pagina->CampoSelect("v_SEQ_PERFIL_ACESSO", "S", "Perfil acesso", "S", $aItemOption), "left");
	*/
	
	/*TODO: NOVO PERFIL ACESSO*/
	// ================================================
	require_once 'include/PHP/class/class.perfil_acesso.php';
	$perfil_acesso = new perfil_acesso();
	$i = 0;
	// Se for administrador tem acesso completo
//	if($_SESSION["SEQ_PERFIL_ACESSO"] != $perfil_acesso->SEQ_PERFIL_ACESSO_ADMINISTRADOR){
//		$perfil_acesso->setSEQ_PERFIL_ACESSO_REMOVER($perfil_acesso->SEQ_PERFIL_ACESSO_ADMINISTRADOR);
//	}

	if(!$pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])){
		$perfil_acesso->setSEQ_PERFIL_ACESSO_REMOVER($perfil_acesso->SEQ_PERFIL_ACESSO_ADMINISTRADOR);
	}
	$perfil_acesso->selectParam("2");
	
	require_once 'include/PHP/class/class.recurso_ti_x_perfil_acesso.php';
	$recurso_ti_x_perfil_acesso = new recurso_ti_x_perfil_acesso();
	$recurso_ti_x_perfil_acesso->setNUM_MATRICULA_RECURSO($banco->NUM_MATRICULA_RECURSO);
	$recurso_ti_x_perfil_acesso->selectParam();
	
	$aItemOption = Array();
	
	//Verificando se j� foi realizada a migra��o
	if($recurso_ti_x_perfil_acesso->database->rows == 0){
		//echo("migracao");
		while ($rowTipoUsuario = pg_fetch_array($perfil_acesso->database->result)){
			
			$aItemOption[$i][0] = $rowTipoUsuario["seq_perfil_acesso"]; 
			 
			if($rowTipoUsuario["seq_perfil_acesso"] != $banco->SEQ_PERFIL_ACESSO[0]){
				$aItemOption[$i][1] = "";
			}else{
				$aItemOption[$i][1] = "checked";
			}
			$aItemOption[$i][2] = $rowTipoUsuario["nom_perfil_acesso"];
			$i++;
		}
		
	}else{
		//echo("nova parte");
		while ($rowTipoUsuario = pg_fetch_array($perfil_acesso->database->result)){
			$aItemOption[$i][0] = $rowTipoUsuario["seq_perfil_acesso"];
			require_once 'include/PHP/class/class.menu_perfil_acesso.php';
			$recurso_ti_x_perfil_acesso->setSEQ_PERFIL_ACESSO($rowTipoUsuario["seq_perfil_acesso"]);
			$recurso_ti_x_perfil_acesso->setNUM_MATRICULA_RECURSO($banco->NUM_MATRICULA_RECURSO);
			$recurso_ti_x_perfil_acesso->selectParam();
			if($recurso_ti_x_perfil_acesso->database->rows == 0){
				$aItemOption[$i][1] = "";
			}else{
				$aItemOption[$i][1] = "checked";
			}
			$aItemOption[$i][2] = $rowTipoUsuario["nom_perfil_acesso"];
			$i++;
		}
	}
	
	

	$pagina->LinhaCampoFormulario("Perfil:", "right", "N", $pagina->CampoCheckbox($aItemOption, "acesso[]"), "left");
	
	print $pagina->CampoHidden("v_SEQ_PERFIL_ACESSO", $banco->SEQ_PERFIL_ACESSO);
	// ================================================
	/*TODO: NOVO PERFIL ACESSO*/
	
	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.area_atuacao.php';
	$area_atuacao = new area_atuacao();
	$aItemOption = Array();

	$area_atuacao->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($area_atuacao->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($banco->SEQ_AREA_ATUACAO == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formul�rio
	$pagina->LinhaCampoFormulario("�rea de Atua��o:", "right", "S", $pagina->CampoSelect("v_SEQ_AREA_ATUACAO", "S", "Perfil acesso", "S", $aItemOption), "left");
	// Montar a combo
	require 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$equipe_ti->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	/*TODO: NOVO PERFIL ACESSO*/
//	if($_SESSION["SEQ_PERFIL_ACESSO"] != $perfil_acesso->SEQ_PERFIL_ACESSO_ADMINISTRADOR){
//		$equipe_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_PERFIL_ACESSO"]);
//	}
	if(!$pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])){
		$equipe_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_PERFIL_ACESSO"]);
	}
	/*TODO: NOVO PERFIL ACESSO*/
	$pagina->LinhaCampoFormulario("Equipe:", "right", "S", $pagina->CampoSelect("v_SEQ_EQUIPE_TI", "S", "Equipe", "S", $equipe_ti->combo(2, $banco->SEQ_EQUIPE_TI)), "left", "v_SEQ_EQUIPE_TI", "30%", "70%");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Alterar regstro
	$banco->setSEQ_PERFIL_RECURSO_TI($v_SEQ_PERFIL_RECURSO_TI);
	//$banco->setSEQ_PERFIL_ACESSO($v_SEQ_PERFIL_ACESSO);
	$banco->setSEQ_PERFIL_ACESSO(1);
	$banco->setSEQ_AREA_ATUACAO($v_SEQ_AREA_ATUACAO);
	$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$banco->update($v_NUM_MATRICULA_RECURSO);
	if($banco->database->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		// Incluir o perfil de acesso
		require_once 'include/PHP/class/class.recurso_ti_x_perfil_acesso.php';
		$recurso_ti_x_perfil_acesso = new recurso_ti_x_perfil_acesso();
		$recurso_ti_x_perfil_acesso->deleteByNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
	    for ($i = 0; $i < count($acesso); $i++){
			$recurso_ti_x_perfil_acesso->setNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
			$recurso_ti_x_perfil_acesso->setSEQ_PERFIL_ACESSO($acesso[$i]);
			$recurso_ti_x_perfil_acesso->insert();
	    }
				
		$pagina->redirectTo("Recurso_tiPesquisa.php?v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&flag=1");
	}
}
?>
