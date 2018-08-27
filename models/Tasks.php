<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $uuid
 * @property int $intId
 * @property string $taskName
 * @property int $status
 * @property int $priority
 *
 * @property TasksTags[] $tasksTags
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'intId', 'status', 'priority'], 'required'],
            [['intId', 'status', 'priority'], 'integer'],
            [['uuid'], 'string', 'max' => 36],
            [['taskName'], 'string', 'max' => 250],
            [['uuid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'intId' => 'Int ID',
            'taskName' => 'Task Name',
            'status' => 'Status',
            'priority' => 'Priority',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasksTags()
    {
        return $this->hasMany(TasksTags::className(), ['taskId' => 'id']);
    }
}
