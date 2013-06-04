<?php

class Z_MainView extends Z_TemplateView
{

    protected $_flushed = false;

    public function __construct($fileName)
    {
        parent::__construct($fileName);
    }

    public function setStyle($link)
    {
        $this->replace("style", "<link rel='stylesheet' type='text/css' href='$link' /><!--style-->");
    }

    public function setLogo($link)
    {
        $this->replace("logo", $link);
    }

    public function setFavicon($link)
    {
        $this->replace("favicon", $link);
    }

    public function setRss($link)
    {
        $this->replace("rss", $link);
    }

    public function setRssIcon($link)
    {
        $this->replace("rssimg", $link);
    }    
    
    public function setTitle($title)
    {
        $this->replace("title", $title . " / pcpost.tk");
    }

    public function flush()
    {
        if ($this->_flushed == false) {
            $html = $this->getHtml();
            $html = preg_replace("#<!--[A-Za-z0-9_]+-->#", "", $html);
            echo $html;
            $this->_flushed = true;
        }
    }

    
    public function setException(Z_ExceptionView $view){
        $this->replace("page", $view->getHtml());
        $this->flush();
        exit();
    }
    
    public function __destruct()
    {
        if (!$this->_flushed)
            $this->flush();
    }

}

?>
