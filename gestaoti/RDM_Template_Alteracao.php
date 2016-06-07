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
$pagina = new Pagina();
$pagina->ForcaAutenticacao();


// ============================================================================================================
// METODOS
// ============================================================================================================
function iif($Condicao, $Sim, $Nao){
		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
}  
// ============================================================================================================
// REALIZAR CADASTRO DO TEMPLATE DA RDM
// ============================================================================================================
if($flag == ""){
	require_once 'include/PHP/class/class.rdm_template.php';
	require_once 'include/PHP/class/class.atividade_rdm_template.php';			 
	require_once 'include/PHP/class/class.atividade_rb_rdm_template.php';
	require_once 'include/PHP/class/class.equipe_ti.php';
	require_once 'include/PHP/class/class.servidor.php';
	require_once 'include/PHP/class/class.item_configuracao.php';
	
	$RDM_TEMPLATE = new rdm_template();
	$RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($seq_rdm_template);
	$RDM_TEMPLATE->select($seq_rdm_template);
	
	$v_TITULO = $RDM_TEMPLATE->TITULO;
	$v_JUSTIFICATIVA = $RDM_TEMPLATE->JUSTIFICATIVA;
	$v_IMPACTO_NAO_EXECUTAR = $RDM_TEMPLATE->IMPACTO_NAO_EXECUTAR;
	$v_OBSERVACAO = $RDM_TEMPLATE->OBSERVACAO;
	$v_NOME_RESP_CHECKLIST = $RDM_TEMPLATE->NOME_RESP_CHECKLIST;
	$v_EMAIL_REP_CHECKLIST = $RDM_TEMPLATE->EMAIL_RESP_CHECKLIST;
	$v_DDD_TELEFONE_RESP_CHECKLIST = $RDM_TEMPLATE->DDD_TELEFONE_RESP_CHECKLIST;
	$v_NUMERO_TELEFONE_RESP_CHECKLIST = $RDM_TEMPLATE->NUMERO_TELEFONE_RESP_CHECKLIST;
	 
	
	//ATIVIDADES DA RDM
 	$_SESSION['ID_ATIVIDADE_RDM'] = 0;
 	$_SESSION['ATIVIDADES_RDM'] = array();
 	
	$ATIVIDADE_RDM_TEMPLATE = new atividade_rdm_template();
	$ATIVIDADE_RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($seq_rdm_template);
	$ATIVIDADE_RDM_TEMPLATE->selectParam("ORDEM");
	
	if($ATIVIDADE_RDM_TEMPLATE->database->rows != 0){		 

		while ($row = pg_fetch_array($ATIVIDADE_RDM_TEMPLATE->database->result)){		 
		 
			$v_DESCRICAO_ATIVIDADE_ATM = $row["descricao"];
			
			if($row["seq_servidor"]!=""){
				// Servidores						
				$servidor = new servidor();	
				$servidor->select($row["seq_servidor"]);
					
				$v_SEQ_ITEM_CONFIGURACAO_ATM = $row["seq_servidor"];
				$v_NOM_ITEM_CONFIGURACAO_ATM = $servidor->NOM_SERVIDOR;
				$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = 1;
				
			}else if($row["seq_item_configuracao"]!=""){
				// Sistemas de informação				
				$sistemas = new item_configuracao();				 
				$sistemas->select($row["seq_item_configuracao"]);				 
				
				$v_SEQ_ITEM_CONFIGURACAO_ATM = $row["seq_item_configuracao"];
				$v_NOM_ITEM_CONFIGURACAO_ATM = $sistemas->NOM_ITEM_CONFIGURACAO;
				$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = 2;
			} 	 

			$v_SEQ_EQUIPE_TI_ATM = $row["seq_equipe_ti"];
		
			  
			$_SESSION['ATIVIDADES_RDM'][] = array($_SESSION['ID_ATIVIDADE_RDM']++,$v_SEQ_ITEM_CONFIGURACAO_ATM ,
					$v_NOM_ITEM_CONFIGURACAO_ATM,$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM,$v_DESCRICAO_ATIVIDADE_ATM,$v_SEQ_EQUIPE_TI_ATM);
					
		} 
		 
		$v_SEQ_ITEM_CONFIGURACAO_ATM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = "";
		$v_DESCRICAO_ATIVIDADE_ATM = "";		 
		$v_SEQ_EQUIPE_TI_ATM = "";					
		 
	} 
	
	//ATIVIDADES DE ROLLBACK DA RDM
	$_SESSION['ID_ATIVIDADE_RB_RDM'] = 0;
 	$_SESSION['ATIVIDADES_RB_RDM'] = array();
 	
 	$ATIVIDADE_RB_RDM_TEMPLATE = new atividade_rb_rdm_template();
	$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($seq_rdm_template);	 
	$ATIVIDADE_RB_RDM_TEMPLATE->selectParam("ORDEM");
	
	if(!$ATIVIDADE_RB_RDM_TEMPLATE->database->rows == 0){				 

		while ($row = pg_fetch_array($ATIVIDADE_RB_RDM_TEMPLATE->database->result)){			  
			
			$v_DESCRICAO_ATIVIDADE_ATRBM = $row["descricao"];
			$v_ORDEM_ATRBM = $row["ordem"];
			
			if($row["seq_servidor"]!=""){
				// Servidores				 
				$servidor = new servidor();	
				$servidor->select($row["seq_servidor"]);				 
									
				$v_SEQ_ITEM_CONFIGURACAO_ATRBM = $row["seq_servidor"];
				$v_NOM_ITEM_CONFIGURACAO_ATRBM = $servidor->NOM_SERVIDOR;
				$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = 1;
				
			}else if($row["seq_item_configuracao"]!=""){
				// Sistemas de informação				
				$sistemas = new item_configuracao();				 
				$sistemas->select($row["seq_item_configuracao"]);				 			
				
				$v_SEQ_ITEM_CONFIGURACAO_ATRBM = $row["seq_item_configuracao"];
				$v_NOM_ITEM_CONFIGURACAO_ATRBM = $sistemas->NOM_ITEM_CONFIGURACAO;
				$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = 2;
			} 
			 
			$v_SEQ_EQUIPE_TI_ATRBM = $row["seq_equipe_ti"];	
			
			$_SESSION['ATIVIDADES_RB_RDM'][] = array($_SESSION['ID_ATIVIDADE_RB_RDM']++,$v_SEQ_ITEM_CONFIGURACAO_ATRBM ,
					$v_NOM_ITEM_CONFIGURACAO_ATRBM,$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM,$v_DESCRICAO_ATIVIDADE_ATRBM,$v_ORDEM_ATRBM,$v_SEQ_EQUIPE_TI_ATRBM);
		
		}  
	 
		$v_SEQ_ITEM_CONFIGURACAO_ATRBM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATRBM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = "";
		$v_DESCRICAO_ATIVIDADE_ATRBM = "";
		$v_ORDEM_ATRBM = "";		 
		$v_SEQ_EQUIPE_TI_ATRBM = "";	 
	}
}
if($v_ACAO=="ALTERAR"){
	$vMsgErro = "";
	
	$vRegrasVioladas = false;
	
	if(count($_SESSION['ATIVIDADES_RDM'])==0){
		$vRegrasVioladas = true;
		$vMsgErro = "Informe pelo menos uma atividade para RDM!";
	}else if(count($_SESSION['ATIVIDADES_RB_RDM'])==0){
		$vRegrasVioladas = true;
		$vMsgErro = "Informe pelo menos uma atividade de Rollback para RDM!";
	}  
	
	
	if(!$vRegrasVioladas){
		 
		require_once 'include/PHP/class/class.util.php';
		require_once 'include/PHP/class/class.rdm_template.php';
		require_once 'include/PHP/class/class.atividade_rdm_template.php';			 
		require_once 'include/PHP/class/class.atividade_rb_rdm_template.php';			
		 
		$RDM_TEMPLATE= new rdm_template();
		$ATIVIDADE_RDM_TEMPLATE = new atividade_rdm_template();
		$ATIVIDADE_RB_RDM_TEMPLATE = new atividade_rb_rdm_template();
		
		$RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($seq_rdm_template);		
		$RDM_TEMPLATE->setTITULO($v_TITULO);
		$RDM_TEMPLATE->setJUSTIFICATIVA($v_JUSTIFICATIVA);
		$RDM_TEMPLATE->setIMPACTO_NAO_EXECUTAR($v_IMPACTO_NAO_EXECUTAR);
		$RDM_TEMPLATE->setNOME_RESP_CHECKLIST($v_NOME_RESP_CHECKLIST);
		$RDM_TEMPLATE->setEMAIL_RESP_CHECKLIST($v_EMAIL_REP_CHECKLIST);
		$RDM_TEMPLATE->setDDD_TELEFONE_RESP_CHECKLIST($v_DDD_TELEFONE_RESP_CHECKLIST);
		$RDM_TEMPLATE->setNUMERO_TELEFONE_RESP_CHECKLIST($v_NUMERO_TELEFONE_RESP_CHECKLIST);		  
		$RDM_TEMPLATE->setOBSERVACAO($v_OBSERVACAO);		
		 
		$RDM_TEMPLATE->update($seq_rdm_template);
		// Código inserido: $RDM_TEMPLATE->SEQ_RDM_TEMPLATE
		 
		
		if($RDM->error == ""){ 
			
			 $ATIVIDADE_RDM_TEMPLATE->deleteByRDM($RDM_TEMPLATE->getSEQ_RDM_TEMPLATE());
			 
			// ===== INCLUIR ATIVIDADES DA RDM =====			 	
		    for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){
		    	
		    	$ATIVIDADE_RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($RDM_TEMPLATE->getSEQ_RDM_TEMPLATE());
				$ATIVIDADE_RDM_TEMPLATE->setDESCRICAO($_SESSION['ATIVIDADES_RDM'][$i][4]);  
				
				if($_SESSION['ATIVIDADES_RDM'][$i][3]==1){//servidores
					$ATIVIDADE_RDM_TEMPLATE->setSEQ_SERVIDOR($_SESSION['ATIVIDADES_RDM'][$i][1]);				
				}else if($_SESSION['ATIVIDADES_RDM'][$i][3]==2){//sistemas
					$ATIVIDADE_RDM_TEMPLATE->setSEQ_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RDM'][$i][1]);
				} 
				$ATIVIDADE_RDM_TEMPLATE->setSEQ_TIPO_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RDM'][$i][3]);
				$ATIVIDADE_RDM_TEMPLATE->setORDEM($i+1); 
				$ATIVIDADE_RDM_TEMPLATE->setSEQ_EQUIPE_TI($_SESSION['ATIVIDADES_RDM'][$i][5]);
				 
				$ATIVIDADE_RDM_TEMPLATE->insert(); 
				
				$ATIVIDADE_RDM_TEMPLATE->setDESCRICAO(NULL);
				$ATIVIDADE_RDM_TEMPLATE->setSEQ_SERVIDOR(NULL);	
				$ATIVIDADE_RDM_TEMPLATE->setSEQ_ITEM_CONFIGURACAO(NULL);
				$ATIVIDADE_RDM_TEMPLATE->setSEQ_TIPO_ITEM_CONFIGURACAO(NULL);
				$ATIVIDADE_RDM_TEMPLATE->setORDEM(NULL);				 
				$ATIVIDADE_RDM_TEMPLATE->setSEQ_EQUIPE_TI(NULL);
				
		    }
		    
		     $ATIVIDADE_RB_RDM_TEMPLATE->deleteByRDM($RDM_TEMPLATE->getSEQ_RDM_TEMPLATE());
		     
			// ===== INCLUIR ATIVIDADES DE ROLLBACK DA RDM ===== 		
		    for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){
				$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($RDM_TEMPLATE->getSEQ_RDM_TEMPLATE());
				$ATIVIDADE_RB_RDM_TEMPLATE->setDESCRICAO($_SESSION['ATIVIDADES_RB_RDM'][$i][4]);
				
				if($_SESSION['ATIVIDADES_RDM'][$i][3]==1){//servidores
					$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_SERVIDOR($_SESSION['ATIVIDADES_RB_RDM'][$i][1]);				
				}else if($_SESSION['ATIVIDADES_RB_RDM'][$i][3]==2){//sistemas
					$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RB_RDM'][$i][1]);
				} 
				$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_TIPO_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RDM'][$i][3]);
				$ATIVIDADE_RB_RDM_TEMPLATE->setORDEM($_SESSION['ATIVIDADES_RB_RDM'][$i][5]);				 		  
				$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_EQUIPE_TI($_SESSION['ATIVIDADES_RB_RDM'][$i][6]);
				 
				$ATIVIDADE_RB_RDM_TEMPLATE->insert();
				
			 	$ATIVIDADE_RB_RDM_TEMPLATE->setDESCRICAO(NULL);
				$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_SERVIDOR(NULL);				
			    $ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_ITEM_CONFIGURACAO(NULL); 
				$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_TIPO_ITEM_CONFIGURACAO(NULL);
				$ATIVIDADE_RB_RDM_TEMPLATE->setORDEM(NULL);					  
				$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_EQUIPE_TI(NULL);
		    }
		    
		    
		    
			unset($_SESSION['ATIVIDADES_RDM']);		
			unset($_SESSION['ATIVIDADES_RB_RDM']);		
			unset($_SESSION['ID_ATIVIDADE_RDM']);		
			unset($_SESSION['ID_ATIVIDADE_RB_RDM']); 
			
			$pagina->redirectTo("RDMTemplatePesquisar.php?v_SEQ_RDM_TEMPLATE=$RDM_TEMPLATE->SEQ_RDM_TEMPLATE&mensagemErro=$vMsgErro");
		}//fim do teste de erro da RDM
		
	}
	
} 
// ============================================================================================================
// Configurações AJAX
// ============================================================================================================
require_once 'include/PHP/class/class.Sajax.php';
$Sajax = new Sajax();

