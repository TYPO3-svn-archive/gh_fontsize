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
 *   52: class tx_ghfontsize_pi1 extends tslib_pibase
 *   66:     function main($content, $conf)
 *   92:     function confFromFF()
 *  142:     function renderMenu()
 *  180:     function renderStyle()
 *  196:     function calculateValue()
 *  236:     function buildUrlParameters($getVars)
 *
 * TOTAL FUNCTIONS: 6
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
		$this->pi_USER_INT_obj=1;

		switch($this->conf['show']) {
		case 'menu':
			$this->confFromFF();
			$content = $this->renderMenu();
			return $this->pi_wrapInBaseClass($content);
			break;
		case 'style':
			$this->calculateValue();
			$content = $this->renderStyle();
			return $content;
			break;
		}
		return false;
	}

	/**
	 * Get configuration from FlexForm
	 *
	 * @return	void
	 */
	function confFromFF() {
		$this->pi_initPIflexForm();

		if( 2 == $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'menuType')) {
			$this->conf['menuType'] = 'image';
		}

		if( 1 == $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'keepUrlParameters')) {
			$this->conf['keepUrlParameters'] = true;
		}
		if( 2 == $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'keepUrlParameters')) {
			$this->conf['keepUrlParameters'] = false;
		}


		if('image' == $this->conf['menuType']) {
			if($this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'smallerImageFile', 'sBilder')) {
				$this->conf['smallerImageFile'] = 'uploads/tx_ghfontsize/'.$this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'smallerImageFile', 'sBilder');
			}

			if($this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'resetImageFile', 'sBilder')) {
				$this->conf['resetImageFile'] = 'uploads/tx_ghfontsize/'.$this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'resetImageFile', 'sBilder');
			}

			if($this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'largerImageFile', 'sBilder')) {
				$this->conf['largerImageFile'] = 'uploads/tx_ghfontsize/'.$this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'largerImageFile', 'sBilder');
			}

		} else {
			if($this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'smallerText', 'sText')) {
				$this->conf['smallerText'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'smallerText', 'sText');
			}

			if($this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'resetText', 'sText')) {
				$this->conf['resetText'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'resetText', 'sText');
			}

			if($this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'largerText', 'sText')) {
				$this->conf['largerText'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'largerText', 'sText');
			}
		}

		return true;
	}

	/**
	 * Generate the HTML of the fontsize menu
	 *
	 * @return	string		HTML
	 */
	function renderMenu() {
		$elements = array('smaller','reset','larger');
		$content = '';

		$getVars = array();
		if(!empty($this->conf['keepUrlParameters'])) {
			$getVars = t3lib_div::_GET();
		}

		foreach($elements as $element) {
			$item = $this->conf[$element.'Text'];
			if('image' == $this->conf['menuType']) {
				$imgConf = array(
					'file' => $this->conf[$element.'ImageFile'],
					'altText' => $this->pi_getLL($element, $element),
				);
				$item = $this->cObj->IMAGE($imgConf);
			}

			$getVars[$this->prefixId]['action'] = $element;
			$urlParameters = $this->buildUrlParameters($getVars);
			$url = str_replace('&', '&amp;', $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $urlParameters));

			$item = '<a href="'.$url. '" class="tx-ghfontsize-'.$element.'" title="'.$this->pi_getLL($element, $element).'">'.$item.'</a>';
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
	function renderStyle() {
		$content = $this->conf['parameterName'].': '.$this->value.$this->conf['parameterUnit'].';';
		if(!empty($this->conf['cssElement'])) {
			$content = $this->conf['cssElement'].' { '.$content.' }';
		}
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
			$this->value = (float) $this->conf['baseValue'];
		}

		$sessionValue = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_ghfontsize_value');

		if(!empty($sessionValue)) {
			$this->value = $sessionValue;
		}

		$newValue = $this->value;

		if(!empty($this->piVars['action'])) {
			switch($this->piVars['action']) {
			case 'smaller':
				$newValue -= (float) $this->conf['increment'];
				break;
			case 'reset':
				$newValue = (float) $this->conf['baseValue'];
				break;
			case 'larger':
				$newValue += (float) $this->conf['increment'];
				break;
			}
		}

		if($this->value != $newValue and $newValue > 0) {
			$this->value = $newValue;
			$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_ghfontsize_value', $this->value);
		}
		return true;
	}

	/**
	 * transforms multi-dimensional array into a one-dimensional array
	 *
	 * @param	array		$getVars: url parameters to be set
	 * @return	array		url parameters to be set, prepared for pi_getPageLink()
	 */
	function buildUrlParameters($getVars) {
		if(empty($getVars) or !is_array($getVars)) {
			return array();
		}

		$return = array();

		foreach($getVars as $key => $value) {
			if(is_array($value)) {
				foreach($value as $key2 => $value2) {
					if(is_array($value2)) {
						foreach($value2 as $key3 => $value3) {
							$return[$key.'['.$key2.']['.$key3.']'] = $value3;
						}
					} else {
						$return[$key.'['.$key2.']'] = $value2;
					}
				}
			} else {
				$return[$key] = $value;
			}
		}

		return $return;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gh_fontsize/pi1/class.tx_ghfontsize_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gh_fontsize/pi1/class.tx_ghfontsize_pi1.php']);
}

?>