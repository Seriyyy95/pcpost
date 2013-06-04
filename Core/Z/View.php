<?php

class Z_View extends Z_Object
{

    protected $_html;
    protected $_key;
    protected $_replace;

    public function __construct($html, $key = null, &$parent = null, $replace = true)
    {
        parent::__construct($parent);
        $this->_html = $html;
        $this->_key = $key;
        $this->_replace = $replace;
    }

    public function replace($key, $value, $replace = true)
    {
        $key = '<!--' . $key . '-->';
        $this->_html = str_replace($key, $value . ($replace ? "" : $key), $this->_html);
    }

    public function getKey()
    {
        return $this->_key;
    }

    public function getHtml()
    {
        $this->render();
        return $this->_html;
    }

    public function getReplace()
    {
        return $this->_replace;
    }

    public function render()
    {
        $this->replace("url", URL);
        $childs = $this->getChilds();
        foreach ($childs As $value) {
            $this->replace($value->getKey(), $value->getHtml(), $value->getReplace());
        }
    }

}

?>
