<?php

class Z_FileUploader {

   private $_filename;

   public function __construct($filename) {
      if (self::isDownloadFile($filename))
         $this->_filename = $filename;
   }

   public static function isDownloadFile($filename) {
      return @is_uploaded_file($_FILES[$filename]["tmp_name"]);
   }

   public function getFileSize() {
      return $_FILES[$this->_filename]["size"];
   }

   public function getFileType() {
      return $_FILES[$this->_filename]["type"];
   }

   public function getFileContents() {
      return file_get_contents($_FILES[$this->_filename]["tmp_name"]);
   }

   public function getFilePath() {
      return $_FILES[$this->_filename]["tmp_name"];
   }

   public function moveFile($filepath) {
      @unlink($_SERVER["DOCUMENT_ROOT"] ."/" .$filepath);
      move_uploaded_file($_FILES[$this->_filename]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] ."/" .$filepath);
   }

}

?>
