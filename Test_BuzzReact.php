<?php
include './vendor/autoload.php';

use Clue\React\Buzz\Browser;
use Clue\React\Buzz\Message\Response;

/**
 * @return array
 */
function indexAction()
{
    // Create a Guzzle client that uses a Guzzle handler that integrates with React event loop
    $loop = React\EventLoop\Factory::create();
    $client = new Browser($loop);

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
 * @param Browser $client
 * @param        $url
 * @param array  $messages
 */
function slowHttpRequest(Browser $client, $url, array &$messages)
{
    $messages[] = $client->get($url)->then(function (Response $result) {
        return $result->getBody();
    });
}


//------------------------------------------------------------------

$timeStart = microtime(true);
print_r(indexAction());

$timeEnd = microtime(true);
$executionTime = ($timeEnd - $timeStart);

echo 'You really waited for: ' . $executionTime . ' seconds' . PHP_EOL;
echo 'This is approximately '.(100 - ($executionTime/25)*100).'% faster than normal curl.'. PHP_EOL;