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
use Sliderck\CKFof;

/**
 * View class for a list of Maximenuck.
 */
class SliderckViewStyles extends CKView {

	protected $items;

	protected $pagination;

	protected $state;

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
		
		$this->items = $this->get('Items');

		$this->toolbar = $this->getToolbar();
		// Load the left sidebar.
		// SliderckHelper::addSubmenu();
		// Load the left sidebar.
		if ($this->input->get('layout') !== 'modal') {
			// SliderckHelper::addSubmenu($this->input->get('view', 'styles'));
			// Load the title
			JToolBarHelper::title(JText::_('COM_SLIDERCK') . ' - ' . JText::_('CK_MODULES_LIST'), 'logo_sliderck_large.png');
		}
		// if (JFactory::getApplication()->isAdmin()) $this->addToolbar();
		parent::display();
	}

	private function getToolbar() {
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		if (CKFof::userCan('create')) {

			if ($this->input->get('layout') == 'modal') {
				JToolBarHelper::addNew('style.add', 'CK_NEW');
			} else {
				// Render the popup button
				$html = '<button class="btn btn-small btn-success" onclick="CKBox.open({handler:\'iframe\', fullscreen: true, url:\'' . JUri::root(true) . '/administrator/index.php?option=com_sliderck&view=style&layout=edit&tmpl=component&id=0\'})">
						<span class="icon-new icon-white"></span>
						' . JText::_('CK_NEW') . '
						</button>';
				$bar->appendButton('Custom', $html);
			}

			JToolBarHelper::custom('style.copy', 'copy', 'copy', 'CK_COPY');
		}
		if (CKFof::userCan('edit')) {
			JToolBarHelper::custom('style.edit', 'edit', 'edit', 'CK_EDIT');
			JToolBarHelper::trash('style.delete', 'CK_TRASH');
		}

		return $bar;
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar() {
		// Load the title
		JToolBarHelper::title(JText::_('COM_SLIDERCK') . ' - ' . JText::_('CK_STYLES_LIST'), 'logo_sliderck_large.png');

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		if (CKFof::userCan('create')) {
			// JToolBarHelper::addNew('style.add', 'CK_NEW');
			if ($this->input->get('layout') == 'modal') {
				JToolBarHelper::addNew('style.add', 'CK_NEW');
			} else {
				// Render the popup button
				$html = '<button class="btn btn-small btn-success" onclick="CKBox.open({handler:\'iframe\', fullscreen: true, url:\'' . JUri::root(true) . '/administrator/index.php?option=com_sliderck&view=style&layout=modal&tmpl=component&id=0\'})">
						<span class="icon-new icon-white"></span>
						' . JText::_('JTOOLBAR_NEW') . '
						</button>';
				$bar->appendButton('Custom', $html);
			}
			JToolBarHelper::custom('style.copy', 'copy', 'copy', 'CK_COPY');
		}
		if (CKFof::userCan('edit')) {
			JToolBarHelper::custom('style.edit', 'edit', 'edit', 'CK_EDIT');
		}
		if (CKFof::userCan('delete')) {
			JToolBarHelper::trash('style.delete', 'CK_REMOVE');
		}

		if (CKFof::userCan('core.admin')) {
			JToolBarHelper::preferences('com_sliderck');
		}

		return $bar;
	}
}
