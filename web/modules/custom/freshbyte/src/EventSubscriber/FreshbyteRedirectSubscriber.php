<?php

/**
 * @file
 * Contains \Drupal\my_module\EventSubscriber\MyModuleRedirectSubscriber
 */
 
namespace Drupal\freshbyte\EventSubscriber;
 
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
 
class FreshbyteRedirectSubscriber implements EventSubscriberInterface {
 
  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return([
      KernelEvents::REQUEST => [
        ['redirectMyContentTypeNode'],
      ]
    ]);
  }
 
  /**
   *
   * @param GetResponseEvent $event
   * @return void
   */
  public function redirectMyContentTypeNode(GetResponseEvent $event) {
    $request = $event->getRequest();
 
    if ($request->attributes->get('_route') !== 'entity.node.canonical') {
      return;
    }
 
    // Redirect to Product content type.
    if ($request->attributes->get('node')->getType() !== 'ct_product') {
      return;
    }
 
    // Set the destination.
    $cookie_value = \Drupal::request()->cookies->get('disclaimer');
    $protected = $request->attributes->get('node')->field_protected->value;

    if ($cookie_value == NULL && $protected != 0) {
      $redirect_url = Url::fromUri('internal:/disclaimer');
      $response = new RedirectResponse($redirect_url->toString(), 301);
      $event->setResponse($response);
    }
  }
}