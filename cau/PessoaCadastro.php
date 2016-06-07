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
require_once '../gestaoti/include/PHP/class/class.area_externa.php';
$pagina = new Pagina();
$banco = new empregados();
$area_externa = new area_externa();

// Configuração da págína
$pagina->cea = 1;
$pagina->flagAutetica = 0;
$pagina->flagMenu = 0;

if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Profissional do SISP"); // Indica o título do cabeçalho da página

	// Inicio do formulário
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
        
        $pagina->LinhaCampoFormulario("Órgão onde trabalha:", "right", "S",
                                        $pagina->CampoSelect("v_SEQ_AREA_EXTERNA", "S", "", "S", $area_externa->combo(2))
                                        , "left");
        
        $pagina->LinhaCampoFormulario("Sigla da lotação:", "right", "N",
                                        $pagina->CampoTexto("v_DEP_SIGLA", "N", "Sigla da área de lotação" , "10", "150", "", "")
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
                          alert(\"Preencha o campo Órgão onde trabalha\");
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
                          alert(\"Senhas não conferem. Confirme a senha novamente.\");
                          return false;
                    }

                 }
                </script>

              ";
	$pagina->MontaRodape();
}else{
	//Pesquisar o número da matricula
	$banco->login($v_DES_EMAIL);
	if($banco->NUM_MATRICULA_RECURSO == ""){
            // Setar variáveis para a inclusão
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
            $banco->setDES_SENHA($pagina->fEncriptSenha($v_DES_SENHA)); // Senha padrão 123456
            $banco->setFLG_CADASTRO_ATUALIZADO("S");
            // Incluir regstro
            $banco->insert();
            if($banco->database->error != ""){
                    $pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
            }else{
                // Incluir o perfil de acesso
                $pagina->mensagem("Pessoa cadastrada com sucesso. <br>Senha de acesso padrão atribuída: 123456");
            }
		
	}else{
		$pagina->mensagem("Registro não incluído. E-mail já cadastrado.");
	}
}
?>
