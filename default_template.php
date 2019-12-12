<?php
    /**
     * @var $msg string
     */

    use entities\User;

    $not_left_panel = in_array($view, ['authorization', 'page_404', 'student']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/resources.css" rel="stylesheet">
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/jquery-3.3.1.js"></script>
    <title>Сторінка викладача</title>
</head>
<body>
    <?php require_once "views/header.php"; ?>  
    <div class="row" style="margin: 0px;">
        <div class="col-3<?= $not_left_panel ? ' d-none' : ''; ?> m-0 p-0">
            <div class="card mb-0">
                <div class="card-header bg-primary text-white">
                    <b>Доступні дії</b>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col">
                            <?php
                                $user = $_SESSION['user'];
                                if (!empty($user))
                                    echo "<b>{$user->getName()}</b><br/>{$user->getPosition()}";
                            ?>
                        </div>
                        <div class="col-auto">
                            <a href="/main/logout" class="btn btn-danger form-control text-left">&times;</a>
                        </div>
                    </div>
                    <?php if (!empty($user)) {                   
                        switch($user->getCategoryName()) {
                            case 'admin': ?>
                                <a href="/admin/teachers" class="btn btn-info form-control text-left mb-1">Викладачі</a>
                                <a href="/admin/materials" class="btn btn-info form-control text-left mb-1">Матеріали</a>
                                <a href="/admin/students" class="btn btn-info form-control text-left">Студенти</a>
                            <?php break;
                            case 'student': ?>
                                <a href="/student/teachers" class="btn btn-info form-control text-left mb-1">Викладачі</a>
                                <a href="/student/subjects" class="btn btn-info form-control text-left mb-1">Предмети</a>
                                <a href="/student/messages" class="btn btn-info form-control text-left">Зворотній зв'язок</a>
                            <?php break;
                            case 'teacher': ?>
                                <a href="/teacher/profile" class="btn btn-info form-control text-left mb-1">Особистий кабінет</a>
                                <a href="/teacher/subjects" class="btn btn-info form-control text-left mb-1">Предмети</a>
                                <a href="/teacher/materials" class="btn btn-info form-control text-left mb-1">Матеріали</a>
                                <a href="/teacher/messages" class="btn btn-info form-control text-left mb-1">Повідомлення</a>
                                <a href="/teacher/students" class="btn btn-info form-control text-left">Студенти</a>
                        <?php }
                    } ?>
                </div>
            </div>
        </div>
        <div class="col-<?= $not_left_panel ? 12 : 9; ?> m-0 p-0">
            <div class="card mb-0">
                <div class="card-body">
                    <?php require_once "views/$view.php"; ?>
                </div>
            </div>
        </div>
    </div>
    <?php require_once "views/footer.php"; ?>   
    <div class="card bg-danger d-none" style="width:30%; position:fixed; right:0; bottom:0" id="error_card">
        <div class="card-header text-white">Помилка</div>
        <div class="card-body text-justify" style="background-color:pink" id="error_body"></div>
    </div>
</body>
<script>
    function showError(msg)
    {
        $('#error_card').prop("classList").remove("d-none");
        $('#error_body').html('<b>' + msg + '</b>');
        setTimeout(() => {
            $('#error_card').prop("classList").add("d-none");
        }, 3000);
    }
    <?php
        if (!empty($msg))
            echo "showError('$msg');";
    ?>
</script>
</html>
