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
require_once 'include/PHP/class/class.parametro.php';
$pagina = new Pagina();
$parametro = new parametro();
// Configura��o da p�g�na
if($_SESSION["NUM_MATRICULA_RECURSO"] != ""){
	$pagina->redirectTo("principal.php");
}elseif($flag == "1"){
	// EXEMPLO do uso dessa fun��o
        $flagUserValidated = 0;
        
        require_once 'include/PHP/class/class.recurso_ti.php';
        $banco = new recurso_ti();
		
        //Verificar se a autentica��o � pelo AD ou pelo sistema
		//print ">>>".$pagina->ldap_server;
        if($pagina->ldap_server == "N"){ // Pelo sistema
            $banco->login($login);
            //print "1>>".$banco->DES_SENHA." | ".$pagina->fEncriptSenha($senha)."<br>";
            if($banco->DES_SENHA == $pagina->fEncriptSenha($senha)){
                $flagUserValidated = 1;
                //print "2<br>";
            }
            //print "3<br>";
        }else{
            if ($pagina->valida_ldap("$dominio\\$login", $senha)) {
                $flagUserValidated = 1;
                $banco->login($login);
            }
        }
	
        if($flagUserValidated == 1){
	//if (true) {
		// Buscar o perfil de acesso do usu�rio
		$pagina1 = new Pagina();
		//require_once 'include/PHP/class/class.recurso_ti.php';
		//$banco = new recurso_ti();
		//$banco->login($login);
		if($banco->NUM_MATRICULA_RECURSO != ""){
			session_start();
			$_SESSION["NUM_MATRICULA_RECURSO"] = $banco->NUM_MATRICULA_RECURSO;
			$_SESSION["SEQ_PERFIL_RECURSO_TI"] = $banco->SEQ_PERFIL_RECURSO_TI;
			$_SESSION["NOM_LOGIN_REDE"] = $banco->NOM_LOGIN_REDE;
			$_SESSION["NOME"] = $banco->NOME;
			$_SESSION["NOME_ABREVIADO"] = $banco->NOME_ABREVIADO;
			$_SESSION["NOME_GUERRA"] = $banco->NOME_GUERRA;
			//$_SESSION["DEP_SIGLA"] = $banco->DEP_SIGLA;
			
			$_SESSION["DEP_ID"] = $banco->DEP_ID;
			$_SESSION["DEP_SIGLA"] = $banco->DEP_SIGLA;
			$_SESSION["UOR_ID"] = $banco->UOR_ID;
			$_SESSION["UOR_SIGLA"] = $banco->UOR_SIGLA;
			$_SESSION["COOR_ID"] = $banco->COOR_ID;
			$_SESSION["COOR_SIGLA"] = $banco->COOR_SIGLA;
			
			//$_SESSION["UOR_SIGLA"] = $banco->UOR_SIGLA;
			$_SESSION["DES_EMAIL"] = $banco->DES_EMAIL;
			$_SESSION["NUM_DDD"] = $banco->NUM_DDD;
			$_SESSION["NUM_TELEFONE"] = $banco->NUM_TELEFONE;
			$_SESSION["NUM_VOIP"] = $banco->NUM_VOIP;
			$_SESSION["DES_ATATUS"] = $banco->DES_ATATUS;
			$_SESSION["SEQ_PERFIL_ACESSO"] = $banco->SEQ_PERFIL_ACESSO;			 
			$_SESSION["SEQ_EQUIPE_TI"] = $banco->SEQ_EQUIPE_TI;
			$_SESSION["NOM_EQUIPE_TI"] = $banco->NOM_EQUIPE_TI;
			$_SESSION["NUM_MATRICULA_PRIORIZADOR"] = $banco->NUM_MATRICULA_PRIORIZADOR;
			
			$_SESSION["SEQ_CENTRAL_ATENDIMENTO"] = $banco->SEQ_CENTRAL_ATENDIMENTO;
			$_SESSION["NOM_CENTRAL_ATENDIMENTO"] = $banco->NOM_CENTRAL_ATENDIMENTO;
                        
                        $_SESSION["screenWidth"] = $screenWidth - 20;
                        
                        //require 'include/PHP/class/class.dependencias.php';
			//$dependencias = new dependencias();
			//$_SESSION["COD_DEPENDENCIA"] = $dependencias->GetCodDependencia($banco->DEP_SIGLA);
			//print "Prio = ".$banco->NUM_MATRICULA_PRIORIZADOR;

			// Perfil de acesso
			require_once 'include/PHP/class/class.recurso_ti_x_perfil_acesso.php';
			$recurso_ti_x_perfil_acesso = new recurso_ti_x_perfil_acesso();
			$recurso_ti_x_perfil_acesso->setNUM_MATRICULA_RECURSO($banco->NUM_MATRICULA_RECURSO);
			$recurso_ti_x_perfil_acesso->selectParam();
			$vPerfis = "";
			
			if($recurso_ti_x_perfil_acesso->database->rows == 0){
                                require_once 'include/PHP/class/class.perfil_acesso.php';
                                $perfil_acesso = new perfil_acesso();
                                $perfil_acesso->select($banco->SEQ_PERFIL_ACESSO[0][0]);
                                $vPerfis = $perfil_acesso->NOM_PERFIL_ACESSO;			 
			}else{				
				$vcont = 1;
				
				while ($rowTipoUsuario = pg_fetch_array($recurso_ti_x_perfil_acesso->database->result)){			 
					require_once 'include/PHP/class/class.perfil_acesso.php';
					$perfil_acesso = new perfil_acesso();
					$perfil_acesso->select($rowTipoUsuario["seq_perfil_acesso"]);
					$vPerfis .= $perfil_acesso->NOM_PERFIL_ACESSO;
					if($recurso_ti_x_perfil_acesso->database->rows > $vcont){
						$vPerfis .= ", ";
					}
					$vcont++;
				}
			}
			$_SESSION["NOM_PERFIL_ACESSO"] = $vPerfis;
			 /*TODO: NOVO PERFIL ACESSO*/
			
			// Perfil profissional
			require_once 'include/PHP/class/class.perfil_recurso_ti.php';
			$perfil_recurso_ti = new perfil_recurso_ti();
			$perfil_recurso_ti->select($banco->SEQ_PERFIL_RECURSO_TI);
			$_SESSION["NOM_PERFIL_RECURSO_TI"] = $perfil_recurso_ti->NOM_PERFIL_RECURSO_TI;

			// Verificar se o recurso � l�der ou substituto em equipe de TI
			require_once 'include/PHP/class/class.equipe_ti.php';
			$equipe_ti = new equipe_ti();
			$equipe_ti->setNUM_MATRICULA_LIDER($banco->NUM_MATRICULA_RECURSO);
			$equipe_ti->setSEQ_EQUIPE_TI($banco->SEQ_EQUIPE_TI);
			$equipe_ti->selectParam();
			if($equipe_ti->database->rows > 0){
				$_SESSION["FLG_LIDER_EQUIPE"] = "S";
			}else{ // Verificar se o recurso � substituto
				$equipe_ti = new equipe_ti();
				$equipe_ti->setNUM_MATRICULA_SUBSTITUTO($banco->NUM_MATRICULA_RECURSO);
				$equipe_ti->setSEQ_EQUIPE_TI($banco->SEQ_EQUIPE_TI);
				$equipe_ti->selectParam();
				if($equipe_ti->database->rows > 0){
					$_SESSION["FLG_LIDER_EQUIPE"] = "S";
				}else{
					$_SESSION["FLG_LIDER_EQUIPE"] = "N";
				}
			}
			
			$pagina1->redirectTo("principal.php");
		}else{
			//session_destroy();
			$pagina1->redirectTo("erro_acesso.php");
			$pagina1 = "";
		}
		$banco = "";
	} else {
		$pagina->flagAutetica = 0;
		$pagina->flagMenu = 0;
		$pagina->redirectTo("index.php?vMsgErro=Usu�rio ou Senha Inv�lida");
	}
}else{
	$pagina->SettipoPagina("O"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Login"); // Indica o t�tulo do cabe�alho da p�gina
	$pagina->flagAutetica = 0;
	$pagina->flagMenu = 0;
	$pagina->method = "post";
	$pagina->MontaCabecalho();
	$pagina->LinhaVazia(3);
	print $pagina->CampoHidden("flag", "1");
        print $pagina->CampoHidden("screenWidth", "1");
	$pagina->AbreTabelaPadrao("center", "100%");
        
        // Verificar se o sistema valida usu�rio no AD ou n�o
        if($pagina->ldap_server == "N"){
            $pagina->LinhaCampoFormulario("Login:", "right", "S", $pagina->CampoTexto("login", "S", "Nome", "30", "30", ""), "left","","40%");
            $pagina->LinhaCampoFormulario("Senha:", "right", "S", $pagina->CampoPassword("senha", "S", "Senha", "30", "30", ""), "left");
        }else{
            $pagina->LinhaCampoFormulario("Dom�nio:", "right", "S", $pagina->CampoTexto("dominio", "S", "Dom�nio", "30", "30", $parametro->GetValorParametro("dominioRedeDefault")), "left");
            $pagina->LinhaCampoFormulario("Login de Rede:", "right", "S", $pagina->CampoTexto("login", "S", "Nome", "30", "30", ""), "left");
            $pagina->LinhaCampoFormulario("Senha de Rede:", "right", "S", $pagina->CampoPassword("senha", "S", "Senha", "30", "30", ""), "left");
            $pagina->LinhaCampoFormularioColspan("center", "<br><br><font size=3>Utilize o mesmo usu�rio e senha usado para logar na rede</font>", "2");
        }
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return validaFormLocal();", " Logar "), "2");
	$pagina->FechaTabelaPadrao();
	
	if($vMsgErro!=""){
		$pagina->ScriptAlert($vMsgErro);
	}
	?>
	<script language="javascript">
            function validaFormLocal(){
                if(document.form.login.value == ""){
                    alert("Preencha o campo login.");
                    document.form.login.focus();
                    return false;
                }
                if(document.form.senha.value == ""){
                    alert("Preencha o campo senha.");
                    document.form.senha.focus();
                    return false;
                }
                return true;
            }
            document.form.screenWidth.value = screen.width;
            document.form.login.focus();
	</script>
	<?
	$pagina->MontaRodape();
}
?>