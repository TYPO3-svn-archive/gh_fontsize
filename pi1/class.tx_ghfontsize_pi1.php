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
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   50: class tx_ghfontsize_pi1 extends tslib_pibase
 *   64:     function main($content, $conf)
 *   88:     function renderMenu()
 *  116:     function renderStyles()
 *  129:     function calculateValue()
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Change Font Size' for the 'gh_fontsize' extension.
 *
 * @author	Gregor Hermens <gregor@a-mazing.de>
 * @package	TYPO3
 * @subpackage	tx_ghfontsize
 */
class tx_ghfontsize_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_ghfontsize_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_ghfontsize_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'gh_fontsize';	// The extension key.
	var $pi_checkCHash = true;
	var $value         = 100;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	string		The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		switch($this->conf['show']) {
		case 'menu':
			$content = $this->renderMenu();
			return $this->pi_wrapInBaseClass($content);
			break;
		case 'style':
			$this->calculateValue();
			$content = $this->renderStyles();
			return $content;
			break;
		}
		return false;
	}

	/**
	 * Generate the HTML of the fontsize menu
	 *
	 * @return	string		HTML
	 */
	function renderMenu() {
		$elements = array('smaller','reset','larger');
		$content = '';

		foreach($elements as $element) {
			$item = $this->conf[$element.'Text'];
			if('image' == $this->conf['menuType']) {
				$imgConf = array(
					'file' => $this->conf['imageLocation'].$this->conf[$element.'ImageFile'],
					'altText' => $element,
				);
				$item = $this->cObj->IMAGE($imgConf);
			}
			$item = '<a href="'.$this->pi_getPageLink($GLOBALS['TSFE']->id, '', array($this->prefixId.'[action]' => $element)).'" class="tx-ghfontsize-'.$element.'">'.$item.'</a>';
			$item = $this->cObj->wrap($item, $this->conf['elementWrap']);

			$content .= $item;
		}

		$content = $this->cObj->wrap($content, $this->conf['menuWrap']);
		return $content;
	}

	/**
	 * Generate the styles to be included in the html header
	 *
	 * @return	string		HTML
	 */
	function renderStyles() {
		$content = $this->conf['cssElement'].' { '.$this->conf['parameterName'].': '.$this->value.$this->conf['parameterUnit'].'; }';
		$content = $this->cObj->wrap($content, $this->conf['styleWrap']);

		return $content;
	}

	/**
	 * Calculates the actual value based on defaults, session data and piVars.
	 * Writes value to session if necessary.
	 *
	 * @return	void
	 */
	function calculateValue() {
		if(!empty($this->conf['baseValue'])) {
			$this->value = (int) $this->conf['baseValue'];
		}

		$sessionValue = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_ghfontsize_value');

		if(!empty($sessionValue)) {
			$this->value = $sessionValue;
		}

		$newValue = $this->value;

		if(!empty($this->piVars['action'])) {
			switch($this->piVars['action']) {
			case 'smaller':
				$newValue -= (int) $this->conf['increment'];
				break;
			case 'reset':
				$newValue = (int) $this->conf['baseValue'];
				break;
			case 'larger':
				$newValue += (int) $this->conf['increment'];
				break;
			}
		}

		if($this->value != $newValue and $newValue > 0) {
			$this->value = $newValue;
			$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_ghfontsize_value', $this->value);
		}
		return true;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gh_fontsize/pi1/class.tx_ghfontsize_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gh_fontsize/pi1/class.tx_ghfontsize_pi1.php']);
}

?>