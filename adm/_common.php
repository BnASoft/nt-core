<?php
define('_ADMIN_', true);
require_once '../common.php';

if(!$isAdmin)
    alert(_('Only the administrator can access it.'), NT_URL);