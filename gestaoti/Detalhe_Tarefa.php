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
	
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Detalhes de Time Sheet"); // Indica o título do cabeçalho da página

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
	
	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_NUM_MATRICULA_RECURSO", $banco_tarefa->NUM_MATRICULA_RECURSO);
	
	
	
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais", 2);	
		
	// Buscar dados da tabela Tarefa
	$banco_tarefa->select($v_SEQ_TAREFA_TI);

	// Buscar dados da tabela recurso_ti
	$banco_recurso_ti->select($banco_tarefa->NUM_MATRICULA_RECURSO);
	
	// Buscar dados da tabela Perfil Recurso
	$banco_perfil_recurso_ti->select($banco_recurso_ti->SEQ_PERFIL_RECURSO_TI);	
	
	// Buscar dados do Lider	
	$banco_lider->select($banco_tarefa->NUM_MATRICULA_LIDER);	
	
	// Adicionar combo no formulário
	print $pagina->CampoHidden("v_SSEQ_TAREFA_TI", $banco_timesheet->SEQ_TAREFA_TI);
	$pagina->LinhaCampoFormulario("Matrícula:", "right", "N", $banco_tarefa->NUM_MATRICULA_RECURSO, "left", "id=".$pagina->GetIdTable(), "30%", "70%");
	$pagina->LinhaCampoFormulario("Nome do Recurso:", "right", "N", $banco_recurso_ti->NOME, "left", "id=".$pagina->GetIdTable(), "30%", "70%");	
	$pagina->LinhaCampoFormulario("Perfil:", "right", "N", $banco_perfil_recurso_ti->NOM_PERFIL_RECURSO_TI, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Departamento:", "right", "N", $banco_recurso_ti->DEP_SIGLA, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Email:", "right", "N", $banco_recurso_ti->DES_EMAIL, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("DDD:", "right", "N", $banco_recurso_ti->NUM_DDD, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Telefone:", "right", "N", $banco_recurso_ti->NUM_TELEFONE, "left", "id=".$pagina->GetIdTable(), "30%", "70%");
	
	$pagina->LinhaCampoFormularioColspanDestaque("Dados do Lider", 2);
	$pagina->LinhaCampoFormulario("Matrícula:", "right", "N", $banco_lider->NUM_MATRICULA_RECURSO, "left", "id=".$pagina->GetIdTable(), "30%", "70%");
	$pagina->LinhaCampoFormulario("Nome do Recurso:", "right", "N", $banco_lider->NOME, "left", "id=".$pagina->GetIdTable(), "30%", "70%");	
	$pagina->LinhaCampoFormulario("Perfil:", "right", "N", $banco_lider->NOM_PERFIL_RECURSO_TI, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Departamento:", "right", "N", $banco_lider->DEP_SIGLA, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Email:", "right", "N", $banco_lider->DES_EMAIL, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("DDD:", "right", "N", $banco_lider->NUM_DDD, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Telefone:", "right", "N", $banco_lider->NUM_TELEFONE, "left", "id=".$pagina->GetIdTable(), "30%", "70%");	
	$pagina->LinhaCampoFormularioColspanDestaque("Tarefa", 2);
	
	$pagina->LinhaCampoFormulario("Tarefa:", "right", "N", $banco_tarefa->SEQ_TAREFA_TI, "left", "id=".$pagina->GetIdTable(), "30%", "70%");			
	if ($banco_tarefa->SEQ_OS != "") {	
		$strDemanda = "Manutenção";
	} else {
		$strDemanda = "Projeto";
	}	
	$pagina->LinhaCampoFormulario("Tipo de Demanda:", "right", "N", $strDemanda, "left", "id=".$pagina->GetIdTable(), "30%", "70%");	
	switch ($banco_tarefa->SEQ_STATUS_TAREFA_TI) 
	{
		case 1:
			$strStatus = "Não Iniciado"; break;
		case 2:
			$strStatus = "Em Andamento"; break;
		case 3:
			$strStatus = "Parado"; break;
		case 4:
			$strStatus = "Encerrado"; break;
		default:
			$strStatus = "Não Iniciado"; break;
	}				
	$pagina->LinhaCampoFormulario("Status:", "right", "N", $strStatus, "left", "id=".$pagina->GetIdTable(), "30%", "70%");	
	$dataCriacao = substr($banco_tarefa->DAT_CRIACAO_TAREFA,8,2)."/".substr($banco_tarefa->DAT_CRIACAO_TAREFA,5,2)."/".substr($banco_tarefa->DAT_CRIACAO_TAREFA,0,4);
	$pagina->LinhaCampoFormulario("Data da Criação da Tarefa:", "right", "N", $dataCriacao, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Hora da Criação da Tarefa:", "right", "N", substr($banco_tarefa->DAT_CRIACAO_TAREFA,11), "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
	$pagina->LinhaCampoFormulario("Extras:", "right", "N", $banco_tarefa->QTD_HORA_TOTAL_UTEIS." minutos", "left", "id=".$pagina->GetIdTable(), "30%", "70%");
	if ($banco_tarefa->QTD_HORA_TOTAL_UTEIS != '0') {
		$pagina->LinhaCampoFormulario("Extras Aprovado:", "right", "N", $banco_tarefa->FLG_APROVA_EXTRA, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
		if ($banco_tarefa->FLG_APROVA_EXTRA == "S") {
			$pagina->LinhaCampoFormulario("Matriculo do Lider qua aprovou:", "right", "N", $banco_tarefa->NUM_MATRICULA_LIDER_APROVA, "left", "id=".$pagina->GetIdTable(), "30%", "70%");
			$dataAprovacao = substr($banco_tarefa->DAT_APROVACAO_EXTRA,8,2)."/".substr($banco_tarefa->DAT_APROVACAO_EXTRA,5,2)."/".substr($banco_tarefa->DAT_APROVACAO_EXTRA,0,4);
			$pagina->LinhaCampoFormulario("Data da Aprovação:", "right", "N", $dataAprovacao, "left", "id=".$pagina->GetIdTable(), "30%", "70%");		
		}			
	}		
		
	$pagina->FechaTabelaPadrao();
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape(); 
?>