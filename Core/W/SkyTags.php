<?php

class W_SkyTags extends Z_TemplateView
{

    protected $_tagsTable;

    public function __construct()
    {
        $mainView = Z_Factory::Z_MainView();
        $this->_tagsTable = new Z_TagsTable("tags");
        parent::__construct("skytags", "skytags", $mainView);
        $this->_printTags();
    }

    private function _printTags()
    {
        $tags = $this->_tagsTable->getTags();
        if (count($tags) > 0) {
            $fontSizeMin = 15;
            $fontSizeMax = 30;
            $fontSizeDif = $fontSizeMax - $fontSizeMin;
            $tagCountMin = min($tags);
            $tagCountMax = max($tags);
            $tagCountDif = $tagCountMax - $tagCountMin;
            if ($tagCountDif == 0)
                $tagCountDif = 1;
            $step = $fontSizeDif / $tagCountDif;
            ksort($tags);
            foreach ($tags as $tag => $count) {
                $size = round($fontSizeMin + (($count - $tagCountMin ) * $step));
                $this->replace("tag", "<a class='skytag' style='font-size:" . $size . "px' href='" . URL .
                        "posts/tags/word/" . urlencode($tag) . "' title='($count)'>" . $tag . '</a>' . "\n", false);
            }
        }
    }

}

?>
