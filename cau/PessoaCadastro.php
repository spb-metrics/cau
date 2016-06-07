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
require '../gestaoti/include/PHP/class/class.pagina.php';
require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
require_once '../gestaoti/include/PHP/class/class.area_externa.php';
$pagina = new Pagina();
$banco = new empregados();
$area_externa = new area_externa();

// Configura��o da p�g�na
$pagina->cea = 1;
$pagina->flagAutetica = 0;
$pagina->flagMenu = 0;

if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Profissional do SISP"); // Indica o t�tulo do cabe�alho da p�gina

	// Inicio do formul�rio
	$pagina->MontaCabecalho();
        $pagina->LinhaVazia(1);
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
        
	$pagina->LinhaCampoFormulario("Nome:", "right", "S",
                                        $pagina->CampoTexto("v_NOME", "S", "Nome" , "100", "150", "", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Nome abreviado:", "right", "N",
                                        $pagina->CampoTexto("v_NOME_ABREVIADO", "N", "Nome abreviado" , "100", "150", "", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Nome de guerra:", "right", "N",
                                        $pagina->CampoTexto("v_NOME_GUERRA", "N", "Nome abreviado" , "100", "150", "", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("�rg�o onde trabalha:", "right", "S",
                                        $pagina->CampoSelect("v_SEQ_AREA_EXTERNA", "S", "", "S", $area_externa->combo(2))
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Sigla da lota��o:", "right", "N",
                                        $pagina->CampoTexto("v_DEP_SIGLA", "N", "Sigla da �rea de lota��o" , "10", "150", "", "")
                                        , "left");
	
        $pagina->LinhaCampoFormulario("Senha:", "right", "N",
                                        $pagina->CampoPassword("v_DES_SENHA", "S", "Preencha o campo senha", "10", "10", "")
                                        , "left");
	
        $pagina->LinhaCampoFormulario("Redigita e Senha:", "right", "N",
                                        $pagina->CampoPassword("v_DES_SENHA_CONFIRMACAO", "S", "Preencha o campo senha", "10", "10", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("E-mail:", "right", "S",
                                        $pagina->CampoTexto("v_DES_EMAIL", "S", "E-mail" , "100", "150", "", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("DDD:", "right", "S",
                                        $pagina->CampoTexto("v_NUM_DDD", "S", "DDD" , "3", "3", "", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Telefone:", "right", "S",
                                        $pagina->CampoTexto("v_NUM_TELEFONE", "S", "Telefone" , "10", "10", "", "")
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Ramal:", "right", "N",
                                        $pagina->CampoTexto("v_NUM_VOIP", "N", "Ramal" , "10", "10", "", "")
                                        , "left");
        
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
        print "
                <script>
                 function fValidaFormLocal(){
                    if (document.form.v_NOME.value == \"\"){
                          alert(\"Preencha o campo nome\");
                          return false;
                    }
                    if (document.form.v_SEQ_AREA_EXTERNA.value == \"\"){
                          alert(\"Preencha o campo �rg�o onde trabalha\");
                          return false;
                    }
                    if (document.form.v_DES_EMAIL.value == \"\"){
                          alert(\"Preencha o campo e-mail\");
                          return false;
                    }
                    if (document.form.v_NUM_DDD.value == \"\"){
                          alert(\"Preencha o campo DDD\");
                          return false;
                    }
                    if (document.form.v_NUM_TELEFONE.value == \"\"){
                          alert(\"Preencha o campo telefone\");
                          return false;
                    }
                    if (document.form.v_DES_SENHA.value == \"\"){
                          alert(\"Preencha o campo senha\");
                          return false;
                    }
                    if (document.form.v_DES_SENHA.value != document.form.v_DES_SENHA_CONFIRMACAO.value){
                          alert(\"Senhas n�o conferem. Confirme a senha novamente.\");
                          return false;
                    }

                 }
                </script>

              ";
	$pagina->MontaRodape();
}else{
	//Pesquisar o n�mero da matricula
	$banco->login($v_DES_EMAIL);
	if($banco->NUM_MATRICULA_RECURSO == ""){
            // Setar vari�veis para a inclus�o
            $banco->setNOM_LOGIN_REDE($v_DES_EMAIL);
            $banco->setNOME($v_NOME);
            $banco->setNOME_ABREVIADO($v_NOME_ABREVIADO);
            $banco->setNOME_GUERRA($v_NOME_GUERRA);
            $banco->setDEP_SIGLA($v_DEP_SIGLA);
            $banco->setUOR_SIGLA($v_SEQ_AREA_EXTERNA);
            $banco->setDES_EMAIL($v_DES_EMAIL);
            $banco->setNUM_DDD($v_NUM_DDD);
            $banco->setNUM_TELEFONE($v_NUM_TELEFONE);
            $banco->setNUM_VOIP($v_NUM_VOIP);
            $banco->setDES_ATATUS("A"); // A - Ativo
            $banco->setDES_SENHA($pagina->fEncriptSenha($v_DES_SENHA)); // Senha padr�o 123456
            $banco->setFLG_CADASTRO_ATUALIZADO("S");
            // Incluir regstro
            $banco->insert();
            if($banco->database->error != ""){
                    $pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
            }else{
                // Incluir o perfil de acesso
                $pagina->mensagem("Pessoa cadastrada com sucesso. <br>Senha de acesso padr�o atribu�da: 123456");
            }
		
	}else{
		$pagina->mensagem("Registro n�o inclu�do. E-mail j� cadastrado.");
	}
}
?>
