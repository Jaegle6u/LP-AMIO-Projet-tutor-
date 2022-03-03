<?php


$servername = "localhost";

// REPLACE with your Database name
$dbname = "potanet";
// REPLACE with Database user
$username = "root";
// REPLACE with Database user password
$password = "";

$first =0;
$nb_mesure=0;
//Modifier si on veut sauvegarder plus de donnée 
$nb_mesure_max=100;

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
// If you change this value, the ESP32 sketch needs to match
$api_key_value = "tPmAT5Ab3j7F96";

$api_key= $sensor = $location = $value1 = $value2  = $value3 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $sensor = test_input($_POST["sensor"]);
        $location = test_input($_POST["location"]);
        $value1 = test_input($_POST["value1"]);
        $value2 = test_input($_POST["value2"]);
        $value3 = test_input($_POST["value3"]);
        
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "INSERT INTO Sensor_Data (sensor, location, value1, value2, value3)
        VALUES ('" . $sensor . "', '" . $location . "', '" . $value1 . "', '" . $value2 . "', '" . $value3 . "')";
        



        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        /*
        $conn->real_querry("SELECT MIN(reading_time) FROM Sensor_Data");
        $first = $mysqli->use_result();

        $sqlDelete = "DELETE FROM Sensor_Data WHERE reading_time = ".$first;
        $conn->query($sqlDelete);
        */
        $conn->close();

        //Supprime plus vieux enregistrement si on dépasse la valeur definie dans $nb_mesure_max
         try
         {
            $bdd = new PDO('mysql:host='.$servername.';dbname='.$dbname.';charset=utf8', $username, $password);
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
        //On recupere nombre de mesure
        $req = $bdd->prepare('SELECT COUNT(*) FROM sensor_data');
        $req->execute();
        while ($donnée = $req->fetch())
        {
            
            $nb_mesure = $donnée[0];
        }
        if ($nb_mesure > $nb_mesure_max) {
            $req = $bdd->prepare('SELECT MIN(reading_time) FROM `Sensor_Data` WHERE 1 ');
            $req->execute();
            while ($donnée = $req->fetch())
            {
                
                $first = $donnée[0];
            }
            $req = $bdd->prepare('DELETE FROM Sensor_Data WHERE reading_time=:reading_time');
            $req->execute(array('reading_time' => $first));
        }
        
    }
    else {
        echo "Wrong API Key provided.";
    }

}







function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

    //Test api Symfony
/*
	$ch = curl_init("http://192.168.1.31:8000/api/arrosage.json/1");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);      
        curl_close($ch);
        echo $output;*/
