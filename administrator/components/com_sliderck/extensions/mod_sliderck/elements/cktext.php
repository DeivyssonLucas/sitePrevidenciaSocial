<?php
/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('JPATH_PLATFORM') or die;

if (!defined('SLIDERCK_MEDIA_URI'))
{
	define('SLIDERCK_MEDIA_URI', JUri::root(true) . '/media/com_sliderck');
}
/**
 * Form Field class for the Joomla Platform.
 * Supports a one line text field.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since       11.1
 */
class JFormFieldCktext extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'Cktext';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
        $icon = $this->element['icon'];
        $suffix = $this->element['suffix'];
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
        $defautlwidth = $suffix ? '128px' : '150px';
        $styles = ( version_compare(JVERSION, '3.0.0') > 0 ) ? ' style="float:none;display:inline-block;width:'.$defautlwidth.';'.$this->element['styles'].'"' : ' style="float:none;display:inline-block;border-radius:3px;padding:1px;width:'.$defautlwidth.';'.$this->element['styles'].'"';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
		$margintop = ( version_compare(JVERSION, '3.0.0') > 0 ) ? 'margin-top:4px;' : 'margin-top:1px;';
        $html = $icon ? '<div style="display:inline-block;vertical-align:top;'.$margintop.'width:20px;"><img src="' . SLIDERCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div style="display:inline-block;width:20px;"></div>';
        $html .= '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $styles . '/>';
        if ($suffix)
            $html .= '<span style="display:inline-block;line-height:25px;">' . $suffix . '</span>';
		return $html;
	}

    /**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   11.1
	 */
	protected function getLabel()
	{
		$label = '';

		if ($this->hidden)
		{
			return $label;
		}

		// Get the label text from the XML element, defaulting to the element name.
		$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
		$text = $this->translateLabel ? JText::_($text) : $text;

		// Build the class for the label.
		$class = !empty($this->description) ? 'hasTooltip' : '';
		$class = $this->required == true ? $class . ' required' : $class;
		$class = !empty($this->labelClass) ? $class . ' ' . $this->labelClass : $class;

		// Add the opening label tag and main attributes attributes.
		$label .= '<label id="' . $this->id . '-lbl" for="' . $this->id . '" class="' . $class . '"';

		// If a description is specified, use it to build a tooltip.
		// If a description is specified, use it to build a tooltip.
		if (!empty($this->description)) {
			$label .= ' title="<strong>'
					. htmlspecialchars(
							trim($text, ':') . '</strong><br />' . ($this->translateDescription ? JText::_($this->description) : $this->description), ENT_COMPAT, 'UTF-8'
					) . '"';
		}
        $width = $this->element['labelwidth'] ? $this->element['labelwidth'] : '150px';
        $styles = ' style="min-width:'.$width.';max-width:'.$width.';width:'.$width.';"';
		// Add the label text and closing tag.
		if ($this->required)
		{
			$label .= $styles.'>' . $text . '<span class="star">&#160;*</span></label>';
		}
		else
		{
			$label .= $styles.'>' . $text . '</label>';
		}

		return $label;
	}
}
