<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoptez un animal à Aulnat (63) | Amis 4 Pattes</title>
    <meta name="description" content="Parcourez nos profils d'animaux à adopter à Aulnat près de Clermont-Ferrand (63). Trouvez votre futur compagnon parmi nos chiens, chats, lapins, hamsters et autres.">
    <link rel="stylesheet" href="Style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Header -->

    <?php include 'header.php'; ?>

    <!-- Filtres -->
    <div class="filters" style="display: flex; justify-content: center; margin: 20px 0; gap: 10px;">
        <select id="espece">
            <option value="">Espèce</option>
            <option value="Chat">Chat</option>
            <option value="Chien">Chien</option>
        </select>
        <select id="race">
            <option value="">Race</option>
            <option value="Maine Coon">Maine Coon</option>
            <option value="Berger">Berger</option>
        </select>
        <select id="sexe">
            <option value="">Sexe</option>
            <option value="M">Mâle</option>
            <option value="F">Femelle</option>
        </select>
        <select id="age">
            <option value="">Âge</option>
            <option value="1">1 an</option>
            <option value="2">2 ans</option>
        </select>
    </div>

    <!-- Conteneur des animaux -->
    <div id="contenu-animaux" class="grid-container"></div>

    <!-- Footer -->
    
    <?php include 'footer.php'; ?>

    <!-- Script AJAX -->
    <script>
        $(document).ready(function() {
            function chargerAnimaux() {
                const espece = $('#espece').val();
                const race = $('#race').val();
                const sexe = $('#sexe').val();
                const age = $('#age').val();

                $.ajax({
                    url: 'API/api_animaux.php', // Appel de l'API
                    type: 'GET',
                    data: { espece, race, sexe, age },
                    success: function(response) {
                        $('#contenu-animaux').empty();
                        if (response.success && response.data.length > 0) {
                            response.data.forEach(animal => {
                                $('#contenu-animaux').append(`
                                    <a href="chat.php?id=${animal.id}" style="text-decoration: none; color: inherit;">
                                        <div class="custom-card" style="width: 300px; margin: 10px; background: #fff; border-radius: 10px; overflow: hidden; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                            <img src="${animal.photo}" alt="${animal.nom}" style="width: 100%; height: 200px; object-fit: cover;">
                                            <div class="card-body" style="padding: 10px;">
                                                <h4 style="margin: 10px 0; color: #333;">${animal.nom}</h4>
                                                
                                            </div>
                                        </div>
                                    </a>
                                `);
                            });
                        } else {
                            $('#contenu-animaux').append('<p style="text-align: center;">Aucun animal trouvé.</p>');
                        }
                    },
                    error: function() {
                        alert('Erreur lors du chargement des données.');
                    }
                });
            }

            // Appel initial et sur changement des filtres
            $('#espece, #race, #sexe, #age').on('change', chargerAnimaux);
            chargerAnimaux();
        });
    </script>
</body>
</html>
