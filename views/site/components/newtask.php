<?php
/**
 * Created by PhpStorm.
 * User: stnik
 * Date: 26.08.2018
 * Time: 20:33
 */
?>

<script type="text/babel">

  class Newtask extends React.Component {

    render() {
      return (
        <div>
          <table className="table">
            <tr>
              <td>
                <b><?=yii::t('app', 'Название') ?>:</b>
              </td>
              <td>
                <input id="taskName" style={{width: "100%"}}/>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>
                <b><?=yii::t('app', 'Приоритет') ?>:</b>
              </td>
              <td>
                <PriorityButton priority="1" rowId="0"/>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>
                <b><?=yii::t('app', 'Теги') ?>:</b>
              </td>
              <td>
                <Tags rowId={0} tags={[]} style={{width: "100%"}}/>
                <input id="newTags" type="hidden"/>
              </td>
            </tr>
          </table>
        </div>
      );
    }
  }

</script>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Новая задача</h4>
            </div>
            <div class="modal-body" id="newTask">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                <button onclick="saveTask()" type="button" class="btn btn-success" data-dismiss="modal">Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/babel">
  function saveTask() {
    console.log("Save task");
    let priority = $("#selP_0").val();
    let taskName = $("#taskName").val();
    let tags = $("#newTags").val();
    //console.log("pr=" + priority + " " + taskName+' '+tags);
    tags = tags.split(',');
    let uuid = guid();
    console.log(tags);
    store.dispatch({
      taskname: taskName,
      status: 0,
      tags: tags,
      priority: priority,
      type: 'ADD_TODO',
      uuid: uuid,
      newTask: true
    });
  }
  ReactDOM.render(
    <Newtask/>,
    document.getElementById("newTask")
  )

</script>

