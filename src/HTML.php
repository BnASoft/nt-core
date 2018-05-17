<?php
/**
 * COMMON Class
 */

class HTML
{
    protected $hs;
    protected $fs;
    protected $hc;
    protected $fc;
    protected $hss;
    protected $fss;
    protected $hcss;
    protected $fcss;

    public $title;

    public function __construct()
    {
        $this->hs = array();
        $this->fs = array();
        $this->hc = array();
        $this->fc = array();

        $this->hss  = array();
        $this->fss  = array();
        $this->hcss = array();
        $this->fcss = array();

        $this->title = '';
    }

    public function setPageTitle(string $title)
    {
        $this->title = getHtmlChar($title);
    }

    public function addStyleSheet(string $style, string $location, int $order = 0, string $ver = '', string $extra = '', string $prepend = '', string $append = '')
    {
        if(trim($style))
            $this->mergeStyleSheet($style, $location, $order, $ver, $extra, $prepend, $append);
    }

    public function addJavaScript(string $script, string $location, int $order = 0, string $ver = '', string $extra = '', string $prepend = '', string $append = '')
    {
        if(trim($script))
            $this->mergeJavaScript($script, $location, $order, $ver, $extra, $prepend, $append);
    }

    public function addStyleString(string $style, string $location, int $order = 0)
    {
        if(trim($style))
            $this->mergeStyleString($style, $location, $order);
    }

    public function addScriptString(string $script, string $location, int $order = 0)
    {
        if(trim($script))
            $this->mergeScriptString($script, $location, $order);
    }

    protected function mergeStyleSheet(string $style, string $location, int $order, string $ver, string $extra, string $prepend, string $append)
    {
        switch ($location) {
            case 'footer':
                $links = $this->fc;
                break;
            default:
                $links = $this->hc;
                break;
        }

        $isMerge = true;

        foreach($links as $link) {
            if($link[1] == $style) {
                $isMerge = false;
                break;
            }
        }

        if($isMerge) {
            switch ($location) {
                case 'footer':
                    $this->fc[] = array($order, $style, $ver, $extra, $prepend, $append);
                    break;
                default:
                    $this->hc[] = array($order, $style, $ver, $extra, $prepend, $append);
                    break;
            }
        }
    }

    protected function mergeJavaScript(string $js, string $location, int $order, string $ver, string $extra, string $prepend, string $append)
    {
        switch ($location) {
            case 'footer':
                $scripts = $this->fs;
                break;
            default:
                $scripts = $this->hs;
                break;
        }

        $isMerge = true;

        foreach($scripts as $script) {
            if($script[1] == $js) {
                $isMerge = false;
                break;
            }
        }

        if($isMerge) {
            switch ($location) {
                case 'footer':
                    $this->fs[] = array($order, $js, $ver, $extra, $prepend, $append);
                    break;
                default:
                    $this->hs[] = array($order, $js, $ver, $extra, $prepend, $append);
                    break;
            }
        }
    }

    protected function mergeStyleString(string $css, string $location, int $order)
    {
        switch ($location) {
            case 'footer':
                $styles = $this->fcss;
                break;
            default:
                $styles = $this->hcss;
                break;
        }

        $isMerge = true;

        foreach($styles as $style) {
            if($style[1] == $css) {
                $isMerge = false;
                break;
            }
        }

        if($isMerge) {
            switch ($location) {
                case 'footer':
                    $this->fcss[] = array($order, $css);
                    break;
                default:
                    $this->hcss[] = array($order, $css);
                    break;
            }
        }
    }

    protected function mergeScriptString(string $js, string $location, int $order)
    {
        switch ($location) {
            case 'footer':
                $scripts = $this->fss;
                break;
            default:
                $scripts = $this->hss;
                break;
        }

        $isMerge = true;

        foreach($scripts as $script) {
            if($script[1] == $js) {
                $isMerge = false;
                break;
            }
        }

        if($isMerge) {
            switch ($location) {
                case 'footer':
                    $this->fss[] = array($order, $js);
                    break;
                default:
                    $this->hss[] = array($order, $js);
                    break;
            }
        }
    }

    public function getPageStyle(string $location)
    {
        switch (strtolower($location)) {
            case 'footer':
                $links = $this->fc;
                break;
            default:
                $links = $this->hc;;
                break;
        }

        $stylesheet = array();

        if(!empty($links)) {
            foreach ($links as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $style[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $links);

            foreach($links as $link) {
                if(!trim($link[1]))
                    continue;

                if ($link[2])
                    $link[1] = preg_replace('#\.css$#i', '.css?ver='.$link[2].'$1', $link[1]);

                $s = array();

                $s[] = $link[4];
                $s[] = '<link rel="stylesheet" href="'.$link[1].'"'.($link[3] ? ' '.$link[3] : '').'>';
                $s[] = $link[5];

                $stylesheet[]= implode('', $s);
            }
        }

        return implode(PHP_EOL, $stylesheet).PHP_EOL;
    }

    public function getPageScript(string $location)
    {
        switch (strtolower($location)) {
            case 'footer':
                $scripts = $this->fs;
                break;
            default:
                $scripts = $this->hs;
                break;
        }

        $javascript = array();

        if(!empty($scripts)) {
            foreach ($scripts as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $script[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $scripts);

            foreach($scripts as $js) {
                if(!trim($js[1]))
                    continue;

                if ($js[2])
                    $js[1] = preg_replace('#\.js$#i', '.js?ver='.$js[2].'$1', $js[1]);

                $s = array();

                $s[] = $js[4];
                $s[] = '<script src="'.$js[1].'"'.($js[3] ? ' '.$js[3] : '').'></script>';
                $s[] = $js[5];

                $javascript[] = implode('', $s);
            }
        }

        return implode(PHP_EOL, $javascript).PHP_EOL;
    }

    public function getStyleString(string $location)
    {
        switch (strtolower($location)) {
            case 'footer':
                $styles = $this->fcss;
                break;
            default:
                $styles = $this->hcss;
                break;
        }

        $css = array();

        if(!empty($styles)) {
            foreach ($styles as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $script[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $styles);

            foreach($styles as $s) {
                if(!trim($s[1]))
                    continue;

                $css[] = $s[1];
            }
        }

        return implode(PHP_EOL, $css).PHP_EOL;
    }

    public function getScriptString(string $location)
    {
        switch (strtolower($location)) {
            case 'footer':
                $scripts = $this->fss;
                break;
            default:
                $scripts = $this->hss;
                break;
        }

        $javascript = array();

        if(!empty($scripts)) {
            foreach ($scripts as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $script[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $scripts);

            foreach($scripts as $js) {
                if(!trim($js[1]))
                    continue;

                $javascript[] = $js[1];
            }
        }

        return implode(PHP_EOL, $javascript).PHP_EOL;
    }

    public function getPageHeader(string $name = null, bool $once = true)
    {
        if($name)
            $file = "header-{$name}.php";
        else
            $file = 'header.php';

        $this->loadPage($file, $once);
    }

    public function getPageFooter(string $name = null, bool $once = true)
    {
        if($name)
            $file = "footer-{$name}.php";
        else
            $file = 'footer.php';

        $this->loadPage($file, $once);
    }

    public function loadPage(string $file, bool $once = true)
    {
        global $html, $config, $member, $isGuest, $isMember, $isAdmin, $isSuper, $nt, $DB;

        $page = NT_THEME_PATH.DIRECTORY_SEPARATOR.$file;

        if (is_file($page)) {
            if ($once)
                require_once($page);
            else
                require($page);
        }

    }
}