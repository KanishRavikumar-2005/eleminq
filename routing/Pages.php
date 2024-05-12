<?php

class Page {
    public static $CDNS = [];



    private static function parseCustomTags($html) {
        // Replace <c::component_name/> tags with corresponding HTML
        $html = preg_replace_callback('/<c::(.*?)\s*\/\s*>/s', function($matches) {
            // Extract component name and attributes
            $componentWithAttributes = $matches[1];
            $ATTR = [];

            // Parse attributes
            preg_match_all('/(\w+)\s*=\s*["\']([^"\']+)["\']/', $componentWithAttributes, $matches_attributes, PREG_SET_ORDER);
            foreach ($matches_attributes as $match) {
                $ATTR[$match[1]] = $match[2];
            }

            // Extract component name
            $component = strtok($componentWithAttributes, ' ');

            // Dynamically include the component file
            $componentFile = $_SERVER['DOCUMENT_ROOT']."/components/{$component}.comp.php";
            if (file_exists($componentFile)) {
                ob_start();
                include $componentFile;
                $html = ob_get_clean();
                return $html;
            } else {
                return "<script>console.error(`Component '$component' not found!`)</script>";
            }
        }, $html);
        return $html;
    }

    public static function load($page, $variables = []) {
        $rootPath = $_SERVER['DOCUMENT_ROOT'];

        // Check the views directory
        $viewsFilePath = "$rootPath/views/$page.php";
        if (file_exists($viewsFilePath)) {
            extract($variables);
            ob_start();
            include $viewsFilePath;
            $html = ob_get_clean();

            // Replace custom tags and variables
            $html = self::parseCustomTags($html);
            foreach ($variables as $key => $value) {
                $html = preg_replace('/{\$' . $key . '}/', $value, $html);
            }

            echo $html;
        } else {
            echo "404 Not Found";
        }
    }

    public static function title($title){
        echo "<title>".htmlentities($title)."</title>";
    }

    public static function empty(&$var, $value){
        if (!isset($var)) {
            $var = $value;
        }
    }

    public static function session($session_id, $session_secret = null, $hash = null){
        $ssc = $session_secret;
        if($hash !== null){
            $ssc = hash($hash, $session_secret);
        }
        $ver = false;
        if (isset($_SESSION[$session_id])){
            $ver = true;
            if($ssc !== null && $_SESSION[$session_id] === $ssc){
                $ver = true;
            }elseif($ssc !== null && $_SESSION[$session_id] !== $ssc){
                $ver = false;
            }
        }
        return $ver;
    }

    public static function goto($to_page){
        echo "<script>window.location.assign('$to_page')</script>";
    }

    public static function reload(){
        echo "<script>window.location.reload()</script>";
    }

    public static function back(){
        echo "<script>history.back()</script>";
    }
    public static function forward(){
        echo "<script>history.forward()</script>";
    }
    public static function import(...$moduleNames) {
        global $CDNS;
        $output = '';
        foreach ($moduleNames as $name) {
            if (isset($CDNS[$name])) {
                $module = $CDNS[$name];
                $output .= self::formatModule($module, $name);
            } else {
                echo "<script>console.error('Module Not Found. \'$name\' is not a part of _cdns')</script>";
            }
        }

        // Check for <mainstyle>
        if (isset($CDNS['<mainstyle>'])) {
            $mainStyleModule = $CDNS['<mainstyle>'];
            $output .= "<link rel='stylesheet' href='{$mainStyleModule[1]}' />\n";
        }

        echo "<script>
                var dynImport = document.createElement('div');
                dynImport.setAttribute(`identifier`,`dynamic-imports`);
                dynImport.innerHTML = `$output`;
                document.head.appendChild(dynImport);
              </script>";
    }

    private static function formatModule($module, $name) {
        $output = '';
        switch ($module[0]) {
            case 'script':
                $output .= "<script src='{$module[1]}'></script>\n";
                break;
            case 'style':
                $output .= "<link rel='stylesheet' href='{$module[1]}' />\n";
                break;
            case 'icon':
                $output .= "<link rel='icon' type='image/x-icon' href='{$module[1]}' />\n";
                break;
            case 'image':
                $output .= "<img src='{$module[1]}' alt='{$name}' />\n";
                break;
            case 'link':
                $attrString = '';
                foreach ($module[1] as $key => $value) {
                    $attrString .= " {$key}='{$value}'";
                }
                $output .= "<link{$attrString} />\n";
                break;
        }
        return $output;
    }

    public static function minify_css($css) {
        // Remove comments and unnecessary whitespaces
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        return str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    }

    
}
?>
