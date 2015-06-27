<?php


require_once('config/lang/eng.php');
//require_once('tcpdf.php');

// extend TCPF with custom functions


class Itemcodeprintpdf extends TCPDF {

	private $barcodetype;
	private $detaildata;
	private $style;
	private $labelwidth;
	private $labelheight;

	// Load table data from file
	public function LoadData($barcodetype, $labelwidth, $labelheight, array $detaildata) {
		// Read file lines
		$this->barcodetype = $barcodetype;
		$this->detaildata = $detaildata;
		$this->labelwidth = $labelwidth;
		$this->labelheight = $labelheight;
	}

	// Colored table
	public function drawItemcodes() {
		// Colors, line width and bold font
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		$this->SetFontSize(10);
		// Data
		$fill = 0;
		$counter=0;
		$iditem='';
		$margin=$this->getMargins();
		for($i=0; $i<count($this->detaildata); $i++) {
			for($j=0; $j<$this->detaildata[$i]['num']; $j++) {
				unset($model);
				$model = lookup::ItemModelFromItemID($this->detaildata[$i]['iditem']);
				$used = count_chars($model, 3);
				if (strlen($used) == 1) {
					$model = lookup::ItemObjectFromItemID($this->detaildata[$i]['iditem']);
				}
				unset($brand);
				$brand = lookup::ItemBrandFromItemID($this->detaildata[$i]['iditem']);
				$this->style['label'] = $brand.'-'.$model.'-'.
						lookup::ItemCodeFromItemID($this->detaildata[$i]['iditem']);
				if (($this->GetX() + $this->labelwidth) > ($this->getPageWidth()- $margin['right'])) 
					$this->Ln((int)$this->labelheight);
				$this->write1DBarcode(lookup::ItemCodeFromItemID($this->detaildata[$i]['iditem']), $this->barcodetype,
					'', '', $this->labelwidth, $this->labelheight, 0.4, $this->style, 'T');
			}
		};	
	}

	public function init()
	{
		$this->SetCreator(PDF_CREATOR);
		$this->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
		$this->SetTitle('Cetak Itemcode');
		$this->SetSubject('Cetak Itemcode');
		$this->SetKeywords('Cetak Itemcode');
		
		// set default header data
		$this->setHeaderData(false, 0, 'Gunung Sari Intan',
				'Cetak Itemcode', 'Testing');
		
		// set header and footer fonts
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$this->setPrintFooter(false);
		
		// set default monospaced font
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		//set auto page breaks
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		//$this->setLanguageArray($l);
		
		// ---------------------------------------------------------
		
		// set font
		$this->SetFont('helvetica', '', 12);
		
		// add a page
		//$this->AddPage();
		$this->style = array(
				'position' => '',
				'align' => 'C',
				'stretch' => false,
				'fitwidth' => true,
				'cellfitalign' => '',
				'border' => true,
				'hpadding' => 'auto',
				'vpadding' => 'auto',
				'fgcolor' => array(0,0,0),
				'bgcolor' => false, //array(255,255,255),
				'text' => true,
				'font' => 'helvetica',
				'fontsize' => 8,
				'stretchtext' => 4	
		);
		
	}
	
	public function display()
	{
		$this->addPage();
		$this->drawItemcodes();
		//$this->Output('KartuStok'.$this->itemname.'-'.$this->warehousecode.'-'.date('Ymd').'.pdf', 'D');
	}
}
?>