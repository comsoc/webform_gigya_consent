<?php

namespace Drupal\webform_gigya_consent\Services;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Provides access to the Gigya Consent API.
 */
class GigyaConsent {

  /**
   * Guzzle client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * Gigya API configuration.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * OAuth access token.
   *
   * @var string
   */
  protected $accessToken;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   Guzzle client.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $http_client) {
    $this->client = $http_client;
    $this->config = $config_factory->get('webform_gigya_consent.settings');
  }

  /**
   * Set a user's consent status.
   *
   * @param string $emailAddress
   *   Consenting user's email address.
   * @param string $consentName
   *   Internal Gigya name of the consent.
   * @param bool $isConsentGranted
   *   True if consent is granted.
   */
  public function setUserConsentInfo(string $emailAddress, string $consentName, bool $isConsentGranted) {
    // First ensure a valid OAuth token.
    $this->buildOauthToken();
    try {
      $this->client->request('POST', "{$this->config->get('gigya_server')}/RST/SetUserConsentInfo", [
        'headers' => [
          "Authorization: {$this->accessToken}",
        ],
        'json' => [
          'SourceApplication' => $this->config->get('source_app_name'),
          'EmailAddress' => $emailAddress,
          'Consents' => [
            'Consent' => [
              'ConsentName' => $consentName,
              'isConsentGranted' => $isConsentGranted,
              'lastConsentModifiedDate' => date('Y/m/d H:i:s'),
            ],
          ],
        ],
      ]);
    }
    catch (GuzzleException $exception) {
      \Drupal::logger('webform_gigya_consent')->error("Could not submit Gigya consent request.");
    }
  }

  /**
   * Ensure a current OAuth token exists in session, or request one.
   */
  private function buildOauthToken() {
    if (!isset($_SESSION['webform_gigya_consent_token']) || (time() >= $_SESSION['webform_gigya_consent_token']['expires'])) {
      // If there is no token in the session or it's expired, request a new one.
      try {
        $response = $this->client->request('POST', "{$this->config->get('gigya_server')}/api/oauth/token", [
          'json' => [
            'grant_type' => 'client_credentials',
            'client_id' => $this->config->get('gigya_client_id'),
            'client_secret' => $this->config->get('gigya_secret_key'),
            'scope' => $this->config->get('gigya_scope'),
          ],
        ]);
        $response = json_decode($response->getBody(), TRUE);
      }
      catch (GuzzleException $exception) {
        \Drupal::logger('webform_gigya_consent')->error("Could not generate Gigya OAuth Token.");
      }

      // Set expiration time if all we were given is expires_in.
      if (!isset($response['expires'])) {
        $response['expires'] = time() + intval($response['expires_in']);
      }
      // Store token in session.
      $_SESSION['webform_gigya_consent_token'] = $response;
    }
    else {
      // Use existing token from the session.
      $response = $_SESSION['webform_gigya_consent_token'];
    }

    $this->accessToken = "{$response['token_type']} {$response['access_token']}";
  }

}
