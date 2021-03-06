<?php
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
function validarJanelaMudanca( $atividadeRDM){
	require_once 'include/PHP/class/class.janela_mudanca.php';
	$dentroDaJanela = false;
	$limiteValido = false;
	$diasDaSemana = array("Dom", "Seg","Ter","Qua","Qui", "Sex", "Sab");
	
	$janela = new janela_mudanca();
	$rowJanelaPermitida = null;
	$seqAtividade  = null;
	
	for ($i = 0; $i < count($atividadeRDM); $i++){ 
		$dentroDaJanela = false;
		$limiteValido = false;
		 
		$janela->selectByIC($atividadeRDM[$i][1],$atividadeRDM[$i][3]);
			
		if($janela->database->rows == 0){
			$dentroDaJanela = false;
			$limiteValido = false;
		}else{
			
			while ($row = pg_fetch_array($janela->database->result)){
				
				$data_array = split("/",$atividadeRDM[$i][5]);
				$dia = $data_array[0];
				$mes = $data_array[1];
				$ano = $data_array[2];		
				
				$hora_array = split(":",$atividadeRDM[$i][6]);
				$h = $hora_array[0];
				$min =  $hora_array[1];
				
				$dataExceucao = mktime(0,0, 0,$mes,$dia,$ano);
				$diaDaSemana = getDiaDaSemana($dataExceucao);
				
				//posicao do dia da semana da data de execucao
				$indexDiaInicioExecucao = 0;
				for ($x = 0; $x < count($diasDaSemana); $x++){
					if($diasDaSemana[$x] == $diaDaSemana){
						$indexDiaInicioExecucao = $x;
						break;
					} 			
				}
				//posicao do dia inicial
				$indexDiaInicial = 0;
				for ($y = 0; $y < count($diasDaSemana); $y++){
					//if($diasDaSemana[$i] == $janela->dia_semana_inicial){
					if($diasDaSemana[$y] == $row["dia_semana_inicial"]){
						$indexDiaInicial = $y;
						break;
					} 			
				}
				
				//posicao do dia final
				$indexDiaFinal = 0;
				for ($z = 0; $z < count($diasDaSemana); $z++){
					//if($diasDaSemana[$i] == $janela->dia_semana_final){
					if($diasDaSemana[$z] == $row["dia_semana_final"]){
						$indexDiaFinal = $z;
						break;
					} 			
				}
				
				if($indexDiaInicial == $indexDiaFinal){
					//if(($janela->hora_inicio_mudanca < $h) && ($h < $janela->hora_fim_mudanca)){
					if(($row["hora_inicio_mudanca"] < $h) && ($h < $row["hora_fim_mudanca"])){
						$seqAtividade = $atividadeRDM[$i][0];
						$rowJanelaPermitida = $row;
						$dentroDaJanela = true;
						$limiteValido = validarLimteAberturaRDM($atividadeRDM[$i],$row);
					//}else if( (($janela->hora_inicio_mudanca == $h) && ($min <= $janela->minuto_inicio_mudanca)) ){
					}else if( (($row["hora_inicio_mudanca"] == $h) && ($min <= $row["minuto_inicio_mudanca"])) ){
						$seqAtividade = $atividadeRDM[$i][0];
						$rowJanelaPermitida = $row;
						$dentroDaJanela = true;
						$limiteValido = validarLimteAberturaRDM($atividadeRDM[$i],$row);
					//}else if( (($janela->hora_fim_mudanca == $h) && ( $min <= $janela->minuto_fim_mudanca)) ){
					}else if( (($row["hora_fim_mudanca"] == $h) && ( $min <= $row["minuto_fim_mudanca"])) ){
						$seqAtividade = $atividadeRDM[$i][0];
						$rowJanelaPermitida = $row;
						$dentroDaJanela = true;
						$limiteValido = validarLimteAberturaRDM($atividadeRDM[$i],$row);
					//}else if(($h < $janela->hora_fim_mudanca) ){
					}else if(($h < $row["hora_fim_mudanca"]) ){
						$seqAtividade = $atividadeRDM[$i][0];
						$rowJanelaPermitida = $row;
						$dentroDaJanela = true;
						$limiteValido = validarLimteAberturaRDM($atividadeRDM[$i],$row);	
					
					//}else if(($janela->hora_inicio_mudanca == $janela->hora_fim_mudanca) && ($janela->hora_inicio_mudanca == $h) && 
					//($janela->hora_fim_mudanca == $h)&& ($min >= $janela->minuto_inicio_mudanca)){
					}else if(($row["hora_inicio_mudanca"] == $row["->hora_fim_mudanca"]) && ($row["hora_inicio_mudanca"] == $h) && 
					($row["hora_fim_mudanca"] == $h)&& ($min >= $row["minuto_inicio_mudanca"])){
						$seqAtividade = $atividadeRDM[$i][0];
						$rowJanelaPermitida = $row;
						$dentroDaJanela = true;
						$limiteValido = validarLimteAberturaRDM($atividadeRDM[$i],$row);		
					}			
				}else if (($indexDiaInicial <= $indexDiaInicioExecucao) && ($indexDiaInicioExecucao <= $indexDiaFinal)){
					// inicio e termino no mesmo dia
					//if($janela->hora_inicio_mudanca <= $janela->hora_fim_mudanca){
					if($row["hora_inicio_mudanca"] <= $row["hora_fim_mudanca"]){				
						//if(($janela->hora_inicio_mudanca < $h) && ($h < $janela->hora_fim_mudanca)){
						if(($row["hora_inicio_mudanca"] < $h) && ($h < $row["hora_fim_mudanca"])){
							$seqAtividade = $atividadeRDM[$i][0];
							$rowJanelaPermitida = $row;
							$dentroDaJanela = true;
							$limiteValido = validarLimteAberturaRDM($atividadeRDM[$i],$row);
						//}else if( (($janela->hora_inicio_mudanca == $h) && ($min >= $janela->minuto_inicio_mudanca)) ){
						}else if( (($row["hora_inicio_mudanca"] == $h) && ($min >= $row["minuto_inicio_mudanca"])) ){
							$seqAtividade = $atividadeRDM[$i][0];
							$rowJanelaPermitida = $row;
							$dentroDaJanela = true;
							$limiteValido = validarLimteAberturaRDM($atividadeRDM[$i],$row);
						//}else if( (($janela->hora_fim_mudanca == $h) && ( $min <= $janela->minuto_fim_mudanca)) ){
						}else if( (($row["hora_fim_mudanca"] == $h) && ( $min <= $row["minuto_fim_mudanca"])) ){
							$seqAtividade = $atividadeRDM[$i][0];
							$rowJanelaPermitida = $row;
							$dentroDaJanela = true;
							$limiteValido = validarLimteAberturaRDM($atividadeRDM[$i],$row);	
						//}else if(($h < $janela->hora_fim_mudanca) ){
						}
						//else if(($h < $row["hora_fim_mudanca"]) ){
						//	$dentroDaJanela = true; 	
						//}		
					//}else if($janela->hora_inicio_mudanca > $janela->hora_fim_mudanca){
					}else if($row["hora_inicio_mudanca"] > $row["hora_fim_mudanca"]){	
						// termino no dia anterior
						//if(($janela->hora_inicio_mudanca < $h) && ($janela->hora_fim_mudanca < $h)){
						if(($row["hora_inicio_mudanca"] < $h) && ($row["hora_fim_mudanca"] < $h)){
							$seqAtividade = $atividadeRDM[$i][0];
							$rowJanelaPermitida = $row;
							$dentroDaJanela = true;
							$limiteValido = validarLimteAberturaRDM($atividadeRDM[$i],$row);
						}
					//}else if($janela->hora_inicio_mudanca == $janela->hora_fim_mudanca){
					}else if($row["hora_inicio_mudanca"] == $row["hora_fim_mudanca"]){	
						// termino no dia anterior
						//if(($janela->hora_inicio_mudanca == $h) && ($janela->hora_fim_mudanca == $h)&& ($min >= $janela->minuto_inicio_mudanca) 
						//&& ( $min <= $janela->minuto_fim_mudanca)){
						if(($row["hora_inicio_mudanca"] == $h) && ($row["hora_fim_mudanca"] == $h)&& ($min >= $row["minuto_inicio_mudanca"]) 
						&& ( $min <= $row["minuto_fim_mudanca"])){
							$seqAtividade = $atividadeRDM[$i][0];
							$rowJanelaPermitida = $row;
							$dentroDaJanela = true;
							$limiteValido = validarLimteAberturaRDM($atividadeRDM[$i],$row);
						}
					}
					
				} 
				
				if($dentroDaJanela && $limiteValido){
					break;
				}
				
			}// FIM WHILE
			
			if(!$dentroDaJanela && !$limiteValido){
					break;
			}
		
		} 
	}// FIM DO FOR
	
	return $dentroDaJanela;
	
}

