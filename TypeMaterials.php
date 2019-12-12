<?php

namespace models;

use db\Mysql;

class TypeMaterials
{
    public static function getTypeMaterials() 
    {
        $sql = "select * from type_materials";
        return Mysql::fetchAssoc($sql);
    }

    public static function isUnique($name)
    {
        $sql = "select count(*) from type_materials where name='$name'";
        return (bool)Mysql::fetchScalar($sql);
    } 
}
