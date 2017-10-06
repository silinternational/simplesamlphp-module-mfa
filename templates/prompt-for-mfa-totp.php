<?php

$this->data['header'] = '2-step verification';
$this->includeAtTemplateBase('includes/header.php');

if ( ! empty($this->data['errorMessage'])) {
    ?>
    <p style="border: 3px solid red; padding: 5px;">
      <b>Oops!</b><br />
      <?= htmlentities($this->data['errorMessage']); ?>
    </p>
    <?php
}

?>
<form action="<?= htmlentities($this->data['formTarget']); ?>">
  
    <?php foreach ($this->data['formData'] as $name => $value): ?>
        <input type="hidden"
               name="<?= htmlentities($name); ?>"
               value="<?= htmlentities($value); ?>" />
    <?php endforeach; ?>
    
    <p>Please enter the 6-digit number from your app: </p>
    <p>
        <input type="text" autofocus id="mfaSubmission" name="mfaSubmission" />
        <button type="submit" id="submitMfa" name="submitMfa"
                style="padding: 4px 8px;">Submit</button>
    </p>
</form>
<?php

$this->includeAtTemplateBase('includes/footer.php');
