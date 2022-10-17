<?php

namespace Drupal\location_time_based_on_timezone;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Component\Datetime\Time;

/**
 * Class CurrentTimeBasedOnTimezoneService.
 */
class CurrentTimeBasedOnTimezoneService {

  /**
   * The Configuration Factory.
   *
   * @var Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Date and time.
   *
   * @var Drupal\Component\Datetime\Time
   */
  protected $time;

  /**
   * Class Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   A configuration factory object.
   * @param \Drupal\Component\Datetime\Time $time
   *   The time service.
   */
  public function __construct(Time $time, ConfigFactory $config_factory) {
    $this->time = $time;
    $this->configFactory = $config_factory;
  }

  /**
   * Get timezone from the config form.
   *
   * @return array
   *   Array of location and its current time.
   */
  public function currentTimeBasedOnTimezone() {
    $config = $this->configFactory->get('country_timezone.adminsettings');
    $timezone = $config->get('timezone');
    $current_time = $this->time->getCurrentTime();
    $now = DrupalDateTime::createFromTimestamp($current_time);
    $now->setTimezone(new \DateTimeZone($timezone));
    $date_time = $now->format('jS M Y - g:i A');
    return $date_time;
  }

}
