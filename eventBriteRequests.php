<?php 

if(isset($_POST['action']) && !empty($_POST['action'])) {

    $key = "Z4VLAA2WR46NYITW6TXU";

    $action = $_POST['action'];

    switch($action) {

        case "searchEvents" :

        // make sure the 2 variables are set
            if(isset($_POST['selectedCity']) && !empty($_POST['selectedCity'] && 
                isset($_POST['selectedDate']) && !empty($_POST['selectedDate']))) {

                $city = ($_POST['selectedCity']);
                $date = ($_POST['selectedDate']);

                $url = "https://www.eventbriteapi.com/v3/events/search?" .
                        "location.address=" . $city .
                        "&start_date.range_start=" . $date .
                        "&expand=organizer,venue" . // for more event info
                        "&token=" . $key;

                $data = callCurl($url);
            
            echo $data;
            }
            break;
    }
}

function callCurl($url) {

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
    // Comment out the line below if you receive an error on certain hosts that have security restrictions
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    // testing
    $testData = json_encode($data);
    //$testData = json_decode($data);
    return $testData;
    //return $data;
}


?>