function ValidarPessoaContato($v_NUM_MATRICULA_CONTATO){
	 
	if($v_NUM_MATRICULA_CONTATO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$pagina = new Pagina();
		$empregados = new empregados();
		$v_NUM_MATRICULA_CONTATO = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_CONTATO);
		$empregados->select($v_NUM_MATRICULA_CONTATO);
		if($empregados->NOME != ""){
			return $v_NUM_MATRICULA_CONTATO."|".$empregados->NOME."|".$empregados->NUM_DDD."-".$empregados->NUM_VOIP;
		}else{
			return "";
		}
	}else{
		return "";
		//return "v_NUM_MATRICULA_CONTATO: ".$v_NUM_MATRICULA_CONTATO;
	}
}

$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export("ValidarPessoaContato");
$Sajax->sajax_handle_client_request();

// ============================================================================================================
// Configuração da págína
// ============================================================================================================

$pagina->SettituloCabecalho("Alteração de Template de RDM"); // Indica o título do cabeçalho da página

// Itens das abas
$aItemAba = Array( array("RDMTemplatePesquisar.php", "", "Pesquisa"),
						array("RDM_Template_Cadastro.php", "", "Adicionar"),
						  array("#", "tabact", "Alterar"));
						;
						
$pagina->SetaItemAba($aItemAba);
	
