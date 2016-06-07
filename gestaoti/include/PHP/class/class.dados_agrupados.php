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

 

