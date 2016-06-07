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
require_once '../gestaoti/include/PHP/class/class.pagina.php';
require_once '../gestaoti/include/PHP/class/class.parametro.php';
$pagina = new Pagina();
$parametro = new parametro();
// Configuração da págína
if($_SESSION["NUM_MATRICULA"] != ""){
	$pagina->redirectTo("principal.php");
}elseif($flag == "1"){
	// EXEMPLO do uso dessa função
        $flagUserValidated = 0;
        
        // Buscar dados do catálogo
        require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
        $banco = new empregados(1);
        
        //Verificar se a autenticação é pelo AD ou pelo sistema
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
	 
		// Buscar o perfil de acesso do usuário
		$pagina1 = new Pagina();
		
		if($banco->NUM_MATRICULA_RECURSO != ""){
			session_start();
			$_SESSION["NUM_MATRICULA"] = $banco->NUM_MATRICULA_RECURSO;
			$_SESSION["NUM_MATRICULA_RECURSO"] = $banco->NUM_MATRICULA_RECURSO;
			$_SESSION["SEQ_PERFIL_RECURSO_TI"] = $banco->SEQ_PERFIL_RECURSO_TI;
			$_SESSION["NOM_LOGIN_REDE"] = $banco->NOM_LOGIN_REDE;
			$_SESSION["NOME"] = $banco->NOME;
			$_SESSION["NOME_ABREVIADO"] = $banco->NOME_ABREVIADO;
			$_SESSION["NOME_GUERRA"] = $banco->NOME_GUERRA;
			
			$_SESSION["DEP_ID"] = $banco->DEP_ID;
			$_SESSION["DEP_SIGLA"] = $banco->DEP_SIGLA;
			$_SESSION["UOR_ID"] = $banco->UOR_ID;
			$_SESSION["UOR_SIGLA"] = $banco->UOR_SIGLA;
			$_SESSION["COOR_ID"] = $banco->COOR_ID;
			$_SESSION["COOR_SIGLA"] = $banco->COOR_SIGLA;
			
			$_SESSION["DES_EMAIL"] = $banco->DES_EMAIL;
			$_SESSION["NUM_DDD"] = $banco->NUM_DDD;
			$_SESSION["NUM_TELEFONE"] = $banco->NUM_TELEFONE;
			$_SESSION["NUM_VOIP"] = $banco->NUM_VOIP;
			$_SESSION["DES_ATATUS"] = $banco->DES_ATATUS;
                        
                        $_SESSION["screenWidth"] = $screenWidth - 20;
			// Pegar o código da dependencia
			require_once '../gestaoti/include/PHP/class/class.dependencias.php';
			$dependencias = new dependencias();
			$_SESSION["COD_PENDENCIA"] = $dependencias->GetCodDependencia($banco->DEP_SIGLA);

			// Verificar se é um cliente gestor
			require_once '../gestaoti/include/PHP/class/class.item_configuracao.php';
			$item_configuracao = new item_configuracao();
			$vRetorno = $item_configuracao->selectQuantidadeItensPorCliente($banco->NUM_MATRICULA_RECURSO);
			if($vRetorno > 0){
				$_SESSION["FLG_CLIENTE_GESTOR"] = "S";
			}else{
				$_SESSION["FLG_CLIENTE_GESTOR"] = "N";
			}

			// Verificar se é cliente priorizador
			require_once '../gestaoti/include/PHP/class/class.equipe_ti.php';
			$equipe_ti = new equipe_ti();
			$vRetorno = $equipe_ti->selectQuantidadeEquipesPorCliente($banco->NUM_MATRICULA_RECURSO);
			if($vRetorno > 0){
				$_SESSION["FLG_CLIENTE_PRIORIZADOR"] = "S";
			}else{
				$_SESSION["FLG_CLIENTE_PRIORIZADOR"] = "N";
			}

			// Redirecionar para a página principal
			$pagina1->redirectTo("principal.php");
		}else{
			$pagina1->redirectTo("erro_acesso.php");
			$pagina1 = "";
		}
		$banco = "";
	} else {
		$pagina->flagAutetica = 0;
		$pagina->flagMenu = 0;
		$pagina->redirectTo("index.php?vMsgErro=Usuário ou Senha Inválida");
	}
}else{
	$pagina->SettipoPagina("O"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Login"); // Indica o título do cabeçalho da página
	$pagina->flagAutetica = 0;
	$pagina->method = "post";
	$pagina->cea = 1;
	$pagina->flagMenu = 0;
	$pagina->MontaCabecalho(0, 1);
	$pagina->LinhaVazia(3);
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
        
        print $pagina->CampoHidden("screenWidth", "1");
	
        // Verificar se o sistema valida usuário no AD ou não
        if($pagina->ldap_server == "N"){
            $pagina->LinhaCampoFormulario("Login:", "right", "S", $pagina->CampoTexto("login", "S", "Nome", "30", "30", ""), "left","","40%");
            $pagina->LinhaCampoFormulario("Senha:", "right", "S", $pagina->CampoPassword("senha", "S", "Senha", "30", "30", ""), "left");
        }else{
            $pagina->LinhaCampoFormulario("Domínio:", "right", "S", $pagina->CampoTexto("dominio", "S", "Domínio", "30", "30", $parametro->GetValorParametro("dominioRedeDefault")), "left");
            $pagina->LinhaCampoFormulario("Login de Rede:", "right", "S", $pagina->CampoTexto("login", "S", "Nome", "30", "30", ""), "left");
            $pagina->LinhaCampoFormulario("Senha de Rede:", "right", "S", $pagina->CampoPassword("senha", "S", "Senha", "30", "30", ""), "left");
            $pagina->LinhaCampoFormularioColspan("center", "<br><br><font size=3>Utilize o mesmo usuário e senha usado para logar na rede</font>", "2");
        }
        
        $pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Logar "), "2");
	$pagina->FechaTabelaPadrao();
	?>
	<script language="javascript">
                document.form.screenWidth.value = screen.width;
		document.form.login.focus();
	</script>
	<?
	$pagina->MontaRodape();
		if($vMsgErro!=""){
		$pagina->ScriptAlert($vMsgErro);
	}
}
?>