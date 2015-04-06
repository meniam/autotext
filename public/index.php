<?php

define('PROJECT_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
define('LIBRARY_PATH', realpath(PROJECT_PATH . DIRECTORY_SEPARATOR . 'library'));

set_include_path(
    get_include_path() .
    PATH_SEPARATOR . LIBRARY_PATH
);

require_once LIBRARY_PATH . '/TextGenerator/Part.php';
require_once LIBRARY_PATH . '/TextGenerator/XorPart.php';
require_once LIBRARY_PATH . '/TextGenerator/OrPart.php';
require_once LIBRARY_PATH . '/TextGenerator/TextGenerator.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Seo generator demo</title>
    <meta charset='utf-8'>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
</head>
<body>
<form action="" method="POST">
    <label><textarea name="template" id="template" cols="100" rows="10">Генератор текста{ -|:} {скрипт, предназначенный|программа, предназначенная} для [+ и +генерации|создания] уникальных [ +, +описаний|названий|{анкоров|ссылок}].</textarea></label>
    <br>
    <input type="submit" value="Генерить!"/>
</form>

<?php
if (isset($_POST['template'])) {
    $template = $_POST['template'];

    $t         = microtime(true);
    $generator = \TextGenerator\TextGenerator::factory($template);
    for ($i = 0; $i < 5000; $i++) {
        echo '<br /><br />';
        echo $generator->generate();
    }

    echo '<br />------------------<br />';
    echo microtime(true) - $t;
}
?>
</body>
</html>