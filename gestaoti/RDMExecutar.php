<?php
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
require_once 'include/PHP/class/class.rdm.php';
require_once 'include/PHP/class/class.situacao_rdm.php';
require_once 'include/PHP/class/class.anexo_rdm.php';
require_once 'include/PHP/class/class.atividade_rdm.php';
require_once 'include/PHP/class/class.atividade_rb_rdm.php';
require_once 'include/PHP/class/class.util.php';
require_once 'include/PHP/class/class.equipe_ti.php';
require 'include/PHP/class/class.servidor.php';
require 'include/PHP/class/class.item_configuracao.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

$RDM = new rdm(); 
$situacaoRDM = new situacao_rdm();	

// ============================================================================================================
//EXECUCAO DAS REGRAS
// ============================================================================================================
$EXECUTADA = false;
if($v_SEQ_ID_ATM != "" && $v_ACAO_ATM != ""){	
$RDM = new rdm();
$situacaoRDM = new situacao_rdm();		 
$atividadeRDM = new atividade_rdm();
$atividadeRBRDM = new atividade_rb_rdm();
	if($v_ACAO_ATM == "EXECUTAR_ATM"){
		
		$atividadeRDM->select($v_SEQ_ID_ATM);
		
		if($atividadeRDM->getSITUACAO()!=$atividadeRDM->EM_EXECUCAO){
			$util = new util();
			$dataHoraAtual = $util->GetlocalTimeStamp();
			$atividadeRDM->setDATA_HORA_INICIO_EXECUCAO($dataHoraAtual);
			$atividadeRDM->updateExecucao($v_SEQ_ID_ATM);		 
			$RDM->setSITUACAO_ATUAL($situacaoRDM->EM_EXECUCAO);
			$RDM->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);
			$RDM->updateSituacao($v_SEQ_RDM);
			
			$situacaoRDM->setSEQ_RDM($v_SEQ_RDM);
			$situacaoRDM->setSITUACAO($situacaoRDM->EM_EXECUCAO);
			$situacaoRDM->setDATA_HORA($dataHoraAtual);	
			$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
			$situacaoRDM->insert();	
		}else{
			$vMsgErro="Atividade já em execução!";
		}
		
	}else if($v_ACAO_ATM == "PARAR_ATM"){
		$atividadeRDM->select($v_SEQ_ID_ATM);
		
		if($atividadeRDM->getSITUACAO()!=$atividadeRDM->PARADA){	
			if($atividadeRDM->getSITUACAO()==$atividadeRDM->EM_EXECUCAO){		
				$util = new util();
				$dataHoraAtual = $util->GetlocalTimeStamp();		 
				$atividadeRDM->updateParada($v_SEQ_ID_ATM);		 
				$RDM->setSITUACAO_ATUAL($situacaoRDM->PARADA);
				$RDM->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);
				$RDM->updateSituacao($v_SEQ_RDM);
				
				$situacaoRDM->setSEQ_RDM($v_SEQ_RDM);
				$situacaoRDM->setSITUACAO($situacaoRDM->PARADA);
				$situacaoRDM->setDATA_HORA($dataHoraAtual);	
				$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
				$situacaoRDM->insert();	
			}else{
				$vMsgErro="Atividade não está em execução!";
			}
		}else{
			$vMsgErro="Atividade já está parada!";
		}
	}else if($v_ACAO_ATM == "FINALIZAR_ATM"){
		$atividadeRDM->select($v_SEQ_ID_ATM);
		
		if($atividadeRDM->getSITUACAO()!=$atividadeRDM->FINALIZADA){
			if($atividadeRDM->getSITUACAO()==$atividadeRDM->EM_EXECUCAO){		
				$util = new util();
				$dataHoraAtual = $util->GetlocalTimeStamp();
				$atividadeRDM->setDATA_HORA_FIM_EXECUCAO($dataHoraAtual);
				$atividadeRDM->updateFinalizacao($v_SEQ_ID_ATM);
				
				$atividadeRDM->setSEQ_RDM($v_SEQ_RDM);
				$atividadeRDM->selectNaoFinalizadas("ORDEM");
				
				// se não existir, segnifica que todas as atividades foram finalizadas
				if($atividadeRDM->database->rows == 0){			 
					$RDM->setSITUACAO_ATUAL($situacaoRDM->EXECUTADA);
					$RDM->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);
					$RDM->updateSituacao($v_SEQ_RDM);
					$situacaoRDM->setSEQ_RDM($v_SEQ_RDM);
					$situacaoRDM->setSITUACAO($situacaoRDM->EXECUTADA);
					$situacaoRDM->setDATA_HORA($dataHoraAtual);	
					$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
					$situacaoRDM->insert();	
					$EXECUTADA =true;
					//$pagina->redirectTo("RDMValidacao.php?v_SEQ_RDM=".$v_SEQ_RDM);
				}
				
			}else{
				$vMsgErro="Atividade não está em execução!";
			}
			
		}else{
			$vMsgErro="Atividade já foi finalizada!";
		}
	}else if($v_ACAO_ATM == "SUSPENDER_ATM"){
		$atividadeRDM->select($v_SEQ_ID_ATM);
		
		if($atividadeRDM->getSITUACAO()!=$atividadeRDM->SUSPENSA){
			$util = new util();
			$dataHoraAtual = $util->GetlocalTimeStamp();		 
			$atividadeRDM->updateSuspensa($v_SEQ_ID_ATM);		 
			$RDM->setSITUACAO_ATUAL($situacaoRDM->SUSPENSA);
			$RDM->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);
			$RDM->updateSituacao($v_SEQ_RDM);
			
			$situacaoRDM->setSEQ_RDM($v_SEQ_RDM);
			$situacaoRDM->setSITUACAO($situacaoRDM->SUSPENSA);
			$situacaoRDM->setDATA_HORA($dataHoraAtual);	
			$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
			$situacaoRDM->insert();	

			 
		}else{
			$vMsgErro="Atividade está suspensa!";
		}
	}else if($v_ACAO_ATM == "FALHA_NA_EXECUCAO_ATM"){
		$atividadeRDM->select($v_SEQ_ID_ATM);
		
		if($atividadeRDM->getSITUACAO()!=$atividadeRDM->FALHA_NA_EXECUCAO){
			if($atividadeRDM->getSITUACAO()==$atividadeRDM->EM_EXECUCAO){
				$util = new util();
				$dataHoraAtual = $util->GetlocalTimeStamp();
				$atividadeRDM->setDATA_HORA_FIM_EXECUCAO($dataHoraAtual);
				$atividadeRDM->updateFalhaExecucao($v_SEQ_ID_ATM);		 
				$RDM->setSITUACAO_ATUAL($situacaoRDM->FALHA_NA_EXECUCAO);
				$RDM->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);
				$RDM->updateSituacao($v_SEQ_RDM);
				
				$situacaoRDM->setSEQ_RDM($v_SEQ_RDM);
				$situacaoRDM->setSITUACAO($situacaoRDM->FALHA_NA_EXECUCAO);
				$situacaoRDM->setDATA_HORA($dataHoraAtual);	
				$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
				$situacaoRDM->insert();	
			}else{
				$vMsgErro="Atividade não está em execução!";
			}
		}else{
			$vMsgErro="Atividade já foi finalizada com  falha!";
		}
	}
	
	
}	

