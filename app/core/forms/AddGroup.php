<?php
/**
 * Form
 *
 * @package SocialStrap
 * @author Milos Stojanovic
 * @copyright 2013 interactive32.com
 */

class Application_Form_AddGroup extends Application_Form_Main
{

	/**
	 *
	 * Add a Group
	 *
	 */
	public function init()
	{
		$cname = explode('_', get_class()); $this->preInit(end($cname));
		
		// use template file
		$this->setDecorators( array(
				array('ViewScript', array('viewScript' => 'forms/AddGroup.phtml'))));

		$username_minchars = Zend_Registry::get('config')->get('username_minchars');
		$username_maxchars = Zend_Registry::get('config')->get('username_maxchars');

		// fields

		// lowercase, alnum without whitespaces
		$name = new Zend_Form_Element_Text('name');
		$name
		->setDecorators(array('ViewHelper', 'Errors'))
		->setRequired(true)
		->addFilter('StringToLower')
		->addValidator('alnum', false, array('allowWhiteSpace' => false))
		->addValidator('stringLength', false, array($username_minchars, $username_maxchars))
		->setErrorMessages(array(sprintf($this->translator->translate('Please choose a valid username between %d and %d characters'), $username_minchars, $username_maxchars)))
		->setLabel($this->translator->translate('Username'))
		->setAttrib('class', 'form-control alnum-only');

		$screenname = new Zend_Form_Element_Text('screen_name');
		$screenname
		->setDecorators(array('ViewHelper', 'Errors'))
		->addFilter('StringTrim')
		->addValidator('alnum', false, array('allowWhiteSpace' => true))
		->addValidator('stringLength', false, array($username_minchars, $username_maxchars))
		->setErrorMessages(array(sprintf($this->translator->translate('Please choose a valid name between %d and %d characters'), $username_minchars, $username_maxchars)))
		->setLabel($this->translator->translate('Screen Name'))
		->setRequired(true)
		->setAttrib('class', 'form-control');

		$description = new Zend_Form_Element_Textarea('description');
		$description
		->setDecorators(array('ViewHelper', 'Errors'))
		->setAttrib('COLS', '')
		->setAttrib('ROWS', '4')
		->addFilter('StripTags')
		->setLabel($this->translator->translate('About this group'))
		->setAttrib('class', 'form-control');

		$profile_privacy = new Zend_Form_Element_Select('profile_privacy');
		$profile_privacy
		->setDecorators(array('ViewHelper', 'Errors'))
		->setMultiOptions(Zend_Registry::get('group_privacy_array'))
		->setErrorMessages(array($this->translator->translate('Select group visibility')))
		->setLabel($this->translator->translate('Select group visibility'))
		->setRequired(true)
		->setAttrib('class', 'form-control');

		$submit = new Zend_Form_Element_Submit('formsubmit');
		$submit
		->setDecorators(array('ViewHelper'))
		->setLabel($this->translator->translate('Save'))
		->setAttrib('class', 'submit btn btn-default');

		$this->addElements(array(
				$screenname,
				$name,
				$profile_privacy,
				$description,
				$submit));

		$this->postInit();
	}
}
