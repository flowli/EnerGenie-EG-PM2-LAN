<?php
require_once dirname(__FILE__) . '/class.EnerGenieSwitcher.php';
$egs = new EnerGenieSwitcher('1.2.3.4', 'my-password');
$egs->doSwitch(array(
	1 => EnerGenieSwitcher::ON,
	2 => EnerGenieSwitcher::OFF,
	3 => EnerGenieSwitcher::ON,
	4 => EnerGenieSwitcher::OFF
));
