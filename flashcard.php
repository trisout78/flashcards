<?php
if (!isset($_GET['id'])) {
    echo "ID de flashcard manquant.";
    exit;
}

$id = intval($_GET['id']);
try {
    $db = new PDO('sqlite:flashcards.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("SELECT data FROM flashcards WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $flashcard = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$flashcard) {
        echo "Flashcard non trouvée.";
        exit;
    }

    $flashcards = json_decode($flashcard['data'], true);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashcards Interactives</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" id="flashcardsContainer">
        <h1>Flashcards Interactives</h1>
        <h2>Vos Flashcards</h2>
        <div class="flashcard" id="flashcard"></div>
        <div class="navigation">
            <button onclick="prevCard()">⬅️ Précédent</button>
            <button onclick="nextCard()">Suivant ➡️</button>
        </div>
        <div class="navigation">
            <button onclick="shuffleCards()">🔀 Mélanger</button>
            <button onclick="toggleDirection()">🔄 Changer le sens</button>
        </div>
        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>
    </div>

    <script>
        const flashcards = <?php echo json_encode($flashcards); ?>;
        let currentCardIndex = 0;
        let showTermFirst = true;
        let keyPressed = false;

        function displayCard() {
            const flashcardElement = document.getElementById('flashcard');
            const card = flashcards[currentCardIndex];
            flashcardElement.classList.remove('answer');

            if (showTermFirst) {
                flashcardElement.textContent = card.term;
            } else {
                flashcardElement.textContent = card.definition;
                flashcardElement.classList.add('answer');
            }

            flashcardElement.onclick = () => {
                if (flashcardElement.textContent === card.term) {
                    flashcardElement.textContent = card.definition;
                    flashcardElement.classList.add('answer');
                } else {
                    flashcardElement.textContent = card.term;
                    flashcardElement.classList.remove('answer');
                }
            };
        }

        function prevCard() {
            currentCardIndex = (currentCardIndex - 1 + flashcards.length) % flashcards.length;
            updateProgressBar();
            displayCard();
        }

        function nextCard() {
            currentCardIndex = (currentCardIndex + 1) % flashcards.length;
            updateProgressBar();
            displayCard();
        }

        function shuffleCards() {
            for (let i = flashcards.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [flashcards[i], flashcards[j]] = [flashcards[j], flashcards[i]];
            }
            currentCardIndex = 0;
            updateProgressBar();
            displayCard();
        }

        function toggleDirection() {
            showTermFirst = !showTermFirst;
            displayCard();
        }

        function updateProgressBar() {
            const progress = ((currentCardIndex + 1) / flashcards.length) * 100;
            document.getElementById('progressBar').style.width = `${progress}%`;
        }

        function handleKeyDown(event) {
            if (keyPressed) return;
            keyPressed = true;
            switch (event.key) {
                case 'ArrowLeft':
                    prevCard();
                    break;
                case 'ArrowRight':
                    nextCard();
                    break;
                case ' ':
                case 'Enter':
                    const flashcardElement = document.getElementById('flashcard');
                    const card = flashcards[currentCardIndex];
                    if (flashcardElement.textContent === card.term) {
                        flashcardElement.textContent = card.definition;
                        flashcardElement.classList.add('answer');
                    } else {
                        flashcardElement.textContent = card.term;
                        flashcardElement.classList.remove('answer');
                    }
                    break;
            }
        }

        function handleKeyUp() {
            keyPressed = false;
        }

        document.addEventListener('keydown', handleKeyDown);
        document.addEventListener('keyup', handleKeyUp);

        displayCard();
    </script>
</body>
</html>
