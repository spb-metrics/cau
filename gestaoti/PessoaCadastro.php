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
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.unidade_organizacional.php';
require_once 'include/PHP/class/class.tipo_funcao_administrativa.php';
$pagina = new Pagina();
$banco = new empregados();
$unidade_organizacional = new unidade_organizacional();
$tipo_funcao_administrativa = new tipo_funcao_administrativa();
// Segurança
$pagina->ForcaAutenticacao();
if(!$pagina->segurancaPerfilAlteracao($_SESSION["SEQ_PERFIL_ACESSO"])){
	$pagina->redirectTo("Recurso_tiPesquisa.php");
}

if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Colaborador da Organização"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("PessoaPesquisa.php", "", "Pesquisa"),
		 	   array("#", "tabact", "Adicionar") );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
        
        $pagina->LinhaCampoFormulario("Usuário de login:", "right", "S",
                                        $pagina->CampoTexto("v_NOM_LOGIN_REDE", "S", "Login" , "30", "150", "", "")
                                        , "left");
        
	$pagina->LinhaCampoFormulario("Nome:", "right", "S",
                                        $pagina->CampoTexto("v_NOME", "S", "Nome" , "100", "150", "", "")
                                        , "left");
        // Pegar a senha
        if($pagina->ldap_server == "N"){
            $pagina->LinhaCampoFormulario("Senha:", "right", "S", $pagina->CampoPassword("senha", "S", "Senha", "30", "30", ""), "left");
            $pagina->LinhaCampoFormulario("Redigite a Senha:", "right", "S", $pagina->CampoPassword("senha1", "S", "Senha", "30", "30", ""), "left");
        }
        
        $pagina->LinhaCampoFormulario("Nome abreviado:", "right", "N",
                                        $pagina->CampoTexto("v_NOME_ABREVIADO", "N", "Nome abreviado" , "100", "150", "", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Nome de guerra:", "right", "N",
                                        $pagina->CampoTexto("v_NOME_GUERRA", "N", "Nome abreviado" , "100", "150", "", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Lotação:", "right", "S",
                                        $pagina->CampoSelect("v_SEQ_UNIDADE_ORGANIZACIONAL", "N", "Unidade pai", "S", $unidade_organizacional->combo("NOM_UNIDADE_ORGANIZACIONAL", ""))
                                        , "left");
	
        $pagina->LinhaCampoFormulario("E-mail:", "right", "S",
                                        $pagina->CampoTexto("v_DES_EMAIL", "S", "E-mail" , "100", "150", "", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("DDD:", "right", "N",
                                        $pagina->CampoTexto("v_NUM_DDD", "N", "DDD" , "3", "3", "", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Telefone:", "right", "N",
                                        $pagina->CampoTexto("v_NUM_TELEFONE", "N", "Telefone" , "10", "10", "", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Ramal:", "right", "N",
                                        $pagina->CampoTexto("v_NUM_VOIP", "N", "Ramal" , "10", "10", "", "")
                                        , "left");
        $pagina->LinhaCampoFormulario("Função administrativa:", "right", "N",
                                        $pagina->CampoSelect("v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA", "N", "Função administrativa", "S", $tipo_funcao_administrativa->combo("2", ""))
                                        , "left");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal();", " Incluir "), "2");
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
                
                <?
                if($pagina->ldap_server == "N"){
                 ?>
                    if(document.form.senha.value == ""){
                        alert("Preencha o campo Senha");
                        document.form.senha.focus();
                        return false;
                    }
                    if(document.form.senha1.value != document.form.senha.value){
                        alert("As senhas estão diferentes.");
                        document.form.senha1.focus();
                        return false;
                    }
                 <?   
                }
                ?>
                
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
	//Pesquisar o número da matricula
	$banco->login($v_NOM_LOGIN_REDE);
	if($banco->NUM_MATRICULA_RECURSO == ""){
            if($banco->GetNomeEmpregadoByEmail($v_DES_EMAIL) == ""){
                // Setar variáveis para a inclusão
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
                if($pagina->ldap_server == "N"){
                    $banco->setDES_SENHA($pagina->fEncriptSenha($senha)); // Senha padrão 123456
                }
                //$banco->setFLG_CADASTRO_ATUALIZADO("S");
                $banco->setSEQ_TIPO_FUNCAO_ADMINISTRATIVA($v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA);
                // Incluir regstro
                $banco->insert();
                if($banco->database->error != ""){
                        $pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
                }else{
                    // Incluir o perfil de acesso
                    $pagina->mensagem("Pessoa cadastrada com sucesso.");
                }
            }else{
                $pagina->mensagem("Registro não incluído. E-mail já cadastrado.");
            }
	}else{
		$pagina->mensagem("Registro não incluído. Login já cadastrado.");
	}
}
?>
