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
