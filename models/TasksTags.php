<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasks_tags".
 *
 * @property int $id
 * @property int $taskId
 * @property int $tagId
 *
 * @property Tags $tag
 * @property Tasks $task
 */
class TasksTags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks_tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['taskId', 'tagId'], 'required'],
            [['taskId', 'tagId'], 'integer'],
            [['tagId'], 'exist', 'skipOnError' => true, 'targetClass' => Tags::className(), 'targetAttribute' => ['tagId' => 'id']],
            [['taskId'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['taskId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'taskId' => 'Task ID',
            'tagId' => 'Tag ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tags::className(), ['id' => 'tagId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'taskId']);
    }
}
