<?php
require_once './_common.php';

$type = preg_replace('#[^a-z0-9]#i', '', $_GET['type']);

$token = new TOKEN();

if ($type == 'adm')
    $t = $token->getToken('ss_adm_token');
else
    $t = $token->getToken();

die($t);