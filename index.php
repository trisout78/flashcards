<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = trim($_POST['flashcards']);
    $lines = explode("\n", $input);
    $flashcards = [];

    foreach ($lines as $line) {
        [$term, $definition] = array_map('trim', explode('=', $line));
        if ($term && $definition) {
            $flashcards[] = ['term' => $term, 'definition' => $definition];
        }
    }

    if (!empty($flashcards)) {
        try {
            $db = new PDO('sqlite:flashcards.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $data = json_encode($flashcards);
            $query = "INSERT INTO flashcards (data) VALUES (:data)";
            $stmt = $db->prepare($query);
            $stmt->execute([':data' => $data]);

            $lastId = $db->lastInsertId();
            $link = "https://flashcards.trisout.fr/flashcard.php?id=$lastId";
            $message = "Flashcards ajoutées avec succès ! <br>
                        <div class='link-container'>
                            <input type='text' value='$link' id='flashcard-link' readonly>
                            <button onclick='copyLink()'>Copier le lien</button>
                        </div>";
        } catch (PDOException $e) {
            $message = "Erreur lors de l'insertion : " . $e->getMessage();
        }
    } else {
        $message = "Format incorrect. Veuillez vérifier vos données.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleindex.css">
    <title>Flashcards Interactives</title>
</head>
<body>
    <div class="container">
        <h1>Créer des Flashcards</h1>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
        <form method="POST" action="index.php">
            <textarea name="flashcards" placeholder="Exemple : Bonjour = Hello" required></textarea>
            <button type="submit">Enregistrer</button>
        </form>
    </div>
    <script>
        function copyLink() {
            var copyText = document.getElementById("flashcard-link");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            alert("Lien copié: " + copyText.value);
        }
    </script>
</body>
</html>
