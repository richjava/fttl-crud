<?php

/**
 * Description of UserMapper
 *
 * @author richard_lovell
 */
class UserMapper {
    private function __construct() {
    }

    /**
     * Maps an array to a User object.
     * @param User $user
     * @param array $properties
     */
    public static function map(User $user, array $properties) {
        if (array_key_exists('id', $properties)) {
            $user->setId($properties['id']);
        }
        if (array_key_exists('first_name', $properties)) {
            $user->setFirstName($properties['first_name']);
        }
        if (array_key_exists('last_name', $properties)) {
            $user->setLastName($properties['last_name']);
        }
        if (array_key_exists('email', $properties)) {
            $user->setEmail($properties['email']);
        }
        if (array_key_exists('password', $properties)) {
            $user->setPassword($properties['password']);
        }
    }
}
