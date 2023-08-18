<?php
/**
 * @copyright	Copyright (C) 2019 Cédric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');
jimport('joomla.filesystem.folder');

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

class plgSystemSliderck extends JPlugin {

	private $shallLoad = true;

	private $modulesList;

	public $increment = 1;

	function __construct(&$subject, $params) {
		parent::__construct($subject, $params);
	}

	private function shallLoad() {
		if (! $this->shallLoad) {
			return false;
		}
		$app = JFactory::getApplication();
		if (! $app->isClient('site') && ! $app->isClient('administrator'))
		{
			return false;
		}
		if ($app->isClient('cli'))
		{
			return false;
		}
		if ($app->isClient('api'))
		{
			return false;
		}

		$doc = JFactory::getDocument();
		$doctype = $doc->getType();
		// $document = \Joomla\CMS\Factory::getApplication()->getDocument();

		if ($doctype !== 'html')
		{
			return false;
		}

		return true;
	}

	/* 
	 * Initiate the lugin load
	 *
	 * Return mixed
	 */
	function registerListeners() {
		if ($this->shallLoad === true) {
			parent::registerListeners();
		} else {
			return false;
		}
	}

	function onContentPrepareForm($form, $data) {
		if ($form->getName() != 'com_modules.module'
				&& $form->getName() != 'com_advancedmodules.module'
				&& $form->getName() != 'com_config.modules' // for frontend edition
				|| ($form->getName() == 'com_modules.module' && $data && $data->module != 'mod_sliderck')
				|| ($form->getName() == 'com_advancedmodules.module' && $data && $data->module != 'mod_sliderck')
				// for frontend edition
				|| ($form->getName() == 'com_config.modules' && $data && $data->module != 'mod_sliderck')
				)
			return;

		// check that we are editing the sliderck module
		$id = JFactory::getApplication()->input->get('id',0, 'int');
		if ($id) {
			$q = "SELECT module FROM #__modules WHERE id = " . $id;
			$db = JFactory::getDbo();
			$db->setQuery($q);
			$module = $db->loadResult();

			if ($module != 'mod_sliderck') return;
		}

		$this->loadLanguage();

		// module options
		if (
				$form->getName() == 'com_modules.module' || $form->getName() == 'com_advancedmodules.module'
				// for frontend edition
				|| $form->getName() == 'com_config.modules'
				) {
			

			// load the custom plugins
			require_once(JPATH_ADMINISTRATOR . '/components/com_sliderck/helpers/ckfof.php');
			Sliderck\CKFof::importPlugin('sliderck');
			$sources = Sliderck\CKFof::triggerEvent('onSliderckGetSourceName');

			if (! empty($sources)) {
				foreach ($sources as $source) {
					$this->loadLanguage();
					JForm::addFormPath(JPATH_SITE . '/plugins/sliderck/' . strtolower($source) . '/params');
					$form->loadFile(strtolower($source) . '_params', false);
				}
			}
		
		}
	}

	/**
	 * Render the tag to a module, uses the syntax : {slideshowck 207}folder=images/test|height=300{/slideshowck}
	 * 
	 */
	function onContentPrepare($context, &$article, &$params, $page = 0) {
		$app = JFactory::getApplication();
		// si pas en frontend, on sort
		if ($app->isClient('administrator')) {
			return false;
		}
		$document = JFactory::getDocument();
		$doctype = $document->getType();
		// si pas HTML, on sort
		if ($doctype !== 'html') {
			return;
		}
		$input = $app->input;
		if ($input->get('layout') == 'edit' || $input->get('task') == 'edit' || $input->get('func') == 'edit' || $input->get('controller') == 'config.display.modules') {
			return;
		}

		// get the page code
		// if (version_compare(JVERSION, '4') >= 0) {
			// $body = JFactory::getApplication()->getBody(); 
		// } else {
			// $body = JResponse::getBody();
		// }
		$body = $article->text;

		// look for the tags and replace
		$regex = "#{sliderck(.*?)}(.*?){/sliderck}#s"; // masque de recherche pour le tag
		$body = preg_replace_callback($regex, array($this, 'createModuleFromTag'), $body);

		// if (version_compare(JVERSION, '4') >= 0) {
			// JFactory::getApplication()->setBody($body); 
		// } else {
			// JResponse::setBody($body);
		// }
		$article->text = $body;

		return;
	}

	private function createModuleFromTag($matches) {
		// dÃ©coupe l'expression pour rÃ©cupÃ©rer les textes
		$patterns = "#{sliderck(.*)}(.*){/sliderck}#Uis";
		$result = preg_match($patterns, $matches[0], $results);

		// vÃ©rifie si des paramÃ¨tres personnalisÃ©s existent
		$params = new stdClass();

		$paramstmp = explode('|', $results[2]);
		for ($i = 0; $i < count($paramstmp); $i++) {
			$param = explode('=', $paramstmp[$i]);
			if (isset($param[0]) && isset($param[1])) {
				$key = $param[0];
				// concat the other values, in case of the = sign is present
				unset($param[0]);
				$value = implode('=', $param);

				$params->{$key} = $value;
			}
		}

		// make the translation between the user param and the module param
		$this->associateParams($params);

		$module = $this->GenModuleById((int)$results[1], $params);

		return $module;
	}

	/**
	 * Make the translation between the user param and the module param
	 *
	 * @return void
	 */
	private function associateParams(&$params) {
		$associations = array('folder' => 'autoloadfoldername');
		foreach ($associations as $tag => $module) {
			if (isset($params->{$tag})) {
				$params->{$module} = $params->{$tag};
			}
		}
	}

	/**
	 * Create the list of all modules published as Object
	 *
	 * @return Array of Objects
	 */
	private function CreateModulesList() {
		if (empty($this->modulesList)) {
			$db = JFactory::getDbo();
			$query = "
				SELECT *
				FROM #__modules
				WHERE published=1
				ORDER BY id
				";
			$db->setQuery($query);
			$this->modulesList = $db->loadObjectList('id');
		}
		return $this->modulesList;
	}

	/**
	 * Render the module
	 *
	 * @param Int $moduleid The module ID to load
	 * @param JRegistry $params
	 * @param Array $modulesList The list of all module objects published
	 *
	 * @return string with HTML
	 */
	private function GenModuleById($moduleid, $params) {
		$attribs['style'] = 'html5';
		$modulesList = $this->CreateModulesList();

		if (! isset($modulesList[$moduleid])) {
			return 'SLIDER CK MESSAGE : module ID ' . $moduleid . ' not found.';
		}
		$module = clone $modulesList[$moduleid];

		// set the module param to know the calling level
		$paramstmp = new \JRegistry;
		$paramstmp->loadString($module->params);
		// implement the custom params in the module
		foreach ($params as $param => $value) {
			$paramstmp->set($param, $value);
		}
		$module->id = $module->id . 'tag' . $this->increment;
		$module->params = $paramstmp->toString();
		$this->increment++;

		return JModuleHelper::renderModule($module, $attribs);
	}
}