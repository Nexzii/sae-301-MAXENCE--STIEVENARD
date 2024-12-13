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
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container my-4">
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="espece" class="form-label">Espèce</label>
                <select id="espece" class="form-select" onchange="updateResults();">
                    <option value="">Toutes</option>
                    <option value="Chat">Chat</option>
                    <option value="Chien">Chien</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="race" class="form-label">Race</label>
                <select id="race" class="form-select" onchange="updateResults();">
                    <option value="">Toutes</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sexe" class="form-label">Sexe</label>
                <select id="sexe" class="form-select" onchange="updateResults();">
                    <option value="">Tous</option>
                    <option value="Mâle">Mâle</option>
                    <option value="Femelle">Femelle</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="age" class="form-label">Âge</label>
                <select id="age" class="form-select" onchange="updateResults();">
                    <option value="">Tous</option>
                    <option value="1">1 an</option>
                    <option value="2">2 ans</option>
                    <option value="3">3 ans</option>
                    <option value="4">4 ans</option>
                </select>
            </div>
        </div>

        <div id="results" class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($animaux as $animal): ?>
                <div class="col">
                    <a href="chat.php?id=<?= $animal['id'] ?>" class="card h-100 text-decoration-none">
                        <img src="<?= htmlspecialchars($animal['photo']) ?>" class="card-img-top" alt="Photo de <?= htmlspecialchars($animal['nom']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($animal['nom']) ?></h5>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function updateResults() {
            const espece = document.getElementById('espece').value;
            const race = document.getElementById('race').value;
            const sexe = document.getElementById('sexe').value;
            const age = document.getElementById('age').value;

            const params = new URLSearchParams({ espece, race, sexe, age});

            fetch('adoption.php?' + params.toString())
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
