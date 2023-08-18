<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;


// set variables
define('SLIDERCK_PLATFORM', 'joomla');
define('SLIDERCK_PATH', JPATH_SITE . '/administrator/components/com_sliderck');
define('SLIDERCK_ADMIN_PATH', SLIDERCK_PATH);
define('SLIDERCK_FRONT_PATH', JPATH_SITE . '/components/com_sliderck');
define('SLIDERCK_PROJECTS_PATH', JPATH_SITE . '/administrator/components/com_sliderck/projects');
define('SLIDERCK_ADMIN_URL', JUri::root(true) . '/administrator/index.php?option=com_sliderck');
define('SLIDERCK_URL', JUri::base(true) . '/index.php?option=com_sliderck');
define('SLIDERCK_ADMIN_GENERAL_URL', JUri::root(true) . '/administrator/index.php?option=com_sliderck&view=templates');
define('SLIDERCK_MEDIA_URI', JUri::root(true) . '/media/com_sliderck');
define('SLIDERCK_MEDIA_URL', SLIDERCK_MEDIA_URI);
define('SLIDERCK_MEDIA_PATH', JPATH_ROOT . '/media/com_sliderck');
define('SLIDERCK_PLUGIN_URL', SLIDERCK_MEDIA_URI);
define('SLIDERCK_TEMPLATES_PATH', JPATH_SITE . '/templates');
define('SLIDERCK_SITE_ROOT', JPATH_ROOT);
define('SLIDERCK_URI', JUri::root(true) . '/administrator/components/com_sliderck');
define('SLIDERCK_URI_ROOT', JUri::root(true));
define('SLIDERCK_URI_BASE', JUri::base(true));
define('SLIDERCK_PLUGINS_PATH', JPATH_SITE . '/plugins/sliderck');

// include the classes
require_once SLIDERCK_PATH . '/helpers/ckinput.php';
require_once SLIDERCK_PATH . '/helpers/cktext.php';
require_once SLIDERCK_PATH . '/helpers/ckfile.php';
require_once SLIDERCK_PATH . '/helpers/ckfolder.php';
require_once SLIDERCK_PATH . '/helpers/ckfof.php';

