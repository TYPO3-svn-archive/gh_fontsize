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
 *   55: class tx_ghfontsize_pi1 extends tslib_pibase
 *   71:     public function main($content, $conf)
 *  104:     protected function confFromFF()
 *  166:     protected function renderMenu()
 *  210:     protected function renderStyle()
 *  225:     protected function renderJS()
 *  314:     protected function calculateValue()
 *  370:     protected function buildUrlParameters($getVars)
 *  401:     protected function checkAjaxRequirements()
 *  423:     protected function parameterName2JS($parameterName)
 *
 * TOTAL FUNCTIONS: 9
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
	var $useAjax       = 0;
	var $JSparameterName = 'fontSize';

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	string		The content that is displayed on the website
	 */
	public function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_USER_INT_obj=1;

		if($this->conf['useAjax']) {
			$this->checkAjaxRequirements();
		}

		switch($this->conf['show']) {
		case 'menu':
			$this->confFromFF();
			$content = $this->renderMenu();
			return $this->pi_wrapInBaseClass($content);
			break;
		case 'style':
			$this->calculateValue();
			$content = $this->renderStyle();
			if($this->useAjax) {
				$content .= $this->renderJS();
			}
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
	protected function confFromFF() {
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

		if( 1 == $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'menuElements')) {
			$this->conf['menuElements'] = 'smaller,reset,larger';
		}
		if( 2 == $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'menuElements')) {
			$this->conf['menuElements'] = 'larger,reset,smaller';
		}
		if( 3 == $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'menuElements')) {
			$this->conf['menuElements'] = 'smaller,larger';
		}
		if( 4 == $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'menuElements')) {
			$this->conf['menuElements'] = 'larger,smaller';
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
	protected function renderMenu() {
		$this->conf['menuElements'] = t3lib_div::trimExplode(',', $this->conf['menuElements'], 1);
		if(!count($this->conf['menuElements'])) {
			$this->conf['menuElements'] = array('smaller', 'reset', 'larger');
		}
		$content = '';

		$getVars = array();
		if(!empty($this->conf['keepUrlParameters'])) {
			$getVars = t3lib_div::_GET();
		}

		$value = $this->calculateValue(true);
		$baseValue = (float) $this->conf['baseValue'];

		foreach($this->conf['menuElements'] as $element) {
			if(!in_array($element, array('smaller', 'reset', 'larger'))) {
				continue;
			}
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

			$class = '';
			if (
				($element == 'smaller' and $value < $baseValue) or
				($element == 'reset' and $value == $baseValue) or
				($element == 'larger' and $value > $baseValue)) {
				$class = 'class="active" ';
			}

			$item = '<a href="'.$url. '" '. ($this->useAjax ? 'onclick="GHfontsize.changeFontSize(\''.$element.'\'); return false;" ' : '').'id="tx-ghfontsize-'.$element.'"'.$class.' title="'.$this->pi_getLL($element, $element).'">'.$item.'</a>';
			$item = $this->cObj->wrap($item, $this->conf['elementWrap']);

			$content .= $item;
		}

		$content = $this->cObj->wrap($content, $this->conf['menuWrap']);
		return $content;
	}

	/**
	 * Generate the styles to be included into the html header
	 *
	 * @return	string		HTML
	 */
	protected function renderStyle() {
		$content = $this->conf['parameterName'].': '.$this->value.$this->conf['parameterUnit'].';';
		if(!empty($this->conf['cssElement'])) {
			$content = $this->conf['cssElement'].' { '.$content.' }';
		}
		$content = $this->cObj->wrap($content, $this->conf['styleWrap']);

		return $content;
	}

	/**
	 * Generate the javasscript to be included into the html header
	 *
	 * @return	string		HTML
	 */
	protected function renderJS() {
		$baseValue = (float) $this->conf['baseValue'];
		$minValue = (float) $this->conf['minValue'];
		if($minValue > $baseValue) {
			$minValue = $baseValue;
		}
		$maxValue = (float) $this->conf['maxValue'];
		if($maxValue < $baseValue) {
			$maxValue = $baseValue;
		}

		$content = '
	<script type="text/javascript">
	/*<![CDATA[*/

		var GHfontsizeClass = Class.create({

			initialize: function(baseValue, actValue, minValue, maxValue, increment) {
				this.baseValue = baseValue;
				this.actValue = actValue;
				this.minValue = minValue;
				this.maxValue = maxValue;
				this.increment = increment;
				this.newValue = this.actValue;
			},

			changeFontSize: function(whatToDo) {
				switch (whatToDo) {
					case "smaller":
						this.newValue = this.actValue - this.increment;
						break;
					case "reset":
						this.newValue = this.baseValue;
						break;
					case "larger":
						this.newValue = this.actValue + this.increment;
						break;
				}

				if(this.newValue < this.minValue) {
					this.newValue = this.minValue;
				}
				if(this.newValue > this.maxValue) {
					this.newValue = this.maxValue;
				}';

		if('body' == $this->conf['cssElement']) {
			$content .= '

				document.getElementsByTagName("body")[0].style.'.$this->JSparameterName.' = this.newValue + "'.$this->conf['parameterUnit'].'";
			';
		} else {
			$content .= '

				document.getElementById("'.substr($this->conf['cssElement'], 1).'").style.'.$this->JSparameterName.' = this.newValue + "'.$this->conf['parameterUnit'].'";
			';
		}
		$content .= '
				if(this.actValue != this.newValue) {
					this.saveToSession();
					if(this.newValue > this.baseValue && this.actValue <= this.baseValue) {
						Element.removeClassName("tx-ghfontsize-smaller", "active");
						Element.removeClassName("tx-ghfontsize-reset", "active");
						Element.addClassName("tx-ghfontsize-larger", "active");
					}
					if(this.newValue == this.baseValue && this.actValue != this.baseValue) {
						Element.removeClassName("tx-ghfontsize-smaller", "active");
						Element.addClassName("tx-ghfontsize-reset", "active");
						Element.removeClassName("tx-ghfontsize-larger", "active");
					}
					if(this.newValue < this.baseValue && this.actValue >= this.baseValue) {
						Element.addClassName("tx-ghfontsize-smaller", "active");
						Element.removeClassName("tx-ghfontsize-reset", "active");
						Element.removeClassName("tx-ghfontsize-larger", "active");
					}
				}
				this.actValue = this.newValue;
			},

			saveToSession: function() {
				new Ajax.Request ( "index.php", {
					parameters: {eID: "gh_fontsize", tx_ghfontsize_newvalue: this.newValue}
				});
			}
		});

		var GHfontsize = new GHfontsizeClass('.$baseValue.', '.$this->value.', '.$minValue.', '.$maxValue.', '. (float) $this->conf['increment'].');

	/*]]>*/
	</script>
';
		return $content;
	}

	/**
	 * Calculates the actual value based on defaults, session data and piVars.
	 * Writes value to session if necessary.
	 *
	 * @param  boolean  If true the new value is NOT written to the session.
	 *
	 * @return	float  The new value
	 */
	protected function calculateValue($dontSave=0) {
		if(!empty($this->conf['baseValue'])) {
			$this->value = (float) $this->conf['baseValue'];
		}

		$sessionValue = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_ghfontsize_value');

		if(!empty($sessionValue)) {
			$this->value = $sessionValue;
		}

		$newValue = $this->value;
		$baseValue = (float) $this->conf['baseValue'];
		$minValue = (float) $this->conf['minValue'];
		if($minValue > $baseValue) {
			$minValue = $baseValue;
		}
		$maxValue = (float) $this->conf['maxValue'];
		if($maxValue < $baseValue) {
			$maxValue = $baseValue;
		}

		if(!empty($this->piVars['action'])) {
			switch($this->piVars['action']) {
			case 'smaller':
				$newValue -= (float) $this->conf['increment'];
				break;
			case 'reset':
				$newValue = (float) $baseValue;
				break;
			case 'larger':
				$newValue += (float) $this->conf['increment'];
				break;
			}
		}

		if($newValue < $minValue) {
			$newValue = $minValue;
		}
		if($newValue > $maxValue) {
			$newValue = $maxValue;
		}

		if(!$dontSave and $this->value != $newValue and $newValue > 0) {
			$this->value = $newValue;
			$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_ghfontsize_value', $this->value);
		}
		if($newValue > 0) {
			return $newValue;
		} else {
			return $this->value;
		}
	}

	/**
	 * transforms multi-dimensional array into a one-dimensional array
	 *
	 * @param	array		$getVars: url parameters to be set
	 * @return	array		url parameters to be set, prepared for pi_getPageLink()
	 */
	protected function buildUrlParameters($getVars) {
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

	/**
	 * check requirements for the use of javascript / ajax
	 *
	 * @return	void
	 */
	protected function checkAjaxRequirements() {
		if(
				$this->conf['useAjax'] && (
					$this->conf['cssElement'] == 'body' || (
						substr($this->conf['cssElement'], 0, 1) == '#' &&
						!preg_match('|\s+|', $this->conf['cssElement'])
					)
				) &&
				$this->parameterName2JS($this->conf['parameterName'])
		) {
			$this->useAjax = 1;
		} else {
			$this->useAjax = 0;
		}
	}

	/**
	 * translate CSS parameter to JavaScript parameter
	 *
	 * @param	string		name of the CSS parameter
	 * @return	boolean		CSS parameter adequate for AJAX functionality
	 */
	protected function parameterName2JS($parameterName) {

		if(empty($parameterName)) {
			return false;
		}

		$JSparameters = array(
			'font-size' => 'fontSize',
			'height' => 'height',
			'max-height' => 'maxHeight',
			'min-height' => 'minHeight',
			'width' => 'width',
			'max-width' => 'maxWidth',
			'min-width' => 'minWidth',
			'left' => 'left',
			'right' => 'right',
			'top' => 'top',
			'bottom' => 'bottom',
			'margin' => 'margin',
			'margin-top' => 'marginTop',
			'margin-right' => 'marginRight',
			'margin-bottom' => 'marginBottom',
			'margin-left' => 'marginLeft',
			'border-width' => 'borderWidth',
			'border-top-width' => 'borderTopWidth',
			'border-right-width' => 'borderRightWidth',
			'border-bottom-width' => 'borderBottomWidth',
			'border-left-width' => 'borderLeftWidth',
			'padding' => 'padding',
			'padding-top' => 'paddingTop',
			'padding-right' => 'paddingRight',
			'padding-bottom' => 'paddingBottom',
			'padding-left' => 'paddingLeft',
			'letter-spacing' => 'letterSpacing',
			'line-height' => 'lineHeight',
			'word-spacing' => 'wordSpacing'
		);

		if(!array_key_exists($parameterName, $JSparameters)) {
			return false;
		}

		$this->JSparameterName = $JSparameters[$parameterName];
		return true;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gh_fontsize/pi1/class.tx_ghfontsize_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gh_fontsize/pi1/class.tx_ghfontsize_pi1.php']);
}

?>