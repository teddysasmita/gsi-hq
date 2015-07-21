<?php


//
//require_once('tcpdf.php');

// extend TCPF with custom functions


class Pricetagprintpdf extends TCPDF {

	private $leftmargin;
	private $topmargin;
	private $rightmargin;
	private $detaildata;
	private $masterdata;

	// Load table data from file
	public function LoadData(array $masterdata, array $detaildata) {
		// Read file lines
		$this->masterdata = $masterdata;
		$this->detaildata = $detaildata;		
	}

	// Colored table
	public function drawPricetags() {
		// Colors, line width and bold font
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFontSize(7);
		// Data
		$fill = 0;
		$this->SetX($this->leftmargin);
		$curx = $this->leftmargin;
		$cury = $this->topmargin;
		$this->setPageMark();
		
 		for($i=0; $i<count($this->detaildata); $i++) {
			$d = $this->detaildata[$i];
			for($j=0; $j<$d['qty']; $j++) {
				//$this->setXY($curx, $cury);
 				$this->image('@'.$this->masterdata['bkjpg'], $curx, $cury, 
 					$this->masterdata['labelwidth'] * 10, 
					$this->masterdata['labelheight'] * 10, '', '', '', true, 300,
 						'', false, false, 'LTRB', true);
 				$this->setXY($curx + $this->masterdata['infoposx'], $cury + $this->masterdata['infoposy']);
 				$this->SetFontSize($this->masterdata['infofontsize']);
 				$this->SetFont($this->masterdata['infofonttype']);
 				$this->MultiCell(
 					($this->masterdata['labelwidth'] * 10) - $this->masterdata['infoposx'], 
 					($this->masterdata['labelheight'] * 10) - $this->masterdata['infoposy'],
 					lookup::ItemNameFromItemID($d['iditem'])	
 				);
 				$curx += $this->masterdata['labelwidth'] * 10;
				if ($curx + ($this->masterdata['labelwidth'] * 10) > 215 ) {
					$curx = $this->leftmargin;
					$cury += $this->masterdata['labelheight'] * 10;
				}
				//$this->setXY($curx, $cury);
				//$boom = $this->checkPageBreak($this->masterdata['labelheight'] * 10, $this->topmargin);
				//$this->checkPageBreak($this->masterdata['labelheight'] * 10);
				$boom = ($cury + $this->masterdata['labelheight'] * 10) > $this->getPageHeight();
				if ($boom) { 
					$this->AddPage();
					$curx = $this->leftmargin;
					$cury = $this->topmargin;
					//$this->setXY($curx, $cury);
					$this->cell(10, 10, 'New Page '.$this->getX().' '.$this->getY());
				} else {
					//$this->cell(10, 10, 'Same Page '.$this->getX().' '.$this->getY());
				}
			}
		};	
	}

	public function init()
	{
		$this->SetCreator(PDF_CREATOR);
		$this->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
		$this->SetTitle('Cetak Label Harga');
		$this->SetSubject('Cetak Label Harga');
		$this->SetKeywords('Cetak Label Harga');
		
		// set default header data
		$this->setHeaderData(false, 0, 'GSI',
				'Cetak Label Harga', 'Testing');
		
		// set header and footer fonts
		$this->setPrintHeader(false);
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$this->setPrintFooter(false);
		
		// set default monospaced font
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		$this->leftmargin = 0;
		$this->topmargin = 0;
		$this->rightmargin = 0;
		$this->SetMargins($this->leftmargin, $this->topmargin, $this->rightmargin);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		//set auto page breaks
		$this->SetAutoPageBreak(TRUE, 0	);
		
		//set image scale factor
		$this->setImageScale(12);
		
		//set some language-dependent strings
		//$this->setLanguageArray($l);
		
		// ---------------------------------------------------------
		
		// set font
		$this->SetFont('helvetica', '', 12);
		
		// add a page
		//$this->AddPage();
		
	}
	
	public function display()
	{
		$this->addPage();
		$this->drawPricetags();
		//$this->Output('KartuStok'.$this->itemname.'-'.$this->warehousecode.'-'.date('Ymd').'.pdf', 'D');
	}
}
?>