<?php

namespace models;

use db\Mysql;
use entities\User;

class Users
{
    public static function getUser($login, $pwd) 
    {
        $sql = "select * from users where login='$login' and password='$pwd' and status=1 limit 1";
        $res = Mysql::fetchOneAssoc($sql);
        if (!count($res))
            return null;
        return  new User($res);
    }

    public static function getForget($email) 
    {
        $sql = "select name, login, password, email from users where email='$email' and status=1 limit 1";
        $res = Mysql::fetchOneAssoc($sql);
        if (!count($res))
            return false;
        return $res;
    }

    public static function getTeachers($status = false) 
    {
        $whereStatus = '';
        if ($status)
            $whereStatus = ' and status=1';
        $sql = "select id, login, email, name, position, status from users where category=2$whereStatus";
        return Mysql::fetchAssoc($sql);
    }

    public static function getStudents() 
    {
        $sql = "select id, login, email, name, position, status from users where category=3";
        return Mysql::fetchAssoc($sql);
    }

    public static function isUnique($login, $email, $position = '')
    {
        $where_pos = '';
        if (!empty($position))
            $where_pos = " or position='$position'";
        $sql = "select count(*) from users where login='$login' or email='$email'$where_pos";
        return (bool)Mysql::fetchScalar($sql);
    } 

    public static function getById($id) 
    {
        $sql = "select * from users where id=$id limit 1";
        $res = Mysql::fetchOneAssoc($sql);
        if (!count($res))
            return null;
        return  new User($res);
    }

    public static function getTeacherById($id) 
    {
        $sql = "select * from users where id=$id and status=1 and category=2 limit 1";
        $res = Mysql::fetchOneAssoc($sql);
        if (!count($res))
            return null;
        return  new User($res);
    }

    public static function getChangeStatus($status)
    {
        $does = [
            0 => ['success' => [1, 'Відновити']],
            1 => ['danger' => [0, 'Блокувати']],
            2 => ['success' => [1, 'Підтвердити'], 'danger' => [0, 'Відмовити']],
        ];
        return $does[$status];
    }
}
