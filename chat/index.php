<?php
session_start(); 
$erreur4 = '';
$erreur5 = '';
/*
if($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'http'){
    header('Location:https://open-discussion.ga');
}
*/
// initialiser pseudo///////////////////////////////////////
if(isset($_SESSION['pseudo'])){
    $pseudo = $_SESSION['pseudo'];
}else{
    $pseudo = ' ';
}
// creer un serveur ////////////////////////////////////////
if(isset($_POST['submit2'])){
    
    if($pseudo != ' '){
        $bdd = new PDO('mysql:host=localhost;dbname=bdd_chat', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $rep3 = $bdd->query('SELECT Serveur FROM `serveurs`');
        $res = true;
        while($res){
            $count = random_int(0,99999);
            $res = false;
            while($res==false && $don = $rep3->fetch()){
                if($don['Serveur'] == $count){
                    $res = true;
                }
            }
        }
        $_SESSION['serveur'] = $count;
        $create_serveur = '
        <form method="POST">
            <label class="label_text2">Création du serveur <strong>'.$_SESSION['serveur'].' :</strong></label><br><br>
            <label class="label_text2">Entrez un mot de passe pour ce serveur: </label><input class="text" type="password" name="mdp_server" placeholder="password..."><br>
            <input class="submit" type="submit" name="submit3" value="Créer">
        </form>';
        
    }else{
        
        $erreur2 = '<p>Vous devez vous <a href="inscription.php" >inscrire</a> ou vous <a href="connexion.php" >connecter</a></p>';
    }
}else{
    $erreur4 = '<form method="POST">
    <input class="submit" type="submit" name="submit2" value="Créer">';
    $erreur5 = '
    </form>';
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
if(isset($_POST['submit3'])){
    if(isset($_POST['mdp_server']) && !empty($_POST['mdp_server']) && $_POST['mdp_server'] != "" && $_POST['mdp_server'] != $_SESSION['serveur']){
        $mdp_ser = htmlspecialchars($_POST['mdp_server']);
        $mdp_ser = password_hash("OSS".$mdp_ser."117", PASSWORD_DEFAULT, ['cost' => 13]);
        $bdd = new PDO('mysql:host=localhost;dbname=bdd_chat', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $insert_serveur = $bdd->exec('INSERT INTO `serveurs`(`Serveur`, `MDP`) VALUES ("'.$_SESSION['serveur'].'","'.$mdp_ser.'")');
        header('Location:chat_post.php');
        exit();
    }else{
        $erreur3 = "<p>Veuillez entrer un code valide</p>";
    }
}

////////////// Liste des serveurs sur lesquels on s'est deja connecté ////////////////////////////////////////////////////////



// Se connecter à un serveur //////////////////////////////////
if(isset($_POST['submit'])){
    if($pseudo != ' '){
        if(isset($_POST['server']) && $_POST['server'] != '' && isset($_POST['mdp_connect_server']) && $_POST['mdp_connect_server'] != ''){
            $input_server = (int)htmlspecialchars($_POST['server']);
            $bdd = new PDO('mysql:host=localhost;dbname=bdd_chat', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $rep1 = $bdd->query('SELECT Serveur, MDP FROM `serveurs` where Serveur = "'.$input_server.'"');
            $res = true;
            while($donnee = $rep1->fetch())
            {
                if(password_verify("OSS".$_POST['mdp_connect_server']."117", $donnee['MDP'])){
                    $res = false;
                }
            }
            if($res == false){
                //on cré les variables de session de l'utilisateur et le redirige vers la page des connectés.
                $_SESSION['serveur'] = $input_server;
                header('Location:chat_post.php');
                exit();
            }else{
                $erreur = '<p>Serveur ou mot de passe incorrect !</p>';
            }
            
        }
        else
        {
            $erreur = '<p>Veuillez renseigner un numero de serveur...</p>';
        }
    }else{
        $erreur = '<p>Vous devez vous <a href="inscription.php" >inscrire</a> ou vous <a href="connexion.php" >connecter</a></p>';
    }
}

// deconnexion ///////////////////////////////////////////////
if(isset($_POST['submit_dec'])){
    session_destroy();
    if($pseudo == ' '){
        header('Location:connexion.php');
    }else{
        header('Location:index.php');
    }
    
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="connecte.css">
    <title>Document</title>
</head>
<body>
    <nav>
        <div class="conteneur-nav">
            <label class="nav_label" for="mobile"><a class="hamburger_lien" href="connexion.php"><?php
                    if($pseudo!=' '){
                        echo '<span class="pseudo">'.$pseudo.'</strong></span>';
                    }else{
                    ?><img class="hamburger" src="Hamburger.png"><?php }?></a>Accueil </label>
            <input class="nav_input" type="checkbox" id="mobile" role="button">
            <ul>
                <li class="titre-nav">
                    <h1 class="nav_h1">Accueil</h1>
                </li>
                <li class="ecart">
                    <div class="ecart">A</div>
                </li>
                <li class="lien-nav">
                    <?php
                    if($pseudo!=' '){
                        echo '<p class="pseudo">Pseudo : <trong>'.$pseudo.'</strong></p>';
                    }
                    ?>
                </li>
                <li class="lien-nav"><form method="POST"><input class="a" type="submit" name="submit_dec" value="<?php if($pseudo ==' '){echo'Se Connecter'; }else{echo 'Se déconnecter'; }?>">
                    </input></form>
                </li>
            </ul>
        </div>
    </nav>
                    
    <div class="connect">
        <h1>Se connecter à un serveur : </h1>
        <form method="POST">
            <label class="label_text">N° de serveur : </label><input class="text" type="text" name="server" placeholder="Ex: 1084..."><br>
            <label class="label_text2">Mot de passe : </label><input class="text" type="password" name="mdp_connect_server" placeholder="password..."><br>
            <?php
            if(isset($erreur)){
                echo $erreur;
            }
            ?>
            <input class="submit" type="submit" name="submit" value="Connexion">
        </form>
    </div>


    <div class="create">
        <h1>Creer un serveur : </h1>
        <?php
        echo $erreur4;
        if(isset($erreur2)){
            echo $erreur2;
        };
        echo $erreur5;
        ?>
        
        <?php
        
        if(isset($create_serveur)){
            echo $create_serveur;
        }
        if(isset($erreur3)){
            echo $erreur3;
        }
        ?>
    </div>
    
    <br><br>
        <footer>
        <p>&copy; <strong>Copiright 2021 open-discussion.ga</strong></p>
        
        <p class="footer_contact">Contact : <a>jldebeauminy@gmail.com</a></p>
    </footer>
</body>
</html>