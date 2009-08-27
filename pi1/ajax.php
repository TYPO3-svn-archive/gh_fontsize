<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Gregor Hermens <gregor@a-mazing.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

if(!defined('PATH_typo3conf')) {
	die('Access denied!');
}

$fe_user = tslib_eidtools::initFeUser();

$sessionValue = $fe_user->getKey('ses', 'tx_ghfontsize_value');

$newValue = (float) t3lib_div::_GET('tx_ghfontsize_newvalue');

if($newValue != $sessionValue) {
	$fe_user->setKey('ses', 'tx_ghfontsize_value', $newValue);
	$fe_user->storeSessionData();
}
exit;
?>