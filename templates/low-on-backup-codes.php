<?php
$this->data['header'] = 'Almost out of Printable Backup Codes';
$this->includeAtTemplateBase('includes/header.php');

$numBackupCodesRemaining = $this->data['numBackupCodesRemaining'];
?>
<p>
  You are almost out of Printable Backup Codes.
  You only have <?= (int)$numBackupCodesRemaining ?> remaining.
</p>
<form method="post">
    <button name="getMore" style="padding: 4px 8px;">
        Get more
    </button>
    
    <button name="continue" style="padding: 4px 8px;">
        Remind me later
    </button>
</form>
<?php
$this->includeAtTemplateBase('includes/footer.php');
