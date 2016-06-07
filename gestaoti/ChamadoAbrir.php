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
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

// ============================================================================================================
// Realizar o cadastro do chamado
// ============================================================================================================
if($flag == "1"){
	// Validar campos
	$camposValidados = 1;
	$mensagemErro = "";
	require_once 'include/PHP/class/class.subtipo_chamado.php';
	require_once 'include/PHP/class/class.tipo_chamado.php';
	$subtipo_chamado = new subtipo_chamado();
	$tipo_chamado = new tipo_chamado();
	if($v_SEQ_TIPO_OCORRENCIA == ""){
		$camposValidados = 0;
		$mensagemErro = "Tipo de Chamado";
	}
	if($v_SEQ_TIPO_CHAMADO == ""){
		$camposValidados = 0;
		$mensagemErro = "Classe de Chamado";
	}
	if($v_SEQ_SUBTIPO_CHAMADO == ""){
		$camposValidados = 0;
		$mensagemErro .= $pagina->iif($mensagemErro=="","Subclasse de chamado", ", Subclasse de chamado");
	}
	if($v_SEQ_ATIVIDADE_CHAMADO == ""){
		$camposValidados = 0;
		$mensagemErro .= $pagina->iif($mensagemErro=="","Atividade", ", Atividade");
	}
	if($v_SEQ_PRIORIDADE_CHAMADO == ""){
		$camposValidados = 0;
		$mensagemErro .= $pagina->iif($mensagemErro=="","Prioridade", ", Prioridade");
	}
	if($v_TXT_CHAMADO == ""){
		$camposValidados = 0;
		$mensagemErro .= $pagina->iif($mensagemErro=="","Solicitação", ", Solicitação");
	}
	if($v_LISTA_ATRIBUICAO == "" && $v_SELECAO_DESTINO == "2"){
		$camposValidados = 0;
	 	$vErroCampos .= $pagina->iif($mensagemErro=="","Atribuição", ", Atribuição");
	}
	if($v_SEQ_TIPO_CHAMADO == $tipo_chamado->COD_TIPO_SISTEMAS_INFORMACAO){
		if($v_SEQ_ITEM_CONFIGURACAO == ""){
			$camposValidados = 0;
			$mensagemErro .= $pagina->iif($mensagemErro=="","Sistema de informação", ", Sistema de informação");
		}
	}else{
		$v_SEQ_ITEM_CONFIGURACAO = "";
	}
	
// VALIDACAO DOS DA CENTRAL DE ATENDIMENTO AUXILIAR
	if($v_SEQ_CENTRAL_ATENDIMENTO == $SEQ_CENTRAL_ATIVIDADES_AUXILIARES){
				 
		if($v_SEQ_TIPO_CHAMADO  != '' && $v_SEQ_TIPO_CHAMADO == $SEQ_CLASSE_CHAMADO_CELULAR){
			 
			if($v_DATA_INICIO_UTILIZACAO_APARELHO == ""){
				$camposValidados = 0;
				$mensagemErro = "Preencha o campo Data de Início Perídodo de Utilização";						 
			}

			if($v_DATA_FIM_UTILIZACAO_APARELHO == ""){
				$camposValidados = 0;
				$mensagemErro = "Preencha o campo Data fim Perídodo de Utilização"; 
			}
		}   else if( $v_SEQ_TIPO_CHAMADO != '' &&  $v_SEQ_TIPO_CHAMADO  == $SEQ_CLASSE_CHAMADO_AUDITORIO ){
			$v_TXT_CHAMADO ="Reserva de Auditório";
			
			if($v_TXT_OBEJTIVO_EVENTO  == ""){
				$camposValidados = 0;
				$mensagemErro = "Preencha o campo Objetivo do Evento"; 
			}
			if( $v_DATA_RESERVA_EVENTO == ""){
				$camposValidados = 0;
				$mensagemErro = "Preencha o campo Data Reserva"; 
			}
			if($v_HORA_RESERVA_EVENTO  == ""){
				$camposValidados = 0;
				$mensagemErro = "Preencha o campo Hora Reserva"; 
			}
			if($v_QUANTIDADE_PESSOAS  == ""){
				$camposValidados = 0;
				$mensagemErro = "Preencha o campo Quantidade de Pessoas"; 
			}
			if($v_TXT_SERVICOS  == ""){
				$camposValidados = 0;
				$mensagemErro = "Preencha o campo Serviços"; 
			}
		}  
				
	}
	if($camposValidados == 1){
		// Incluir chamado
		require_once 'include/PHP/class/class.chamado.php';
		require_once 'include/PHP/class/class.prioridade_chamado.php';
		require_once 'include/PHP/class/class.atividade_chamado.php';
		require_once 'include/PHP/class/class.situacao_chamado.php';
		$chamado = new chamado();
		$situacao_chamado = new situacao_chamado();
		$prioridade_chamado = new prioridade_chamado();
		$atividade_chamado = new atividade_chamado();

		
		/*
		 * Caso o chamado seja aberto por usuário com perfil de Diretor, Coordenador ou chefe de divisão, 
		 * o mesmo não será encaminhado para aprovação; 
		 * 
		 */
		
		if($v_SEQ_CENTRAL_ATENDIMENTO == $SEQ_CENTRAL_ATIVIDADES_AUXILIARES){
			//SUSPENSO TEMPORARIAMENTE
			/*  */
//			require_once '../gestaoti/include/PHP/class/class.parametro.php';
//			$parametro = new parametro();
//			 if($v_SEQ_TIPO_OCORRENCIA == $parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_SOLICITACAO")){
//				if($v_SEQ_TIPO_CHAMADO == $SEQ_CLASSE_CHAMADO_TRANSPORTE){
//					require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
//					$empregados= new empregados(1);
//					$funcADM = $empregados->GetFuncaoAdministrativaByLogin($_SESSION["NOM_LOGIN_REDE"]);
//					if($chamado->transporteEspecial($funcADM)){
//						$v_SEQ_ATIVIDADE_CHAMADO = $parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRANSPORTE_ESPECIAL");
//					}
//					
//				} 
//			 }
			
		}
		// Verificar se trata-se de um chamado com SLA Pré ou Pós Estabelecido conforme a atividade selecionada
		$atividade_chamado->select($v_SEQ_ATIVIDADE_CHAMADO); 
		
		
		//NOVA PARTE: APROVACAO DE CHAMADOS
		if($atividade_chamado->getFLG_EXIGE_APROVACAO() == "1"){
//			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Aprovacao;
//			$qtdMinutosEspera = 1;
//			require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
//			$CHEFE= new empregados(1);
//			$CHEFE->SelectChefeByIDSubordinado($_SESSION["NUM_MATRICULA_RECURSO"]);
                        /*
			require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
 			$empregados= new empregados(1);
 			$funcADM = $empregados->GetFuncaoAdministrativaByLogin($_SESSION["NOM_LOGIN_REDE"]);
			
			if($chamado->aprovadorDeChamados($funcADM)){
 				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Triagem;
				$v_NUM_PRIORIDADE_FILA = "";
				$v_SEQ_ITEM_CONFIGURACAO = "";
				$qtdMinutosEspera = $chamado->CalcularTempoEspera();	 
 			}else{ 				

                        */
				$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Aprovacao;
			//	$qtdMinutosEspera = 1;
 			//}
 			
		}elseif($v_SELECAO_DESTINO == "1"){// Se o chamado for encaminhado para o primeiro nível
			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Triagem;
		}elseif($atividade_chamado->QTD_MIN_SLA_ATENDIMENTO == ""){ // Não tempos SLA - Fluxo de aprovação
			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Planejamento;
		}else{ // Temos SLA - Fluxo normal de atendimento
			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Atendimento;
		}
		//NOVA PARTE: APROVACAO DE CHAMADOS
		
		/*
		if($v_SELECAO_DESTINO == "1"){
			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Triagem;
		}elseif($atividade_chamado->QTD_MIN_SLA_ATENDIMENTO == ""){ // Não tempos SLA - Fluxo de aprovação
			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Planejamento;
		}else{ // Temos SLA - Fluxo normal de atendimento
			$v_SEQ_SITUACAO_CHAMADO = $situacao_chamado->COD_Aguardando_Atendimento;
		}*/

		//$chamado->setNUM_MATRICULA_SOLICITANTE($v_NUM_MATRICULA_SOLICITANTE_REAL);
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$v_NUM_MATRICULA_SOLICITANTE = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_SOLICITANTE);

		$chamado->setNUM_MATRICULA_SOLICITANTE($v_NUM_MATRICULA_SOLICITANTE);
		$chamado->setFLG_DESTINACAO_CHAMADO($v_SELECAO_DESTINO);
		
		$chamado->setSEQ_ATIVIDADE_CHAMADO($v_SEQ_ATIVIDADE_CHAMADO);
		$chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
		$chamado->setSEQ_LOCALIZACAO_FISICA($v_SEQ_LOCALIZACAO_FISICA);
		$chamado->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
		$chamado->setTXT_CHAMADO(addslashes($v_TXT_CHAMADO));
		$chamado->setDTH_INICIO_PREVISAO($pagina->add_minutos(1, false, "Y-m-d H:i:s"));
		$chamado->setNUM_MATRICULA_CONTATO($v_NUM_MATRICULA_CONTATO_REAL);
		$chamado->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
		$chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);

		if($_SESSION["NUM_MATRICULA_RECURSO"] != $v_NUM_MATRICULA_SOLICITANTE){
			$chamado->setNUM_MATRICULA_CADASTRANTE($_SESSION["NUM_MATRICULA_RECURSO"]);
		}

		$chamado->setOBJETIVO_EVENTO($v_TXT_OBEJTIVO_EVENTO);	 
		 
		 
		//A $v_DATA_RESERVA_EVENTO
		if($v_DATA_RESERVA_EVENTO != null){
			$data = split("/",$v_DATA_RESERVA_EVENTO);
			$d = $data[0];
			$m = $data[1];
			$a = $data[2];
		
			$hora = split(":",$v_HORA_RESERVA_EVENTO);
			$hr = $hora[0];
			$minuto =  $hora[1];			 
			$DTH_RESERVA_EVENTO = mktime($hr,$minuto, 0,$m,$d,$a);
		
			$chamado->setDTH_RESERVA_EVENTO(date("Y-m-d H:i:s",$DTH_RESERVA_EVENTO));
			
		}
		
		
		$chamado->setQUANTIDADE_PESSOAS_EVENTO($v_QUANTIDADE_PESSOAS);
		$chamado->setSERVICOS_EVENTO($v_TXT_SERVICOS); 
		
		if($v_DATA_INICIO_UTILIZACAO_APARELHO != null){
			$data = split("/",$v_DATA_INICIO_UTILIZACAO_APARELHO);
			$d = $data[0];
			$m = $data[1];
			$a = $data[2];			 
			$v_DATA_INICIO_UTILIZACAO_APARELHO = mktime(0,0, 0,$m,$d,$a);
			$chamado->setDT_INICIO_UTILIZACAO_APARELHO(date("Y-m-d H:i:s",$v_DATA_INICIO_UTILIZACAO_APARELHO)); 
		}
		if($v_DATA_FIM_UTILIZACAO_APARELHO != null){
			 
			$data = split("/",$v_DATA_FIM_UTILIZACAO_APARELHO);
			$d = $data[0];
			$m = $data[1];
			$a = $data[2];			 
			$v_DATA_FIM_UTILIZACAO_APARELHO = mktime(0,0, 0,$m,$d,$a);
			
			$chamado->setDT_FIM_UTILIZACAO_APARELHO(date("Y-m-d H:i:s",$v_DATA_FIM_UTILIZACAO_APARELHO));
		}
		
		//$chamado->setNUM_PRIORIDADE_FILA($v_NUM_PRIORIDADE_FILA); // Fila é alimentada apenas por clientes
		$chamado->insert();
		// Código inserido: $chamado->SEQ_CHAMADO
		if($chamado->error == ""){
			// Inserir atribuições
			$v_TXT_HISTORICO = "Chamado encaminhado para ";
			if($v_LISTA_ATRIBUICAO != ""){
				require_once 'include/PHP/class/class.atribuicao_chamado.php';
				require_once 'include/PHP/class/class.equipe_ti.php';
				$situacao_chamado = new situacao_chamado();
				$equipe_ti = new equipe_ti();
				$aLISTA_ATRIBUICAO = split("$&", $v_LISTA_ATRIBUICAO);
				for($i=0; $i<count($aLISTA_ATRIBUICAO);$i++){
					//print $aLISTA_ATRIBUICAO[$i];
					$aValores = explode("|", $aLISTA_ATRIBUICAO[$i]);
					//print $aValores;
					$v_SEQ_EQUIPE_TI         = $aValores[0];
					$v_NUM_MATRICULA         = $aValores[1];
					$v_TXT_ATIVIDADE         = $aValores[2];
					//$v_SEQ_EQUIPE_ATRIBUICAO = $aValores[3];
					// Pegar o nome da equipe
					$equipe_ti->select($v_SEQ_EQUIPE_TI);
					$v_TXT_HISTORICO .= $equipe_ti->NOM_EQUIPE_TI.",";

					$atribuicao_chamado = new atribuicao_chamado();
					$atribuicao_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
					$atribuicao_chamado->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
					$atribuicao_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
					$atribuicao_chamado->setNUM_MATRICULA($v_NUM_MATRICULA);
					//$atribuicao_chamado->setSEQ_EQUIPE_ATRIBUICAO($v_SEQ_EQUIPE_ATRIBUICAO);
					$atribuicao_chamado->setTXT_ATIVIDADE(addslashes($v_TXT_ATIVIDADE));
					$atribuicao_chamado->insert();

					// Enviar e-mail para o profissional atribuído
					if($v_NUM_MATRICULA != ""){
						require_once 'include/PHP/class/class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->From     = $pagina->EmailRemetente;
						$mail->FromName = $pagina->remetenteEmailCEA;
						$mail->Sender   = $pagina->EmailRemetente;
						$mail->Subject  = "CAU - Notificação de Atribuição Chamado";
						$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
						if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
					        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
					    } else {
					        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
					    }

					    $chamadoEmail = new chamado();
					    $chamadoEmail->email($chamado->SEQ_CHAMADO);
                                            $v_DS_CORPO ="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
                                                                        \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
                                                                    <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
                                                                    <head>
                                                                            <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
                                                                            <link href=\"".$pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
                                                                    </head>
                                                                    <body>
                                                                    <div align=\"left\">
                                                                                    <NOM_DETINATARIO>,<br>
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;acaba de ser aberto, no CAU, o chamado nº ".$chamado->SEQ_CHAMADO.", atribuído você. Seguem abaixo os dados do chamado:
                                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
                                                               <br>&nbsp;&nbsp;- Solicitação: <b>".$chamadoEmail->TXT_CHAMADO."</b>
                                                               <br>&nbsp;&nbsp;- Cliente: <b>".$chamadoEmail->NOM_CLIENTE."</b>
                                                               <br>
                                                               <br>Para maiores informações acesse o CAU na sua área de atendimento.
                                                               <br>Esta é uma mensagem autom&aacute;tica, favor não responder.
                                                               <br>---
                                                               <br>Central de Atendimento ao Usuário
                                                               <br>".$pagina->enderecoGestaoTI."
                                                               </div>
                                                               </body>
                                                               </html>";

						// Buscar contato do líder e do substituto
						require_once 'include/PHP/class/class.equipe_ti.php';
						$equipe_ti = new equipe_ti();
						$equipe_ti->EmailLiderSubstituto($v_SEQ_EQUIPE_TI);
						// Ao líder
						if($equipe_ti->DSC_EMAIL_LIDER != ""){
							$mail->AddAddress($equipe_ti->DSC_EMAIL_LIDER, $equipe_ti->NOM_LIDER);
						}

						// Buscar e-mail do colaborador
						require_once 'include/PHP/class/class.empregados.oracle.php';
						$empregados = new empregados();
						$empregados->GetNomeEmail($v_NUM_MATRICULA);
						// Adicionar
						$mail->AddAddress($empregados->DES_EMAIL, $empregados->NOME);
						// Enviar
						$mail->Body    = str_replace("<NOM_DETINATARIO>", $empregados->NOME, $v_DS_CORPO);
                                                $mail->AltBody = str_replace("<NOM_DETINATARIO>", $empregados->NOME, $v_DS_CORPO);
                                                $mail->Send();
						$mail->ClearAddresses();
					}else{ // Enviar e-mail para o líder da equipe
						require_once 'include/PHP/class/class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->From     = $pagina->EmailRemetente;
						$mail->FromName = $pagina->remetenteEmailCEA;
						$mail->Sender   = $pagina->EmailRemetente;
						$mail->Subject  = "CAU - Notificação de Atribuição Chamado";
						$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
						if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
					        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
					    } else {
					        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
					    }

					    $chamadoEmail = new chamado();
					    $chamadoEmail->email($chamado->SEQ_CHAMADO);
                                            $v_DS_CORPO ="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
                                                                        \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
                                                                    <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
                                                                    <head>
                                                                            <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
                                                                            <link href=\"".$pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
                                                                    </head>
                                                                    <body>
                                                                    <div align=\"left\">
                                                                                    <NOM_DETINATARIO>,<br>
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;acaba de ser aberto, no CAU, o chamado nº ".$chamado->SEQ_CHAMADO.", atribuído para a sua equipe. Seguem abaixo os dados do chamado:
                                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                                               <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
                                                               <br>&nbsp;&nbsp;- Solicitação: <b>".$chamadoEmail->TXT_CHAMADO."</b>
                                                               <br>&nbsp;&nbsp;- Cliente: <b>".$chamadoEmail->NOM_CLIENTE."</b>
                                                               <br>
                                                               <br>Para maiores informações acesse o CAU na sua área de atendimento.
                                                               <br>Esta é uma mensagem autom&aacute;tica, favor n&atilde;o responder.
                                                               <br>---
                                                               <br>Central de Atendimento ao Usuário
                                                               <br>".$pagina->enderecoGestaoTI."
                                                               </div>
                                                               </body>
                                                               </html>";

						// Buscar contatos do líder e do substituto
						require_once 'include/PHP/class/class.equipe_ti.php';
						$equipe_ti = new equipe_ti();
						$equipe_ti->EmailLiderSubstituto($v_SEQ_EQUIPE_TI);
						// Ao líder
						if($equipe_ti->DSC_EMAIL_LIDER != ""){
							$mail->AddAddress($equipe_ti->DSC_EMAIL_LIDER, $equipe_ti->NOM_LIDER);
							$mail->Body    = str_replace("<NOM_DETINATARIO>", $equipe_ti->NOM_LIDER, $v_DS_CORPO);
			    			$mail->AltBody = str_replace("<NOM_DETINATARIO>", $equipe_ti->NOM_LIDER, $v_DS_CORPO);
							$mail->Send();
							$mail->ClearAddresses();
						}

						// Ao Substituto
						if($equipe_ti->DSC_EMAIL_SUBSTITUTO != ""){
							$mail->AddAddress($equipe_ti->DSC_EMAIL_SUBSTITUTO, $equipe_ti->NOM_SUBSTITUTO);
							$mail->Body    = str_replace("<NOM_DETINATARIO>", $equipe_ti->NOM_SUBSTITUTO, $v_DS_CORPO);
                                                        $mail->AltBody = str_replace("<NOM_DETINATARIO>", $equipe_ti->NOM_SUBSTITUTO, $v_DS_CORPO);
                                                        $mail->Send();
							$mail->ClearAddresses();
						}
					}
				}
			}
			$v_TXT_HISTORICO = substr($v_TXT_HISTORICO,0,$v_TXT_HISTORICO-1);

			if($v_SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Aprovacao){
				$v_TXT_HISTORICO = $v_TXT_HISTORICO. " OBS: Chamado aguardand aprovação!!";
			}
			
			// Incluir hitórico
			require_once 'include/PHP/class/class.historico_chamado.php';
			$historico_chamado = new historico_chamado();
			$historico_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
			$historico_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
			$historico_chamado->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
			//$historico_chamado->setSEQ_MOTIVO_SUSPENCAO($v_SEQ_MOTIVO_SUSPENCAO);
			$historico_chamado->setTXT_HISTORICO($v_TXT_HISTORICO);
			$historico_chamado->insert();

			// Incluir patrimonios
			if($v_LISTA_PATRIMONIOS != ""){
				require_once 'include/PHP/class/class.patrimonio_chamado.php';
				$aNUM_PATRIMONIO = split(",", $v_LISTA_PATRIMONIOS);
				for($i=0; $i<count($aNUM_PATRIMONIO);$i++){
					$patrimonio_chamado = new patrimonio_chamado();
					$patrimonio_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
					$patrimonio_chamado->setNUM_PATRIMONIO($aNUM_PATRIMONIO[$i]);
					$patrimonio_chamado->insert();
				}
			}
			
			//VINCULAR CHAMADO PARA APROVACAO 
			if($v_SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Aprovacao){
					require_once 'include/PHP/class/class.aprovacao_chamado_departamento.php';				
					$aprovacao_chamado_departamento = new aprovacao_chamado_departamento();
					$aprovacao_chamado_departamento->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
					$aprovacao_chamado_departamento->setID_UNIDADE($_SESSION["UOR_ID"]);
					$aprovacao_chamado_departamento->setID_COORDENACAO($_SESSION["COOR_ID"]);
					$aprovacao_chamado_departamento->insert();				
			}
			//VINCULAR CHAMADO PARA APROVACAO 

			// Enviar e-mail para o solicitante
			require_once 'include/PHP/class/class.phpmailer.php';
			$mail = new PHPMailer();
			$mail->From     = $pagina->EmailRemetente;
			$mail->FromName = $pagina->remetenteEmailCEA;
			$mail->Sender   = $pagina->EmailRemetente;
			$mail->Subject  = "CAU - Confirmação de Abertura de Chamado";
			$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
			if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
		        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
		    } else {
		        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
		    }

		    $chamadoEmail = new chamado();
		    $chamadoEmail->email($chamado->SEQ_CHAMADO);
			$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
								    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
								<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
								<head>
									<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
									<link href=\"".$pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
								</head>
								<body>
								<div align=\"left\">
										Prezado(a) usuário(a) ".$_SESSION["NOME"].",<br>
								<br>
							   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seu chamado foi registrado com o Nº ".$chamado->SEQ_CHAMADO.". Seguem informações registradas:
							   <br>
							   <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").": <b>".$chamadoEmail->NOM_TIPO_OCORRENCIA."</b>
                                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").": <b>".$chamadoEmail->DSC_TIPO_CHAMADO."</b>
                                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").": <b>".$chamadoEmail->DSC_SUBTIPO_CHAMADO."</b>
                                                           <br>&nbsp;&nbsp;- ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").": <b>".$chamadoEmail->DSC_ATIVIDADE_CHAMADO."</b>
							   <br>&nbsp;&nbsp;- Solicitação: <b>".$chamadoEmail->TXT_CHAMADO."</b>
							   <br>
							   <br>A Central de Atendimento ao Usuário agradece o seu contato.
								   	   Em caso de problemas ou solicitações, estamos à disposição para atendê-lo.
							   <br>
							   <br>Para maiores informações acesse o CAU.
							   <br>Esta é uma mensagem autom&aacute;tica, favor n&atilde;o responder.
							   <br>---
							   <br>Central de Atendimento ao Usuário - ".$pagina->nom_area_ti."
							   <br>".$pagina->enderecoCEA."
							   </div>
							   </body>
							   </html>
							   ";

			$mail->AddAddress($_SESSION["DES_EMAIL"], $_SESSION["NOME"]);
			$mail->Body    = $v_DS_CORPO;
			$mail->AltBody = $v_DS_CORPO;
			// Comentado para n~ao enviar e-mail antes do lan'camento oficial do sistema
			$mail->Send();
			$mail->ClearAddresses();

			// Incluir anexos
			require_once 'include/PHP/class/class.anexo_chamado.php';
			// Arquivo 1
			if($v_NOM_ARQUIVO_ORIGINAL_1 != ""){
				if($_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['size'] > 0){
					//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['size']/2048) <= 2048){
						// Inserir registro
						$anexo_chamado = new anexo_chamado();
						$anexo_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
						$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['name']);
						$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
						$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['name'])));
						$anexo_chamado->insert();
						if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
							$mensagemErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 1.";
						}
					//}else{
					//	$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['name'].", por exceder o tamanho de 2 Mb.";
					//}
				}else{
					$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['name'].". Arquivo não existe.";
				}
			}
			// Arquivo 2
			if($v_NOM_ARQUIVO_ORIGINAL_2 != ""){
				if($_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['size'] > 0){
					//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['size']/2048) <= 2048){
						// Inserir registro
						$anexo_chamado = new anexo_chamado();
						$anexo_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
						$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['name']);
						$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
						$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['name'])));
						$anexo_chamado->insert();
						if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
							$mensagemErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 2.";
						}
					//}else{
					//	$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['name'].", por exceder o tamanho de 2 Mb.";
					//}
				}else{
					$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['name'].". Arquivo não existe.";
				}
			}
			// Arquivo 3
			if($v_NOM_ARQUIVO_ORIGINAL_3 != ""){
				if($_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['size'] > 0){
					//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['size']/2048) <= 2048){
						// Inserir registro
						$anexo_chamado = new anexo_chamado();
						$anexo_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
						$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['name']);
						$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
						$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['name'])));
						$anexo_chamado->insert();
						if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
							$mensagemErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 3.";
						}
					//}else{
					//	$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['name'].", por exceder o tamanho de 2 Mb.";
					//}
				}else{
					$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['name'].". Arquivo não existe.";
				}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_4 != ""){
				if($_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['size'] > 0){
					//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['size']/2048) <= 2048){
						// Inserir registro
						$anexo_chamado = new anexo_chamado();
						$anexo_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
						$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['name']);
						$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
						$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['name'])));
						$anexo_chamado->insert();
						if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
							$mensagemErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 4.";
						}
					//}else{
					//	$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['name'].", por exceder o tamanho de 2 Mb.";
					//}
				}else{
					$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['name'].". Arquivo não existe.";
				}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_5 != ""){
				if($_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['size'] > 0){
					//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['size']/2048) <= 2048){
						// Inserir registro
						$anexo_chamado = new anexo_chamado();
						$anexo_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
						$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['name']);
						$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
						$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['name'])));
						$anexo_chamado->insert();
						if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
							$mensagemErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 5.";
						}
					//}else{
					//	$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['name'].", por exceder o tamanho de 2 Mb.";
					//}
				}else{
					$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['name'].". Arquivo não existe.";
				}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_6 != ""){
				if($_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['size'] > 0){
					//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['size']/2048) <= 2048){
						// Inserir registro
						$anexo_chamado = new anexo_chamado();
						$anexo_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
						$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['name']);
						$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
						$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['name'])));
						$anexo_chamado->insert();
						if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
							$mensagemErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 6.";
						}
					//}else{
					//	$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['name'].", por exceder o tamanho de 2 Mb.";
					//}
				}else{
					$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['name'].". Arquivo não existe.";
				}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_7 != ""){
				if($_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['size'] > 0){
					//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['size']/2048) <= 2048){
						// Inserir registro
						$anexo_chamado = new anexo_chamado();
						$anexo_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
						$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['name']);
						$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
						$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['name'])));
						$anexo_chamado->insert();
						if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
							$mensagemErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 7.";
						}
					//}else{
					//	$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['name'].", por exceder o tamanho de 2 Mb.";
					//}
				}else{
					$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['name'].". Arquivo não existe.";
				}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_8 != ""){
				if($_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['size'] > 0){
					//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['size']/2048) <= 2048){
						// Inserir registro
						$anexo_chamado = new anexo_chamado();
						$anexo_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
						$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['name']);
						$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
						$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['name'])));
						$anexo_chamado->insert();
						if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
							$mensagemErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 7.";
						}
					//}else{
					//	$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['name'].", por exceder o tamanho de 2 Mb.";
					//}
				}else{
					$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['name'].". Arquivo não existe.";
				}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_9 != ""){
				if($_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['size'] > 0){
					//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['size']/2048) <= 2048){
						// Inserir registro
						$anexo_chamado = new anexo_chamado();
						$anexo_chamado->setSEQ_CHAMADO($chamado->SEQ_CHAMADO);
						$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['name']);
						$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
						$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['name'])));
						$anexo_chamado->insert();
						if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
							$mensagemErro .= "Registro Incluído com sucesso, mas ocorreu um erro no momento do upload do arquivo nº 7.";
						}
					//}else{
					//	$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['name'].", por exceder o tamanho de 2 Mb.";
					//}
				}else{
					$mensagemErro .= "Chamado cadastrado, mas não foi possível carregar o arquivo ".$_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['name'].". Arquivo não existe.";
				}
			}

			if($v_SELECAO_DESTINO == "1" && $v_INICIA_ATENDIMENTO == "S"){
				// Redirecionar para a página de confirmação
				$pagina->redirectTo("ChamadoTriagem.php?v_SEQ_CHAMADO=$chamado->SEQ_CHAMADO&vErroCampos=$mensagemErro");
			}else{
				// Redirecionar para a página de confirmação
				$pagina->redirectTo("ChamadoConfirmacao.php?v_SEQ_CHAMADO=$chamado->SEQ_CHAMADO&mensagemErro=$mensagemErro");
			}
		}else{
			$mensagemErro .= "Registro não incluído. O seguinte erro ocorreu:<br> $chamado->error";
		}
	}else{
		$mensagemErro .= "Os seguintes campos são obrigatórios: ".$mensagemErro;
	}
}

