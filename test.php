
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- bootstrap cdns -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js" integrity="sha384-pjaaA8dDz/5BgdFUPX6M/9SUZv4d12SUPF0axWc+VRZkx5xU3daN+lYb49+Ax+Tl" crossorigin="anonymous"></script>

    
    <!-- jQuery -->
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
    </script>

    <!-- moment js for date formatting (not used)
    <script src="scripts/moment.js"></script>
    -->

    <!-- google maps script -->
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgNEko9ehJ_d79NeRbZIPx5r0nX3NyeGE">
        // really should not hard code the key here
    </script>

    <!-- temporary location for these styles -->
    <style>
        .logoImage {
            width: 100%;
            height: 150px;
        }
    </style>
</head>

<body>
    <div class="container">

        <h1>Testing Event Brite API</h1>

        <h2>Select a City and Date</h2>

        <p class="lead">Ongoing events during the selected date will be shown</p>

        <label for="selCity">Select a City</label>
        <select id="selCity" name="selCity">
            <option value="">Please Select a City</option>
            <option value="Montreal">Greater Montreal</option>
            <option value="Toronto">Toronto</option>
        </select>
        
        <br />

        <label for="date">Select a Date</label>
        <input type="date" name="date" id="selDate">

        <br />

        <label for="selCity">Select a Category (optional)</label>
        <select id="selCat" name="selCat">
            <option value="">See Categories</option>
        </select>

        <br />
        
        <a href="" class="btn btn-primary" id="btnSearch">Search</a>

        <p class="lead">Quick Search</p>

        <a href="" class="btn btn-light" id="btnWeekEvents">See events this week</a>
        <a href="" class="btn btn-light" id="btnMonthEvents">See events this month</a>

        <!-- map container -->
        <div id="map" style="height: 700px; width: 100%;">

        <!-- table for testing 
        <table class="table" id="content">
            <tr>
                <th>Event Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>City</th>
                <th>Address</th>
            </tr>
            
        </table>
        -->
    </div>
    

    <script>
        $( document ).ready(function() {

            var baseUrl = "https://www.eventbriteapi.com/v3/events/search?";

            loadCategories();
            
            $( "#btnWeekEvents" ).click(function(event) {
            
                if ( $("#selCity").val() == "") {
                    alert("Select a City");
                    return;
                }

                event.preventDefault();

                var city = $("#selCity").val();

                $.ajax({
                    url: 'eventBriteRequests.php',
                    type: 'POST', // should probably be GET but coulndt get it to work with that yet
                    dataType: "json",
                    data: {
                        action: "weekEvents",
                        selectedCity: city,
                        //category : category add this later
                    } 
                })
                .done(function(data){
                        
                        var results = JSON.parse(data);

                        var locations = [];

                        for (var i = 0; i < results.events.length; i++) {

                            locations[i] = [
                                results.events[i].name.text ,
                                results.events[i].venue.latitude,
                                results.events[i].venue.longitude,
                                results.events[i].url, // the url for the event
                                //results.events[i].logo.url,
                                ];
                        }
                        
                        // center the map around the chosen city, yes its hard coded
                        if (city == "Montreal") center = [45.5017, -73.5673];
                        if (city == "Toronto") center = [43.6532, -79.3832];
                        initMap(locations, center);
                }); 
            });

            $( "#btnMonthEvents" ).click(function(event) {
            
                if ( $("#selCity").val() == "") {
                    alert("Select a City");
                    return;
                }

                event.preventDefault();

                var city = $("#selCity").val();

                $.ajax({
                    url: 'eventBriteRequests.php',
                    type: 'POST', // should probably be GET but coulndt get it to work with that yet
                    dataType: "json",
                    data: {
                        action: "monthEvents",
                        selectedCity: city,
                        //category : category add this later
                    } 
                })
                .done(function(data){
                        
                        var results = JSON.parse(data);

                        var locations = [];

                        for (var i = 0; i < results.events.length; i++) {

                            locations[i] = [
                                results.events[i].name.text ,
                                results.events[i].venue.latitude,
                                results.events[i].venue.longitude,
                                results.events[i].url, // the url for the event
                                //results.events[i].logo.url,
                                ];
                        }
                        
                        // center the map around the chosen city, yes its hard coded
                        if (city == "Montreal") center = [45.5017, -73.5673];
                        if (city == "Toronto") center = [43.6532, -79.3832];
                        initMap(locations, center);
                }); 
            });

            $( "#btnSearch" ).click(function(event) {
            
                event.preventDefault(); // to stop the page from refreshing after the click

                if ( $("#selCity").val() == "") {
                    alert("Select a City");
                    return;
                }
                if ( $("#selDate").val() == "") {
                    alert("Select a Date");
                    return;
                }

                var category = "";
                // if a category is selected
                if ( $("#selCat").val() != "") {
                    category = $("#selCat").val();
                }
                

                var city = $("#selCity").val();
                var dateValue = $("#selDate").val();
                // hard coding since were not taking time into account and this is the format the API wants
                var date = dateValue + "T00:00:00";

               $.ajax({
                    url: 'eventBriteRequests.php',
                    type: 'POST', // should probably be GET but coulndt get it to work with that yet
                    dataType: "json",
                    data: {
                        action: "searchEvents",
                        selectedCity: city,
                        selectedDate: date,
                        category : category
                    }
                }).done(function(data){
                        
                        var results = JSON.parse(data);

                        var locations = [];

                        for (var i = 0; i < results.events.length; i++) {

                            locations[i] = [
                                results.events[i].name.text ,
                                results.events[i].venue.latitude,
                                results.events[i].venue.longitude,
                                results.events[i].url, // the url for the event
                                //results.events[i].logo.url,
                                ];
                        }
                        
                        // center the map around the chosen city, yes its hard coded
                        if (city == "Montreal") center = [45.5017, -73.5673];
                        if (city == "Toronto") center = [43.6532, -79.3832];
                        initMap(locations, center);
                }); 
            });

            // test map function
            function initMap(locations, center) {
                
                /*
                var locations = [
                    ['Bondi Beach', -33.890542, 151.274856, 4],
                    ['Coogee Beach', -33.923036, 151.259052, 5],
                    ['Cronulla Beach', -34.028249, 151.157507, 3],
                    ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
                    ['Maroubra Beach', -33.950198, 151.259302, 1]
                ];
                */

                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: new google.maps.LatLng(center[0], center[1]),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var infowindow = new google.maps.InfoWindow();

                var marker, i;

                for (i = 0; i < locations.length; i++) { 
                    
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                        map: map
                    });

                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            // this is what shows up when the map marker is clicked
                            // locations[3] is the url to the event, locations [0] is the event name (defined above)
                            // locations[4] is the image url for the logo
                            infowindow.setContent(
                                // for some extremely strange reasons, when searching montreal no logo
                                // is returned, i have no idea why.
                                //"<img src='" + locations[i][4] + "' class='logoImage' /> <br />" + 
                                "<a href='" + locations[i][3] + "'>" + locations[i][0] +
                                "</a>");
                                                
                            infowindow.open(map, marker);

                        }

                    })
                    (marker, i)); // no idea what this line is
                }
            }

            function loadCategories() {
                // load categories when page is loaded             
                $.ajax({
                    url: 'eventBriteRequests.php',
                    type: 'POST', 
                    dataType: "json",
                    data: {
                        action: "eventCategories",
                    }
                })
                .done(function(data){
                            
                    var results = JSON.parse(data);

                    for (var i = 0; i < results.categories.length; i++) {
                        $("#selCat").append("<option value='" + results.categories[i].id + "'>" +
                        results.categories[i].name + "</option>");
                    }

                }); 
            }
        });
        
    </script>


</body>
</html>