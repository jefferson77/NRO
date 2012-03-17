<?php
require_once(NIVO.'nro/xlib/tcpdf/tcpdf.php');
require_once(NIVO.'nro/xlib/fpdi/fpdi.php');

class RELPDF extends fpdi {
    ## Clear Page header
    public function Header() {
		return true;
    }
    
    // Clear Page footer
    public function Footer() {
		return true;
    }
}


## Usage : hashcheck (datas, document full path)
function hashcheck ($data, $document) {
	global $DB;
	$thishash = hash("sha1", $data);
	$oldhash = $DB->getOne("SELECT hash FROM dochashes WHERE path LIKE '".$document."'");
	
	if (!empty($oldhash) and !file_exists($document)) $oldhash = 'X';
	
	if ($thishash != $oldhash) {
		if (empty($oldhash)) {
				$DB->inline("INSERT INTO dochashes (path, hash) VALUES ('".$document."', '".$thishash."')");
		} else {
				$DB->inline("UPDATE dochashes SET hash = '".$thishash."' WHERE path LIKE '".$document."'");
		}

		return 'new';
	} else {
		return 'ok';
	}
}

function reliure ($fichiers, $chemin = 'tmp') {
	## init
	$ret = array();
	$pdfnf = new RELPDF ('P', 'mm', 'A4', true, 'UTF-8', false);
	$ret['pages'] = 0;
	
	if (count($fichiers) > 0) {
		foreach ($fichiers as $fichier)
		{
			$pagecount = $pdfnf->setSourceFile($fichier);
			for ($i = 1; $i <= $pagecount; $i++) {
				$page = $pdfnf->ImportPage($i);
				$s = $pdfnf->getTemplatesize($page);
				$pdfnf->AddPage($s['h'] > $s['w'] ? 'P' : 'L');
				$ret['pages']++;
				$pdfnf->useTemplate($page, 0, 0);
				unset($page);
			}
		}
		
		if(!is_dir(Conf::read('Env.root')."document/temp/".$chemin."/")) mkdir(Conf::read('Env.root')."document/temp/".$chemin."/", 0777, true); 

		$ret['path'] = "document/temp/".$chemin."/".prezero($_SESSION['idagent'], 2).'-'.date("Ymd-His").'-'.prezero(dechex(rand(0, 65535)), "4").".pdf";

		$pdfnf->Output(Conf::read('Env.root').$ret['path'], 'F');
	} else {
		$ret['path'] = 'No files';
	}

	return $ret;
}
?>