<?php
/**
 * @name		Slider CK
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Helper Class.
 */
class SliderckHelpersourceFolder {

	private static $params;

	private static $folderLabels = array();

	private static $imagesOrderByLabels = array();

	public static function getItems(&$params) {
		self::$params = $params;
		$authorisedExt = array('png', 'jpg', 'JPG', 'JPEG', 'jpeg', 'bmp', 'tiff', 'gif');
		$folder = trim($params->get('autoloadfoldername', $params->get('folder')), '/');

		if (file_exists($folder . '/labels.txt')) {
			$items = self::loadImagesFromFolder($folder);
		} else {
			$items = \Sliderck\CKFolder::files(JPATH_ROOT . '/' . $folder, '\.(?:png|jpe?g|bmp|tiff|PNG|JPE?G|BMP|TIFF?)$', false, false);

			foreach ($items as $i => $name) {
				$item = SliderckHelper::initItem();
				$item->image = trim(str_replace('\\','/',$name), '/');
				$item->image = JUri::root(true) . '/' . $folder . '/' . trim($item->image, '\\');

				if (!in_array(strToLower(\Sliderck\CKFile::getExt($item->image)), $authorisedExt))
					continue;

				// load the image data from txt
				$item = self::getImageDataFromfolderLegacy($item, $params);
				$items[$i] = $item;

				// route the url
				if (strcasecmp(substr($item->link, 0, 4), 'http') && (strpos($item->link, 'index.php?') !== false)) {
					$item->link = JRoute::_($item->link, true, false);
				} else {
					$item->link = JRoute::_($item->link);
				}
			}
		}
		return $items;
	}

	/**
	 * Old method that looks for a txt file that has the same name as the image
	 * @param type $item
	 * @param type $params
	 * @return type
	 */
	static function getImageDataFromfolderLegacy(&$item, $params) {
		// load the image data from txt
		$datafile = JPATH_ROOT . '/' . str_replace(JFile::getExt($item->image), 'txt', $item->image);
		$data = file_exists($datafile) ? file_get_contents($datafile) : '';
		$imgdatatmp = explode("\n", $data);

		$parmsnumb = count($imgdatatmp);
		for ($i = 0; $i < $parmsnumb; $i++) {
			$imgdatatmp[$i] = trim($imgdatatmp[$i]);
			$item->text = stristr($imgdatatmp[$i], "caption=") ? str_replace('caption=', '', $imgdatatmp[$i]) : $item->text;
			$item->articleid = stristr($imgdatatmp[$i], "articleid=") ? str_replace('articleid=', '', $imgdatatmp[$i]) : $item->articleid;
			$item->video = stristr($imgdatatmp[$i], "video=") ? str_replace('video=', '', $imgdatatmp[$i]) : $item->video;
			$item->link = stristr($imgdatatmp[$i], "link=") ? str_replace('link=', '', $imgdatatmp[$i]) : $item->link;
			$item->time = stristr($imgdatatmp[$i], "time=") ? str_replace('time=', '', $imgdatatmp[$i]) : $item->time;
			$item->target = stristr($imgdatatmp[$i], "target=") ? str_replace('target=', '', $imgdatatmp[$i]) : $item->target;
		}

		if ($item->video)
			$item->slideselect = 'video';
		
		// manage the title and description
		if (stristr($item->text, "||")) {
			$splitcaption = explode("||", $item->text);
			$item->text = '<div class="sliderck_title">' . $splitcaption[0] . '</div><div class="sliderck_description">' . $splitcaption[1] . '</div>';
		}

		if (isset($item->articleid) && $item->articleid) {
			$item = SliderckHelper::getArticle($item, $params);
		}

		return $item;
	}

