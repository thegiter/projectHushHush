<?php
	defined('root') or die;
	
	$def_cols = [
		'mc' => 'DECIMAL(40, 30)',
		'bps' => 'DECIMAL(7, 4)',
		'so' => 'INT',
		'ce' => 'DECIMAL(40, 30)',
		'der' => 'DECIMAL(6, 4)',
		'debt' => 'DECIMAL(40, 30)',
		'cap' => 'DECIMAL(40, 30)',
		'lyni' => 'DECIMAL(40, 30)',
		't12mni' => 'DECIMAL(40, 30)',
		'lyie' => 'DECIMAL(40, 30)',
		'lypii' => 'DECIMAL(40, 30)',
		'lyltd' => 'DECIMAL(40, 30)',
		'lystd' => 'DECIMAL(40, 30)',
		'lyd' => 'DECIMAL(40, 30)',
		'lye' => 'DECIMAL(40, 30)',
		'lycap' => 'DECIMAL(40, 30)',
		'lyroc' => 'DECIMAL(7, 4)',
		'slyroc' => 'DECIMAL(7, 4)',
		'tlyroc' => 'DECIMAL(7, 4)',
		'arocg' => 'DECIMAL(33, 30)',
		't12maroc' => 'DECIMAL(7, 4)',
		'lt12maroc' => 'DECIMAL(7, 4)',
		'slt12maroc' => 'DECIMAL(7, 4)',
		'tlt12maroc' => 'DECIMAL(7, 4)',
		'tlrocr' => 'DECIMAL(33, 30)',
		'lypcr' => 'DECIMAL(32, 30)',
		'lyoi' => 'DECIMAL(40, 30)',
		't12moi' => 'DECIMAL(40, 30)',
		'lyom' => 'DECIMAL(7, 4)',
		'slyom' => 'DECIMAL(7, 4)',
		'tlyom' => 'DECIMAL(7, 4)',
		'aomg' => 'DECIMAL(33, 30)',
		't12maom' => 'DECIMAL(7, 4)',
		'lt12maom' => 'DECIMAL(7, 4)',
		'slt12maom' => 'DECIMAL(7, 4)',
		'tlt12maom' => 'DECIMAL(7, 4)',
		'tlomr' => 'DECIMAL(33, 30)',
		'wacodr' => 'DECIMAL(34, 30)',
		'lyroe' => 'DECIMAL(7, 4)',
		'slyroe' => 'DECIMAL(7, 4)',
		'tlyroe' => 'DECIMAL(7, 4)',
		'aroeg' => 'DECIMAL(33, 30)',
		't12maroe' => 'DECIMAL(7, 4)',
		'lt12maroe' => 'DECIMAL(7, 4)',
		'slt12maroe' => 'DECIMAL(7, 4)',
		'tlt12maroe' => 'DECIMAL(7, 4)',
		'tlroer' => 'DECIMAL(33, 30)',
		'pci' => 'DECIMAL(40, 30)',
		'pa' => 'DECIMAL(32, 30)',
		'pfi' => 'DECIMAL(40, 30)',
		'apfi' => 'DECIMAL(40, 30)',
		'arota' => 'DECIMAL(35, 30)',
		'normArota' => 'DECIMAL(35, 30)',
		'rotaRank' => 'DECIMAL(32, 30)',
		'arote' => 'DECIMAL(35, 30)',
		'car' => 'DECIMAL(4, 2)',
		'lper' => 'DECIMAL(7, 4)',
		'aper' => 'DECIMAL(33, 30)',
		'lpbr' => 'DECIMAL(7, 4)',
		'apbr' => 'DECIMAL(33, 30)',
		'gtlp' => 'DECIMAL(35, 30)',
		'lpgc' => 'BOOLEAN',
		'gtap' => 'DECIMAL(35, 30)',
		'apgc' => 'BOOLEAN',
		'cc' => 'BOOLEAN',
		'pc' => 'BOOLEAN',
		'anios' => 'DECIMAL(40, 30)',
		'cigr' => 'DECIMAL(34, 30)',
		'prlyvIcm' => 'DECIMAL(33, 30)',
		'prlyvE' => 'DECIMAL(33, 30)',
		'prlyv' => 'DECIMAL(33, 30)',
		'cpigr' => 'DECIMAL(32, 30)',
		'prcvIcm' => 'DECIMAL(33, 30)',
		'prcv0gIcm' => 'DECIMAL(33, 30)',
		'prcvE' => 'DECIMAL(33, 30)',
		'prcv0gE' => 'DECIMAL(33, 30)',
		'prcv' => 'DECIMAL(33, 30)',
		'prcv0g' => 'DECIMAL(33, 30)',
		'fpigr' => 'DECIMAL(32, 30)',
		'fpIcm' => 'DECIMAL(33, 30)',
		'fpE' => 'DECIMAL(33, 30)',
		'fp' => 'DECIMAL(33, 30)',
		'fptm' => 'DECIMAL(33, 30)',
		'dwmoe' => 'DECIMAL(31, 30)',
		'afptmIcm' => 'DECIMAL(33, 30)',
		'afptmE' => 'DECIMAL(33, 30)',
		'afptm' => 'DECIMAL(33, 30)',
		'lffptmIcm' => 'DECIMAL(33, 30)',
		'lffptmE' => 'DECIMAL(33, 30)',
		'lffptm' => 'DECIMAL(33, 30)',
		'ep' => 'DECIMAL(33, 30)',
		'bp' => 'DECIMAL(33, 30)',
		'abdr' => 'DECIMAL(31, 30)',
		'niosi' => 'DECIMAL(34, 30)',
		'cp' => 'DECIMAL(7, 3)',
		'bpcpr' => 'DECIMAL(32, 30)',
		'iv' => 'DECIMAL(33, 30)',
		'ivcpr' => 'DECIMAL(32, 30)',
		'cpfptmr' => 'DECIMAL(32, 30)',
		'pow' => 'DECIMAL(32, 30)',
		'powm' => 'DECIMAL(32, 30)',
		'advice' => 'VARCHAR(32)'
	];
?>