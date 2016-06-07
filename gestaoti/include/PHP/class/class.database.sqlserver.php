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
			die (print "Class Database: Erro no momento da conecção ao banco de dados");
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
