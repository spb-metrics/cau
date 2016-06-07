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
Class GridExport{
	
	var $dados;
	
	function GridExport($dados){
		
		$this->dados = $dados;
		
	}
	
	
	function exportHtml(){
		$HMTL = ""; 
		$cor = "";
		
		$HMTL .= "<table align=\"".$this->dados->GetAlign()."\" width=\"".$this->dados->GetWidth()."\" border=\"1\" style=\"border-color: black;\" cellspacing=\"1\" cellpadding=\"1\">";
		$HMTL .= "<tr>";
		$HMTL .= "<td colspan=\"".count($this->dados->headers)."\" id=\"header\">".$this->dados->titulo."</td>";
		$HMTL .= "</tr>";
		    
		$HMTL .= "<tr>";			 
		for($y=0;$y<count($this->dados->headers);$y++){
			$HMTL .= "<td id=\"header\" width=\"".$this->dados->headers[$y][1]."\">".$this->dados->headers[$y][0]."</td>"; 
		}		 
	    $HMTL .= "</tr>";
	    
		for($i=0;$i<count($this->dados->rows);$i++){
				
			if($i % 2 == 0) {
				$cor = "claro_export";
			}else{ 
				$cor = "escuro_export";
			}
	    
			$HMTL .= "<tr class=\"".$cor."\"  >";
			
			for($x=0;$x<count($this->dados->rows[$i]);$x++){
				
				$HMTL .= "<td align=\"".$this->dados->rows[$i][$x][0]."\" class=\"".$cor."\"  >";
				
				if(strpos($this->dados->rows[$i][$x][2], "img")||strpos($this->dados->rows[$i][$x][2], "IMG")
				||strpos($this->dados->rows[$i][$x][2], "image")||strpos($this->dados->rows[$i][$x][2], "IMAGE")){
					$auxSRC = split("src",$this->dados->rows[$i][$x][2]);
					$aux = split(" ",$auxSRC[1]);
					
					$HMTL .= $aux[0];
				}else{
					//$HMTL .= $this->iif(strlen($this->dados->rows[$i][$x][2])>60,substr($this->dados->rows[$i][$x][2],0,60)."...", $this->dados->rows[$i][$x][2]);
					$HMTL .= $this->dados->rows[$i][$x][2];
				}
				//$HMTL .= $this->dados->rows[$i][$x][2];
				$HMTL .= "</td>"; 
			}
	    	$HMTL .= "</tr>";
		}
		
		$HMTL .= "</table>";
		
		return $HMTL;
	}
	
	function exportHtmlToPDF(){
		$HMTL = ""; 
		$cor = "";
		
		$HMTL .= "<table align=\"".$this->dados->GetAlign()."\" width=\"".$this->dados->GetWidth()."\" border=\"1\" >";
		$HMTL .= "<tr>";
		$HMTL .= "<td colspan=\"".count($this->dados->headers)."\" >".$this->dados->titulo."</td>";
		$HMTL .= "</tr>";
		    
		$HMTL .= "<tr>";			 
		for($y=0;$y<count($this->dados->headers);$y++){
			$HMTL .= "<td  width=\"".$this->dados->headers[$y][1]."\">".$this->dados->headers[$y][0]."</td>"; 
		}		 
	    $HMTL .= "</tr>";
	    
		for($i=0;$i<count($this->dados->rows);$i++){
				
			
			$HMTL .= "<tr   >";
			
			for($x=0;$x<count($this->dados->rows[$i]);$x++){
				
				$HMTL .= "<td align=\"".$this->dados->rows[$i][$x][0]."\"  >";
				
				if(strpos($this->dados->rows[$i][$x][2], "img")||strpos($this->dados->rows[$i][$x][2], "IMG")
				||strpos($this->dados->rows[$i][$x][2], "image")||strpos($this->dados->rows[$i][$x][2], "IMAGE")){
					$auxSRC = split("src",$this->dados->rows[$i][$x][2]);
					$aux = split(" ",$auxSRC[1]);
					
					$HMTL .= $aux[0];
				}else{
					$HMTL .= $this->iif(strlen($this->dados->rows[$i][$x][2])>60,substr($this->dados->rows[$i][$x][2],0,60)."...", $this->dados->rows[$i][$x][2]);
					//$HMTL .= $this->dados->rows[$i][$x][2];
				}
				//$HMTL .= $this->dados->rows[$i][$x][2];
				$HMTL .= "</td>"; 
			}
	    	$HMTL .= "</tr>";
		}
		
		$HMTL .= "</table>";
		
		return $HMTL;
	}
	
	function iif($Condicao, $Sim, $Nao){
   		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
   }
	
}


?>