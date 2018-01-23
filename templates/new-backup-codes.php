<?php
$this->data['header'] = 'New Printable Backup Codes';
$this->includeAtTemplateBase('includes/header.php');

$newBackupCodes = $this->data['newBackupCodes'];
$mfaSetupUrl = $this->data['mfaSetupUrl'];
?>

<?php if (empty($newBackupCodes)): ?>
    <p>
        Something went wrong while we were trying to get more Printable Backup Codes for you.
    </p>
    <p>
        We are sorry for the inconvenience. After you finish logging in, please
        check your 2-Step Verification methods here: <br />
        <a href="<?= htmlentities($mfaSetupUrl); ?>"><?= htmlentities($mfaSetupUrl); ?></a>
    </p>
<?php else: ?>
    <p>
        Here are your new Printable Backup Codes. <b>Remember</b> to keep them
        secret (like a password) and store them somewhere safe.
    </p>
    <pre><?php
        foreach ($newBackupCodes as $backupCode) {
            echo htmlentities($backupCode) . "\n";
        }
    ?></pre><br />
    <p>
        Once you have stored them somewhere safe, you are welcome to click the
        button below to continue to where you were going.
    </p>
<?php endif; ?>

<form method="post">
    <button name="continue" style="padding: 4px 8px;">
        Finish login
    </button>
</form>
<?php
$this->includeAtTemplateBase('includes/footer.php');
