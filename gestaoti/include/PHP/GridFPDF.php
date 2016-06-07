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
require('include/PHP/FPDF/fpdf.php');

class GridFPDF extends FPDF{

	var $gridMetaDados;

	function GridFPDF($gridMetaDados){
		$this->gridMetaDados = $gridMetaDados;
		$this->FPDF();
	}

	//Simple table
	function BasicTable()
	{
		//$w = Array();

		$w = count($this->gridMetaDados->headers);
		if($w <= 6){
			$w = 100 / 2;
		}if($w < 7){
			$w = 100 / 2.5;
		}else{
			$w = 100 / 3;
		}
		//$w = 50 ;
		//Header
		for($y=0;$y<count($this->gridMetaDados->headers);$y++){
	 	//$this->Cell(50,7,$this->gridMetaDados->headers[$y][0],1);
	 	$this->Cell($w,7,$this->gridMetaDados->headers[$y][0],1);
	 	//$w[$y] = $this->gridMetaDados->headers[$y][1];
		}
		$this->Ln();
		$texto ="";
		//$w =0;
		//Data
//		$numeroDeLinhas = 0;
//		for($i=0;$i<count($this->gridMetaDados->rows);$i++){
//			for($x=0;$x<count($this->gridMetaDados->rows[$i]);$x++){
//				//$texto = $this->iif(strlen($this->gridMetaDados->rows[$i][$x][2])>$w,substr($this->gridMetaDados->rows[$i][$x][2],0,$w - 40)."...", $this->gridMetaDados->rows[$i][$x][2]);
//				$texto = $this->gridMetaDados->rows[$i][$x][2];
//				//$w = strlen($this->gridMetaDados->rows[$i][$x][2]);
//				//$this->Cell(40,6,$this->gridMetaDados->rows[$i][$x][2],1);
//				//if(strlen($this->gridMetaDados->rows[$i][$x][2])>50){
//				//$this->Cell(50,6,$texto,1);
//				//$numeroDeLinhas = $this->NbLines($w,$texto);
//				//$this->Cell($w, 6, $numeroDeLinhas." = ".$texto,1);
//				//$this->Cell2($w, 6, $numeroDeLinhas." = ".$texto,1);
//				$this->MultiCell($w,0.4,$texto,0,'L','C');
//				//}else{
//				//$this->Cell(40,6,$texto,1);
//				//}
//			}
//			$this->Ln();
//		}

	 
		for($i=0;$i<count($this->gridMetaDados->rows);$i++){
			//for($x=0;$x<count($this->gridMetaDados->rows[$i]);$x++){
				 $this->Row($this->gridMetaDados->rows[$i],$w);   
			//}
			 
		}
	}



	function iif($Condicao, $Sim, $Nao){
		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
	}
	function WordWrap($text, $maxwidth)
	{
		$arrTexto = split(" ",$text);
		$novoTexto="";
		for($i=0; $i <count($arrTexto); $i++){

			if(strlen($novoTexto." ".$arrTexto[$i])>= $maxwidth){
				$novoTexto.= "\\n";
			}

			$novoTexto = $novoTexto." ".$arrTexto[$i];

		}


	}

	function Row($data,$w)   {
		//Calculate the height of the row
		$nb=0;    
		for($i=0;$i< count($data);$i++)        
		 $nb=max($nb,$this->NbLines($w,$data[$i][2]));  
		   // $h=0.6*$nb;
		    $h= 5 * $nb;
		    
		   // $h= $w /2;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		
		//Draw the cells of the row
		for($i=0;$i< count($data);$i++)     {
			//$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();        
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
			$this->MultiCell($w,5,$data[$i][2],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);   
		  }
			//Go to the next line
			$this->Ln($h);   
	}

			function CheckPageBreak($h)   {
				//If the height h would cause an overflow, add a new page immediately
				if($this->GetY()+$h>$this->PageBreakTrigger)
				$this->AddPage($this->CurOrientation);   }

				function NbLines($w,$txt)   {
					//Computes the number of lines a MultiCell of width w will take
					$cw=&$this->CurrentFont['cw'];
					if($w==0)
					$w=$this->w-$this->rMargin-$this->x;
					$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
					$s=str_replace("\r",'',$txt);     $nb=strlen($s);
					if($nb>0 and $s[$nb-1]=="\n")         $nb--;     $sep=-1;     $i=0;     $j=0;     $l=0;     $nl=1;
					while($i<$nb)     {         $c=$s[$i];         if($c=="\n")         {             $i++;             $sep=-1;             $j=$i;             $l=0;             $nl++;             continue;         }         if($c==' ')             $sep=$i;         $l+=$cw[$c];         if($l>$wmax)         {             if($sep==-1)             {                 if($i==$j)                     $i++;             }             else                 $i=$sep+1;             $sep=-1;             $j=$i;             $l=0;             $nl++;         }         else             $i++;     }     return $nl;   }




					//===
}
?>