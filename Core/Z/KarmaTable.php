<?php

class Z_KarmaTable extends Z_Table
{

    const TYPE_COMMENT =  "comment";
    const TYPE_POST = "post";
    
    private $_user;
    private $_usersTable;

    public function __construct(Z_User $user)
    {
        $this->_user = $user;
        $this->_usersTable = Z_Factory::Z_UsersTable();
        parent::__construct("karma");
    }

    public function canUpKarma(Z_Element $post, $type)
    {
        $count = $this->count("user_id='" . $this->_user->id() . "' AND post_id='" . $post->id() . "' AND type='up' AND write_type='$type'");
        return $count == 0 && $this->_user->getUserGroup()->isPermitAction("vote") && $this->_user->id() != $post->autor();
    }

    public function canDownKarma(Z_Element $post, $type)
    {
       $count = $this->count("user_id='" . $this->_user->id() . "' AND post_id='" . $post->id() . "' AND type='down' AND write_type='$type'");
        return $count == 0 && $this->_user->getUserGroup()->isPermitAction("vote") && $this->_user->id() != $post->autor();        
    }
    
    public function upKarma(Z_Element $post, $type)
    {
        if($this->canUpKarma($post, $type)){
            $post->karma($post->karma() + 1);
            $karmaStruct = array(
                "user_id" => $this->_user->id(),
                "post_id" => $post->id(),
                "type" => "up",
                "autor_id" => $post->autor(),
                "write_type" => $type
            );
            $this->add($karmaStruct);
            $this->_db->setQuery("DELETE FROM $this WHERE post_id='" . $post->id() . "' AND type='down' AND user_id='" . $this->_user->id() . "'");
            if ($this->count("type='up' AND autor_id='" . $post->autor() . "' AND checked='0'") > 10) {
               $autor = $this->_usersTable->loadObject($post->autor());
               $autor->karma($autor->karma() + 1);
               $this->_db->setQuery("UPDATE $this SET checked='1' WHERE autor_id='" . $post->autor() . "' AND type='up'");
            }
        }else{
            throw new Z_AccessDeniedException();
        }
    }
    
    public function downKarma(Z_Element $post, $type)
    {
        if($this->canDownKarma($post, $type)){
            $post->karma($post->karma() - 1);
            $karmaStruct = array(
                "user_id" => $this->_user->id(),
                "post_id" => $post->id(),
                "type" => "down",
                "autor_id" => $post->autor(),
                "write_type" => $type
            );
            $this->add($karmaStruct);
            $this->_db->setQuery("DELETE FROM $this WHERE post_id='" . $post->id() . "' AND type='up' AND user_id='" . $this->_user->id() . "'");
            if ($this->count("type='down' AND autor_id='" . $post->autor() . "' AND checked='0'") > 10) {
               $autor = $this->_usersTable->loadObject($post->autor());
               $autor->karma($autor->karma() - 1);
               $this->_db->setQuery("UPDATE $this SET checked='1' WHERE autor_id='" . $post->autor() . "' AND type='down'");
            }
        }else{
            throw new Z_AccessDeniedException();
        }
    }
    
    public function safeRemoveUser()
    {
        $this->_db->setQuery("DELETE FROM " . $this->_table . " WHERE user_id='" . $this->_user->id() . "' OR autor_id='" . $this->_user->id() . "'");
    }

}

?>
