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