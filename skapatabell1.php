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
    // skapar query för att skapa tabellerna
    $query = "CREATE TABLE filmer (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
        title VARCHAR(128),
        director VARCHAR(128),
        year SMALLINT,
        catID SMALLINT
    )";
    // lagrar resultat av query med skapandet i var result
    $result = $pdo->query($query);
    // en tabell skapad
    echo "Tabel filmer skapad";
?>