<?php
    /**
     * @var $students array
     */

    use entities\User;
    use utils\Utility;

?>
<div class="row">
    <div class="col">
        <h3 class="text-center">Робота зі студентами</h3>
        <div class="card mb-0 mt-4">
            <div class="card-header bg-default">
                <div class="row">
                    <div class="col mt-2">
                        Список студентів (<?= count($students); ?> чол.)
                    </div>
                </div>
            </div>
            <?php if (count($students)) { ?>
                <div class="card-body">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>Прізвище, ім'я, по батькові</th>
                            <th>Студентський квиток</th>
                            <th>Логін</th>
                            <th>Адреса електроної пошти</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($students as $student) { ?>
                            <tr>
                                <td><?= $student['name']; ?></td>
                                <td><?= $student['position']; ?></td>
                                <td><?= $student['login']; ?></td>
                                <td><?= $student['email']; ?></td>
                                <td><span class="badge badge-<?= User::getColorStatus($student['status']); ?>"><?= User::getStatusName($student['status']); ?></span></td>
                                <td><?php Utility::showActionStudent($student); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
