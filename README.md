# HTTP Request Coroutines

Playground to test how to make the fastest parallel HTTP requests with the existing PHP technologies.


## Install

This repository assumes that you're developing using modern tools such as `composer` and `docker`.

Just copy & paste this in your terminal. 

```
composer install && sh docker.sh start
```

## The Experiment

The test consists in firing 5 requests to a slow site, `http://localhost:8000`.  This page by default, will load after `5 seconds`, so firing up 5 request leaves us with the following:

- Fastest parallel processing theoretical time: `5 seconds`.
- Fastest serial processing theoretical time: `25 seconds`.


## Configuration

You may modify the delay time by modifying the `index.php` file found in `public/index.php`.

Its contents just simulate a slow loading website:

```php
<?php 
$delay = 5;
sleep($delay);
echo 'You waited for '.$delay.' seconds.'; 
```

## Running it

On a terminal window run: 

```
docker run -v $PWD/public/:/var/www/ php_coroutines .
```

Now on **ANOTHER** terminal window, you may run: 

```
php withCoroutines.php
php withoutCoroutines.php
```

And see the difference in timings between implementations. 

## Experiments: 

- Curl: `withoutCoroutines.php`
- Guzzle6 with React Loop: `withCoroutines.php`

## Todo: 

- Native Curl multi requests.
- Any other anyone proposes via Pull Request.