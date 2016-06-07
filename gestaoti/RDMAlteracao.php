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
require_once 'include/PHP/class/class.atividade_rdm.php';
require_once 'include/PHP/class/class.atividade_rb_rdm.php';
require_once 'include/PHP/class/class.util.php';
require_once 'include/PHP/class/class.equipe_ti.php';
require 'include/PHP/class/class.servidor.php';
require 'include/PHP/class/class.item_configuracao.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.chamado_rdm.php';

$pagina = new Pagina();
$pagina->ForcaAutenticacao();
// ============================================================================================================
// METODOS
// ============================================================================================================

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
	
	$dataHoraAtualMaisMinutosNew =  date("d/m/y H:i:s",$dataHoraAtualMaisMinutos);
	
	if ($dataHoraAtualMaisMinutos > $dataExceucao){					 
		$limiteValido = false;
		 
	} 
	
	return $limiteValido;
	
}

function validarLimteAberturaRDM_XXX($atividadeRDM){
	require_once 'include/PHP/class/class.janela_mudanca.php';
	require_once 'include/PHP/class/class.util.php';
	$limiteValido = true;
	 
	$util = new util();
	$dataHoraAtual = $util->GetlocalTimeStamp();
	$array_teste = split(" ",$dataHoraAtual);
	$data_array_teste = split("/",$array_teste[0]);
	$hora_array_teste = split(":",$array_teste[1]);	
	
	if(count ($data_array_teste)==1){
		$data_array_teste = split("-",$array_teste[0]);
	}
	$dia_teste = $data_array_teste[0];
	$mes_teste = $data_array_teste[1];
	$ano_teste = $data_array_teste[2];	 
	$h_teste = $hora_array_teste[0];
	$min_teste =  $hora_array_teste[1];
	
	
	$janela = new janela_mudanca();
	
	for ($i = 0; $i < count($atividadeRDM); $i++){ 
				
		$janela->selectByIC($atividadeRDM[$i][1],$atividadeRDM[$i][3]);
			
		if($janela->database->rows == 0){
			$limiteValido = false;
		}else{
			
			while ($row = pg_fetch_array($janela->database->result)){
				$data_array_exec = split("/",$atividadeRDM[$i][5]);
				$dia = $data_array_exec[0];
				$mes = $data_array_exec[1];
				$ano = $data_array_exec[2];		
				
				$hora_array_exec = split(":",$atividadeRDM[$i][6]);
				$hr_exec = $hora_array_exec[0];
				$min =  $hora_array_exec[1];
				
				$dataExceucao = mktime($h,$min, 0,$mes,$dia,$ano);
				///$dataHoraAtualMaisMinutos = mktime($h_teste,$min_teste+$janela->limite_para_rdm, 0,$mes_teste,$dia_teste,$ano_teste);
				$dataHoraAtualMaisMinutos = mktime($h_teste,$min_teste+$row["limite_para_rdm"], 0,$mes_teste,$dia_teste,$ano_teste);
				
				if ($dataHoraAtualMaisMinutos > $dataExceucao){					 
					$limiteValido = false;
					break;
				}
			}
		}//FIM ELSE
		
		if(!$limiteValido){
			break;
		}
		
	}// FIM FOR
	
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
// CAREGAR A TELA COM OS DADOS DA RDM
// ============================================================================================================
if($v_SEQ_RDM == ""){
	$pagina->redirectTo("RDMPesquisar.php");
}else if($v_SEQ_RDM != "" && $v_ACAO=="ALTERAR"){ 
	$RDM = new rdm();
	$situacaoRDM = new situacao_rdm();		 
	$atividadeRDM = new atividade_rdm();
	$atividadeRBRDM = new atividade_rb_rdm();
	
	
	// pesquisa
	$RDM->select($v_SEQ_RDM);
	
	// Dados do Solicitante	
	$empregados = new empregados();
	$empregados->select($RDM->NUM_MATRICULA_SOLICITANTE);
	
	$v_NUM_MATRICULA_SOLICITANTE_REAL = $empregados->NOM_LOGIN_REDE;
	
	$v_TITULO = $RDM->TITULO;
	$v_JUSTIFICATIVA = $RDM->JUSTIFICATIVA;
	$v_IMPACTO_NAO_EXECUTAR = $RDM->IMPACTO_NAO_EXECUTAR;
	$v_OBSERVACAO = $RDM->OBSERVACAO;
	$v_NOME_RESP_CHECKLIST = $RDM->NOME_RESP_CHECKLIST;
	$v_EMAIL_REP_CHECKLIST = $RDM->EMAIL_RESP_CHECKLIST;
	$v_DDD_TELEFONE_RESP_CHECKLIST = $RDM->DDD_TELEFONE_RESP_CHECKLIST;
	$v_NUMERO_TELEFONE_RESP_CHECKLIST = $RDM->NUMERO_TELEFONE_RESP_CHECKLIST;
	//print('v_NUMERO_TELEFONE_RESP_CHECKLIST: '.$v_NUMERO_TELEFONE_RESP_CHECKLIST);
	 
 	//ATIVIDADES DA RDM
 	$_SESSION['ID_ATIVIDADE_RDM'] = 0;
 	$_SESSION['ATIVIDADES_RDM'] = array();
	$atividadeRDM->setSEQ_RDM($RDM->SEQ_RDM);
	$atividadeRDM->selectParam("ORDEM");
	
	if($atividadeRDM->database->rows != 0){		 

		while ($row = pg_fetch_array($atividadeRDM->database->result)){		 
		 
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
		
			$array_teste = split(" ",$row["data_hora_prevista_execucao"]);
			$data_array_teste = split("-",$array_teste[0]);
			$hora_array_teste = split(":",$array_teste[1]);			
			$dia_teste = $data_array_teste[2];
			$mes_teste = $data_array_teste[1];
			$ano_teste = $data_array_teste[0];			
			$h_teste = $hora_array_teste[0];
			$min_teste =  $hora_array_teste[1];
			$data_hora_prevista_execucao = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
			
			$v_DATA_EXECUCAO_ATM =  date("d/m/Y",$data_hora_prevista_execucao);			
			$v_HORA_EXECUCAO_ATM = date("H:i", $data_hora_prevista_execucao);
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
 	
	$atividadeRBRDM->setSEQ_RDM($RDM->SEQ_RDM);
	$atividadeRBRDM->selectParam("ORDEM");
	
	if(!$atividadeRBRDM->database->rows == 0){				 

		while ($row = pg_fetch_array($atividadeRBRDM->database->result)){			  
			
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
		//CHAMDOS ASSOCIADOS
		
		$chamado_rdm = new chamado_rdm();
		$chamado_rdm->setSEQ_RDM($RDM->SEQ_RDM);
		$chamado_rdm->selectParam("SEQ_CHAMADO");
	
		if(!$chamado_rdm->database->rows == 0){
			$_SESSION['CHAMADOS_RDM'] = array();
			
			while ($row = pg_fetch_array($chamado_rdm->database->result)){
				$_SESSION['CHAMADOS_RDM'][] = array($row["seq_chamado"],$row["dsc_atividade_chamado"],$row["txt_chamado"]);				 
			}
		}else{
			unset($_SESSION['CHAMADOS_RDM']);	
		}
		
	 
 	
 	
}else if($v_SEQ_RDM != "" && ($v_ACAO=="SALVAR" || $v_ACAO=="ENVIAR")){
	$vMsgErro = "";
	$vRegrasVioladas = false;
	
	if(count($_SESSION['ATIVIDADES_RDM'])==0){
		$vRegrasVioladas = true;
		$vMsgErro = "Informe pelo menos uma atividade para RDM!";
	}else if(count($_SESSION['ATIVIDADES_RB_RDM'])==0){
		$vRegrasVioladas = true;
		$vMsgErro = "Informe pelo menos uma atividade de Rollback para RDM!";
	}else if(count($_SESSION['CHAMADOS_RDM'])==0){
		$vRegrasVioladas = true;
		$vMsgErro = "Informe pelo menos um chamado para RDM!";
	}  
	
	if(!$vRegrasVioladas){
		$RDM = new rdm();
		$situacaoRDM = new situacao_rdm();		 
		$atividadeRDM = new atividade_rdm();
		$atividadeRBRDM = new atividade_rb_rdm();
		$empregados = new empregados();
		$chamado_rdm = new chamado_rdm();
		
		$RDM->setSEQ_RDM($v_SEQ_RDM);
		//$RDMAux = new rdm(); 
		
		// pesquisa
		//$RDMAux->select($v_SEQ_RDM);
		 
		if($v_ACAO=="SALVAR"){		 
			$RDM->setSITUACAO_ATUAL($situacaoRDM->CRIADA);
		}else if($v_ACAO=="ENVIAR"){
			$RDM->setSITUACAO_ATUAL($situacaoRDM->AGUARDANDO_APROVACAO);
		}
		 
		
		
		
		// Preenchendo a RDM com os campos do formulário 
		$v_NUM_MATRICULA_SOLICITANTE_REAL = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_SOLICITANTE_REAL);
		$RDM->setNUM_MATRICULA_SOLICITANTE($v_NUM_MATRICULA_SOLICITANTE_REAL);
				
		$RDM->setTITULO($v_TITULO);
		$RDM->setJUSTIFICATIVA($v_JUSTIFICATIVA);
		$RDM->setIMPACTO_NAO_EXECUTAR($v_IMPACTO_NAO_EXECUTAR);
		$RDM->setNOME_RESP_CHECKLIST($v_NOME_RESP_CHECKLIST);
		$RDM->setEMAIL_RESP_CHECKLIST($v_EMAIL_REP_CHECKLIST);
		
		$RDM->setDDD_TELEFONE_RESP_CHECKLIST($v_DDD_TELEFONE_RESP_CHECKLIST);
		$RDM->setNUMERO_TELEFONE_RESP_CHECKLIST($v_NUMERO_TELEFONE_RESP_CHECKLIST);
		
		// VERIFICANDO O TIPO DA RDM
//		if(validarJanelaMudanca($_SESSION['ATIVIDADES_RDM']) &&
//			validarLimteAberturaRDM($_SESSION['ATIVIDADES_RDM'])){
		if(validarJanelaMudanca($_SESSION['ATIVIDADES_RDM'])){
			$RDM->setTIPO($RDM->NORMAL);
		}else{
			$RDM->setTIPO($RDM->EMERGENCIAL);
		} 
		
		$RDM->setOBSERVACAO($v_OBSERVACAO);
		
		//A data prevista para execuação inicial é a mesma da primeira atividade da RDM
		
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
		
		$RDM->update($RDM->SEQ_RDM);
		// Código inserido: $RDM->SEQ_RDM
		
		if($RDM->error == ""){
			if($v_ACAO=="ENVIAR"){
				$situacaoRDM->setSEQ_RDM($RDM->SEQ_RDM);
				$situacaoRDM->setSITUACAO($situacaoRDM->AGUARDANDO_APROVACAO);
				$situacaoRDM->setOBSERVACAO("Enviada para aprovação");
				$situacaoRDM->setDATA_HORA($dataHoraAtual);	
				$situacaoRDM->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
				$situacaoRDM->insert();				 
			}	

			// ===== INCLUIR ATIVIDADES DA RDM =====	
			$atividadeRDM->deleteByRDM($RDM->SEQ_RDM);			
				
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
		    $atividadeRBRDM->deleteByRDM($RDM->SEQ_RDM);
			 
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
				
				$atividadeRBRDM->setDESCRICAO(NULL);
				$atividadeRBRDM->setSEQ_SERVIDOR(NULL);				
			    $atividadeRBRDM->setSEQ_ITEM_CONFIGURACAO(NULL); 
				$atividadeRBRDM->setSEQ_TIPO_ITEM_CONFIGURACAO(NULL);
				$atividadeRBRDM->setORDEM(NULL);
				$atividadeRBRDM->setSITUACAO(NULL);			  
				$atividadeRBRDM->setSEQ_EQUIPE_TI(NULL);
				
		    }
		    
		}
		
	  	// ===== INCLUIR CHAMADOS ASSOCIADOS ===== 		
	  		$chamado_rdm->deleteByRDM($RDM->SEQ_RDM);
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
			//$pagina->redirectTo("RDMPesquisar.php?flag=1&v_SEQ_RDM=$RDM->SEQ_RDM&mensagemErro=$vMsgErro");
			
			if($v_ACAO=="ENVIAR"){
				
				 // Enviar e-mail para o solicitante
				require_once 'include/PHP/class/class.rdm_email.php';
				$rdm_email = new rdm_email($pagina,$RDM);
				$rdm_email->sendEmailRDMEnviadaParaAprovacao();		
			
				
			}
			
			
			$pagina->redirectTo("RDMDetalhe.php?&v_SEQ_RDM=$RDM->SEQ_RDM");
		
	}
	
}


$pagina->SettituloCabecalho("Alterar Requisição de mudança - RDM"); // Indica o título do cabeçalho da página
if($RDM->NUM_MATRICULA_SOLICITANTE==null){
	$situacaoRDM = new situacao_rdm();	
	$RDM = new rdm();
	$RDM->select($v_SEQ_RDM);
}	

// Itens das abas
$aItemAba = Array();
$aItemAba[] = array("RDMPesquisar.php", "", "Pesquisar");
$aItemAba[] = array("RDMDetalhe.php?v_SEQ_RDM=".$v_SEQ_RDM, " ", "Detalhes");

if(($RDM->NUM_MATRICULA_SOLICITANTE == $_SESSION["NUM_MATRICULA_RECURSO"])&&
		($RDM->getSITUACAO_ATUAL()==$situacaoRDM->CRIADA || 
		 $RDM->getSITUACAO_ATUAL()==$situacaoRDM->REPROVADA || 
		 $RDM->getSITUACAO_ATUAL()==$situacaoRDM->PLANEJAMENTO_REPROVADO)){
	$aItemAba[] = array("#", "tabact", "Alterar");
}

$APROVAR = false;
if(($RDM->getSITUACAO_ATUAL()==$situacaoRDM->AGUARDANDO_APROVACAO)){
	if($RDM->getTIPO()==$RDM->NORMAL){
		if($pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isCoordenadorTI($_SESSION["SEQ_PERFIL_ACESSO"])||
			$pagina->isGerenteDeMudancas($_SESSION["SEQ_PERFIL_ACESSO"])){
			$APROVAR = true;
		}
	}else if($RDM->getTIPO()==$RDM->EMERGENCIAL){
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
		$aItemAba[] = array("RDMExecutar.php?v_SEQ_RDM=".$v_SEQ_RDM, "", "Executar");
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
print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_RDM", $v_SEQ_RDM);



// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================

?>

<script language="javascript">
 

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
		 if(document.form.v_NOM_ITEM_CONFIGURACAO_ATM.value == ""){
			 	alert("Preencha o campo Item de configuração");
			 	return false;
		 }
		 if(document.form.v_DESCRICAO_ATIVIDADE_ATM.value == ""){
			 	alert("Preencha o campo Descrição");
			 	return false;
		 }
		 if(document.form.v_DATA_EXECUCAO_ATM.value == ""){
			 	alert("Preencha o campo Data de execução");
			 	return false;
		 }
		 if(document.form.v_HORA_EXECUCAO_ATM.value == ""){
			 	alert("Preencha o campo Hora de execução");
			 	return false;
		 }
		 if(document.form.v_SEQ_EQUIPE_TI_ATM.value == ""){
			 	alert("Preencha o campo Executor");
			 	return false;
		 }

		 if(!verificarDataMenorQueHoraAtual(document.form.v_DATA_EXECUCAO_ATM.value,document.form.v_HORA_EXECUCAO_ATM.value)){
				return false;
		 }
	 	 
		 return true;
  
	}
	function fValidaFormAtividadesRBRDM(v_ACAO_ATRBM){
		 document.form.v_ACAO_ATRBM.value = v_ACAO_ATRBM;
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
		 if(document.form.v_HORA_EXECUCAO_ATRBM.value == ""){
			 	alert("Preencha o campo Hora de execução");
			 	return false;
		 }
		 if(document.form.v_SEQ_EQUIPE_TI_ATRBM.value == ""){
			 	alert("Preencha o campo Executor");
			 	return false;
		 }

		 
	 	 
	 	return true;
 
	}

	function AnexaNovoArquivo($ID){
		if(document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).value != ""){
			document.getElementById("Newfile"+$ID).style.display = "none";
			$novo = $ID + 1;
			document.getElementById("file"+$novo).style.display = "block";
			document.getElementById("Newfile"+$novo).style.display = "block";
		}else{
			alert("É necessário anexar um arquivo antes de adionar um novo.");
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
			document.form.submit();
		}
	}
	function fExcluirATRBM(vValor){
	    if(confirm("Desejar apagar o registro?")){
	    	document.form.v_ACAO_ATRBM.value='EXCLUIR_ATRBM';
	    	document.form.v_SEQ_ID_ATRBM.value=vValor;
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
		document.form.v_SEQ_EQUIPE_TI_ATRBM.value == "";		 
		document.getElementById("tabelaAtividadesRBRDM").style.display = "none";
		document.getElementById("MaisAtividadesRBRDM").style.display = "block";
		document.getElementById("MenosAtividadesRBRDM").style.display = "none";
		return false;
		 
	}

	function fAddChamados(chamadosSelecionados){
		//alert('fAddChamados');
		document.form.v_ACAO.value = "INCLUIR_CHAMADO";
		//document.form.v_LIMPAR_SESSAO.value = 'NAO';
		document.form.v_CHAMADOS_SELECIONADOS.value = chamadosSelecionados;
		document.form.submit();
		//return false;
		 
	}
	
	 
</script>
<?		
 			  								  
// ============================================================================================================
// Informações Gerais
// ============================================================================================================
$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais", 2);

//Solicitante da RDM
print $pagina->CampoHidden("v_NUM_MATRICULA_SOLICITANTE_REAL",$v_NUM_MATRICULA_SOLICITANTE_REAL);
$pagina->LinhaCampoFormulario("Solicitante:", "right", "N", $v_NUM_MATRICULA_SOLICITANTE_REAL  , "left", "id=".$pagina->GetIdTable());	

				  
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
 					$vMsgErro	.= " O(s) seguinte(s) chamado(s) não foram associados, pois já o foram anteriormente: ";	
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
$header[] = array("Número do Chamado", "15%");
$header[] = array("Atividade", "40%");
$header[] = array("Descrição", "40%");
 

if(count($_SESSION['CHAMADOS_RDM']) == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum chamado associado à RDM", count($header));
}else{
	 
//	foreach ($_SESSION['CHAMADOS_RDM'] as $key => $row) {
//	    $data[$key] = $row[5];
//	    $hora[$key] = $row[6];
//	}
//	
//	array_multisort($data, SORT_ASC, $hora, SORT_ASC, $_SESSION['CHAMADOS_RDM']);
	 
	$corpo = array();
	 
	$pagina->LinhaHeaderTabelaResultado("Chamados associados à RDM", $header);

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
	 		$_SESSION['ATIVIDADES_RDM'][$i][5] == $v_DATA_EXECUCAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][6] == $v_HORA_EXECUCAO_ATM &&
	 		$_SESSION['ATIVIDADES_RDM'][$i][7] == $v_SEQ_EQUIPE_TI_ATM){
	 			$vMsgErro		 = "Atividade já cadastrada!";
	 			$v_EXIBIR_ATM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RDM'][$i][5] == $v_DATA_EXECUCAO_ATM &&
	 			  $_SESSION['ATIVIDADES_RDM'][$i][6] == $v_HORA_EXECUCAO_ATM ){
	 			$vMsgErro	 = "Já existe uma atividade para esta data e hora!";
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
	 			$vMsgErro		 = "Atividade já cadastrada!";
	 			$v_EXIBIR_ATM = "EXIBIR";
	 			$v_OK = false;
			
	 		break;
	 	}else if ($_SESSION['ATIVIDADES_RDM'][$i][0] != $v_SEQ_ID_ATM &&
	 			  $_SESSION['ATIVIDADES_RDM'][$i][5] == $v_DATA_EXECUCAO_ATM &&
	 			  $_SESSION['ATIVIDADES_RDM'][$i][6] == $v_HORA_EXECUCAO_ATM ){
	 			$vMsgErro	 = "Já existe uma atividade para esta data e hora!";
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

$pagina->LinhaCampoFormulario("Data/Hora de execução:", "right", "S",
			$pagina->CampoData("v_DATA_EXECUCAO_ATM", "N", "Data de execução ", $v_DATA_EXECUCAO_ATM,"")
			." ". $pagina->CampoHora("v_HORA_EXECUCAO_ATM", "N", "Hora de execução ", $v_HORA_EXECUCAO_ATM,"")			 
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
$header[] = array("Data/Hora de execução", "");
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

//$pagina->LinhaCampoFormulario("Data/Hora de execução:", "right", "S",
//			$pagina->CampoData("v_DATA_EXECUCAO_ATRBM", "N", "Data de execução ", $v_DATA_EXECUCAO_ATRBM,"")
//			." ". $pagina->CampoHora("v_HORA_EXECUCAO_ATRBM", "N", "Hora de execução ", $v_HORA_EXECUCAO_ATRBM,"")			 
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
$pagina->CampoButton("if(fValidaForm()){document.form.v_ACAO.value='SALVAR';return true;}else{return false;}", " Salvar ")
." &nbsp;&nbsp;".$pagina->CampoButton("if(fValidaForm()){document.form.v_ACAO.value='ENVIAR';return true;}else{return false;}", " Enviar para aprovação ")

,"2");
$pagina->FechaTabelaPadrao();


$v_ACAO ="";
print $pagina->CampoHidden("v_ACAO", $v_ACAO);
if($vMsgErro!=""){
	$pagina->ScriptAlert($vMsgErro);
}
 





$pagina->MontaRodape();

							  								  


