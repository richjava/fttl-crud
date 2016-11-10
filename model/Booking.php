<?php

/**
 * Description of Booking
 *
 * @author richard_lovell
 */
class Booking {
    private $id;
    private $flightName;
    private $flightDate;
    private $dateCreated;
    private $status;
    private $userId;
    private $imageUrl;
    
    function getId() {
        return $this->id;
    }

    function getFlightName() {
        return $this->flightName;
    }

    function getFlightDate() {
        return $this->flightDate;
    }

    function getDateCreated() {
        return $this->dateCreated;
    }

    function getUserId() {
        return $this->userId;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFlightName($flightName) {
        $this->flightName = $flightName;
    }

    function setFlightDate($flightDate) {
        $this->flightDate = $flightDate;
    }

    function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    function setUserId($user_id) {
        $this->userId = $user_id;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    function getImageUrl() {
        return $this->imageUrl;
    }

    function setImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
    }


}
