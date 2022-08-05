<?php
        // constantes d'environnement
        define("DBHOST", "localhost");
        define("DBUSER", "root");
        define("DBPASS", "");
        define("DBNAME", "tuto-php-crud");

        // DSN de connexion
        $dsn = "mysql:dbname=".DBNAME.";host=".DBHOST;

        // on va se connecter a la base
        try{
            // on va instancier PDO
            $db = new PDO($dsn, DBUSER, DBPASS);
            
            // on va s'assurer d'envoyer les données en utf8 
            $db->exec("SET NAMES utf8");

            // on définit le mode de "fetch" par défaut
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            die("Erreur:".$e->getMessage());
        }
        // ici on est connectés a la base