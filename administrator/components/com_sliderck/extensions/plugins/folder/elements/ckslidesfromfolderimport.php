<?php

/**
 * @copyright	Copyright (C) 2013 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.folder');

class JFormFieldCkslidesfromfolderimport extends JFormField {

	protected $type = 'ckslidesfromfolderimport';

	protected function getInput() {
		$path = 'plugins/sliderck/folder/elements/ckslidesfromfoldermanager/';
		JHtml::_('script', $path.'ckslidesfromfoldermanager.js');
		JHtml::_('stylesheet', $path.'ckslidesfromfoldermanager.css');
		$this->value = $this->value ? $this->value : 'media/com_sliderck/images/slides';
		$imgdir = JPATH_ROOT . '/' . trim($this->value, "/");
		$imgs = JFolder::files($imgdir, '.jpg|.png|.jpeg|.gif|.JPG|.JPEG|.jpeg', false, true);
		$imgs = str_replace(JPATH_ROOT, "", $imgs);
		$imgs = str_replace("\\", "/", $imgs);
		foreach ($imgs as &$img) {
			$img = trim($img, "/");
		}
		$imgs = json_encode($imgs);
		$imgs = str_replace("\"", "|qq|", $imgs);

		$html = '';
		$icon = $this->element['icon'];
		$html .= $icon ? '<img src="' . JUri::root(true) . '/media/com_sliderck/images/' . $icon . '" style="margin-right:5px;" />' : '<div style="float:left;width:15px;margin-right:5px;">&nbsp;</div>';
		$html .= '<span class="ckbutton-group"><input name="' . $this->name . '" id="' . $this->id . '" type="text" value="' . $this->value . '" style=""/>'
				. '<div type="button" class="ckbutton" onclick="showfolderslistck();" style="min-width:80px;"><i class="fas fa-edit"></i> ' . JText::_('SLIDERCK_SELECT') . '</div>'
				. '<div type="button" class="ckbutton btn-primary" onclick="ckBeforeImportFromFolder(\'' . $imgs . '\');" style="min-width:80px;"><i class="fas fa-download"></i> ' . JText::_('SLIDERCK_FOLDER_IMPORT') . '</div></span>';

		$html .= '<div id="cklistfolders">';
		$html .= self::listFolders(JPATH_ROOT . '/images', '.', $level = 1);
		$html .= '</div>';
		$html .= '<div id="ckfolderimportpopup" style="display:none;">
				<h1>
					' . JText::_('SLIDERCK_FOLDER_IMPORT') . '
				</h1>
				<div style="border-top: 1px solid #ddd;">
					<br />
					<p>' . JText::_('SLIDERCK_FOLDER_IMPORT_HOW_TO') . '</p>
					<br />
					<div class="cktoolbar" class="clearfix" style="text-align:center;">
						<span class="ckbutton ckaction" onclick="ckImportFromFolder(true)">' . JText::_('SLIDERCK_FOLDER_IMPORT_REPLACE') . '</span>
						<span class="ckbutton ckaction" onclick="ckImportFromFolder()">' . JText::_('SLIDERCK_FOLDER_IMPORT_ADD') . '</span>
					</div>
				</div>
				</div>';

		return $html;
	}
	
	protected function listFolders($path, $filter, $level = 1) {
		$fhtml = '';
		$folders = JFolder::folders($path, $filter);
		if (! count($folders)) return;
		if ($level > 1) {
			$labelfor = explode('/', str_replace('\\', '', str_replace('/', '', str_replace(JPATH_ROOT, '', $path))));
			$labelfor = end($labelfor) . '_level' . $level;
			$labelfor = preg_replace('/[^a-zA-Z0-9]/', "_", $labelfor);
			$fhtml .=  '<input type="checkbox" style="display: none;" name="' . $labelfor . '" id="' . $labelfor . '"/>';
			$fhtml .=  '<label class="cksubfoldertoggler" for="' . $labelfor . '">+</label>';
			$fhtml .=  '<div class="cksubfolder">';
		}
		foreach ($folders as $name)
		{
			$fullname = JPath::clean($path . '/' . $name);
			$relname = str_replace(JPATH_ROOT, '', $fullname);

			$fhtml .= '<div class="ckfolderitem"><span style="display:inline-block;padding-left:' . ($level*20) . 'px"><img src="' . JUri::root(true) . '/media/com_sliderck/images/folder.png" width="16" height="16" /></span>'
				.'<span class="ckfoldername" data-foldername="' . $relname . '" onclick="selectfolderck(this, \'' . $this->id . '\')">' . $name . '</span></div>';
			$fhtml .= self::listFolders($fullname, $filter, $level + 1);
		}
		if ($level > 1) {
			$fhtml .=  '</div>';
		}

		return $fhtml;
	}
}