if($EXECUTADA){
	// Enviar e-mail para o solicitante
	$RDMEmail = new rdm();
	$RDMEmail->setSEQ_RDM($v_SEQ_RDM);
	$RDMEmail->select($v_SEQ_RDM); 
	require_once 'include/PHP/class/class.rdm_email.php';
	
	$rdm_email = new rdm_email($pagina,$RDMEmail);
	$rdm_email->sendEmailRDMValidacao();	
	$pagina->redirectTo("RDMValidacao.php?v_SEQ_RDM=".$v_SEQ_RDM);	
}
					

// ============================================================================================================
// CAREGAR A TELA COM OS DADOS DA RDM
// ============================================================================================================
	


$pagina->SettituloCabecalho("Execução da RDM"); // Indica o título do cabeçalho da página


// pesquisa
$RDM->select($v_SEQ_RDM);
	
// Itens das abas
$aItemAba = Array();
$aItemAba[] = array("RDMPesquisar.php?".$vLink."&vNumPagina=$vNumPagina", "", "Pesquisar");
$aItemAba[] = array("RDMDetalhe.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Detalhes");

if(($RDM->NUM_MATRICULA_SOLICITANTE == $_SESSION["NUM_MATRICULA_RECURSO"])&&
	($RDM->getSITUACAO_ATUAL()==$situacaoRDM->CRIADA || 
	 $RDM->getSITUACAO_ATUAL()==$situacaoRDM->REPROVADA || 
	 $RDM->getSITUACAO_ATUAL()==$situacaoRDM->PLANEJAMENTO_REPROVADO)){
	$aItemAba[] = array("RDMAlteracao.php?v_SEQ_RDM=".$v_SEQ_RDM."&v_ACAO=ALTERAR", "", "Alterar");
}

