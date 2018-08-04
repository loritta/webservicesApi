<?php

    $key = "Z4VLAA2WR46NYITW6TXU";
    $test_url = "https://www.eventbriteapi.com/v3/users/me/?token=" . $key;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
    // Comment out the line below if you receive an error on certain hosts that have security restrictions
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    $info = json_decode($data, true);
    print_r($test_url);
    print_r($info);
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <h1>Testing Event Brite API</h1>
</body>
</html>