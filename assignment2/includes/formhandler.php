<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first-name"];
    $last_name = $_POST["last-name"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $city_or_town = $_POST["city-town"];
    $state = $_POST["state"];
    $postcode = $_POST["postal-code"];
    $phone_num = $_POST["phone-number"];
    $org = $_POST["volunteer"];


    try {
        require_once "dbh_inc.php";

        $query = "INSERT INTO volunteer_information (first_name, last_name, email, street_address, city_or_town, state, postcode, phone_num, organization) 
        VALUES (?,?,?,?,?,?,?,?,?);";

        $stmt = $pdo->prepare($query);

        $stmt->execute([$first_name, $last_name, $email, $address, $city_or_town, $state, $postcode, $phone_num, $org]);

        $pdo = null;
        $stmt = null;

        header("Location : ../index.php");

        die();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location : ../index.php");
}