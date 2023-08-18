<?php
/**
 * @copyright	Copyright (C) 2019 Cédric KEIFLIN alias ced1870
 * https://www.template-creator.com
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');

class plgSliderckFolder extends JPlugin {

	private $type = 'folder';

	function __construct(&$subject, $params) {
		parent::__construct($subject, $params);
	}

	/* 
	 * Send the infos in the source list to add the type in the plugin options
	 *
	 * Return string the source type
	 */
	public function onSliderckGetSourceName() {
		$this->loadLanguage();
		return $this->type;
	}
}