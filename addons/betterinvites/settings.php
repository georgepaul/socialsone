<?php 
// double-check if admin
if (! defined('PUBLIC_PATH') || ! Zend_Auth::getInstance()->hasIdentity() || Zend_Auth::getInstance()->getIdentity()->role != 'admin') die('not allowed');
?>


<?php 
$fn = realpath(dirname(__FILE__)) . "/email.phtml"; 

if (isset($_POST['customemail'])) {
	@file_put_contents($fn, $_POST['customemail']);
} 

$text = htmlspecialchars_decode(@file_get_contents($fn));

?> 


<div class="well">

<?php if (! is_writable($fn)) echo '<p>Error: file not writtable: <br />' .$fn. '<hr /></p>';?>

<p>
<strong>Available tags:</strong><br />
NETWORK_NAME<br />
INVITATION_LINK<br />
INVITED_BY_SCREENNAME<br />
INVITED_BY_PROFILE_LINK
</p>


<form action="" method="post"> 
<div class="form-group">
	<label for="motd">Invitation email html:</label>
	<textarea id="customemail" name="customemail" cols="" rows="12" class="form-control"><?php echo $text?></textarea><br/> 
</div>

<div class="pull-right">
<input type="submit" name="submitbtn" id="submitbtn" value="Update" class="submit btn btn-default">
</div>

</form>

</div>


