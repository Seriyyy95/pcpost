<?php

class Z_Object
{

    protected $_className;
    protected $_parent;
    protected $_childs = array();

    public function __construct(Z_Object &$parent = null)
    {
        if ($parent != null) {
            $this->setParent($parent);
        }
        $this->_className = get_class($this);
        Z_Factory::set($this->_className, $this);
    }

    public function getParent()
    {
        return $this->_parent;
    }

    public function setParent(Z_Object &$parent)
    {
        $this->_parent = &$parent;
        $parent->addChild($this);
    }

    public function addChild(Z_Object &$object)
    {
        $this->_childs[] = &$object;
    }

    public function getChilds()
    {
        return $this->_childs;
    }

    public function setClassName($className)
    {
        $this->_className = $className;
    }

    public function getClassName()
    {
        return $this->_className;
    }

}

?>
