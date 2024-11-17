<?php
// Crée la base de données SQLite
try {
    $db = new PDO('sqlite:flashcards.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "CREATE TABLE IF NOT EXISTS flashcards (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        data TEXT NOT NULL
    )";

    $db->exec($query);
    echo "La base de données et la table ont été créées avec succès !";
} catch (PDOException $e) {
    echo "Erreur lors de la création de la base de données : " . $e->getMessage();
}
?>
