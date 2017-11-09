<?php

$this->data['header'] = 'Set up 2-step verification';

$this->includeAtTemplateBase('includes/header.php');

?>
<p>
  Your account requires additional security. 
  You must set up 2-step verification at this time.
</p>
<form action="<?= htmlentities($this->data['formTarget']); ?>">
  
    <?php foreach ($this->data['formData'] as $name => $value): ?>
        <input type="hidden"
               name="<?= htmlentities($name); ?>"
               value="<?= htmlentities($value); ?>" />
    <?php endforeach; ?>
    
    <button type="submit" id="setUpMfa" name="setUpMfa"
            style="padding: 4px 8px;">
        Set up 2-step verification
    </button>
</form>
<?php

$this->includeAtTemplateBase('includes/footer.php');
