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
require 'include/PHP/class/class.time_sheet.php';
$pagina = new Pagina();
$banco = new time_sheet();

// Pesquisar
$banco->select($v_SEQ_TIME_SHEET);

if($flag == "1"){
	// Verificar se a alteração implica em conflito de horários
	if($banco->ValidarSolicitacaoCorrecao($banco->NUM_MATRICULA, $v_SEQ_TIME_SHEET, $v_DTH_INICIO_CORRECAO, $v_DTH_FIM_CORRECAO) == false){
		$pagina->ScriptAlert("As datas informadas conflitam com outros lançamentos de horas. Por favor, informe um período válido.");
		$pagina->redirectToJS("Time_sheetSolicitaAlteracao.php?v_SEQ_TIME_SHEET=$v_SEQ_TIME_SHEET");
	}
}

if($flag == ""){
	// Configuração da págína
	$pagina->SettituloCabecalho("Solicitar correção de horas trabalhadas"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Time_sheetPesquisa.php", "", "Pesquisa"),
						array("#", "tabact", "Correção") );
	$pagina->SetaItemAba($aItemAba);

	// Validação de segurança
	//if($_SESSION["SEQ_PERFIL_ACESSO"] == "5" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){ // ==== Gestor PRTI pode ver tudo
	/*TODO: NOVO PERFIL ACESSO*/
	if($pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"]) || $pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])){	
		
		// Usuário pode solicitar alteração em qualquer

	}elseif($_SESSION["FLG_LIDER_EQUIPE"] == "S"){ // Lider de equipe pode ver todas da sua equipe
		if($banco->SEQ_EQUIPE_TI != $_SESSION["SEQ_EQUIPE_TI"]){
			$pagina->ScriptAlert("Não é possível soliciar a alteração deste registro.");
			$pagina->redirectToJS("Time_sheetPesquisa.php");
		}
	}else{ // Colaborador ve somente o seu
		if($banco->NUM_MATRICULA != $_SESSION["NUM_MATRICULA_RECURSO"]){
			$pagina->ScriptAlert("Não é possível soliciar a alteração deste registro.");
			$pagina->redirectToJS("Time_sheetPesquisa.php");
		}
	}

	// Inicio do formulário
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
				alert("Preencha o campo Data de início correta");
				return false;
			}
			if(document.form.v_HOR_INICIO_CORRECAO.value == ""){
				alert("Preencha o campo Hora de início correta");
				return false;
			}
			if(document.form.v_DAT_FIM_CORRECAO.value == ""){
				alert("Preencha o campo Data de término correta");
				return false;
			}
			if(document.form.v_HOR_FIM_CORRECAO.value == ""){
				alert("Preencha o campo Hora de término correta");
				return false;
			}
			if(!comparaDatas(document.form.v_DAT_INICIO_CORRECAO, document.form.v_DAT_FIM_CORRECAO)){
				alert("A data de início deve ser menor que a data de términio.");
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
	$pagina->LinhaCampoFormulario("Início registrado:", "right", "N", $banco->DTH_INICIO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Fim registrado:", "right", "N", $banco->DTH_FIM, "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormularioColspanDestaque("Correção solicitada", 2);
	$pagina->LinhaCampoFormulario("Data de início correta:", "right", "S",
									  $pagina->CampoData("v_DAT_INICIO_CORRECAO", "S", "Data de início", substr($banco->DTH_INICIO,0,10), "onclick=\"validarSaida = false;\"")
									  , "left", "id=".$pagina->GetIdTable(),"27%");

	$pagina->LinhaCampoFormulario("Hora de início correta:", "right", "S",
									  $pagina->CampoHora("v_HOR_INICIO_CORRECAO", "S", "Hora de início correta", "")
									  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Data de términio correta:", "right", "S",
									  $pagina->CampoData("v_DAT_FIM_CORRECAO", "S", "Data de término", substr($banco->DTH_FIM,0,10), "onclick=\"validarSaida = false;\"")
									  , "left", "id=".$pagina->GetIdTable(),"27%");

	$pagina->LinhaCampoFormulario("Hora de términio correta:", "right", "S",
									  $pagina->CampoHora("v_HOR_FIM_CORRECAO", "S", "Hora de término correta", "")
									  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Justificativa:", "right", "S",
									  $pagina->CampoTextArea("v_TXT_JUSTIFICATIVA_CORRECAO", $ObservacaoObrigatorio, "Observação", "80", "3", "", "onkeyup=\"ContaCaracteres(900, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">900</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal();", " Salvar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Verificar se já existe um registro de correção para o lançamento
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

	// Enviar e-mail para o líder da equipe
	require_once 'include/PHP/class/class.phpmailer.php';
	$mail = new PHPMailer();
	$mail->From     = $pagina->EmailRemetente;
	$mail->FromName = $pagina->remetenteEmailCEA;
	$mail->Sender   = $pagina->EmailRemetente;
	$mail->Subject  = "CEA - Solicitação de alteração de time sheet";
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
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;acaba de ser solicitada uma alteração de lançamento de horas de trabalho pelo profissional ".$_SESSION["NOME"].". 			   <br>
			   <br>Para avaliar a solicitação, acesse o Gestão TI no menu Profissionais - Time Sheet - Analisar Solicitações de Correção.
			   <br>Esta e uma mensagem autom&aacute;tica, favor n&atilde;o responder.
			   <br>---
			   <br>Gestão TI
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

	$pagina->ScriptAlert("Solicitação enviada com sucesso. Aguarde aprovação do seu líder.");
	$pagina->redirectToJS("Time_sheetPesquisa.php");
}
?>