$APROVAR = false;
if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->AGUARDANDO_APROVACAO)){
	if($RDM->getTIPO() == $RDM->NORMAL){
		if($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isCoordenadorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGerenteDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"])){
			$APROVAR = true;
		}
	}else if($RDM->getTIPO() == $RDM->EMERGENCIAL){
		if($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isCoordenadorTI($_SESSION["SEQ_PERFIL_ACESSO"])){
			$APROVAR = true;
		}
	}
}
if($APROVAR){
	$aItemAba[] = array("RDMAprovacao.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Aprovar/Reprovar");	 
}	


if(($RDM->NUM_MATRICULA_SOLICITANTE == $_SESSION["NUM_MATRICULA_RECURSO"])&& 
	($RDM->getSITUACAO_ATUAL()==$situacaoRDM->CRIADA)){	
	$aItemAba[] = array("RDMCancelamento.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Cancelar");
}else if ($RDM->getSITUACAO_ATUAL()==$situacaoRDM->AGUARDANDO_APROVACAO){
	if($RDM->getTIPO() == $RDM->EMERGENCIAL){
		if(($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isCoordenadorTI($_SESSION["SEQ_PERFIL_ACESSO"]))){	
			$aItemAba[] = array("RDMCancelamento.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Cancelar");
		}
	}else if($RDM->getTIPO() == $RDM->NORMAL){
		if(($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isCoordenadorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGerenteDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"]))){	
			$aItemAba[] = array("RDMCancelamento.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Cancelar");
		}
	}
}else if ( ($RDM->getSITUACAO_ATUAL()==$situacaoRDM->APROVADA)&& 
			($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGerenteDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"])) ){
			$aItemAba[] = array("RDMCancelamento.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Cancelar");
}


$PLANEJAR = false; 
if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->APROVADA) || 
	($RDM->getSITUACAO_ATUAL()==$situacaoRDM->AGUARADANDO_EXECUCAO)){		
	if($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
		$pagina->isGerenteDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"])){
		$PLANEJAR = true;
	}		
}	
if($PLANEJAR){
	$aItemAba[] = array("RDMPlanejamento.php?v_SEQ_RDM=".$v_SEQ_RDM."&v_ACAO=PLANEJAR", "", "Planejar"); 
}		

if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->AGUARADANDO_EXECUCAO ||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->EM_EXECUCAO||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->PARADA ||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->SUSPENSA  ||
	$RDM->getSITUACAO_ATUAL()==$situacaoRDM->FALHA_NA_VALIDACAO) &&
	($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"]) ||
	 $pagina->isExecutorDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"]))){ 
		$aItemAba[] = array("#" , "tabact", "Executar");
}

if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->EXECUTADA) &&
	($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"]) ||
	 $pagina->isExecutorDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"]))){			
		$aItemAba[] = array("RDMValidacao.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Validar");
}
	 
//$aItemAba[] = array("RDMAnexos.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Anexos");				 
$pagina->SetaItemAba($aItemAba); 

// Inicio do formulário
$pagina->MontaCabecalho();


?>
<script language="javascript">

function verifica(operacao) { 

	 
	var valor = 0; 
	//alert(document.form.elements.length);
	for(i = 0; i < document.form.elements.length; i++) {
		if(document.form.elements[i].type == "radio"){
			if (document.form.elements[i].name == "v_SEQ_ID_ATM") {                       
				if(document.form.elements[i].checked){
					valor++;
				}				                        
			}                
		}        
	}

	if(valor==0) { 
		alert("Selecione alguma atividade") 
		return false; 
	} else { 
		document.form.v_ACAO_ATM.value= operacao;
		if('SUSPENDER_ATM' == operacao){
			//document.form.v_ACAO_ATM.value= '';
			document.form.action ='RDMSuspender.php';
		}else if('FALHA_NA_EXECUCAO_ATM' == operacao){
			//document.form.v_ACAO_ATM.value= '';
			document.form.action ='RDMFalhaExecucao.php';
		}
		
		document.form.submit(); 
	} 

} 

