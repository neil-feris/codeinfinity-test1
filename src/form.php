<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>CodeInfinity Users</title>
</head>

<body>
    <!-- Your HTML content goes here -->
</body>

</html>

<?php

// create a showForm function so that we can reuse it in index as well as submit
function showForm($errorMessage, $name, $surname, $id_number, $dob)
{
    return '<form action="submit.php" method="post">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" value="' . $name . '" required><br><br>
          <label for="surname">Surname:</label>
          <input type="text" id="surname" name="surname" value="' . $surname . '" required><br><br>
          <label for="id_number">ID Number:</label>
          <input type="number" id="id_number" name="id_number" value="' . $id_number . '" required><br><br>
          <label for="dob">Date of Birth:</label>
          <input type="date" id="dob" name="dob" value="' . $dob . '" required><br><br>
          <div class="error-message">' . $errorMessage . '</div>
          <input type="submit" value="Submit">
          <input type="button" value="Cancel" onclick="location.href=\'cancel.php\'">
    </form>';
}