<?php
# =================================================================================================================================================
# = Header footer =================================================================================================================================
# =================================================================================================================================================
class MYPDF extends TCPDF {
	public function LargeurUtile() {
		return $this->getPageWidth() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT;
	}
	public function HauteurUtile() {
		return $this->getPageHeight() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT;
	}
    ## Page header
    public function Header() {
		global $titrePDF;
        $this->Image(NIVO.'print/illus/logoPrint.png', 10, 8, 0, 10);
        // Set font
        $this->SetFont('helvetica', '', 16);
        $this->Cell(80);
        $this->Cell(150, 16, $titrePDF, 0, 0, 'C');
        $this->Ln(20);
        $this->SetFont('helvetica', '', 14);
    }
    
    // Page footer
    public function Footer() {
        // Set font
        $this->SetFont('helvetica', 'I', 7);
        // Page number
        $this->Cell($this->LargeurUtile() / 3, 3, "Exception - Exception scrl", 0, 0, 'L');
        $this->Cell($this->LargeurUtile() / 3, 3, "BTW BE 430 597 846 TVA", 0, 0, 'C');
        $this->Cell($this->LargeurUtile() / 3, 3, "Tel : 02 732.74.40", 0, 0, 'R');
        $this->Ln();                           
        $this->Cell($this->LargeurUtile() / 3, 3, "Jachtlaan 195 Av. de la Chasse", 0, 0, 'L');
        $this->Cell($this->LargeurUtile() / 3, 3, "Printed ".date("d/m/Y h:i"), 0, 0, 'C');
        $this->Cell($this->LargeurUtile() / 3, 3, "Fax : 02 732.79.38", 0, 0, 'R');
        $this->Ln();                           
        $this->Cell($this->LargeurUtile() / 3, 3, "Brussel - 1040 - Bruxelles", 0, 0, 'L');
        $this->SetFont('helvetica', 'B', 7);
        $this->Cell($this->LargeurUtile() / 3, 3, "Page ".$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'C');
        $this->SetFont('helvetica', 'I', 7);
        $this->Cell($this->LargeurUtile() / 3, 3, "www.exception2.be", 0, 0, 'R');

    }
}

?>