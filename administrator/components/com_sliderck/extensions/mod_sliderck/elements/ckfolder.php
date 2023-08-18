<?php
/**
 * @copyright	Copyright (C) 2016 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('JPATH_PLATFORM') or die;
if (!defined('SLIDERCK_MEDIA_URI'))
{
	define('SLIDERCK_MEDIA_URI', JUri::root(true) . '/media/com_sliderck');
}

JText::script('MOD_SLIDERCK_SAVE_CLOSE');

class JFormFieldCkfolder extends JFormField
{

	protected $type = 'ckfolder';

	function __construct($form = null) {
		$doc = JFactory::getDocument();
		/*$js = 'function ckGetFolder(id, path) {
			document.getElementById(id).value = path;
			CKBox.close();
		}';
		$conf = JFactory::getConfig();
		$editor = $conf->get('editor');
		$editor = JEditor::getInstance($editor);
		echo '<div id="ckeditor">' . $editor->display('ckeditor', $html = '', $width = '', $height = '200px', $col='', $row='', $buttons = true, $id = 'ckeditor') . '</div>';
		*/
		JHtml::_('jquery.framework');
		$input	= JFactory::getApplication()->input;
		$moduleId = $input->get('id', '', 'int');
		$js = 'CKURIROOT = "' . JUri::root(true) . '";';
		$js .= 'CKURIBASE = "' . JUri::base(true) . '";';
		$js .= 'CKMODULEID = "' . $moduleId . '";';
		$doc->addScriptDeclaration($js);
		$doc->addStyleSheet(JUri::root(true) . '/modules/mod_sliderck/elements/ckfolder.css');
		$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/jquery-ui.min.js');
		$doc->addScript(JUri::root(true) . '/modules/mod_sliderck/elements/ckfolder.js');

//		JHtml::_('jquery.ui', array('sortable'));
		parent::__construct($form);
	}

	protected function getInput() {
		$doc = JFactory::getDocument();
		$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/ckbox.css');
		$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/ckbox.js');
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

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
		$html = $icon ? '<div style="display:inline-block;vertical-align:top;margin-top:4px;width:20px;"><img src="' . SLIDERCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div style="display:inline-block;width:20px;"></div>';
		$html .= '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $styles . '/>';
		if ($suffix)
			$html .= '<span style="display:inline-block;line-height:25px;">' . $suffix . '</span>';
		$html .= '<div class="btn" onclick="CKBox.open({url: \'index.php?option=com_sliderck&view=browse&type=folder&id=' . $this->id . '&tmpl=component\'})">' . JText::_('MOD_SLIDERCK_SELECT') . '</div>';
		$html .= '<p></p><p>' . JText::_('MOD_SLIDERCK_EDIT_LABELS_DESC') . '</p>';
		$html .= '<p><div class="btn" onclick="ckEditLabelsFile()">' . JText::_('MOD_SLIDERCK_EDIT_LABELS') . '</div></p>';
		$html .= '<h3>' . JText::_('MOD_SLIDERCK_SLIDES_PREVIEW') . '</h3>';
		$html .= '<div id="' . $this->id . 'imagespreview"></div>';
		$html .= '<div id="' . $this->id . 'labelseditor" ></div>';
		return $html;
	}
}
