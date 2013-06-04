<?php

class Z_SiteMapGenerator extends Z_Object {

    protected $_links;
    protected $_config;
    protected $_postsTable;
    protected $_usersTable;
    protected $_tagsTable;
    protected $_sectionsTable;
    protected $_postsList;

    public function __construct(Z_Object &$parent = null) {
        parent::__construct($parent);
        $this->_config = Z_Factory::Z_Config();
        $this->_usersTable = Z_Factory::Z_UsersTable();
        $this->_postsTable = Z_Factory::Z_PostsTable();
        $this->_tagsTable = new Z_TagsTable("tags");
        $this->_sectionsTable = new Z_Table("sections");
        $this->_postsList = $this->_postsTable->getPublicPosts();
    }

    public function generateMap() {
        $this->_generatePostsLinks();
        $this->_generateSectionsLinks();
        $this->_generatePostsPagesLinks();
        $this->_generatePostsSectionsLinks();
        $this->_generateUsersPostsLinks();
        $this->_generateTagsLinks();
    }

    public function saveXmlMap() {
        $this->_removeExistsXmlSitemap();
        $this->_printHeaderXmlSitemap();
        foreach ($this->_links As $link) {
            $this->_printXmlLink($link);
        }
        $this->_printXmlSitemapEnds();
        return count($this->_links);
    }

    protected function _generatePostsLinks() {
        foreach ($this->_postsList As $post) {
            $this->_links[] = URL . "post/" . $post->id();
        }
    }

    protected function _generateTagsLinks() {
        $tags = $this->_tagsTable->getList();
        foreach ($tags As $tag) {
            $tagsCount = $tag->count();
            $numPages = ceil($tagsCount / $this->_config->page_count());
            for ($i = 1; $i <= $numPages; $i++) {
                $this->_links[] = URL . "posts/tags/word/" . $tag->tag() . "/page/$i";
            }
        }
    }

    protected function _generateSectionsLinks() {
        $sectionsCount = $this->_sectionsTable->count();
        $numPages = ceil($sectionsCount / $this->_config->page_count());
        for ($i = 1; $i <= $numPages; $i++) {
            $this->_links[] = URL . "sections/show/page/$i";
        }
    }

    protected function _generatePostsPagesLinks() {
        $sectionsCount = count($this->_postsList);
        $numPages = ceil($sectionsCount / $this->_config->page_count());
        for ($i = 1; $i <= $numPages; $i++) {
            $this->_links[] = URL . "posts/all/page/$i";
        }
    }

    protected function _generatePostsSectionsLinks() {
        $sectionsList = $this->_sectionsTable->getList();
        foreach ($sectionsList As $section) {
            $tagsCount = $this->_postsTable->count("parent_id='" . $section->id() . "'");
            $numPages = ceil($tagsCount / $this->_config->page_count());
            for ($i = 1; $i <= $numPages; $i++) {
                $this->_links[] = URL . "posts/sec/" . $section->id() . "/page/$i";
            }
        }
    }

    protected function _generateUsersPostsLinks(){
        $usersList = $this->_usersTable->getList();
        foreach ($usersList As $user) {
            $postsCount = $this->_postsTable->count("autor='" . $user->id() . "'");
            $numPages = ceil($postsCount / $this->_config->page_count());
            for ($i = 1; $i <= $numPages; $i++) {
                $this->_links[] = URL . "posts/user/id/" . $user->id() . "/page/$i";
            }
        }        
    }
    
    protected function _removeExistsXmlSitemap() {
        if (file_exists(APPLICATION_PATH . "/sitemap.xml")) {
            unlink(APPLICATION_PATH . "/sitemap.xml");
        }
    }

    protected function _printHeaderXmlSitemap() {
        file_put_contents(APPLICATION_PATH . "/sitemap.xml", "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset
      xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
      xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
      xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">
<!-- created with pcpost sitemap tools -->\n"
                , FILE_APPEND);
    }

    protected function _printXmlLink($link) {
        file_put_contents(APPLICATION_PATH . "/sitemap.xml", "<url>
    <loc>$link </loc>
</url>\n"
                , FILE_APPEND);
    }
    
    protected function _printXmlSitemapEnds()
    {
        file_put_contents(APPLICATION_PATH . "/sitemap.xml", "</urlset>", FILE_APPEND);        
    }

}

?>
