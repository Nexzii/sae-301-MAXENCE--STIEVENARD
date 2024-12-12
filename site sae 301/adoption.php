<?php
include 'config.php';


// Récupération des filtres
$whereClauses = [];
$params = [];

if (!empty($_GET['espece'])) {
    $whereClauses[] = "espece = ?";
    $params[] = $_GET['espece'];
}

if (!empty($_GET['race'])) {
    $whereClauses[] = "race = ?";
    $params[] = $_GET['race'];
}

if (!empty($_GET['sexe'])) {
    $whereClauses[] = "sexe = ?";
    $params[] = $_GET['sexe'];
}

if (!empty($_GET['lieu_adoption'])) {
    $whereClauses[] = "lieu_adoption LIKE ?";
    $params[] = "%" . $_GET['lieu_adoption'] . "%";
}

if (!empty($_GET['age'])) {
    $age = (int)$_GET['age'];
    $whereClauses[] = "TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) = ?";
    $params[] = $age;
}

// Construire la requête SQL
$whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";
$query = $pdo->prepare("SELECT *, TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) AS age FROM animaux $whereSql ORDER BY created_at DESC");
$query->execute($params);

// Récupérer les résultats
$animaux = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ami 4 Pattes - Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="filters">
        <select id="espece" onchange="updateResults();">
            <option value="">Espèces</option>
            <option value="Chat">Chat</option>
            <option value="Chien">Chien</option>
        </select>
        <select id="race" onchange="updateResults();">
            <option value="">Races</option>
        </select>
        <select id="sexe" onchange="updateResults();">
            <option value="">Sexe</option>
            <option value="Mâle">Mâle</option>
            <option value="Femelle">Femelle</option>
        </select>
        <select id="age" onchange="updateResults();">
            <option value="">Âge</option>
            <option value="1">1 an</option>
            <option value="2">2 ans</option>
            <option value="3">3 ans</option>
            <option value="4">4 ans</option>
        </select>
        <input type="text" id="lieu_adoption" placeholder="Ville d'adoption" oninput="updateResults();">
    </div>

    <main>
        <h2 style="text-align: center;">Nos Animaux à l'Adoption</h2>
        <div id="results" class="grid-container">
            <?php foreach ($animaux as $animal): ?>
                <a href="chat.php?id=<?= $animal['id'] ?>" class="card">
                    <div class="image">
                        <img src="<?= htmlspecialchars($animal['photo']) ?>" alt="Photo de <?= htmlspecialchars($animal['nom']) ?>">
                    </div>
                    <div class="info">
                        <h3><?= htmlspecialchars($animal['nom']) ?></h3>
                        <p>Âge : <?= htmlspecialchars($animal['age']) ?> an(s)</p>
                        <p>Lieu : <?= htmlspecialchars($animal['lieu_adoption']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>

    <script>
        function updateResults() {
            const espece = document.getElementById('espece').value;
            const race = document.getElementById('race').value;
            const sexe = document.getElementById('sexe').value;
            const age = document.getElementById('age').value;
            const lieuAdoption = document.getElementById('lieu_adoption').value;

            const params = new URLSearchParams({ espece, race, sexe, age, lieu_adoption: lieuAdoption });

            fetch('index.php?' + params.toString())
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newResults = doc.querySelector('#results');
                    document.getElementById('results').innerHTML = newResults.innerHTML;
                });
        }

        document.getElementById('espece').addEventListener('change', function () {
            const espece = this.value;
            const raceSelect = document.getElementById('race');
            const racesByEspece = {
                'Chat': ['Persan', 'Siamois', 'Maine Coon', 'Européen'],
                'Chien': ['Labrador', 'Berger Allemand', 'Golden Retriever', 'Bulldog']
            };

            raceSelect.innerHTML = '<option value="">Races</option>';
            if (racesByEspece[espece]) {
                racesByEspece[espece].forEach(race => {
                    const option = document.createElement('option');
                    option.value = race;
                    option.textContent = race;
                    raceSelect.appendChild(option);
                });
            }

            updateResults();
        });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
