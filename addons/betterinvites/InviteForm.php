<?php
/**
 * Invite friends add-on
 *
 * @package SocialStrap add-on
 * @author Milos Stojanovic
 * @copyright 2014 interactive32.com
 */
class Addon_Form_BetterInvite extends Zend_Form
{

	/**
	 *
	 * Invite friends form
	 *
	 */
	public function init()
	{
		$translator = $this->getTranslator();

		// hidden form identifier
		$identifier = new Zend_Form_Element_Hidden('identifier');
		$identifier
		->setDecorators(array('ViewHelper'))
		->setValue('Invite');

		// use template file
		$this->setDecorators( array(
				array('ViewScript', array('viewScript' => 'InviteForm.phtml'))));

		$this->setName('Invite');
		$this->setMethod('post');
		$this->setAction('');

		// fields
		$email = new Zend_Form_Element_Text('email');
		$email
		->setDecorators(array('ViewHelper', 'Errors'))
		->addFilter('StringToLower')
		->setRequired(true)
		->addValidator('EmailAddress', true)
		->setLabel($translator->translate('Email:'))
		->setAttrib('class', 'form-control')
		->setErrorMessages(array($translator->translate('Enter a valid email address')));

		$submit = new Zend_Form_Element_Submit('submitform');
		$submit
		->setDecorators(array('ViewHelper'))
		->setLabel($translator->translate('Send Invitation'))
		->setAttrib('class', 'submit btn btn-default');

		// add elements
		$this->addElements(array($identifier, $email, $submit));
	}

}

