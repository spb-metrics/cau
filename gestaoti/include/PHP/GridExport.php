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