<?php

$this->data['header'] = '2-step verification';
$this->includeAtTemplateBase('includes/header.php');

?>
<form action="<?= htmlentities($this->data['formTarget']); ?>">
  
    <?php foreach ($this->data['formData'] as $name => $value): ?>
        <input type="hidden"
               name="<?= htmlentities($name); ?>"
               value="<?= htmlentities($value); ?>" />
    <?php endforeach; ?>
    
    <p>Please insert your security key and press its button.</p>
    <p>
        <input type="hidden" id="mfaSubmission" name="mfaSubmission" />
        <button type="submit" name="submitMfa" id="submitMfa"
                style="padding: 4px 8px;">Submit</button>
    </p>
</form>
<?php

$this->includeAtTemplateBase('includes/footer.php');
