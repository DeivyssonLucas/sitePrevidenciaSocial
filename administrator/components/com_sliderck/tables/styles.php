<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

class SliderckTableStyles extends JTable {

	/**
	 * Constructor
	 *
	 * @param JDatabase A database connector object
	 */
	public function __construct(&$db) {
		$this->setColumnAlias('published', 'state');
		parent::__construct('#__sliderck_styles', 'id', $db);
	}
}