function validarLimteAberturaRDM($atividadeRDM,$janela){	 
	require_once 'include/PHP/class/class.util.php';
	$limiteValido = true;
	 
	$util = new util();
	$dataHoraAtual = $util->GetlocalTimeStamp();
	$array_atual = split(" ",$dataHoraAtual);
	$data_array_atual = split("/",$array_atual[0]);
	$hora_array_atual = split(":",$array_atual[1]);	
	
	if(count ($data_array_atual)==1){
		$data_array_atual = split("-",$array_atual[0]);
	}
	$dia_atual = $data_array_atual[0];
	$mes_atual = $data_array_atual[1];
	$ano_atual = $data_array_atual[2];	 
	$h_atual   =   $hora_array_atual[0];
	$min_atual =  $hora_array_atual[1];
	
	
	$dataHoraLimite = $util->GetlocalTimeStamp();
	$array_limite = split(" ",$dataHoraLimite);
	$data_array_limite = split("/",$array_limite[0]);
	$hora_array_limite = split(":",$array_limite[1]);	
	
	if(count ($data_array_limite)==1){
		$data_array_limite = split("-",$array_limite[0]);
	}
	$dia_limite = $data_array_limite[0];
	$mes_limite = $data_array_limite[1];
	$ano_limite = $data_array_limite[2];	 
	$h_limite   = $janela["hora_inicio_mudanca"];
	$min_limite = $janela["minuto_inicio_mudanca"]; 	
	 
	
	//$dataExceucao = mktime($hr_exec,$min_exec, 0,$mes_exec,$dia_exec,$ano_exec);
	$dataExceucao = mktime($h_limite,$min_limite, 0,$mes_limite,$dia_limite,$ano_limite);
	///$dataHoraAtualMaisMinutos = mktime($h_teste,$min_teste+$janela->limite_para_rdm, 0,$mes_teste,$dia_teste,$ano_teste);
	$dataHoraAtualMaisMinutos = mktime($h_atual,$min_atual+$janela["limite_para_rdm"], 0,$mes_atual,$dia_atual,$ano_atual);
	 
	
	if ($dataHoraAtualMaisMinutos > $dataExceucao){					 
		$limiteValido = false;
		 
	} 
	
	return $limiteValido;
	
}



function getDiaDaSemana($dataExceucao){
	$diaDaSemana = date("D",$dataExceucao);
	$dia ="";
	switch($diaDaSemana){
		case "Sun":
			$dia =   "Dom";
		break;
	
		case "Mon":
			$dia = "Seg";
		break;
	
		case "Tue":
			$dia = "Ter";
		break;
	
		case "Wed":
			$dia = "Qua";
		break;
	
		case "Thu":
			$dia = "Qui";
		break;
	
		case "Fri":
			$dia = "Sex";
		break;
	
		case "Sat":
			$dia = "Sab";
		break;
	}//Fim do switch
	
	return $dia;
	
}
// ============================================================================================================
// REALIZAR CADASTRO/ENVIO DA RDM
// ============================================================================================================
//print "v_ACAO: ".$v_ACAO;
 
