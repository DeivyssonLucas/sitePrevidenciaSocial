<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Sliderck\CKView;

/**
 * View class for a list of Maximenuck.
 */
class SliderckViewModules extends CKView {

	protected $items;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$user = JFactory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_sliderck') || (count($user->getAuthorisedCategories('com_sliderck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		$this->items = $this->get('Modules');

		// Load the left sidebar.
		// SliderckHelper::addSubmenu($this->input->get('view', 'modules'));
		// Load the title
		JToolBarHelper::title(JText::_('COM_SLIDERCK') . ' - ' . JText::_('CK_MODULES_LIST'), 'logo_sliderck_large.png');

		parent::display($tpl);
	}
}
