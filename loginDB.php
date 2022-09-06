<?php   // inloggning in i lokala mysql databasen
    $host = 'localhost';    // variabeln med ip addressen som databasen är på
    $data = 'praktprov';    // var med namn på databasen att kopplas till
    $user = 'prov';         // var med användarnamnet för inloggning
    $pass = 'praktiskt';    // var med lösenordet för inloggninen
    $chrs = 'utf8mb4';      // var för teckenkodning
    // var för inkopplings strängen
    $attr = "mysql:host=$host;dbname=$data;charset=$chrs";
    // ytterliggare var för inställningar för uppkoppling till DB
    $opts = 
    [
        PDO::ATTR_ERRMODE                   => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE        => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES          => false,
    ];

?>