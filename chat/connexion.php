<?php
session_start();
?><?php
//On test si $pseudo et $mdp correspondent bien a un Pseudo et un Mot_de_passe de la table 'chater' de la base de donnée 'bdd_chat'

if(isset($_POST['conect_submit']))
{
    if($_POST['conect_pseudo'] != '' && $_POST['conect_password'] != ''){

        $pseudo = htmlspecialchars($_POST['conect_pseudo']);
        $mdp = htmlspecialchars($_POST['conect_password']);

        $bdd = new PDO('mysql:host=localhost;dbname=bdd_chat', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $rep = $bdd->query('SELECT Pseudo, Mot_de_passe FROM chater WHERE Pseudo = "'.$pseudo.'"');
        while( $donnees = $rep->fetch()){
            if($donnees['Pseudo'] === $pseudo && password_verify("OSS".$mdp.'117', $donnees['Mot_de_passe'])){
                $_SESSION['pseudo'] = $pseudo;
                header('Location:index.php');
                exit();
            }
        }
        $erreur = '<p class="erreur">Identifiant ou mot de passe incorrect !</p>';
    }else{
        $erreur = '<p class="erreur">Pour vous connecter, veuillez remplir tout les camps !</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="connexion.css">
        <title>Connexion</title>
    </head>


    <body>
    <nav>
        <div class="conteneur-nav">
            <label class="nav_label" for="mobile">Page de connexion</label>
            <input class="nav_input" type="checkbox" id="mobile" role="button">
            <ul>
                <li class="titre-nav">
                    <h1 class="nav_h1">Page de connexion</h1>
                </li>
            </ul>
        </div>
    </nav>
    <div class="contener">
        <h2>Se connecter : </h2><br><br>
        <form action="" method="POST">
            <label>Pseudo : <input class="text" type="text" name="conect_pseudo" placeholder="Pseudo..."></label><br><br>
            <label>Mot de passe : <input class="text" type="password" name="conect_password" placeholder="Mot de passe..."></label><br><br>
            
            <?php
            //On affiche le message d'erreur si il a ete envoyé par notre code php
            if(isset($erreur)){
                echo $erreur;
            }
            ?>
            <input type="submit" name="conect_submit" value="Conexion">
        </form>

        <p>Vous n'avez pas encore d'identifiant ? <a href="inscription.php">S'inscrire</a></p>
    </div>
    <br><br><br>
        <footer>
        <p>&copy; <strong>Copiright 2021 open-discussion.ga</strong></p>
        
        <p class="footer_contact">Contact : <a>jldebeauminy@gmail.com</a></p>
    </footer>
    </body>
</html>