<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Header Styles */
        header {
            background-color: #1cbac9;
            color: white;
            padding: 15px 30px;
            margin: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 20px 20px 20px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        header nav {
        display: flex;
        gap: 15px;
        }

        header nav a {
        margin: 5px 0;
        }

        header nav.active {
        flex-direction: column;
        background-color: #1cbac9;
        position: absolute;
        top: 70px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        header .logo {
            font-size: 24px;
            font-weight: bold;
        }

        header .nav-links {
            display: flex;
            gap: 20px;
        }

        header .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        header .nav-links a:hover {
            text-decoration: underline;
        }

        .hamburger {
            display: none;
            cursor: pointer;
            flex-direction: column;
        }

        .hamburger span {
            background-color: white;
            height: 3px;
            width: 25px;
            margin: 4px 0;
            border-radius: 5px;
        }

        .hamburger div {
        width: 25px;
        height: 3px;
        background-color: #fff;
        margin: 5px 0;
        transition: 0.4s;
        }

        .nav-links.mobile {
            display: none;
            flex-direction: column;
            position: absolute;
            top: 70px;
            right: 10px;
            background-color: #1cbac9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .nav-links.mobile.active {
            display: flex;
        }
        
        @media (max-width: 768px) {
        .hamburger {
            display: block;
        }

        header nav {
            display: none;
        }
}
    </style>
</head>

<body>
    <header>
        <div class="logo">Amis 4 Pattes</div>
        <div class="hamburger" aria-label="Menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <nav class="nav-links desktop">
            <a href="index.php">Accueil</a>
            <a href="adoption.php">Adoption</a>
            <a href="apropos.php">À propos</a>
        </nav>
        <nav class="nav-links mobile">
            <a href="acceuil.php">Accueil</a>
            <a href="adoption.php">Adoption</a>
            <a href="apropos.php">À propos</a>
        </nav>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="css/curseur.css">
        <link href="../css/style.css" rel="stylesheet">
    </header>

    <body>


        <script>
            // JavaScript for responsive hamburger menu
            const hamburger = document.querySelector('.hamburger');
            const mobileNav = document.querySelector('.nav-links.mobile');

            hamburger.addEventListener('click', () => {
                const isActive = mobileNav.classList.toggle('active');
                hamburger.setAttribute('aria-expanded', isActive.toString());
            });

            // Close menu when clicking a link
            document.querySelectorAll('.nav-links.mobile a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileNav.classList.remove('active');
                    hamburger.setAttribute('aria-expanded', 'false');
                });
            });

            // Close menu if clicked outside
            document.addEventListener('click', (event) => {
                if (!hamburger.contains(event.target) && !mobileNav.contains(event.target)) {
                    mobileNav.classList.remove('active');
                    hamburger.setAttribute('aria-expanded', 'false');
                }
            });
        </script>
    </body>

</html>