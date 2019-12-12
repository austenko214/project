<?php
    /**
     * @var $teachers array
     */

    use entities\User;

?>
<div class="row">
    <div class="col">
        <h3 class="text-center">Вибір викладача</h3>
        <div class="card mb-0 mt-4">
            <div class="card-header bg-default">
                <div class="row">
                    <div class="col mt-2">
                        Список викладачів (<?= count($teachers); ?> чол.)
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
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($teachers as $teacher) {
                            if (empty($_SESSION['teacher']))
                                $this_teacher = false;
                            else
                                $this_teacher = $_SESSION['teacher']->getId() == $teacher['id']; ?>
                            <tr <?= $this_teacher ? 'style="background-color: lightgreen"' : "onclick='location=\"/student/select_teacher/?id={$teacher['id']}\"'";?>>
                                <td><?= $teacher['name']; ?></td>
                                <td><?= $teacher['position']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>