$pagina->method = "post";
$pagina->MontaCabecalho(1);
print $pagina->CampoHidden("flag", "1");


// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================

?>

<script language="javascript">
	<?
	$Sajax->sajax_show_javascript();
	?>
	 
	// Chamada
	function do_ValidarSolicitante() {
		if(document.form.v_NUM_MATRICULA_SOLICITANTE.value != ""){
			//window.dados_solicitante.innerHTML = "carregando....";
			document.getElementById("dados_solicitante").innerHTML = "carregando....";
			v_NUM_MATRICULA_SOLICITANTE = document.form.v_NUM_MATRICULA_SOLICITANTE.value;
			//v_NUM_MATRICULA_SOLICITANTE = document.form.v_NUM_MATRICULA_SOLICITANTE.value.replace(/A-Z/i, '');
			//v_NUM_MATRICULA_SOLICITANTE = v_NUM_MATRICULA_SOLICITANTE.replace( /[^0-9\.]/, '' );
			//alert(v_NUM_MATRICULA_SOLICITANTE);
			x_ValidarPessoaContato(v_NUM_MATRICULA_SOLICITANTE, retorno_ValidarSolicitante);
		}
	}
	// Retorno
	function retorno_ValidarSolicitante(val) {
		// Separar os valores retornados
		 
		if(val != ""){
			//  $v_NUM_MATRICULA_CONTATO."|".$empregados->NOME."|".$empregados->NUM_DDD."-".$empregados->NUM_VOIP;
			v_NUM_MATRICULA_SOLICITANTE = val.substr(0, val.indexOf("|"));
			StringRestante = val.substr(val.indexOf("|")+1, val.length);
			v_NOME = StringRestante.substr(0, StringRestante.indexOf("|"));
			StringRestante = StringRestante.substr(StringRestante.indexOf("|")+1, StringRestante.length);
			v_TELEFONE = StringRestante;
			// Adicionar resultado ao formulário
			document.form.v_NUM_MATRICULA_SOLICITANTE_REAL.value = v_NUM_MATRICULA_SOLICITANTE;
			//window.dados_solicitante.innerHTML = "Nome: <b>"+v_NOME+"</b> - Ramal: <b>"+v_TELEFONE+"</b>";
			document.getElementById("dados_solicitante").innerHTML = "Nome: <b>"+v_NOME+"</b> - Ramal: <b>"+v_TELEFONE+"</b>";
		}else{
			alert("Pessoa não encontrada. Clique na imagem de lupa para efetuar uma pesquisa.");
			//window.dados_solicitante.innerHTML = "Preencha este campo com a matrícula do solicitante.";
			document.getElementById("dados_solicitante").innerHTML = "Preencha este campo com a matrícula do solicitante.";
			document.form.v_NUM_MATRICULA_SOLICITANTE.value = "";
		}
	}

	// =======================================================================
	// Controle de eventos
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
		// default warning message
		var msg = "Confirma a saída? Esta ação ocasionará a perda das informações já preenchidas.";

		// set event
		if (!e) { e = window.event; }
		if (e) { e.returnValue = msg; }
		// return warning message
		return msg;
	}

	// Initialise
	//addEvent(window, 'load', addListeners, false);
	
	// ==================================================== FIM AJAX =====================================

	function fValidaFormAtividadesRDM(v_ACAO_ATM){
		 document.form.v_ACAO_ATM.value = v_ACAO_ATM;
		 document.form.v_LIMPAR_SESSAO.value = 'NAO';
		 if(document.form.v_NOM_ITEM_CONFIGURACAO_ATM.value == ""){
			 	alert("Preencha o campo Item de configuração");
			 	return false;
		 }
		 if(document.form.v_DESCRICAO_ATIVIDADE_ATM.value == ""){
			 	alert("Preencha o campo Descrição");
			 	return false;
		 }
		
		 if(document.form.v_SEQ_EQUIPE_TI_ATM.value == ""){
			 	alert("Preencha o campo Executor");
			 	return false;
		 }

		  
		 //return true;
  
	}
	function fValidaFormAtividadesRBRDM(v_ACAO_ATRBM){
		 document.form.v_ACAO_ATRBM.value = v_ACAO_ATRBM;
		 document.form.v_LIMPAR_SESSAO.value = 'NAO';
		 if(document.form.v_NOM_ITEM_CONFIGURACAO_ATRBM.value == ""){
			 	alert("Preencha o campo Item de configuração");
			 	return false;
		 }
		 if(document.form.v_DESCRICAO_ATIVIDADE_ATRBM.value == ""){
			 	alert("Preencha o campo Descrição");
			 	return false;
		 }
		 if(document.form.v_ORDEM_ATRBM.value == ""){
			 	alert("Preencha o campo Ordem da atividade");
			 	return false;
		 }
		  
		 if(document.form.v_SEQ_EQUIPE_TI_ATRBM.value == ""){
			 	alert("Preencha o campo Executor");
			 	return false;
		 }

		 
	 	 
	 	//return true;
 
	}

	

	function fExibirAtividadesRDM(){
		if(document.getElementById("tabelaAtividadesRDM").style.display == "none"){
			document.getElementById("tabelaAtividadesRDM").style.display = "block";
			document.getElementById("MaisAtividadesRDM").style.display = "none";
			document.getElementById("MenosAtividadesRDM").style.display = "block";
		}else{
			document.getElementById("tabelaAtividadesRDM").style.display = "none";
			document.getElementById("MaisAtividadesRDM").style.display = "block";
			document.getElementById("MenosAtividadesRDM").style.display = "none";
		}
	}


	function fExcluirATM(vValor){
	    if(confirm("Desejar apagar o registro?")){
	    	document.form.v_ACAO_ATM.value='EXCLUIR_ATM';
	    	document.form.v_SEQ_ID_ATM.value=vValor;
	    	document.form.v_LIMPAR_SESSAO.value = 'NAO';
			document.form.submit();
		}
	}
	function fExcluirATRBM(vValor){
	    if(confirm("Desejar apagar o registro?")){
	    	document.form.v_ACAO_ATRBM.value='EXCLUIR_ATRBM';
	    	document.form.v_SEQ_ID_ATRBM.value=vValor;
	    	document.form.v_LIMPAR_SESSAO.value = 'NAO';
			document.form.submit();
		}
	}

	function fCancelarAtividadesRDM(){
		document.form.v_ACAO_ATM.value = "";
		document.form.v_NOM_ITEM_CONFIGURACAO_ATM.value = "";		 
		document.form.v_SEQ_ID_ATM.value = "";
		document.form.v_SEQ_ITEM_CONFIGURACAO_ATM.value = "";
		document.form.v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM.value = "";
		document.form.v_DESCRICAO_ATIVIDADE_ATM.value = "";		
		document.form.v_SEQ_EQUIPE_TI_ATM.value = "";
		document.getElementById("tabelaAtividadesRDM").style.display = "none";
		document.getElementById("MaisAtividadesRDM").style.display = "block";
		document.getElementById("MenosAtividadesRDM").style.display = "none";	
		return false;	 
	}

	function fExibirAtividadesRBRDM(){
		if(document.getElementById("tabelaAtividadesRBRDM").style.display == "none"){
			document.getElementById("tabelaAtividadesRBRDM").style.display = "block";
			document.getElementById("MaisAtividadesRBRDM").style.display = "none";
			document.getElementById("MenosAtividadesRBRDM").style.display = "block";
		}else{
			document.getElementById("tabelaAtividadesRBRDM").style.display = "none";
			document.getElementById("MaisAtividadesRBRDM").style.display = "block";
			document.getElementById("MenosAtividadesRBRDM").style.display = "none";
		}
	}

	function fCancelarAtividadesRBRDM(){
		document.form.v_ACAO_ATRBM.value = "";
		document.form.v_NOM_ITEM_CONFIGURACAO_ATRBM.value = "";
		document.form.v_SEQ_ID_ATRBM.value = "";
		document.form.v_SEQ_ITEM_CONFIGURACAO_ATRBM.value = "";
		document.form.v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM.value = "";
		document.form.v_DESCRICAO_ATIVIDADE_ATRBM.value = "";
		document.form.v_ORDEM_ATRBM.value = "";
		//document.form.v_HORA_EXECUCAO_ATRBM.value == "";
		document.form.v_SEQ_EQUIPE_TI_ATRBM.value = "";		 
		document.getElementById("tabelaAtividadesRBRDM").style.display = "none";
		document.getElementById("MaisAtividadesRBRDM").style.display = "block";
		document.getElementById("MenosAtividadesRBRDM").style.display = "none";
		return false;
		 
	}
	
	
	 
