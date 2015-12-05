<?php
/**
 * Cool Comments add-on
 *
 * @package SocialStrap add-on
 * @author Milos Stojanovic
 * @copyright 2014 interactive32.com
 */

$this->attach('view_head', 20, function($view) {
	
	echo '
		<style type="text/css">
			#posts-wrapper textarea#comment {height: 35px;}
					
			@media (max-width: 979px) {
				.add-comment-submit-btn{
					display: inline!important;
					width: 35%;
				}
				
				.add-comment-input{
					width: 65%!important;
				}
		
				.add-comment-submit-btn{
					display: inline;
					width: 100%!important;
				}
		
				.add-comment-submit-btn input{
					float: right;
				}
		
				.add-comment-input{
					margin-bottom: 5px;
				}
				
				.add-comment-input{
					width: 100%!important;
				}
			}
		
			@media (max-width: 500px) {
		
				.add-comment-submit-btn input{
					height: 22px;
					padding: 1px 5px;
					font-size: 12px;
				}

			}
		</style>
		';
});


$this->attach('view_body', 20, function($view) {
	
	echo '<script type="text/javascript" src="'.$view->baseUrl().'/addons/'.basename(__DIR__).'/jquery.autosize.min.js"></script>';
	echo '		
		<script type="text/javascript">
		
			// attach to postLoaded
			$(document).on("postsLoaded", function (e) {
				$(".add-comment textarea#comment").trigger("autosize.destroy").autosize({append:""});
			});
		
			// focus
			$(document).on("click", ".add-comment-btn", function (e) {
				 $(this).closest(".single-post").find(".add-comment textarea#comment").focus();
			});
		
		</script>
		';
});

// Override default AddComent Form
$this->attach('form_AddComment', 10, function(&$form) {

	$translator = Zend_Registry::get('Zend_Translate');
	
	// remove input element
	$form->removeElement('comment');

	// add new textarea element
	$comment = new Zend_Form_Element_Textarea('comment');
	$comment
	->setDecorators(array('ViewHelper', 'Errors'))
	->addFilter('StripTags')
	->setRequired(true)
	->setAttrib('autocomplete', 'off')
	->setAttrib('class', 'form-control')
	->setAttrib('cols', '40')
	->setAttrib('rows', '1')
	->setAttrib('placeholder', $translator->translate('Write a comment...'));
	
	$form->addElement($comment);
	
	return;
});
	