// ============================================================================================================
// Início da página
// ============================================================================================================

// ============================================================================================================
// Configurações AJAX
// ============================================================================================================
require_once 'include/PHP/class/class.Sajax.php';
$Sajax = new Sajax();

function CarregarComboTipoChamado($v_SEQ_TIPO_OCORRENCIA,$v_SEQ_CENTRAL_ATENDIMENTO){
	if($v_SEQ_TIPO_OCORRENCIA != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.tipo_chamado.php';
		$pagina = new Pagina();
		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		$tipo_chamado->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
		return $pagina->AjaxFormataArrayCombo($tipo_chamado->combo("DSC_TIPO_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboSubtipoChamado($v_SEQ_TIPO_CHAMADO, $v_SEQ_TIPO_OCORRENCIA){
	if($v_SEQ_TIPO_CHAMADO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.subtipo_chamado.php';
		$pagina = new Pagina();
		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
		$subtipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		//$subtipo_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
		return $pagina->AjaxFormataArrayCombo($subtipo_chamado->combo("DSC_SUBTIPO_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboAtividade($v_SEQ_SUBTIPO_CHAMADO, $v_SEQ_TIPO_OCORRENCIA){
	if($v_SEQ_SUBTIPO_CHAMADO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.atividade_chamado.php';
		$pagina = new Pagina();
		$atividade_chamado = new atividade_chamado();
		$atividade_chamado->setSEQ_SUBTIPO_CHAMADO($v_SEQ_SUBTIPO_CHAMADO);
		$atividade_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		
		require_once '../gestaoti/include/PHP/class/class.parametro.php';
		$parametro = new parametro();
		//$parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRANSPORTE_ESPECIAL");
		$atividade_chamado->setSEQ_ATIVIDADE_CHAMADO_REMOVER($parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRANSPORTE_ESPECIAL"));
		
		//$atividade_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
		return $pagina->AjaxFormataArrayCombo($atividade_chamado->combo("DSC_ATIVIDADE_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboSistemaInformacao(){
	require_once 'include/PHP/class/class.pagina.php';
	require_once 'include/PHP/class/class.item_configuracao.php';
	$pagina = new Pagina();
	$item_configuracao = new item_configuracao();
	//$item_configuracao->setNUM_MATRICULA_GESTOR($_SESSION["NUM_MATRICULA_RECURSO"]);
	return $pagina->AjaxFormataArrayCombo($item_configuracao->combo("SIG_ITEM_CONFIGURACAO"));
}

function CarregarComboEdificacao($v_COD_DEPENDENCIA){
	require_once 'include/PHP/class/class.pagina.php';
	require_once 'include/PHP/class/class.edificacao.php';
	$pagina = new Pagina();
	$edificacao = new edificacao();
	$edificacao->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
	return $pagina->AjaxFormataArrayCombo($edificacao->comboSimples("NOM_EDIFICACAO"));
}

function CarregarComboLocalFisico($v_SEQ_EDIFICACAO){
	if($v_SEQ_EDIFICACAO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.localizacao_fisica.php';
		$pagina = new Pagina();
		$localizacao_fisica = new localizacao_fisica();
		$localizacao_fisica->setSEQ_EDIFICACAO($v_SEQ_EDIFICACAO);
		return $pagina->AjaxFormataArrayCombo($localizacao_fisica->combo("NOM_LOCALIZACAO_FISICA"));
	}else{
		return "";
	}
}

function ValidarPessoaContato($v_NUM_MATRICULA_CONTATO){
	if($v_NUM_MATRICULA_CONTATO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$pagina = new Pagina();
		$empregados = new empregados();
		$v_NUM_MATRICULA_CONTATO = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_CONTATO);
		$empregados->select($v_NUM_MATRICULA_CONTATO);
		if($empregados->NOME != ""){
			return rawurlencode($v_NUM_MATRICULA_CONTATO."|".$empregados->NOME."|".$empregados->NUM_DDD."-".$empregados->NUM_VOIP);
		}else{
			return "";
		}
	}else{
		return "";
	}
}

function ValidarPatrimonio($v_NUM_PATRIMONIO){
	require_once 'include/PHP/class/class.patrimonio_ti.ativos.php';
	$ativos = new ativos();
	$ativos->select($v_NUM_PATRIMONIO);
	if($ativos->NUM_PATRIMONIO != ""){
		return rawurlencode($ativos->NOM_BEM."|".$ativos->NOM_MODELO."|".$ativos->DSC_LOCALIZACAO."|".$v_NUM_PATRIMONIO);
	}else{
		return "";
	}
}

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

function CarregarComboEquipeAtribuicao($v_SEQ_EQUIPE_TI){
	if($v_SEQ_EQUIPE_TI != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.equipe_atribuicao.php';
		$pagina = new Pagina();
		$equipe_atribuicao = new equipe_atribuicao();
		$equipe_atribuicao->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
		return $pagina->AjaxFormataArrayCombo($equipe_atribuicao->combo("DSC_EQUIPE_ATRIBUICAO"));
	}else{
		return "";
	}
}

function CarregarComboProfissional($v_SEQ_EQUIPE_TI){
	//if($v_SEQ_EQUIPE_TI != "" && $v_SEQ_EQUIPE_TI == $_SESSION["SEQ_EQUIPE_TI"]){
	if($v_SEQ_EQUIPE_TI != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.recurso_ti.php';
		$pagina = new Pagina();
		$recurso_ti = new recurso_ti();
		$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
		return $pagina->AjaxFormataArrayCombo($recurso_ti->combo("NOME"));
	}else{
		return "";
	}
}

function BuscarAtribuicaoAutomatica($v_SEQ_ATIVIDADE_CHAMADO){
	$aRetorno = Array();
	if($v_SEQ_ATIVIDADE_CHAMADO == ""){
		$aRetorno[0] = "";
		$aRetorno[1] = "";
		$aRetorno[2] = "";
		$aRetorno[3] = "Selecione a atividade";

		return $aRetorno;
	}else{
		require_once 'include/PHP/class/class.atividade_chamado.php';
		require_once 'include/PHP/class/class.tipo_ocorrencia.php';

		$atividade_chamado = new atividade_chamado();
		$tipo_ocorrencia = new tipo_ocorrencia();
		$atividade_chamado->select($v_SEQ_ATIVIDADE_CHAMADO);
		if($atividade_chamado->SEQ_EQUIPE_TI != ""){
			// Buscar o nome da equipe
			require_once 'include/PHP/class/class.equipe_ti.php';
			$equipe_ti = new equipe_ti();
			$equipe_ti->select($atividade_chamado->SEQ_EQUIPE_TI);

			$aRetorno[0] = $atividade_chamado->SEQ_EQUIPE_TI;
			$aRetorno[1] = $equipe_ti->NOM_EQUIPE_TI;
			$aRetorno[2] = rawurlencode($atividade_chamado->TXT_ATIVIDADE);
		}else{
			$aRetorno[0] = "";
			$aRetorno[1] = "";
			$aRetorno[2] = "";
		}
		$vInfoAtividade = "";
		if($atividade_chamado->QTD_MIN_SLA_ATENDIMENTO == ""){
			$vInfoAtividade = "Medição de tempo: Horas Planejadas. ";
		}else{
			if($atividade_chamado->FLG_FORMA_MEDICAO_TEMPO == "C"){
				$vInfoAtividade = "Medição de tempo: Horas Corridas. ";
			}else{
				$vInfoAtividade = "Medição de tempo: Horas Úteis. ";
			}
			if($tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE == $atividade_chamado->SEQ_TIPO_OCORRENCIA){
				$vInfoAtividade .= "Tempo de Contingenciamento Máximo: ".$atividade_chamado->QTD_MIN_SLA_ATENDIMENTO." min. Solução Definitiva: ".$atividade_chamado->QTD_MIN_SLA_SOLUCAO_FINAL." min. ";
			}else{
				$vInfoAtividade .= "Tempo de Atendimento Máximo: ".$atividade_chamado->QTD_MIN_SLA_ATENDIMENTO." min. ";
			}
		}

		if($atividade_chamado->FLG_ATENDIMENTO_EXTERNO == "S"){
			$vInfoAtividade .= "Atendimento Externo.";
		}else{
			$vInfoAtividade .= "Atividade Interna.";
		}
                
                if($atividade_chamado->FLG_EXIGE_APROVACAO == "1"){
                        $vInfoAtividade .= " <font color=red><b>Exige Aprovação.</b></font>.";
                        if($atividade_chamado->NUM_MATRICULA_APROVADOR != ""){
                            require_once 'include/PHP/class/class.empregados.oracle.php';
                            $empregados = new empregados();
                            $vInfoAtividade .= " <b>Aprovador:</b> ".$empregados->GetNomeEmpregado($atividade_chamado->NUM_MATRICULA_APROVADOR).".";
                        }
                        if($atividade_chamado->NUM_MATRICULA_APROVADOR_SUBSTITUTO != ""){
                            require_once 'include/PHP/class/class.empregados.oracle.php';
                            $empregados = new empregados();
                            $vInfoAtividade .= " <b>Aprovador substituto:</b> ".$empregados->GetNomeEmpregado($atividade_chamado->NUM_MATRICULA_APROVADOR_SUBSTITUTO).".";
                        }
		}

		$aRetorno[3] = rawurlencode($vInfoAtividade);

		return $aRetorno;
	}
}

$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export("CarregarComboSubtipoChamado", "CarregarComboAtividade", "CarregarComboEdificacao", "CarregarComboLocalFisico", "ValidarPessoaContato", "CarregarComboEquipe", "CarregarComboEquipeAtribuicao", "CarregarComboProfissional", "CarregarComboSistemaInformacao", "ValidarPatrimonio", "BuscarAtribuicaoAutomatica", "CarregarComboTipoChamado");
$Sajax->sajax_handle_client_request();

// ============================================================================================================
// Configuração da págína
// ============================================================================================================
require_once '../gestaoti/include/PHP/class/class.tipo_chamado.php';
require_once '../gestaoti/include/PHP/class/class.subtipo_chamado.php';
$tipo_chamado = new tipo_chamado();
$subtipo_chamado = new subtipo_chamado();

$pagina->SettituloCabecalho("Abrir Chamado"); // Indica o título do cabeçalho da página
$pagina->method = "post";
$pagina->MontaCabecalho(1);
print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("SEQ_CENTRAL_ATENDIMENTO", $_SEQ_CENTRAL_ATENDIMENTO);
 
print $pagina->CampoHidden("SEQ_TIPO_OCORRENCIA_SOLICITACAO", $pagina->SEQ_TIPO_OCORRENCIA_SOLICITACAO);  
print $pagina->CampoHidden2("SEQ_CENTRAL_ATIVIDADES_AUXILIARES", $pagina->SEQ_CENTRAL_ATIVIDADES_AUXILIARES);
print $pagina->CampoHidden2("SEQ_CLASSE_CHAMADO_AR_CONDICIONADO", $pagina->SEQ_CLASSE_CHAMADO_AR_CONDICIONADO);
print $pagina->CampoHidden2("SEQ_CLASSE_CHAMADO_AUDITORIO", $pagina->SEQ_CLASSE_CHAMADO_AUDITORIO);
print $pagina->CampoHidden2("SEQ_CLASSE_CHAMADO_CARIMBO", $pagina->SEQ_CLASSE_CHAMADO_CARIMBO);
print $pagina->CampoHidden2("SEQ_CLASSE_CHAMADO_CELULAR", $pagina->SEQ_CLASSE_CHAMADO_CELULAR);
print $pagina->CampoHidden2("SEQ_CLASSE_CHAMADO_CHAVEIRO", $pagina->SEQ_CLASSE_CHAMADO_CHAVEIRO);
print $pagina->CampoHidden2("SEQ_CLASSE_CHAMADO_TRANSPORTE", $pagina->SEQ_CLASSE_CHAMADO_TRANSPORTE);

// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================
?>
<script language="javascript">
	<?
	$Sajax->sajax_show_javascript();
	?>
	// Chamada
	function do_CarregarComboTipoChamado() {		
		x_CarregarComboTipoChamado(document.form.v_SEQ_TIPO_OCORRENCIA.value,document.form.SEQ_CENTRAL_ATENDIMENTO.value, retorno_CarregarComboTipoChamado);
		configurarTelabyAtividade();
	}
	// Retorno
	function retorno_CarregarComboTipoChamado(val) {
		fEncheComboBox(val, document.form.v_SEQ_TIPO_CHAMADO);
		do_CarregarComboSubtipoChamado();
	}
	// Chamada
	function do_CarregarComboSubtipoChamado() {
		if(document.form.v_SEQ_TIPO_CHAMADO.value == "<?=$tipo_chamado->COD_TIPO_SISTEMAS_INFORMACAO?>"){
			document.getElementById("combo_sistema").style.display = "block";
			do_CarregarComboSistemaInformacao();
		}else{
			document.getElementById("combo_sistema").style.display = "none";
		}

		x_CarregarComboSubtipoChamado(document.form.v_SEQ_TIPO_CHAMADO.value, document.form.v_SEQ_TIPO_OCORRENCIA.value, retorno_CarregarComboSubtipoChamado);
		configurarTelabyAtividade();
	}
	// Retorno
	function retorno_CarregarComboSubtipoChamado(val) {
		fEncheComboBox(val, document.form.v_SEQ_SUBTIPO_CHAMADO);
	}
	// Chamada
	function do_CarregarComboAtividade() {
		x_CarregarComboAtividade(document.form.v_SEQ_SUBTIPO_CHAMADO.value, document.form.v_SEQ_TIPO_OCORRENCIA.value, retorno_CarregarComboAtividade);
	}
	// Retorno
	function retorno_CarregarComboAtividade(val) {
		fEncheComboBox(val, document.form.v_SEQ_ATIVIDADE_CHAMADO);
	}
	// Chamada
	function do_CarregarComboEdificacao() {
		x_CarregarComboEdificacao(document.form.v_COD_DEPENDENCIA.value, retorno_CarregarComboEdificacao);
	}
	// Retorno
	function retorno_CarregarComboEdificacao(val) {
		fEncheComboBox(val, document.form.v_SEQ_EDIFICACAO);
	}
	// Chamada
	function do_CarregarComboLocalFisico() {
		x_CarregarComboLocalFisico(document.form.v_SEQ_EDIFICACAO.value, retorno_CarregarComboLocalFisico);
	}
	// Retorno
	function retorno_CarregarComboLocalFisico(val) {
		fEncheComboBox(val, document.form.v_SEQ_LOCALIZACAO_FISICA);
	}
	// Chamada
	function do_ValidarSolicitante() {
		if(document.form.v_NUM_MATRICULA_SOLICITANTE.value != ""){
			//window.dados_solicitante.innerHTML = "carregando....";
			document.getElementById("dados_solicitante").innerHTML = "carregando....";
			v_NUM_MATRICULA_SOLICITANTE = document.form.v_NUM_MATRICULA_SOLICITANTE.value;
			//v_NUM_MATRICULA_SOLICITANTE = document.form.v_NUM_MATRICULA_SOLICITANTE.value.replace(/A-Z/i, '');
			//v_NUM_MATRICULA_SOLICITANTE = v_NUM_MATRICULA_SOLICITANTE.replace( /[^0-9\.]/, '' );
			x_ValidarPessoaContato(v_NUM_MATRICULA_SOLICITANTE, retorno_ValidarSolicitante);
		}
	}
	// Retorno
	function retorno_ValidarSolicitante(val) {
		// Separar os valores retornados
		if(val != ""){
			val = url_decode(val);
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
	// Chamada
	function do_ValidarPessoaContato() {
		if(document.form.v_NUM_MATRICULA_CONTATO.value != ""){
			//window.dados_pessoa_contato.innerHTML = "carregando....";
			document.getElementById("dados_pessoa_contato").innerHTML = "carregando....";
			v_NUM_MATRICULA_CONTATO = document.form.v_NUM_MATRICULA_CONTATO.value.replace(/A-Z/i, '');
			v_NUM_MATRICULA_CONTATO = v_NUM_MATRICULA_CONTATO.replace( /[^0-9\.]/, '' );
			x_ValidarPessoaContato(v_NUM_MATRICULA_CONTATO, retorno_ValidarPessoaContato);
		}
	}
	// Retorno
	function retorno_ValidarPessoaContato(val) {
		// Separar os valores retornados
		if(val != ""){
			val = url_decode(val);
			//  $v_NUM_MATRICULA_CONTATO."|".$empregados->NOME."|".$empregados->NUM_DDD."-".$empregados->NUM_VOIP;
			v_NUM_MATRICULA_CONTATO = val.substr(0, val.indexOf("|"));
			StringRestante = val.substr(val.indexOf("|")+1, val.length);
			v_NOME = StringRestante.substr(0, StringRestante.indexOf("|"));
			StringRestante = StringRestante.substr(StringRestante.indexOf("|")+1, StringRestante.length);
			v_TELEFONE = StringRestante;
			// Adicionar resultado ao formulário
			document.form.v_NUM_MATRICULA_CONTATO_REAL.value = v_NUM_MATRICULA_CONTATO;
			//window.dados_pessoa_contato.innerHTML = "Nome: <b>"+v_NOME+"</b> - Ramal: <b>"+v_TELEFONE+"</b>";
			document.getElementById("dados_pessoa_contato").innerHTML = "Nome: <b>"+v_NOME+"</b> - Ramal: <b>"+v_TELEFONE+"</b>";
		}else{
			alert("Pessoa não encontrada. Clique na imagem de lupa para efetuar uma pesquisa.");
			//window.dados_pessoa_contato.innerHTML = "Preencha este campo caso o atendimento não seja direcionado a pessoa autenticada no sistema.";
			document.getElementById("dados_pessoa_contato").innerHTML = "Preencha este campo caso o atendimento não seja direcionado a pessoa autenticada no sistema.";
			document.form.v_NUM_MATRICULA_CONTATO.value = "";
		}
	}
	// Chamada
	function do_ValidarPatrimonio(){
		if(document.form.v_NUM_PATRIMONIO.value != ""){
			if(!VerificarExistenciaValorCombo(document.form.v_PATRIMONIOS, document.form.v_NUM_PATRIMONIO.value)){
				document.getElementById("dados_patrimonio").innerHTML = "carregando....";
				x_ValidarPatrimonio(document.form.v_NUM_PATRIMONIO.value, retorno_ValidarPatrimonio);
			}else{
				alert("Patrimônio já adionado ao chamado");
			}
		}
	}
	// Retorno
	function retorno_ValidarPatrimonio(val) {
		// Separar os valores retornados
		document.getElementById("dados_patrimonio").innerHTML = "Preencha este campo com o número existente na plaqueta de patrimônio.";
		if(val != ""){
			//  $ativos->NOM_BEM."|".$ativos->NOM_MODELO."|".$ativos->NOM_DETENTOR
			val = url_decode(val);
			v_NOM_BEM = val.substr(0, val.indexOf("|"));
			StringRestante = val.substr(val.indexOf("|")+1, val.length);
			v_NOM_MODELO = StringRestante.substr(0, StringRestante.indexOf("|"));
			StringRestante = StringRestante.substr(StringRestante.indexOf("|")+1, StringRestante.length);
			v_NOM_DETENTOR = StringRestante.substr(0, StringRestante.indexOf("|"));
			StringRestante = StringRestante.substr(StringRestante.indexOf("|")+1, StringRestante.length);
			v_NUM_PATRIMONIO = StringRestante;
			// Adicionar resultado ao formulário
			document.getElementById("comboPatrimonio").style.display = "block";
			ValorCombo = v_NUM_PATRIMONIO+" - "+v_NOM_BEM+" - Local: "+v_NOM_DETENTOR;
			fAdicionaValorCombo(v_NUM_PATRIMONIO, ValorCombo, document.form.v_PATRIMONIOS);
			document.form.v_NUM_PATRIMONIO.value = "";
		}else{
			alert("Patrimônio não encontrado.");
			document.getElementById("v_NUM_PATRIMONIO").value = "";
		}
	}
	// Chamada
	function do_CarregarComboEquipe() {
		x_CarregarComboEquipe(document.form.v_COD_DEPENDENCIA_ATRIBUICAO.value, retorno_CarregarComboEquipe);
	}
	// Retorno
	function retorno_CarregarComboEquipe(val) {
		fEncheComboBox(val, document.form.v_SEQ_EQUIPE_TI);
	}
	// Chamada
	function do_CarregarComboEquipeAtribuicao() {
		//x_CarregarComboEquipeAtribuicao(document.form.v_SEQ_EQUIPE_TI.value, retorno_CarregarComboEquipeAtribuicao);
	}
	// Retorno
	function retorno_CarregarComboEquipeAtribuicao(val) {
		//fEncheComboBox(val, document.form.v_SEQ_EQUIPE_ATRIBUICAO);
	}
	// Chamada
	function do_CarregarComboProfissional() {
		x_CarregarComboProfissional(document.form.v_SEQ_EQUIPE_TI.value, retorno_CarregarComboProfissional);
	}
	// Retorno
	function retorno_CarregarComboProfissional(val) {
		if(val == ""){
			fEncheComboBoxPlus("", document.form.v_NUM_MATRICULA_RECURSO, "Profissionais - Selecione a sua equipe");
		}else{
			fEncheComboBox(val, document.form.v_NUM_MATRICULA_RECURSO);
		}
	}
	// Chamada
	function do_CarregarComboSistemaInformacao() {
		x_CarregarComboSistemaInformacao(retorno_CarregarComboSistemaInformacao);
	}
	// Retorno
	function retorno_CarregarComboSistemaInformacao(val) {
		fEncheComboBox(val, document.form.v_SEQ_ITEM_CONFIGURACAO);
	}
	// Chamada
	function do_BuscarAtribuicaoAutomatica() {
		// Excluir atribuições caso existam
		for (i=0; i < document.form.v_ATRIBUICAO.options.length; i++) {
			document.form.v_ATRIBUICAO.options[i].selected = true;
		}
		ExcluirAtribuicao();
		document.getElementById("comboAtribuicao").style.display = "none";
		document.getElementById("mensagem_start").style.display = "block";

		x_BuscarAtribuicaoAutomatica(document.form.v_SEQ_ATIVIDADE_CHAMADO.value, retorno_BuscarAtribuicaoAutomatica);
	}
	// Retorno
	function retorno_BuscarAtribuicaoAutomatica(val) {
		//if(val){
		//	AtribuirEquipeProfissionalAutomatico(val[0], val[1], val[2]);
		//}
		document.form.v_SEQ_EQUIPE_TI.value = val[0];
		document.form.v_NUM_MATRICULA_RECURSO.value = "";
		document.form.v_TXT_ATIVIDADE.value = url_decode(val[2]);
		document.getElementById('info_atividade').innerHTML = url_decode(val[3]);
	}
	// ==================================================== FIM AJAX =====================================

	function ExcluirPatrimonio(){
		retorno = fExcluirValorCombo(document.form.v_PATRIMONIOS);
		if(document.form.v_PATRIMONIOS.options.length == 0){
			document.getElementById("comboPatrimonio").style.display = "none";
		}
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

	function ExcluirArquivo($ID){
		document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).value = "";
		document.getElementById("file"+$ID).style.display = "none";
		document.getElementById("Newfile"+$ID).style.display = "none";
	}

	function AtribuirEquipeProfissional(){
		/*
		if(document.form.v_COD_DEPENDENCIA_ATRIBUICAO.value == ""){
			alert("Selecione a dependência da equipe de TI");
			document.form.v_COD_DEPENDENCIA_ATRIBUICAO.focus();
			return;
		}
		*/
		if(document.form.v_SEQ_EQUIPE_TI.value == ""){
			alert("Selecione a equipe de TI");
			document.form.v_SEQ_EQUIPE_TI.focus();
			return;
		}
		if(document.form.v_TXT_ATIVIDADE.value == ""){
			alert("Informe as atividades a serem desempenhadas");
			document.form.v_TXT_ATIVIDADE.focus();
			return;
		}
		document.getElementById("comboAtribuicao").style.display = "block";
		document.getElementById("mensagem_start").style.display = "none";
//		vCodigo = document.form.v_SEQ_EQUIPE_TI.value+"|"+document.form.v_NUM_MATRICULA_RECURSO.value+"|"+document.form.v_TXT_ATIVIDADE.value+"|"+document.form.v_SEQ_EQUIPE_ATRIBUICAO.value;
		vCodigo = document.form.v_SEQ_EQUIPE_TI.value+"|"+document.form.v_NUM_MATRICULA_RECURSO.value+"|"+document.form.v_TXT_ATIVIDADE.value;
		vCodigoValidacao = document.form.v_SEQ_EQUIPE_TI.value+"|"+document.form.v_NUM_MATRICULA_RECURSO.value;
		//vText = "Dependência: "+GetTextItemCombo(document.form.v_COD_DEPENDENCIA_ATRIBUICAO);
		vText = "";
		vText = vText + " - Equipe: "+GetTextItemCombo(document.form.v_SEQ_EQUIPE_TI);
		if(document.form.v_NUM_MATRICULA_RECURSO.value != ""){
			vText = vText + " - Proffissional: "+GetTextItemCombo(document.form.v_NUM_MATRICULA_RECURSO);
		}
//		vText = vText + " - Atribuição: "+GetTextItemCombo(document.form.v_SEQ_EQUIPE_ATRIBUICAO);
		vText = vText + " - Atividades: "+document.form.v_TXT_ATIVIDADE.value;
		if(!VerificarExistenciaValorCombo(document.form.v_ATRIBUICAO_VALIDACAO, vCodigoValidacao)){
			fAdicionaValorCombo(vCodigo, url_encode(vText), document.form.v_ATRIBUICAO);
			fAdicionaValorCombo(vCodigoValidacao, url_encode(vText), document.form.v_ATRIBUICAO_VALIDACAO);
		}else{
			alert("Atribuição já realizada");
		}
	}

	function AtribuirEquipeProfissionalAutomatico(v_SEQ_EQUIPE_TI, v_NOM_EQUIPE_TI, v_TXT_ATIVIDADE){
		document.getElementById("comboAtribuicao").style.display = "block";
		document.getElementById("mensagem_start").style.display = "none";
		vCodigo = v_SEQ_EQUIPE_TI+"||"+v_TXT_ATIVIDADE+"|";
		vCodigoValidacao = v_SEQ_EQUIPE_TI+"|";
		vText = "";
		vText = vText + " - Equipe: "+v_NOM_EQUIPE_TI;
		vText = vText + " - Atividades: "+v_TXT_ATIVIDADE;
		if(!VerificarExistenciaValorCombo(document.form.v_ATRIBUICAO_VALIDACAO, vCodigoValidacao)){
			fAdicionaValorCombo(vCodigo, url_encode(vText), document.form.v_ATRIBUICAO);
			fAdicionaValorCombo(vCodigoValidacao, url_encode(vText), document.form.v_ATRIBUICAO_VALIDACAO);
		}
	}

	function ExcluirAtribuicao(){
		cont = 0;
		for (i=0; i < document.form.v_ATRIBUICAO.options.length; i++) {
			if(document.form.v_ATRIBUICAO.options[i].selected == true){
				document.form.v_ATRIBUICAO_VALIDACAO.options.remove(i-cont);
				cont++;
			}
		}
		retorno = fExcluirValorCombo(document.form.v_ATRIBUICAO);
		retorno = fExcluirValorCombo(document.form.v_ATRIBUICAO_VALIDACAO);
		if(document.form.v_ATRIBUICAO.options.length == 0){
			document.getElementById("comboAtribuicao").style.display = "none";
			document.getElementById("mensagem_start").style.display = "block";
		}
		if(cont == 0){
		//	alert("Para exclusão, é necessário selecionar pelo menos uma atribuição");
		}
	}


	function fValidaFormLocal(){
		// Verificar se a pessoa de contato foi validada
		//if(document.form.v_NUM_MATRICULA_CONTATO.value != "" &&  document.form.v_NUM_MATRICULA_CONTATO_REAL.value == ""){
		//	do_ValidarPessoaContato();
		//}

		// Validar campos
		if(document.form.v_SEQ_TIPO_OCORRENCIA.value == ""){
			alert("Preencha o campo Tipo");
			document.form.v_SEQ_TIPO_OCORRENCIA.focus();
			return false;
		}
		if(document.form.v_SEQ_TIPO_CHAMADO.value == ""){
			alert("Preencha o campo Classe");
			document.form.v_SEQ_TIPO_CHAMADO.focus();
			return false;
		}
		if(document.form.v_SEQ_SUBTIPO_CHAMADO.value == ""){
			alert("Preencha o campo Subclasse");
			document.form.v_SEQ_TIPO_CHAMADO.focus();
			return false;
		}
		if(document.form.v_SEQ_ATIVIDADE_CHAMADO.value == ""){
			alert("Preencha o campo Atividade");
			document.form.v_SEQ_TIPO_CHAMADO.focus();
			return false;
		}
		if(document.form.v_SEQ_PRIORIDADE_CHAMADO.value == ""){
			alert("Preencha o campo Prioridade");
			document.form.v_SEQ_PRIORIDADE_CHAMADO.focus();
			return false;
		}
		if(document.form.v_TXT_CHAMADO.value == ""){
			alert("Preencha o campo Solicitação");
			document.form.v_SEQ_TIPO_CHAMADO.focus();
			return false;
		}

		if(document.form.SEQ_CENTRAL_ATENDIMENTO.value == document.form.SEQ_CENTRAL_ATIVIDADES_AUXILIARES.value){

			if(document.form.v_SEQ_TIPO_CHAMADO.value != '' && document.form.v_SEQ_TIPO_CHAMADO.value == document.form.SEQ_CLASSE_CHAMADO_CELULAR.value){			 

				if(document.form.v_DATA_INICIO_UTILIZACAO_APARELHO.value == ""){
					alert("Preencha o campo Data de Início Perídodo de Utilização");
					document.form.v_DATA_INICIO_UTILIZACAO_APARELHO.focus();
					return false;
				}

				if(document.form.v_DATA_FIM_UTILIZACAO_APARELHO.value == ""){
					alert("Preencha o campo Data fim Perídodo de Utilização");
					document.form.v_DATA_FIM_UTILIZACAO_APARELHO.focus();
					return false;
				}
			} else if(document.form.v_SEQ_TIPO_CHAMADO.value != '' &&  document.form.v_SEQ_TIPO_CHAMADO.value == document.form.SEQ_CLASSE_CHAMADO_AUDITORIO.value){
				if(document.form.v_TXT_OBEJTIVO_EVENTO.value == ""){
					alert("Preencha o campo Objetivo do Evento");
					document.form.v_TXT_OBEJTIVO_EVENTO.focus();
					return false;
				}
				if(document.form.v_DATA_RESERVA_EVENTO.value == ""){
					alert("Preencha o campo Data Reserva");
					document.form.v_DATA_RESERVA_EVENTO.focus();
					return false;
				}
				if(document.form.v_HORA_RESERVA_EVENTO.value == ""){
					alert("Preencha o campo Hora Reserva");
					document.form.v_HORA_RESERVA_EVENTO.focus();
					return false;
				}
				if(document.form.v_QUANTIDADE_PESSOAS.value == ""){
					alert("Preencha o campo Quantidade de Pessoas");
					document.form.v_QUANTIDADE_PESSOAS.focus();
					return false;
				}
				if(document.form.v_TXT_SERVICOS.value == ""){
					alert("Preencha o campo Serviços");
					document.form.v_TXT_SERVICOS.focus();
					return false;
				}
			}  

		}
		
		 
		// Validar sistema de informação
		if(document.form.v_SEQ_TIPO_CHAMADO.value == "<?=$tipo_chamado->COD_TIPO_SISTEMAS_INFORMACAO?>"){
			if(document.form.v_SEQ_ITEM_CONFIGURACAO.value == ""){
				alert("Preencha o campo Sistema de Informação");
				document.form.v_SEQ_ITEM_CONFIGURACAO.focus();
				return false;
			}
		}

		
		// Selecionar todos os patrimônios
		vPatrimonios = "";
		for (i = 0; i < document.form.v_PATRIMONIOS.options.length; i++){
			vPatrimonios = vPatrimonios + document.form.v_PATRIMONIOS.options[i].value;
			if(i!=document.form.v_PATRIMONIOS.options.length-1){
				vPatrimonios = vPatrimonios+",";
			}
		}
		document.form.v_LISTA_PATRIMONIOS.value=vPatrimonios;

		// Selecionar todos profissionais/equipes atribuidas
		if(document.form.v_SELECAO_DESTINO.value == "2"){
			 vAtribuicao = "";
			 for (i = 0; i < document.form.v_ATRIBUICAO.options.length; i++){
				vAtribuicao = vAtribuicao + document.form.v_ATRIBUICAO.options[i].value;
				if(i!=document.form.v_ATRIBUICAO.options.length-1){
					vAtribuicao = vAtribuicao+"$&";
				}
			 }

			 if(vAtribuicao == ""){
			 	alert("Atribua o chamado a pelo menos uma equipe");
			 	return false;
			 }else{
			 	document.form.v_LISTA_ATRIBUICAO.value=vAtribuicao;
			 }
		}else{
			if(confirm("Confirma a abertura do chamado para o primeiro nível de atendimento?")==false){
				return false;
			}
		}

		// Desabilitar o botão e enviar
		document.form.enviar.disabled = true;
		return true;
	}

	// Seleciona os tipos de atendimento
	function SelecionaAtendimento(){
		if(document.form.v_SELECAO_DESTINO.value == "2"){
			document.getElementById("tabelaTriagem").style.display = "block";
			document.getElementById("tabela1nivel").style.display = "none";
		}else{
			document.getElementById("tabelaTriagem").style.display = "none";
			document.getElementById("tabela1nivel").style.display = "block";
		}
	}



	function configurarTelabyAtividade() {
		//alert('configurarTelabyAtividade');
		if(document.form.v_SEQ_TIPO_OCORRENCIA.value == document.form.SEQ_TIPO_OCORRENCIA_SOLICITACAO.value &&
				document.form.SEQ_CENTRAL_ATENDIMENTO.value == document.form.SEQ_CENTRAL_ATIVIDADES_AUXILIARES.value){			
			if(document.form.v_SEQ_TIPO_CHAMADO.value != '' &&  document.form.v_SEQ_TIPO_CHAMADO.value == document.form.SEQ_CLASSE_CHAMADO_CELULAR.value){
					document.getElementById("CAMPOS_CELULAR").style.display = "block";
					document.getElementById("CAMPOS_AUDITORIO").style.display = "none";
			}  else if(document.form.v_SEQ_TIPO_CHAMADO.value != '' &&  document.form.v_SEQ_TIPO_CHAMADO.value == document.form.SEQ_CLASSE_CHAMADO_CARIMBO.value){
				document.getElementById("CAMPOS_AUDITORIO").style.display = "none";
				document.getElementById("CAMPOS_CELULAR").style.display = "none";
			}  else if(document.form.v_SEQ_TIPO_CHAMADO.value != '' &&  document.form.v_SEQ_TIPO_CHAMADO.value == document.form.SEQ_CLASSE_CHAMADO_CHAVEIRO.value){
				document.getElementById("CAMPOS_AUDITORIO").style.display = "none";
				document.getElementById("CAMPOS_CELULAR").style.display = "none";
			}  else if(document.form.v_SEQ_TIPO_CHAMADO.value != '' &&  document.form.v_SEQ_TIPO_CHAMADO.value == document.form.SEQ_CLASSE_CHAMADO_TRANSPORTE.value){
				document.getElementById("CAMPOS_AUDITORIO").style.display = "none";
				document.getElementById("CAMPOS_CELULAR").style.display = "none";
			}  else if(document.form.v_SEQ_TIPO_CHAMADO.value != '' &&  document.form.v_SEQ_TIPO_CHAMADO.value == document.form.SEQ_CLASSE_CHAMADO_AUDITORIO.value){
				document.getElementById("CAMPOS_AUDITORIO").style.display = "block";
				document.getElementById("CAMPOS_CELULAR").style.display = "none";
			}  else if(document.form.v_SEQ_TIPO_CHAMADO.value != '' &&  document.form.v_SEQ_TIPO_CHAMADO.value == document.form.SEQ_CLASSE_CHAMADO_AR_CONDICIONADO.value){
				document.getElementById("CAMPOS_AUDITORIO").style.display = "none";
				document.getElementById("CAMPOS_CELULAR").style.display = "none";
			}   			
		}
		return true;
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
</script>
<style>
		#combo_multiple {
			font-family: Verdana;
			width: 776px;
			size: 3;
			font-size: 10px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#combo_patrimonio {
			font-family: Verdana;
			width: 576px;
			size: 3;
			font-size: 10px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#combo_equipe {
			width: 270px;
			font-family: Verdana;
			font-size: 11px;
			color: #000000;
			border-color: #F0F0F0;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#combo_equipe_atribuicao {
			width: 345px;
			font-family: Verdana;
			font-size: 11px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#combo_profissional {
			width: 344px;
			font-family: Verdana;
			font-size: 11px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#CampoSelect {
			font-family: Verdana;
			width: 480px;
			font-size: 10px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
	</style>
<?
if($mensagemErro != ""){
	$pagina->ScriptAlert($mensagemErro);
}
// ============================================================================================================
// Dados do solicitante
// ============================================================================================================
$pagina->AbreTabelaPadrao("left", "100%", "border=0 cellspacing=0 cellpading=0");
$pagina->LinhaCampoFormularioColspanDestaque("Dados do Solicitante", 2);
// Solicitante
print $pagina->CampoHidden("v_NUM_MATRICULA_SOLICITANTE_REAL", "");
$pagina->LinhaCampoFormulario("Solicitante:", "right", "N",
								  $pagina->CampoTexto("v_NUM_MATRICULA_SOLICITANTE", "N", "Login do solicitante" , "15", "20", $_SESSION["NOM_LOGIN_REDE"], "onBlur=\"do_ValidarSolicitante()\"").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_SOLICITANTE").
								  "&nbsp;
								  <span id=\"dados_solicitante\">
								  	Preencha este campo com a matrícula do solicitante.
								  </span>
								  "
								  , "left", "id=".$pagina->GetIdTable());
// ============================================================================================================
// Dados do chamado
// ============================================================================================================
$pagina->LinhaCampoFormularioColspanDestaque("Dados do Chamado", 2);

// Montar a combo da tabela tipo_chamado
require_once 'include/PHP/class/class.tipo_ocorrencia.php';
$tipo_ocorrencia = new tipo_ocorrencia();
//$tipo_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").":", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_OCORRENCIA", "S", "Classe", "S", $tipo_ocorrencia->combo(1, $v_SEQ_TIPO_OCORRENCIA), "Escolha", "do_CarregarComboTipoChamado()", "CampoSelect"), "left", "id=".$pagina->GetIdTable(), "20%");

// Montar a combo da tabela tipo_chamado
require_once 'include/PHP/class/class.tipo_chamado.php';
$aItemOption[] = array("", "", "Selecione o ".$pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA"));
$tipo_chamado = new tipo_chamado();
//$tipo_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_CHAMADO", "S", "Classe", "N", $aItemOption, "Escolha", "do_CarregarComboSubtipoChamado()", "CampoSelect"), "left", "id=".$pagina->GetIdTable(), "20%");

// Montar a combo da tabela subtipo_chamado
$aItemOption = Array();
$aItemOption[] = array("", "", "Selecione a ".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO"));
$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").":", "right", "S", $pagina->CampoSelect("v_SEQ_SUBTIPO_CHAMADO", "S", "Subclasse", "N", $aItemOption, "Escolha", "do_CarregarComboAtividade()", "CampoSelect"), "left", "id=".$pagina->GetIdTable());

// Montar a combo da tabela atividade
$aItemOption = Array();
$aItemOption[] = array("", "", "Selecione a ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO"));
$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").":", "right", "S", $pagina->CampoSelect("v_SEQ_ATIVIDADE_CHAMADO", "S", "Atividade", "N", $aItemOption, "Escolha", "do_BuscarAtribuicaoAutomatica()", "CampoSelect"), "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Informações sobre a ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").":", "right", "N","<span id=\"info_atividade\">Selecione a atividade</span>", "left", "id=".$pagina->GetIdTable());

// Montar a combo de Sistemas de Informação
$aItemOption = Array();
$aItemOption[] = array("", "", "Selecione o Sistema de Informação");
$pagina->LinhaCampoFormulario("Sistema de Informação:", "right", "S", $pagina->CampoSelect("v_SEQ_ITEM_CONFIGURACAO", "S", "Sistema de Informação", "N", $aItemOption), "left", " style=\"display: none\" id=\"combo_sistema\" class=".$pagina->GetIdTable());


// Montar a combo da tabela prioridade
require_once 'include/PHP/class/class.prioridade_chamado.php';
$prioridade_chamado = new prioridade_chamado();
$pagina->LinhaCampoFormulario("Prioridade:", "right", "S", $pagina->CampoSelect("v_SEQ_PRIORIDADE_CHAMADO", "S", "Prioridade", "N", $prioridade_chamado->combo("DSC_PRIORIDADE_CHAMADO", $prioridade_chamado->COD_Prioridade_Padrao)), "left", "id=".$pagina->GetIdTable());

// Descição do chamado
$pagina->LinhaCampoFormulario("Solicitação:", "right", "S",
								  $pagina->CampoTextArea("v_TXT_CHAMADO", "S", "Solicitação", "99", "3", "", "onkeyup=\"ContaCaracteres(5000, this, document.getElementById('conta_caracteres'))\"").
								  "<br><span id=\"conta_caracteres\">5000</span> Caracteres restantes"
								  , "left", "id=".$pagina->GetIdTable());

						
// Localização
//require 'include/PHP/class/class.dependencias.php';
//$dependencias = new dependencias();
//$aItemOptionEdificacao = Array();
//if($_SESSION["DEP_SIGLA"] != ""){
//	$vCOD_DEPENDENCIA = $dependencias->GetCodDependencia($_SESSION["DEP_SIGLA"]);
	require_once 'include/PHP/class/class.edificacao.php';
	$edificacao = new edificacao();
//	$edificacao->setCOD_DEPENDENCIA($vCOD_DEPENDENCIA);
	$aItemOptionEdificacao = $edificacao->combo("SEQ_EDIFICACAO");
//}else{
//	$aItemOptionEdificacao[] = array("", "", "Selecione a dependência");
//}

$aItemOptionLocal = Array();
$aItemOptionLocal[] = array("", "", "Selecione a edificação");

$pagina->LinhaCampoFormulario("Localização:", "right", "N",
//				$pagina->CampoSelect("v_COD_DEPENDENCIA", "N", "Dependência", "N", $dependencias->comboSimples(2, $vCOD_DEPENDENCIA), "Escolha", "do_CarregarComboEdificacao()")." ".
				$pagina->CampoSelect("v_SEQ_EDIFICACAO", "N", "Edificação", "S", $aItemOptionEdificacao, "Escolha", "do_CarregarComboLocalFisico()")." ".
				$pagina->CampoSelect("v_SEQ_LOCALIZACAO_FISICA", "N", "Localização Física", "S", $aItemOptionLocal)
				, "left", "id=".$pagina->GetIdTable());


/* Contato
print $pagina->CampoHidden("v_NUM_MATRICULA_CONTATO_REAL", "");
$pagina->LinhaCampoFormulario("Mat. pessoa de contato:", "right", "N",
								  $pagina->CampoTexto("v_NUM_MATRICULA_CONTATO", "N", "Matrícula da pessoa de contato" , "10", "10", "", "onBlur=\"do_ValidarPessoaContato()\"").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_CONTATO").
								  "&nbsp;
								  <span id=\"dados_pessoa_contato\">
								  	Preencha este campo caso o atendimento não seja direcionado a pessoa autenticada no sistema.
								  </span>
								  "
								  , "left", "id=".$pagina->GetIdTable());

// Patromônios
*/

print $pagina->CampoHidden("v_LISTA_PATRIMONIOS", "");

if($pagina->flg_usar_funcionalidades_patrimonio == "S"){
        $pagina->LinhaCampoFormulario("Nº de patrimônio:", "right", "N",
              $pagina->CampoTexto("v_NUM_PATRIMONIO", "N", "Número do patrimônio" , "10", "10", "", "")."&nbsp;".
              $pagina->CampoButton("do_ValidarPatrimonio()", "Adicionar", "button").
              "&nbsp;
              <span id=\"dados_patrimonio\">
                    Preencha este campo com o número existente na plaqueta de patrimônio.
              </span>
              <span id=\"comboPatrimonio\" style=\"display: none\">
                            <select id=\"combo_patrimonio\" name=\"v_PATRIMONIOS\" multiple>
                            </select>
                            <div align=right>".$pagina->CampoButton("ExcluirPatrimonio()", "Excluir patrimônios selecionados", "button")."</div>
              </span>
              "
              , "left", "id=".$pagina->GetIdTable());
}

								  						  		  
								  
// CENTRAL DE SERVIÇOS AUXILIARES 
if($_SEQ_CENTRAL_ATENDIMENTO == $pagina->SEQ_CENTRAL_ATIVIDADES_AUXILIARES){
	
 
	print "<tr align=left><td colspan=2  align=left>";
	
	print " <div id=\"CAMPOS_CELULAR\" style=\"display: none\">";	
	$pagina->AbreTabelaPadrao("left", "100%", "id=tabela1nivel cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	
	
	// SERVIÇOS DE CELULAR
	$pagina->LinhaCampoFormulario("Perídodo de Utilização do Aparelho:", "right", "S",
				$pagina->CampoData("v_DATA_INICIO_UTILIZACAO_APARELHO", "N", "Data de início de Utilização do Aparelho", $v_DATA_INICIO_UTILIZACAO_APARELHO,"")
				." ". $pagina->CampoData("v_DATA_FIM_UTILIZACAO_APARELHO", "N", "Data fim de Utilização do Aparelho", $v_DATA_FIM_UTILIZACAO_APARELHO,"")			 
				, "left", "id=".$pagina->GetIdTable(),"0%","80%");
	
	$pagina->FechaTabelaPadrao();			
	print "</div>";
				
	print " <div id=\"CAMPOS_AUDITORIO\" style=\"display: none\">";
	$pagina->AbreTabelaPadrao("left", "100%", "id=tabela1nivel cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");			
	// SERVIÇOS DE AUDTORIO
	$pagina->LinhaCampoFormulario("Objetivo do Evento:", "right", "S",
									  $pagina->CampoTextArea("v_TXT_OBEJTIVO_EVENTO", "S", "Objetivo do Evento", "99", "3", "", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable(),"0%","80%");
									  
									  
	$pagina->LinhaCampoFormulario("Data/Hora Reserva:", "right", "S",
				$pagina->CampoData("v_DATA_RESERVA_EVENTO", "N", "Data de execução ", $v_DATA_RESERVA_EVENTO,"")
				." Hora ". $pagina->CampoHora("v_HORA_RESERVA_EVENTO", "N", "Hora de execução ", $v_HORA_RESERVA_EVENTO,"")			 
				, "left", "id=".$pagina->GetIdTable(),"0%","80%");
				
	$pagina->LinhaCampoFormulario("Quantidade de Pessoas:", "right", "S", $pagina->CampoInt("v_QUANTIDADE_PESSOAS", "S", "Quantidade de Pessoas", "10", $v_QUANTIDADE_PESSOAS, ""),
	 "left", "id=".$pagina->GetIdTable(),"0%","80%");
	
									  
	$pagina->LinhaCampoFormulario("Serviços:", "right", "S",
									  $pagina->CampoTextArea("v_TXT_SERVICOS", "S", "Serviços", "99", "3", "", "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable(),"0%","80%");
									  
									  
									  
									  
$pagina->LinhaCampoFormularioColspan("center", "<div align=center><font color=red size=2>Os serviços de TI devem ser solicitados com antencedência para a CTEC</font>  </div>" , 2);
									  
	$pagina->FechaTabelaPadrao();
	print "</div>";								  
				

	
	print "</tr></td>";
}	
// Anexos
$pagina->LinhaCampoFormulario("Anexo(s):", "right", "N",
								  $pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_1", "N", "", "40").
								  "
								  <span id=\"Newfile1\">
								  	<a href=\"javascript: AnexaNovoArquivo(1)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file2\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_2", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(2)\">Excluir</a>
								  </span>
								  <span id=\"Newfile2\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(2)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file3\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_3", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(3)\">Excluir</a>
								  </span>
								  <span id=\"Newfile3\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(3)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file4\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_4", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(4)\">Excluir</a>
								  </span>
								  <span id=\"Newfile4\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(4)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file5\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_5", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(5)\">Excluir</a>
								  </span>
								  <span id=\"Newfile5\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(5)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file6\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_6", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(6)\">Excluir</a>
								  </span>
								  <span id=\"Newfile6\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(6)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file7\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_7", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(7)\">Excluir</a>
								  </span>
								  <span id=\"Newfile7\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(7)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file8\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_8", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(8)\">Excluir</a>
								  </span>
								  <span id=\"Newfile8\" style=\"display: none\">
								  	<a href=\"javascript: AnexaNovoArquivo(8)\">Anexar outro arquivo</a>
								  </span>
					  <!-- ================================================================================= -->
								  <span id=\"file9\" style=\"display: none\">
										".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_9", "N", "", "40")."
										<a href=\"javascript: ExcluirArquivo(9)\">Excluir</a>
								  </span>
								  "
								  , "left", "id=".$pagina->GetIdTable());


								  
									  
								  
								  
								  
								  
								  
								  
								  
								  
// Montar a combo de seleção do detino do chamado
$aItemOption = Array();
$aItemOption[] = array("1", "Selected", "Atendimento de 1º nível");
$aItemOption[] = array("2", "", "Atendimento de 2º nível");
$pagina->LinhaCampoFormulario("Destinação do chamado:", "right", "S", $pagina->CampoSelect("v_SELECAO_DESTINO", "S", "DESTINO", "N", $aItemOption, "", "SelecionaAtendimento()"), "left", "id=".$pagina->GetIdTable());

print "<tr><td colspan=2>";

$pagina->AbreTabelaPadrao("center", "100%", "id=tabela1nivel cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
$pagina->LinhaCampoFormularioColspan("left", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
$pagina->CampoCheckboxSimples("v_INICIA_ATENDIMENTO", "S", "", "<span id=label>Iniciar o atendimento de 1º nível após a abertura do chamado</span>"), "2");
$pagina->FechaTabelaPadrao();

print "</tr></td>";

print "<tr><td colspan=2>";




//================================================================================================================================
// Encaminhar para o segundo nível
//================================================================================================================================

$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaTriagem style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");

$pagina->LinhaCampoFormularioColspanDestaque("Encaminhar chamado ao atendimento de 2º nível", 2);

$tabela = array();
$header = array();
// Data de Abertura

// Cabeçalho
$header = array();
$header[] = array("Equipe", "center", "50%", "header");
$header[] = array("Atividades a serem desempenhadas", "left", "50%", "header");
$header[] = array("&nbsp;", "left", "", "header");
$tabela[] = $header;

// Montar a combo da tabela equipe_ti
//	$dependencias = new dependencias();

require_once 'include/PHP/class/class.equipe_ti.php';
$equipe_ti = new equipe_ti();
$equipe_ti->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
//	$equipe_ti->setCOD_DEPENDENCIA($_SESSION["COD_DEPENDENCIA"]);

//$aItemOptionEquipeAtribuicao = Array();
//$aItemOptionEquipeAtribuicao[] = array("", "", "Atribuição da equipe designada - Selecione a equipe");

$aItemOptionProfissional = Array();
$aItemOptionProfissional[] = array("", "", "Profissionais - Selecione a sua equipe");

// Campos
$header = array();
$header[] = array(
//				$pagina->CampoSelect("v_COD_DEPENDENCIA_ATRIBUICAO", "N", "Dependencia", "S", $dependencias->comboSimplesEquipe("SG_DEPENDENCIA", $_SESSION["COD_DEPENDENCIA"]), "Escolha", "do_CarregarComboEquipe()")."&nbsp;".
//			$pagina->CampoSelect("v_SEQ_EQUIPE_TI", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Escolha", "do_CarregarComboEquipeAtribuicao(); do_CarregarComboProfissional()", "combo_equipe").
//			$pagina->CampoSelect("v_NUM_MATRICULA_RECURSO", "N", "Profissional", "N", $aItemOptionProfissional, "Escolha", "", "combo_profissional")
//				  , "center", "", "");
				"Equipe:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$pagina->CampoSelect("v_SEQ_EQUIPE_TI", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Escolha", "do_CarregarComboProfissional();", "combo_profissional")
				."<br>".
				"Profissional:".$pagina->CampoSelect("v_NUM_MATRICULA_RECURSO", "N", "Profissional", "N", $aItemOptionProfissional, "Escolha", "", "combo_profissional")
					  , "center", "", "");


$header[] = array(
//		$pagina->CampoSelect("v_SEQ_EQUIPE_ATRIBUICAO", "N", "Atribuicao da equipe", "N", $aItemOptionEquipeAtribuicao, "Escolha", "", "combo_equipe_atribuicao")."<br>".
	$pagina->CampoTextArea("v_TXT_ATIVIDADE", "N", "center", 54, 2, "", "onkeyup=\"ContaCaracteres(900, this, document.getElementById('conta_caracteres_atividade'))\"").
							  "<br><span id=\"conta_caracteres_atividade\">900</span> Caracteres restantes"

	, "left", "", "");
$header[] = array($pagina->CampoButton("AtribuirEquipeProfissional()", "Adicionar", "button"), "left", "", "");
$tabela[] = $header;
$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
$pagina->LinhaCampoFormularioColspan("center", "<hr>", 2);

// Combo
print $pagina->CampoHidden("v_LISTA_ATRIBUICAO", "");
$pagina->LinhaCampoFormularioColspan("center",
"<span id=\"mensagem_start\">
  	Nenhuma equipe ou profissional atribuído.<br>Selecione uma equipe ou um profissional, especifique suas atividades e clique sobre o botão adicionar.
  </span>
  <span id=\"comboAtribuicao\" style=\"display: none\">
		<select id=\"combo_multiple\" name=\"v_ATRIBUICAO\" multiple></select>
		<div align=right>".$pagina->CampoButton("ExcluirAtribuicao()", "Excluir atribuições selecionadas", "button")."</div>
		<select id=\"combo_multiple\" name=\"v_ATRIBUICAO_VALIDACAO\"  style=\"display: none\" multiple></select>
  </span>
"
, 2);
$pagina->FechaTabelaPadrao();

//================================================================================================================================

print "</tr></td>";


$pagina->LinhaCampoFormularioColspan("center", "<hr><div align=left><font color=red>*</font> - Preenchimento obrigatório</div>".
												$pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormLocal(); ", " Salvar ")
, 2);

$pagina->FechaTabelaPadrao();

?>
<script language="javascript">
	do_CarregarComboLocalFisico();
	do_ValidarSolicitante();
</script>
<?
$pagina->MontaRodape();
?>