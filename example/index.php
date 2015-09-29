<?php

require "../vendor/autoload.php";

$gkp = new \GoogleKeywordPosition\GoogleKeywordPosition();

$domain = false;
$keyword = false;
$position = false;

if($_POST){
    $domain = $_POST['domain'];
    $keyword = $_POST['keyword'];
    $position = $gkp->getKeywordPosition($domain, $keyword);
}

?>

<! DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Google keyword position - Example</title>
    </head>
    <body>
        <?php if($position !== false) : ?>
            Keyword '<?= $keyword; ?>' position of domain '<?= $domain; ?>' is: <?= $position; ?>
        <?php endif; ?>
        <form method="post">
            Domain:  <input name="domain" value="<?= @$domain; ?>" />
            <br />
            Keyword: <input name="keyword" value="<?= @$keyword; ?>" />
            <br />
            <input type="submit" value="Submit" />
        </form>
    </body>
</html>
