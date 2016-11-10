<?php

/**
 * Description of ImageResizer
 *
 * @author richard.lovell
 */
class ImageResizer {

    private $filePath;
    private $dimension;
    private $destFolder;
    private $prefix;
    private $fullPath;
    
    function ImageResizer($filePath, $dimension, $destFolder, $prefix) {
        $this->filePath = $filePath;
        $this->dimension = $dimension;
        $this->destFolder = $destFolder;
        $this->prefix = $prefix;
    }

    function resize() {
        if (!file_exists($this->filePath)) {
            return false;
        }
        
        @ $imageInfo = getimagesize($this->filePath);

        $origW = $imageInfo[0]; // original width
        $origH = $imageInfo[1]; // original height
        if ($origH > $origW) {// if height is greater than width
            $newW = ($this->dimension / $origH) * $origW;
            $newH = $this->dimension;
        } else {
            $newH = ($this->dimension / $origW) * $origH;
            $newW = $this->dimension;
        }
        $file_name = basename($this->filePath);
       $d_folder = ($this->destFolder) ? $this->destFolder . "/" : "";
        $d_file = ($this->prefix) ? $this->prefix . "_" . $file_name : $file_name;
        $this->fullPath = $d_folder . "" . $d_file;
        if ($this->destFolder) {
            if (!is_dir($this->destFolder)) {
                $old_umask = umask(0);
                if (!mkdir($this->destFolder, 0777)) {
                    return false;
                }
                umask($old_umask);
            }
        }
        if (strcmp($imageInfo['mime'], "image/jpeg") == 0) {
            return $this->resize_jpeg($newW, $newH, $origW, $origH, $this->fullPath);
        }
        else if (strcmp($imageInfo['mime'], "image/gif") == 0) {
            return $this->resize_gif($newW, $newH, $origW, $origH, $this->fullPath);
        }
        else if (strcmp($imageInfo['mime'], "image/png") == 0) {
            return $this->resize_png($newW, $newH, $origW, $origH, $this->fullPath);
        }
        else {
            return false;
        }
    }
    
    function resize_jpeg($new_w, $new_h, $orig_w, $orig_h, $full_path) {
        $im = ImageCreateTrueColor($new_w, $new_h);
        $baseimage = ImageCreateFromJpeg($this->filePath);
        imagecopyResampled($im, $baseimage, 0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);
        imagejpeg($im, $full_path);
        imagedestroy($im);
        if (file_exists($full_path)) {
            return true;
        } else {
            return false;
        }
    }

    function resize_gif($new_w, $new_h, $orig_w, $orig_h, $full_path) {
        $im = ImageCreateTrueColor($new_w, $new_h);
        $baseimage = imagecreatefromgif($this->filePath);
        imagecopyResampled($im, $baseimage, 0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);
        imagegif($im, $full_path);
        imagedestroy($im);
        if (file_exists($full_path)) {
            return true;
        } else {
            return false;
        }
    }

    function resize_png($new_w, $new_h, $orig_w, $orig_h, $full_path) {
        $im = ImageCreateTrueColor($new_w, $new_h);
        $baseimage = imagecreatefrompng($this->filePath);
        imagecopyResampled($im, $baseimage, 0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);
        imagepng($im, $full_path);
        imagedestroy($im);
        if (file_exists($full_path)) {
            return true;
        } else {
            return false;
        }
    }

    public function getFullPath() {
        return $this->fullPath;
    }
}
