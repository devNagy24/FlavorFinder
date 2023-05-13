<?php
//require('index.php');
//require('db.php');
ini_set('memory_limit','256M');

$web = \Web::instance();
$response = $web->request('https://forkify-api.herokuapp.com/api/v2/recipes/5ed6604591c37cdc054bc886');

// Check the status code:

    // Status code indicates success:
    $data = json_decode($response['body'], true);

    var_dump($data);
    echo '<pre>';
    print_r($data);
    echo '</pre>';


