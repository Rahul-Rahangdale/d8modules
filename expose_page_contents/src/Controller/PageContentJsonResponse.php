<?php

namespace Drupal\expose_page_contents\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Controller to handle the request for the node data of type page.
 */
class PageContentJsonResponse extends ControllerBase {

  private $tempStore;

  /**
   * @inheritdoc
   */
  public function __construct(PrivateTempStoreFactory $temp_store) {
    $this->tempStore = $temp_store->get('expose_node');
  }

  /**
   * Dependency Injection.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container Interface.
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
    );
  }

  /**
   * Check if api key and node exists and give the response accordingly.
   *
   * @param string $api_key
   *   API key sent in the url.
   * @param int $nid
   *   Id of requested node.
   *
   * @return object
   *   Returns access object.
   */
  public function access($api_key, $nid) {
    // Get stored api key.
    $site_api_key = $this->config('siteapikey.configuration')->get('siteapikey');

    // Load the node by given id.
    $node = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->load($nid);
    if ($site_api_key !== $api_key || is_null($node) || $node->bundle() !== 'page') {
      return AccessResult::forbidden();
    }
    else {
      // Set the value of node in tempstore to avoid the call to,
      // entityTypeManager again in jsonResponse function.
      $this->tempStore->set($nid, $node);
      return AccessResult::allowed();
    }

  }

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
    // Get the nodedata store in tempstore.
    $node = $this->tempStore->get($nid);
    return new JsonResponse($node->toArray());
  }

}