</script>
<?		


// ============================================================================================================
// FORMULARIO DE ATIVIDADES DA RDM
// ============================================================================================================

if($v_SEQ_RDM != ""){	
	$RDM = new rdm();
	$situacaoRDM = new situacao_rdm();		 
	$atividadeRDM = new atividade_rdm();
	$atividadeRBRDM = new atividade_rb_rdm();
	// pesquisa
	$RDM->select($v_SEQ_RDM);
	
	// Inicio do grid de atividades
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("&nbsp;", "2%");
	$header[] = array("Ordem", "");
	$header[] = array("Item", "");
	$header[] = array("Descrição", "");
	$header[] = array("Prevista execução", "");
	$header[] = array("Início execução", "");
	$header[] = array("Fim execução", "");
	$header[] = array("Responsável", "");
	//$header[] = array("Profissional", "");
	$header[] = array("Situação", "");
	
	$atividadeRDM->setSEQ_RDM($RDM->SEQ_RDM);
	$atividadeRDM->selectParam("ORDEM");
	
	if($atividadeRDM->database->rows == 0){	
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro de atividade de RDM informado", count($header));
	}else{
		 
		
		$corpo = array();
		//$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Atividades da RDM", $header);
		$pendenteExecucao = false;
		$SituacaoAtividadesAnterior ="";
		
		 while ($row = pg_fetch_array($atividadeRDM->database->result)){
			$valor ="";
			
		 	if(($row["situacao"] != $atividadeRDM->FINALIZADA)&&
		 		($row["situacao"] != $atividadeRDM->FALHA_NA_EXECUCAO)&&
		 		($row["num_matricula_recurso"] ==$_SESSION["NUM_MATRICULA_RECURSO"])&&
		 		($RDM->getSITUACAO_ATUAL()!= $situacaoRDM->FALHA_NA_EXECUCAO) &&
		 		($RDM->getSITUACAO_ATUAL()!= $situacaoRDM->FINALIZADA)
		 	){
//		 		if($SituacaoAtividadesAnterior == $atividadeRDM->FINALIZADA ||		
//		 		   $SituacaoAtividadesAnterior == ""){
		 			$valor = "<INPUT TYPE=RADIO NAME=\"v_SEQ_ID_ATM\" VALUE=\"";
		 			$valor .=$row["seq_atividade_rdm"]."\"";
		 			$pendenteExecucao = true;
		 	//	}
		 			
		 	}
		  
			
			//$valor.="<a href=\"#\" onclick=\"fExcluirATM(".$_SESSION['ATIVIDADES_RDM'][$i][0].");\"><img src=\"imagens/excluir.gif\" alt=\"Excluir\" border=\"0\"></a>";
		
			$corpo[] = array("left", "campo", $valor);
			$corpo[] = array("center", "campo", $row["ordem"]);
			
			if($row["seq_servidor"]!=""){
				// Servidores						
				$servidor = new servidor();	
				$servidor->select($row["seq_servidor"]);
				 
				$corpo[] = array("left", "campo",  $servidor->NOM_SERVIDOR);
			}else if($row["seq_item_configuracao"]!=""){
				// Sistemas de informação				
				$sistemas = new item_configuracao();				 
				$sistemas->select($row["seq_item_configuracao"]);				 
				 
				$corpo[] = array("left", "campo", $sistemas->NOM_ITEM_CONFIGURACAO);
				 
			} 	 			 
			
			$corpo[] = array("left", "campo", $row["descricao"]);
			$corpo[] = array("left", "campo", $row["data_hora_prevista_execucao"]);
			$corpo[] = array("left", "campo", $row["data_hora_inicio_execucao"]);
			$corpo[] = array("left", "campo", $row["data_hora_fim_execucao"]);
			
			$executor= new equipe_ti();		 
			$executor->select($row["seq_equipe_ti"]);
			//$corpo[] = array("left", "campo", $executor->NOM_EQUIPE_TI);
			
			$empregados = new empregados();
			$empregados->select($row["num_matricula_recurso"]);
			
			//$corpo[] = array("left", "campo", $empregados->NOME);
			
			$corpo[] = array("left", "campo", $executor->NOM_EQUIPE_TI."<br>".$empregados->NOME);
			
			$corpo[] = array("left", "campo", $atividadeRDM->getDescricaoSituacaoAtividade($row["situacao"]));
			
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
			
			//$SituacaoAtividadesAnterior = $row["situacao"];
		}
		
		$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		$valor ="";
		if($pendenteExecucao){
			//$valor .="<div";
			$valor .="<input type=\"button\" id=\"campo_texto\"  style=\"cursor:hand;width:12%;\"  value=\"Executar\" alt=\"Executar\" border=\"0\" onClick=\"verifica('EXECUTAR_ATM');\" \>";
			
		 	$valor .="&nbsp;&nbsp;<input type=\"button\" id=\"campo_texto\"  style=\"cursor:hand;width:12%;\"  value=\"Parar\" hint=\"Parar\" border=\"0\" onClick=\"verifica('PARAR_ATM');\" />";
		 	
		 	$valor .="&nbsp;&nbsp;<input type=\"button\" id=\"campo_texto\"  style=\"cursor:hand;width:12%;\"  value=\"Suspender\" alt=\"Suspender\" border=\"0\" onClick=\"verifica('SUSPENDER_ATM');\" />";
		 	
		 	$valor .="&nbsp;&nbsp;<input type=\"button\" id=\"campo_texto\"  style=\"cursor:hand;width:12%;\"  value=\"Finalizar\" alt=\"Finalizar\" border=\"0\" onClick=\"verifica('FINALIZAR_ATM');\" />";
		 	
		 	$valor .="&nbsp;&nbsp;<input type=\"button\" id=\"campo_texto\"  style=\"cursor:hand;width:12%;\"  value=\"Falha na execução\"  alt=\"Falha na execução\" border=\"0\" onClick=\"verifica('FALHA_NA_EXECUCAO_ATM');\" />";
		 	$valor.="&nbsp;&nbsp;&nbsp;";
		}else{
			$valor ="<input type=\"button\" id=\"campo_texto\"  style=\"cursor:hand;width:12%;\"  value=\"Executar\"  alt=\"Executar\" border=\"0\" onClick=\"alert('Não existem atividades habilitadas');\" \>";
			
		 	$valor .="&nbsp;&nbsp;<input type=\"button\" id=\"campo_texto\"  style=\"cursor:hand;width:12%;\"  value=\"Parar\" alt=\"Parar\" border=\"0\" onClick=\"alert('Não existem atividades habilitadas');\" />";
		 	
		 	$valor .="&nbsp;&nbsp;<input type=\"button\" id=\"campo_texto\"  style=\"cursor:hand;width:12%;\"  value=\"Suspender\" alt=\"Suspender\" border=\"0\" onClick=\"alert('Não existem atividades habilitadas');\" />";
		 	
		 	$valor .="&nbsp;&nbsp;<input type=\"button\" id=\"campo_texto\"  style=\"cursor:hand;width:12%;\"  value=\"Finalizar\"  alt=\"Finalizar\" border=\"0\" onClick=\"alert('Não existem atividades habilitadas');\" />";
		 	
		 	$valor .="&nbsp;&nbsp;<input type=\"button\" id=\"campo_texto\"  style=\"cursor:hand;width:12%;\"  value=\"Falha na execução\" alt=\"Falha na execução\" border=\"0\" onClick=\"alert('Não existem atividades habilitadas');\" />";
		 	$valor.="&nbsp;&nbsp;&nbsp;";
		}
		$pagina->LinhaCampoFormularioColspanDestaqueCenter($valor, 2);		
		$pagina->FechaTabelaPadrao();
	} 
	
	$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");

}

if($vMsgErro!=""){
	$pagina->ScriptAlert($vMsgErro);
} 
 	
print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_RDM", $v_SEQ_RDM);
print $pagina->CampoHidden("v_ACAO", $v_ACAO);
print $pagina->CampoHidden("v_ACAO_ATM", $v_ACAO_ATM);
//print $pagina->CampoHidden("v_SEQ_ID_ATM", $v_SEQ_ID_ATM);  
$pagina->MontaRodape();