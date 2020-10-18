<?php
namespace Drupal\expose_page_contents\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Show the extended site information form when the route is matched.
    if ($route = $collection->get('system.site_information_settings')) {
      $route->setDefault('_form', '\Drupal\expose_page_contents\Form\ExtendedSiteInformationForm');
    }
  }

}
