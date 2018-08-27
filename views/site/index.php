<?php
/* @var $this yii\web\View */

$this->title = 'Tasks';
?>
<br /><br /><br />
<h1>Задачи</h1>

<div class="row">
    <div class="col-md-10">

    </div>
    <div class="col-sm-2">
        <button id="addTask" class="btn-success btn-lg " data-toggle="modal" data-target="#myModal">Новая задача</button>
    </div>
</div>


<br /><hr /><br />

<?=$this->render('components/store') ?>
<?=$this->render('components/task') ?>
<?=$this->render('components/tasks') ?>
<?=$this->render('components/newtask') ?>

<script type="text/babel">

  ReactDOM.render(
    React.createElement(ReactRedux.Provider, {store},
    React.createElement(ConnApp)
  ),
  document.getElementById("app")
  )


</script>