if($v_ACAO=="SELECIONAR_TEMPLATE_DE_RDM" && $v_SEQ_RM_TEMPLATE != ""){
	require_once 'include/PHP/class/class.rdm_template.php';
	require_once 'include/PHP/class/class.atividade_rdm_template.php';			 
	require_once 'include/PHP/class/class.atividade_rb_rdm_template.php';
	require_once 'include/PHP/class/class.equipe_ti.php';
	require_once 'include/PHP/class/class.servidor.php';
	require_once 'include/PHP/class/class.item_configuracao.php';
	
	$RDM_TEMPLATE = new rdm_template();
	$RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($v_SEQ_RM_TEMPLATE);
	$RDM_TEMPLATE->select($v_SEQ_RM_TEMPLATE);
	
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
	$ATIVIDADE_RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($v_SEQ_RM_TEMPLATE);
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
				// Sistemas de informa��o				
				$sistemas = new item_configuracao();				 
				$sistemas->select($row["seq_item_configuracao"]);				 
				
				$v_SEQ_ITEM_CONFIGURACAO_ATM = $row["seq_item_configuracao"];
				$v_NOM_ITEM_CONFIGURACAO_ATM = $sistemas->NOM_ITEM_CONFIGURACAO;
				$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = 2;
			} 	 

			$v_SEQ_EQUIPE_TI_ATM = $row["seq_equipe_ti"];
		
			 
			
			$v_DATA_EXECUCAO_ATM =  "";			
			$v_HORA_EXECUCAO_ATM = "";
			$_SESSION['ATIVIDADES_RDM'][] = array($_SESSION['ID_ATIVIDADE_RDM']++,$v_SEQ_ITEM_CONFIGURACAO_ATM ,
					$v_NOM_ITEM_CONFIGURACAO_ATM,$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM,$v_DESCRICAO_ATIVIDADE_ATM,
					$v_DATA_EXECUCAO_ATM,$v_HORA_EXECUCAO_ATM,$v_SEQ_EQUIPE_TI_ATM);
					
		} 
		 
		$v_SEQ_ITEM_CONFIGURACAO_ATM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = "";
		$v_DESCRICAO_ATIVIDADE_ATM = "";
		$v_DATA_EXECUCAO_ATM = "";
		$v_HORA_EXECUCAO_ATM = "";
		$v_SEQ_EQUIPE_TI_ATM = "";					
		 
	} 
	
	//ATIVIDADES DE ROLLBACK DA RDM
	$_SESSION['ID_ATIVIDADE_RB_RDM'] = 0;
 	$_SESSION['ATIVIDADES_RB_RDM'] = array();
 	
 	$ATIVIDADE_RB_RDM_TEMPLATE = new atividade_rb_rdm_template();
	$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($v_SEQ_RM_TEMPLATE);	 
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
				// Sistemas de informa��o				
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
	
	$v_SEQ_RM_TEMPLATE ="";
 
}else if($v_ACAO=="INCLUIR" || $v_ACAO=="ENVIAR"){
	$vMsgErro = "";
	
	$vRegrasVioladas = false;
	
	if(count($_SESSION['ATIVIDADES_RDM'])==0){
		$vRegrasVioladas = true;
		$vMsgErro = "Informe pelo menos uma atividade para RDM!";
	}else if(count($_SESSION['ATIVIDADES_RB_RDM'])==0){
		$vRegrasVioladas = true;
		$vMsgErro = "Informe pelo menos uma atividade de Rollback para RDM!";
	} else if(count($_SESSION['CHAMADOS_RDM'])==0){
		$vRegrasVioladas = true;
		$vMsgErro = "Informe pelo menos um chamado para RDM!";
	} 
	
	 for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){
				 
			if(($_SESSION['ATIVIDADES_RDM'][$i][5]==null || $_SESSION['ATIVIDADES_RDM'][$i][5]=="")||
				($_SESSION['ATIVIDADES_RDM'][$i][6]==null || $_SESSION['ATIVIDADES_RDM'][$i][6]=="")){
					$vRegrasVioladas = true;
					$vMsgErro = "Informe a Data/Hora de execu��o para todas as atividades da RDM!";
			} 				 
	}
	
	if(!$vRegrasVioladas){
		require_once 'include/PHP/class/class.rdm.php';
		require_once 'include/PHP/class/class.situacao_rdm.php';		 
		require_once 'include/PHP/class/class.atividade_rdm.php';
		require_once 'include/PHP/class/class.chamado_rdm.php';
		require_once 'include/PHP/class/class.atividade_rb_rdm.php';
		require_once 'include/PHP/class/class.util.php';
		require_once 'include/PHP/class/class.rdm_template.php';
		require_once 'include/PHP/class/class.atividade_rdm_template.php';			 
		require_once 'include/PHP/class/class.atividade_rb_rdm_template.php';
			
		$RDM = new rdm();
		$situacaoRDM = new situacao_rdm();		 
		$atividadeRDM = new atividade_rdm();
		$atividadeRBRDM = new atividade_rb_rdm();
		$chamado_rdm = new chamado_rdm();
		
		$RDM_TEMPLATE= new rdm_template();
		$ATIVIDADE_RDM_TEMPLATE = new atividade_rdm_template();
		$ATIVIDADE_RB_RDM_TEMPLATE = new atividade_rb_rdm_template();
		
		// Preenchendo a RDM com os campos do formul�rio
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$v_NUM_MATRICULA_SOLICITANTE = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_SOLICITANTE);
		$RDM->setNUM_MATRICULA_SOLICITANTE($v_NUM_MATRICULA_SOLICITANTE);
				
		$RDM->setTITULO($v_TITULO);
		$RDM->setJUSTIFICATIVA($v_JUSTIFICATIVA);
		$RDM->setIMPACTO_NAO_EXECUTAR($v_IMPACTO_NAO_EXECUTAR);
		$RDM->setNOME_RESP_CHECKLIST($v_NOME_RESP_CHECKLIST);
		$RDM->setEMAIL_RESP_CHECKLIST($v_EMAIL_REP_CHECKLIST);
		$RDM->setDDD_TELEFONE_RESP_CHECKLIST($v_DDD_TELEFONE_RESP_CHECKLIST);
		$RDM->setNUMERO_TELEFONE_RESP_CHECKLIST($v_NUMERO_TELEFONE_RESP_CHECKLIST);
		
		if($v_ACAO=="INCLUIR"){
			$RDM->setSITUACAO_ATUAL($situacaoRDM->CRIADA);
		}else if($v_ACAO=="ENVIAR"){
			$RDM->setSITUACAO_ATUAL($situacaoRDM->AGUARDANDO_APROVACAO);
		}
		
		
		// VERIFICANDO O TIPO DA RDM
