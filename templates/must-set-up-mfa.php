<?php
$this->data['header'] = 'Set up 2-Step Verification';
$this->includeAtTemplateBase('includes/header.php');

?>
<p>
  Your account requires additional security. 
  You must set up 2-step verification at this time.
</p>
<form method="post">
    <button name="setUpMfa" style="padding: 4px 8px;">
        Set up 2-step verification
    </button>
</form>
<?php
$this->includeAtTemplateBase('includes/footer.php');
