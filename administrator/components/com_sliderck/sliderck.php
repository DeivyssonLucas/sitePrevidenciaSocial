<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */


// no direct access
defined('_JEXEC') or die;
if (! defined('CK_LOADED')) define('CK_LOADED', 1);

require_once JPATH_ADMINISTRATOR . '/components/com_sliderck/helpers/defines.php';

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_sliderck')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// loads the language files from the frontend
$lang	= JFactory::getLanguage();
$lang->load('com_sliderck', JPATH_SITE . '/components/com_sliderck', $lang->getTag(), false);
$lang->load('com_sliderck', JPATH_SITE);

// loads the helper in any case
require_once SLIDERCK_PATH . '/helpers/cktext.php';
require_once SLIDERCK_PATH . '/helpers/ckpath.php';
require_once SLIDERCK_PATH . '/helpers/ckfile.php';
require_once SLIDERCK_PATH . '/helpers/ckfolder.php';
require_once SLIDERCK_PATH . '/helpers/ckuri.php';
require_once SLIDERCK_PATH . '/helpers/ckfof.php';
require_once SLIDERCK_PATH . '/helpers/helper.php';
require_once SLIDERCK_PATH . '/helpers/ckframework.php';
require_once SLIDERCK_PATH . '/helpers/ckcontroller.php';
require_once SLIDERCK_PATH . '/helpers/ckmodel.php';
require_once SLIDERCK_PATH . '/helpers/ckview.php';

\Sliderck\CKFramework::load();

// Include dependancies
require_once SLIDERCK_PATH . '/controller.php';

$controller	= \Sliderck\CKController::getInstance('Sliderck');
$controller->execute(JFactory::getApplication()->input->get('task'));