//		if(validarJanelaMudanca($_SESSION['ATIVIDADES_RDM']) &&
//			validarLimteAberturaRDM($_SESSION['ATIVIDADES_RDM'])){
		if(validarJanelaMudanca($_SESSION['ATIVIDADES_RDM'])){
			$RDM->setTIPO($RDM->NORMAL);
		}else{
			$RDM->setTIPO($RDM->EMERGENCIAL);
		} 
		
		$RDM->setOBSERVACAO($v_OBSERVACAO);
		
		//A data prevista para execua��o inicial � a mesma da primeira atividade da RDM
		
		$data = split("/",$_SESSION['ATIVIDADES_RDM'][0][5]);
		$d = $data[0];
		$m = $data[1];
		$a = $data[2];
		
		$hora = split(":",$_SESSION['ATIVIDADES_RDM'][0][6]);
		$hr = $hora[0];
		$minuto =  $hora[1];			 
		$dataHoraExecucao = mktime($hr,$minuto, 0,$m,$d,$a);
		
		$util = new util();
		$dataHoraAtual = $util->GetlocalTimeStamp();
		
		$RDM->setDATA_HORA_ABERTURA($dataHoraAtual);
		$RDM->setDATA_HORA_ULTIMA_ATUALIZACAO($dataHoraAtual);		
		$RDM->setDATA_HORA_PREVISTA_EXECUCAO(date("Y-m-d H:i:s",$dataHoraExecucao));
		
		$RDM->insert();
		// C�digo inserido: $RDM->SEQ_RDM
		
		/* ====== TEMPLATES DE RDM ======  */
		
		
		if($v_GERAR_TEMPLATE == "1"){
			 
			$RDM_TEMPLATE->setSEQ_RDM_ORIGEM($RDM->SEQ_RDM);
			$RDM_TEMPLATE->setTITULO($RDM->TITULO);
			$RDM_TEMPLATE->setJUSTIFICATIVA($RDM->JUSTIFICATIVA);
			$RDM_TEMPLATE->setIMPACTO_NAO_EXECUTAR($RDM->IMPACTO_NAO_EXECUTAR);
			$RDM_TEMPLATE->setOBSERVACAO($RDM->OBSERVACAO);
			$RDM_TEMPLATE->setNOME_RESP_CHECKLIST($RDM->NOME_RESP_CHECKLIST);
			$RDM_TEMPLATE->setEMAIL_RESP_CHECKLIST($RDM->EMAIL_RESP_CHECKLIST);
			$RDM_TEMPLATE->setDDD_TELEFONE_RESP_CHECKLIST($RDM->DDD_TELEFONE_RESP_CHECKLIST);
			$RDM_TEMPLATE->setNUMERO_TELEFONE_RESP_CHECKLIST($RDM->NUMERO_TELEFONE_RESP_CHECKLIST);			
			$RDM_TEMPLATE->insert();
		}
		/* ====== TEMPLATES DE RDM ======  */
		
		if($RDM->error == ""){
			
			// ===== WOrkFlow=====	
			if($v_ACAO=="INCLUIR"){
				$situacaoRDM->setSEQ_RDM($RDM->SEQ_RDM);
				$situacaoRDM->setSITUACAO($situacaoRDM->CRIADA);
				$situacaoRDM->setOBSERVACAO("Abertura");
				$situacaoRDM->setDATA_HORA($dataHoraAtual);
				$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);			
				$situacaoRDM->insert();		
			}else if($v_ACAO=="ENVIAR"){
				$situacaoRDM->setSEQ_RDM($RDM->SEQ_RDM);
				$situacaoRDM->setSITUACAO($situacaoRDM->AGUARDANDO_APROVACAO);
				$situacaoRDM->setOBSERVACAO("Enviada para aprova��o");
				$situacaoRDM->setDATA_HORA($dataHoraAtual);	
				$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
				$situacaoRDM->insert();				 
			}	
			
			// ===== INCLUIR ATIVIDADES DA RDM =====			 	
		    for ($i = 0; $i < count($_SESSION['ATIVIDADES_RDM']); $i++){
				$atividadeRDM->setSEQ_RDM($RDM->SEQ_RDM);
				$atividadeRDM->setDESCRICAO($_SESSION['ATIVIDADES_RDM'][$i][4]);
				
				if($_SESSION['ATIVIDADES_RDM'][$i][3]==1){//servidores
					$atividadeRDM->setSEQ_SERVIDOR($_SESSION['ATIVIDADES_RDM'][$i][1]);				
				}else if($_SESSION['ATIVIDADES_RDM'][$i][3]==2){//sistemas
					$atividadeRDM->setSEQ_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RDM'][$i][1]);
				} 
				$atividadeRDM->setSEQ_TIPO_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RDM'][$i][3]);
				$atividadeRDM->setORDEM($i+1);
				$atividadeRDM->setSITUACAO($atividadeRDM->NAO_INICIADA);
				
				
				$data_array = split("/",$_SESSION['ATIVIDADES_RDM'][$i][5]);
				$dia = $data_array[0];
				$mes = $data_array[1];
				$ano = $data_array[2];
				
				$hora_array = split(":",$_SESSION['ATIVIDADES_RDM'][$i][6]);
				$h = $hora_array[0];
				$min =  $hora_array[1];			 
				$dataHoraExceucao = mktime($h,$min, 0,$mes,$dia,$ano);
				
				$atividadeRDM->setDATA_HORA_PREVISTA_EXECUCAO(date("Y-m-d H:i:s",$dataHoraExceucao));
				$atividadeRDM->setSEQ_EQUIPE_TI($_SESSION['ATIVIDADES_RDM'][$i][7]);
				 
				$atividadeRDM->insert(); 
				
				
				/* ====== TEMPLATES DE RDM ======  */
				if($v_GERAR_TEMPLATE == "1"){ 
					$ATIVIDADE_RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($RDM_TEMPLATE->getSEQ_RDM_TEMPLATE());
					$ATIVIDADE_RDM_TEMPLATE->setDESCRICAO($atividadeRDM->getDESCRICAO());
					$ATIVIDADE_RDM_TEMPLATE->setORDEM($atividadeRDM->getORDEM());
					$ATIVIDADE_RDM_TEMPLATE->setSEQ_ITEM_CONFIGURACAO($atividadeRDM->getSEQ_ITEM_CONFIGURACAO());					
					$ATIVIDADE_RDM_TEMPLATE->setSEQ_SERVIDOR($atividadeRDM->getSEQ_SERVIDOR());
					$ATIVIDADE_RDM_TEMPLATE->setSEQ_TIPO_ITEM_CONFIGURACAO($atividadeRDM->getSEQ_TIPO_ITEM_CONFIGURACAO());
					$ATIVIDADE_RDM_TEMPLATE->setSEQ_EQUIPE_TI($atividadeRDM->getSEQ_EQUIPE_TI());
					$ATIVIDADE_RDM_TEMPLATE->insert(); 
				}
				/* ====== TEMPLATES DE RDM ======  */
				
				$atividadeRDM->setDESCRICAO(NULL);
				$atividadeRDM->setSEQ_SERVIDOR(NULL);	
				$atividadeRDM->setSEQ_ITEM_CONFIGURACAO(NULL);
				$atividadeRDM->setSEQ_TIPO_ITEM_CONFIGURACAO(NULL);
				$atividadeRDM->setORDEM(NULL);
				$atividadeRDM->setSITUACAO(NULL); 
				$atividadeRDM->setDATA_HORA_PREVISTA_EXECUCAO(NULL);
				$atividadeRDM->setSEQ_EQUIPE_TI(NULL);
				
		    }
		    
			// ===== INCLUIR ATIVIDADES DE ROLLBACK DA RDM ===== 		
		    for ($i = 0; $i < count($_SESSION['ATIVIDADES_RB_RDM']); $i++){
				$atividadeRBRDM->setSEQ_RDM($RDM->SEQ_RDM);
				$atividadeRBRDM->setDESCRICAO($_SESSION['ATIVIDADES_RB_RDM'][$i][4]);
				
				if($_SESSION['ATIVIDADES_RB_RDM'][$i][3]==1){//servidores
					$atividadeRBRDM->setSEQ_SERVIDOR($_SESSION['ATIVIDADES_RB_RDM'][$i][1]);				
				}else if($_SESSION['ATIVIDADES_RB_RDM'][$i][3]==2){//sistemas
					$atividadeRBRDM->setSEQ_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RB_RDM'][$i][1]);
				} 
				$atividadeRBRDM->setSEQ_TIPO_ITEM_CONFIGURACAO($_SESSION['ATIVIDADES_RDM'][$i][3]);
				$atividadeRBRDM->setORDEM($_SESSION['ATIVIDADES_RB_RDM'][$i][5]);
				$atividadeRBRDM->setSITUACAO($atividadeRDM->NAO_INICIADA);			  
				$atividadeRBRDM->setSEQ_EQUIPE_TI($_SESSION['ATIVIDADES_RB_RDM'][$i][6]);
				 
				$atividadeRBRDM->insert();
				
				/* ====== TEMPLATES DE RDM ======  */
				if($v_GERAR_TEMPLATE == "1"){ 
					$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_RDM_TEMPLATE($RDM_TEMPLATE->getSEQ_RDM_TEMPLATE());
					$ATIVIDADE_RB_RDM_TEMPLATE->setDESCRICAO($atividadeRBRDM->getDESCRICAO());
					$ATIVIDADE_RB_RDM_TEMPLATE->setORDEM($atividadeRBRDM->getORDEM());
					$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_ITEM_CONFIGURACAO($atividadeRBRDM->getSEQ_ITEM_CONFIGURACAO());					
					$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_SERVIDOR($atividadeRBRDM->getSEQ_SERVIDOR());
					$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_TIPO_ITEM_CONFIGURACAO($atividadeRBRDM->getSEQ_TIPO_ITEM_CONFIGURACAO());
					$ATIVIDADE_RB_RDM_TEMPLATE->setSEQ_EQUIPE_TI($atividadeRBRDM->getSEQ_EQUIPE_TI());
					$ATIVIDADE_RB_RDM_TEMPLATE->insert(); 
				}
				/* ====== TEMPLATES DE RDM ======  */
				
				$atividadeRBRDM->setDESCRICAO(NULL);
				$atividadeRBRDM->setSEQ_SERVIDOR(NULL);				
			    $atividadeRBRDM->setSEQ_ITEM_CONFIGURACAO(NULL); 
				$atividadeRBRDM->setSEQ_TIPO_ITEM_CONFIGURACAO(NULL);
				$atividadeRBRDM->setORDEM(NULL);
				$atividadeRBRDM->setSITUACAO(NULL);			  
				$atividadeRBRDM->setSEQ_EQUIPE_TI(NULL);
		    }
		    
		    // ===== INCLUIR CHAMADOS ASSOCIADOS ===== 		
		 	for ($i = 0; $i < count($_SESSION['CHAMADOS_RDM']); $i++){
				$chamado_rdm->setSEQ_RDM($RDM->SEQ_RDM);
				$chamado_rdm->setSEQ_CHAMADO($_SESSION['CHAMADOS_RDM'][$i][0]);	 
				$chamado_rdm->insert();
		    }
		    
			unset($_SESSION['ATIVIDADES_RDM']);		
			unset($_SESSION['ATIVIDADES_RB_RDM']);		
			unset($_SESSION['ID_ATIVIDADE_RDM']);		
			unset($_SESSION['ID_ATIVIDADE_RB_RDM']);
			unset($_SESSION['CHAMADOS_RDM']);				
			
			
			if($v_ACAO=="INCLUIR"){
				 
				// Enviar e-mail para o solicitante
				require_once 'include/PHP/class/class.rdm_email.php';
				$rdm_email = new rdm_email($pagina,$RDM);
				$rdm_email->sendEmailAberturaRDM();
				
				 
			}else if($v_ACAO=="ENVIAR"){				
				 	 
				// Enviar e-mail para o solicitante
				require_once 'include/PHP/class/class.rdm_email.php';
				$rdm_email = new rdm_email($pagina,$RDM);
				$rdm_email->sendEmailRDMEnviadaParaAprovacao();		
				 
			}
			
			
			
			$pagina->redirectTo("RDMConfirmacao.php?v_SEQ_RDM=$RDM->SEQ_RDM&mensagemErro=$vMsgErro");
		}//fim do teste de erro da RDM
		
	}
	
} 
// ============================================================================================================
// Configura��es AJAX
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
// Configura��o da p�g�na
// ============================================================================================================