</script>
<?	


// ============================================================================================================
// Dados do solicitante
// ============================================================================================================
$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
//$pagina->LinhaCampoFormularioColspanDestaque("Dados do Solicitante", 2);
 

print $pagina->CampoHidden2("seq_rdm_template", $seq_rdm_template);
 
						  								  
// ============================================================================================================
// Informações Gerais
// ============================================================================================================
$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais ", 2);

		 					  
//Titulo da RDM
$pagina->LinhaCampoFormulario("Título:", "right", "S", $pagina->CampoTexto("v_TITULO", "S", "Título", "80", "80", "$v_TITULO"), "left", "id=".$pagina->GetIdTable());
 
//RAZO DA RDM								  
$pagina->LinhaCampoFormulario("Justificativa:", "right", "S", $pagina->CampoTexto("v_JUSTIFICATIVA", "S", "Justificativa", "80", "80", "$v_JUSTIFICATIVA"), "left", "id=".$pagina->GetIdTable());
								  
//Impacto DA RDM								  
$pagina->LinhaCampoFormulario("Impacto de não executar:", "right", "S", $pagina->CampoTexto("v_IMPACTO_NAO_EXECUTAR", "S", "Impacto de não executar", "80", "80", "$v_IMPACTO_NAO_EXECUTAR"), "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Observações:", "right", "N", 
 $pagina->CampoTextArea("v_OBSERVACAO", "N", "Observações", "99", "3", "$v_OBSERVACAO", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
, "left", "id=".$pagina->GetIdTable());
						  
$pagina->FechaTabelaPadrao();

// ============================================================================================================
// FORMULARIO DE ATIVIDADES DA RDM
// ============================================================================================================
 
$v_EXIBIR_ATM = "";
//$vMsgErro = "";
// inicia a sessão
//session_start();
 
if (!isset($_SESSION['ID_ATIVIDADE_RDM']) && !isset($_SESSION['ATIVIDADES_RDM'])){
	//print "inicialização da sessão <br>";
  	$_SESSION['ID_ATIVIDADE_RDM'] = 0;
 	$_SESSION['ATIVIDADES_RDM'] = array();
}else if (isset($_SESSION['ID_ATIVIDADE_RDM']) && isset($_SESSION['ATIVIDADES_RDM'])){
	//print "recuperou atividades da sessao <br>";
	//print "Qtde: ".$_SESSION['ID_ATIVIDADE_RDM']."<br>";	
	//print "ID: "+ $_SESSION['ID_ATIVIDADE_RDM'] ."<br>";	 
}


//print "v_ACAO_ATM: ".$v_ACAO_ATM."<br>";
if($v_ACAO_ATM == "INCLUIR_ATM"){
	//validar resgistro
	$v_OK = true;
	for ($i = 0; $i <= count($_SESSION['ATIVIDADES_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RDM'][$i][1] == $v_SEQ_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][2] == $v_NOM_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][3] == $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][4] == $v_DESCRICAO_ATIVIDADE_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][5] == $v_SEQ_EQUIPE_TI_ATM){
	 			$vMsgErro		 = "Atividade já cadastrada!";
	 			$v_EXIBIR_ATM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}
	 }	
	 
	if($v_OK){
		$_SESSION['ATIVIDADES_RDM'][] = array($_SESSION['ID_ATIVIDADE_RDM']++,$v_SEQ_ITEM_CONFIGURACAO_ATM ,
					$v_NOM_ITEM_CONFIGURACAO_ATM,$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM,$v_DESCRICAO_ATIVIDADE_ATM,$v_SEQ_EQUIPE_TI_ATM);
		$v_SEQ_ITEM_CONFIGURACAO_ATM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATM = "";
		$v_DESCRICAO_ATIVIDADE_ATM = "";
		$v_DATA_EXECUCAO_ATM = "";
		$v_HORA_EXECUCAO_ATM = "";
		$v_SEQ_EQUIPE_TI_ATM = "";					
		$v_ACAO_ATM = "";		
	}	
					
				
}else if($v_ACAO_ATM == "ALTERAR_ATM"){
	$v_EXIBIR_ATM = "EXIBIR";
 	for ($i = 0; $i <= count($_SESSION['ATIVIDADES_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RDM'][$i][0] == $v_SEQ_ID_ATM){
	 		$v_SEQ_ITEM_CONFIGURACAO_ATM =$_SESSION['ATIVIDADES_RDM'][$i][1];
	 		$v_NOM_ITEM_CONFIGURACAO_ATM = $_SESSION['ATIVIDADES_RDM'][$i][2];
	 		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = $_SESSION['ATIVIDADES_RDM'][$i][3];
	 		$v_DESCRICAO_ATIVIDADE_ATM = $_SESSION['ATIVIDADES_RDM'][$i][4];	 		
	 		$v_SEQ_EQUIPE_TI_ATM = $_SESSION['ATIVIDADES_RDM'][$i][5];	 		 
			
	 		break;
	 	}
	 }		
}else if($v_ACAO_ATM == "CONF_ALTERAR_ATM"){
	//validar resgistro
	$v_OK = true;
	for ($i = 0; $i <= count($_SESSION['ATIVIDADES_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RDM'][$i][0] != $v_SEQ_ID_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][1] == $v_SEQ_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][2] == $v_NOM_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][3] == $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][4] == $v_DESCRICAO_ATIVIDADE_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][5] == $v_DATA_EXECUCAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][6] == $v_HORA_EXECUCAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][7] == $v_SEQ_EQUIPE_TI_ATM){
	 			$vMsgErro		 = "Atividade já cadastrada!";
	 			$v_EXIBIR_ATM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}
	 }	
	 if($v_OK){
		for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){ 		 
		 	if ($_SESSION['ATIVIDADES_RDM'][$i][0] == $v_SEQ_ID_ATM){
		 		$_SESSION['ATIVIDADES_RDM'][$i][1] = $v_SEQ_ITEM_CONFIGURACAO_ATM;
		 		$_SESSION['ATIVIDADES_RDM'][$i][2] = $v_NOM_ITEM_CONFIGURACAO_ATM;
		 		$_SESSION['ATIVIDADES_RDM'][$i][3] = $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM;
		 		$_SESSION['ATIVIDADES_RDM'][$i][4] = $v_DESCRICAO_ATIVIDADE_ATM;		 		
		 		$_SESSION['ATIVIDADES_RDM'][$i][5] = $v_SEQ_EQUIPE_TI_ATM; 
		 		break;
		 	}
		 }	
		$v_SEQ_ID_ATM = "";		
		$v_SEQ_ITEM_CONFIGURACAO_ATM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = "";
		$v_DESCRICAO_ATIVIDADE_ATM = "";		
		$v_SEQ_EQUIPE_TI_ATM = "";					
		$v_ACAO_ATM = "";	
	 }
}else if($v_ACAO_ATM == "EXCLUIR_ATM"){
	for ($i = 0; $i <= count($_SESSION['ID_ATIVIDADE_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RDM'][$i][0] == $v_SEQ_ID_ATM){
	 		unset($_SESSION['ATIVIDADES_RDM'][$i]);			 
	 		break;
	 	}
	 }		
	 $v_ACAO_ATM = "";	
	 $v_SEQ_ID_ATM = "";	
}

