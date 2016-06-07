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
require '../gestaoti/include/PHP/class/class.pagina.php';
require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
require_once '../gestaoti/include/PHP/class/class.area_externa.php';
$pagina = new Pagina();
$banco = new empregados();
$area_externa = new area_externa();
// Configuração da págína

if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterar senha"); // Indica o título do cabeçalho da página
	
	// Inicio do formulário
	$pagina->MontaCabecalho();
        $pagina->LinhaVazia(1);
        $banco->select($_SESSION["NUM_MATRICULA_RECURSO"]);
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_PESSOA", $_SESSION["NUM_MATRICULA_RECURSO"]);
	$pagina->AbreTabelaPadrao("center", "85%");
        
	$pagina->LinhaCampoFormulario("Senha:", "right", "S", $pagina->CampoPassword("v_DES_SENHA", "S", "Senha", "30", "30", ""), "left");
	
        
        $pagina->LinhaCampoFormulario("Confirmação da Senha:", "right", "S", $pagina->CampoPassword("v_DES_SENHA_CONFIRMA", "S", "Senha", "30", "30", ""), "left");
	
        
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
    //Pesquisar o número da matricula se houver alteração do e-mail

    $banco = new empregados();
    // Alterar regstro
    if($v_DES_SENHA == $v_DES_SENHA_CONFIRMA){
        $banco->alterarSenha($v_SEQ_PESSOA, $pagina->fEncriptSenha($v_DES_SENHA));
        if($banco->database->error != ""){
                $pagina->mensagem("<br><br><br>Senha não alterada. O seguinte erro ocorreu:<br> $banco->error");
        }else{
            // Incluir o perfil de acesso
            $pagina->mensagem("<br><br><br>Senha alterada com sucesso.");
        }
    }else{
        $pagina->mensagem("<br><br><br>Senha não alterada. Senhas não conferem.");
        
    }
}
?>
