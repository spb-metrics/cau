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
Class GridMetaDados{
	
	var $titulo;
	var $headers;
	var $projecao;
	var $sql;
	var $rows;
	var $nomeGrid;
	
	var $align;
	var $width;
	
	function GridMetaDados(){
		$this->titulo = "";
		$this->nomeGrid = "";
		$this->sql = "";
		$this->headers = array();
		$this->rows = array();
		$this->projecao = array();
	}
	
	function SetTitulo($val){
		$this->titulo = $val;
	}
	
	function SetNomeGrid($val){
		$this->nomeGrid = $val;
	}
	
	
	function SetHeaders($val){
		$this->headers = $val;
	}
	
	function SetAlign($val){
		$this->align = $val;
	}
	
	function SetWidth($val){
		$this->width = $val;
	}
	
	function SetProjecao($val){
		$this->projecao = $val;
	}
	
	function SetSql($val){
		$this->sql = $val;
	}
	
	
	//GETs
	function GetTitulo(){
		return $this->titulo;
	}
	
	
	function GetNomeGrid(){
		return $this->nomeGrid;
	}
	
	function GetHeaders(){
		return $this->headers;
	}
	
	function GetRows(){
		return $this->rows;
	}
	
	function GetAlign(){
		return $this->align;
	}
	
	function GetWidth(){
		return $this->width;
	}
	
	function addRow ($row){
		$this->rows[] = $row;		
	}
	
	function GetProjecao(){
		return $this->projecao;
	} 
	function GetSql(){
		return $this->sql;
	} 
}
?>