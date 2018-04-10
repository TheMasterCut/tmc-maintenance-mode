<?php
/**
 * Plugin Name: The real Maintenance Mode TMC
 * Description: Modifies your .htaccess file to redirect all non-whitelisted IP's to static page. Works even if your page is broken.
 * Version:     1.0.0
 * Author:      Jakub Kuranda
 * License:     GPL-2.0+
 */

//  ----------------------------------------
//  Requirements
//  ----------------------------------------

require dirname( __FILE__ ) . '/lib/ShellPress/src/Shared/Utility/RequirementChecker.php';

$requirementChecker = new ShellPress_RequirementChecker();

$checkPHP   = $requirementChecker->checkPHPVersion( '5.3', 'TMC Maintenance mode requires PHP version >= 5.3' );
$checkWP    = $requirementChecker->checkWPVersion( '4.3', 'TMC Maintenance mode requires WP version >= 4.3' );

if( ! $checkPHP || ! $checkWP ) return;

//  ----------------------------------------
//  ShellPress
//  ----------------------------------------

use tmc\mm\src\App;

require_once( __DIR__ . '/lib/ShellPress/ShellPress.php' );
require_once( __DIR__ . '/src/App.php' );

App::initShellPress( __FILE__, 'tmc_mm', '1.0.1' );   //  <--- Remember to always change version here
