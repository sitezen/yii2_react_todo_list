<?php
/**
 * Created by PhpStorm.
 * User: str-nik
 * Date: 27.08.2018
 * Time: 10:45
 */

namespace app\models;



class Repo
{
    public static function addTask($jsonData){
        $data = json_decode($jsonData);
        if(empty($data)) return false;
        $task = new Tasks();
        $task->intId = $data->id;
        $task->taskName = $data->taskname;
        $task->status = $data->status;
        $task->priority = $data->priority;
        $task->uuid = $data->uuid;
        if(!$task->save()) return false;
        $task->refresh();
        foreach ($data->tags as $tag){
            $tagId = self::idOfTag($tag);
            $tagLink = new TasksTags();
            $tagLink->tagId = $tagId;
            $tagLink->taskId = $task->id;
            if(!$tagLink->save()) return false;
        }
        return true;
    }

    public static function idOfTag($tag){
        $tagModel = Tags::find()->where(['name' => $tag])->one();
        if(!empty($tagModel)) return $tagModel->id;
        $newTag = new Tags();
        $newTag->name = $tag;
        $newTag->save();
        $newTag->refresh();
        return $newTag->id;
    }

    public static function listData($filter){
        if(!empty($filter)){
            $filter = json_decode($filter);
            if(!empty($filter->tag)){
                $tagId = Tags::find()->where(['name' => $filter->tag])->one();
                if(empty($tagId)) return [];
                $ids = [];
                $tags = TasksTags::find()->where(['tagId' => $tagId])->all();
                foreach ($tags as $tagLink){
                    $ids[] = $tagLink->taskId;
                }
                // todo: ... (поиск по одному тегу)
            }
        }

        $tasks = Tasks::find()->all();
        $result = [];
        /** @var Tasks $task */
        foreach ($tasks as $task){
            $item = [];
            $item['id'] = $task->intId;
            $item['taskname'] = $task->taskName;
            $item['status'] = $task->status;
            $item['priority'] = $task->priority;
            $item['uuid'] = $task->uuid;

            $tags = [];
            /** @var TasksTags $tasksTag */
            foreach ($task->tasksTags as $tasksTag){
                $tags[] = $tasksTag->tag->name;
            }
            $item['tags'] = $tags;
            $result[] = $item;
        }
        return $result;
    }

    public static function deleteTask($uuid){
        if(empty($uuid)) return false;
        return Tasks::deleteAll(['uuid'=>$uuid]);
    }

    public static function update($uuid, $field, $newVal){
        $allowed_fields = ['taskName', 'tags', 'status', 'priority'];
        if(!in_array($field, $allowed_fields)) return false;

        /** @var Tasks $task */
        $task = Tasks::find()->where(['uuid'=>$uuid])->one();
        if(empty($task)) return false;

        if($field === 'tags'){
            $tags = json_decode($newVal);
            // todo: оставалось бы время - переделать не удаляя все теги
            TasksTags::deleteAll(['taskId' => $task->id]);
            foreach ($tags as $tag){
                $tagId = self::idOfTag($tag);
                $tagLink = new TasksTags();
                $tagLink->tagId = $tagId;
                $tagLink->taskId = $task->id;
                if(!$tagLink->save()) return false;
            }
            return true;
        }else{
            $task->$field = $newVal;
            return $task->save();
        }
        return false;
    }
}