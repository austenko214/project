<div class="card mb-0 card-default">
    <div class="card-body">
        <div class="col text-lg text-center text-uppercase">
            <h2><?= empty($_SESSION['teacher']) ? 'Інтерактивний сайт викладачів кафедри ПЗАС' : "{$_SESSION['teacher']->getName()}<br/>{$_SESSION['teacher']->getPosition()}"; ?></h2>
        </div>
    </div>
</div>
