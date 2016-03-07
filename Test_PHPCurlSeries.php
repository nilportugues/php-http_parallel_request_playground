<?php
include './vendor/autoload.php';


/**
 *
 */
function indexAction() {

    $messages = array();
    $messages[] = slowHttpRequest();
    $messages[] = slowHttpRequest();
    $messages[] = slowHttpRequest();
    $messages[] = slowHttpRequest();
    $messages[] = slowHttpRequest();

    return $messages;
}


/**
 * @return string
 */
function slowHttpRequest() {
    $curl = curl_init();
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'http://localhost:8000']);

    return curl_exec($curl);
}

//------------------------------------------------------------------

$timeStart = microtime(true);
print_r(indexAction());

$timeEnd = microtime(true);
$executionTime = ($timeEnd - $timeStart);

echo 'You really waited for: ' . $executionTime . ' seconds' . PHP_EOL;
echo 'This is approximately '.abs(($executionTime/25)*100).'% slower than theoretical time.'. PHP_EOL;