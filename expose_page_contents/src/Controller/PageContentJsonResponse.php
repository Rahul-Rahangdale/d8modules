<?php

namespace Drupal\expose_page_contents\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Controller to handle the request for the node data of type page.
 */
class PageContentJsonResponse extends ControllerBase {

  /**
   * Returns the json data of a node if found, otherwise shows access denied.
   *
   * @param string $api_key
   *   API key sent in the url.
   * @param int $nid
   *   Id of requested node.
   *
   * @return object
   *   Returns JSON data if key and nid match else access denied.
   */
  public function jsonResponse($api_key, $nid) {
    // Get stored api key.
    $site_api_key = $this->config('siteapikey.configuration')->get('siteapikey');

    // Compare if both the API keys are same.
    if ($site_api_key == $api_key && $nid) {
      // Load node by id.
      $node = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->load($nid);

      if ($node && $node->bundle() == 'page') {
        return new JsonResponse($node->toArray());
      }
      // If node doesn't exist or it is not of type page, show access denied.
      else {
        throw new AccessDeniedHttpException();
      }
    }
    // If api key and nid does not match show access denied error.
    else {
      throw new AccessDeniedHttpException();
    }
  }

}
