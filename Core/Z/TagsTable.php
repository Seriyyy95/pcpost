<?php

class Z_TagsTable extends Z_Table
{

    public function __construct($table)
    {
        parent::__construct($table);
    }

    public function addTag($tag)
    {
        if ($this->count("tag='$tag'") != 0) {
            $this->_db->setQuery("UPDATE tags SET count=count+1 WHERE tag='$tag'");
        } else {
            $this->add(array("tag" => $tag));
        }
    }

    public function delTag($tag)
    {
        $id = $this->getId("tag", $tag);
        if ($id != 0) {
            $object = $this->loadObject($id);
            if ($object->count() == 1) {
                $object->remove();
            } else {
                $object->count($object->count() - 1);
            }
        }
    }

    public function earseTags($src, $new)
    {
        $srctags = explode(",", $src);
        foreach ($srctags As $tag) {
            $this->delTag($tag);
        }
        $newtags = explode(",", $new);
        foreach ($newtags As $tag) {
            $this->addTag($tag);
        }
    }

    public function getTags()
    {
        $this->_db->setQuery("SELECT tag, count FROM " . $this->_table . " ORDER BY count LIMIT 30");
        $tags = array();
        while ($record = $this->_db->loadResult()) {
            $tags[$record["tag"]] = $record["count"];
        }
        return $tags;
    }

}

?>
