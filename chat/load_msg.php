<?php
session_start(); 
if(isset($_SESSION['pseudo'])){
    $pseudo = $_SESSION['pseudo'];
}else{
    $pseudo = ' ';
}
    $dernier_chat = $_SESSION['dernier_chat'];

    // on affiche les differents chats
    $bdd = new PDO('mysql:host=localhost;dbname=bdd_chat', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $rep = $bdd->query('SELECT ID, Pseudo, chat, Serveur, Date FROM `chats` WHERE Serveur = "'.$_SESSION['serveur'].'" ORDER BY ID ASC ');
    $les_chats = "";
    
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

        // remetre le chat en bas a chaque message envoyé
        $_SESSION['dernier_chat'] = $donnees['ID'];
    }
    if($dernier_chat != $_SESSION['dernier_chat']){
        ?>
        <script>document.getElementsByClassName('div_chat')[0].scrollTop = document.getElementsByClassName('div_chat')[0].scrollHeight;
        
        </script>
        <?php
    }/////////////////////


    //affichage du message //////////////////////////////////////////
    echo $les_chats;
?>
