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
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	require_once '../gestaotiinclude/PHP/class/class.pagina.php';
	require_once '../gestaoti/include/PHP/GridMetaDados.php';
	require_once '../gestaoti/include/PHP/GridExport.php';
	require_once '../gestaoti/include/PHP/GridFPDF.php';
}else{
	require_once 'include/PHP/class/class.pagina.php';	 
	require_once 'include/PHP/GridMetaDados.php';
	require_once 'include/PHP/GridExport.php';
	require_once 'include/PHP/GridFPDF.php';	
}


class PDFHandler{
	var $gridMetaDados;
	
	function PDFHandler($gridMetaDados){
		$this->gridMetaDados = $gridMetaDados;
	}
	
	function output(){		 
		
	}
}

?>