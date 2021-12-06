<?php
$this->data['header'] = '2-Step Verification';
$this->includeAtTemplateBase('includes/header.php');
?>
<?php if ($this->data['supportsWebAuthn']): ?>
    <script src="https://unpkg.com/@simplewebauthn/browser@4.1.0/dist/bundle/index.umd.min.js"
            integrity="sha384-WiaH30NJIrU5UVPRVBjvWA8J1olK/4OMlboN6o6NTmQW7XExLsqc6OFbKvhFoPAj"
            crossorigin="anonymous"></script>
    <script type="application/javascript">
        window.onload = function() {

          console.log('data:');
          console.log(<?=json_encode($this->data)?>);

          var mfa = <?php echo json_encode($this->data['mfaOption']['data'], JSON_PRETTY_PRINT);?>;
          console.log(mfa);
          u2f.sign(mfa.appId, mfa.challenge, [mfa], function(response) {
            console.log(response);
            if (response.errorCode && response.errorCode != 0) {
              alert("Error from WebAuthn, code: " + response.errorCode);
              return;
            }
            var mfaForm = document.getElementById('mfaForm');
            var mfaResponse = document.getElementById('mfaSubmission');
            mfaResponse.value = JSON.stringify(response);
            mfaForm.submit();
          });
        }
    </script>
<?php endif; ?>
<form method="post">
    <h2>USB Security Key</h2>
    <?php if ($this->data['supportsWebAuthn']): ?>
        <p id="mfaInstructions">Please insert your security key and press its button.</p>
        <p>
            <input type="checkbox" name="rememberMe" id="rememberMe" value="true" checked="checked"/>
            <label for="rememberMe">Remember this computer for 30 days</label>
            <br />
            <input type="hidden" id="mfaSubmission" name="mfaSubmission" />
            <input type="hidden" id="submitMfa" name="submitMfa" />
            
        </p>
    <?php else: ?>
        <p>
            USB Security Keys are not supported in your current browser.
            Please consider a more secure browser like
            <a href="https://www.google.com/chrome/browser" target="_blank">Google Chrome</a>.
        </p>
    <?php endif; ?>

    <?php if (count($this->data['mfaOptions']) > 1): ?>
        <p>
            Don't have your security key handy? You may also use:
        </p>
        <ul>
            <?php
            foreach ($this->data['mfaOptions'] as $mfaOpt) {
                if ($mfaOpt['type'] != 'webauthn') {
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
