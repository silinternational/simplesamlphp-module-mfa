<?php
$this->data['header'] = 'Last Printable Backup Code';
$this->includeAtTemplateBase('includes/header.php');

$hasOtherMfaOptions = $this->data['hasOtherMfaOptions'];
?>
<p>
    You just used your last Printable Backup Code.
</p>

<?php if ($hasOtherMfaOptions): ?>
    <p>
      We recommend you get more now so that you will have some next time we ask
      you for one. Otherwise, you will need to use a different option (such as a
      Security Key or Smartphone App) the next time we ask you for 2-Step Verification.
    </p>
<?php else: ?>
    <p>
      Since you do not have any other 2-Step Verification options set up yet,
      you need to get more Printable Backup Codes now so that you will have some
      next time we ask you for one.
    </p>
<?php endif; ?>

<form method="post">
    <button name="getMore" style="padding: 4px 8px;">
        Get more
    </button>
    
    <?php if ($hasOtherMfaOptions): ?>
        <button name="continue" style="padding: 4px 8px;">
            Remind me later
        </button>
    <?php endif; ?>
</form>
<?php
$this->includeAtTemplateBase('includes/footer.php');
