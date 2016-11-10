<?php

/**
 * Description of Upload
 *
 * @author richard.lovell
 */
class Uploader {

    private $fileArrName;
    private $folderPath = "img/upload";
    private $type;

    const JPEG_TYPE = "image/jpeg";
    const GIF_TYPE = "image/gif";
    const PNG_TYPE = "image/png";
    const PDF_TYPE = "application/pdf";

    function Uploader($fileArrName) {
        $this->fileArrName = $fileArrName;
    }

    //TODO: instead of echo out errors directly, return error for use in validation
    function upload() {
        if ($_FILES[$this->fileArrName]['name']) {
            if ($_FILES[$this->fileArrName]['error']) {
                switch ($_FILES[$this->fileArrName]['error']) {
                    case 1: echo "Error : File exceeds maximum upload
file size<br />";
                        return false;
                        break;
                    case 2: echo "Error : File exceeds maximum upload
size<br />";
                        return false;
                        break;
                    case 3: echo "Error : Partially uploaded<br />";
                        return false;
                        break;
                    case 4: echo "Error : No file uploaded<br />";
                        return false;
                }
            } 
            $this->type = trim(strtolower($_FILES[$this->fileArrName]['type']));
            $types = count($this->getFileTypes());
            $wrong_type = 0; 
            foreach ($this->getFileTypes() as $ftype) {
                if (!strcmp($this->type, $ftype) == 0) {
                    $wrong_type++;
                }
            }
            if ($wrong_type == $types) {
                echo "Error : WRONG FILE TYPE<br />";
                return false;
            }
            if (is_uploaded_file($_FILES[$this->fileArrName]['tmp_name'])) {
                $this->createDirectory($this->folderPath);
                $file_name = $_FILES[$this->fileArrName]['name'];
                $file_path = ($this->folderPath) ? $this->folderPath . "/" .
                        $file_name : $file_name;
                if (file_exists($file_path)) {
                    $new_name = uniqid("CP") . $file_name;
                    $file_path = ($this->folderPath) ? $this->folderPath . "/" . $new_name : $new_name;
                }
                if (move_uploaded_file($_FILES[$this->fileArrName]
                                ['tmp_name'], $file_path)) {
                    if (file_exists($file_path)) {
                        return $file_path;
                    } else {
                        return false;
                    }
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    private function createDirectory($folderPath) {
        if ($folderPath) {
            if (!is_dir($folderPath)) {
                $old_umask = umask(0); 
                if (!mkdir($folderPath, 0777, true)) {
                    return false;
                }
                umask($old_umask);
            }
        }
    }

    public function getFileTypes() {
        return array(
            self::GIF_TYPE,
            self::JPEG_TYPE,
            self::PNG_TYPE,
            self::PDF_TYPE
        );
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }
}

