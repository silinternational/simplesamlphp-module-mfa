<?php

$this->data['header'] = 'Set up 2-step verification';

$this->includeAtTemplateBase('includes/header.php');

$learnMoreUrl = $this->data['learnMoreUrl'];
?>
<p>
  It is time to set up 2-step verification for your account.
</p>
<p>
  You will need to do this before you can continue to where you were going.
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
        
    <?php if (! empty($learnMoreUrl)): ?>
        <p><a href="<?= htmlentities($learnMoreUrl) ?>"
              target="_blank">Learn more</a></p>
    <?php endif; ?>
</form>
<?php

$this->includeAtTemplateBase('includes/footer.php');
