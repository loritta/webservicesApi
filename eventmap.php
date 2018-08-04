<?php  ?>
<!DOCTYPE html>
<html lang="en" >

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

    <div class="eventMap">
<iframe src="https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d2798.755205608877!2d-73.63918359958498!3d45.45458882899824!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e0!4m3!3m2!1d45.4546657!2d-73.6374889!4m5!1s0x4cc9172cf1977443%3A0xe0c76647704aa460!2z0JzQvtC90YDQtdCw0LvRjCwg0JrQstC10LHQtdC6IEg0QiAxWjYsINCa0LDQvdCw0LTQsA!3m2!1d45.454341199999995!2d-73.6366194!5e0!3m2!1sen!2sca!4v1533396390671" allowfullscreen></iframe>
</div>
</body>
</html>
