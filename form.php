<?php   // Huvud sida här ska hela scriptet köras
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
    echo '<h1>List of Movies</h1>';
    // raderings funktionen
    if(isset($_POST['delete']) && isset($_POST['id'])){
        $id = get_post($pdo, 'id'); // laddar in resultat från att hämta id nummer med hjälp av get_post funktionen
        $query = "DELETE FROM filmer WHERE id=$id"; // skapar query för att radera raden med samma id nr
        $result = $pdo->query($query);  // vi kör query/raderingen genom att ladda in den i result
    }
    // skapar lagring av ny filmer med hjälp av "sanering"
    // och säker inläggning av data in i databasen
    if(isset($_POST['newmovie']) && !empty($_POST['title']) &&
        !empty($_POST['director']) &&
        !empty($_POST['year'])){
        // när ovan rutorna är ifyllda då aktiveras detta block
        // vi lägger in rensad data i variabler
        $title = $_POST['title'];
        $director = $_POST['director'];
        $year = $_POST['year'];
        $catID = $_POST['catID'];   // behöver inte rensas
        // kontrollerar att det är siffror i Year rutan
        if(is_numeric($year)){
            $title = sanitizeInput($pdo, $title);
            $director = sanitizeInput($pdo, $director);
            $year = sanitizeInput($pdo, $year);
            // rensat och klar för att kalla på metod för inläggning
            addMovie($pdo, $title, $director, $year, $catID); // använder addMovie metod
            // nu är filmen inlagd
        }
    }
    // if sats för ändringar av filmer
    if(!empty($_POST['titleEdit']) &&
        !empty($_POST['directorEdit']) &&
        !empty($_POST['yearEdit']) && $_POST['new'] == 1){
        // när ovan rutorna är ifyllda då aktiveras detta block
        // vi lägger in rensad data i variablerna
        $title = $_POST['titleEdit'];
        $director = $_POST['directorEdit'];
        $year = $_POST['yearEdit'];
        $catID = $_POST['catIDEdit'];   // behöver inte rensas
        $id = $_POST['id'];
        // kontrollerar att det är siffror i Year rutan
        if(is_numeric($year)){
            $title = sanitizeInput($pdo, $title);
            $director = sanitizeInput($pdo, $director);
            $year = sanitizeInput($pdo, $year);
            // rensat och klar för att kalla på metod för inläggning
            editMovie($pdo, $title, $director, $year, $catID, $id);
            // nu är filmen ändrad
        }
    }
    // skapar första formen med hjälp END sättet
    echo <<<_END
    <form action="form.php" method="post">
        <input type="hidden" name="newmovie" value="1" ><pre>
        Title       <input type="text" name="title">
        Director    <input type="text" name="director">
        Year        <input type="text" name="year">
        Choose Category     Thriller<input type="radio" name="catID" value="1" checked='checked'>Romantic<input type="radio" name="catID" value="2">
                             Swedish<input type="radio" name="catID" value="3">Animated<input type="radio" name="catID" value="4">Comedy<input type="radio" name="catID" value="5">
        <input type="submit" value="ADD MOVIE">
        </pre></form>
    _END;
    // skapar utskrift av innehållet i tabellen filmer och category
    // hämtar från category tabellen också
    $query = "SELECT filmer.id, title, director, year, category.categorys FROM filmer, category
                WHERE filmer.catID = category.id ORDER BY title";
    $result = $pdo->query($query);
    // skapar tabellen och kolumnnamn
    echo '<table border="1" style="border-collapse: collapse">
            <tr><th>Title</th><th>Director</th><th>Year</th>
                <th>Category</th><th>Edit Movie<th>Delete Movie</th></tr>';
    // skriver ut raderna hämtade ur query inne i $result
    while($row = $result->fetch(PDO::FETCH_BOTH)){
        // ett säker omvandling av speciella tecken
        // av data i tabellen till html kod för säkerhets skull
        // i fall det har klarat sig igenom "rensningen" ändå
        $h1 = htmlspecialchars($row['title']);
        $h2 = htmlspecialchars($row['director']);
        $h3 = htmlspecialchars($row['year']);
        $h4 = htmlspecialchars($row['categorys']);
        $h5 = htmlspecialchars($row['id']);
        // skapar rader och celler med hämtad data i dem
        echo '<tr><td align="center">'.$h1.'</td>';
        echo '<td align="center">'.$h2.'</td>';
        echo '<td align="center">'.$h3.'</td>';
        echo '<td align="center">'.$h4.'</td>';
        // cell för att EDITERA
        echo "<td align='center'><form action='edit.php' method='post'>";
        echo "<input type='hidden' name='edit' value='yes'>";
        echo "<input type='hidden' name='id' value='$h5'>";
        echo "<input type='submit' value='EDIT'></form></td>";
        // cell för att RADERA
        echo "<td align='center'><form action='form.php' method='post'>";
        echo "<input type='hidden' name='delete' value='yes'>";
        echo "<input type='hidden' name='id' value='$h5'>";
        echo "<input type='submit' value='DELETE'></form></td></tr>";
    }
    // avslutar tabellen
    echo '</table>';
    // funktion för att hämta data från tabellen på webbsidan
    function get_post($pdo, $var){
        return $pdo->quote($_POST[$var]);
    }
    // skapar funktion för "rensning" av användardata som är
    // inskrivet i rutorna Title,Director,Year
    function sanitizeInput($pdo, $inp){
        // tar bort backsnedstreck
        //$inp = stripslashes($inp);
        // tar bort html taggen
        //$inp = strip_tags($inp);
        // omvandlar speciella tecken till html kod
        $inp = htmlentities($inp);
        return $inp;
    }
    // skapar säker funktion för inläggning i tabellen
    // med hjälp av prepaired statements filmer
    function addMovie($pdo, $title, $direct, $year, $cat){
        // skapar en statement för sql query för inläggning
        $stmt = $pdo->prepare('INSERT INTO filmer VALUES (NULL,?,?,?,?)');
        // binder variablerna till platser i queryn ovanför
        $stmt->bindParam(1, $title, PDO::PARAM_STR, 128);
        $stmt->bindParam(2, $direct, PDO::PARAM_STR, 128);
        $stmt->bindParam(3, $year, PDO::PARAM_INT );
        $stmt->bindParam(4, $cat, PDO::PARAM_INT );
        // queryn exekveras för att läggas in
        $stmt->execute([$title, $direct, $year, $cat]);
    }
    // skapar säker funktion för ändring i tabellen
    // med hjälp av prepaired statements filmer
    function editMovie($pdo, $title, $direct, $year, $cat, $id){
        // skapar en statement för sql query för uppdatering
        $stmt = $pdo->prepare('UPDATE filmer SET title= ?, director= ?,
                                year= ?, catID= ? WHERE id= ?');
        // binder variablerna till platser i queryn ovanför
        $stmt->bindParam(1, $title, PDO::PARAM_STR, 128);
        $stmt->bindParam(2, $direct, PDO::PARAM_STR, 128);
        $stmt->bindParam(3, $year, PDO::PARAM_INT );
        $stmt->bindParam(4, $cat, PDO::PARAM_INT );
        $stmt->bindParam(5, $id, PDO::PARAM_INT );
        //queryn exekveras för att läggas in
        $stmt->execute([$title, $direct, $year, $cat, $id]);
    }

?>