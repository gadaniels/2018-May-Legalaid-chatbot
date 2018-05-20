<?php
namespace Drupal\dialogDemo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\JsonDecode;

/**
* Class DefaultController.
*/
class DefaultController extends ControllerBase {

/**
* Symfony\Component\HttpFoundation\RequestStack definition.
*
* @var \Symfony\Component\HttpFoundation\RequestStack
*/
protected $requestStack;

/**
* The logger factory.
*
* @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
*/
protected $loggerFactory;

/**
* Constructs a new DefaultController object.
*/
public function __construct(RequestStack $request_stack, LoggerChannelFactoryInterface $loggerFactory) {
$this->requestStack = $request_stack;
$this->loggerFactory = $loggerFactory;
}

/**
* {@inheritdoc}
*/
public static function create(ContainerInterface $container) {
return new static(
$container->get('request_stack'),
$container->get('logger.factory')
);
}

/**
* Handlerequest.
*
* @return mixed
*   Return Hello string.
*/
public function handleRequest() {
  $text = "Please visit illinoislegalaid.org for help with this issue";
  $results = $_REQUEST;
  $results = file_get_contents( 'php://input' );
  $clean_results = json_decode($results);
  $query = strtolower($clean_results->result->resolvedQuery);
  $intent = $clean_results->result->metadata->intentName;
  $text = $this->processRequest($intent, $query);

  $data = [
'speech' => $text,
'displayText' => $text,
'data' => '',
'contextOut' => [],
'source' => 'ilao',
];
return JsonResponse::create($data, 200);
}

protected function processRequest($intent, $query) {
  switch ($intent) {
    case 'LegalIntent':
      if (stripos($query,'legal') !== FALSE || (stripos($query,'lawyer') !== FALSE)) {

        $title = $title ."Your nearest legal self-help center is at the Kane County courthouse.  It opens Monday at 9am.";
        return $title;
      }

      if (stripos($query,'divorce') !== FALSE) {
        $response = 'If a person wants to end a marriage, they can file for divorce. In a divorce, the court ends the marriage and all of the legal benefits that are a part of that marriage. A divorce can be contested (spouses do not agree) or uncontested (spouses agree).';
        return $response;
      }

      if (stripos($query, 'evict') !== FALSE || stripos($query, 'hous') !== FALSE  ) {
        $response = 'A landlord can evict a tenant if the tenant fails to pay the rent when it is due;
Violates any of the terms and conditions of the lease agreement;
Damages the property;
Does not leave the property after the lease comes to an end; or
Does not have a written lease, but pays rent monthly, and the landlord gives a 30-day notice to move.';
      return $response;
      }
      if (stripos($query, 'forecl') !== FALSE || stripos($query, 'mortg') !== FALSE  ) {
        $response = 'It is important to participate in your court case so you know what is happening and what you can do. You should also work with the lender on a loss mitigation option. During the case, lenders may accept your payments if they are in whole month amounts. The lender must apply the payment to your unpaid debt balance. If the payment is not enough to pay off the entire debt currently due, the lender may continue its foreclosure case.';
    return $response;
      }
      break;
    default:
      return "I don't know how to help you with this.";
    break;
  }

// Here we will process the request to get intent

// and fulfill the action.
}
}