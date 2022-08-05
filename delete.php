<?php
// on démarre une session
session_start();

// Est-ce que l'id existe et n'est pas vide dans l'url
if(isset($_GET['id']) && !empty($_GET['id'])){
    
    require_once('connect.php');

    // VERIFIE SON EXISTENCE DANS LA DB
    $id = strip_tags($_GET['id']);    
    $req = "SELECT prenom,nom, examens.matiere, examens.note FROM etudiants INNER JOIN examens ON etudiants.id_etudiant = examens.id_etudiant WHERE etudiants.id_etudiant = :id" ;
    $request = $db->prepare($req);
    $request->bindValue(':id', $id, PDO::PARAM_INT);
    $request->execute();
    $ficheAll = $request->fetchAll();

    // SUPPRIME DE LA DB
    $req = "DELETE  FROM etudiants WHERE etudiants.id_etudiant = :id" ;
    $request = $db->prepare($req);
    $request->bindValue(':id', $id, PDO::PARAM_INT);
    $request->execute();
    $_SESSION['message'] = "Eleve supprimé";
    header('Location: index.php');
    
}else{
    $_SESSION['erreur'] = "URL invalide";
    header('Location: index.php');
}