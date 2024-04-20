<?php 

require_once('Pipleline.php');

$pipeline = new Pipeline('Hello');

$result = $pipeline
    ->send('Hello')
    ->through([
        function ($value, $next) {
            return $next($value . ' ');
        },
        function ($value, $next) {
            return $next($value . 'World');
        },
        function ($value, $next) {
            return $next($value . '!');
        },
    ])
    ->then(function ($value) {
        return strtoupper($value);
    });

echo $result; // Output: HELLO WORLD!