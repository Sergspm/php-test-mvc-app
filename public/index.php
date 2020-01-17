<?php

declare(strict_types=1);

use App\Controllers\{OrdersController, ProductsController};
use App\DTO\HttpResponseErrorDTO;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\{Exception\MethodNotAllowedException,
    Exception\ResourceNotFoundException,
    Matcher\UrlMatcher,
    RequestContext,
    Route,
    RouteCollection};

require __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();

$routes = new RouteCollection();

$routes->add(
    'products.list',
    (new Route('/products', ['_controller' => ProductsController::class]))->setMethods(['GET'])
);
$routes->add(
    'orders.create',
    (new Route('/orders', ['_controller' => OrdersController::class, '_action' => 'create']))->setMethods(['POST'])
);
$routes->add(
    'orders.pay',
    (new Route('/orders/pay', ['_controller' => OrdersController::class, '_action' => 'pay']))->setMethods(['POST'])
);

$matcher = new UrlMatcher($routes, (new RequestContext('/'))->fromRequest($request));

$response = new JsonResponse();

try {
    $parameters = $matcher->match($request->getRequestUri());

    $controller = new $parameters['_controller']();
    $action = $parameters['_action'] ?? 'index';

    $response
        ->setData($controller->{$action}($request, $parameters))
        ->setStatusCode(200);
} catch (ResourceNotFoundException $e) {
    $response
        ->setData(new HttpResponseErrorDTO('Not found'))
        ->setStatusCode(404);
} catch (MethodNotAllowedException $e) {
    $response
        ->setData(new HttpResponseErrorDTO('Method not allowed'))
        ->setStatusCode(405);
} catch (Exception $e) {
    $response
        ->setData(new HttpResponseErrorDTO('Server error'))
        ->setStatusCode(500);
}

$response->send();

