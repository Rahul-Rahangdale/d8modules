<?php

namespace Drupal\current_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the block which returns the current time with configured timezone.
 *
 * @Block(
 *  id = "location_block",
 *  admin_label = @Translation("Current Location Block"),
 * )
 */
class LocationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Configuration factory service to access configuration.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactoryService;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactoryService = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get location configurations.
    $location_config = $this->configFactoryService->get('current_location.config');
    $data['country'] = $location_config->get('country');
    $data['city'] = $location_config->get('city');

    // Get current datetime using lazy_builder as we don't have to cache this.
    $data['current_time'] = [
      '#lazy_builder' => ['current_location.time:getCurrentTime', []],
      '#create_placeholder' => TRUE,
    ];

    // Pass data to the template.
    $build = [
      '#theme' => 'location-block',
      '#data' => $data,
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), ['config:current_location.config']);
  }

}