$pagina->SettituloCabecalho("Abrir Requisi��o de mudan�a - RDM"); // Indica o t�tulo do cabe�alho da p�gina
$pagina->method = "post";
$pagina->MontaCabecalho(1);
$pagina->LinhaVazia(1);
print $pagina->CampoHidden("flag", "1");


// ============================================================================================================
// Configura��es AJAX JAVASCRIPTS
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
			// Adicionar resultado ao formul�rio
			document.form.v_NUM_MATRICULA_SOLICITANTE_REAL.value = v_NUM_MATRICULA_SOLICITANTE;
			//window.dados_solicitante.innerHTML = "Nome: <b>"+v_NOME+"</b> - Ramal: <b>"+v_TELEFONE+"</b>";
			document.getElementById("dados_solicitante").innerHTML = "Nome: <b>"+v_NOME+"</b> - Ramal: <b>"+v_TELEFONE+"</b>";
		}else{
			alert("Pessoa n�o encontrada. Clique na imagem de lupa para efetuar uma pesquisa.");
			//window.dados_solicitante.innerHTML = "Preencha este campo com a matr�cula do solicitante.";
			document.getElementById("dados_solicitante").innerHTML = "Preencha este campo com a matr�cula do solicitante.";
			document.form.v_NUM_MATRICULA_SOLICITANTE.value = "";
		}
	}

	// =======================================================================
	// Controle de eventos
	// =======================================================================
	// Gest�o de Eventos
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
	// Flag de valida��o da sa�da do fomul�rio
	var validarSaida = true;
	// Exit Alert
	function exitAlert(e){
		// default warning message
		var msg = "Confirma a sa�da? Esta a��o ocasionar� a perda das informa��es j� preenchidas.";

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
			 	alert("Preencha o campo Item de configura��o");
			 	return false;
		 }
		 if(document.form.v_DESCRICAO_ATIVIDADE_ATM.value == ""){
			 	alert("Preencha o campo Descri��o");
			 	return false;
		 }
		 if(document.form.v_DATA_EXECUCAO_ATM.value == ""){
			 	alert("Preencha o campo Data de execu��o");
			 	return false;
		 }
		 if(document.form.v_HORA_EXECUCAO_ATM.value == ""){
			 	alert("Preencha o campo Hora de execu��o");
			 	return false;
		 }
		 if(document.form.v_SEQ_EQUIPE_TI_ATM.value == ""){
			 	alert("Preencha o campo Executor");
			 	return false;
		 }

		 if(!verificarDataMenorQueHoraAtual(document.form.v_DATA_EXECUCAO_ATM.value,document.form.v_HORA_EXECUCAO_ATM.value)){
				return false;
		 }
	 	 
		 //return true;
  
	}
	function fValidaFormAtividadesRBRDM(v_ACAO_ATRBM){
		 document.form.v_ACAO_ATRBM.value = v_ACAO_ATRBM;
		 document.form.v_LIMPAR_SESSAO.value = 'NAO';
		 if(document.form.v_NOM_ITEM_CONFIGURACAO_ATRBM.value == ""){
			 	alert("Preencha o campo Item de configura��o");
			 	return false;
		 }
		 if(document.form.v_DESCRICAO_ATIVIDADE_ATRBM.value == ""){
			 	alert("Preencha o campo Descri��o");
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

	function AnexaNovoArquivo($ID){
		if(document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).value != ""){
			document.getElementById("Newfile"+$ID).style.display = "none";
			$novo = $ID + 1;
			document.getElementById("file"+$novo).style.display = "block";
			document.getElementById("Newfile"+$novo).style.display = "block";
		}else{
			alert("� necess�rio anexar um arquivo antes de adionar um novo.");
			document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).focus();
		}
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
		document.form.v_DATA_EXECUCAO_ATM.value = "";
		document.form.v_HORA_EXECUCAO_ATM.value = "";
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
	function fAddChamados(chamadosSelecionados){
		//alert('fAddChamados');
		document.form.v_ACAO.value = "INCLUIR_CHAMADO";
		document.form.v_LIMPAR_SESSAO.value = 'NAO';
		document.form.v_CHAMADOS_SELECIONADOS.value = chamadosSelecionados;
		document.form.submit();
		//return false;
		 
	}


	function fSelTemplateRDM(idTemplate){
		//alert('fSelTemplateRDM');
		document.form.v_ACAO.value = "SELECIONAR_TEMPLATE_DE_RDM";
		document.form.v_LIMPAR_SESSAO.value = 'NAO';
		document.form.v_SEQ_RM_TEMPLATE.value = idTemplate;
		document.form.submit();
		//return false;
		 
	}
	
	
	 
</script>
<?	


// ============================================================================================================
// Dados do solicitante
// ============================================================================================================
$pagina->AbreTabelaPadrao("center", "100%", "border=0 cellspacing=0 cellpading=0");
//$pagina->LinhaCampoFormularioColspanDestaque("Dados do Solicitante", 2);

if($v_LIMPAR_SESSAO == "" ){
	unset($_SESSION['ATIVIDADES_RDM']);		
	unset($_SESSION['ATIVIDADES_RB_RDM']);		
	unset($_SESSION['ID_ATIVIDADE_RDM']);		
	unset($_SESSION['ID_ATIVIDADE_RB_RDM']);	
	unset($_SESSION['CHAMADOS_RDM']);				
			
}
$v_LIMPAR_SESSAO = "NAO";

print $pagina->CampoHidden2("v_LIMPAR_SESSAO", $v_LIMPAR_SESSAO);
print $pagina->CampoHidden2("v_SEQ_RM_TEMPLATE", $v_SEQ_RM_TEMPLATE);							  								  
// ============================================================================================================
// Informa��es Gerais
// ============================================================================================================
$pagina->LinhaCampoFormularioColspanDestaque("Informa��es Gerais &nbsp;".$pagina->ButtonSelecionarTemplateDeRDM("fSelTemplateRDM"), 2);

//Solicitante da RDM
print $pagina->CampoHidden("v_NUM_MATRICULA_SOLICITANTE_REAL", "");
$pagina->LinhaCampoFormulario("Solicitante:", "right", "N",
								  $pagina->CampoTexto("v_NUM_MATRICULA_SOLICITANTE", "N", "Solicitante" , "20", "20", $_SESSION["NOM_LOGIN_REDE"], "onBlur=\"do_ValidarSolicitante()\"").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_SOLICITANTE").
								  "&nbsp;
								  <span id=\"dados_solicitante\">
								  	Preencha este campo com a matr�cula do solicitante.
								  </span>
								  "
								  , "left", "id=".$pagina->GetIdTable());	

		 					  
//Titulo da RDM
$pagina->LinhaCampoFormulario("T�tulo:", "right", "S", $pagina->CampoTexto("v_TITULO", "S", "T�tulo", "80", "80", "$v_TITULO"), "left", "id=".$pagina->GetIdTable());
 
//RAZO DA RDM								  
$pagina->LinhaCampoFormulario("Justificativa:", "right", "S", $pagina->CampoTexto("v_JUSTIFICATIVA", "S", "Justificativa", "80", "80", "$v_JUSTIFICATIVA"), "left", "id=".$pagina->GetIdTable());
								  
//Impacto DA RDM								  
$pagina->LinhaCampoFormulario("Impacto de n�o executar:", "right", "S", $pagina->CampoTexto("v_IMPACTO_NAO_EXECUTAR", "S", "Impacto de n�o executar", "80", "80", "$v_IMPACTO_NAO_EXECUTAR"), "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Observa��es:", "right", "N", 
 $pagina->CampoTextArea("v_OBSERVACAO", "N", "Observa��es", "99", "3", "$v_OBSERVACAO", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
, "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Gerar Template:", "right", "N", $pagina->CampoCheckboxSimples("v_GERAR_TEMPLATE", "1", iif($v_GERAR_TEMPLATE=="1", "checked", ""),""), "left", "id=".$pagina->GetIdTable());
							  
$pagina->FechaTabelaPadrao();


// ============================================================================================================
// CHAMADOS ASSOCIADOS
// ============================================================================================================
if (!isset($_SESSION['CHAMADOS_RDM'])){ 
 	$_SESSION['CHAMADOS_RDM'] = array();
}
//unset($_SESSION['CHAMADOS_RDM']);

if($v_ACAO == "INCLUIR_CHAMADO"){
	$v_OK = true;	 
	$vMsgErro = "";
	$v_ARRAY_CHAMADOS_SELECIONADOS = split("#_#",$v_CHAMADOS_SELECIONADOS);
	$v_ARRAY_DADOS_CHAMADO;
	$v_CHAMADOS_JA_CADASTRADOS="";
	
	for ($i = 0; $i < count($v_ARRAY_CHAMADOS_SELECIONADOS); $i++){
		$v_OK = true;	 
		//print "v_ARRAY_CHAMADOS_SELECIONADOS[".$i."]: ".$v_ARRAY_CHAMADOS_SELECIONADOS[$i];
		//print "<br>";
		
		$v_ARRAY_DADOS_CHAMADO  = split("<_>",$v_ARRAY_CHAMADOS_SELECIONADOS[$i]);
		//print "v_ARRAY_DADOS_CHAMADO".$v_ARRAY_DADOS_CHAMADO[0]."-".$v_ARRAY_DADOS_CHAMADO[1];
		//print "<br>";
		
		if(count($_SESSION['CHAMADOS_RDM'])>0){
			
			
			for ($X = 0; $X < count($_SESSION['CHAMADOS_RDM']); $X++){				
				if ($_SESSION['CHAMADOS_RDM'][$X][0] == $v_ARRAY_DADOS_CHAMADO[0]){
		 				$v_OK = false;
		 				break;
		 		} 
			}
			
			if($v_OK){
				$_SESSION['CHAMADOS_RDM'][] = array($v_ARRAY_DADOS_CHAMADO[0],$v_ARRAY_DADOS_CHAMADO[1],$v_ARRAY_DADOS_CHAMADO[2]);
			}else{
				
				if($vMsgErro==""){
 					$vMsgErro	.= " O(s) seguinte(s) chamado(s) n�o foram associados, pois j� o foram anteriormente: ";	
 					$v_CHAMADOS_JA_CADASTRADOS .= $v_ARRAY_DADOS_CHAMADO[0];
 				}else{
 					$v_CHAMADOS_JA_CADASTRADOS .= ",".$v_ARRAY_DADOS_CHAMADO[0];
 				}
			}
		}else{
			$_SESSION['CHAMADOS_RDM'][] = array($v_ARRAY_DADOS_CHAMADO[0],$v_ARRAY_DADOS_CHAMADO[1],$v_ARRAY_DADOS_CHAMADO[2]);
		}
	}
	
	$v_ACAO ="";
	$v_CHAMADOS_SELECIONADOS="";
	if($vMsgErro!=""){
		$vMsgErro	.= $v_CHAMADOS_JA_CADASTRADOS.".";
	}
}else if($v_ACAO == "EXCLUIR_CHAMADO"){
	for ($i = 0; $i <= count($_SESSION['CHAMADOS_RDM']); $i++){ 		 
	 	if ($_SESSION['CHAMADOS_RDM'][$i][0] == $v_SEQ_CHAMADO){
	 		unset($_SESSION['CHAMADOS_RDM'][$i]);			 
	 		//break;
	 	}
	 }		
	   
	 $v_SEQ_CHAMADO = "";
	 $v_ACAO ="";
}

$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");
print $pagina->CampoHidden("v_CHAMADOS_SELECIONADOS", $v_CHAMADOS_SELECIONADOS);
print $pagina->CampoHidden("v_SEQ_CHAMADO", $v_SEQ_CHAMADO);


//$pagina->LinhaCampoFormulario("v_CHAMADOS_SELECIONADOS:", "right", "N", $pagina->CampoTexto("v_CHAMADOS_SELECIONADOS", "N", "v_CHAMADOS_SELECIONADOS", "80", "80", "$v_CHAMADOS_SELECIONADOS"), "left", "id=".$pagina->GetIdTable());

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisChamadosRDM\"  cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormularioColspanDestaque("Adicionar Chamados  &nbsp;".$pagina->ButtonProcuraChamados("fAddChamados"), 2);
$pagina->FechaTabelaPadrao();


// Inicio do grid de chamados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("N�mero do Chamado", "15%");
$header[] = array("Atividade", "40%");
$header[] = array("Descri��o", "40%");
 

if(count($_SESSION['CHAMADOS_RDM']) == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum chamado associado � RDM", count($header));
}else{
	 
//	foreach ($_SESSION['CHAMADOS_RDM'] as $key => $row) {
//	    $data[$key] = $row[5];
//	    $hora[$key] = $row[6];
//	}
//	
//	array_multisort($data, SORT_ASC, $hora, SORT_ASC, $_SESSION['CHAMADOS_RDM']);
	 
	$corpo = array();
	 
	$pagina->LinhaHeaderTabelaResultado("Chamados associados � RDM", $header);

	for ($i = 0; $i < count($_SESSION['CHAMADOS_RDM']); $i++){
//	 	$valor ="<a href=\"javascript:document.form.v_ACAO_ATM.value='ALTERAR_ATM';
//		document.form.v_SEQ_CHAMADO.value=".$_SESSION['CHAMADOS_RDM'][$i][0].";document.form.submit();\">
//		<img src=\"imagens/alterar.gif\" alt=\"Alterar\" border=\"0\"></a>";
		
		$valor.="<a href=\"#\" onclick=\"document.form.v_ACAO.value='EXCLUIR_CHAMADO';
		document.form.v_SEQ_CHAMADO.value=".$_SESSION['CHAMADOS_RDM'][$i][0].";document.form.submit();\" ><img src=\"imagens/excluir.gif\" alt=\"Excluir\" border=\"0\"></a>";
	
		$corpo[] = array("center", "campo", $valor);
		
		$corpo[] = array("left", "campo", $_SESSION['CHAMADOS_RDM'][$i][0]);
		$corpo[] = array("left", "campo", $_SESSION['CHAMADOS_RDM'][$i][1]);
		$corpo[] = array("left", "campo", $_SESSION['CHAMADOS_RDM'][$i][2]);
		
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
		$valor = "";
	}
}
 
$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader");


// ============================================================================================================
// FORMULARIO DE ATIVIDADES DA RDM
// ============================================================================================================
 
$v_EXIBIR_ATM = "";
//$vMsgErro = "";
// inicia a sess�o
//session_start();
 
if (!isset($_SESSION['ID_ATIVIDADE_RDM']) && !isset($_SESSION['ATIVIDADES_RDM'])){
	//print "inicializa��o da sess�o <br>";
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
	 		$_SESSION['ATIVIDADES_RDM'][$i][5] == $v_DATA_EXECUCAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][6] == $v_HORA_EXECUCAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][7] == $v_SEQ_EQUIPE_TI_ATM){
	 			$vMsgErro		 = "Atividade j� cadastrada!";
	 			$v_EXIBIR_ATM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RDM'][$i][5] == $v_DATA_EXECUCAO_ATM &&
	 			  $_SESSION['ATIVIDADES_RDM'][$i][6] == $v_HORA_EXECUCAO_ATM ){
	 			$vMsgErro	 = "J� existe uma atividade para esta data e hora!";
	 			$v_EXIBIR_ATM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}
	 }	
	 
	if($v_OK){
		$_SESSION['ATIVIDADES_RDM'][] = array($_SESSION['ID_ATIVIDADE_RDM']++,$v_SEQ_ITEM_CONFIGURACAO_ATM ,
					$v_NOM_ITEM_CONFIGURACAO_ATM,$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM,$v_DESCRICAO_ATIVIDADE_ATM,
					$v_DATA_EXECUCAO_ATM,$v_HORA_EXECUCAO_ATM,$v_SEQ_EQUIPE_TI_ATM);
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
	 		$v_DATA_EXECUCAO_ATM =  $_SESSION['ATIVIDADES_RDM'][$i][5];
	 		$v_HORA_EXECUCAO_ATM = $_SESSION['ATIVIDADES_RDM'][$i][6];
	 		$v_SEQ_EQUIPE_TI_ATM = $_SESSION['ATIVIDADES_RDM'][$i][7];	 		 
			
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
	 			$vMsgErro		 = "Atividade j� cadastrada!";
	 			$v_EXIBIR_ATM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RDM'][$i][0] != $v_SEQ_ID_ATM &&
	 			  $_SESSION['ATIVIDADES_RDM'][$i][5] == $v_DATA_EXECUCAO_ATM &&
	 			  $_SESSION['ATIVIDADES_RDM'][$i][6] == $v_HORA_EXECUCAO_ATM ){
	 			$vMsgErro	 = "J� existe uma atividade para esta data e hora!";
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
		 		$_SESSION['ATIVIDADES_RDM'][$i][5] = $v_DATA_EXECUCAO_ATM;
		 		$_SESSION['ATIVIDADES_RDM'][$i][6] = $v_HORA_EXECUCAO_ATM;
		 		$_SESSION['ATIVIDADES_RDM'][$i][7] = $v_SEQ_EQUIPE_TI_ATM; 
		 		break;
		 	}
		 }	
		$v_SEQ_ID_ATM = "";		
		$v_SEQ_ITEM_CONFIGURACAO_ATM = "";
		$v_NOM_ITEM_CONFIGURACAO_ATM = "";
		$v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM = "";
		$v_DESCRICAO_ATIVIDADE_ATM = "";
		$v_DATA_EXECUCAO_ATM = "";
		$v_HORA_EXECUCAO_ATM = "";
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

