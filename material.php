<?php
    /**
     * @var $name string
     */
?>
<div class="row">
    <div class="col">
        <h3 class="text-center">Додати тип навчальних матеріалів</h3>
        <div class="card mb-0">
            <div class="card-header bg-default">
                <div class="row">
                    <div class="col">
                        Новий тип матеріалу
                    </div>
                </div>
            </div>
            <form method="post" class="mb-0" action="/admin/adding_material">
                <div class="card-body mb-0">
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label for="name" class="col-auto mt-2">Назва</label>
                                <div class="col">
                                    <input class="form-control" id="name" type="text" name="name" autofocus value="<?= !empty($name) ? $name : ''; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <a href="/admin/materials" class="btn btn-dark form-control">Повернутися</a>
                        </div> 
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success form-control" id="btn_add_material">Додати</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#btn_add_material').click(() => {
        var name = $('#name').val().trim();
        if (name === "") {
            showError("Потрібно ввести назву матеріалу!");
            $('#name').focus();
            return false;
        }
        return true;
    });
</script>