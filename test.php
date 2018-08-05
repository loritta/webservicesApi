

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

    <!-- moment js for date formatting -->
    <script src="scripts/moment.js"></script>
</head>

<body>
    <div class="container">

        <h1>Testing Event Brite API</h1>

        <h2>Select a City and Date</h2>

        <select id="selCity">
            <option value="">Please Select a City</option>
            <option value="Montreal">Greater Montreal</option>
            <option value="Toronto">Toronto</option>
        </select>

        <label for="date">Select a Date</label>
        <input type="date" name="date" id="selDate">

        <a href="" class="btn btn-primary" id="btnSearch">Search</a>

        <table class="table" id="content">
            <tr>
                <th>Event Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>City</th>
                <th>Address</th>
            </tr>
            
        </table>
        
    </div>
    

    <script>
        $( document ).ready(function() {

            var baseUrl = "https://www.eventbriteapi.com/v3/events/search?";


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

                var city = $("#selCity").val();
                var dateValue = $("#selDate").val();
                // hard coding since were not taking time into account
                var date = dateValue + "T00:00:00";

               $.ajax({
                    url: 'eventBriteRequests.php',
                    type: 'POST', // should probably be GET but coulndt get it to work with that yet
                    dataType: "json",
                    data: {
                        action: "searchEvents",
                        selectedCity: city,
                        selectedDate: date
                    }
                }).done(function(data){
                        
                        var results = JSON.parse(data);

                        for (var i = 0; i < results.events.length; i++) {

                            var eventURL = results.events[i].url;
                            var eventName = "<td><a href='" + eventURL + 
                                            "'>" + results.events[i].name.text + "</a></td>";
                            
                            var formattedStart = moment(results.events[i].start.local).format('LL');
                            var formattedEnd = moment(results.events[i].start.local).format('LL');
                            
                            var eventStart = "<td>" + formattedStart + "</td>";
                            var eventEnd = "<td>" + formattedEnd + "</td>";

                            var eventCity = "<td>" + results.events[i].venue.address.city + "</td>";
                            var eventAddress = "<td>" + results.events[i].venue.address.address_1 + "</td>";

                            $("#content").append("<tr>" + eventName + eventStart + eventEnd + 
                                eventCity + eventAddress + "</tr>");
                        }
                        
                }); 
            });

        });
    </script>
</body>
</html>