// Mostrar ou n�o os par�metros
//if($flag == ""){ // Mostrar par�metros
//	$MaisAtividadesRDM = "style=\"display: none;\" ";
//	$MenosAtividadesRDM = "";
//	$tabelaAtividadesRDM = "";
//}else{ // N�o mostrar par�metros
//	$MaisAtividadesRDM = "style=\"display: none;\" ";
//	$MenosAtividadesRDM = "";
//	$tabelaAtividadesRDM = "style=\"display: none;\" ";
//}

if($v_EXIBIR_ATM == "EXIBIR"){//mostrar
	$MaisAtividadesRDM = "style=\"display: none;\" ";
	$MenosAtividadesRDM = "";
	$tabelaAtividadesRDM = " ";
}else{//n�o mostrar
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

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaAtividadesRDM\" $tabelaAtividadesRDM");

print $pagina->CampoHidden("v_ACAO_ATM", $v_ACAO_ATM);
print $pagina->CampoHidden("v_SEQ_ID_ATM", $v_SEQ_ID_ATM);
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO_ATM", $v_SEQ_ITEM_CONFIGURACAO_ATM);
print $pagina->CampoHidden("v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM", $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM);

$pagina->LinhaCampoFormulario("Item configuracao:", "right", "S", 
									   $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO_ATM", "N", "" , "60", "60", "$v_NOM_ITEM_CONFIGURACAO_ATM", "readonly").
									   $pagina->ButtonProcuraItemConfiguracao("v_NOM_ITEM_CONFIGURACAO_ATM", "v_SEQ_ITEM_CONFIGURACAO_ATM","v_SEQ_TIPO_ITEM_CONFIGURACAO_ATM")
									, "left", "id=".$pagina->GetIdTable());