//$_SESSION['ATIVIDADES_RDM'] = $atividadesRDM;

// Mostrar ou não os parâmetros
//if($flag == ""){ // Mostrar parâmetros
//	$MaisAtividadesRDM = "style=\"display: none;\" ";
//	$MenosAtividadesRDM = "";
//	$tabelaAtividadesRDM = "";
//}else{ // Não mostrar parâmetros
//	$MaisAtividadesRDM = "style=\"display: none;\" ";
//	$MenosAtividadesRDM = "";
//	$tabelaAtividadesRDM = "style=\"display: none;\" ";
//}

if($v_EXIBIR_ATM == "EXIBIR"){//mostrar
	$MaisAtividadesRDM = "style=\"display: none;\" ";
	$MenosAtividadesRDM = "";
	$tabelaAtividadesRDM = " ";
}else{//não mostrar
	$MaisAtividadesRDM = " ";
	$MenosAtividadesRDM = "style=\"display: none;\" ";
	$tabelaAtividadesRDM = "style=\"display: none;\" ";
}

 

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisAtividadesRDM\" $MaisAtividadesRDM cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormularioColspanDestaque("Adicionar atividades da RDM <a href=\"javascript: fExibirAtividadesRDM();\"><img border=\"0\" src=\"imagens/mais.jpg\"></a>", 2);
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MenosAtividadesRDM\" $MenosAtividadesRDM cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormularioColspanDestaque("Adicionar atividades da RDM <a href=\"javascript: fExibirAtividadesRDM();\"><img border=\"0\" src=\"imagens/menos.jpg\"></a>", 2);
$pagina->FechaTabelaPadrao();

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaAtividadesRDM\" $tabelaAtividadesRDM");

