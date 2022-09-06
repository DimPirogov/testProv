<?php   // här ska 2 filmer läggas till 
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
    // lägger in filmer så det finns en varje i all kategorier
    $query = "INSERT INTO filmer VALUES
            (NULL, 'Mörkt vatten', 'Rafael Edholm', 2012, 1),
            (NULL, 'Änglagård - tredje gången gillt', 'Colin Nutley', 2010, 3),
            (NULL, 'Memory', 'Martin Campbell', 2022, 1),
            (NULL, 'Toy Story', 'John Lasseter', 1999, 4),
            (NULL, 'Passengers', 'Morten Tyldum', 2016, 2),
            (NULL, 'Tropic Thunder', 'Ben Stiller', 2008, 5)";
    $result = $pdo->query($query);
?>