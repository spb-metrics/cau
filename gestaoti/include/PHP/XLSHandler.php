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

		// Configurações header para forçar o download
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$arquivo");
		header("Pragma: no-cache");
		header("Expires: 0"); 
?> 
