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
		 /*Konstruktor
		 // Valores de homologa��o
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
