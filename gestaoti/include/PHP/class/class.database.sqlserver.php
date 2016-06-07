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
class DatabaseSQLServer
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

	 function DatabaseSQLServer(){ // Method : begin
		 //Konstruktor
		 // ********** ADJUST THESE VALUES HERE **********
		  $this->host = "spmssql";//"S_SEBN67";     //  DSN     <<---------
		  $this->password = "3mb@pr0duc@0";  //          <<---------
		  $this->user = "usr_producao";  //          <<---------
		  $this->database = "";          //          <<---------
		  $this->rows = 0;

	 } // Method : end

	 function OpenLink(){ // Method : begin
		$this->link = odbc_connect($this->host,$this->user,$this->password);
		if(!$this->link){
			die (print "Class Database: Erro no momento da conec��o ao banco de dados");
		}
	 } // Method : end

	 function CloseDB(){ // Method : begin
		 odbc_close($this->link);
	 } // Method : end

	 function Query($query){ // Method : begin
		$this->OpenLink();
		$this->query = $query;

		$this->result = odbc_exec($this->link, $this->query) or die (print "Classe Database: Erro ao executar a consulta(".odbc_errormsg($this->link)."): <br><br>$query");

		 // $rows=mysql_affected_rows();
		if(ereg("SELECT",$query) || ereg("select",$query) || ereg("Select",$query)){
		 	$this->rows = odbc_num_rows($this->result);
		}
		if(!$this->result){
			$this->error = odbc_errormsg($this->link);
		}
		//$this->CloseDB();
	 } // Method : end

	function iif($Condicao, $Sim, $Nao){
   		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
   }

 } // Class : end

?>
