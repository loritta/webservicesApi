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

                // to show events ending up to a month after the selected date            
                //$endDate = date('Y-m-d', strtotime($date. ' + 1 months')) . "T00:00:00";

                // if category is not null, not sure if != "" is the same as !empty ?
                if (isset($_POST['category']) && ($_POST['category']) != "") {

                    $category = $_POST['category']; // should probably filter this
                    $url = "https://www.eventbriteapi.com/v3/events/search?" .
                        "location.address=" . $city .
                        "&categories=" . $category .
                        //"&start_date.range_start=" . $startDate .
                        "&start_date.range_end=" . $date .
                        "&expand=organizer,venue" . // for more event info like the address
                        "&token=" . $key;
                }
                else {
                    $url = "https://www.eventbriteapi.com/v3/events/search?" .
                    "location.address=" . $city .
                    //"&start_date.range_start=" . $date .
                    "&start_date.range_end=" . $date .
                    "&expand=organizer,venue" . // for more event info like the address
                    "&token=" . $key;
                }
                

                $data = callCurl($url);
            
                // i dont believe this is taking pagination into account
                // there are a LOT of events, thousands per search, so keeping it a 1 page for now
            echo $data;

            }
            break;

        // get all categories
        case "eventCategories" :

            $url = "https://www.eventbriteapi.com/v3/categories" .
            "?token=" . $key;
            
            $data = callCurl($url);
            echo $data;
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