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
require_once 'include/PHP/class/class.phpmailer.php';
require_once 'include/PHP/class/class.situacao_rdm.php';
include_once("include/PHP/class/class.database.postgres.php");
include_once("include/PHP/class/class.perfil_acesso.php");

/*
* -------------------------------------------------------
* Nome da Classe:	rdm_email
* Data de criação:	19.1.2010
* Nome do Arquivo:	gestaoti/GeraPHP/include/PHP/class/class.rdm_email.php
* Nome da tabela:	-
* -------------------------------------------------------
*/

class rdm_email{
	
	
	var $pagina;
	var $RDM;
	
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página
	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	
	
	function rdm_email($pagina,$RDM){
		$this->pagina = $pagina;
		$this->RDM = $RDM;
		
		$this->database = new Database();
	}
	
	// **********************
	// GETTER METHODS
	// **********************
	function getrowCount(){
		return $this->rowCount;
	}

	function getvQtdRegistros(){
		return $this->vQtdRegistros;
	}
	
	// **********************
	// SETTER METHODS
	// **********************
	
	function setrowCount($val){
		$this->rowCount = $val;
	}

	function setvQtdRegistros($val){
		$this->vQtdRegistros = $val;
	}
	
	function sendEmailAberturaRDM(){
		$situacaoRDM = new situacao_rdm();		
		
		$array_teste = split(" ",$this->RDM->DATA_HORA_PREVISTA_EXECUCAO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		 
		
		$DATA_HORA_PREVISTA_EXECUCAO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		$TIPO_RDM = $this->iif($this->RDM->TIPO==1, "Normal", "Emergencial");
		
		$mail = new PHPMailer();
		$mail->From     = $this->pagina->EmailRemetente;
		$mail->FromName = $this->pagina->remetenteEmailCEA;
		$mail->Sender   = $this->pagina->EmailRemetente;
		$mail->Subject  = "CAU - Confirmação de Abertura de RDM";		
		
		$cidTitulo = date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."GM_Titulo.jpg",$cidTitulo,'GM_Titulo'); 
		
		$cidStatus = 'status'.date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."aberturaRDM.jpg",$cidStatus,'aberturaRDM'); 
		
		
		//$cidRodape = 'rodape'.date('YmdHms').'.'.time();  
		//$mail->AddEmbeddedImage($this->pagina->vPathImagens."CAU_Rodape.jpg",$cidRodape,'CAU_Rodape'); 
		
				
		$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
		if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
	    } else {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
	    }
		
	    require_once 'include/PHP/class/class.chamado_rdm.php';
		$chamado_rdm = new chamado_rdm();
		$chamado_rdm->setSEQ_RDM($this->RDM->SEQ_RDM);
		$chamado_rdm->selectParam("SEQ_CHAMADO");
		$chamadosAssociados ="";
		$QtdLinhas = 0;
		while ($row = pg_fetch_array($chamado_rdm->database->result)){
			$QtdLinhas++;
			
			if($chamadosAssociados=="" && $QtdLinhas==$chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"].".";
			}else if($chamadosAssociados=="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= ", ".$row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas == $chamado_rdm->database->rows){
				$chamadosAssociados .= " e ".$row["seq_chamado"].".";
			}				 
		}
		 
	
		$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
							    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
							<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
							<head>
								<title>.:: CAU - Gestão de Mudanças ::.</title>
  
								  <style type=\"text/css\">
									<!--
										#body {
											color: #000000;
											font-family: verdana; arial;
											font-size: 10pt;
											background-color: #ffffff;		 
										} 
									-->
								  </style>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
								<link href=\"".$this->pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
							</head>	
							<body bgcolor=\"#ffffff\" topmargin=\"10\" leftmargin=\"10\" marginwidth=\"10\" marginheight=\"10\">
							 
							
								<table border=\"0\" cellpadding=\"0\" style=\"border:solid 1px #999999;\" cellspacing=\"0\" width=\"514\">
								    <tr>      
								      <td  align=\"center\">  
								      
											<img src=\"cid:$cidTitulo\" border=\"0\">
											<br><br>
											
										</td>
									 </tr>	
									 <tr>
										<td>
											<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">
												<tr>
												  <td colspan=\"3\" align=\"center\"> <b> CAU - Confirmação de Abertura de RDM </b></td>
												</tr>  
												<tr>
												  <td colspan=\"3\" align=\"center\">&nbsp;</td>
												</tr>  
												<tr>
												  <td width=\"10\">&nbsp;</td>
												  <td colspan=\"2\"> 
													Prezado(a) usuário(a) <b> ".$_SESSION["NOME"]."</b>,
													<br><br>&nbsp;&nbsp;&nbsp;&nbsp;Sua RDM (Requisição de Mudança) foi registrada com o Nº <b>".$this->RDM->SEQ_RDM."</b>. 
													<br><br>
													<b>Informações da Mudança: </b>
													<br>
													<br>&nbsp;&nbsp;- Tipo: <b>".$TIPO_RDM." </b>
													<br>&nbsp;&nbsp;- Título: <b>".$this->RDM->TITULO." </b>
													<br>&nbsp;&nbsp;- Justificativa: <b>".$this->RDM->JUSTIFICATIVA." </b>
													<br>&nbsp;&nbsp;- Impacto de não executar: <b>".$this->RDM->IMPACTO_NAO_EXECUTAR."</b>. 
													<br>&nbsp;&nbsp;- Situação Atual: <b>".$situacaoRDM->getDescricao($this->RDM->SITUACAO_ATUAL)." </b>
													<br>&nbsp;&nbsp;- Responsável pela validação: <b>".$this->RDM->NOME_RESP_CHECKLIST." </b>
													<br>&nbsp;&nbsp;- Telefone: <b>".$this->RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".$this->RDM->NUMERO_TELEFONE_RESP_CHECKLIST."</b>
													<br>&nbsp;&nbsp;- Data/Hora prevista para execução: <b>".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b> 
													<br>&nbsp;&nbsp;- Chamado(s) associado(s): <b>".$chamadosAssociados." </b>
													<br><br>
													&nbsp;&nbsp;A RDM criada encontra-se na fase de <b>ABERTURA</b>. Para transitá-la para \"Aprovação\" é necessário enviar para aprovação no CAU. 
													
													<br><br>					
													<img src=\"cid:$cidStatus\" border=\"0\" width=\"500\">
													<br><br>
													
													A Central de Atendimento ao Usuário agradece o seu contato. Em caso de problemas ou solicitações, estamos à disposição para atendê-lo. 
													<br><br>
													Para maiores informações acesse o CAU. 
													<br>Esta é uma mensagem automática, favor não responder. 
													<br><br><br><hr width=\"450\" align=\"left\"> 
													<br>Gestão de Mudanças
													<br>Central de Atendimento ao Usuário - ".$this->pagina->nom_area_ti.". 
													<br>55 61 3429 7872 
													<br> <a href=\"".$this->pagina->enderecoGestaoTI."\"> ".$this->pagina->enderecoGestaoTI."</a>
													<br><br>
												  </td>
												</tr>  
											</table>
										</td>
									</tr>
									 <tr>
										<td   bgcolor=\"#00009C\" align=\"center\" height=\"25\">
											 	<font face=\"arial\" size=\"1\" color=\"#FFFFFF\" >
												<b>CAU - Central de atendimento ao usuário</b>
												</font>	
										</td> 
								    </tr>    
								  </table>
							
							
							
						    </body>
						   </html>
						   ";

		$mail->AddAddress($_SESSION["DES_EMAIL"], $_SESSION["NOME"]);
		$mail->Body    = $v_DS_CORPO;
		$mail->AltBody = $v_DS_CORPO;
		// Comentado para n~ao enviar e-mail antes do lan'camento oficial do sistema
		$mail->Send();
		$mail->ClearAddresses();
		
	}
	

	function sendEmailRDMEnviadaParaAprovacao(){
		$situacaoRDM = new situacao_rdm();	
		$array_teste = split(" ",$this->RDM->DATA_HORA_PREVISTA_EXECUCAO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		 
		
		$DATA_HORA_PREVISTA_EXECUCAO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		$TIPO_RDM = $this->iif($this->RDM->TIPO==1, "Normal", "Emergencial");
		
		//ENVIAR E_MAIL PARA SOLICITANTE
		$mail = new PHPMailer();
		$mail->From     = $this->pagina->EmailRemetente;
		$mail->FromName = $this->pagina->remetenteEmailCEA;
		$mail->Sender   = $this->pagina->EmailRemetente;
		$mail->Subject  = "CAU – Notificação de envio de RDM para aprovação";
		
		$cidTitulo = date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."GM_Titulo.jpg",$cidTitulo,'GM_Titulo'); 
		
		$cidStatus = 'status'.date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."RDMEnviadaParaAprovacao.jpg",$cidStatus,'RDMEnviadaParaAprovacao'); 
		
		
		//$cidRodape = date('YmdHms').'.'.time();  
		//$mail->AddEmbeddedImage($this->pagina->vPathImagens."CAU_Rodape.jpg",$cidRodape,'CAU_Rodape'); 
				 
		$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
		if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
	    } else {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
	    }

		require_once 'include/PHP/class/class.chamado_rdm.php';
		$chamado_rdm = new chamado_rdm();
		$chamado_rdm->setSEQ_RDM($this->RDM->SEQ_RDM);
		$chamado_rdm->selectParam("SEQ_CHAMADO");
		$chamadosAssociados ="";
		$QtdLinhas = 0;
		while ($row = pg_fetch_array($chamado_rdm->database->result)){
			$QtdLinhas++;
			
			if($chamadosAssociados=="" && $QtdLinhas==$chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"].".";
			}else if($chamadosAssociados=="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= ", ".$row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas == $chamado_rdm->database->rows){
				$chamadosAssociados .= " e ".$row["seq_chamado"].".";
			}				 
		}
		
		$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
							    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
							<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
							<head>
								<title>.:: CAU - Gestão de Mudanças ::.</title>
  

								  <style type=\"text/css\">
									<!--
										#body {
											color: #000000;
											font-family: verdana; arial;
											font-size: 10pt;
											background-color: #ffffff;		 
										} 
									-->
								  </style>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
								<link href=\"".$this->pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
							</head>
							
							<body bgcolor=\"#ffffff\" topmargin=\"10\" leftmargin=\"10\" marginwidth=\"10\" marginheight=\"10\">
							
							<table border=\"0\" cellpadding=\"0\" style=\"border:solid 1px #999999;\" cellspacing=\"0\" width=\"514\">
							    <tr>      
							      <td  align=\"center\">	
											<img src=\"cid:$cidTitulo\" border=\"0\">
										<br><br>
										
									</td>
								 </tr>	
								 <tr>
									<td>
										<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">
											<tr>
											  <td colspan=\"3\" align=\"center\"> <b> CAU – Notificação de envio de RDM para aprovação</b></td>
											</tr>  
											<tr>
											  <td colspan=\"3\" align=\"center\">&nbsp;</td>
											</tr>  
											<tr>
											  <td width=\"10\">&nbsp;</td>
											  <td colspan=\"2\"> 
												Prezado(a) usuário(a) <b>".$_SESSION["NOME"]."</b>,
												<br><br>&nbsp;&nbsp;&nbsp;&nbsp; A RDM <b>".$this->RDM->SEQ_RDM."</b> foi enviada para análise de aprovação. 
												<br><br>
												<b>Informações da Mudança: </b>
												<br>
												<br>&nbsp;&nbsp;- Tipo: <b>".$TIPO_RDM." </b>
												<br>&nbsp;&nbsp;- Título: <b>".$this->RDM->TITULO." </b>
												<br>&nbsp;&nbsp;- Justificativa: <b>".$this->RDM->JUSTIFICATIVA." </b>
												<br>&nbsp;&nbsp;- Impacto de não executar: <b>".$this->RDM->IMPACTO_NAO_EXECUTAR."</b>. 
												<br>&nbsp;&nbsp;- Situação Atual: <b>".$situacaoRDM->getDescricao($this->RDM->SITUACAO_ATUAL)." </b>
												<br>&nbsp;&nbsp;- Responsável pela validação: <b>".$this->RDM->NOME_RESP_CHECKLIST." </b>
												<br>&nbsp;&nbsp;- Telefone: <b>".$this->RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".$this->RDM->NUMERO_TELEFONE_RESP_CHECKLIST."</b>
												<br>&nbsp;&nbsp;- Data/Hora prevista para execução: <b>".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b> 
												<br>&nbsp;&nbsp;- Chamado(s) associado(s): <b>".$chamadosAssociados." </b>  
												<br><br>					
													<img src=\"cid:$cidStatus\" border=\"0\" width=\"500\">
												<br><br>
												
												A Central de Atendimento ao Usuário agradece o seu contato. Em caso de problemas ou solicitações, estamos à disposição para atendê-lo. 
												<br><br>
												Para maiores informações acesse o CAU. 
												<br>Esta é uma mensagem automática, favor não responder. 
												<br><br><br><hr width=\"450\" align=\"left\">  
												<br>Gestão de Mudanças
												<br>Central de Atendimento ao Usuário - ".$this->pagina->nom_area_ti.".  
												<br>55 61 3429 7872 
												<br> <a href=\"".$this->pagina->enderecoGestaoTI."\"> ".$this->pagina->enderecoGestaoTI."</a>
												<br><br>
											  </td>
											</tr>  
										</table>
									</td>
								</tr>
								<tr>
									<td   bgcolor=\"#00009C\" align=\"center\" height=\"24\">
										<font face=\"arial\" size=\"1\" color=\"#FFFFFF\" >
											<b>CAU - Central de atendimento ao usuário</b>
										</font>	
									</td> 
								</tr>
							  </table>
							</body>
						   </html>
						   ";

		$mail->AddAddress($_SESSION["DES_EMAIL"], $_SESSION["NOME"]);
		$mail->Body    = $v_DS_CORPO;
		$mail->AltBody = $v_DS_CORPO;
		// Comentado para n~ao enviar e-mail antes do lan'camento oficial do sistema
		$mail->Send();
		$mail->ClearAddresses();
		
		/*
		 *	ENVIAR E_MAIL PARA OS PERFIS:
		 *	Coordenador de TI, Gestor de TI e Gerente de Mudanças		
		 */
		
		$perfis = new perfil_acesso();
		$SEQ_PERFIL_ACESSO = Array();
		
		if($this->RDM->TIPO==1){			 
			$SEQ_PERFIL_ACESSO[0] = $perfis->SEQ_PERFIL_ACESSO_GESTOR_TI;
			$SEQ_PERFIL_ACESSO[1] = $perfis->SEQ_PERFIL_ACESSO_GERENTE_DE_MUDANCAS;
		}else if($this->RDM->TIPO==2){
			$SEQ_PERFIL_ACESSO[0] = $perfis->SEQ_PERFIL_ACESSO_COORDENADOR_TI;
			$SEQ_PERFIL_ACESSO[1] = $perfis->SEQ_PERFIL_ACESSO_GESTOR_TI;
			$SEQ_PERFIL_ACESSO[2] = $perfis->SEQ_PERFIL_ACESSO_GERENTE_DE_MUDANCAS;
		}
		

		$this->obterDestinatariosPorPerfil($SEQ_PERFIL_ACESSO);
	
		if($this->database->rows > 0){
			
			while ($row = pg_fetch_array($this->database->result)){
				//print "Email: ".$row["des_email"]." - ".$row["nome"];
				$mail->AddAddress($row["des_email"],$row["nome"]);
			}
			
			//$mail->Subject  = "CAU - RDM Enviada para Aprovação CTI_GTI_GM";
			
			$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
							    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
							<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
							<head>
								<title>.:: CAU - Gestão de Mudanças ::.</title>

								  <style type=\"text/css\">
									<!--
										#body {
											color: #000000;
											font-family: verdana; arial;
											font-size: 10pt;
											background-color: #ffffff;		 
										} 
									-->
								  </style>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
								<link href=\"".$this->pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
							</head>
							
							<body bgcolor=\"#ffffff\" topmargin=\"10\" leftmargin=\"10\" marginwidth=\"10\" marginheight=\"10\">
							
							<table border=\"0\" cellpadding=\"0\" style=\"border:solid 1px #999999;\" cellspacing=\"0\" width=\"514\">
							    <tr>      
							      <td  align=\"center\">	
											<img src=\"cid:$cidTitulo\" border=\"0\">
										<br><br>
										
									</td>
								 </tr>	
								 <tr>
									<td>
										<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">
											<tr>
											  <td colspan=\"3\" align=\"center\"> <b> CAU – Notificação de envio de RDM para aprovação</b></td>
											</tr>  
											<tr>
											  <td colspan=\"3\" align=\"center\">&nbsp;</td>
											</tr>  
											<tr>
											  <td width=\"10\">&nbsp;</td>
											  <td colspan=\"2\"> 
												Prezados Gestores,
												<br><br>&nbsp;&nbsp;&nbsp;&nbsp; A RDM <b>".$this->RDM->SEQ_RDM."</b> foi enviada para análise de aprovação. 
												<br><br>
												<b>Informações da Mudança: </b>
												<br>
												<br>&nbsp;&nbsp;- Tipo: <b>".$TIPO_RDM." </b>
												<br>&nbsp;&nbsp;- Título: <b>".$this->RDM->TITULO." </b>
												<br>&nbsp;&nbsp;- Justificativa: <b>".$this->RDM->JUSTIFICATIVA." </b>
												<br>&nbsp;&nbsp;- Impacto de não executar: <b>".$this->RDM->IMPACTO_NAO_EXECUTAR."</b>. 
												<br>&nbsp;&nbsp;- Situação Atual: <b>".$situacaoRDM->getDescricao($this->RDM->SITUACAO_ATUAL)." </b>
												<br>&nbsp;&nbsp;- Responsável pela validação: <b>".$this->RDM->NOME_RESP_CHECKLIST." </b>
												<br>&nbsp;&nbsp;- Telefone: <b>".$this->RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".$this->RDM->NUMERO_TELEFONE_RESP_CHECKLIST."</b>
												<br>&nbsp;&nbsp;- Data/Hora prevista para execução: <b>".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b> 
												<br>&nbsp;&nbsp;- Chamado(s) associado(s): <b>".$chamadosAssociados." </b>
												<br><br>
												&nbsp;&nbsp;
												A RDM criada encontra-se na fase de <b>APROVAÇÃO</b>.  Para transitá-la para <b>\"PLANEJAMENTO\"</b> é necessário aprovar a RDM no CAU.					 
													
												<br><br>					
													<img src=\"cid:$cidStatus\" border=\"0\" width=\"500\">
												<br><br>
												
												A Central de Atendimento ao Usuário agradece o seu contato. Em caso de problemas ou solicitações, estamos à disposição para atendê-lo. 
												<br><br>
												Para maiores informações acesse o CAU. 
												<br>Esta é uma mensagem automática, favor não responder. 
												<br><br><br><hr width=\"450\" align=\"left\">
												<br>Gestão de Mudanças
												<br>Central de Atendimento ao Usuário - ".$this->pagina->nom_area_ti.".  
												<br>55 61 3429 7872 
												<br> <a href=\"".$this->pagina->enderecoGestaoTI."\"> ".$this->pagina->enderecoGestaoTI."</a>
												<br><br>
											  </td>
											</tr>  
										</table>
									</td>
								</tr>
								<tr>
									<td   bgcolor=\"#00009C\" align=\"center\" height=\"24\">
										<font face=\"arial\" size=\"1\" color=\"#FFFFFF\" >
											<b>CAU - Central de atendimento ao usuário</b>
										</font>	
									</td> 
								</tr> 
							  </table>
							</body>
						   </html>
						   ";
			
			$mail->Body    = $v_DS_CORPO;
			$mail->AltBody = $v_DS_CORPO;			 
			$mail->Send();
			$mail->ClearAddresses();
		}
		
	}
	
	
	function sendEmailRDMAprovada($v_OBSERVACAO){
		 
		$situacaoRDM = new situacao_rdm();	
		$array_teste = split(" ",$this->RDM->DATA_HORA_PREVISTA_EXECUCAO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		 
		
		$DATA_HORA_PREVISTA_EXECUCAO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		$TIPO_RDM = $this->iif($this->RDM->TIPO==1, "Normal", "Emergencial");
		
		//ENVIAR E_MAIL PARA SOLICITANTE
		$mail = new PHPMailer();
		$mail->From     = $this->pagina->EmailRemetente;
		$mail->FromName = $this->pagina->remetenteEmailCEA;
		$mail->Sender   = $this->pagina->EmailRemetente;
		$mail->Subject  = "CAU – Notificação de planejamento de RDM ";
		
		$cidTitulo = date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."GM_Titulo.jpg",$cidTitulo,'GM_Titulo'); 
		
		$cidStatus = 'status'.date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."RDMPlanejamento.jpg",$cidStatus,'RDMPlanejamento'); 
		
		
		//$cidRodape = date('YmdHms').'.'.time();  
		//$mail->AddEmbeddedImage($this->pagina->vPathImagens."CAU_Rodape.jpg",$cidRodape,'CAU_Rodape'); 
		
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$empregados->select($this->RDM->NUM_MATRICULA_SOLICITANTE);
	
		$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
		if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
	    } else {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
	    }
		require_once 'include/PHP/class/class.chamado_rdm.php';
		$chamado_rdm = new chamado_rdm();
		$chamado_rdm->setSEQ_RDM($this->RDM->SEQ_RDM);
		$chamado_rdm->selectParam("SEQ_CHAMADO");
		$chamadosAssociados ="";
		$QtdLinhas = 0;
		while ($row = pg_fetch_array($chamado_rdm->database->result)){
			$QtdLinhas++;
			
			if($chamadosAssociados=="" && $QtdLinhas==$chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"].".";
			}else if($chamadosAssociados=="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= ", ".$row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas == $chamado_rdm->database->rows){
				$chamadosAssociados .= " e ".$row["seq_chamado"].".";
			}				 
		}
	
		$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
							    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
							<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
							<head>
								<title>.:: CAU - Gestão de Mudanças ::.</title>
  
								  <style type=\"text/css\">
									<!--
										#body {
											color: #000000;
											font-family: verdana; arial;
											font-size: 10pt;
											background-color: #ffffff;		 
										} 
									-->
								  </style>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
								<link href=\"".$this->pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
							</head>
							
							<body bgcolor=\"#ffffff\" topmargin=\"10\" leftmargin=\"10\" marginwidth=\"10\" marginheight=\"10\">
							
							
								<table border=\"0\" cellpadding=\"0\" style=\"border:solid 1px #999999;\" cellspacing=\"0\" width=\"514\">
								    <tr>      
								      <td  align=\"center\">	
											<img src=\"cid:$cidTitulo\" border=\"0\">
											<br><br>
											
										</td>
									 </tr>	
									 <tr>
										<td>
											<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">
												<tr>
												  <td colspan=\"3\" align=\"center\"> <b> CAU – Notificação de planejamento de RDM </b></td>
												</tr>  
												<tr>
												  <td colspan=\"3\" align=\"center\">&nbsp;</td>
												</tr>  
												<tr>
												  <td width=\"10\">&nbsp;</td>
												  <td colspan=\"2\"> 
													Prezado(a) usuário(a) <b>".$empregados->NOME."</b>,					 
													<br><br>&nbsp;&nbsp;&nbsp;&nbsp;  A sua RDM <b>".$this->RDM->SEQ_RDM."</b> foi aprovada e enviada para o <b>PLANEJAMENTO</b>.					 
													<br><br>
													<b>Informações da Mudança: </b>
													<br>
													<br>&nbsp;&nbsp;- Tipo: <b>".$TIPO_RDM." </b>
													<br>&nbsp;&nbsp;- Título: <b>".$this->RDM->TITULO." </b>
													<br>&nbsp;&nbsp;- Justificativa: <b>".$this->RDM->JUSTIFICATIVA." </b>
													<br>&nbsp;&nbsp;- Impacto de não executar: <b>".$this->RDM->IMPACTO_NAO_EXECUTAR."</b>. 
													<br>&nbsp;&nbsp;- Situação Atual: <b>".$situacaoRDM->getDescricao($this->RDM->SITUACAO_ATUAL)." </b>
													<br>&nbsp;&nbsp;- Responsável pela validação: <b>".$this->RDM->NOME_RESP_CHECKLIST." </b>
													<br>&nbsp;&nbsp;- Telefone: <b>".$this->RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".$this->RDM->NUMERO_TELEFONE_RESP_CHECKLIST."</b>
													<br>&nbsp;&nbsp;- Data/Hora prevista para execução: <b>".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b> 
													<br>&nbsp;&nbsp;- Chamado(s) associado(s): <b>".$chamadosAssociados." </b>
													<br><br>
													
													<b>Informações da Aprovação:</b>
													<br>
													<br>&nbsp;&nbsp;- Detalhe da aprovação: <b> ".$v_OBSERVACAO." </b>													
													
													<br><br>					
													<img src=\"cid:$cidStatus\" border=\"0\" width=\"500\">
													<br><br>
													
													A Central de Atendimento ao Usuário agradece o seu contato. Em caso de problemas ou solicitações, estamos à disposição para atendê-lo. 
													<br><br>
													Para maiores informações acesse o CAU. 
													<br>Esta é uma mensagem automática, favor não responder. 
													<br><br><br><hr width=\"450\" align=\"left\">
													<br>Gestão de Mudanças
													<br>Central de Atendimento ao Usuário - ".$this->pagina->nom_area_ti.". 
													<br>55 61 3429 7872 
													<br> <a href=\"".$this->pagina->enderecoGestaoTI."\"> ".$this->pagina->enderecoGestaoTI."</a>
													<br><br>
												  </td>
												</tr>  
											</table>
										</td>
									</tr>
								<tr>
									<td   bgcolor=\"#00009C\" align=\"center\" height=\"24\">
										<font face=\"arial\" size=\"1\" color=\"#FFFFFF\" >
											<b>CAU - Central de atendimento ao usuário</b>
										</font>	
									</td> 
								</tr> 
								  </table>
							 
							</body>
						   </html>
						   ";

		$mail->AddAddress($empregados->DES_EMAIL,$empregados->NOME);
		$mail->Body    = $v_DS_CORPO;
		$mail->AltBody = $v_DS_CORPO;
		// Comentado para n~ao enviar e-mail antes do lan'camento oficial do sistema
		$mail->Send();
		$mail->ClearAddresses();
		
		/*
		 *	ENVIAR E_MAIL PARA OS PERFIS:
		 *	Coordenador de TI, Gestor de TI e Gerente de Mudanças		
		 */
		
		$perfis = new perfil_acesso();
		$SEQ_PERFIL_ACESSO = Array();
		$SEQ_PERFIL_ACESSO[0] = $perfis->SEQ_PERFIL_ACESSO_GERENTE_DE_MUDANCAS;

		$this->obterDestinatariosPorPerfil($SEQ_PERFIL_ACESSO);
	
		if($this->database->rows > 0){
			
			while ($row = pg_fetch_array($this->database->result)){
				//print "Email: ".$row["des_email"]." - ".$row["nome"];
				$mail->AddAddress($row["des_email"],$row["nome"]);
			}
			
			//$mail->Subject  = "CAU - RDM Enviada para Aprovação CTI_GTI_GM";
			
			$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
							    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
							<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
							<head>
								<title>.:: CAU - Gestão de Mudanças ::.</title>
  

								  <style type=\"text/css\">
									<!--
										#body {
											color: #000000;
											font-family: verdana; arial;
											font-size: 10pt;
											background-color: #ffffff;		 
										} 
									-->
								  </style>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
								<link href=\"".$this->pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
							</head>
							
							<body bgcolor=\"#ffffff\" topmargin=\"10\" leftmargin=\"10\" marginwidth=\"10\" marginheight=\"10\">
							
							
								<table border=\"0\" cellpadding=\"0\" style=\"border:solid 1px #999999;\" cellspacing=\"0\" width=\"514\">
								    <tr>      
								      <td  align=\"center\">	
											<img src=\"cid:$cidTitulo\" border=\"0\">
											<br><br>
											
										</td>
									 </tr>	
									 <tr>
										<td>
											<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">
												<tr>
												  <td colspan=\"3\" align=\"center\"> <b> CAU – Notificação de planejamento de RDM </b></td>
												</tr>  
												<tr>
												  <td colspan=\"3\" align=\"center\">&nbsp;</td>
												</tr>  
												<tr>
												  <td width=\"10\">&nbsp;</td>
												  <td colspan=\"2\"> 
													Prezados Gestores,						 
													<br><br>&nbsp;&nbsp;&nbsp;&nbsp;  RDM <b>".$this->RDM->SEQ_RDM."</b> foi enviada planejamento.																		 
													<br><br>
													<b>Informações da Mudança: </b>
													<br>
													<br>&nbsp;&nbsp;- Tipo: <b>".$TIPO_RDM." </b>
													<br>&nbsp;&nbsp;- Título: <b>".$this->RDM->TITULO." </b>
													<br>&nbsp;&nbsp;- Justificativa: <b>".$this->RDM->JUSTIFICATIVA." </b>
													<br>&nbsp;&nbsp;- Impacto de não executar: <b>".$this->RDM->IMPACTO_NAO_EXECUTAR."</b>. 
													<br>&nbsp;&nbsp;- Situação Atual: <b>".$situacaoRDM->getDescricao($this->RDM->SITUACAO_ATUAL)." </b>
													<br>&nbsp;&nbsp;- Responsável pela validação: <b>".$this->RDM->NOME_RESP_CHECKLIST." </b>
													<br>&nbsp;&nbsp;- Telefone: <b>".$this->RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".$this->RDM->NUMERO_TELEFONE_RESP_CHECKLIST."</b>
													<br>&nbsp;&nbsp;- Data/Hora prevista para execução: <b>".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b> 
													<br>&nbsp;&nbsp;- Chamado(s) associado(s): <b>".$chamadosAssociados." </b>
													<br><br>
													
													<b>Informações da Aprovação:</b>
													<br>
													<br>&nbsp;&nbsp;- Detalhe da aprovação: <b> ".$v_OBSERVACAO." </b>
													
													<br><br>
													&nbsp;&nbsp;
													A RDM encontra-se na fase de <b>PLANEJAMENTO</b>.  Para transitá-la para <b>\"EXECUÇÃO\"</b> é necessário realizar o planejamento da RDM no CAU. 
													
													
													<br><br>					
													<img src=\"cid:$cidStatus\" border=\"0\" width=\"500\">
													<br><br>
													
													A Central de Atendimento ao Usuário agradece o seu contato. Em caso de problemas ou solicitações, estamos à disposição para atendê-lo. 
													<br><br>
													Para maiores informações acesse o CAU. 
													<br>Esta é uma mensagem automática, favor não responder. 
													<br><br><br><hr width=\"450\" align=\"left\">
													<br>Gestão de Mudanças
													<br>Central de Atendimento ao Usuário - ".$this->pagina->nom_area_ti.". 
													<br>55 61 3429 7872 
													<br> <a href=\"".$this->pagina->enderecoGestaoTI."\"> ".$this->pagina->enderecoGestaoTI."</a>
													<br><br>
												  </td>
												</tr>  
											</table>
										</td>
									</tr>
								<tr>
									<td   bgcolor=\"#00009C\" align=\"center\" height=\"24\">
										<font face=\"arial\" size=\"1\" color=\"#FFFFFF\" >
											<b>CAU - Central de atendimento ao usuário</b>
										</font>	
									</td> 
								</tr>
								  </table>
							 
							</body>
						   </html>
						   ";
			
			$mail->Body    = $v_DS_CORPO;
			$mail->AltBody = $v_DS_CORPO;			 
			$mail->Send();
			$mail->ClearAddresses();
		}
		
	}
	
	function sendEmailRDMReprovada($v_OBSERVACAO){
		$situacaoRDM = new situacao_rdm();		
		
		$array_teste = split(" ",$this->RDM->DATA_HORA_PREVISTA_EXECUCAO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		 
		
		$DATA_HORA_PREVISTA_EXECUCAO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		$TIPO_RDM = $this->iif($this->RDM->TIPO==1, "Normal", "Emergencial");
		
		//ENVIAR E_MAIL PARA SOLICITANTE
		$mail = new PHPMailer();
		$mail->From     = $this->pagina->EmailRemetente;
		$mail->FromName = $this->pagina->remetenteEmailCEA;
		$mail->Sender   = $this->pagina->EmailRemetente;
		$mail->Subject  = "CAU – Notificação de reprovação de RDM";
		
		$cidTitulo = date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."GM_Titulo.jpg",$cidTitulo,'GM_Titulo'); 
		
		$cidStatus = 'status'.date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."RDMPlanejamento.jpg",$cidStatus,'RDMPlanejamento'); 
		
		
		//$cidRodape = date('YmdHms').'.'.time();  
		//$mail->AddEmbeddedImage($this->pagina->vPathImagens."CAU_Rodape.jpg",$cidRodape,'CAU_Rodape'); 
		
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$empregados->select($this->RDM->NUM_MATRICULA_SOLICITANTE);
		
		$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
		if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
	    } else {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
	    }
		
	    require_once 'include/PHP/class/class.chamado_rdm.php';
		$chamado_rdm = new chamado_rdm();
		$chamado_rdm->setSEQ_RDM($this->RDM->SEQ_RDM);
		$chamado_rdm->selectParam("SEQ_CHAMADO");
		$chamadosAssociados ="";
		$QtdLinhas = 0;
		while ($row = pg_fetch_array($chamado_rdm->database->result)){
			$QtdLinhas++;
			
			if($chamadosAssociados=="" && $QtdLinhas==$chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"].".";
			}else if($chamadosAssociados=="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= ", ".$row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas == $chamado_rdm->database->rows){
				$chamadosAssociados .= " e ".$row["seq_chamado"].".";
			}				 
		}
	
		$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
							    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
							<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
							<head>
								<title>.:: CAU - Gestão de Mudanças ::.</title>
  

								  <style type=\"text/css\">
									<!--
										#body {
											color: #000000;
											font-family: verdana; arial;
											font-size: 10pt;
											background-color: #ffffff;		 
										} 
									-->
								  </style>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
								<link href=\"".$this->pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
							</head>
							
							<body bgcolor=\"#ffffff\" topmargin=\"10\" leftmargin=\"10\" marginwidth=\"10\" marginheight=\"10\">
							
							
								<table border=\"0\" cellpadding=\"0\" style=\"border:solid 1px #999999;\" cellspacing=\"0\" width=\"514\">
								    <tr>      
								      <td  align=\"center\">	
											<img src=\"cid:$cidTitulo\" border=\"0\">
											<br><br>
											
										</td>
									 </tr>	
									 <tr>
										<td>
											<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">
												<tr>
												  <td colspan=\"3\" align=\"center\"> <b> CAU – Notificação de reprovação de RDM </b></td>
												</tr>  
												<tr>
												  <td colspan=\"3\" align=\"center\">&nbsp;</td>
												</tr>  
												<tr>
												  <td width=\"10\">&nbsp;</td>
												  <td colspan=\"2\"> 
													Prezados,						 
													<br><br>&nbsp;&nbsp;&nbsp;&nbsp; A RDM <b>".$this->RDM->SEQ_RDM."</b> foi reprovada  no CAU. Seguem os dados da RDM e do reprovação.													 																		 
													<br><br>
													<b>Informações da Mudança: </b>
													<br>
													<br>&nbsp;&nbsp;- Tipo: <b>".$TIPO_RDM." </b>
													<br>&nbsp;&nbsp;- Título: <b>".$this->RDM->TITULO." </b>
													<br>&nbsp;&nbsp;- Justificativa: <b>".$this->RDM->JUSTIFICATIVA." </b>
													<br>&nbsp;&nbsp;- Impacto de não executar: <b>".$this->RDM->IMPACTO_NAO_EXECUTAR."</b>. 
													<br>&nbsp;&nbsp;- Situação Atual: <b>".$situacaoRDM->getDescricao($this->RDM->SITUACAO_ATUAL)." </b>
													<br>&nbsp;&nbsp;- Responsável pela validação: <b>".$this->RDM->NOME_RESP_CHECKLIST." </b>
													<br>&nbsp;&nbsp;- Telefone: <b>".$this->RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".$this->RDM->NUMERO_TELEFONE_RESP_CHECKLIST."</b>
													<br>&nbsp;&nbsp;- Data/Hora prevista para execução: <b>".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b> 
													<br>&nbsp;&nbsp;- Chamado(s) associado(s): <b>".$chamadosAssociados." </b>
													<br><br>
													
													<b>Informações da Reprovação:</b>
													<br>
													<br>&nbsp;&nbsp;- Detalhe da Reprovação: <b> ".$v_OBSERVACAO." </b>
													<br><br>
													
													A Central de Atendimento ao Usuário agradece o seu contato. Em caso de problemas ou solicitações, estamos à disposição para atendê-lo. 
													<br><br>
													Para maiores informações acesse o CAU. 
													<br>Esta é uma mensagem automática, favor não responder. 
													<br><br><br><hr width=\"450\" align=\"left\">
													<br>Gestão de Mudanças
													<br>Central de Atendimento ao Usuário - ".$this->pagina->nom_area_ti.". 
													<br>55 61 3429 7872 
													<br> <a href=\"".$this->pagina->enderecoGestaoTI."\"> ".$this->pagina->enderecoGestaoTI."</a>
													<br><br>
												  </td>
												</tr>  
											</table>
										</td>
									</tr>
								<tr>
									<td   bgcolor=\"#00009C\" align=\"center\" height=\"24\">
										<font face=\"arial\" size=\"1\" color=\"#FFFFFF\" >
											<b>CAU - Central de atendimento ao usuário</b>
										</font>	
									</td> 
								</tr>   
								  </table>
							 
							</body>
						   </html>
						   ";

		$mail->AddAddress($empregados->DES_EMAIL,$empregados->NOME);
		
		if($this->RDM->SITUACAO_ATUAL == $situacaoRDM->APROVADA ){
			$perfis = new perfil_acesso();
			$SEQ_PERFIL_ACESSO = Array();
			$SEQ_PERFIL_ACESSO[0] = $perfis->SEQ_PERFIL_ACESSO_COORDENADOR_TI;
			$SEQ_PERFIL_ACESSO[1] = $perfis->SEQ_PERFIL_ACESSO_GESTOR_TI;
	
			$this->obterDestinatariosPorPerfil($SEQ_PERFIL_ACESSO);
		
			if($this->database->rows > 0){
				
				while ($row = pg_fetch_array($this->database->result)){
					//print "Email: ".$row["des_email"]." - ".$row["nome"];
					$mail->AddAddress($row["des_email"],$row["nome"]);
				}
			}			
		}		
		$mail->Body    = $v_DS_CORPO;
		$mail->AltBody = $v_DS_CORPO;
		// Comentado para n~ao enviar e-mail antes do lan'camento oficial do sistema
		$mail->Send();
		$mail->ClearAddresses();
		
	}
	
	function sendEmailRDMCancelada($v_OBSERVACAO){
		$situacaoRDM = new situacao_rdm();	
		$array_teste = split(" ",$this->RDM->DATA_HORA_PREVISTA_EXECUCAO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		 
		
		$DATA_HORA_PREVISTA_EXECUCAO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		$TIPO_RDM = $this->iif($this->RDM->TIPO==1, "Normal", "Emergencial");
		
		//ENVIAR E_MAIL PARA SOLICITANTE
		$mail = new PHPMailer();
		$mail->From     = $this->pagina->EmailRemetente;
		$mail->FromName = $this->pagina->remetenteEmailCEA;
		$mail->Sender   = $this->pagina->EmailRemetente;
		$mail->Subject  = "CAU – Notificação de cancelamento de RDM";
		
		$cidTitulo = date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."GM_Titulo.jpg",$cidTitulo,'GM_Titulo'); 
		
		$cidStatus = 'status'.date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."RDMPlanejamento.jpg",$cidStatus,'RDMPlanejamento'); 
		
		
		//$cidRodape = date('YmdHms').'.'.time();  
		//$mail->AddEmbeddedImage($this->pagina->vPathImagens."CAU_Rodape.jpg",$cidRodape,'CAU_Rodape'); 
		
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$empregados->select($this->RDM->NUM_MATRICULA_SOLICITANTE);
		
		$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
		if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
	    } else {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
	    }

		require_once 'include/PHP/class/class.chamado_rdm.php';
		$chamado_rdm = new chamado_rdm();
		$chamado_rdm->setSEQ_RDM($this->RDM->SEQ_RDM);
		$chamado_rdm->selectParam("SEQ_CHAMADO");
		$chamadosAssociados ="";
		$QtdLinhas = 0;
		while ($row = pg_fetch_array($chamado_rdm->database->result)){
			$QtdLinhas++;
			
			if($chamadosAssociados=="" && $QtdLinhas==$chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"].".";
			}else if($chamadosAssociados=="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= ", ".$row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas == $chamado_rdm->database->rows){
				$chamadosAssociados .= " e ".$row["seq_chamado"].".";
			}				 
		}	
		
		$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
							    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
							<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
							<head>
								<title>.:: CAU - Gestão de Mudanças ::.</title>
  

								  <style type=\"text/css\">
									<!--
										#body {
											color: #000000;
											font-family: verdana; arial;
											font-size: 10pt;
											background-color: #ffffff;		 
										} 
									-->
								  </style>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
								<link href=\"".$this->pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
							</head>
							
							<body bgcolor=\"#ffffff\" topmargin=\"10\" leftmargin=\"10\" marginwidth=\"10\" marginheight=\"10\">
							
							
								<table border=\"0\" cellpadding=\"0\" style=\"border:solid 1px #999999;\" cellspacing=\"0\" width=\"514\">
								    <tr>      
								      <td  align=\"center\">	
											<img src=\"cid:$cidTitulo\" border=\"0\">
											<br><br>
											
										</td>
									 </tr>	
									 <tr>
										<td>
											<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">
												<tr>
												  <td colspan=\"3\" align=\"center\"> <b> CAU – Notificação de cancelamento de RDM   </b></td>
												</tr>  
												<tr>
												  <td colspan=\"3\" align=\"center\">&nbsp;</td>
												</tr>  
												<tr>
												  <td width=\"10\">&nbsp;</td>
												  <td colspan=\"2\"> 
													Prezados,						 
													<br><br>&nbsp;&nbsp;&nbsp;&nbsp; A RDM <b>".$this->RDM->SEQ_RDM."</b> foi cancelada  no CAU. Seguem os dados da RDM e do Cancelamento.													 																		 
													<br><br>
													<b>Informações da Mudança: </b>
													<br>
													<br>&nbsp;&nbsp;- Tipo: <b>".$TIPO_RDM." </b>
													<br>&nbsp;&nbsp;- Título: <b>".$this->RDM->TITULO." </b>
													<br>&nbsp;&nbsp;- Justificativa: <b>".$this->RDM->JUSTIFICATIVA." </b>
													<br>&nbsp;&nbsp;- Impacto de não executar: <b>".$this->RDM->IMPACTO_NAO_EXECUTAR."</b>. 
													<br>&nbsp;&nbsp;- Situação Atual: <b>".$situacaoRDM->getDescricao($this->RDM->SITUACAO_ATUAL)." </b>
													<br>&nbsp;&nbsp;- Responsável pela validação: <b>".$this->RDM->NOME_RESP_CHECKLIST." </b>
													<br>&nbsp;&nbsp;- Telefone: <b>".$this->RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".$this->RDM->NUMERO_TELEFONE_RESP_CHECKLIST."</b>
													<br>&nbsp;&nbsp;- Data/Hora prevista para execução: <b>".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b> 
													<br>&nbsp;&nbsp;- Chamado(s) associado(s): <b>".$chamadosAssociados." </b>
													<br><br>
													
													<b>Informações da Cancelamento:</b>
													<br>
													<br>&nbsp;&nbsp;- Detalhe da Cancelamento: <b> ".$v_OBSERVACAO." </b>
													<br><br>
													
													A Central de Atendimento ao Usuário agradece o seu contato. Em caso de problemas ou solicitações, estamos à disposição para atendê-lo. 
													<br><br>
													Para maiores informações acesse o CAU. 
													<br>Esta é uma mensagem automática, favor não responder. 
													<br><br><br><hr width=\"450\" align=\"left\">
													<br>Gestão de Mudanças
													<br>Central de Atendimento ao Usuário - ".$this->pagina->nom_area_ti.". 
													<br>55 61 3429 7872 
													<br> <a href=\"".$this->pagina->enderecoGestaoTI."\"> ".$this->pagina->enderecoGestaoTI."</a>
													<br><br>
												  </td>
												</tr>  
											</table>
										</td>
									</tr>
								<tr>
									<td   bgcolor=\"#00009C\" align=\"center\" height=\"24\">
										<font face=\"arial\" size=\"1\" color=\"#FFFFFF\" >
											<b>CAU - Central de atendimento ao usuário</b>
										</font>	
									</td> 
								</tr>  
								  </table>
							 
							</body>
						   </html>
						   ";

		$mail->AddAddress($empregados->DES_EMAIL,$empregados->NOME);
		
		if($this->RDM->SITUACAO_ATUAL == $situacaoRDM->APROVADA ){
			$perfis = new perfil_acesso();
			$SEQ_PERFIL_ACESSO = Array();
			$SEQ_PERFIL_ACESSO[0] = $perfis->SEQ_PERFIL_ACESSO_COORDENADOR_TI;
			$SEQ_PERFIL_ACESSO[1] = $perfis->SEQ_PERFIL_ACESSO_GESTOR_TI;
	
			$this->obterDestinatariosPorPerfil($SEQ_PERFIL_ACESSO);
		
			if($this->database->rows > 0){
				
				while ($row = pg_fetch_array($this->database->result)){
					//print "Email: ".$row["des_email"]." - ".$row["nome"];
					$mail->AddAddress($row["des_email"],$row["nome"]);
				}
			}			
		}		
		$mail->Body    = $v_DS_CORPO;
		$mail->AltBody = $v_DS_CORPO;
		// Comentado para n~ao enviar e-mail antes do lan'camento oficial do sistema
		$mail->Send();
		$mail->ClearAddresses();
		
	}
	
	function sendEmailRDMEnviadaParaExecucao(){
		 
		$situacaoRDM = new situacao_rdm();	
		$array_teste = split(" ",$this->RDM->DATA_HORA_PREVISTA_EXECUCAO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		 
		
		$DATA_HORA_PREVISTA_EXECUCAO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		$TIPO_RDM = $this->iif($this->RDM->TIPO==1, "Normal", "Emergencial");
		
		//ENVIAR E_MAIL PARA SOLICITANTE
		$mail = new PHPMailer();
		$mail->From     = $this->pagina->EmailRemetente;
		$mail->FromName = $this->pagina->remetenteEmailCEA;
		$mail->Sender   = $this->pagina->EmailRemetente;
		$mail->Subject  = "CAU – Comunicado de Mudança de TI";
		
		$cidTitulo = date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."GM_Titulo.jpg",$cidTitulo,'GM_Titulo'); 
		
		$cidStatus = 'status'.date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."RDMExecucao.jpg",$cidStatus,'RDMExecucao'); 
		
		
		//$cidRodape = date('YmdHms').'.'.time();  
		//$mail->AddEmbeddedImage($this->pagina->vPathImagens."CAU_Rodape.jpg",$cidRodape,'CAU_Rodape'); 
		
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$empregados->select($this->RDM->NUM_MATRICULA_SOLICITANTE);
		
		$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
		if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
	    } else {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
	    }

		require_once 'include/PHP/class/class.chamado_rdm.php';
		$chamado_rdm = new chamado_rdm();
		$chamado_rdm->setSEQ_RDM($this->RDM->SEQ_RDM);
		$chamado_rdm->selectParam("SEQ_CHAMADO");
		$chamadosAssociados ="";
		$QtdLinhas = 0;
		while ($row = pg_fetch_array($chamado_rdm->database->result)){
			$QtdLinhas++;
			
			if($chamadosAssociados=="" && $QtdLinhas==$chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"].".";
			}else if($chamadosAssociados=="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= ", ".$row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas == $chamado_rdm->database->rows){
				$chamadosAssociados .= " e ".$row["seq_chamado"].".";
			}				 
		}
	
		$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
							    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
							<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
							<head>
								<title>.:: CAU - Gestão de Mudanças ::.</title>
  

								  <style type=\"text/css\">
									<!--
										#body {
											color: #000000;
											font-family: verdana; arial;
											font-size: 10pt;
											background-color: #ffffff;		 
										} 
									-->
								  </style>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
								<link href=\"".$this->pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
							</head>
							
							<body bgcolor=\"#ffffff\" topmargin=\"10\" leftmargin=\"10\" marginwidth=\"10\" marginheight=\"10\">
							
							
								<table border=\"0\" cellpadding=\"0\" style=\"border:solid 1px #999999;\" cellspacing=\"0\" width=\"514\">
								    <tr>      
								      <td  align=\"center\">	
											<img src=\"cid:$cidTitulo\" border=\"0\">
											<br><br>
											
										</td>
									 </tr>	
									 <tr>
										<td>
											<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">
												<tr>
												  <td colspan=\"3\" align=\"center\"> <b> CAU – Comunicado de Mudança de TI  </b></td>
												</tr>  
												<tr>
												  <td colspan=\"3\" align=\"center\">&nbsp;</td>
												</tr>  
												<tr>
												  <td width=\"10\">&nbsp;</td>
												  <td colspan=\"2\"> 
													Prezados,						 
													<br><br>&nbsp;&nbsp;&nbsp;&nbsp;   A RDM  abaixo será executada no ambiente de produção da Embratur.													 												 																		 
													<br><br>
													<b>Informações da Mudança: </b>
													<br>
													<br>&nbsp;&nbsp;- Número: <b>".$this->RDM->SEQ_RDM." </b>
													<br>&nbsp;&nbsp;- Tipo: <b>".$TIPO_RDM." </b>
													<br>&nbsp;&nbsp;- Título: <b>".$this->RDM->TITULO." </b>
													<br>&nbsp;&nbsp;- Justificativa: <b>".$this->RDM->JUSTIFICATIVA." </b>
													<br>&nbsp;&nbsp;- Impacto de não executar: <b>".$this->RDM->IMPACTO_NAO_EXECUTAR."</b>. 
													<br>&nbsp;&nbsp;- Situação Atual: <b>".$situacaoRDM->getDescricao($this->RDM->SITUACAO_ATUAL)." </b>
													<br>&nbsp;&nbsp;- Responsável pela validação: <b>".$this->RDM->NOME_RESP_CHECKLIST." </b>
													<br>&nbsp;&nbsp;- Telefone: <b>".$this->RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".$this->RDM->NUMERO_TELEFONE_RESP_CHECKLIST."</b>
													<br>&nbsp;&nbsp;- Data/Hora prevista para execução: <b>".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b> 
													<br>&nbsp;&nbsp;- Chamado(s) associado(s): <b>".$chamadosAssociados." </b>
													<br><br> 
													
													<br><br>					
													<img src=\"cid:$cidStatus\" border=\"0\" width=\"500\">
													<br><br>
													
													A Central de Atendimento ao Usuário agradece o seu contato. Em caso de problemas ou solicitações, estamos à disposição para atendê-lo. 
													<br><br>
													Para maiores informações acesse o CAU. 
													<br>Esta é uma mensagem automática, favor não responder. 
													<br><br><br><hr width=\"450\" align=\"left\">
													<br>Gestão de Mudanças
													<br>Central de Atendimento ao Usuário - ".$this->pagina->nom_area_ti.". 
													<br>55 61 3429 7872 
													<br> <a href=\"".$this->pagina->enderecoGestaoTI."\"> ".$this->pagina->enderecoGestaoTI."</a>
													<br><br>
												  </td>
												</tr>  
											</table>
										</td>
									</tr>
								<tr>
									<td   bgcolor=\"#00009C\" align=\"center\" height=\"24\">
										<font face=\"arial\" size=\"1\" color=\"#FFFFFF\" >
											<b>CAU - Central de atendimento ao usuário</b>
										</font>	
									</td> 
								</tr> 
								  </table>
							 
							</body>
						   </html>
						   ";

		$mail->AddAddress($empregados->DES_EMAIL,$empregados->NOME);
		
		 
			
		$perfis = new perfil_acesso();
		$SEQ_PERFIL_ACESSO = Array();
		$SEQ_PERFIL_ACESSO[0] = $perfis->SEQ_PERFIL_ACESSO_COORDENADOR_TI;
		$SEQ_PERFIL_ACESSO[1] = $perfis->SEQ_PERFIL_ACESSO_GESTOR_TI;
		$SEQ_PERFIL_ACESSO[2] = $perfis->SEQ_PERFIL_ACESSO_GERENTE_DE_MUDANCAS;
		$SEQ_PERFIL_ACESSO[3] = $perfis->SEQ_PERFIL_ACESSO_ADMINISTRADOR;
		$SEQ_PERFIL_ACESSO[4] = $perfis->SEQ_PERFIL_ACESSO_EXECUTOR_DE_MUDANCAS;
		$SEQ_PERFIL_ACESSO[5] = $perfis->SEQ_PERFIL_ACESSO_REQUISITANTE_DE_MUDANCAS;
		$SEQ_PERFIL_ACESSO[6] = $perfis->SEQ_PERFIL_ACESSO_GERENTE_DE_MUDANCAS;
		
	
		$this->obterDestinatariosPorPerfil($SEQ_PERFIL_ACESSO);
	
		if($this->database->rows > 0){
			
			while ($row = pg_fetch_array($this->database->result)){
				//print "Email: ".$row["des_email"]." - ".$row["nome"];
				$mail->AddAddress($row["des_email"],$row["nome"]);
			}
		}			
 	
		$mail->Body    = $v_DS_CORPO;
		$mail->AltBody = $v_DS_CORPO;
		// Comentado para n~ao enviar e-mail antes do lan'camento oficial do sistema
		$mail->Send();
		$mail->ClearAddresses();
	}
	
	function sendEmailRDMValidacao(){
		 
		$situacaoRDM = new situacao_rdm();	
		$array_teste = split(" ",$this->RDM->DATA_HORA_PREVISTA_EXECUCAO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		 
		
		$DATA_HORA_PREVISTA_EXECUCAO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		$TIPO_RDM = $this->iif($this->RDM->TIPO==1, "Normal", "Emergencial");
		
		//ENVIAR E_MAIL PARA SOLICITANTE
		$mail = new PHPMailer();
		$mail->From     = $this->pagina->EmailRemetente;
		$mail->FromName = $this->pagina->remetenteEmailCEA;
		$mail->Sender   = $this->pagina->EmailRemetente;
		$mail->Subject  = "CAU - Notificação de Validação de RDM";
		
		$cidTitulo = date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."GM_Titulo.jpg",$cidTitulo,'GM_Titulo'); 
		
		$cidStatus = 'status'.date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."RDMValidacao.jpg",$cidStatus,'RDMValidacao'); 
		
		
		//$cidRodape = date('YmdHms').'.'.time();  
		//$mail->AddEmbeddedImage($this->pagina->vPathImagens."CAU_Rodape.jpg",$cidRodape,'CAU_Rodape'); 
		
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$empregados->select($this->RDM->NUM_MATRICULA_SOLICITANTE);
		
		$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
		if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
	    } else {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
	    }

		require_once 'include/PHP/class/class.chamado_rdm.php';
		$chamado_rdm = new chamado_rdm();
		$chamado_rdm->setSEQ_RDM($this->RDM->SEQ_RDM);
		$chamado_rdm->selectParam("SEQ_CHAMADO");
		$chamadosAssociados ="";
		$QtdLinhas = 0;
		while ($row = pg_fetch_array($chamado_rdm->database->result)){
			$QtdLinhas++;
			
			if($chamadosAssociados=="" && $QtdLinhas==$chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"].".";
			}else if($chamadosAssociados=="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= ", ".$row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas == $chamado_rdm->database->rows){
				$chamadosAssociados .= " e ".$row["seq_chamado"].".";
			}				 
		}
		
		$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
							    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
							<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
							<head>
								<title>.:: CAU - Gestão de Mudanças ::.</title>
  

								  <style type=\"text/css\">
									<!--
										#body {
											color: #000000;
											font-family: verdana; arial;
											font-size: 10pt;
											background-color: #ffffff;		 
										} 
									-->
								  </style>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
								<link href=\"".$this->pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
							</head>
							
							<body bgcolor=\"#ffffff\" topmargin=\"10\" leftmargin=\"10\" marginwidth=\"10\" marginheight=\"10\">
							
							
								<table border=\"0\" cellpadding=\"0\" style=\"border:solid 1px #999999;\" cellspacing=\"0\" width=\"514\">
								    <tr>      
								      <td  align=\"center\">	
											<img src=\"cid:$cidTitulo\" border=\"0\">
											<br><br>
											
										</td>
									 </tr>	
									 <tr>
										<td>
											<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">
												<tr>
												  <td colspan=\"3\" align=\"center\"> <b> CAU - Notificação de Validação de RDM </b></td>
												</tr>  
												<tr>
												  <td colspan=\"3\" align=\"center\">&nbsp;</td>
												</tr>  
												<tr>
												  <td width=\"10\">&nbsp;</td>
												  <td colspan=\"2\"> 
													Prezados,						 
													<br><br>&nbsp;&nbsp;&nbsp;&nbsp;   A RDM  abaixo foi executada no ambiente de produção e está disponível para realização do checklist técnico/funcional.													 												 																		 
													<br><br>
													<b>Informações da Mudança: </b>
													<br>
													<br>&nbsp;&nbsp;- Número: <b>".$this->RDM->SEQ_RDM." </b>
													<br>&nbsp;&nbsp;- Tipo: <b>".$TIPO_RDM." </b>
													<br>&nbsp;&nbsp;- Título: <b>".$this->RDM->TITULO." </b>
													<br>&nbsp;&nbsp;- Justificativa: <b>".$this->RDM->JUSTIFICATIVA." </b>
													<br>&nbsp;&nbsp;- Impacto de não executar: <b>".$this->RDM->IMPACTO_NAO_EXECUTAR."</b>. 
													<br>&nbsp;&nbsp;- Situação Atual: <b>".$situacaoRDM->getDescricao($this->RDM->SITUACAO_ATUAL)." </b>
													<br>&nbsp;&nbsp;- Responsável pela validação: <b>".$this->RDM->NOME_RESP_CHECKLIST." </b>
													<br>&nbsp;&nbsp;- Telefone: <b>".$this->RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".$this->RDM->NUMERO_TELEFONE_RESP_CHECKLIST."</b>
													<br>&nbsp;&nbsp;- Data/Hora prevista para execução: <b>".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b> 
													<br>&nbsp;&nbsp;- Chamado(s) associado(s): <b>".$chamadosAssociados." </b>
													<br><br> 
													
													<br><br>					
													<img src=\"cid:$cidStatus\" border=\"0\" width=\"500\">
													<br><br>
													
													A Central de Atendimento ao Usuário agradece o seu contato. Em caso de problemas ou solicitações, estamos à disposição para atendê-lo. 
													<br><br>
													Para maiores informações acesse o CAU. 
													<br>Esta é uma mensagem automática, favor não responder. 
													<br><br><br><hr width=\"450\" align=\"left\">
													<br>Gestão de Mudanças
													<br>Central de Atendimento ao Usuário - ".$this->pagina->nom_area_ti.". 
													<br>55 61 3429 7872 
													<br> <a href=\"".$this->pagina->enderecoGestaoTI."\"> ".$this->pagina->enderecoGestaoTI."</a>
													<br><br>
												  </td>
												</tr>  
											</table>
										</td>
									</tr>
								<tr>
									<td   bgcolor=\"#00009C\" align=\"center\" height=\"24\">
										<font face=\"arial\" size=\"1\" color=\"#FFFFFF\" >
											<b>CAU - Central de atendimento ao usuário</b>
										</font>	
									</td> 
								</tr>   
								  </table>
							 
							</body>
						   </html>
						   ";

		$mail->AddAddress($empregados->DES_EMAIL,$empregados->NOME);
		$mail->AddAddress($this->RDM->EMAIL_RESP_CHECKLIST,$this->RDM->NOME_RESP_CHECKLIST);
		
		$mail->Body    = $v_DS_CORPO;
		$mail->AltBody = $v_DS_CORPO;
		// Comentado para n~ao enviar e-mail antes do lan'camento oficial do sistema
		$mail->Send();
		$mail->ClearAddresses();
	}
	
	function sendEmailRDMFinalizacao(){
		 
		$situacaoRDM = new situacao_rdm();	
		$array_teste = split(" ",$this->RDM->DATA_HORA_PREVISTA_EXECUCAO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		 
		
		$DATA_HORA_PREVISTA_EXECUCAO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		$TIPO_RDM = $this->iif($this->RDM->TIPO==1, "Normal", "Emergencial");
		
		//ENVIAR E_MAIL PARA SOLICITANTE
		$mail = new PHPMailer();
		$mail->From     = $this->pagina->EmailRemetente;
		$mail->FromName = $this->pagina->remetenteEmailCEA;
		$mail->Sender   = $this->pagina->EmailRemetente;
		$mail->Subject  = " CAU – Notificação de Finalização de RDM ";
		
		$cidTitulo = date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."GM_Titulo.jpg",$cidTitulo,'GM_Titulo'); 
		
		$cidStatus = 'status'.date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."RDMFechamento.jpg",$cidStatus,'RDMFechamento'); 
		
		
		//$cidRodape = date('YmdHms').'.'.time();  
		//$mail->AddEmbeddedImage($this->pagina->vPathImagens."CAU_Rodape.jpg",$cidRodape,'CAU_Rodape'); 
		
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$empregados->select($this->RDM->NUM_MATRICULA_SOLICITANTE);
		
		$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
		if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
	    } else {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
	    }

		require_once 'include/PHP/class/class.chamado_rdm.php';
		$chamado_rdm = new chamado_rdm();
		$chamado_rdm->setSEQ_RDM($this->RDM->SEQ_RDM);
		$chamado_rdm->selectParam("SEQ_CHAMADO");
		$chamadosAssociados ="";
		$QtdLinhas = 0;
		while ($row = pg_fetch_array($chamado_rdm->database->result)){
			$QtdLinhas++;
			
			if($chamadosAssociados=="" && $QtdLinhas==$chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"].".";
			}else if($chamadosAssociados=="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= ", ".$row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas == $chamado_rdm->database->rows){
				$chamadosAssociados .= " e ".$row["seq_chamado"].".";
			}				 
		}
	    
		$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
							    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
							<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
							<head>
								<title>.:: CAU - Gestão de Mudanças ::.</title>
  

								  <style type=\"text/css\">
									<!--
										#body {
											color: #000000;
											font-family: verdana; arial;
											font-size: 10pt;
											background-color: #ffffff;		 
										} 
									-->
								  </style>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
								<link href=\"".$this->pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
							</head>
							
							<body bgcolor=\"#ffffff\" topmargin=\"10\" leftmargin=\"10\" marginwidth=\"10\" marginheight=\"10\">
							
							
								<table border=\"0\" cellpadding=\"0\" style=\"border:solid 1px #999999;\" cellspacing=\"0\" width=\"514\">
								    <tr>      
								      <td  align=\"center\">	
											<img src=\"cid:$cidTitulo\" border=\"0\">
											<br><br>
											
										</td>
									 </tr>	
									 <tr>
										<td>
											<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">
												<tr>
												  <td colspan=\"3\" align=\"center\"> <b>  CAU – Notificação de Finalização de RDM  </b></td>
												</tr>  
												<tr>
												  <td colspan=\"3\" align=\"center\">&nbsp;</td>
												</tr>  
												<tr>
												  <td width=\"10\">&nbsp;</td>
												  <td colspan=\"2\"> 
													Prezados,						 
													<br><br>&nbsp;&nbsp;&nbsp;&nbsp;A RDM  abaixo foi finalizada e está disponível para \"Fechamento\" no CAU. 													 												 																		 
													<br><br>
													<b>Informações da Mudança: </b>
													<br>
													<br>&nbsp;&nbsp;- Número: <b>".$this->RDM->SEQ_RDM." </b>
													<br>&nbsp;&nbsp;- Tipo: <b>".$TIPO_RDM." </b>
													<br>&nbsp;&nbsp;- Título: <b>".$this->RDM->TITULO." </b>
													<br>&nbsp;&nbsp;- Justificativa: <b>".$this->RDM->JUSTIFICATIVA." </b>
													<br>&nbsp;&nbsp;- Impacto de não executar: <b>".$this->RDM->IMPACTO_NAO_EXECUTAR."</b>. 
													<br>&nbsp;&nbsp;- Situação Atual: <b>".$situacaoRDM->getDescricao($this->RDM->SITUACAO_ATUAL)." </b>
													<br>&nbsp;&nbsp;- Responsável pela validação: <b>".$this->RDM->NOME_RESP_CHECKLIST." </b>
													<br>&nbsp;&nbsp;- Telefone: <b>".$this->RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".$this->RDM->NUMERO_TELEFONE_RESP_CHECKLIST."</b>
													<br>&nbsp;&nbsp;- Data/Hora prevista para execução: <b>".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b> 
													<br>&nbsp;&nbsp;- Chamado(s) associado(s): <b>".$chamadosAssociados." </b>
													<br><br> 
													
													<br><br>					
													<img src=\"cid:$cidStatus\" border=\"0\" width=\"500\">
													<br><br>
													
													A Central de Atendimento ao Usuário agradece o seu contato. Em caso de problemas ou solicitações, estamos à disposição para atendê-lo. 
													<br><br>
													Para maiores informações acesse o CAU. 
													<br>Esta é uma mensagem automática, favor não responder. 
													<br><br><br><hr width=\"450\" align=\"left\">
													<br>Gestão de Mudanças
													<br>Central de Atendimento ao Usuário - ".$this->pagina->nom_area_ti.". 
													<br>55 61 3429 7872 
													<br> <a href=\"".$this->pagina->enderecoGestaoTI."\"> ".$this->pagina->enderecoGestaoTI."</a>
													<br><br>
												  </td>
												</tr>  
											</table>
										</td>
									</tr>
								<tr>
									<td   bgcolor=\"#00009C\" align=\"center\" height=\"24\">
										<font face=\"arial\" size=\"1\" color=\"#FFFFFF\" >
											<b>CAU - Central de atendimento ao usuário</b>
										</font>	
									</td> 
								</tr>  
								  </table>
							 
							</body>
						   </html>
						   ";

		$mail->AddAddress($empregados->DES_EMAIL,$empregados->NOME);
		$mail->AddAddress($this->RDM->EMAIL_RESP_CHECKLIST,$this->RDM->NOME_RESP_CHECKLIST);
		
		$mail->Body    = $v_DS_CORPO;
		$mail->AltBody = $v_DS_CORPO;
		// Comentado para n~ao enviar e-mail antes do lan'camento oficial do sistema
		$mail->Send();
		$mail->ClearAddresses();
	}
	
	function sendEmailRDMFechamento(){
		 
		$situacaoRDM = new situacao_rdm();	
		$array_teste = split(" ",$this->RDM->DATA_HORA_PREVISTA_EXECUCAO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		 
		
		$DATA_HORA_PREVISTA_EXECUCAO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		$TIPO_RDM = $this->iif($this->RDM->TIPO==1, "Normal", "Emergencial");
		
		//ENVIAR E_MAIL PARA SOLICITANTE
		$mail = new PHPMailer();
		$mail->From     = $this->pagina->EmailRemetente;
		$mail->FromName = $this->pagina->remetenteEmailCEA;
		$mail->Sender   = $this->pagina->EmailRemetente;
		$mail->Subject  = " CAU – Notificação de Fechamento de RDM ";
		
		$cidTitulo = date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."GM_Titulo.jpg",$cidTitulo,'GM_Titulo'); 
		
		$cidStatus = 'status'.date('YmdHms').'.'.time();  
		$mail->AddEmbeddedImage($this->pagina->vPathImagens."RDMFechamento.jpg",$cidStatus,'RDMFechamento'); 
		
		
		//$cidRodape = date('YmdHms').'.'.time();  
		//$mail->AddEmbeddedImage($this->pagina->vPathImagens."CAU_Rodape.jpg",$cidRodape,'CAU_Rodape'); 
		
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$empregados->select($this->RDM->NUM_MATRICULA_SOLICITANTE);
		
		$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
		if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
	    } else {
	        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
	    }

		require_once 'include/PHP/class/class.chamado_rdm.php';
		$chamado_rdm = new chamado_rdm();
		$chamado_rdm->setSEQ_RDM($this->RDM->SEQ_RDM);
		$chamado_rdm->selectParam("SEQ_CHAMADO");
		$chamadosAssociados ="";
		$QtdLinhas = 0;
		while ($row = pg_fetch_array($chamado_rdm->database->result)){
			$QtdLinhas++;
			
			if($chamadosAssociados=="" && $QtdLinhas==$chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"].".";
			}else if($chamadosAssociados=="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= $row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas < $chamado_rdm->database->rows){
				$chamadosAssociados .= ", ".$row["seq_chamado"];
			}else if($chamadosAssociados!="" && $QtdLinhas == $chamado_rdm->database->rows){
				$chamadosAssociados .= " e ".$row["seq_chamado"].".";
			}				 
		}
	    
		$v_DS_CORPO = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
							    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
							<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
							<head>
								<title>.:: CAU - Gestão de Mudanças ::.</title>
  

								  <style type=\"text/css\">
									<!--
										#body {
											color: #000000;
											font-family: verdana; arial;
											font-size: 10pt;
											background-color: #ffffff;		 
										} 
									-->
								  </style>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
								<link href=\"".$this->pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
							</head>
							
							<body bgcolor=\"#ffffff\" topmargin=\"10\" leftmargin=\"10\" marginwidth=\"10\" marginheight=\"10\">
							
							
								<table border=\"0\" cellpadding=\"0\" style=\"border:solid 1px #999999;\" cellspacing=\"0\" width=\"514\">
								    <tr>      
								      <td  align=\"center\">	
											<img src=\"cid:$cidTitulo\" border=\"0\">
											<br><br>
											
										</td>
									 </tr>	
									 <tr>
										<td>
											<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">
												<tr>
												  <td colspan=\"3\" align=\"center\"> <b>  CAU – Notificação de Fechamento de RDM  </b></td>
												</tr>  
												<tr>
												  <td colspan=\"3\" align=\"center\">&nbsp;</td>
												</tr>  
												<tr>
												  <td width=\"10\">&nbsp;</td>
												  <td colspan=\"2\"> 
													Prezados,						 
													<br><br>&nbsp;&nbsp;&nbsp;&nbsp;A RDM  abaixo foi fechada no CAU. 													 												 																		 
													<br><br>
													<b>Informações da Mudança: </b>
													<br>
													<br>&nbsp;&nbsp;- Número: <b>".$this->RDM->SEQ_RDM." </b>
													<br>&nbsp;&nbsp;- Tipo: <b>".$TIPO_RDM." </b>
													<br>&nbsp;&nbsp;- Título: <b>".$this->RDM->TITULO." </b>
													<br>&nbsp;&nbsp;- Justificativa: <b>".$this->RDM->JUSTIFICATIVA." </b>
													<br>&nbsp;&nbsp;- Impacto de não executar: <b>".$this->RDM->IMPACTO_NAO_EXECUTAR."</b>. 
													<br>&nbsp;&nbsp;- Situação Atual: <b>".$situacaoRDM->getDescricao($this->RDM->SITUACAO_ATUAL)." </b>
													<br>&nbsp;&nbsp;- Responsável pela validação: <b>".$this->RDM->NOME_RESP_CHECKLIST." </b>
													<br>&nbsp;&nbsp;- Telefone: <b>".$this->RDM->DDD_TELEFONE_RESP_CHECKLIST." - ".$this->RDM->NUMERO_TELEFONE_RESP_CHECKLIST."</b>
													<br>&nbsp;&nbsp;- Data/Hora prevista para execução: <b>".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b> 
													<br>&nbsp;&nbsp;- Chamado(s) associado(s): <b>".$chamadosAssociados." </b>
													<br><br> 
													
													<br><br>					
													<img src=\"cid:$cidStatus\" border=\"0\" width=\"500\">
													<br><br>
													
													A Central de Atendimento ao Usuário agradece o seu contato. Em caso de problemas ou solicitações, estamos à disposição para atendê-lo. 
													<br><br>
													Para maiores informações acesse o CAU. 
													<br>Esta é uma mensagem automática, favor não responder. 
													<br><br><br><hr width=\"450\" align=\"left\">
													<br>Gestão de Mudanças
													<br>Central de Atendimento ao Usuário - ".$this->pagina->nom_area_ti.". 
													<br>55 61 3429 7872 
													<br> <a href=\"".$this->pagina->enderecoGestaoTI."\"> ".$this->pagina->enderecoGestaoTI."</a>
													<br><br>
												  </td>
												</tr>  
											</table>
										</td>
									</tr>
								<tr>
									<td   bgcolor=\"#00009C\" align=\"center\" height=\"24\">
										<font face=\"arial\" size=\"1\" color=\"#FFFFFF\" >
											<b>CAU - Central de atendimento ao usuário</b>
										</font>	
									</td> 
								</tr>  
								  </table>
							 
							</body>
						   </html>
						   ";

		$mail->AddAddress($empregados->DES_EMAIL,$empregados->NOME);
		$mail->AddAddress($this->RDM->EMAIL_RESP_CHECKLIST,$this->RDM->NOME_RESP_CHECKLIST);
		
		$mail->Body    = $v_DS_CORPO;
		$mail->AltBody = $v_DS_CORPO;
		// Comentado para n~ao enviar e-mail antes do lan'camento oficial do sistema
		$mail->Send();
		$mail->ClearAddresses();
	}
	
	function obterDestinatariosPorPerfil($SEQ_PERFIL_ACESSO){
		
		$sqlSelect ="
		 select	DISTINCT
			p.idpessoa as NUM_MATRICULA_RECURSO,
			u.dsloginrede as NOM_LOGIN_REDE,		
			p.nopessoa as NOME,									
			u.dsemail as DES_EMAIL
		from sgrh.tbpessoa p, sgu.tbusuario u, gestaoti.recurso_ti r, gestaoti.recurso_ti_x_perfil_acesso rp
		WHERE 
			p.idpessoa = u.idpessoa	
			and p.idpessoa = r.NUM_MATRICULA_RECURSO	
			and r.NUM_MATRICULA_RECURSO = rp. NUM_MATRICULA_RECURSO
		";

		if($SEQ_PERFIL_ACESSO != ""){
			
			$sqlSelect .= "  and rp.SEQ_PERFIL_ACESSO in ( ";
			$count = count($SEQ_PERFIL_ACESSO); 
			for ($i = 0; $i < $count; $i++) {				 
				$sqlSelect .= $SEQ_PERFIL_ACESSO[$i];
				if($i+1 < $count){
				 $sqlSelect .= ", ";
				}
			}			 
			 
			$sqlSelect .= "  ) ";			
		}

		$this->database->query($sqlSelect);
	}
	function iif($Condicao, $Sim, $Nao){
		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
	}
}
?>