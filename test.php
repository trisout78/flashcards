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
    <title>Test de Flashcards</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .correct { color: green; }
        .incorrect { color: red; }
    </style>
</head>
<body>
    <div class="container" id="flashcardsContainer">
        <h1>Test de Flashcards</h1>
        <h2>Vos Flashcards</h2>
        <div class="flashcard" id="flashcard"></div>
        <div class="input-group">
            <input type="text" id="userInput" placeholder="Votre réponse">
            <button onclick="checkAnswer()">Valider</button>
        </div>
        <div class="navigation">
            <button onclick="prevCard()">⬅️ Précédent</button>
            <button onclick="nextCard()">Suivant ➡️</button>
        </div>
        <div class="navigation">
            <button onclick="shuffleCards()">🔀 Mélanger</button>
            <button onclick="toggleDirection()">🔄 Changer le sens</button>
            <button onclick="toggleRandomDirection()">🔀 Sens aléatoire</button>
        </div>
        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>
    </div>

    <script>
        const flashcards = <?php echo json_encode($flashcards); ?>;
        let currentCardIndex = 0;
        let showTermFirst = true;
        let randomDirection = false;

        function displayCard() {
            const flashcardElement = document.getElementById('flashcard');
            const card = flashcards[currentCardIndex];
            flashcardElement.classList.remove('answer');

            if (randomDirection) {
                showTermFirst = Math.random() >= 0.5;
            }

            if (showTermFirst) {
                flashcardElement.textContent = card.term;
            } else {
                flashcardElement.textContent = card.definition;
                flashcardElement.classList.add('answer');
            }
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

        function toggleRandomDirection() {
            randomDirection = !randomDirection;
            if (randomDirection) {
                document.querySelector("button[onclick='toggleRandomDirection()']").classList.add('active');
            } else {
                document.querySelector("button[onclick='toggleRandomDirection()']").classList.remove('active');
            }
            displayCard();
        }

        function updateProgressBar() {
            const progress = ((currentCardIndex + 1) / flashcards.length) * 100;
            document.getElementById('progressBar').style.width = `${progress}%`;
        }

        function checkAnswer() {
            const userInput = document.getElementById('userInput').value.trim();
            const flashcardElement = document.getElementById('flashcard');
            const card = flashcards[currentCardIndex];
            const correctAnswer = showTermFirst ? card.definition : card.term;
            const result = compareAnswers(userInput, correctAnswer);
            flashcardElement.innerHTML = result;
        }

        function compareAnswers(userInput, correctAnswer) {
            userInput = userInput.toLowerCase();
            correctAnswer = correctAnswer.toLowerCase();
            let result = '';

            for (let i = 0; i < correctAnswer.length; i++) {
                let char = correctAnswer[i] === ' ' ? '&nbsp;' : correctAnswer[i];
                if (i < userInput.length && userInput[i] === correctAnswer[i]) {
                    result += `<span class="correct">${char}</span>`;
                } else {
                    result += `<span class="incorrect">${char}</span>`;
                }
            }

            return result;
        }

        displayCard();
    </script>
    <footer class="footer">
        Made with ❤️ by Trisout (Tristan)
    </footer>
</body>
</html>