	private static function getImageLabelsFromFolder($directory) {
		$dirindex = SliderckHelper::cleanName($directory);
		if (! empty(self::$folderLabels[$dirindex])) return;

		$items = array();
		$item = new stdClass();

		// get the language
		$lang = JFactory::getLanguage();
		$langtag = $lang->getTag(); // returns fr-FR or en-GB

		// load the image data from txt
		if (file_exists(JPATH_ROOT . '/' . $directory . '/labels.' . $langtag . '.txt')) {
			$data = file_get_contents(JPATH_ROOT . '/' . $directory . '/labels.' . $langtag . '.txt');
		} else if (file_exists(JPATH_ROOT . '/' . $directory . '/labels.txt')) {
			$data = file_get_contents(JPATH_ROOT . '/' . $directory . '/labels.txt');
		} else {
			return null;
		}

		$doUTF8encode = true;
		// remove UTF-8 BOM and normalize line endings
		if (!strcmp("\xEF\xBB\xBF", substr($data,0,3))) {  // file starts with UTF-8 BOM
			$data = substr($data, 3);  // remove UTF-8 BOM
			$doUTF8encode = false;
		}
		$data = str_replace("\r", "\n", $data);  // normalize line endings

		// if no data found, exit
		if(! $data) return null;

		// explode the file into rows
		// $imgdatatmp = explode("\n", $data);
		$imgdatatmp = preg_split("/\r\n|\n|\r/", $data, -1, PREG_SPLIT_NO_EMPTY);

		$parmsnumb = count($imgdatatmp);
		for ($i = 0; $i < $parmsnumb; $i++) {
			$imgdatatmp[$i] = trim($imgdatatmp[$i]);
			$line = explode('|', $imgdatatmp[$i]);

			// store the order or files from the TXT file
			self::$imagesOrderByLabels[] = $line[0];

			$item = SliderckHelper::initItem();
			$item->index = SliderckHelper::cleanName($line[0]);
			$item->title = (isset($line[1])) ? ( $doUTF8encode ? (utf8_encode($line[1])) : ($line[1]) ) : '';
			$item->text = (isset($line[2])) ? ( $doUTF8encode ? (utf8_encode($line[2])) : ($line[2]) ) : '';
			$item->link = (isset($line[3])) ? ( $doUTF8encode ? (utf8_encode($line[3])) : ($line[3]) ) : '';
			$item->video = (isset($line[4])) ? ( $doUTF8encode ? (utf8_encode($line[4])) : ($line[4]) ) : '';
			$item->articleid = (isset($line[5])) ? $line[5] : '';
			if ($item->articleid) $item = SliderckHelper::getArticle($item);

			$items[$item->index] = $item;
		}

		self::$folderLabels[$dirindex] = $items;
	}

	/*
	 * Load the data for the image (title and description)
	 */
	private static function getImageDataFromfolder($file, $directory) {
		$filename = explode('/', $file);
		$filename = end($filename);
		$dirindex = SliderckHelper::cleanName($directory);
		$fileindex = SliderckHelper::cleanName($filename);

		if (! empty(self::$folderLabels[$dirindex]) && ! empty(self::$folderLabels[$dirindex][$fileindex])) {
			$item = self::$folderLabels[$dirindex][$fileindex];
		} else {
			$item = SliderckHelper::initItem();
		}

		return $item;
	}
	

	/*
	 * Load the image from the specified folder 
	 */
	public static function loadImagesFromFolder($directory) {

		// encode the folder path, needed if contains an accent
		try {
			$translatedDirectory = iconv("UTF-8", "ISO-8859-1//TRANSLIT", urldecode($directory));
			if ($translatedDirectory) $directory = $translatedDirectory;
		} catch (Exception $e) {
			echo 'CK Message : ',  $e->getMessage(), "\n";
		}

		// load the files from the folder
		$files = \Sliderck\CKFolder::files(trim(trim($directory), '/'), '.', false, true);

		if (! $files) return 'CK message : No files found in the directory : ' . $directory;

		self::$imagesOrderByLabels = array();
		// load the labels from the folder
		self::getImageLabelsFromFolder($directory);

		$order = self::$params->get('displayorder');
		// set the images order
		if ($order == 'shuffle') {
			shuffle($files);
		} else {
			natsort($files);
			$files = array_map(array('SliderckHelper', 'formatPath'), $files);
			$baseDir = SliderckHelper::formatPath($directory);
			$labelsOrder = array_reverse(self::$imagesOrderByLabels);
			foreach ($labelsOrder as $name) {
				$imgFile = $baseDir . '/' . $name;
				array_unshift($files, $imgFile);
			}
			// now make it unique
			$files = array_unique($files);
		}

		$authorisedExt = array('png','jpg','jpeg','bmp','tiff','gif');
		$items = array();
		$i = 0;
		foreach ($files as $file) {
			$fileExt = \Sliderck\CKFile::getExt($file);
			if (!in_array(strToLower($fileExt),$authorisedExt)) continue;

			$item = SliderckHelper::initItem();

			// get the data for the image
			$filedata = self::getImageDataFromfolder($file, $directory);

			$file = str_replace("\\", "/", utf8_encode($file));
			if (isset($filedata->link) && $filedata->link) {
				$item->link = $filedata->link;
			} else {
				$videoFile = str_replace($fileExt, 'mp4', $file);
				$hasVideo = file_exists($videoFile);
				$item->link = $hasVideo ? $videoFile : $file;
			}

			$item->image = JUri::base(true) . '/' . $file;
			$item->title = $filedata->title;
			$item->text = $filedata->text;
			$item->video = $filedata->video;

			$items[$i] = $item;
			$i++;
		}

		return $items;
	}

