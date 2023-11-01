<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="<?php echo _WEB_ROOT ?>/public/assets/client/css/style.css">
</head>
<body>
    <?php   
        $this->render('blocks/header'); 
        $this->render($content, $sub_content);
        $this->render('blocks/footer');
    ?>
</body>
</html>