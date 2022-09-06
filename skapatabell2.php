<?php   // vi använder oss av inkopplingsfilen
    require_once 'loginDB.php'; 
    // felhanterar uppkopplingen och vi använder det för att få ett felmeddelande
    // på orsaken eller själva felet
    // bäddar in uppkopplingen mot servern i en try catch sats
    try
    {
        $pdo = new PDO($attr, $user, $pass, $opts);
    }
    catch (PDOException $e)
    {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
    // skapar query för att skapa tabellen
    $query = "CREATE TABLE category (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
        categorys VARCHAR(128)
    )";
    // lagrar resultat av query med skapandet i var result
    $result = $pdo->query($query);
    echo "Tabell skapad";   // skriver ut på skärmen meddelandet
    // skapar ny query med att lägga in kategorierna i tabellen
    // vi skriver över den variablrna över
    $query = "INSERT INTO category VALUES
        (NULL, 'Thriller'),
        (NULL, 'Romantic'),
        (NULL, 'Swedish'),
        (NULL, 'Animated'),
        (NULL, 'Comedy')";
    $result = $pdo->query($query);
    echo "kategorierna inlagda";
?>