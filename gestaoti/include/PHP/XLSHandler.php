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
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){ 
	require_once '../gestaoti/include/PHP/GridMetaDados.php';
	require_once '../gestaoti/include/PHP/GridExport.php';
}else{	 
	require_once 'include/PHP/GridMetaDados.php';
	require_once 'include/PHP/GridExport.php';
}


class XLSHandler{
		
	var $gridMetaDados;
	
	function XLSHandler($gridMetaDados){
		$this->gridMetaDados = $gridMetaDados;
	}
	
	function output(){
		$gridExport = new  GridExport($this->gridMetaDados);
		 	
		// Criamos uma tabela HTML com o formato da planilha  
		print $gridExport->exportHtml(); 
	}
}

		$arquivo = "arquivo.xls";  

		// Configura��es header para for�ar o download
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$arquivo");
		header("Pragma: no-cache");
		header("Expires: 0"); 
?> 
