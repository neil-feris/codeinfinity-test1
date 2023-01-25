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
require '../vendor/autoload.php';
require 'form.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::create(__DIR__, '../.env');
$dotenv->load();

// Get MongoDB Atlas connection string from .env file
$uri = getenv('MONGODB_URI');
$db_name = 'codeinfinity';
$collection_name = 'users';

// Connect to MongoDB Atlas
$client = new MongoDB\Client($uri);

// Select a database
$db = $client->$db_name;

// Select a collection
$collection = $db->$collection_name;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // form data
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $id_number = $_POST['id_number'];
    $dob = $_POST['dob'];


    // Check that there is valid data in the name and surname fields and no  characters that can cause a record not to be inputted into the database.
    if (!preg_match("/^[a-zA-Z ]*$/", $name) || !preg_match("/^[a-zA-Z ]*$/", $surname)) {
        // Repopulate the form with error message
        echo showForm("Only letters and white space allowed!", $name, $surname, $id_number, $dob);
        exit();
    }

    // Check that id_number is a number
    if (!is_numeric($id_number)) {
        // Repopulate the form with error message
        echo showForm("ID number must be a number!", $name, $surname, $id_number, $dob);
        exit();
    }

    // Check that id_number is exactly 13 digits
    if (strlen($id_number) != 13) {
        // Repopulate the form with error message
        echo showForm("ID number must be 13 digits!", $name, $surname, $id_number, $dob);
        exit();
    }

    // Check that the first 6 digits of the ID number are a valid date
    $year = substr($id_number, 0, 2);
    $month = substr($id_number, 2, 2);
    $day = substr($id_number, 4, 2);

    if (!checkdate($month, $day, $year)) {
        // Repopulate the form with error message
        echo showForm("ID number is not valid!", $name, $surname, $id_number, $dob);
        exit();
    }

    // Check that the year, month and day are the same as the date of birth
    $dob_year = substr($dob, 2, 2); // get the last 2 digits of the year

    $dob_month = substr($dob, 5, 2);
    $dob_day = substr($dob, 8, 2);

    if ($year != $dob_year || $month != $dob_month || $day != $dob_day) {
        // Repopulate the form with error message
        echo showForm("ID number and date of birth do not match!", $name, $surname, $id_number, $dob);
        exit();
    }

    // Check if the ID number already exists in the collection
    $find_result = $collection->findOne(['id_number' => $id_number]);

    if ($find_result) {
        // ID number already exists in the collection
        // Repopulate the form with error message
        echo showForm("ID number already exists!", $name, $surname, $id_number, $dob);

    } else {
        // ID number does not exist in the collection
        // Insert data into the collection
        $insert_result = $collection->insertOne([
            'name' => $name,
            'surname' => $surname,
            'id_number' => $id_number,
            'dob' => $dob
        ]);

        if ($insert_result->getInsertedCount()) {
            // Data inserted successfully send and alert and redirect to index.php
            echo '<script>alert("Data inserted successfully");setTimeout(function(){window.location.href = "index.php";}, 1);</script>';
        } else {
            // Error inserting data
            echo "Error inserting data";
        }
    }
} else {
    echo showForm("", "", "", "", "");
}

?>