print $pagina->CampoHidden("v_ACAO_ATM", $v_ACAO_ATM);
print $pagina->CampoHidden("v_SEQ_ID_ATM", $v_SEQ_ID_ATM);
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO_ATM", $v_SEQ_ITEM_CONFIGURACAO_ATM);
print $pagina->CampoHidden("v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM", $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM);

$pagina->LinhaCampoFormulario("Item configuracao:", "right", "S", 
									   $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO_ATM", "N", "" , "60", "60", "$v_NOM_ITEM_CONFIGURACAO_ATM", "readonly").
									   $pagina->ButtonProcuraItemConfiguracao("v_NOM_ITEM_CONFIGURACAO_ATM", "v_SEQ_ITEM_CONFIGURACAO_ATM","v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM")
									, "left", "id=".$pagina->GetIdTable());
// Descição do chamado
$pagina->LinhaCampoFormulario("Descrição:", "right", "S",
								  $pagina->CampoTextArea("v_DESCRICAO_ATIVIDADE_ATM", "N", "Descrição", "100", "5", "$v_DESCRICAO_ATIVIDADE_ATM", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
								  , "left", "id=".$pagina->GetIdTable());	



require_once 'include/PHP/class/class.equipe_ti.php';
$equipe_ti = new equipe_ti();

$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI");
$vItemTodosEquipe = "S";

if($v_ACAO_ATM == "ALTERAR_ATM"){
	for($i=0;$i<count($aItemOptionEquipe);$i++){
		if($aItemOptionEquipe[$i][0]==$v_SEQ_EQUIPE_TI_ATM){
			$aItemOptionEquipe[$i][1]="Selected";
		}
	}
}

$pagina->LinhaCampoFormulario("Executor:", "right", "S",								 
								  $pagina->CampoSelect("v_SEQ_EQUIPE_TI_ATM", "N", "Executor", $vItemTodosEquipe, $aItemOptionEquipe, "Escolha", "", "combo_equipe")
								  , "left", "id=".$pagina->GetIdTable());	

if($v_ACAO_ATM == "ALTERAR_ATM"){
$pagina->LinhaCampoFormularioColspan("center",
		$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormAtividadesRDM('CONF_ALTERAR_ATM'); ", " Salvar Atividade ")
		." ".$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fCancelarAtividadesRDM(); ", " Cancelar ")
		, "2");

}else{
	$pagina->LinhaCampoFormularioColspan("center",
			$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormAtividadesRDM('INCLUIR_ATM'); ", " Salvar Atividade ")
			." ".$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fCancelarAtividadesRDM(); ", " Cancelar ")
			, "2");
}

$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");
$pagina->FechaTabelaPadrao();



// Inicio do grid de atividades
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Ordem", "");
$header[] = array("Item", "");
$header[] = array("Descrição", "");

$header[] = array("Executor", "");

if(count($_SESSION['ATIVIDADES_RDM']) == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro de atividade de RDM informado", count($header));
}else{
	
	//print "<br>DEPOIS DA ORDENACAO <br>";
	//print_r($_SESSION['ATIVIDADES_RDM']);
	
	$corpo = array();
	//$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Atividades da RDM", $header);
	 for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){
	 		$valor ="<a href=\"javascript:document.form.v_ACAO_ATM.value='ALTERAR_ATM';
		document.form.v_SEQ_ID_ATM.value=".$_SESSION['ATIVIDADES_RDM'][$i][0].";document.form.submit();\">
		<img src=\"imagens/alterar.gif\" alt=\"Alterar\" border=\"0\"></a>";
		
		$valor.="<a href=\"#\" onclick=\"fExcluirATM(".$_SESSION['ATIVIDADES_RDM'][$i][0].");\"><img src=\"imagens/excluir.gif\" alt=\"Excluir\" border=\"0\"></a>";
	
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("center", "campo", $i+1);
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RDM'][$i][2]);
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RDM'][$i][4]);
		
		$executor= new equipe_ti();		 
		$executor->select($_SESSION['ATIVIDADES_RDM'][$i][5]);
		$corpo[] = array("left", "campo", $executor->NOM_EQUIPE_TI);
		
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}




$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");

// ============================================================================================================
// FORMULARIO DE ATIVIDADES DE ROLLBACK
// ============================================================================================================

$v_EXIBIR_ATRBM = "";
//$vMsgErro = "";
// inicia a sessão
//session_start();
 
if (!isset($_SESSION['ID_ATIVIDADE_RB_RDM']) && !isset($_SESSION['ATIVIDADES_RB_RDM'])){
	//print "inicialização da sessão <br>";
  	$_SESSION['ID_ATIVIDADE_RB_RDM'] = 0;
 	$_SESSION['ATIVIDADES_RB_RDM'] = array();
}else if (isset($_SESSION['ID_ATIVIDADE_RB_RDM']) && isset($_SESSION['ATIVIDADES_RB_RDM'])){
	//print "recuperou atividades da sessao <br>";
	//print "Qtde: ".$_SESSION['ID_ATIVIDADE_RDM']."<br>";	
	//print "ID: "+ $_SESSION['ID_ATIVIDADE_RDM'] ."<br>";	 
}


//print "v_ACAO_ATRBM: ".$v_ACAO_ATRBM."<br>";
if($v_ACAO_ATRBM == "INCLUIR_ATRBM"){
	//validar resgistro
	$v_OK = true;
	for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RB_RDM'][$i][1] == $v_SEQ_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][2] == $v_NOM_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][3] == $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][4] == $v_DESCRICAO_ATIVIDADE_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][5] == $v_ORDEM_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][6] == $v_SEQ_EQUIPE_TI_ATRBM){
	 			$vMsgErro		 = "Atividade já cadastrada!";
	 			$v_EXIBIR_ATRBM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RB_RDM'][$i][5] == $v_ORDEM_ATRBM){
	 			$vMsgErro	 = "Já existe uma atividade para esta ordem!";
	 			$v_EXIBIR_ATRBM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}
	 }	
	 
	if($v_OK){
		$_SESSION['ATIVIDADES_RB_RDM'][] = array($_SESSION['ID_ATIVIDADE_RB_RDM']++,$v_SEQ_ITEM_CONFIGURACAO_ATRBM ,
					$v_NOM_ITEM_CONFIGURACAO_ATRBM,$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM,$v_DESCRICAO_ATIVIDADE_ATRBM,$v_ORDEM_ATRBM,$v_SEQ_EQUIPE_TI_ATRBM);
		$v_SEQ_ITEM_CONFIGURACAO_ATRBM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATRBM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = "";
		$v_DESCRICAO_ATIVIDADE_ATRBM = "";
		$v_ORDEM_ATRBM = "";		 
		$v_SEQ_EQUIPE_TI_ATRBM = "";					
		$v_ACAO_ATRBM = "";		
	}	
					
				
}else if($v_ACAO_ATRBM == "ALTERAR_ATRBM"){
	$v_EXIBIR_ATRBM = "EXIBIR";
 	for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RB_RDM'][$i][0] == $v_SEQ_ID_ATRBM){
	 		$v_SEQ_ITEM_CONFIGURACAO_ATRBM = $_SESSION['ATIVIDADES_RB_RDM'][$i][1];
	 		$v_NOM_ITEM_CONFIGURACAO_ATRBM = $_SESSION['ATIVIDADES_RB_RDM'][$i][2];
	 		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = $_SESSION['ATIVIDADES_RB_RDM'][$i][3];
	 		$v_DESCRICAO_ATIVIDADE_ATRBM = $_SESSION['ATIVIDADES_RB_RDM'][$i][4];
	 		$v_ORDEM_ATRBM =  $_SESSION['ATIVIDADES_RB_RDM'][$i][5];	 		
	 		$v_SEQ_EQUIPE_TI_ATRBM = $_SESSION['ATIVIDADES_RB_RDM'][$i][6];	 		 
			
	 		break;
	 	}
	 }		
}else if($v_ACAO_ATRBM == "CONF_ALTERAR_ATRBM"){
	//validar resgistro
	$v_OK = true;
	for ($i = 0; $i <= count($_SESSION['ATIVIDADES_RB_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RB_RDM'][$i][0] != $v_SEQ_ID_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][1] == $v_SEQ_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][2] == $v_NOM_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][3] == $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][4] == $v_DESCRICAO_ATIVIDADE_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][5] == $v_ORDEM_ATRBM &&
	 		$_SESSION['ATIVIDADES_RB_RDM'][$i][6] == $v_SEQ_EQUIPE_TI_ATRBM){
	 			$vMsgErro		 = "Atividade já cadastrada!";
	 			$v_EXIBIR_ATRBM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RB_RDM'][$i][0] != $v_SEQ_ID_ATRBM &&
	 			  $_SESSION['ATIVIDADES_RB_RDM'][$i][5] == $v_ORDEM_ATRBM  ){
	 			$vMsgErro	 = "Já existe uma atividade para esta ordem!";
	 			$v_EXIBIR_ATRBM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}
	 }	
	 if($v_OK){
		for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){ 		 
		 	if ($_SESSION['ATIVIDADES_RB_RDM'][$i][0] == $v_SEQ_ID_ATRBM){
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][1] = $v_SEQ_ITEM_CONFIGURACAO_ATRBM;
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][2] = $v_NOM_ITEM_CONFIGURACAO_ATRBM;
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][3] = $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM;
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][4] = $v_DESCRICAO_ATIVIDADE_ATRBM;
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][5] = $v_ORDEM_ATRBM;		 		
		 		$_SESSION['ATIVIDADES_RB_RDM'][$i][6] = $v_SEQ_EQUIPE_TI_ATRBM; 
		 		break;
		 	}
		 }		
		$v_SEQ_ID_ATRBM = "";
		$v_SEQ_ITEM_CONFIGURACAO_ATRBM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATRBM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM = "";
		$v_DESCRICAO_ATIVIDADE_ATRBM = "";
		$v_ORDEM_ATRBM = "";		 
		$v_SEQ_EQUIPE_TI_ATRBM = "";					
		$v_ACAO_ATRBM = "";	
	 }
}else if($v_ACAO_ATRBM == "EXCLUIR_ATRBM"){
	for ($i = 0; $i < count($_SESSION['ID_ATIVIDADE_RB_RDM']); $i++){ 		 
	 	if ($_SESSION['ATIVIDADES_RB_RDM'][$i][0] == $v_SEQ_ID_ATRBM){
	 		unset($_SESSION['ATIVIDADES_RB_RDM'][$i]);			 
	 		break;
	 	}
	 }		
	 $v_ACAO_ATM = "";	
	 $v_SEQ_ID_ATM = "";	
}

