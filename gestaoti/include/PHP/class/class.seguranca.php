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
include_once("include/PHP/class/class.database.postgres.php");
include_once("include/PHP/class/class.menu.php");
require_once 'include/PHP/class/class.menu_perfil_acesso.php';


class Seguranca{

	var $NOME_ARQUIVO_COMPLETO;


	function Seguranca($NOME_ARQUIVO_COMPLETO){
		$this->NOME_ARQUIVO_COMPLETO = $NOME_ARQUIVO_COMPLETO;
	}

	function validarAcesso(){ 		 
		
		if(isset($_SESSION["SEQ_PERFIL_ACESSO"])  && isset($_SESSION["NUM_MATRICULA_RECURSO"])){
			
			$NOME_ARQUIVO_ARRAY = split("/",$this->NOME_ARQUIVO_COMPLETO);
			$NOME_ARQUIVO = $NOME_ARQUIVO_ARRAY[count($NOME_ARQUIVO_ARRAY)-1];		
			 
			$menu = new menu();
			$menu->selectByNomeArquivo($NOME_ARQUIVO);
			
			if($menu->SEQ_MENU_ACESSO != null && $menu->SEQ_MENU_ACESSO != ""){
				
				$menu_perfil_acesso = new menu_perfil_acesso();
				$menu_perfil_acesso->setSEQ_MENU_ACESSO($menu->SEQ_MENU_ACESSO);
				$menu_perfil_acesso->selectParam();
				
				$temAcesoso = false;
				
				while ($rowMenuPerfilAcesso = pg_fetch_array($menu_perfil_acesso->database->result)){			
					for ($i = 0; $i < count($_SESSION["SEQ_PERFIL_ACESSO"]); $i++) {
					    if($_SESSION["SEQ_PERFIL_ACESSO"][$i][0]== $rowMenuPerfilAcesso["seq_perfil_acesso"]){
					    	$temAcesoso = true;
					    	break;
					    }
					}
					
					if($temAcesoso){
						break;
					} 
				}
				
				if(!$temAcesoso){
					require_once 'include/PHP/class/class.pagina.php';
				    $pagina1 = new Pagina();
					$pagina1->redirectTo("AcessoNegado.php");
					$pagina1 = "";
				}
				
			}
			
		}

	}

}

?>
