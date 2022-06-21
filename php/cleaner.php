<?php
function cleanString($text) {
    $utf8 = array(
        '/[áàâãªä]/u'   =>   'a',
        '/[ÁÀÂÃÄ]/u'    =>   'A',
        '/[ÍÌÎÏ]/u'     =>   'I',
        '/[íìîï]/u'     =>   'i',
        '/[éèêë]/u'     =>   'e',
        '/[ÉÈÊË]/u'     =>   'E',
        '/[óòôõºö]/u'   =>   'o',
        '/[ÓÒÔÕÖ]/u'    =>   'O',
        '/[úùûü]/u'     =>   'u',
        '/[ÚÙÛÜ]/u'     =>   'U',
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/ñ/'           =>   'n',
        '/Ñ/'           =>   'N',
        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
        '/[“”«»„"]/u'    =>   ' ', // Double quote
        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
        '/[\'@#!_+=()*&^%$?;:{}]/u'  =>   ''
    );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}

function validate($data)
{
    $data = trim($data);
    $data = cleanString($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8', true);
    return $data;
}

function validate_multi($data)
{
    $cleaned = Array();
    for($i = 0; $i < sizeof($data); $i++){
        $d = trim($data[$i]);
        $d = cleanString($d);
        $d = stripslashes($d);
        $d = strip_tags($d);
        $d = htmlspecialchars($d, ENT_QUOTES, 'UTF-8', true);
        $cleaned[] = $d;
    }
    return $cleaned;
}