//$_SESSION['ATIVIDADES_RDM'] = $atividadesRDM;

// Mostrar ou não os parâmetros
//if($flag == ""){ // Mostrar parâmetros
//	$MaisAtividadesRDM = "style=\"display: none;\" ";
//	$MenosAtividadesRDM = "";
//	$tabelaAtividadesRDM = "";
//}else{ // Não mostrar parâmetros
//	$MaisAtividadesRDM = "style=\"display: none;\" ";
//	$MenosAtividadesRDM = "";
//	$tabelaAtividadesRDM = "style=\"display: none;\" ";
//}

if($v_EXIBIR_ATRBM == "EXIBIR"){//mostrar
	$MaisAtividadesRBRDM = "style=\"display: none;\" ";
	$MenosAtividadesRBRDM = "";
	$tabelaAtividadesRBRDM = " ";
}else{//não mostrar
	$MaisAtividadesRBRDM = " ";
	$MenosAtividadesRBRDM = "style=\"display: none;\" ";
	$tabelaAtividadesRBRDM = "style=\"display: none;\" ";
}

 

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisAtividadesRBRDM\" $MaisAtividadesRBRDM cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormularioColspanDestaque("Adicionar atividades de rollback da RDM <a href=\"javascript: fExibirAtividadesRBRDM();\"><img border=\"0\" src=\"imagens/mais.jpg\"></a>", 2);
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MenosAtividadesRBRDM\" $MenosAtividadesRBRDM cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormularioColspanDestaque("Adicionar atividades de rollback da RDM <a href=\"javascript: fExibirAtividadesRBRDM();\"><img border=\"0\" src=\"imagens/menos.jpg\"></a>", 2);
$pagina->FechaTabelaPadrao();

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaAtividadesRBRDM\" $tabelaAtividadesRBRDM");

print $pagina->CampoHidden("v_ACAO_ATRBM", $v_ACAO_ATRBM);
print $pagina->CampoHidden("v_SEQ_ID_ATRBM", $v_SEQ_ID_ATRBM);
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO_ATRBM", $v_SEQ_ITEM_CONFIGURACAO_ATRBM);
print $pagina->CampoHidden("v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM", $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM);

$pagina->LinhaCampoFormulario("Item configuracao:", "right", "S", 
									   $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO_ATRBM", "N", "" , "60", "60", "$v_NOM_ITEM_CONFIGURACAO_ATRBM", "readonly").
									   $pagina->ButtonProcuraItemConfiguracao("v_NOM_ITEM_CONFIGURACAO_ATRBM", "v_SEQ_ITEM_CONFIGURACAO_ATRBM","v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM")
									, "left", "id=".$pagina->GetIdTable());
