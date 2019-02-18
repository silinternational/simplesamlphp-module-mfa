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
    <h2>Manager Rescue Code</h2>
    <p>
        We can send a code to your manager. To request a code,
        <a href="send-manager-mfa.php?StateId=<?= htmlentities($this->data['stateId']) ?>">click here</a>.
    </p>
    <p>
      When you receive your code from your manager, enter it here.
    </p>
    <p>
        Enter code: <input type="text" id="mfaSubmission" name="mfaSubmission" />
        <br />
        <button type="submit" id="submitMfa" name="submitMfa"
                style="padding: 4px 8px;">Submit</button>
    </p>
    <?php if (count($this->data['mfaOptions']) > 1): ?>
        <p>
            You may also use:
        </p>
        <ul>
            <?php
            foreach ($this->data['mfaOptions'] as $mfaOpt) {
                if ($mfaOpt['type'] != 'manager') {
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
</form>
<?php
$this->includeAtTemplateBase('includes/footer.php');
