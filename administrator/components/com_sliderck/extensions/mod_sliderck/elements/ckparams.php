<?php

/**
 * @copyright	Copyright (C) 2017 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('JPATH_PLATFORM') or die;
if (!defined('SLIDERCK_MEDIA_URI'))
{
	define('SLIDERCK_MEDIA_URI', JUri::root(true) . '/media/com_sliderck');
}

class JFormFieldCkparams extends JFormField {

	protected $type = 'ckparams';

	protected function getLabel() {
		return '';
	}

	protected function getInput() {
		$html = array();

		$this->element['icon'] = 'information.png';
		// Test to see if the patch is installed
		$testparams = $this->testParams();
		$text = $testparams ? $testparams : JText::_('MOD_SLIDERCK_PARAMS_NOT_INSTALLED');

		// set the icon
		$icon = $this->element['icon'];

		// Add the label text and closing tag.
		$html[] = '<div id="' . $this->id . '-lbl" class="ckinfo">';
		$html[] = $icon ? '<img src="' . SLIDERCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" />' : '';
		$html[] = ! $testparams ? '<a href="https://www.joomlack.fr/en/joomla-extensions/slider-ck" target="_blank">' : '';
		$html[] = $text;
		$html[] = ! $testparams ? '</a>' : '';
		$html[] = '</div>';

		return implode('', $html);
	}

	protected function testParams() {
		if (JFile::exists(JPATH_ROOT.'/plugins/system/sliderckparams/sliderckparams.php')) {
			$this->element['icon'] = 'accept.png';
			return JText::_('MOD_SLIDERCK_PARAMS_INSTALLED');
		}
		return false;
	}
}