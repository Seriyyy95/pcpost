<?php

class sitemapgen extends Z_Controller {

    protected $_siteMapGenerator;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("generate_sitemap")) {
            $this->_siteMapGenerator = new Z_SiteMapGenerator();
        }else{
            throw new Z_AccessDeniedException("У вас не достаточно полномочий для генерации sitemap");
        }
    }

    public function run() {
        $this->_siteMapGenerator->generateMap();
        $savedLinks = $this->_siteMapGenerator->saveXmlMap();
        $this->_view->replace("saved", $savedLinks);
    }

}

?>
