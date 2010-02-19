<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_ghfontsize_pi1.php', '_pi1', 'list_type', 0);

$TYPO3_CONF_VARS['FE']['eID_include']['gh_fontsize'] = 'EXT:gh_fontsize/pi1/ajax.php';

$TYPO3_CONF_VARS['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration']['ghfontsize'] = 'EXT:gh_fontsize/class.tx_ghfontsize_realurl.php:tx_ghfontsize_realurl->addGhfontsizeConfig';
?>