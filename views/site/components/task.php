<?php
/**
 * Created by PhpStorm.
 * User: stnik
 * Date: 22.08.2018
 * Time: 13:13
 */

?>

<script type="text/babel">
  const priorities = ["Высокий", "Средний", "Низкий"];
  const statuses = ["В работе", "Завершена"];

  class StatusButton extends React.Component {

    constructor(props) {
      super(props);
      this.state = {status: this.props.status};
      this.press = this.press.bind(this);
    }

    static  classByStatus(status){
      const classes = ["btn-success btn-sm", "btn-danger btn-sm"];
      return classes[status];
    }

    press(){
      let status = (this.props.status===0)?1:0;
      store.dispatch({type: 'SET_STATUS', id: this.props.rowId, status: status, uuid: this.props.uuid});
      //this.setState({status: status});
    }

    render() {
      return <button onClick={this.press} style={{width: "100%"}}
                     className={StatusButton.classByStatus(this.props.status)}>{statuses[this.props.status]}</button>;
    }
  }

  class PriorityButton extends React.PureComponent {

    constructor(props) {
      super(props);
      this.state = {priority: this.props.priority};
      this.myRef = React.createRef();
    }

    /* Выпадающий список из бутстрапа был бы красивее, но из-за virtual DOM возникают некоторые сложности,
    *  впрочем, эти же проблемы были успешно решены ниже для Tags, так что и этот код теперь можно переделать аналогично
    *
        static  classByPriority(priority){
          const classes = ["danger", "warning", "success"];
          return "btn btn-sm dropdown-toggle btn-" + classes[priority];
        }

       clicked = (ev) => {
          console.log("clicked");
       }

        componentDidMount(){
          console.log(this.myRef.current);
          this.myRef.current.oninput = this.clicked;
          $(".link"+this.props.rowId).on("click",
            (event)=>{
              console.log($(event.target).data("id"));
              r.setState($(event.target).data("id"));
          });
        }

        render() {
          return <div ref={this.myRef} className="btn-group">
            <button type="button" className={PriorityButton.classByPriority(this.state.priority)} data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Action <span className="caret" />
            </button>
            <ul className="dropdown-menu">
              {priorities.map((row, i) =>
                <li><a  className={"link"+this.props.rowId} data-id={i} id={"dd_"+this.props.rowId+"_"+i} href="#">{row}</a></li>
              )}
            </ul>
          </div>;
        }
       */

    colorByPriority(priority){
      const colors = ["red", "#550", "green"];
      return colors[priority];
    }


    setPriority = (ev) => {
      console.log($(ev.target).data('id'));
      store.dispatch({type: 'SET_PRIORITY', id: $(ev.target).data('id'),
        priority: ev.target.value, uuid: this.props.uuid});
    };

     render(){
      return (this.props.rowId>0)?(<span><select style={{color: this.colorByPriority(this.props.priority), width: "100%"}}
        id={"selP_"+this.props.rowId} value={this.props.priority} data-id={this.props.rowId} onChange={this.setPriority}>
        {priorities.map((row, i) =>
          <option selected={(i===this.props.priority)?"selected":""} value={i}>{row}</option>
        )}
      </select></span>):
        (<span><select style={{color: this.colorByPriority(this.props.priority), width: "100%"}}
                       id={"selP_"+this.props.rowId} data-id={this.props.rowId} onChange={this.setPriority}>
        {priorities.map((row, i) =>
          <option selected={(i===this.props.priority)?"selected":""} value={i}>{row}</option>
        )}
      </select></span>)
    }

  }

  class TaskName extends React.PureComponent{

    constructor(props) {
      super(props);
    }

    setName = (ev) => {
      let newName = prompt("Введите новое название:", $(ev.target).html());
      if(newName) {
        store.dispatch({type: 'SET_TASKNAME', id: this.props.rowId, taskname: newName, uuid: this.props.uuid});
      }
    };

    render(){
      return <span onClick={this.setName} style={{width: "100%", cursor: "pointer"}}>
        {this.props.taskName}
      </span>
    }
  }

  class DelButton extends React.Component {

    constructor(props) {
      super(props);
      this.press = this.press.bind(this);
    }

    press(){
      if(!confirm("Действительно удалить задачу?")) return;
      store.dispatch({type: 'DELETE', id: this.props.rowId, uuid: this.props.uuid});
    }

    render() {
      return <button onClick={this.press} style={{width: "100%"}}
                     className="btn btn-danger">Удалить</button>;
    }
  }

  class Tags extends React.PureComponent{

    constructor(props) {
      super(props);
      this.myRef = React.createRef();
      this.prevTags = this.props.tags.join(',');
    }

    componentDidMount(){
      // todo: add suggestions
      this.myRef.current.itemAdded = this.changed;
      this.myRef.current.itemRemoved = this.changed;
      $(this.myRef.current).tagsinput();
    }

    componentDidUpdate(){
      let ti = $(this.myRef.current);
      ti.tagsinput('destroy');
      ti.tagsinput();
    }

    componentWillUnmount(){
      $(this.myRef.current).tagsinput('removeAll');
    }

    changed = (ev) => {
      if(this.myRef.current.value!==this.prevTags) {
        //console.log("changed "+this.props.rowId);
        this.prevTags = this.myRef.current.value;
        const tags = this.myRef.current.value.split(',');
        //console.log("Tags changed!");
        if(this.props.rowId === 0){
           $("#newTags").val(this.myRef.current.value);
        }else {
          store.dispatch({type: 'SET_TAGS', id: this.props.rowId, tags: tags, uuid: this.props.uuid});
        }
        this.render();
      }
    };

    render(){
      return <input  ref={this.myRef} id={"tags_"+this.props.rowId} className="tags"
                    type="text" value={this.props.tags} />
    }
  }


  class Task extends React.Component {

    constructor(props) {
      super(props);
      this.state = {taskName: "", priority: priorities.indexOf("Средний"), status: 0, tags: []};
    };

    render() {
      const row = this.props.row;
      if(!row.id) return "";
      return (
        <tr>
        <td><TaskName rowId={row.id} uuid={row.uuid} taskName={row.taskname} /></td>
        <td><PriorityButton priority={row.priority} uuid={row.uuid} rowId={row.id} /></td>
        <td><Tags rowId={row.id} tags={row.tags} uuid={row.uuid} /></td>
        <td><StatusButton rowId={row.id} status={row.status} uuid={row.uuid} /> </td>
         <td><DelButton uuid={row.uuid} rowId={row.id} /></td>
        </tr>
      )
    }
  }
</script>

