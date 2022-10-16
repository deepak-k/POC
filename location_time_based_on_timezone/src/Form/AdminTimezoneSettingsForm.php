<?php

namespace Drupal\location_time_based_on_timezone\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

/**
 * AdminTimezoneSettingsForm.
 */
class AdminTimezoneSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'country_timezone.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'admin_timezone_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('country_timezone.adminsettings');

    $form['country_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country Name'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('country_name') ? $config->get('country_name') : 'India',
    ];

    $form['city_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City Name'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('city_name') ? $config->get('city_name') : 'Dharamshala',
    ];

    $timezone_list = [
      'America/Chicago' => 'America/Chicago',
      'America/New_York' => 'America/New_York',
      'Asia/Tokyo' => 'Asia/Tokyo',
      'Asia/Dubai' => 'Asia/Dubai',
      'Asia/Kolkata' => 'Asia/Kolkata',
      'Europe/Amsterdam' => 'Europe/Amsterdam',
      'Europe/Oslo' => 'Europe/Oslo',
      'Europe/London' => 'Europe/London',
    ];

    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#options' => $timezone_list,
      '#default_value' => $config->get('timezone') ? $config->get('timezone') : 'Asia/Kolkata',

    ];

    return parent::buildForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('country_timezone.adminsettings')
      ->set('country_name', $form_state->getValue('country_name'))
      ->set('city_name', $form_state->getValue('city_name'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();

    Cache::invalidateTags(['custom_cache_tag']);
  }

}
