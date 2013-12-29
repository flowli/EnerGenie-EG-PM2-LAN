<?php
require_once dirname(__FILE__) . '/class.EnerGenieSwitcher.php';

/**
 * Initialize with connection parameters (ip address and credentials)
 */
$egs = new EnerGenieSwitcher('1.2.3.4', 'my-password');

/**
 * Switch port statuses
 */
$egs->doSwitch(array(
	1 => EnerGenieSwitcher::ON,
	2 => EnerGenieSwitcher::OFF,
	3 => EnerGenieSwitcher::ON,
	4 => EnerGenieSwitcher::OFF
));

/**
 * Verify the status of the ports
 */
var_dump($egs->getStatus());
