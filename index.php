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
    <meta name="description" content="Créez gratuitement vos flashcards interactives pour un apprentissage efficace. Partagez facilement vos cartes mémoire pour réviser et apprendre.">
    <meta name="keywords" content="flashcards, cartes mémoire, apprentissage, révisions, éducation, mémorisation, Trisout, Trisout Flashcards">
    <meta name="author" content="Flashcards Interactives">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://flashcards.trisout.fr/">
    <meta property="og:title" content="Flashcards Interactives - Créez vos cartes mémoire">
    <meta property="og:description" content="Créez et partagez vos flashcards pour un apprentissage efficace">
    <meta property="og:image" content="https://flashcards.trisout.fr/images/flashcards.png">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://flashcards.trisout.fr/">
    <meta name="twitter:title" content="Flashcards Interactives - Créez vos cartes mémoire">
    <meta name="twitter:description" content="Créez et partagez vos flashcards pour un apprentissage efficace">
    <meta name="twitter:image" content="https://flashcards.trisout.fr/images/flashcards.png">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://flashcards.trisout.fr/index.php">

    <link rel="stylesheet" href="styleindex.css">
    <title>Flashcards Interactives - Créez et partagez vos cartes mémoire | Apprentissage efficace</title>

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "Flashcards Interactives",
        "description": "Application de création et partage de flashcards pour l'apprentissage",
        "url": "https://flashcards.trisout.fr",
        "applicationCategory": "EducationalApplication",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "EUR"
        }
    }
    </script>
</head>
<body>
    <div class="container">
        <h1>Créer des Flashcards</h1>
        <p>1 carte par ligne.</p>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
        <form method="POST" action="index.php">
            <textarea name="flashcards" placeholder="Exemple : Bonjour = Hello" required></textarea>
            <button type="submit">Enregistrer</button>
        </form>
        <div class="new-version">
            <h2>Découvrez notre nouvelle version du site, basée sur digiflashcards !</h2>
            <p>Testez les toutes dernières fonctionnalités et profitez d'une expérience améliorée. Ne manquez pas cette opportunité de rendre votre apprentissage encore plus interactif et efficace.</p>
            <button onclick="window.location.href='/new'">Tester maintenant</button>
        </div>
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
