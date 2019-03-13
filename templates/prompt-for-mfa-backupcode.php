<?php
$this->data['header'] = '2-Step Verification';
$this->includeAtTemplateBase('includes/header.php');

if (! empty($this->data['errorMessage'])) {
    ?>
    <p style="border: 3px solid red; padding: 5px;">
      <b>Oops!</b><br />
      <?= htmlentities($this->data['errorMessage']); ?>
    </p>
    <?php
}

?>
<form method="post">
    <h2>Printable Backup Code</h2>
    <p>
      Each code can only be used once, so the code you enter this time will be
      used up and will not be available again.
    </p>
    <p>
        Enter code: <input type="text" id="mfaSubmission" name="mfaSubmission" />
        <br />
        <input type="checkbox" name="rememberMe" id="rememberMe" value="true" checked="checked"/>
        <label for="rememberMe">Remember this computer for 30 days</label>
        <br />
        <button type="submit" id="submitMfa" name="submitMfa"
                style="padding: 4px 8px;">Submit</button>
    </p>
    <?php if (count($this->data['mfaOptions']) > 1): ?>
        <p>
            Don't have your printable backup codes handy? You may also use:
        </p>
        <ul>
            <?php
            foreach ($this->data['mfaOptions'] as $mfaOpt) {
                if ($mfaOpt['type'] != 'backupcode') {
                    ?>
                    <li><a href="prompt-for-mfa.php?StateId=<?= htmlentities($this->data['stateId']) ?>&mfaId=<?= htmlentities($mfaOpt['id']) ?>"><?=
                       htmlentities($mfaOpt['type'])
                    ?></a></li>
                    <?php
                }
            }
            ?>
        </ul>
    <?php endif; ?>
    <?php if ( ! empty($this->data['managerEmail'])): ?>
        <p>
            Can't use any of your 2-Step Verification options?
            <a href="send-manager-mfa.php?StateId=<?= htmlentities($this->data['stateId']) ?>">
                Click here</a> for assistance.
        </p>
    <?php endif; ?>
</form>
<?php
$this->includeAtTemplateBase('includes/footer.php');
