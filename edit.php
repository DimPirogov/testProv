<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ändra filmer sidan</title>
</head>
<body>
    <h1>Edit Movie</h1>
    <?php   // inkluderar inloggning fil med koppling till DB
    require_once 'loginDB.php';
    // kommer testa att lägga EDIT separat och i en html kodad sida
    // felhantering av koppling till DB 
    try
    {
        $pdo = new PDO($attr, $user, $pass, $opts);
    }
    catch (PDOException $e)
    {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
    // laddar uppgifter om filmen med hjälp av id från filmer tabellen
    // och skapar queryn för att laddas in i cellerna på sidan
    $id = $_REQUEST['id'];  // laddar in id variabel från form sidan
    $query = "SELECT filmer.id, title, director, year, category.categorys
                FROM filmer, category
                WHERE filmer.catID = category.id  AND filmer.id = '".$id."' ";
    $result = $pdo->query($query);  // laddar hela queryn i result variabel
    $row = $result->fetch();    // laddar queryn
    // ett säker omvandling av speciella tecken
    // av data i tabellen till html kod för säkerhetskull
    // i fall det har klarat sig igenom "rensningen" ändå
    $tit1 = htmlspecialchars($row['title']);
    $dir2 = htmlspecialchars($row['director']);
    $year3 = htmlspecialchars($row['year']);
    $cat4 = htmlspecialchars($row['categorys']);    // används inte på denna sida
    ?>
    <!-- // skapar formen med rutorna för ändringarna-->
    <form name="form" method="post" action="form.php">
        <input type="hidden" name="new" value="1" >
        <input type="hidden" name="id" value="<?php echo $id;?>" >
        <p><input type="text" name="titleEdit" placeholder="Enter Movie Title" 
            required value="<?php echo $tit1;?>" ></p>
        <p><input type="text" name="directorEdit" placeholder="Enter Movie Director" 
            required value="<?php echo $dir2;?>" ></p>
        <p><input type="text" name="yearEdit" placeholder="Enter Production Year" 
            required value="<?php echo $year3;?>" ></p>
        <p>Choose Category:</p>
        <p>Thriller<input type="radio" name="catIDEdit" value="1" checked='checked'></p>
        <p>Romantic<input type="radio" name="catIDEdit" value="2"></p>
        <p>Swedish<input type="radio" name="catIDEdit" value="3"></p>
        <p>Animated<input type="radio" name="catIDEdit" value="4"></p>
        <p>Comedy<input type="radio" name="catIDEdit" value="5"></p>
        <p><input type="submit" name="submit" value="Update Movie " ></p>
    </form>
</body>
</html>