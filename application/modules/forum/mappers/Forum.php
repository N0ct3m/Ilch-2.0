<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Modules\Forum\Models\ForumItem as ForumItem;
use Modules\Forum\Models\ForumPost as PostModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;

class Forum extends \Ilch\Mapper
{
    public function getForumItemsByParent($forumId, $itemId)
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('forum_items')
                ->where(['forum_id' => $forumId, 'parent_id' => $itemId])
                ->order(['sort' => 'ASC'])
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new ForumItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setDesc($itemRow['description']);
            $itemModel->setParentId($itemId);
            $itemModel->setForumId($forumId);
            $itemModel->setPrefix($itemRow['prefix']);
            $itemModel->setReadAccess($itemRow['read_access']);
            $itemModel->setReplayAccess($itemRow['replay_access']);
            $itemModel->setCreateAccess($itemRow['create_access']);
            $itemModel->setSubItems($this->getForumItemsByParent('1',$itemRow['id']));
            $itemModel->setTopics($this->getCountTopicsById($itemRow['id']));
            $itemModel->setLastPost($this->getLastPostByTopicId($itemRow['id']));
            $itemModel->setPosts($this->getCountPostsById($itemRow['id']));
            $items[] = $itemModel;
        }

        return $items;
    }

    public function getForumById($id)
    {
        $itemRows = $this->db()->select('*')
                ->from('forum_items')
                ->where(['id' => $id])
                ->order(['sort' => 'DESC'])
                ->execute()
                ->fetchAssoc();

        if (empty($itemRows)) {
            return null;
        }

        $itemModel = new ForumItem();
        $itemModel->setId($itemRows['id']);
        $itemModel->setType($itemRows['type']);
        $itemModel->setTitle($itemRows['title']);
        $itemModel->setDesc($itemRows['description']);
        $itemModel->setParentId($itemRows['parent_id']);
        $itemModel->setForumId($itemRows['forum_id']);
        $itemModel->setPrefix($itemRows['prefix']);
        $itemModel->setReadAccess($itemRows['read_access']);
        $itemModel->setReplayAccess($itemRows['replay_access']);
        $itemModel->setCreateAccess($itemRows['create_access']);

        return $itemModel;
    }

    public function getForumByTopicId($topicId)
    {
        $select = $this->db()->select();
        $result = $select->fields(['t.id', 't.topic_id'])
            ->from(['t' => 'forum_topics'])
            ->join(['i' => 'forum_items'], 'i.id = t.topic_id', 'LEFT', ['i.id', 'i.type', 'i.title', 'i.description', 'i.prefix', 'i.parent_id', 'i.forum_id', 'i.read_access', 'i.replay_access', 'i.create_access'])
            ->where(['t.id' => $topicId]);

        $items = $result->execute();

        $itemRows = $items->fetchAssoc();

        if (empty($itemRows)) {
            return null;
        }

        $itemModel = new ForumItem();
        $itemModel->setId($itemRows['id']);
        $itemModel->setType($itemRows['type']);
        $itemModel->setTitle($itemRows['title']);
        $itemModel->setDesc($itemRows['description']);
        $itemModel->setParentId($itemRows['parent_id']);
        $itemModel->setForumId($itemRows['forum_id']);
        $itemModel->setPrefix($itemRows['prefix']);
        $itemModel->setReadAccess($itemRows['read_access']);
        $itemModel->setReplayAccess($itemRows['replay_access']);
        $itemModel->setCreateAccess($itemRows['create_access']);

        return $itemModel;
    }

    public function getLastPostByTopicId($topicId)
    {
        $sql = 'SELECT `t`.`id`, `t`.`topic_id`, `p`.`read`, `p`.`id`, `p`.`topic_id`, `p`.`date_created`, `p`.`user_id`
                FROM `[prefix]_forum_topics` AS `t`
                LEFT JOIN `[prefix]_forum_posts` AS `p` ON `t`.`id` = `p`.`topic_id`
                WHERE `t`.`topic_id` = '.$topicId.'
                ORDER BY `p`.`id` DESC';
        $fileRow = $this->db()->queryRow($sql);

        if (empty($fileRow)) {
            return null;
        }

        $entryModel = new PostModel();
        $userMapper = new UserMapper();
        $entryModel->setId($fileRow['id']);
        if ($userMapper->getUserById($fileRow['user_id'])) {
            $entryModel->setAutor($userMapper->getUserById($fileRow['user_id']));
        } else {
            $entryModel->setAutor($userMapper->getDummyUser());
        }
        $entryModel->setDateCreated($fileRow['date_created']);
        $entryModel->setTopicId($fileRow['topic_id']);
        $entryModel->setRead($fileRow['read']);
        $posts = $this->getCountPostsByTopicId($fileRow['topic_id'])-1;
        $page = floor($posts / 20)+1;
        $entryModel->setPage($page);

        return $entryModel;
    }

    public function getCatByParentId($id)
    {
        $itemRows = $this->db()->select('*')
                ->from('forum_items')
                ->where(['id' => $id])
                ->order(['sort' => 'ASC'])
                ->execute()
                ->fetchAssoc();

        if (empty($itemRows)) {
            return null;
        }

        $itemModel = new ForumItem();
        $itemModel->setId($itemRows['id']);
        $itemModel->setType($itemRows['type']);
        $itemModel->setTitle($itemRows['title']);
        $itemModel->setDesc($itemRows['description']);
        $itemModel->setParentId($itemRows['parent_id']);
        $itemModel->setForumId($itemRows['forum_id']);
        $itemModel->setPrefix($itemRows['prefix']);
        $itemModel->setReadAccess($itemRows['read_access']);
        $itemModel->setReplayAccess($itemRows['replay_access']);
        $itemModel->setCreateAccess($itemRows['create_access']);

        return $itemModel;
    }

    public function saveItem(ForumItem $forumItem)
    {
        $fields = [
            'title' => $forumItem->getTitle(),
            'forum_id' => $forumItem->getForumId(),
            'sort' => $forumItem->getSort(),
            'parent_id' => $forumItem->getParentId(),
            'type' => $forumItem->getType(),
            'description' => $forumItem->getDesc(),
            'prefix' => $forumItem->getPrefix(),
            'read_access' => $forumItem->getReadAccess(),
            'replay_access' => $forumItem->getReplayAccess(),
            'create_access' => $forumItem->getCreateAccess()
        ];

        foreach ($fields as $key => $value) {
            if ($value === null) {
                unset($fields[$key]);
            }
        }

        $itemId = (int)$this->db()->select('id')
            ->from('forum_items')
            ->where(['id' => $forumItem->getId()])
            ->execute()
            ->fetchCell();

        if ($itemId) {
            $this->db()->update('forum_items')
                ->values($fields)
                ->where(['id' => $itemId])
                ->execute();
        } else {
            $itemId = $this->db()->insert('forum_items')
                ->values($fields)
                ->execute();
        }

        return $itemId;
    }
 
    public function deleteItem($forumItem)
    {
        $topicMapper = new TopicMapper();
        $id = $forumItem->getId();
        $topics = $topicMapper->getTopicsByForumId($id);
        foreach ($topics as $topic){
            $topicMapper->deleteById($topic->getId());
        }
        $this->db()->delete('forum_items')
            ->where(['id' => $id])
            ->execute();
    }

    public function getForumItems($forumId)
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('forum_items')
                ->where(['forum_id' => $forumId])
                ->order(['sort' => 'ASC'])
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new ForumItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setDesc($itemRow['description']);
            $itemModel->setParentId($itemRow['parent_id']);
            $itemModel->setForumId($forumId);
            $itemModel->setPrefix($itemRow['prefix']);
            $itemModel->setReadAccess($itemRow['read_access']);
            $itemModel->setReplayAccess($itemRow['replay_access']);
            $itemModel->setCreateAccess($itemRow['create_access']);
            $items[] = $itemModel;
        }

        return $items;
    }

    public function getCountPostsById($id)
    {
        $sql = 'SELECT COUNT(`p`.`topic_id`), `t`.`id`, `t`.`topic_id`, `p`.`id`, `p`.`topic_id`
                FROM `[prefix]_forum_topics` AS `t`
                LEFT JOIN `[prefix]_forum_posts` AS `p` ON `t`.`id` = `p`.`topic_id`
                WHERE `t`.`topic_id` = '.$id.'
                GROUP BY `t`.`id`, `t`.`topic_id`, `p`.`id`, `p`.`topic_id`';

        $topics = $this->db()->queryCell($sql);

        if (empty($topics)) {
            return '0';
        }

        return $topics;
    }

    public function getCountPostsByTopicId($id)
    {
        $sql = 'SELECT COUNT(id)
                FROM [prefix]_forum_posts
                WHERE topic_id = '.$id;
        $topics = $this->db()->queryCell($sql);

        if (empty($topics)) {
            return '0';
        }

        return $topics;
    }

    public function getCountTopicsById($id)
    {
        $sql = 'SELECT COUNT(`topic_id`)
                FROM `[prefix]_forum_topics`
                WHERE `topic_id` ='.$id;

        $topics = $this->db()->queryCell($sql);

        if (empty($topics)) {
            return '0';
        }

        return $topics;
    }

    public function getForumPermas()
    {
        $sql = 'SELECT * FROM `[prefix]_forum_items`';
        $permas = $this->db()->queryArray($sql);
        $permaArray = [];

        if (empty($permas)) {
            return null;
        }

        foreach ($permas as $perma) {
            $permaArray[$perma['title']] = $perma;
        }

        return $permaArray;
    }
}
