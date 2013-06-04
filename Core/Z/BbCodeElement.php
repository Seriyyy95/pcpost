<?php

class Z_BbCodeElement extends Z_Element
{

    public function __construct(Z_Table $table, $id)
    {
        parent::__construct($table, $id);
    }

    public function __call($name, $args)
    {
        if (strstr($name, "format_")) {
            return $this->replacetags(parent::__call(substr($name, 7), $args));
        }
        else
            return parent::__call($name, $args);
    }

    public function replacetags($string)
    {
        $ereg_code = array(
            "#\[b\](.*)\[\/b\]#isU",
            "#\[i\](.*)\[\/i\]#isU",
            "#\[u\](.*)\[\/u\]#isU",
            "#\[s\](.*)\[\/s\]#isU",
            "#\[strong\](.*)\[\/strong\]#isU",
            "#\[em\](.*)\[\/em\]#isU",
            "#\[small\](.*)\[\/small\]#isU",
            '#\[url=(http:\/\/.*)\](.*)\[\/url\]#isU',
            '#\[img=(http:\/\/.+?) alt=(.*)\]#is',
            '#\[img=(http:\/\/.+?)\]#is',
            '#\[color=(.*)\](.*)\[\/color\]#isU',
            "#\[quote\](.*)\[\/quote\]isU#"
        );
        $ereg_value = array(
            '<b>\\1</b>',
            '<i>\\1</i>',
            '<u>\\1</u>',
            '<s>\\1</s>',
            "<strong>\\1</strong>",
            "<em>\\1</em>",
            "<small>\\1</small>",
            "<a href='\\1'>\\2</a>",
            "<img src='\\1' alt='\\2'>",
            "<img src='\\1'>",
            "<span style='color: \\1'>\\2</span>",
            "<div class='quote'>\"\\1\"</div>"
        );
        $string = preg_replace($ereg_code, $ereg_value, $string);

        $string = str_replace("\n", "<br />", $string);
        $string = str_replace("&slash", "\\", $string);
        $string = str_replace(":D", "<img src='" . URL . "Images/smiles/biggrin.gif'>", $string);
        $string = str_replace(":))", "<img src='" . URL . "Images/smiles/lol.gif'>", $string);
        $string = str_replace("8-o", "<img src='" . URL . "Images/smiles/shok.gif'>", $string);
        $string = str_replace(":?:", "<img src='" . URL . "Images/smiles/vopros.gif'>", $string);
        $string = str_replace(";)", "<img src='" . URL . "Images/smiles/wink.gif'>", $string);
        $string = str_replace("=)", "<img src='" . URL . "Images/smiles/smile2.gif'>", $string);
        $string = str_replace(":=.", "<img src='" . URL . "Images/smiles/unsure.gif'>", $string);
        $string = str_replace(":(", "<img src='" . URL . "Images/smiles/sad.gif'>", $string);
        $string = str_replace(":-/", "<img src='" . URL . "Images/smiles/skeptik.gif'>", $string);
        $string = str_replace(":super", "<img src='" . URL . "Images/smiles/super.gif'>", $string);
        $string = str_replace("&gt;:[", "<img src='" . URL . "Images/smiles/angry.gif'>", $string);
        $string = str_replace("&gt;:O", "<img src='" . URL . "Images/smiles/mat.gif'>", $string);
        $string = str_replace(":)", "<img src='" . URL . "Images/smiles/smile1.gif'>", $string);
        $string = str_replace(":figa", "<img src='" . URL . "Images/smiles/figa.gif'>", $string);
        $string = str_replace("o_O", "<img src='" . URL . "Images/smiles/blink.gif'>", $string);
        $string = str_replace(":!:", "<img src='" . URL . "Images/smiles/shout.gif'>", $string);
        $string = str_replace("8-)", "<img src='" . URL . "Images/smiles/cool.gif'>", $string);
        return $string;
    }

}

?>
