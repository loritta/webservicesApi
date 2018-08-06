
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Event's map</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="main.css" />

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
    <!--
    <style>
        .logoImage {
            width: 100%;
            height: 150px;
        }
    </style>
    -->
</head>

<body>
<section>
        <div class="searchCity">
        <h1>Testing Event Brite API with Google Maps API</h1>
        <p class="lead">Ongoing events during the selected date will be shown</p>
        <table>
        <tr>
        <td>
        <label for="city">Choose a city: </label>
        </td>
        <td>
            <select id="selCity">
                <option value="">Please Select a City</option>
                <option value="Montreal">Greater Montreal</option>
                <option value="Toronto">Toronto</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <label for="date">Select a Date: </label>
        </td>

        <td>
            <input type="date" name="date" id="selDate">
        </td>
        <td>
        <a href="" class="btn btn-light" id="btnWeekEvents">See events this week</a>
        </td>
        <td>
            <a href="" class="btn btn-light" id="btnMonthEvents">See events this month</a>
        </td>

    </tr>
    <tr>
        <td>
            <label for="selCity">Select a Category (optional): </label>
        </td>

        <td>
            <select id="selCat" name="selCat">
                <option value="">Please Select a Category</option>
            </select>
        </td>

    </tr>
    <tr>

        <td>
            <a href="" class="btn btn-primary" id="btnSearch">Search</a>
            <span class="lead">Quick Search</span>
        </td>

    </tr>
    </table>
</div>
        <!-- map container -->
        <div id="map">

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
</section>

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

                            if (results.events[i].logo == null) {
                                locations[i] = [
                                    results.events[i].name.text,
                                    results.events[i].venue.latitude,
                                    results.events[i].venue.longitude,
                                    results.events[i].url, // the url for the event
                                    "", // this is to save headaches
                                    results.events[i].start.local, // start date
                                    results.events[i].end.local, // end date
                                    results.events[i].description.text
                                ];
                            }
                            else {
                                locations[i] = [
                                    results.events[i].name.text,
                                    results.events[i].venue.latitude,
                                    results.events[i].venue.longitude,
                                    results.events[i].url,
                                    results.events[i].logo.url,
                                    results.events[i].start.local, // start date
                                    results.events[i].end.local, // end date
                                    results.events[i].description.text
                                ];
                            }
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

                            if (results.events[i].logo == null) {
                                locations[i] = [
                                    results.events[i].name.text,
                                    results.events[i].venue.latitude,
                                    results.events[i].venue.longitude,
                                    results.events[i].url, // the url for the event
                                    "", // this is to save headaches
                                    results.events[i].start.local, // start date
                                    results.events[i].end.local, // end date
                                    results.events[i].description.text
                                ];
                            }
                            else {
                                locations[i] = [
                                    results.events[i].name.text,
                                    results.events[i].venue.latitude,
                                    results.events[i].venue.longitude,
                                    results.events[i].url,
                                    results.events[i].logo.url,
                                    results.events[i].start.local, // start date
                                    results.events[i].end.local, // end date
                                    results.events[i].description.text
                                ];
                            }
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


                            if (results.events[i].logo == null) {
                                locations[i] = [
                                    results.events[i].name.text,
                                    results.events[i].venue.latitude,
                                    results.events[i].venue.longitude,
                                    results.events[i].url, // the url for the event
                                    "", // this is to save headaches
                                    results.events[i].start.local, // start date
                                    results.events[i].end.local, // end date
                                    results.events[i].description.text
                                ];
                            }
                            else {
                                locations[i] = [
                                    results.events[i].name.text,
                                    results.events[i].venue.latitude,
                                    results.events[i].venue.longitude,
                                    results.events[i].url,
                                    results.events[i].logo.url,
                                    results.events[i].start.local, // start date
                                    results.events[i].end.local, // end date
                                    results.events[i].description.text
                                ];
                            }

                        }

                        // center the map around the chosen city, yes its hard coded
                        if (city == "Montreal") center = [45.5017, -73.5673];
                        if (city == "Toronto") center = [43.6532, -79.3832];
                        initMap(locations, center);
                });
            });

            // initialize the map
            function initMap(locations, center) {

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
                            // locations[5] and [6] are the start and end dates
                            // the last element in the array is the event description

                            var startDateObj = new Date(locations[i][5]);
                            var startDate = startDateObj.toDateString();

                            var endDateObj = new Date(locations[i][6]);
                            var endDate = endDateObj.toDateString();

                            var description = locations[i][locations[i].length-1];
                            // because the descriptions are way too long.
                            if (description.length > 200) {
                                description = description.substring(0, 500) + "...";
                            }

                            // if theres no logo
                            if (locations[i][4] == "") {
                                infowindow.setContent(

                                "<div class='container-fluid text-center'>" +
                                "<p class='lead m-3'><a href='" + locations[i][3] + "'>" + locations[i][0] +
                                "</a></p>" +
                                "<p>" + description + "</p>" +
                                "<p>Start Date: " + startDate + "</p>" +
                                "<p>End Date: " + endDate + "</p>" +
                                "</div>");
                            }
                            else {
                                infowindow.setContent(
                                "<div class='container-fluid text-center'>" +
                                "<img src='" + locations[i][4] + "' class='img-fluid' /> <br />" +
                                "<p class='lead m-3'><a href='" + locations[i][3] + "'>" + locations[i][0] +
                                "</a><p>" + description + "</p>" +
                                "<p>Start Date: " + startDate + "</p>" +
                                "<p>End Date: " + endDate + "</p>" +
                                "</div>");
                            }

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
