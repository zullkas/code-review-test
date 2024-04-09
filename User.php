<?php

namespace models;

use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public static function getCommentsForPosts()
    {
        $posts = Post::find()->where(['user_id' => $this->id])->all();
        $result = [];
        foreach ($posts as $post)
        {
            $comments = Comment::find()->where(['post_id' => $post->id])->all();
            $result += $comments;
        }

        return $result;
    }

    public function getFriends($status = 1)
    {
        $friends = Friend::find()
            ->where(['user_id' => $this->id])
            ->andWhere(['status' => $status])
            ->all();

        return $friends;
    }

    protected function addFriend($userId)
    {
        $friend = new Friend();
        $friend->user_id = $this->id;
        $friend->friend_id = $userId;
        $friend->status = 1;
        $friend->save();
        return true;
    }

    protected function deleteFriend($userId)
    {
        $friend = Friend::find()->where(['friend_id' => $userId])->one();
        $friend->delete();
        return true;
    }

    public function hasPosts()
    {
        $posts = Post::find()->where(['user_id' => $this->id])->all();
        return count($posts) > 0;
    }

    public function hasActivePosts()
    {
        $activePosts = Post::find()->where(['user_id' => $this->id, 'status' => 1])->all();
        return count($activePosts) > 0;
    }
}
