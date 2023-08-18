<?php
/**
 * @copyright	Copyright (C) 2016 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('JPATH_PLATFORM') or die;

use \Sliderck\CKFof;

if (!defined('SLIDERCK_MEDIA_URI'))
{
	define('SLIDERCK_MEDIA_URI', JUri::root(true) . '/media/com_sliderck');
}

include_once JPATH_ROOT . '/administrator/components/com_sliderck/helpers/ckfof.php';

JText::script('CK_SAVE_CLOSE');

class JFormFieldCkfolder extends JFormField
{

	protected $type = 'ckfolder';

	function __construct($form = null) {
		$doc = JFactory::getDocument();

		JHtml::_('jquery.framework');
		$doc->addStyleSheet(JUri::root(true) . '/plugins/sliderck/folder/elements/ckfolder/ckfolder.css');
		$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/jquery-ui.min.js');
		$doc->addScript(JUri::root(true) . '/plugins/sliderck/folder/elements/ckfolder/ckfolder.js');
		$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/ckbox.css');
		$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/ckbox.js');

		parent::__construct($form);
	}

	protected function getInput() {
		// Initialize some field attributes.
		$icon = $this->element['icon'];
		$suffix = $this->element['suffix'];
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$defautlwidth = $suffix ? '128px' : '150px';
		$styles = ' style="width:'.$defautlwidth.';'.$this->element['styles'].'"';

		$input = JFactory::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$moduleOptions = CKFof::dbLoadObject("SELECT * FROM #__modules WHERE id = " . (int)$id);
		$params = json_decode($moduleOptions->params);
		if (isset($params->folder)) {
			echo 'test';
			$this->value = $params->folder;
		}

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
		$html = $icon ? '<div style="display:inline-block;vertical-align:top;margin-top:4px;width:20px;"><img src="' . SLIDERCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div style="display:inline-block;width:20px;"></div>';
		$html .= '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $styles . '/>';
		if ($suffix)
			$html .= '<span style="display:inline-block;line-height:25px;">' . $suffix . '</span>';
		$html .= '<div class="ckbutton" onclick="CKBox.open({url: \'index.php?option=com_sliderck&view=browse&type=folder&field=' . $this->id . '&tmpl=component&func=ckSelectFolder\'})"><i class="fas fa-edit"></i> ' . JText::_('SLIDERCK_SELECT') . '</div>';
		$html .= '<p></p><p>' . JText::_('SLIDERCK_FOLDER_EDIT_LABELS_DESC') . '</p>';
		$html .= '<p><div class="btn" onclick="ckEditLabelsFile()">' . JText::_('SLIDERCK_FOLDER_EDIT_LABELS') . '</div></p>';
//		$html .= '<h3>' . JText::_('SLIDERCK_FOLDER_SLIDES_PREVIEW') . '</h3>';
//		$html .= '<div id="' . $this->id . 'imagespreview"></div>';
		$html .= '<div id="' . $this->id . 'labelseditor" ></div>';
		return $html;
	}
}
