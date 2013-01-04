<?php

class MYpdfgeneral extends FPDF {
	var $show_head = true;
	var $titulo1 = 'Red Fire de Colima';
	var $titulo2 = '';
	var $titulo3 = '';
	
	var $hheader = '';
	
	var $limiteY = 0;
	
	/**
	 * P:Carta Vertical, L:Carta Horizontal, lP:Legal vertical, lL:Legal Horizontal
	 * @param unknown_type $orientation
	 * @param unknown_type $unit
	 * @param unknown_type $size. Letter, Legal
	 */
	function __construct($orientation='P', $unit='mm', $size='Letter'){
		parent::__construct($orientation, $unit, $size);
		
	}
    
    
    
    
    var $col=0;
    
    function SetCol($col){
    	//Move position to a column
    	$this->col=$col;
    	$x=10+$col*65;
    	$this->SetLeftMargin($x);
    	$this->SetX($x);
    }
    
    function AcceptPageBreak(){
    	if($this->col<2){
    		//Go to next column
    		$this->SetCol($this->col+1);
    		$this->SetY(10);
    		return false;
    	}else{
    		//Regrese a la primera columna y emita un salto de página
    		$this->SetCol(0);
    		return true;
    	}
    }
    
    
    
    
    /*Crear tablas*/
    var $widths;
    var $aligns;
    var $links;
    
    function SetWidths($w){
    	$this->widths=$w;
    }
    
    function SetAligns($a){
    	$this->aligns=$a;
    }
    
    function SetMyLinks($a){
    	$this->links=$a;
    }
    
    function Row($data, $header=false, $bordes=true){
    	$nb=0;
    	for($i=0;$i<count($data);$i++)
    		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    		$h=$this->FontSize*$nb+3;
    		if($header)
    			$h += 2;
    		$this->CheckPageBreak($h);
    		for($i=0;$i<count($data);$i++){
	    		$w=$this->widths[$i];
	    		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
	    		$x=$this->GetX();
	    		$y=$this->GetY();
	    		
	    		if($header && $bordes)
	    			$this->Rect($x,$y,$w,$h,'DF');
	    		elseif($bordes)
	    			$this->Rect($x,$y,$w,$h);
	    		
	    		if($header)
	    			$this->SetXY($x,$y+3);
	    		else	
	    			$this->SetXY($x,$y+2);
    			
	    		if(isset($this->links[$i]{0}) && $header==false){
	    			$this->SetTextColor(35, 95, 185);
	    			$this->Cell($w, $this->FontSize, $data[$i], 0, strlen($data[$i]), $a, false, $this->links[$i]);
	    			$this->SetTextColor(0,0,0);
	    		}else
    				$this->MultiCell($w,$this->FontSize, $data[$i],0,$a);
    			
    			$this->SetXY($x+$w,$y);
    		}
    		$this->Ln($h);
    }
    
    function CheckPageBreak($h, $limit=0){
    	$limit = $limit==0? $this->PageBreakTrigger: $limit;
    	if($this->GetY()+$h>$limit)
    		$this->AddPage($this->CurOrientation);
    }
    
    function NbLines($w,$txt){
    	$cw=&$this->CurrentFont['cw'];
    	if($w==0)
    		$w=$this->w-$this->rMargin-$this->x;
    	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    	$s=str_replace("\r",'',$txt);
    	$nb=strlen($s);
    	if($nb>0 and $s[$nb-1]=="\n")
    		$nb--;
    	$sep=-1;
    	$i=0;
    	$j=0;
    	$l=0;
    	$nl=1;
    	while($i<$nb){
    		$c=$s[$i];
    		if($c=="\n"){
    			$i++;
    			$sep=-1;
    			$j=$i;
    			$l=0;
    			$nl++;
    			continue;
    		}
    		if($c==' ')
    			$sep=$i;
    		$l+=$cw[$c];
    		if($l>$wmax){
    			if($sep==-1){
    				if($i==$j)
    					$i++;
    			}else
    				$i=$sep+1;
    			$sep=-1;
    			$j=$i;
    			$l=0;
    			$nl++;
    		}else
    			$i++;
    	}
    	return $nl;
    }
}


?>