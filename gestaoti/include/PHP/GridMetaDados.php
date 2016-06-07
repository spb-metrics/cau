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