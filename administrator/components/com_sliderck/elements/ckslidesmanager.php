<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . '/components/com_sliderck/helpers/ckframework.php';
require_once JPATH_ADMINISTRATOR . '/components/com_sliderck/helpers/helper.php';

Sliderck\CKFramework::load();
SliderckHelper::loadCkbox();

JText::script('SLIDERCK_ADDSLIDE');
JText::script('SLIDERCK_SELECTIMAGE');
JText::script('SLIDERCK_SELECT_LINK');
JText::script('SLIDERCK_REMOVE2');
JText::script('SLIDERCK_SELECT');
JText::script('SLIDERCK_CAPTION');
JText::script('SLIDERCK_USETOSHOW');
JText::script('SLIDERCK_IMAGE');
JText::script('SLIDERCK_VIDEO');
JText::script('SLIDERCK_TEXTOPTIONS');
JText::script('SLIDERCK_IMAGEOPTIONS');
JText::script('SLIDERCK_LINKOPTIONS');
JText::script('SLIDERCK_VIDEOOPTIONS');
JText::script('SLIDERCK_ALIGNEMENT_LABEL');
JText::script('SLIDERCK_TOPLEFT');
JText::script('SLIDERCK_TOPCENTER');
JText::script('SLIDERCK_TOPRIGHT');
JText::script('SLIDERCK_MIDDLELEFT');
JText::script('SLIDERCK_CENTER');
JText::script('SLIDERCK_MIDDLERIGHT');
JText::script('SLIDERCK_BOTTOMLEFT');
JText::script('SLIDERCK_BOTTOMCENTER');
JText::script('SLIDERCK_BOTTOMRIGHT');
JText::script('SLIDERCK_LINK');
JText::script('SLIDERCK_TARGET');
JText::script('SLIDERCK_SAMEWINDOW');
JText::script('SLIDERCK_NEWWINDOW');
JText::script('SLIDERCK_VIDEOURL');
JText::script('SLIDERCK_REMOVE');
JText::script('SLIDERCK_IMPORTFROMFOLDER');
JText::script('SLIDERCK_ARTICLEOPTIONS');
JText::script('SLIDERCK_SLIDETIME');
JText::script('SLIDERCK_CLEAR');
JText::script('SLIDERCK_SELECT');
JText::script('SLIDERCK_TITLE');
JText::script('SLIDERCK_STARTDATE');
JText::script('SLIDERCK_ENDDATE');
JText::script('SLIDERCK_SAVE');
JText::script('SLIDERCK_TEXT_CUSTOM');
JText::script('SLIDERCK_ARTICLE');
JText::script('SLIDERCK_TEXT');
JText::script('SLIDERCK_ONLY_PRO');


class JFormFieldCkslidesmanager extends JFormField {

	protected $type = 'ckslidesmanager';

	protected function getInput() {
		// loads the language files from the frontend
		$lang	= JFactory::getLanguage();
		$lang->load('com_sliderck', JPATH_SITE . '/components/com_sliderck', $lang->getTag(), false);
		$lang->load('com_sliderck', JPATH_SITE, $lang->getTag(), false);

		require_once(JPATH_ROOT . '/administrator/components/com_sliderck/helpers/defines.js.php');
		$path = 'media/com_sliderck/assets/elements/ckslidesmanager/';
		JHtml::_('jquery.framework');
		// JHtml::_('jquery.ui', array('core', 'sortable'));
		JHTML::_('script', 'media/com_sliderck/assets/jquery-uick-custom.js');
		JHTML::_('script', 'media/com_sliderck/assets/admin.js');
		JHTML::_('script', $path . 'ckslidesmanager.js');
		// if (\Sliderck\CKFof::isSite()) {
			// JHTML::_('stylesheet', 'media/com_sliderck/assets/front-edition.css');
		// }
		
		JHTML::_('stylesheet', 'media/com_sliderck/assets/jquery-ui.min.css');
		JHTML::_('stylesheet', $path . 'ckslidesmanager.css');

		$html = '<input name="' . $this->name . '" id="ckslides" type="hidden" value="' . $this->value . '" />'
				. '<div class="ckaddslide ckbutton ckbutton-success" onclick="javascript:ckAddSlide(false, \'top\');"><i class="far fa-plus-square"></i> ' . JText::_('SLIDERCK_ADDSLIDE') . '</div>'
				. '<ul id="ckslideslist" class="ckinterface" style="clear:both;"></ul>'
				. '<div class="ckaddslide ckbutton ckbutton-success" onclick="javascript:ckAddSlide();"><i class="far fa-plus-square"></i> ' . JText::_('SLIDERCK_ADDSLIDE') . '</div>';

		return $html;
	}

	protected function getLabel() {

		return '';
	}
}

