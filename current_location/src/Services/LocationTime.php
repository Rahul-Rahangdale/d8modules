<?php

namespace Drupal\current_location\Services;

use DateTime;
use DateTimeZone;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Provides a class for handling location time based timezone configured.
 */
class LocationTime implements TrustedCallbackInterface {

  private $configFactoryService;

  /**
   * Constructor function.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Configuration factory service.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactoryService = $config_factory;
  }

  /**
   * Returns the current date time with configured timezone.
   *
   * @return string
   *   Datetime formatted string.
   */
  public function getCurrentTime() {
    // Create datetime object.
    $dateTime = new DateTime();

    // Get timezone from configuration form.
    $timezone = $this->configFactoryService->get('current_location.config')->get('timezone');

    // Check if timezone config is set.
    if (isset($timezone)) {
      $dateTime->setTimezone(new DateTimeZone($timezone));
    }

    /* Return the renderable array so that it can be replaced when called using
    lazy loader. */
    return [
      '#markup' => $dateTime->format('jS M Y - h:i:s A'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return ['getCurrentTime'];
  }

}
