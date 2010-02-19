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
 */

class tx_ghfontsize_realurl {

	/**
	 * Generates additional RealURL configuration and merges it with provided configuration
	 *
	 * @param  array  $paramsDefault  configuration
	 * @param  object  $pObj  Parent object
	 * @return  array  Updated configuration
	 */
	function addGhfontsizeConfig($params, &$pObj) {

		return array_merge_recursive($params['config'], array(
			'postVarSets' => array(
				'_DEFAULT' => array(
					'fontsize' => array(
						array(
							'GETvar' => 'tx_ghfontsize_pi1[action]',
						),
					),
				),
			),
		));
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gh_fontsize/class.tx_ghfontsize_realurl.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gh_fontsize/class.tx_ghfontsize_realurl.php']);
}

?>