
<?php

// First, setup the variables you will use on your <iframe> code
// Your Iframe will need a Width and Height set
// as well as the address you plan to Iframe
// Don't forget to get a Google Maps API key


//This has to be changed according to the EventBrite API
$base_url =  "https://www.eventbriteapi.com/v3";
$categories = fetch_curl($base_url . 'categories');
$city = fetch_curl($base_url.'city');
$date = fetch_curl($base_url.'date');

//need to verify and adjust the code
$city_search = ($_SERVER['REQUEST_METHOD'] == "POST") ? $_POST['city'] : "";


$latitude = '';
$longitude = '';
$iframe_width = '100vw';
$iframe_height = '80vh';
$address = '2086 Westmore ave., Montreal, Quebec';
$address = urlencode($address);
$key = "AIzaSyCgNEko9ehJ_d79NeRbZIPx5r0nX3NyeGE";
$url = "http://maps.google.com/maps/geo?q=".$address."&output=json&key=".$key;
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
// Comment out the line below if you receive an error on certain hosts that have security restrictions
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$data = curl_exec($ch);
curl_close($ch);

$geo_json = json_decode($data, true);

// Uncomment the line below to see the full output from the API request
// var_dump($geo_json);

// If the Json request was successful (status 200) proceed
//if ($geo_json['Status']['code'] == '200') {

$latitude = $geo_json['Placemark'][0]['Point']['coordinates'][0];
$longitude = $geo_json['Placemark'][0]['Point']['coordinates'][1];
$iframe ='<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="
http://maps.google.com/maps
?f=q
&amp;source=s_q
&amp;hl=en
&amp;geocode=
&amp;q='.$address.'&amp;aq=0
&amp;ie=UTF8
&amp;hq=
&amp;hnear='.$address.'&amp;t=m
&amp;ll='.$longitude.','.$latitude.'&amp;z=12
&amp;iwloc=
&amp;output=embed"></iframe>';
?>
<head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="main.css" />
   </head>
<body>
    <div class="searchCity">
        <form action="actionMapEvent.php">
            <table>
  <tr>
    <td>
        <label for="city">Please enter the city:</label>
    </td>
    <td>
        <input type="text" name="city">
    </td>
  </tr>
  <tr>
    <td>
        <label for="date">Please choose the category:</label>
    </td>
    <td>
        <select name="event">
            <option value="">---</option>
            <?php foreach ($categories as $category){ ?>
                <option value="<?= $category; ?>" <?= $eventSearch==$category?"selected":""; ?> ><?=$category; ?></option>
            <?php }//endloop categories dropdown ?>
        </select>
    </td>
  </tr>
  <tr>
    <td>
        <label for="date">Please enter the date:</label>
    </td>
    <td>
        <input type="date" name="date">
    </td>
  </tr>
  <tr>
      <td>
      <input type="submit" value="Search">
  </td>
  </tr>
</table>
        </form>
    </div>

    <div id="eventMap">
        <? echo $iframe?>
        </div>

</body>
</html>


<?php
//need to be adjusted according to the search option we want
if ($_SERVER['REQUEST_METHOD'] == "POST"){

  //check that category is in the list
  if (in_array($cat_search, $categories)){
    // request a random fact for selected category
    $chuck = fetch_curl($base_url . 'random?category='.$_POST['cat']);

    //display category as title if there is a category
    if (!empty($chuck->category)){
      echo "<h3>";
      foreach ($chuck->category as $i =>$c) echo ($i!=0?", ":"") . ucfirst($c);
      echo "</h3>";
    } //endif !empty categories

    //display information about the random fact
    echo "<img src='$chuck->icon_url' style='float:left' alt='Chuck Norris Facts' />";
    echo $chuck->value;

  } //endif in_array()

}//endif POST request method
?>
