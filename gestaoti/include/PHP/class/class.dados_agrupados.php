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
DEFINE("CD_COR_PESSIMO",5);
DEFINE("CD_COR_RUIM",3);
DEFINE("CD_COR_REGULAR",4);
DEFINE("CD_COR_BOM",2);
DEFINE("CD_COR_OTIMO",1);

DEFINE("COR_PESSIMO","darkgreen");
DEFINE("COR_RUIM","green");
DEFINE("COR_REGULAR","cyan");
DEFINE("COR_BOM","burlywood");
DEFINE("COR_OTIMO","orange");


class DadosAgrupados{
	
	var $dados;
	var $legenda;
	var $id;
	var $cor;

	function DadosAgrupados(){
		$this->legenda = "";
		$this->dados = array(); 
	}
	
	function SetLegenda($val){
		$this->legenda = $val;
	}
	
	function SetId($val){
		$this->id = $val;
	}
	
	function GetLegenda(){
		return $this->legenda;
	}
	
	function GetId(){
		return $this->id;
	}
	
	function addDados ($dado){
		$this->dados[] = $dado;		
	}
	
	function getCor(){
		$cor = "";
		
		switch ($this->id) {
		    case 1:
		        $cor = COR_OTIMO;
		        break;
		    case 2:
		        $cor = COR_BOM;
		        break;
		    case 3:
		        $cor = COR_RUIM;
		        break;
		    case 4:
		        $cor = COR_REGULAR;
		        break;
		    case 5:
		        $cor = COR_PESSIMO;
		        break;
		}
 
		return $cor;
	} 
	
}

 

