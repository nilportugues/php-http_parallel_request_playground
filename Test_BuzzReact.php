<?php
include './vendor/autoload.php';

use Clue\React\Buzz\Browser;

/**
 * @return array
 * @throws Exception
 */
function indexAction()
{
    $loop = React\EventLoop\Factory::create();
    $client = new Browser($loop);

    $url = 'http://127.0.0.1:8000/'; //will fail without trailing /...wtf :(

    $promises = [];
    $promises[] = slowHttpRequest($client, $url);
    $promises[] = slowHttpRequest($client, $url);
    $promises[] = slowHttpRequest($client, $url);
    $promises[] = slowHttpRequest($client, $url);
    $promises[] = slowHttpRequest($client, $url);
    $resolvedPromised = Clue\React\Block\awaitAll($promises, $loop);

    /** @var \Clue\React\Buzz\Message\Body $message */
    $messages = [];
    foreach($resolvedPromised as $message) {
        $messages[] = (string) $message->getBody();
    }

    return $messages;
}


/**
 * @param Browser $client
 * @param string  $url
 *
 * @return \React\Promise\PromiseInterface
 */
function slowHttpRequest(Browser $client, $url)
{
    return $client->get($url);
}


//------------------------------------------------------------------

$timeStart = microtime(true);
print_r(indexAction());

$timeEnd = microtime(true);
$executionTime = ($timeEnd - $timeStart);

echo 'You really waited for: ' . $executionTime . ' seconds' . PHP_EOL;
echo 'This is approximately '.(100 - ($executionTime/25)*100).'% faster than normal curl.'. PHP_EOL;