	/**
	 * Ajax method called from the module to list the images
	 */
	public static function loadLabelsFile() {
		$input	= \Sliderck\CKFof::getInput();
		$path = $input->get('path', '', 'string');

		// outputs the heading
		echo '<div id="labelseditor">';
		echo '<div class="ckheader">'
					. '<div class="col ckmove">' . JText::_('CK_ORDERING') . '</div>'
					. '<div class="col ckimage">' . JText::_('CK_IMAGE') . '</div>'
					. '<div class="col">' . JText::_('CK_TEXT') . '</div>'
					. '<div class="col">' . JText::_('CK_LINK') . '</div>'
				. '</div>';

		$params = new JRegistry();
		$params->set('autoloadfoldername', $path);
		$items = self::getItems($params);
		$images = Array();
		foreach ($items as $item) {
			$filename = explode('/', $item->image);
			$filename = end($filename);
			$images[] = $filename;
		}

		$i = 0;
		$labels = Array();
		if (file_exists(JPATH_SITE . '/' . $path . '/labels.txt')) {
			$labels = \Sliderck\CKFile::read(JPATH_SITE . '/' . $path . '/labels.txt');
			$lines = explode("\n", $labels);
			foreach ($lines as $line) {
				$line = trim($line);
				if (!$line) continue;
				$t = explode('|', $line);
				$image = isset($t[0]) ? $t[0] : '';
				if (! $image) continue;
				$title = isset($t[1]) ? $t[1] : '';
				$desc = isset($t[2]) ? $t[2] : '';
				$link = isset($t[3]) ? $t[3] : '';
				if (in_array($image, $images)) {
					$key = array_search($image, $images);
					unset($images[$key]);
					self::renderLabelEdition($path, $image, $i, $title, $desc, $link);
					$i++;
				}

				
			}
		}

		
		foreach ($images as $image) {
			self::renderLabelEdition($path, $image, $i, $title = '', $desc = '', $link = '');
			$i++;
		}
		echo '</div>';
	}

	/*
	 * Output the html code for the label edition
	 */
	private static function renderLabelEdition($path, $image, $i, $title, $desc, $link) {
		echo '<div class="ckrow">'
				. '<div class="col ckmove">&nbsp;</div>'
				. '<div class="col ckfile cktip" data-image="' . utf8_encode($image) . '" title="' . utf8_encode($image) . '" style="background-image: url(\'' . JUri::root(true) . '/' . $path . '/' . $image . '\');" ></div>'
				. '<div class="col">'
					.'<div><label style="display: inline-block;padding: 5px;min-width: 100px">' . JText::_('CK_TITLE') . '</label><input class="labeltitle" type="text" value="' . utf8_encode($title) . '" /></div>'
					. '<div><label style="display: inline-block;padding: 5px;min-width: 100px">' . JText::_('CK_DESCRIPTION') . '</label><input class="labeldesc" type="text" value="' . utf8_encode($desc) . '" /></div>'
				. '</div>'
				. '<div class="col input-append"><input id="labellink'.$i.'" class="labellink" type="text" value="' . utf8_encode($link) . '" /><button class="btn" onclick="CKBox.open({url: \''.JUri::root(true).'/administrator/index.php?option=com_sliderck&view=menus&tmpl=component&fieldid=labellink'.$i.'\', id:\'ckmenusmodal\', style: {padding: \'10px\'} })">' . JText::_('CK_SELECT') . '</button></div>'
			. '</div>';
	}

	/*
	 * Write the labels.txt file in the folder
	 */
	public static function writeLabelsFile() {
		$input	= \Sliderck\CKFof::getInput();
		$path = $input->get('path', '', 'string');
		$labels = utf8_decode($input->get('labels', '', 'html'));
		$labelsFile = JPATH_SITE . '/' . $path . '/labels.txt';

		echo \Sliderck\CKFile::write($labelsFile, $labels);
		exit();
	}

	public static function importFromFolder() {
		$input	= \Sliderck\CKFof::getInput();
		$folder = $input->get('folder', '', 'string');
		$imgdir = JPATH_ROOT . '/' . trim(trim($folder, "/"), "\\");
		$imgs = \Sliderck\CKFolder::files($imgdir, '\.(?:png|jpe?g|bmp|tiff?)$', false, true);
		$imgs = str_replace(JPATH_ROOT, "", $imgs);
		$imgs = str_replace("\\", "/", $imgs);
		foreach ($imgs as &$img) {
			$img = trim($img, "/");
		}
		$imgs = json_encode($imgs);

		echo $imgs;
		exit;
	}
}
