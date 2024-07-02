<?php
global $cssLink;
global $pageTitle;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/css/animations.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href=<?php echo $cssLink ?> />
    <link rel="icon" href="assets/img/hospital-logo.png">
    <title><?php echo $pageTitle ?></title>
    <style>
        table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>