<?php

/**
 * @copyright	Copyright (C) 2017 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('JPATH_PLATFORM') or die;


class JFormFieldCklight extends JFormField {

	protected $type = 'cklight';

	protected function getLabel() {
		return '';
	}

	protected function getInput() {
		$html = array();

		// Add the label text and closing tag.
		$html[] = '<div id="' . $this->id . '-lbl" class="ckinfo">';
		$html[] = '<i class="fas fa-info" style="color:orange"></i>';
		$html[] = JText::_('SLIDERCK_USE_FREE_VERSION');
		$html[] = ' <a href="https://www.joomlack.fr/en/joomla-extensions/slider-ck" target="_blank">';
		$html[] = '<span class="cklabel cklabel-info"><i class="fas fa-link"></i> ' . JText::_('SLIDERCK_GET_PRO_INFOS') . '</label>';
		$html[] = '</a>';
		$html[] = '</div>';

//		if (! $testparams) {
//			$html[] = 'Mettre ici description de la version pro avec les fonctionnalités et le lien';
//		}

		return implode('', $html);
	}

	protected function testParams() {
		if (JFile::exists(JPATH_ROOT.'/plugins/system/sliderckparams/sliderckparams.php')) {
			$this->state = 'green';
			return JText::_('SLIDERCK_USE_PRO_VERSION');
		}
		return false;
	}
}