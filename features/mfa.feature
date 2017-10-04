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
