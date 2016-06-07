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
require 'include/PHP/class/class.time_sheet.php';
$pagina = new Pagina();
$banco = new time_sheet();

// Pesquisar
$banco->select($v_SEQ_TIME_SHEET);

if($flag == "1"){
	// Verificar se a altera��o implica em conflito de hor�rios
	if($banco->ValidarSolicitacaoCorrecao($banco->NUM_MATRICULA, $v_SEQ_TIME_SHEET, $v_DTH_INICIO_CORRECAO, $v_DTH_FIM_CORRECAO) == false){
		$pagina->ScriptAlert("As datas informadas conflitam com outros lan�amentos de horas. Por favor, informe um per�odo v�lido.");
		$pagina->redirectToJS("Time_sheetSolicitaAlteracao.php?v_SEQ_TIME_SHEET=$v_SEQ_TIME_SHEET");
	}
}

if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettituloCabecalho("Solicitar corre��o de horas trabalhadas"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Time_sheetPesquisa.php", "", "Pesquisa"),
						array("#", "tabact", "Corre��o") );
	$pagina->SetaItemAba($aItemAba);

	// Valida��o de seguran�a
	//if($_SESSION["SEQ_PERFIL_ACESSO"] == "5" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){ // ==== Gestor PRTI pode ver tudo
	/*TODO: NOVO PERFIL ACESSO*/
	if($pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"]) || $pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])){	
		
		// Usu�rio pode solicitar altera��o em qualquer

	}elseif($_SESSION["FLG_LIDER_EQUIPE"] == "S"){ // Lider de equipe pode ver todas da sua equipe
		if($banco->SEQ_EQUIPE_TI != $_SESSION["SEQ_EQUIPE_TI"]){
			$pagina->ScriptAlert("N�o � poss�vel soliciar a altera��o deste registro.");
			$pagina->redirectToJS("Time_sheetPesquisa.php");
		}
	}else{ // Colaborador ve somente o seu
		if($banco->NUM_MATRICULA != $_SESSION["NUM_MATRICULA_RECURSO"]){
			$pagina->ScriptAlert("N�o � poss�vel soliciar a altera��o deste registro.");
			$pagina->redirectToJS("Time_sheetPesquisa.php");
		}
	}

	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("v_SEQ_TIME_SHEET", $v_SEQ_TIME_SHEET);
	print $pagina->CampoHidden("v_SEQ_EQUIPE_TI", $banco->SEQ_EQUIPE_TI);
	print $pagina->CampoHidden("v_DTH_INICIO_CORRECAO", "");
	print $pagina->CampoHidden("v_DTH_FIM_CORRECAO", "");
	print $pagina->CampoHidden("flag", "1");

	?>
	<script language="javascript">
		function fValidaFormLocal(){
			if(document.form.v_DAT_INICIO_CORRECAO.value == ""){
				alert("Preencha o campo Data de in�cio correta");
				return false;
			}
			if(document.form.v_HOR_INICIO_CORRECAO.value == ""){
				alert("Preencha o campo Hora de in�cio correta");
				return false;
			}
			if(document.form.v_DAT_FIM_CORRECAO.value == ""){
				alert("Preencha o campo Data de t�rmino correta");
				return false;
			}
			if(document.form.v_HOR_FIM_CORRECAO.value == ""){
				alert("Preencha o campo Hora de t�rmino correta");
				return false;
			}
			if(!comparaDatas(document.form.v_DAT_INICIO_CORRECAO, document.form.v_DAT_FIM_CORRECAO)){
				alert("A data de in�cio deve ser menor que a data de t�rminio.");
			 	return false;
			}
			if(document.form.v_TXT_JUSTIFICATIVA_CORRECAO.value == ""){
				alert("Preencha o campo justificativa");
				return false;
			}
			document.form.v_DTH_INICIO_CORRECAO.value = document.form.v_DAT_INICIO_CORRECAO.value+" "+document.form.v_HOR_INICIO_CORRECAO.value+":00";
			document.form.v_DTH_FIM_CORRECAO.value = document.form.v_DAT_FIM_CORRECAO.value+" "+document.form.v_HOR_FIM_CORRECAO.value+":00";
			return true;
		}
	</script>
	<?

	$pagina->AbreTabelaPadrao("center", "100%");
	$pagina->LinhaCampoFormularioColspanDestaque("Dados Registrados", 2);
	$pagina->LinhaCampoFormulario("Equipe:", "right", "N", $banco->NOM_EQUIPE_TI, "left", "id=".$pagina->GetIdTable(), "20%");
	$pagina->LinhaCampoFormulario("Profissional:", "right", "N", $banco->NOM_COLABORADOR, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Chamado:", "right", "N", $banco->SEQ_CHAMADO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("In�cio registrado:", "right", "N", $banco->DTH_INICIO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Fim registrado:", "right", "N", $banco->DTH_FIM, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormularioColspanDestaque("Corre��o solicitada", 2);
	$pagina->LinhaCampoFormulario("Data de in�cio correta:", "right", "S",
									  $pagina->CampoData("v_DAT_INICIO_CORRECAO", "S", "Data de in�cio", substr($banco->DTH_INICIO,0,10), "onclick=\"validarSaida = false;\"")
									  , "left", "id=".$pagina->GetIdTable(),"27%");

	$pagina->LinhaCampoFormulario("Hora de in�cio correta:", "right", "S",
									  $pagina->CampoHora("v_HOR_INICIO_CORRECAO", "S", "Hora de in�cio correta", "")
									  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Data de t�rminio correta:", "right", "S",
									  $pagina->CampoData("v_DAT_FIM_CORRECAO", "S", "Data de t�rmino", substr($banco->DTH_FIM,0,10), "onclick=\"validarSaida = false;\"")
									  , "left", "id=".$pagina->GetIdTable(),"27%");

	$pagina->LinhaCampoFormulario("Hora de t�rminio correta:", "right", "S",
									  $pagina->CampoHora("v_HOR_FIM_CORRECAO", "S", "Hora de t�rmino correta", "")
									  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Justificativa:", "right", "S",
									  $pagina->CampoTextArea("v_TXT_JUSTIFICATIVA_CORRECAO", $ObservacaoObrigatorio, "Observa��o", "80", "3", "", "onkeyup=\"ContaCaracteres(900, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">900</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal();", " Salvar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Verificar se j� existe um registro de corre��o para o lan�amento
	require 'include/PHP/class/class.correcao_time_sheet.php';
	$correcao_time_sheet = new correcao_time_sheet();
	$correcao_time_sheet->select($v_SEQ_TIME_SHEET);
	if($correcao_time_sheet->FLG_APROVADO == ""){
		// Inserir registro
		$correcao_time_sheet->setSEQ_TIME_SHEET($v_SEQ_TIME_SHEET);
		$correcao_time_sheet->setDTH_INICIO_CORRECAO($v_DTH_INICIO_CORRECAO);
		$correcao_time_sheet->setDTH_FIM_CORRECAO($v_DTH_FIM_CORRECAO);
		$correcao_time_sheet->setTXT_JUSTIFICATIVA_CORRECAO($v_TXT_JUSTIFICATIVA_CORRECAO);
		$correcao_time_sheet->setFLG_APROVADO("N");
		$correcao_time_sheet->setNUM_MATRICULA_APROVADOR("");
		$correcao_time_sheet->insert();
	}else{
		// Alterar Registro
		$correcao_time_sheet->setDTH_INICIO_CORRECAO($v_DTH_INICIO_CORRECAO);
		$correcao_time_sheet->setDTH_FIM_CORRECAO($v_DTH_FIM_CORRECAO);
		$correcao_time_sheet->setTXT_JUSTIFICATIVA_CORRECAO($v_TXT_JUSTIFICATIVA_CORRECAO);
		$correcao_time_sheet->setFLG_APROVADO("N");
		$correcao_time_sheet->setNUM_MATRICULA_APROVADOR("");
		$correcao_time_sheet->update($v_SEQ_TIME_SHEET);
	}

	// Enviar e-mail para o l�der da equipe
	require_once 'include/PHP/class/class.phpmailer.php';
	$mail = new PHPMailer();
	$mail->From     = $pagina->EmailRemetente;
	$mail->FromName = $pagina->remetenteEmailCEA;
	$mail->Sender   = $pagina->EmailRemetente;
	$mail->Subject  = "CEA - Solicita��o de altera��o de time sheet";
	$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);
	if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
    } else {
        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
    }

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
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;acaba de ser solicitada uma altera��o de lan�amento de horas de trabalho pelo profissional ".$_SESSION["NOME"].". 			   <br>
			   <br>Para avaliar a solicita��o, acesse o Gest�o TI no menu Profissionais - Time Sheet - Analisar Solicita��es de Corre��o.
			   <br>Esta e uma mensagem autom&aacute;tica, favor n&atilde;o responder.
			   <br>---
			   <br>Gest�o TI
			   <br>".$pagina->enderecoGestaoTI."
			   </div>
			   </body>
			   </html>";

	// Buscar contatos do l�der e do substituto
	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$equipe_ti->EmailLiderSubstituto($v_SEQ_EQUIPE_TI);
	// Ao l�der
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

	$pagina->ScriptAlert("Solicita��o enviada com sucesso. Aguarde aprova��o do seu l�der.");
	$pagina->redirectToJS("Time_sheetPesquisa.php");
}
?>
