Feature: Prompt for MFA credentials

  Scenario: Don't prompt for MFA
    Given I provide credentials that do not need MFA
    When I login
    Then I should end up at my intended destination

  Scenario: Needs MFA, but no MFA options are available
    Given I provide credentials that need MFA but have no MFA options available
    When I login
    Then I should see a message that I have to set up MFA
      And there should be a way to go set up MFA now

  Scenario: Needs MFA, has backup code option available
    Given I provide credentials that need MFA and have backup codes available
    When I login
    Then I should see a prompt for a backup code

  Scenario: Needs MFA, has TOTP option available
    Given I provide credentials that need MFA and have TOTP available
    When I login
    Then I should see a prompt for a TOTP code

  Scenario: Needs MFA, has U2F option available
    Given I provide credentials that need MFA and have U2F available
    When I login
    Then I should see a prompt for a U2F security key

  Scenario: Allow some failed MFA attempts
    Given I provide credentials that need MFA and have backup codes available
      And I have logged in
      And I have submitted an incorrect backup code 2 times
    When I submit a correct backup code
    Then I should end up at my intended destination

  Scenario: Prevent too many failed MFA attempts
    Given I provide credentials that need MFA and have backup codes available
      And I have logged in
      And I have submitted an incorrect backup code 2 times
    When I submit another incorrect backup code
    Then I should have to provide my username and password again

  Scenario: Prevent too many valid user/pass and failed MFA sequences
    Given I provide credentials that need MFA and have backup codes available
      And I have logged in
      And I have submitted too many incorrect backup codes
      And I have logged in again
      And I have submitted an incorrect backup code 2 times
    When I submit another incorrect backup code
    Then that account should not be allowed to log in for awhile
