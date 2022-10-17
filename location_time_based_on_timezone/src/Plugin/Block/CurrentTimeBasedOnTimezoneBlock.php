<?php

namespace Drupal\location_time_based_on_timezone\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\location_time_based_on_timezone\CurrentTimeBasedOnTimezoneService;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Cache\Cache;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'CurrentTimeBasedOnTimezone' block.
 *
 * @Block(
 *  id = "current_time_based_on_timezone_block",
 *  admin_label = @Translation("Current Time Based On Timezone Block"),
 * )
 */
class CurrentTimeBasedOnTimezoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The Configuration Factory.
   *
   * @var Drupal\Core\Config\Config
   */
  protected $configFactory;

  /**
   * The current time based on timezone Drupal.
   *
   * @var current_time_based_on_timezoneDrupal\location_time_based_on_timezone\CurrentTimeBasedOnTimezoneService
   */
  protected $currentTimeBasedOnTimezone;

  /**
   * Constructs Block.
   *
   * @param array $configuration
   *   Configuration array.
   * @param string $plugin_id
   *   Plugin id.
   * @param mixed $plugin_definition
   *   Plugin definition.
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   The config factory.
   * @param \Drupal\location_time_based_on_timezone\CurrentTimeBasedOnTimezoneService $currentTimeBasedOnTimezone
   *   Current date time.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentTimeBasedOnTimezoneService $currentTimeBasedOnTimezone, ConfigFactory $configFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentTimeBasedOnTimezone = $currentTimeBasedOnTimezone;
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('location_time_based_on_timezone.current_time_service'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $current_time_data = $this->currentTimeBasedOnTimezone->currentTimeBasedOnTimezone();

    $config = $this->configFactory->get('country_timezone.adminsettings');
    $country_name = $config->get('country_name');
    $city_name = $config->get('city_name');

    $build = [
      '#theme' => 'current_time_based_on_timezone_block',
      '#country' => $country_name,
      '#city' => $city_name,
      '#current_time' => $current_time_data,
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), ['custom_cache_tag']);
  }

}
