<?php

/**
 * Description of Template
 *
 * @author richard_lovell
 */
class HeadTemplate {
    private $title;
    private $description;
    
    public function __construct($title, $description) {
        $this->title = $title;
        $this->description = $description;
    }
    
    function getTitle() {
        return $this->title;
    }

    function getDescription() {
        return $this->description;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setDescription($description) {
        $this->description = $description;
    }


}
