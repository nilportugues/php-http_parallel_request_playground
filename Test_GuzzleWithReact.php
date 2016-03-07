<?php
include './vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

/**
 * @return array
 */
function indexAction()
{
    // Create a Guzzle client that uses a Guzzle handler that integrates with React event loop
    $loop = Factory::create();
    $client = new Client(['handler' => HandlerStack::create(multiCurlHandler($loop))]);

    // Send a request and handle the response asynchronously
    $url = 'http://localhost:8000';
    $messages = [];
    slowHttpRequest($client, $url, $messages);
    slowHttpRequest($client, $url, $messages);
    slowHttpRequest($client, $url, $messages);
    slowHttpRequest($client, $url, $messages);
    slowHttpRequest($client, $url, $messages);

    $loop->run();

    return $messages;
}


/**
 * @param Client $client
 * @param        $url
 * @param array  $messages
 */
function slowHttpRequest(Client $client, $url, array &$messages)
{
    $promise        = $client->getAsync($url);
    $resolvePromise = function (ResponseInterface $response) use (&$messages) {
        $messages[] = $response->getBody()->getContents();
    };
    $promise->then($resolvePromise);
}

/**
 * @param LoopInterface $loop
 *
 * @return CurlMultiHandler
 */
function multiCurlHandler(LoopInterface $loop)
{
    $handler = new CurlMultiHandler();

    //Timer is actually used. Ignore your IDE.
    $timer  = $loop->addPeriodicTimer(0, \Closure::bind(function () use (&$timer) {
        $this->tick();
        if (empty($this->handles) && Promise\queue()->isEmpty()) {
            $timer->cancel();
        }
    }, $handler, $handler));

    return $handler;
}

//------------------------------------------------------------------

$timeStart = microtime(true);
print_r(indexAction());

$timeEnd = microtime(true);
$executionTime = ($timeEnd - $timeStart);

echo 'You really waited for: ' . $executionTime . ' seconds' . PHP_EOL;
echo 'This is approximately '.(($executionTime/25)*100).'% faster than normal curl.'. PHP_EOL;