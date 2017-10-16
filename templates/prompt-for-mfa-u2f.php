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

    <?php
    if (count($this->data['mfaOptions']) > 1) {
        ?>
        <p>
            Don't have your security key handy? You may also use:
        <ul>
            <?php
            foreach ($this->data['mfaOptions'] as $mfaOpt) {
                if ($mfaOpt['type'] != 'u2f'){
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
