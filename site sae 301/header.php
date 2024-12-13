<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amis 4 Pattes</title>
    <link rel="shortcut icon" href="ressources/favicon 48x48.png"/>
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

        header .logo {
            font-size: 24px;
            font-weight: bold;
        }

        /* Desktop navigation */
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

        /* Hamburger menu styles (hidden on desktop) */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .hamburger span {
            background-color: white;
            height: 3px;
            width: 25px;
            margin: 4px 0;
            border-radius: 5px;
        }

        /* Mobile navigation */
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
            /* Hide desktop links */
            header .nav-links.desktop {
                display: none;
            }

            /* Show hamburger menu */
            .hamburger {
                display: flex;
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
            <a href="index.php">Accueil</a>
            <a href="adoption.php">Adoption</a>
            <a href="apropos.php">À propos</a>
        </nav>
    </header>

    <script>
        // JavaScript for responsive hamburger menu
        const hamburger = document.querySelector('.hamburger');
        const mobileNav = document.querySelector('.nav-links.mobile');

        hamburger.addEventListener('click', () => {
            const isActive = mobileNav.classList.toggle('active');
            hamburger.setAttribute('aria-expanded', isActive);
        });

        // Close menu if clicked outside
        document.addEventListener('click', (event) => {
            if (!hamburger.contains(event.target) && !mobileNav.contains(event.target)) {
                mobileNav.classList.remove('active');
                hamburger.setAttribute('aria-expanded', false);
            }
        });
        document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('header nav');

    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });
});

    </script>
</body>
</html>