// Desci��o do chamado
$pagina->LinhaCampoFormulario("Descri��o:", "right", "S",
								  $pagina->CampoTextArea("v_DESCRICAO_ATIVIDADE_ATM", "N", "Descri��o", "100", "5", "$v_DESCRICAO_ATIVIDADE_ATM", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
								  , "left", "id=".$pagina->GetIdTable());	

$pagina->LinhaCampoFormulario("Data/Hora de execu��o:", "right", "S",
			$pagina->CampoData("v_DATA_EXECUCAO_ATM", "N", "Data de execu��o ", $v_DATA_EXECUCAO_ATM,"")
			." ". $pagina->CampoHora("v_HORA_EXECUCAO_ATM", "N", "Hora de execu��o ", $v_HORA_EXECUCAO_ATM,"")			 
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
$header[] = array("Descri��o", "");
$header[] = array("Data/Hora de execu��o", "");
$header[] = array("Executor", "");

if(count($_SESSION['ATIVIDADES_RDM']) == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro de atividade de RDM informado", count($header));
}else{
	//print "ANTES DA ORDENACAO <br>";
	//print_r($_SESSION['ATIVIDADES_RDM']);
	//ORDENAR ARRAY PELA DATA HORA
	foreach ($_SESSION['ATIVIDADES_RDM'] as $key => $row) {
	    $data[$key] = $row[5];
	    $hora[$key] = $row[6];
	}
	array_multisort($data, SORT_ASC, $hora, SORT_ASC, $_SESSION['ATIVIDADES_RDM']);
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
		$corpo[] = array("left", "campo", $_SESSION['ATIVIDADES_RDM'][$i][5]." ".$_SESSION['ATIVIDADES_RDM'][$i][6]);
		
		$executor= new equipe_ti();		 
		$executor->select($_SESSION['ATIVIDADES_RDM'][$i][7]);
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
// inicia a sess�o
//session_start();
 
if (!isset($_SESSION['ID_ATIVIDADE_RB_RDM']) && !isset($_SESSION['ATIVIDADES_RB_RDM'])){
	//print "inicializa��o da sess�o <br>";
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
	 			$vMsgErro		 = "Atividade j� cadastrada!";
	 			$v_EXIBIR_ATRBM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RB_RDM'][$i][5] == $v_ORDEM_ATRBM){
	 			$vMsgErro	 = "J� existe uma atividade para esta ordem!";
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
	 			$vMsgErro		 = "Atividade j� cadastrada!";
	 			$v_EXIBIR_ATRBM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RB_RDM'][$i][0] != $v_SEQ_ID_ATRBM &&
	 			  $_SESSION['ATIVIDADES_RB_RDM'][$i][5] == $v_ORDEM_ATRBM  ){
	 			$vMsgErro	 = "J� existe uma atividade para esta ordem!";
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

// Mostrar ou n�o os par�metros
//if($flag == ""){ // Mostrar par�metros
//	$MaisAtividadesRDM = "style=\"display: none;\" ";
//	$MenosAtividadesRDM = "";
//	$tabelaAtividadesRDM = "";
//}else{ // N�o mostrar par�metros
//	$MaisAtividadesRDM = "style=\"display: none;\" ";
//	$MenosAtividadesRDM = "";
//	$tabelaAtividadesRDM = "style=\"display: none;\" ";
//}

if($v_EXIBIR_ATRBM == "EXIBIR"){//mostrar
	$MaisAtividadesRBRDM = "style=\"display: none;\" ";
	$MenosAtividadesRBRDM = "";
	$tabelaAtividadesRBRDM = " ";
}else{//n�o mostrar
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

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaAtividadesRBRDM\" $tabelaAtividadesRBRDM");

print $pagina->CampoHidden("v_ACAO_ATRBM", $v_ACAO_ATRBM);
print $pagina->CampoHidden("v_SEQ_ID_ATRBM", $v_SEQ_ID_ATRBM);
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO_ATRBM", $v_SEQ_ITEM_CONFIGURACAO_ATRBM);
print $pagina->CampoHidden("v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM", $v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM);

$pagina->LinhaCampoFormulario("Item configuracao:", "right", "S", 
									   $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO_ATRBM", "N", "" , "60", "60", "$v_NOM_ITEM_CONFIGURACAO_ATRBM", "readonly").
									   $pagina->ButtonProcuraItemConfiguracao("v_NOM_ITEM_CONFIGURACAO_ATRBM", "v_SEQ_ITEM_CONFIGURACAO_ATRBM","v_SEQ_TIPO_ITEM_CONFIGURACAO_ATRBM")
									, "left", "id=".$pagina->GetIdTable());
// Desci��o do chamado
$pagina->LinhaCampoFormulario("Descri��o:", "right", "S",
								  $pagina->CampoTextArea("v_DESCRICAO_ATIVIDADE_ATRBM", "N", "Descri��o", "100", "5", "$v_DESCRICAO_ATIVIDADE_ATRBM", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
								  , "left", "id=".$pagina->GetIdTable());	

//$pagina->LinhaCampoFormulario("Data/Hora de execu��o:", "right", "S",
//			$pagina->CampoData("v_DATA_EXECUCAO_ATRBM", "N", "Data de execu��o ", $v_DATA_EXECUCAO_ATRBM,"")
//			." ". $pagina->CampoHora("v_HORA_EXECUCAO_ATRBM", "N", "Hora de execu��o ", $v_HORA_EXECUCAO_ATRBM,"")			 
//			, "left", "id=".$pagina->GetIdTable());

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
$header[] = array("Descri��o", "");
//$header[] = array("Data/Hora de execu��o", "");
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
$pagina->LinhaCampoFormularioColspanDestaque("Respons�vel pelo checklist / valida��o da RDM", 2);
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
$pagina->CampoButton("if(fValidaForm()){document.form.v_ACAO.value='INCLUIR';return true;}else{return false;}", " Salvar ")
." &nbsp;&nbsp;".$pagina->CampoButton("if(fValidaForm()){document.form.v_ACAO.value='ENVIAR';return true;}else{return false;}", " Enviar para aprova��o ")

,"2");
$pagina->FechaTabelaPadrao();
$v_ACAO ="";
print $pagina->CampoHidden("v_ACAO", $v_ACAO);

if($vMsgErro!=""){
	$pagina->ScriptAlert($vMsgErro);
}


?>
<script language="javascript">	 
	do_ValidarSolicitante();
</script>
<?
$pagina->MontaRodape();
?>
