<?php

namespace controllers;

use utils\View;
use utils\Utility;
use models\Users;

class Student extends AuthController
{
    public function beforeAction()
    {
        parent::beforeAction();
        if ($_SESSION['user']->getCategoryName() !== 'student') 
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
            'teachers' => Users::getTeachers(true),
        ];
        View::render($vars, 'select_teachers');    
    }
    
    public function subjects()
    {
        if (empty($_SESSION['teacher'])) {
            $this->teachers('Спочатку потрібно вибрати викладача!');
            return;
        }
		$vars = [
            'msg' => '',
        ];
        View::render($vars, 'select_subjects');    
    }
    
    public function messages()
    {
        if (empty($_SESSION['teacher'])) {
            $this->teachers('Спочатку потрібно вибрати викладача!');
            return;
        }
		$vars = [
            'msg' => '',
        ];
        View::render($vars, 'create_messages');    
    }

    public function selectTeacher()
    {
        $teacher = Users::getTeacherById($_GET['id']);
        if (is_null($teacher)) {
            $this->teachers('Такого викладача не існує!');    
            return;
        }
        $_SESSION['teacher'] = $teacher;
        $this->teachers();
    }
}
