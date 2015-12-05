<?php
/**
 * Better invites add-on
 *
 * @package SocialStrap add-on
 * @author Milos Stojanovic
 * @copyright 2014 interactive32.com
 */


// do now load for guests
if (!Zend_Auth::getInstance()->hasIdentity()) return;

$front = Zend_Controller_Front::getInstance();
$request = $front->getRequest();
$controller = $request->getControllerName();
$action = $request->getActionName();

// load on home and profile pages only
if ($controller !== 'index' && $controller != 'profiles') return;


// attach to sidebar hook
$this->attach('hook_view_sidebar', 100, function($view) {

	$view->addScriptPath(realpath(dirname(__FILE__)));
	$form = getBetterInvitaionForm();
	
	echo '
	<div class="well sidebarbox">
		<div class="sb-title">
			<span>' . $view->translate('Invite Friends') . '</span>
		</div>
		' . $form . '
	</div>
	';
	
});


/**
 *
 * Load & submit invitation form
 *
*/
function getBetterInvitaionForm()
{		
	require_once 'InviteForm.php';
	
	$form = new Addon_Form_BetterInvite();
	$translator = Zend_Registry::get('Zend_Translate');

	// form is submitted and valid?
	if(isset($_POST['identifier']) && $_POST['identifier'] == 'Invite')
	{
		if($form->isValid($_POST))
		{
			$to = $form->getValue('email');
			$subject = $translator->translate('Invitation');
		
			$base_url = Application_Plugin_Common::getFullBaseUrl();
			
			$user_id = Zend_Auth::getInstance()->getIdentity()->id;
			$user_name = Zend_Auth::getInstance()->getIdentity()->name;
			$user_screenname = Zend_Auth::getInstance()->getIdentity()->screen_name;
			$invitation_link = $base_url.'/?ref='.$user_id;
			$profile_link = $base_url.'/'.$user_name.'/?ref='.$user_id;
		
			// prepare phtml email template
			$view = new Zend_View();
			$view->setScriptPath(realpath(dirname(__FILE__)));
			$view->assign('invitation_link', $invitation_link);
			$body = $view->render('email.phtml');
			
			$body = str_replace("NETWORK_NAME", Zend_Registry::get('config')->get('network_name'), $body);
			$body = str_replace("INVITATION_LINK", $invitation_link, $body);
			$body = str_replace("INVITED_BY_SCREENNAME", $user_screenname, $body);
			$body = str_replace("INVITED_BY_PROFILE_LINK", $profile_link, $body);
		
			// send email
			$ret = Application_Plugin_Common::sendEmail($to, $subject, $body, true);
		
			// show info message
			if ($ret){
				Application_Plugin_Alerts::success(Zend_Registry::get('Zend_Translate')->translate('Invitation has been sent'), 'on');
			}
		
		}

		// flush field
		$form->getElement('email')->setValue('');	
	}

	return $form;
}