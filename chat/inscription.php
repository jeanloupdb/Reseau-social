<?php session_start();
        if(isset($_POST['insc_submit'])){
            if($_POST['insc_pseudo'] != '' && $_POST['insc_password'] != '' && $_POST['insc_qi'] != 0 && $_POST['insc_pseudo'] != $_POST['insc_password'])
            {

                $pseudo = htmlspecialchars( $_POST['insc_pseudo']);

                $mdp = htmlspecialchars($_POST['insc_password']);
                $mdp = password_hash('OSS'.$mdp."117", PASSWORD_DEFAULT, ['cost' => 13]);

                $qi = (int)htmlspecialchars($_POST['insc_qi']);
                $adn = (int)htmlspecialchars($_POST['insc_adn']);
                if (isset($_POST['insc_pongiste']) && $_POST['insc_pongiste'] == 'on')
                {
                    $pongiste = 1;
                }else{
                    $pongiste = 0;
                }

                $bdd = new PDO('mysql:host=localhost;dbname=bdd_chat', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                $rep = $bdd->exec('INSERT INTO `chater`( `Pseudo`, `Mot_de_passe`, `Quotient_intellectuel`, `Age_de_naissance`, `Pongiste`) VALUES("'.$pseudo.'", "'.$mdp.'", "'.$qi.'", "'.$adn.'", "'.$pongiste.'")');
               
                $_SESSION['pseudo'] = $pseudo;
                header('Location:index.php');
                exit();
                
            }
            else
            {
                $erreur_champs = '<p>Veuillez remplir tout les champs ou utiliser un autre mot de passe.</p>';
            }
        }
        ?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="inscription.css">
        <meta charset="UTF-8">
        <title>inscription</title>
    </head>


    <body>
    
        
    <nav>
    <div class="conteneur-nav">
            <label class="nav_label" for="mobile">Page d'inscription</label>
            <input class="nav_input" type="checkbox" id="mobile" role="button">
            <ul>
                <li class="titre-nav">
                    <h1 class="nav_h1">Page de connexion</h1>
                </li>
            </ul>
        </div>
    </nav>
        <br>
        <div class="contener">
        <h2>S'inscrire : </h2>
        <form action="" method="POST">
            <label>Pseudo : <input class="text" type="text" name="insc_pseudo" placeholder="Pseudo..."></label><br><br>
            <label>Mot de passe : <input class="text" type="password" name="insc_password" placeholder="ex: 3exp38d["></label><br><br>
            <label>Quotient intellectuel : <input class="text" type="number" name="insc_qi" placeholder="n° de QI..."></label><br><br>
            <label>Age de naissance : <input class="text" type="number" name="insc_adn" placeholder="ex: 0"></label><br><br>
            <label>Êtes vous pongiste ? : <input class="check" type="checkbox" name="insc_pongiste"></label><br><br>
            <p><?php
            if(isset($erreur_champs)){
                echo $erreur_champs;
            }
            ?></p>
            <input type="submit" name="insc_submit">
        </form>
        <p>Vous êtes déjà inscrit ? <a href="connexion.php">Connectez vous</a>.</p>
        </div>
        <br><br>
    <footer>
        <p>&copy; <strong>Copiright 2021 open-discussion.ga</strong></p>
        
        <p class="footer_contact">Contact : <a>jldebeauminy@gmail.com</a></p>
    </footer>
    </body>
</html>