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
    include_once("../gestaoti/gestaoti_configs.inc.php");
}else{    
    include_once("gestaoti_configs.inc.php");
}
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
	 var $encoding;

	 function Database(){ // Method : begin
            global $gestaoti_settings;
            $this->host     = $gestaoti_settings['db_postgres_host'];
            $this->port     = $gestaoti_settings['db_postgres_port']; 
            $this->user     = $gestaoti_settings['db_postgres_user']; 
            $this->password = $gestaoti_settings['db_postgres_pass']; 
            $this->database = $gestaoti_settings['db_postgres_name']; 
            $this->encoding = $gestaoti_settings['db_postgres_enconding']; 
            $this->rows = 0;
	 } // Method : end

	 function OpenLink(){ // Method : begin
	 	//$this->link = pg_connect("host=".$this->host." port=5432 dbname=".$this->database." user=".$this->user." password=".$this->password);
	 	$this->link = pg_pconnect("host=".$this->host." port=".$this->port." dbname=".$this->database." user=".$this->user." password=".$this->password);
		//pg_set_client_encoding($this->link, "UTF8");
		//set_charset("utf8");		 
		
	 	if (!$this->link) {
		  die ("Class Database Postgres: Erro ao conectar ao banco Postgres - ".pg_last_error($this->link));
		}
	 } // Method : end

	 function SelectDB(){ // Method : begin

	 } // Method : end

	 function CloseDB(){ // Method : begin
		 pg_close($this->link);
	 } // Method : end

	 function Query($query){ // Method : begin
		$this->OpenLink();
		$this->query = $query;
		//pg_query($this->link," set client_encoding to '$this->encoding' ");
		pg_set_client_encoding($this->link, $this->encoding);
		
		$this->result = pg_query($this->link, $this->query);

		if(!$this->result){
			//print "Erro ao executar a consulta:<br>$query";
			$this->error = pg_last_error($this->link);
		}else{
	        //if(ereg("SELECT",$query) || ereg("select",$query) || ereg("Select",$query)){
			if(preg_match("/SELECT/",$query) || preg_match("/select/",$query) || preg_match("/Select/",$query)){
				$this->rows = pg_num_rows($this->result);
			}
		}
		$this->CloseDB();
	 } // Method : end

	 function GetSequenceValue($vSequenceName){
		$this->Query("select nextval('".$vSequenceName."') as valor ");
		$row = pg_fetch_array($this->result,0);
		//print ">>>".$row[0];
		return $row[0];
	}

	function iif($Condicao, $Sim, $Nao){
   		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
   }
   
   function GetlocalTimeStamp(){
		$this->Query(" SELECT LOCALTIMESTAMP(0)  as valor ");
		//$this->Query(" SELECT to_char(LOCALTIMESTAMP, 'DD/MM/YYYY HH24:MI:SS') as valor ");
		 
		$row = pg_fetch_array($this->result,0);
		//print ">>>".$row[0];
		return $row[0];
	}
 	function GetCurrentTimeStamp(){
		$this->Query(" SELECT CURRENT_TIMESTAMP  as valor ");
		$row = pg_fetch_array($this->result,0);
		//print ">>>".$row[0];
		return $row[0];
	}

} // class : end
?>
