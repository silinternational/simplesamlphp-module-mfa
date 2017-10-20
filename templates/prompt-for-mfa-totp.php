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
<form action="<?= htmlentities($this->data['formTarget']); ?>" method="POST">
  
    <?php foreach ($this->data['formData'] as $name => $value): ?>
        <input type="hidden"
               name="<?= htmlentities($name); ?>"
               value="<?= htmlentities($value); ?>" />
    <?php endforeach; ?>
    
    <p>Please enter the 6-digit number from your app: </p>
    <p>

        <input type="text" autofocus id="mfaSubmission" name="mfaSubmission" />
        <br />
        <input type="checkbox" name="rememberMe" id="rememberMe" value="true" checked="checked"/>
        <label for="rememberMe">Remember this computer for 30 days</label>
        <br />
        <button type="submit" id="submitMfa" name="submitMfa"
                style="padding: 4px 8px;">Submit</button>
    </p>
    <?php
    if (count($this->data['mfaOptions']) > 1) {
        ?>
        <p>
            Don't have your app handy? You may also use:
        <ul>
            <?php
            foreach ($this->data['mfaOptions'] as $mfaOpt) {
                if ($mfaOpt['type'] != 'totp'){
                    ?>
                    <li><a href="<?=htmlentities($this->data['formTarget'])?>?StateId=<?=$this->data['stateId']?>&mfaId=<?=$mfaOpt['id']?>"><?=$mfaOpt['type']?></a></li>
                    <?php
                }
            }
            ?>
        </ul>
        </p>
        <?php
    }
    ?>
</form>
<?php

$this->includeAtTemplateBase('includes/footer.php');
