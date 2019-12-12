<?php

namespace entities;

use db\Mysql;

class User extends Entity
{
    private $id;
    private $login;
    private $password;
    private $email;
    private $category;
    private $name;
    private $position;
    private $status;

    const USER_STATUS_ACTIVE = 1;
    const USER_STATUS_BLOCKED = 0;
    const USER_STATUS_AWAITING = 2;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = (string)$login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($pwd)
    {
        $this->password = (string)$pwd;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($txt)
    {
        $this->email = (string)$txt;
    }
    
    public function getCategory()
    {
        return $this->category;
    }
    
    public function setCategory($num)
    {
        $this->category = (int)$num;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($txt)
    {
        $this->name = (string)$txt;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($txt)
    {
        $this->position = (string)$txt;
    }
            
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = (int)$status;
    }

    private $categoryName = [
        1 => 'admin',
        2 => 'teacher',
        3 => 'student',
    ];

    private static $statusName = [
        0 => 'blocked',
        1 => 'active',
        2 => 'awaiting',
    ];

    private static $colorStatus = [
        0 => 'danger',
        1 => 'success',
        2 => 'warning',
    ];

    public function getCategoryName()
    {
        return $this->categoryName[$this->getCategory()];
    }

    public static function getStatusName($status)
    {
        return self::$statusName[$status];
    }

    public static function getColorStatus($status)
    {
        return self::$colorStatus[$status];
    }

    public function save($fields_to_save)
    {
        if (empty($fields_to_save))
            return;
        $data = $this->toArray();
        if (empty($data['password']))
            unset($data['password']);
        foreach ($data as $data_field => $data_value)
            if (!in_array($data_field, $fields_to_save, true))
                unset($data[$data_field]);
        if (!$this->getId()) {
            $this->setId(Mysql::saveRow("users", $data));
        } else {
            Mysql::saveRow("users", $data);
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'login' => $this->getLogin(),
            'password' => $this->getPassword(),
            'email' => $this->getEmail(),
            'category' => $this->getCategory(),
            'name' => $this->getName(),
            'position' => $this->getPosition(),
            'status' => $this->getStatus(),
        ];
    }
}