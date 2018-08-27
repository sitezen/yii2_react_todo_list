<?php
/**
 * Created by PhpStorm.
 * User: stnik
 * Date: 24.08.2018
 * Time: 13:30
 */

?>

<script type="text/babel">

  class Tasks extends React.Component {
    constructor(props) {
      super(props);
      this.state = {rows: this.props.state};
    }

    render() {
      return (
        <div>
        <table className="table table-striped table-bordered table-hover">
          <thead>
          <th style={{width: "40%"}}><?=yii::t('app', 'Название') // если нужна мультиязычность ?></th>
          <th>Приоритет</th>
          <th style={{width: "30%"}}>Теги</th>
          <th>Статус</th>
          <th>Действия</th>
          </thead>
          <tbody>
          {this.props.state !== undefined &&
          this.props.state.map((row, i) =>
              <Task row={row} />
          )
          }
          </tbody>
        </table>
        </div>
      );
    }
  }

  function mapStateToProps(state) {
    return { state: state }
  }

  var ConnApp = ReactRedux.connect(mapStateToProps)(Tasks)
</script>