// Descição do chamado
$pagina->LinhaCampoFormulario("Descrição:", "right", "S",
								  $pagina->CampoTextArea("v_DESCRICAO_ATIVIDADE_ATRBM", "N", "Descrição", "100", "5", "$v_DESCRICAO_ATIVIDADE_ATRBM", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
								  , "left", "id=".$pagina->GetIdTable());	

 

$pagina->LinhaCampoFormulario("Ordem da atividade:", "right", "S", $pagina->CampoInt("v_ORDEM_ATRBM", "N", "Ordem da atividade", "3", "$v_ORDEM_ATRBM", ""), "left");


require_once 'include/PHP/class/class.equipe_ti.php';
$equipe_ti = new equipe_ti();

$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI");
$vItemTodosEquipe = "S";
 
if($v_ACAO_ATRBM == "ALTERAR_ATRBM"){ 
	for($i=0;$i<count($aItemOptionEquipe);$i++){		 
		if($aItemOptionEquipe[$i][0]==$v_SEQ_EQUIPE_TI_ATRBM){
			$aItemOptionEquipe[$i][1]="Selected";
		}
	}
}

$pagina->LinhaCampoFormulario("Executor:", "right", "S",								 
								  $pagina->CampoSelect("v_SEQ_EQUIPE_TI_ATRBM", "N", "Executor", $vItemTodosEquipe, $aItemOptionEquipe, "Escolha", "", "combo_equipe")
								  , "left", "id=".$pagina->GetIdTable());	

if($v_ACAO_ATRBM == "ALTERAR_ATRBM"){
$pagina->LinhaCampoFormularioColspan("center",
		$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormAtividadesRBRDM('CONF_ALTERAR_ATRBM'); ", " Salvar Atividade ")
		." ".$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fCancelarAtividadesRBRDM(); ", " Cancelar ")
		, "2");

}else{
	$pagina->LinhaCampoFormularioColspan("center",
			$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormAtividadesRBRDM('INCLUIR_ATRBM'); ", " Salvar Atividade ")
			." ".$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fCancelarAtividadesRBRDM(); ", " Cancelar ")
			, "2");
}

$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");
$pagina->FechaTabelaPadrao();



// Inicio do grid de atividades
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Ordem", "");
$header[] = array("Item", "");
$header[] = array("Descrição", "");
//$header[] = array("Data/Hora de execução", "");
$header[] = array("Executor", "");

if(count($_SESSION['ATIVIDADES_RB_RDM']) == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro de atividade de Rollback da RDM informado", count($header));
}else{
	//print "ANTES DA ORDENACAO <br>";
	//print_r($_SESSION['ATIVIDADES_RDM']);
	//ORDENAR ARRAY PELA DATA HORA
	foreach ($_SESSION['ATIVIDADES_RB_RDM'] as $key => $row) {
	    $ordem[$key] = $row[5];	     
	}
	array_multisort($ordem, SORT_ASC,  $_SESSION['ATIVIDADES_RB_RDM']);
	//print "<br>DEPOIS DA ORDENACAO <br>";
	//print_r($_SESSION['ATIVIDADES_RDM']);
	
	$corpo = array();
	//$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Atividades de Rollback da RDM", $header);
	 for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){
	 		$valor ="<a href=\"javascript:document.form.v_ACAO_ATRBM.value='ALTERAR_ATRBM';
		document.form.v_SEQ_ID_ATRBM.value=".$_SESSION['ATIVIDADES_RB_RDM'][$i][0].";document.form.submit();\">
		<img src=\"imagens/alterar.gif\" alt=\"Alterar\" border=\"0\"></a>";
		
		$valor.="<a href=\"#\" onclick=\"fExcluirATRBM(".$_SESSION['ATIVIDADES_RB_RDM'][$i][0].");\"><img src=\"imagens/excluir.gif\" alt=\"Excluir\" border=\"0\"></a>";
	
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RB_RDM'][$i][5]);
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RB_RDM'][$i][2]);
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RB_RDM'][$i][4]);
		//$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RB_RDM'][$i][4]." ".$_SESSION['ATIVIDADES_RB_RDM'][$i][5]);
		$executor= new equipe_ti();		 
		$executor->select( $_SESSION['ATIVIDADES_RB_RDM'][$i][6]);
		$corpo[] = array("left", "campo", $executor->NOM_EQUIPE_TI);
		
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}




$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");


// ============================================================================================================
// CHECHKLIST/VALIDACAO DA RDM
// ============================================================================================================
$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
$pagina->LinhaCampoFormularioColspanDestaque("Responsável pelo checklist / validação da RDM", 2);
$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOME_RESP_CHECKLIST", "S", "Nome", "80", "80", "$v_NOME_RESP_CHECKLIST"), "left", "id=".$pagina->GetIdTable());
$pagina->LinhaCampoFormulario("E-mail:", "right", "S", $pagina->CampoTexto("v_EMAIL_REP_CHECKLIST", "S", "E-mail", "80", "80", "$v_EMAIL_REP_CHECKLIST"), "left", "id=".$pagina->GetIdTable());
$pagina->LinhaCampoFormulario("Telefone:", "right", "S", 
	$pagina->CampoInt("v_DDD_TELEFONE_RESP_CHECKLIST", "S", "DDD ", "2", "$v_DDD_TELEFONE_RESP_CHECKLIST", "")
	." - ".
	$pagina->CampoInt("v_NUMERO_TELEFONE_RESP_CHECKLIST", "S", "Telefone ", "8", "$v_NUMERO_TELEFONE_RESP_CHECKLIST", "")
	,"left", "id=".$pagina->GetIdTable());
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");
$pagina->FechaTabelaPadrao();

 		

$pagina->LinhaColspan("center", "<br><br><br>", "2", "");

$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
$pagina->LinhaCampoFormularioColspanDestaque("&nbsp;", 2);
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
$pagina->LinhaCampoFormularioColspan("center", 
$pagina->CampoButton("if(fValidaForm()){document.form.v_ACAO.value='ALTERAR';return true;}else{return false;}", " Salvar ") 

,"2");
$pagina->FechaTabelaPadrao();
$v_ACAO ="";
print $pagina->CampoHidden("v_ACAO", $v_ACAO);

if($vMsgErro!=""){
	$pagina->ScriptAlert($vMsgErro);
}


 
$pagina->MontaRodape();
?>
