<?php

namespace Todo;

use Todo\Entity\Todo;
use Todo\Entity\User;

use Zend\Feed\Writer\Feed as FeedWriter;

class Feed
{

    /**
     * @var User
     */
    private $user;

    /**
     * @var Todo[]
     */
    private $todos;

    /**
     * @var FeedWriter
     */
    private $feed;

    /**
     * @param User   $user
     * @param Todo[] $todos
     * @param string $appUrl
     * @param string $feedUrl
     */
    public function __construct(User $user, $todos, $appUrl, $feedUrl)
    {
        $this->user = $user;
        $this->todos = $todos;

        $this->feed = new FeedWriter();

        $this->feed->setTitle(
            sprintf('Todo App - Todos for %s', $this->user->getEmail())
        );
        $this->feed->setGenerator('Todo App');
        $this->feed->setLink($appUrl);
        $this->feed->setFeedLink($feedUrl, 'rss');
        $this->feed->setDateModified(time());
        $this->feed->setDescription(
            sprintf('Todos for %s', $this->user->getEmail())
        );

        foreach ($this->todos as $todo) {
            $entry = $this->getEntry($todo);
            $this->feed->addEntry($entry);
        }
    }

    public function getContent()
    {
        $out = $this->feed->export('rss');
        return $out;
    }

    private function getEntry(Todo $todo)
    {
        $entry = $this->feed->createEntry();
        $entry->setTitle($todo->getTodo());
        $entry->setDateModified(time());
        $entry->setDateCreated(time());
        $entry->setDescription($todo->getTodo());
        return $entry;
    }

}
