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
		 /*Konstruktor
		 // Valores de homologação
		 $this->host = "PRODSEDE.INFRAERO.GOV.BR";
		 $this->user = "usr_gestaoti";
		 $this->password = "infraero";
		 */
		 // Valores de desenvolvimento
		 $this->host = "PRODSEDE.INFRAERO.GOV.BR";
		 $this->user = "usr_gestao_ti_homolog";
		 $this->password = "infraero";
		 //*/
		 $this->rows = 0;
	 } // Method : end

	 function OpenLink(){ // Method : begin
	 	$this->link = OCILogon($this->user, $this->password, $this->host);
	 	if (!$this->link) {
		  $e = oci_error();   // For oci_connect errors pass no handle
		  die ("Class Database Oracle: Erro ao conectar ao banco Oracle - ".$e['message']);
		}
	 } // Method : end

	 function SelectDB(){ // Method : begin

	 } // Method : end

	 function CloseDB(){ // Method : begin
		 ocilogoff($this->link);
	 } // Method : end

	 function Query($query){ // Method : begin
		$this->OpenLink();
		$this->query = $query;

		$this->result = ociparse($this->link,$this->query);
        ociexecute($this->result) or die (print "Class Database: Erro ao executar a consulta:<br>$query");
		if(ereg("SELECT",$query) || ereg("select",$query) || ereg("Select",$query)){
			//$this->rows = PGSQL_NUM_rows($this->result);
			//print "Rows = ".$this->rows;
		}
		if(!$this->result){
			$this->error = oci_error();
		}else{
			oci_commit($this->link);
		}
		$this->CloseDB();
	 } // Method : end

	 function GetSequenceValue($vSequenceName){
		$this->Query("select ".$vSequenceName.".nextval as valor FROM gestaoti.dual ");
		$row = pg_fetch_array($this->result);
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

} // class : end
?>
