<!DOCTYPE html>
<html lang="fr">

<head>
    <style>
        body {
            padding: 0;
            margin: 0;
            background: url('ecole.jpg') no-repeat center fixed;
            -webkit-background-size: cover;
            /* pour anciens Chrome et Safari */
            background-size: cover;
            /* version standardisée */
        }

        h1,
        h2,
        h3 {
            text-align: center;
            font-weight: bold;
            letter-spacing: 4px;
            font-family: 'Girassol', cursive;
            text-decoration: underline;
        }

        .opacitor {
            opacity: 0.8;
        }

        td:hover {
            opacity: 0.7;
            font-weight: bold;
            transform: scale(1.1);
            transition: all 0.4s;
        }

        .redo_color {
            color: green;
            padding-right: 10px;
        }

        .trash_color {
            color: red;
            padding-right: 10px;
        }

        .a_modify,
        .a_delete {
            color: black;
        }

        .a_modify:hover {
            color: green;
        }

        .a_delete:hover {
            color: red;
        }
    </style>
    <!-- Google font styles -->
    <link href="https://fonts.googleapis.com/css2?family=Girassol&display=swap" rel="stylesheet">

    <!-- Links to use Font Awesome icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Gestion des étudiants</title>
</head>

<body>

    <?php
    //connexion à notre base de données
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=force3;port=3308;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    }
    ?>