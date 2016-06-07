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
require_once 'include/PHP/class/class.tarefa.php';
require_once 'include/PHP/class/class.time_sheet.php';
require_once 'include/PHP/class/class.time_sheet.php';
require_once 'include/PHP/class/class.recurso_ti.php';
require_once 'include/PHP/class/class.perfil_recurso_ti.php';

$pagina = new Pagina();
$banco_tarefa = new tarefa();
$banco_timesheet = new time_sheet();
$banco_recurso_ti = new recurso_ti();
$banco_lider = new recurso_ti();
$banco_perfil_recurso_ti = new perfil_recurso_ti();


	$pagina->setMethod("post");
	
	$v_GESTOR = $_REQUEST['GESTOR'];
	
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Detalhes de Time Sheet"); // Indica o t�tulo do cabe�alho da p�gina

	// Itens das abas
	if ($v_GESTOR == "TRUE" ) {
		$aItemAba = Array( array("Admin_TimeSheet.php", "", "Pesquisa"), array("TimeSheet.php?v_NUM_MATRICULA_RECURSO=".$v_NUM_MATRICULA_RECURSO."&GESTOR=TRUE", "", "Time Sheet"),
					   array("#", "tabact", "Detalhes")					   
						 );
	} else {
		$aItemAba = Array( array("TimeSheet.php?v_NUM_MATRICULA_RECURSO=".$v_NUM_MATRICULA_RECURSO."", "", "Time Sheet"),
					   array("#", "tabact", "Detalhes")					   
						 );	
	}						 
	$pagina->SetaItemAba($aItemAba);
	
	// Buscar dados da tabela Tarefa
	$banco_tarefa->select($v_SEQ_TAREFA_TI);
	
	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_NUM_MATRICULA_RECURSO", $banco_tarefa->NUM_MATRICULA_RECURSO);
	
	
	
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informa��es Gerais", 2);	
		
	// Buscar dados da tabela Tarefa
	$banco_tarefa->select($v_SEQ_TAREFA_TI);

	// Buscar dados da tabela recurso_ti
	$banco_recurso_ti->select($banco_tarefa->NUM_MATRICULA_RECURSO);
	
	// Buscar dados da tabela Perfil Recurso
	$banco_perfil_recurso_ti->select($banco_recurso_ti->SEQ_PERFIL_RECURSO_TI);	
	
	// Buscar dados do Lider	
	$banco_lider->select($banco_tarefa->NUM_MATRICULA_LIDER);	
	
	// Adicionar combo no formul�rio
	print $pagina->CampoHidden("v_SSEQ_TAREFA_TI", $banco_timesheet->SEQ_TAREFA_TI);
	$pagina->LinhaCampoFormulario("Matr�cula:", "right", "N", $banco_tarefa->NUM_MATRICULA_RECURSO, "left", "id=".$pagina->GetIdTable(), "30%", "70%");
	$pagina->LinhaCampoFormulario("Nome do Recurso:", "right", "N", $banco_recurso_ti->NOME, "left", "id=".$pagina->GetIdTable(), "30%", "70%");	
	$pagina->LinhaCampoFormulario("Perfil:", "right", "N", $banco_perfil_recurso_ti->NOM_PERFIL_RECURSO_TI, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Departamento:", "right", "N", $banco_recurso_ti->DEP_SIGLA, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Email:", "right", "N", $banco_recurso_ti->DES_EMAIL, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("DDD:", "right", "N", $banco_recurso_ti->NUM_DDD, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Telefone:", "right", "N", $banco_recurso_ti->NUM_TELEFONE, "left", "id=".$pagina->GetIdTable(), "30%", "70%");
	
	$pagina->LinhaCampoFormularioColspanDestaque("Dados do Lider", 2);
	$pagina->LinhaCampoFormulario("Matr�cula:", "right", "N", $banco_lider->NUM_MATRICULA_RECURSO, "left", "id=".$pagina->GetIdTable(), "30%", "70%");
	$pagina->LinhaCampoFormulario("Nome do Recurso:", "right", "N", $banco_lider->NOME, "left", "id=".$pagina->GetIdTable(), "30%", "70%");	
	$pagina->LinhaCampoFormulario("Perfil:", "right", "N", $banco_lider->NOM_PERFIL_RECURSO_TI, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Departamento:", "right", "N", $banco_lider->DEP_SIGLA, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Email:", "right", "N", $banco_lider->DES_EMAIL, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("DDD:", "right", "N", $banco_lider->NUM_DDD, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Telefone:", "right", "N", $banco_lider->NUM_TELEFONE, "left", "id=".$pagina->GetIdTable(), "30%", "70%");	
	$pagina->LinhaCampoFormularioColspanDestaque("Tarefa", 2);
	
	$pagina->LinhaCampoFormulario("Tarefa:", "right", "N", $banco_tarefa->SEQ_TAREFA_TI, "left", "id=".$pagina->GetIdTable(), "30%", "70%");			
	if ($banco_tarefa->SEQ_OS != "") {	
		$strDemanda = "Manuten��o";
	} else {
		$strDemanda = "Projeto";
	}	
	$pagina->LinhaCampoFormulario("Tipo de Demanda:", "right", "N", $strDemanda, "left", "id=".$pagina->GetIdTable(), "30%", "70%");	
	switch ($banco_tarefa->SEQ_STATUS_TAREFA_TI) 
	{
		case 1:
			$strStatus = "N�o Iniciado"; break;
		case 2:
			$strStatus = "Em Andamento"; break;
		case 3:
			$strStatus = "Parado"; break;
		case 4:
			$strStatus = "Encerrado"; break;
		default:
			$strStatus = "N�o Iniciado"; break;
	}				
	$pagina->LinhaCampoFormulario("Status:", "right", "N", $strStatus, "left", "id=".$pagina->GetIdTable(), "30%", "70%");	
	$dataCriacao = substr($banco_tarefa->DAT_CRIACAO_TAREFA,8,2)."/".substr($banco_tarefa->DAT_CRIACAO_TAREFA,5,2)."/".substr($banco_tarefa->DAT_CRIACAO_TAREFA,0,4);
	$pagina->LinhaCampoFormulario("Data da Cria��o da Tarefa:", "right", "N", $dataCriacao, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Hora da Cria��o da Tarefa:", "right", "N", substr($banco_tarefa->DAT_CRIACAO_TAREFA,11), "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Extras:", "right", "N", $banco_tarefa->QTD_HORA_TOTAL_UTEIS." minutos", "left", "id=".$pagina->GetIdTable(), "30%", "70%");
	if ($banco_tarefa->QTD_HORA_TOTAL_UTEIS != '0') {
		$pagina->LinhaCampoFormulario("Extras Aprovado:", "right", "N", $banco_tarefa->FLG_APROVA_EXTRA, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
		if ($banco_tarefa->FLG_APROVA_EXTRA == "S") {
			$pagina->LinhaCampoFormulario("Matriculo do Lider qua aprovou:", "right", "N", $banco_tarefa->NUM_MATRICULA_LIDER_APROVA, "left", "id=".$pagina->GetIdTable(), "30%", "70%");
			$dataAprovacao = substr($banco_tarefa->DAT_APROVACAO_EXTRA,8,2)."/".substr($banco_tarefa->DAT_APROVACAO_EXTRA,5,2)."/".substr($banco_tarefa->DAT_APROVACAO_EXTRA,0,4);
			$pagina->LinhaCampoFormulario("Data da Aprova��o:", "right", "N", $dataAprovacao, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
		}			
	}		
		
	$pagina->FechaTabelaPadrao();
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape(); 
?>