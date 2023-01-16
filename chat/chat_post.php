<?php 
session_start(); 
if(isset($_SESSION['pseudo'])){
    $pseudo = $_SESSION['pseudo'];
}
if(isset($_POST['submit_dec'])){
    header('Location:connexion.php');
}
$_SESSION['dernier_chat'] = 1;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="chat.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>chat</title>
</head>
<body>
<nav>
        <div class="conteneur-nav">
        <label class="nav_label" for="mobile"><a class="hamburger_lien" href="index.php"><?php
                    if($pseudo!=' '){
                        echo '<span class="pseudo">Changer de<br>serveur</strong></span>';
                    }?></a><p class="n_serveur">Serveur <?php echo $_SESSION['serveur'];?></p></label>
            <input class="nav_input" type="checkbox" id="mobile" role="button">
            <ul>
                <li class="titre-nav">
                    <h1 class="nav_h1">Serveur <?php echo $_SESSION['serveur']; ?></h1>(<a class="server_change" href="index.php">Changer de serveur</a>)
                </li>
                <li class="ecart">
                    <div class="ecart">A</div>
                </li>
                <li class="lien-nav"><form method="POST"><input class="a" type="submit" name="submit_dec" value="Se déconnecter">
                    </input></form>
                </li>
            </ul>
        </div>
    </nav>
    <h1><?php
    if(isset($_SESSION['pseudo'])){
        echo 'Ecrivez en tant que <span>'.$_SESSION['pseudo'].'</span>.';
    }
    ?></h1>
    <div class="div_contener">
        <div class="div_chat">
            <?php
            // on affiche les different chats
            $bdd = new PDO('mysql:host=localhost;dbname=bdd_chat', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                $rep = $bdd->query('SELECT ID, Pseudo, chat, Serveur, Date FROM `chats` WHERE Serveur = "'.$_SESSION['serveur'].'" ORDER BY ID ASC ');            $les_chats = "";
                
                while($donnees = $rep->fetch()){
                    //Recuperer la date /////////////////
                    $date = $donnees['Date'];
            
                    //changer la class du message selon le pseudo/////////////////////
                    if($donnees['Pseudo'] == $pseudo){
                        $les_chats_debut = '<p class="mon_chat">';
                    }else{
                        $les_chats_debut = '<p class="un_chat">';
                    }
            
                    // enregistrement du message ////////////////////////////////
                    $les_chat_1 = '<span class="pseudo_chat">'.$donnees['Pseudo'].' : </span>';
                    $les_chats_milieu = $donnees['chat'];
                    $les_chats_fin = '<br><span class="pseudo_chat">'.$date.' </span></p>';
                    $les_chats .= $les_chats_debut . $les_chat_1.$les_chats_milieu . $les_chats_fin;
                }
                echo $les_chats;
        ?>
            
        </div>
        
        <form class="div_ecrire" method="POST">
        <?php
        // ajouter un message a la base de donnee /////////////////////////////
        if(isset($_POST['chat_a_envoyer']) && isset($_POST['submit']) && !empty($_POST['submit']) && ($_POST['submit'] != '')){
            date_default_timezone_set('Europe/Paris');
            $date = date("d-m-Y");
            $heure = date("H:i");
            $date_str = 'Le '.$date.' à '.$heure;

            $chataenvoyer = htmlspecialchars($_POST['chat_a_envoyer']);
            $chataenvoyer2 = "";
            $i = 0;
            
            function resol()
            {
            $resol='<script type="text/javascript">
                            document.write(screen.width);
            </script>';
            return $resol;
            }
            $count=(int)resol();
            if($count >= 427){
                $count = 50;
            }else{
                $count = 25;
            }
            $i_count = $count;
            while( $i<strlen($chataenvoyer)){
                if($i>$count && $chataenvoyer[$i] == ' '){
                    $chataenvoyer2.='<br>';
                    $count+=$i_count;
                }else{
                    $chataenvoyer2.=$chataenvoyer[$i];
                }
                $i++;
            }
            $bdd = new PDO('mysql:host=localhost;dbname=bdd_chat', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $inserer = $bdd->exec('INSERT INTO `chats`(`Pseudo`, `chat`, `Serveur`,`Date` ) VALUES ("'.$pseudo.'","'.$chataenvoyer2.'", "'.$_SESSION['serveur'].'", "'.$date_str.'")');
        ?>
        <script>document.getElementsByClassName('div_chat')[0].scrollTop = document.getElementsByClassName('div_chat')[0].scrollHeight;</script>
        <?php
        }
        ?>
            <input class="submit" type="submit" name="submit" value="↑"><input class="chat_a_envoyer" type="text" name="chat_a_envoyer" placeholder="Message...">
        </form>
    </div>

        
    <script>
        document.getElementsByClassName('div_chat')[0].scrollTop = document.getElementsByClassName('div_chat')[0].scrollHeight;
        setInterval('load_msg()',1500);
        function load_msg(){
            $('.div_chat').load('load_msg.php');
        }
        
    </script>
</body>
</html>