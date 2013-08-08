<?php
require_once dirname(__FILE__) . '/class.EnerGenieSwitcher.php';
$egs = new EnerGenieSwitcher('10.49.0.103', true);
$egs->doSwitch(array(
	1 => EnerGenieSwitcher::ON,
	2 => EnerGenieSwitcher::OFF,
	3 => EnerGenieSwitcher::ON,
	4 => EnerGenieSwitcher::OFF
));
