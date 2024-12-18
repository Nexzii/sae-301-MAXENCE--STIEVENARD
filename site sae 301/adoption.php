
<?php
include_once 'config.php';
include_once './API/filtre_api.php';

$filters = [];
if (!empty($_GET)) {
    foreach ($_GET as $key => $value) {
        if (!empty($value)) {
            $filters[$key] = $value;
        }
    }
}

$api = new AnimalAPI($pdo);
$animaux = $api->getAnimals($filters);

if (empty($animaux)) {
    echo '<p class="text-center">Aucun animal ne correspond à vos critères.</p>';
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoptez un animal à Aulnat (63) | Amis 4 Pattes</title>
    <meta name="description" content="Parcourez nos profils d'animaux à adopter à Aulnat près de Clermont-Ferrand (63). Trouvez votre futur compagnon parmi nos chiens, chats, lapins, hamsters et autres.">
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container my-4">
    <div class="row md-5 filters m-5 row justify-content-center">
        <div class="col-md-2 m-2 text-center">
            <label for="espece" class="form-label">Espèce</label>
            <select id="espece" class="form-select" onchange="updateResults();">
                <option value="">Toutes</option>
                <option value="Chat">Chat</option>
                <option value="Chien">Chien</option>
            </select>
        </div>
        <div class="col-md-2 m-2 text-center">
            <label for="race" class="form-label">Race</label>
            <select id="race" class="form-select" onchange="updateResults();">
                <option value="">Toutes</option>
            </select>
        </div>
        <div class="col-md-2 m-2 text-center">
            <label for="sexe" class="form-label">Sexe</label>
            <select id="sexe" class="form-select" onchange="updateResults();">
                <option value="">Tous</option>
                <option value="Mâle">Mâle</option>
                <option value="Femelle">Femelle</option>
            </select>
        </div>
        <div class="col-md-2 m-2 text-center">
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
</div>

        <div id="results" class="row row-cols-1 row-cols-md-3 g-4">
            <?php if (!empty($animaux)): ?>
                <?php foreach ($animaux as $animal): ?>
                    <div class="col">
                        <a href="chat.php?id=<?= htmlspecialchars($animal['id']) ?>" class="card h-100 text-decoration-none">
                            <img src="<?= htmlspecialchars($animal['photo']) ?>" class="card-img-top" alt="Photo de <?= htmlspecialchars($animal['nom']) ?>">
                            <div class="card-body">
                                <h5 class="card-title text-center"><?= htmlspecialchars($animal['nom']) ?></h5>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Aucun animal ne correspond à vos critères.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function updateResults() {
        const espece = document.getElementById('espece').value;
        const race = document.getElementById('race').value;
        const sexe = document.getElementById('sexe').value;
        const age = document.getElementById('age').value;

        const params = new URLSearchParams({ espece, race, sexe, age });

        fetch('adoption.php?' + params.toString())
            .then(response => response.text())
            .then(html => {
                document.getElementById('results').innerHTML = html;
            });
    }

    document.getElementById('espece').addEventListener('change', function () {
        const espece = this.value;
        const raceSelect = document.getElementById('race');
        const racesByEspece = {
            'Chat': ['Persan', 'Siamois', 'Maine Coon', 'Européen'],
            'Chien': ['Labrador', 'Berger Allemand', 'Golden Retriever', 'Bulldog']
        };

        raceSelect.innerHTML = '<option value="">Toutes</option>';
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
