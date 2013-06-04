<?php


class Z_TemplateView extends Z_View
{
    
    public function __construct($fileName, $key=null, &$parent=null, $replace=true)
    {
        $html = file_get_contents(TEMPLATES_PATH . $fileName . '.tpl');
        parent::__construct($html, $key, $parent, $replace);
    }
    
}

?>
