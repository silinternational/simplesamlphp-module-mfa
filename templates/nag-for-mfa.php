<?php
$this->data['header'] = 'Set up 2-Step Verification';
$this->includeAtTemplateBase('includes/header.php');

?>
    <p>
        Did you know you could greatly increase the security of your account by enabling 2-Step Verification?
    </p>
    <p>
        We highly encourage you to do this for your own safety
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

        <button type="submit" id="continue" name="continue"
                style="padding: 4px 8px;">
            Remind me later
        </button>
    </form>
<?php

$this->includeAtTemplateBase('includes/footer.php');
