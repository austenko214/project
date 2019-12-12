<?php

namespace controllers;

use utils\View;
use utils\Utility;
use models\Users;
use models\TypeMaterials;
use entities\User;
use entities\TypeMaterial;


class Admin extends AuthController
{
    public function beforeAction()
    {
        parent::beforeAction();
        if ($_SESSION['user']->getCategoryName() !== 'admin') 
            Utility::redirect('/');
    }

	public function index()
	{
        $this->teachers();
    }
    
    public function teachers($msg = '')
    {
		$vars = [
            'msg' => $msg,
            'teachers' => Users::getTeachers(),
        ];
        View::render($vars, 'teachers');    
    }
    
    public function materials($msg = '')
    {
		$vars = [
            'msg' => $msg,
            'materials' => TypeMaterials::getTypeMaterials(),
        ];
        View::render($vars, 'type_materials');    
    }
    
    public function students($msg = '')
    {
		$vars = [
            'msg' => $msg,
            'students' => Users::getStudents(),
        ];
        View::render($vars, 'students');    
    }
    
    public function addTeacher($msg = '', $data = [])
    {
        $data['msg'] = $msg;
        View::render($data, 'teacher');    
    }
    
    public function addingTeacher()
    {
        if (Users::isUnique($_POST['login'], $_POST['email'])) {
            $this->addTeacher('Логін або адреса поштової скриньки не унікальні!', $_POST);
            return;
        }
        if ($_POST['pwd'] !== $_POST['pwd2']) {
            $this->addTeacher('Паролі не співпадають!', $_POST);
            return;
        }
        $_POST['password'] = $_POST['pwd'];
        unset($_POST['pwd'], $_POST['pwd2']);
        $_POST['name'] = $_POST['name1'] . ' ' . $_POST['name2'] . ' ' . $_POST['name3'];
        unset($_POST['name1'], $_POST['name2'], $_POST['name3']);
        $_POST['category'] = 2;
        $_POST['status'] = 1;
        $user = new User($_POST);
        $user->save(array_keys($_POST));
        $this->teachers();
    }
    
    public function blockTeacher()
    {
        $user = Users::getById($_GET['id']);
        if (empty($_GET['id']) || is_null($user)) {
            $this->teachers('Такого викладача не існує!');
            return;
        }
        $user->setStatus($_GET['status']);
        $user->save(array_keys($_GET));
        $this->teachers();
    }
    
    public function addMaterial($msg = '', $data = [])
    {
        $data['msg'] = $msg;
        View::render($data, 'material');    
    }
    
    public function addingMaterial()
    {
        if (TypeMaterials::isUnique($_POST['name'])) {
            $this->addMaterial('Назва типу матеріалу не унікальна!', $_POST);
            return;
        }
        $type_material = new TypeMaterial($_POST);
        $type_material->save(array_keys($_POST));
        $this->materials();
    }
    
    public function blockStudent()
    {
        $user = Users::getById($_GET['id']);
        if (empty($_GET['id']) || is_null($user)) {
            $this->teachers('Такого студента не існує!');
            return;
        }
        if (User::getStatusName($user->getStatus()) === 'awaiting' && $_GET['status'] == 1)
            Utility::sendMail($user->toArray());
        $user->setStatus($_GET['status']);
        $user->save(array_keys($_GET));
        $this->students();
    }
}
