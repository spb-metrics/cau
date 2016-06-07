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
require '../gestaoti/include/PHP/class/class.pagina.php';
require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
require_once '../gestaoti/include/PHP/class/class.unidade_organizacional.php';
require_once '../gestaoti/include/PHP/class/class.tipo_funcao_administrativa.php';
$pagina = new Pagina();
$banco = new empregados();
$unidade_organizacional = new unidade_organizacional();
$tipo_funcao_administrativa = new tipo_funcao_administrativa();
// Configuração da págína
$pagina->cea = 1;

if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Meu cadastro"); // Indica o título do cabeçalho da página
	
	// Inicio do formulário
	$pagina->MontaCabecalho();
        $pagina->LinhaVazia(1);
        $banco->select($_SESSION["NUM_MATRICULA"]);
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_DES_EMAIL_ORIGINAL", $banco->DES_EMAIL);
	print $pagina->CampoHidden("v_SEQ_PESSOA", $_SESSION["NUM_MATRICULA"]);
	$pagina->AbreTabelaPadrao("center", "85%");
        $pagina->LinhaCampoFormulario("Login ao sistema:", "right", "S",
                                        $pagina->CampoTexto("v_NOM_LOGIN_REDE", "S", "Nome" , "100", "150", $banco->NOM_LOGIN_REDE)
                                        , "left");
        
	$pagina->LinhaCampoFormulario("Nome:", "right", "S",
                                        $pagina->CampoTexto("v_NOME", "S", "Nome" , "100", "150", $banco->NOME)
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Nome abreviado:", "right", "N",
                                        $pagina->CampoTexto("v_NOME_ABREVIADO", "N", "Nome abreviado" , "100", "150", $banco->NOME_ABREVIADO)
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Nome de guerra:", "right", "N",
                                        $pagina->CampoTexto("v_NOME_GUERRA", "N", "Nome abreviado" , "100", "150", $banco->NOME_GUERRA)
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Lotação:", "right", "S",
                                        $pagina->CampoSelect("v_SEQ_UNIDADE_ORGANIZACIONAL", "N", "Unidade  ", "S", $unidade_organizacional->combo("NOM_UNIDADE_ORGANIZACIONAL", $banco->SEQ_UNIDADE_ORGANIZACIONAL))
                                        , "left");
	
        $pagina->LinhaCampoFormulario("E-mail:", "right", "S",
                                        $pagina->CampoTexto("v_DES_EMAIL", "S", "E-mail" , "100", "150", $banco->DES_EMAIL)
                                        , "left");
        
        $pagina->LinhaCampoFormulario("DDD:", "right", "N",
                                        $pagina->CampoTexto("v_NUM_DDD", "N", "DDD" , "3", "3", $banco->NUM_DDD)
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Telefone:", "right", "N",
                                        $pagina->CampoTexto("v_NUM_TELEFONE", "N", "Telefone" , "10", "10", $banco->NUM_TELEFONE)
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Ramal:", "right", "N",
                                        $pagina->CampoTexto("v_NUM_VOIP", "N", "Ramal" , "10", "10", $banco->NUM_VOIP)
                                        , "left");
        $aItemOption = Array();
        $aItemOption[0] = array("A", $pagina->iif($banco->DES_ATATUS == "A","Selected", ""), "Ativo");
        $aItemOption[1] = array("D", $pagina->iif($banco->DES_ATATUS == "D","Selected", ""), "Desativado");
        
        //$pagina->LinhaCampoFormulario("Status:", "right", "S",
        //                                $pagina->CampoSelect("v_DES_STATUS", "S", "", "N", $aItemOption)
        //                                , "left");
        $pagina->LinhaCampoFormulario("Função administrativa:", "right", "N",
                                        $pagina->CampoSelect("v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA", "N", "Função administrativa", "S", $tipo_funcao_administrativa->combo("2", $banco->SEQ_TIPO_FUNCAO_ADMINISTRATIVA))
                                        , "left");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
        ?>
        <script language="javascript">
            function fValidaFormLocal(){
                if(document.form.v_NOM_LOGIN_REDE.value == ""){
                    alert("Preencha o campo Usuário de login");
                    document.form.v_NOM_LOGIN_REDE.focus();
                    return false;
                }
                if(document.form.v_NOME.value == ""){
                    alert("Preencha o campo Nome");
                    document.form.v_NOME.focus();
                    return false;
                }
                
                if(document.form.v_SEQ_UNIDADE_ORGANIZACIONAL.value == ""){
                    alert("Preencha o campo Lotação");
                    document.form.v_SEQ_UNIDADE_ORGANIZACIONAL.focus();
                    return false;
                }
                if(document.form.v_DES_EMAIL.value == ""){
                    alert("Preencha o campo E-mail");
                    document.form.v_DES_EMAIL.focus();
                    return false;
                }
                
                return true;
            }
        </script>
        <?
	$pagina->MontaRodape();
}else{
	//Pesquisar o número da matricula se houver alteração do e-mail
    if($v_DES_EMAIL_ORIGINAL != $v_DES_EMAIL){
        $banco->login($v_DES_EMAIL);
	if($banco->NUM_MATRICULA_RECURSO == ""){
            // Setar variáveis para a alteração
            $banco->setNOM_LOGIN_REDE($v_NOM_LOGIN_REDE);
            $banco->setNOME($v_NOME);
            $banco->setNOME_ABREVIADO($v_NOME_ABREVIADO);
            $banco->setNOME_GUERRA($v_NOME_GUERRA);
            $banco->setSEQ_UNIDADE_ORGANIZACIONAL($v_SEQ_UNIDADE_ORGANIZACIONAL);
            $banco->setDES_EMAIL($v_DES_EMAIL);
            $banco->setNUM_DDD($v_NUM_DDD);
            $banco->setNUM_TELEFONE($v_NUM_TELEFONE);
            $banco->setNUM_VOIP($v_NUM_VOIP);
            $banco->setDES_STATUS($v_DES_STATUS); // A - Ativo
            $banco->setSEQ_TIPO_FUNCAO_ADMINISTRATIVA($v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA);
            // Alterar regstro
            $banco->update($v_SEQ_PESSOA);
            if($banco->database->error != ""){
                    $pagina->mensagem("<br><br><br>Cadastro não alterado. O seguinte erro ocorreu:<br> $banco->error");
            }else{
                // Incluir o perfil de acesso
                $pagina->mensagem("<br><br><br>Cadastro alterado com sucesso.");
            }
		
	}else{
		$pagina->mensagem("<br><br><br>Cadastro não alterado. E-mail já cadastrado.");
	}
        
    }else{
        $banco = new empregados();
        // Setar variáveis para a alteração
        $banco->setNOM_LOGIN_REDE($v_NOM_LOGIN_REDE);
        $banco->setNOME($v_NOME);
        $banco->setNOME_ABREVIADO($v_NOME_ABREVIADO);
        $banco->setNOME_GUERRA($v_NOME_GUERRA);
        $banco->setSEQ_UNIDADE_ORGANIZACIONAL($v_SEQ_UNIDADE_ORGANIZACIONAL);
        $banco->setDES_EMAIL($v_DES_EMAIL);
        $banco->setNUM_DDD($v_NUM_DDD);
        $banco->setNUM_TELEFONE($v_NUM_TELEFONE);
        $banco->setNUM_VOIP($v_NUM_VOIP);
        $banco->setDES_STATUS("A"); // A - Ativo
        $banco->setSEQ_TIPO_FUNCAO_ADMINISTRATIVA($v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA);
        // Alterar regstro
        $banco->update($v_SEQ_PESSOA);
        if($banco->database->error != ""){
                $pagina->mensagem("<br><br><br>Cadastro não alterado. O seguinte erro ocorreu:<br> $banco->error");
        }else{
            // Incluir o perfil de acesso
            $pagina->ForcaAutenticacao();
            $_SESSION["NOM_LOGIN_REDE"] = $v_DES_EMAIL;
            $_SESSION["NOME"] = $v_NOME;
            $_SESSION["NOME_ABREVIADO"] = $v_NOME_ABREVIADO;
            $_SESSION["NOME_GUERRA"] = $v_NOME_ABREVIADO;
            $_SESSION["DEP_SIGLA"] = $v_DEP_SIGLA;
            $_SESSION["UOR_SIGLA"] = $v_SEQ_unidade_organizacional;
            $_SESSION["DES_EMAIL"] = $v_DES_EMAIL;
            $_SESSION["NUM_DDD"] = $v_NUM_DDD;
            $_SESSION["NUM_TELEFONE"] = $v_NUM_TELEFONE;
            $_SESSION["NUM_VOIP"] = $v_NUM_VOIP;
            $_SESSION["DES_ATATUS"] = "A";
            $_SESSION["FLG_CADASTRO_ATUALIZADO"] = "S";
            $pagina->mensagem("<br><br><br>Cadastro alterado com sucesso.");
        }
        
    }
}
?>
