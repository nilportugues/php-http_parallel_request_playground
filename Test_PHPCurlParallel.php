<?php
include './vendor/autoload.php';


/**
 *
 */
function indexAction() {

    $data = [
        'http://localhost:8000',
        'http://localhost:8000',
        'http://localhost:8000',
        'http://localhost:8000',
        'http://localhost:8000',
    ];
    $messages = multiRequest($data);

    return $messages;
}


function multiRequest($data, $options = array()) {

    // array of curl handles
    $curly = [];
    // data to be returned
    $result = [];

    // multi handle
    $mh = curl_multi_init();

    // loop through $data and create curl handles
    // then add them to the multi-handle
    foreach ($data as $id => $d) {
        $curly[$id] = curl_init();
        $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
        curl_setopt($curly[$id], CURLOPT_URL,            $url);
        curl_setopt($curly[$id], CURLOPT_HEADER,         0);
        curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

        // extra options?
        if (!empty($options)) {
            curl_setopt_array($curly[$id], $options);
        }

        curl_multi_add_handle($mh, $curly[$id]);
    }

    // execute the handles
    $running = null;
    do {
        curl_multi_exec($mh, $running);
    } while($running > 0);


    // get content and remove handles
    foreach($curly as $id => $c) {
        $result[$id] = curl_multi_getcontent($c);
        curl_multi_remove_handle($mh, $c);
    }

    // all done
    curl_multi_close($mh);

    return $result;
}


//------------------------------------------------------------------

$timeStart = microtime(true);
print_r(indexAction());

$timeEnd = microtime(true);
$executionTime = ($timeEnd - $timeStart);

echo 'You really waited for: ' . $executionTime . ' seconds' . PHP_EOL;
echo 'This is approximately '.abs(($executionTime/25)*100).'% slower than theoretical time.'. PHP_EOL;