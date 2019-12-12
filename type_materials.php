<?php
    /**
     * @var $materials array
     */

?>
<div class="row">
    <div class="col">
        <h3 class="text-center">Довідник типів навчальних матеріалів</h3>
        <div class="card mb-0 mt-4">
            <div class="card-header bg-default">
                <div class="row">
                    <div class="col mt-2">
                        Список типів навчальних матеріалів (<?= count($materials); ?> шт.)
                    </div>
                    <div class="col-auto">
                        <a href="/admin/add_material" class="btn btn-success form-control" id="btn_enter">Новий тип</a>
                    </div>
                </div>
            </div>
            <?php if (count($materials)) { ?>
                <div class="card-body">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>Назва</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($materials as $material) { ?>
                            <tr>
                                <td><?= $material['name']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
