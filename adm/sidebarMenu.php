<?php
$_ADMIN_LINK = array(
    array('name' => _('Dashboard'), 'link' => NT_ADMIN_URL, 'icon' => 'home', 'key' => 'index'),
    array('name' => _('Member'),    'link' => NT_ADMIN_URL.DIRECTORY_SEPARATOR.'member.php', 'icon' =>'users',     'key' => 'member'),
    array('name' => _('Board'),     'link' => NT_ADMIN_URL.DIRECTORY_SEPARATOR.'board.php',  'icon' =>'list',      'key' => 'board'),
    array('name' => _('Pages'),     'link' => NT_ADMIN_URL.DIRECTORY_SEPARATOR.'pages.php',  'icon' =>'file-text', 'key' => 'pages')
);

if ($isSuper)
    $_ADMIN_LINK[] = array('name' => _('Configuration'), 'link' => NT_ADMIN_URL.DIRECTORY_SEPARATOR.'config.php', 'icon' => 'settings', 'key' => 'config');

function getSidebarMenu()
{
    global $_ADMIN_LINK;

    $menu = array();

    $current = $_SERVER['SCRIPT_NAME'];

    foreach ($_ADMIN_LINK as $link) {
        if (preg_match('#'.preg_quote($link['key']).'.*\.php$#', $current))
            $active = ' active';
        else
            $active = '';

        $menu[] = '<li class="nav-item">
                        <a class="nav-link'.$active.'" href="'.$link['link'].'">
                            <span data-feather="'.$link['icon'].'"></span>
                            '.getHtmlChar($link['name']).'
                        </a>
                    </li>';
    }

    echo implode(PHP_EOL, $menu);
}