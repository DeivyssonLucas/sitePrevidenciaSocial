<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
 
defined('_JEXEC') or die('Restricted access');
/*
	preflight which is executed before install and update
	install
	update
	uninstall
	postflight which is executed after install and update
	*/

class com_sliderckInstallerScript {

	private $doInstallFolderPlugin = false;

	function install($parent) {
		
	}
	
	function update($parent) {
		
	}

	function uninstall($parent) {
		// jimport('joomla.installer.installer');
		// Latest Module
		// Check first that the module exist
		/*$db->setQuery('SELECT `extension_id` FROM #__extensions WHERE `element` = "mod_test" AND `type` = "module"');
		$id = $db->loadResult();
		if($id)
		{
			$installer = new JInstaller;
			$result = $installer->uninstall('module',$id,1);
		}*/

		// disable all plugins and modules
		$db = JFactory::getDbo();
		$db->setQuery("UPDATE `#__modules` SET `published` = 0 WHERE `module` LIKE '%sliderck%'");
		$db->execute();

		$db->setQuery("UPDATE `#__extensions` SET `enabled` = 0 WHERE `type` = 'plugin' AND `element` LIKE '%sliderck%' AND `folder` NOT LIKE '%sliderck%'");
		$db->execute();
		return true;
	}

	function preflight($type, $parent) {
		// check if a pro version already installed
		$xmlPath = JPATH_ROOT . '/administrator/components/com_sliderck/sliderck.xml';

		// if no file already exists
		if (! file_exists($xmlPath)) return true;

		$xmlData = $this->getXmlData($xmlPath);
		$isProInstalled = ((int)$xmlData->ckpro);

		if ($isProInstalled) {
			throw new RuntimeException('Slider CK Light cannot be installed over Slider CK Pro. Please install Slider CK Pro. To downgrade, please first uninstall Slider CK Pro. <a href="https://forum.joomlack.fr/index.php/slider-ck/17181-how-to-update-slider-ck-v1-to-v2" target="_blank">Read more</a>');
			// return false;
		}

		// check if a V1 version is installed with the params (needs the pro)
		$xmlPath = JPATH_ROOT . '/modules/mod_sliderck/mod_sliderck.xml';

		// if no file already exists
		if (! file_exists($xmlPath)) return true;

		$xmlData = $this->getXmlData($xmlPath);
		$installedVersion = ((int)$xmlData->version );
		// if the installed version is the V1
		if(version_compare($installedVersion, '2.0.0', '<')) {
			// if the params is also installed
			if (file_exists(JPATH_ROOT . '/plugins/system/sliderckparams/sliderckparams.xml')) {
				throw new RuntimeException('Slider CK Light cannot be installed over Slider CK V1 + Params. Please install Slider CK Pro to get the same features as previously, else you may loose your existing settings. To downgrade, please first uninstall Slider CK Params. <a href="https://forum.joomlack.fr/index.php/slider-ck/17181-how-to-update-slider-ck-v1-to-v2" target="_blank">Read more</a>');
				// return false;
			}

			// install over V1, but not over Params. Then install the folder plugin
			$this->doInstallFolderPlugin = true;
		}

		return true;
	}

	public function getXmlData($file) {
		if ( ! is_file($file))
		{
			return '';
		}

		$xml = simplexml_load_file($file);

		if ( ! $xml || ! isset($xml['version']))
		{
			return '';
		}

		return $xml;
	}

	// run on install and update
	function postflight($type, $parent) {
		// install modules and plugins
		jimport('joomla.installer.installer');
		$db = JFactory::getDbo();
		$status = array();
		$src_ext = dirname(__FILE__).'/administrator/extensions';
		$installer = new JInstaller;

		// module
		$result = $installer->install($src_ext.'/mod_sliderck');
		$status[] = array('name'=>'Slider CK - Module','type'=>'module', 'result'=>$result);

		// folder plugin, if coming from the V1
		if ($this->doInstallFolderPlugin) {
			// system plugin
			$result = $installer->install($src_ext.'/sliderck');
			$status[] = array('name'=>'System - Slider CK','type'=>'plugin', 'result'=>$result);
			// system plugin must be enabled for user group limits and private areas
			$db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'sliderck' AND `type` = 'plugin'");
			$db->execute();

			$plugins_src_ext = $src_ext . '/plugins';
			$plugin = 'folder';
			$result = $installer->install($plugins_src_ext . '/' . $plugin);
			$status[] = array('name'=>'Slider CK - ' . $plugin,'type'=>'plugin', 'result'=>$result);
			// auto enable the plugin
			$db->setQuery("UPDATE #__extensions SET enabled = '1', ordering = '1' WHERE `element` = '" . $plugin . "' AND `type` = 'plugin' AND `folder` = 'sliderck'");
			$db->execute();
		}

		foreach ($status as $statu) {
			if ($statu['result'] == true) {
				$alert = 'success';
				$icon = 'icon-ok';
				$text = 'Successful';
			} else {
				$alert = 'warning';
				$icon = 'icon-cancel';
				$text = 'Failed';
			}
			echo '<div class="alert alert-' . $alert . '"><i class="icon ' . $icon . '"></i>Installation and activation of the <b>' . $statu['type'] . ' ' . $statu['name'] . '</b> : ' . $text . '</div>';
		}

		return true;
	}
}
