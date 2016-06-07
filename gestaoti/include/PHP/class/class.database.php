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
class Database
 { // Class : begin
	 var $host;  		//Hostname, Server
	 var $password; 	//Passwort MySQL
	 var $user; 		//User MySQL
	 var $database; 	//Datenbankname MySQL
	 var $link;
	 var $query;
	 var $result;
	 var $rows;
	 var $error;
	 
	 function Database(){ // Method : begin
		 //Konstruktor
		 // ********** ADJUST THESE VALUES HERE **********
		  $this->host = "10.0.17.121";                  //          <<---------
		  $this->password = "Infr@3r0";           //          <<---------
		  $this->user = "usr_bdgc";                   //          <<---------
		  $this->database = "bdgc";           //          <<---------
		  $this->rows = 0;
	 } // Method : end
	 
	 function OpenLink(){ // Method : begin
	  $this->link = @mysql_connect($this->host,$this->user,$this->password) or die (print "Class Database: Error while connecting to DB (link)");
	 } // Method : end
	 
	 function SelectDB(){ // Method : begin
	 	@mysql_select_db($this->database,$this->link) or die (print "Class Database: Error while selecting DB");
	 } // Method : end
	 
	 function CloseDB(){ // Method : begin
		 mysql_close();
	 } // Method : end
	 
	 function Query($query){ // Method : begin
		$this->OpenLink();
		$this->SelectDB();
		$this->query = $query;
		
		$this->result = mysql_query($query,$this->link) or die (print "Class Database: Error while executing Query (".mysql_error()."): <br><br>$query");
		 // $rows=mysql_affected_rows();
		if(ereg("SELECT",$query) || ereg("select",$query) || ereg("Select",$query)){
		 	$this->rows = mysql_num_rows($this->result);
		}
		if(!$this->result){
			$this->error = mysql_error();
		}
		$this->CloseDB();
	 } // Method : end	
 } // Class : end
 
?>
