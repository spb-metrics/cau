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
require '../gestaoti/include/PHP/class/class.pagina.php';
require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
require_once '../gestaoti/include/PHP/class/class.area_externa.php';
$pagina = new Pagina();
$banco = new empregados();
$area_externa = new area_externa();
// Configura��o da p�g�na

if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterar senha"); // Indica o t�tulo do cabe�alho da p�gina
	
	// Inicio do formul�rio
	$pagina->MontaCabecalho();
        $pagina->LinhaVazia(1);
        $banco->select($_SESSION["NUM_MATRICULA_RECURSO"]);
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_PESSOA", $_SESSION["NUM_MATRICULA_RECURSO"]);
	$pagina->AbreTabelaPadrao("center", "85%");
        
	$pagina->LinhaCampoFormulario("Senha:", "right", "S", $pagina->CampoPassword("v_DES_SENHA", "S", "Senha", "30", "30", ""), "left");
	
        
        $pagina->LinhaCampoFormulario("Confirma��o da Senha:", "right", "S", $pagina->CampoPassword("v_DES_SENHA_CONFIRMA", "S", "Senha", "30", "30", ""), "left");
	
        
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
    //Pesquisar o n�mero da matricula se houver altera��o do e-mail

    $banco = new empregados();
    // Alterar regstro
    if($v_DES_SENHA == $v_DES_SENHA_CONFIRMA){
        $banco->alterarSenha($v_SEQ_PESSOA, $pagina->fEncriptSenha($v_DES_SENHA));
        if($banco->database->error != ""){
                $pagina->mensagem("<br><br><br>Senha n�o alterada. O seguinte erro ocorreu:<br> $banco->error");
        }else{
            // Incluir o perfil de acesso
            $pagina->mensagem("<br><br><br>Senha alterada com sucesso.");
        }
    }else{
        $pagina->mensagem("<br><br><br>Senha n�o alterada. Senhas n�o conferem.");
        
    }
}
?>
