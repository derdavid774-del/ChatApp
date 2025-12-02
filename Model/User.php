<?php
namespace Model;

use JsonSerializable;

class User implements JsonSerializable {
    private $username;
    private $firstName;
    private $lastName;
    private $coffeeOrTea;
    private $description;
    private $chatLayout;
    private $history = array();

    public function __construct($username = null) {
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getCoffeeOrTea() {
        return $this->coffeeOrTea;
    }

    public function setCoffeeOrTea($coffeeOrTea) {
        $this->coffeeOrTea = $coffeeOrTea;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getChatLayout() {
        return $this->chatLayout;
    }

    public function setChatLayout($chatLayout) {
        $this->chatLayout = $chatLayout;
    }

    public function getHistory() {
        return $this->history;
    }

    public function setHistory($history) {
        $this->history = $history;
    }

    public function addToHistory() {
        if (!is_array($this->history)) {
            $this->history = array();
        }
        $this->history[] = date('Y-m-d H:i:s');
    }

    public function jsonSerialize():mixed {
        return get_object_vars($this);
    }

    public static function fromJson($data) {
        $user = new User();
        foreach ($data as $key => $value) {
            $user->{$key} = $value;
        }
        return $user;
    }
}
?>