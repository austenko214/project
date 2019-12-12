<?php

namespace utils;

use models\Users;

class Utility
{
    public static function camelCase($action)
    {
        $words = explode('_', $action);
        for($i = 1; $i < count($words); $i++)
        	$words[$i] = ucfirst($words[$i]);
        return implode('', $words);
    }
    
    public static function notFound()
    {
        http_response_code(404);
        return View::render([], 'page_404');
    }
    
    public static function redirect($location, $code = 301, $is_exit = true)
    {
        header("Location: $location", 1, $code);
        if ($is_exit)
            exit;
    }

    public static function sendMail($mail_info)
    {
        $message = "<html><head><title>Нагадування паролю</title></head> 
                    <body><p><h3 style='text-align: center;'>Шановний(а) {$mail_info['name']}!</h3> 
                    <h4 style='text-align: justify;'>Логін: {$mail_info['login']}</h4>
                    <h4 style='text-align: justify;'>Пароль: {$mail_info['password']}</h4>";
        $headers  = 'MIME-Version: 1.0' . PHP_EOL;
        $headers .= 'Content-type: text/html; charset=utf-8' . PHP_EOL;
        $headers .= 'From: Адміністратор <andrii@ce.in.ua>';
        mail($mail_info['email'], 'Нагадування', $message, $headers);
    }

    public static function showActionStudent($student)
    {
        $does = Users::getChangeStatus($student['status']);
        $res = [];
        foreach ($does as $key => $button)
            $res[] = "<a href='/admin/block_student/?id={$student['id']}&status={$button[0]}' class='btn btn-$key form-control'>{$button[1]}</a>";
        echo implode('<br/>', $res);
    }
}