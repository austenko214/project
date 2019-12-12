<?php

namespace entities;

use db\Mysql;

class TypeMaterial extends Entity
{
    private $id;
    private $name;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($txt)
    {
        $this->name = (string)$txt;
    }

    public function save($fields_to_save)
    {
        if (empty($fields_to_save))
            return;
        $data = $this->toArray();
        foreach ($data as $data_field => $data_value)
            if (!in_array($data_field, $fields_to_save, true))
                unset($data[$data_field]);
        if (!$this->getId()) {
            $this->setId(Mysql::saveRow("type_materials", $data));
        } else {
            Mysql::saveRow("type_materials", $data);
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}