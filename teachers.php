<?php
    /**
     * @var $teachers array
     */

     use entities\User;

?>
<div class="row">
    <div class="col">
        <h3 class="text-center">Робота з викладачами</h3>
        <div class="card mb-0 mt-4">
            <div class="card-header bg-default">
                <div class="row">
                    <div class="col mt-2">
                        Список викладачів (<?= count($teachers); ?> чол.)
                    </div>
                    <div class="col-auto">
                        <a href="/admin/add_teacher" class="btn btn-success form-control" id="btn_enter">Новий викладач</a>
                    </div>
                </div>
            </div>
            <?php if (count($teachers)) { ?>
                <div class="card-body">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>Прізвище, ім'я, по батькові</th>
                            <th>Посада</th>
                            <th>Логін</th>
                            <th>Адреса електроної пошти</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($teachers as $teacher) { ?>
                            <tr>
                                <td><?= $teacher['name']; ?></td>
                                <td><?= $teacher['position']; ?></td>
                                <td><?= $teacher['login']; ?></td>
                                <td><?= $teacher['email']; ?></td>
                                <td><span class="badge badge-<?= User::getColorStatus($teacher['status']); ?>"><?= User::getStatusName($teacher['status']); ?></span></td>
                                <td>
                                    <a href="/admin/block_teacher/?id=<?= $teacher['id']; ?>&status=<?= (1 - $teacher['status']); ?>" class="btn btn-<?= User::getColorStatus(1 - $teacher['status']); ?> form-control"><?= $teacher['status'] == 1 ? 'Блокувати' : 'Відновити'; ?></a>ё
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
