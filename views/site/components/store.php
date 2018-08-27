<?php
/** Redux store
 * Created by PhpStorm.
 * User: str-nik
 * Date: 26.08.2018
 * Time: 14:19
 */
?>

  <script type="text/babel">


  const sortF = (a, b) => {
    if (a.status > b.status) return 1;
    if (a.status < b.status) return -1;

    if (a.priority > b.priority) return 1;
    if (a.priority < b.priority) return -1;

    if (a.id > b.id) return 1;
    if (a.id < b.id) return -1;

  };

  const sort = (state) => state.sort(sortF);

  // скорее, псевдо-uuid4
  function guid() {
    function s4() {
      return Math.floor((1 + Math.random()) * 0x10000)
        .toString(16)
        .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
  }

  const updateTask = (uuid, field, newVal) => {
    $.post("/site/api/", {action: 'UPDATE', field: field, uuid: uuid, newVal: newVal})
      .done(function (data) {
        console.log("Result: " + data);
        if(data==='error') alert("Произошла ошибка при изменении данных");
      });
  }

  const todos = (state = [], action) => {
    //console.log("Store dispatch... " + action.type);
    switch (action.type) {
      case 'ADD_TODO':
        let id =1;
        if(state.length) {
          state.forEach((v)=>{if(v.id > id) id = v.id});
          id++;
        }
        if(action.newTask) {
          action.id = id;
          $.post("/site/api/", {action: 'ADD', data: JSON.stringify(action)})
            .done(function (data) {
              console.log("Result: " + data);
            });
        }
        return sort([
          ...state,
          {
            id: id,
            taskname: action.taskname,
            status: action.status,
            tags: action.tags,
            priority: action.priority,
            uuid: action.uuid
          }
        ])
      case 'DELETE':
        $.post("/site/api/", {action: 'DELETE', uuid: action.uuid})
          .done(function (data) {
            console.log("Result: " + data);
            if(data==='error') alert("Произошла ошибка при изменении данных");
          });
        return state.map(todo =>
          (todo.id === action.id)
            ? {}
            : todo
        )
      case 'SET_STATUS':
        updateTask(action.uuid, 'status', action.status);
        return sort(state.map(todo =>
          (todo.id === action.id)
            ? {...todo, status: action.status}
            : todo
        ))
      case 'SET_TASKNAME':
        updateTask(action.uuid, 'taskName', action.taskname);
        return state.map(todo =>
          (todo.id === action.id)
            ? {...todo, taskname: action.taskname}
            : todo
        )
      case 'SET_TAGS':
        updateTask(action.uuid, 'tags', JSON.stringify(action.tags));
        return state.map(todo =>
          (todo.id === action.id)
            ? {...todo, tags: action.tags}
            : todo
        )
      case 'SET_PRIORITY':
        updateTask(action.uuid, 'priority', action.priority);
        return sort(state.map(todo =>
          (todo.id === action.id)
            ? {...todo, priority: action.priority}
            : todo
        ))
      default:
        return state
    }
  }

  var store = Redux.createStore(todos);

  $.post("/site/api/", {action: 'LIST', filter: ""})
    .done(function (data) {
      console.log("Data Loaded: ");
      let tasks = JSON.parse(data);
      console.log(tasks);
      tasks.forEach( (task)=>{
        task.type = 'ADD_TODO';
        task.newTask = false;
        store.dispatch(task)
      } )
    });


</script>
