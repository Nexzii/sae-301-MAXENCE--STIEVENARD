<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À Propos - Ami 4 Pattes</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <style>
        #map {
            height: 400px;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="about-section">
            <img src="./recources/logo.svg" alt="Logo Ami 4 Pattes" class="logo">
            <h2>Qui sommes-nous ?</h2>
            <p>
                Bienvenue sur le site d'Ami 4 Pattes, un refuge dédié aux animaux en quête d'une nouvelle famille.
                Notre mission est de leur offrir une seconde chance et de les aider à trouver un foyer aimant.
            </p>
            <p>
                Nous croyons que chaque animal mérite une vie remplie de bonheur et de sécurité. Rejoignez-nous
                dans cette mission !
            </p>
        </section>

      

        <section class="contact-section">
            <h2>Contactez-nous</h2>
            <div class="contacts">
                <a href="mailto:contact@ami4pattes.fr">✉️ Email</a>
                <a href="https://instagram.com/ami4pattes" target="_blank">📸 Instagram</a>
                <a href="https://facebook.com/ami4pattes" target="_blank">📘 Facebook</a>
            </div>
        </section>
        <section class="location-section">
            <h2>Où nous retrouver ?</h2>
            <p>Adresse : 3 HLM le Grenouillet, 63510 Aulnat</p>
            <div id="map"></div>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var map = L.map('map').setView([45.801095, 3.190154], 16);

            // Ajout des tuiles OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Ajout d'un marqueur
            L.marker([45.801095, 3.190154]).addTo(map)
                .bindPopup('<b>Refuge Ami 4 Pattes</b><br>3 HLM le Grenouillet, 63510 Aulnat')
                .openPopup();
        });
    </script>
        <section class="saviors-section">
            <h2>Nos sauveurs</h2>
            <div class="saviors">
                <div><img src="recources/moi.jpg" alt="Nathaniel"><p>Nathaniel</p></div>
                <div><img src="recources/moi.jpg" alt="Johanna"><p>Johanna</p></div>
                <div><img src="recources/moi.jpg" alt="Béatrice"><p>Béatrice</p></div>
                <div><img src="recources/moi.jpg" alt="Michel"><p>Michel</p></div>
                <div><img src="recources/moi.jpg" alt="Jeanne"><p>Jeanne</p></div>
                <div><img src="recources/moi.jpg" alt="Christophe"><p>Christophe</p></div>
            </div>
        </section>
    </main>



    <?php include 'footer.php'; ?>


</body>
</html>
</html>


