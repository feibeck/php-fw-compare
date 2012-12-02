<?php

namespace Todo\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Todo\Entity\TodoRepository;
use Todo\Entity\UserRepository;

use Todo\Feed;

class FeedController extends AbstractActionController
{

    /**
     * @var TodoRepository
     */
    private $todoRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Constructor setting the EntityManager
     *
     * @param TodoRepository $todoRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        TodoRepository $todoRepository,
        UserRepository $userRepository
    )
    {
        $this->todoRepository = $todoRepository;
        $this->userRepository = $userRepository;
    }

    public function feedAction()
    {
        $userhash = $this->params()->fromRoute('userhash');
        $user = $this->userRepository->getByHash($userhash);

        if ($user == null) {
            throw new \InvalidArgumentException("Invalid hash");
        }

        $todos = $this->todoRepository->getTodosForUser($user);

        $urlOptions = array('force_canonical' => true);
        $appUrl  = $this->url()->fromRoute(
            'home',
            array(),
            $urlOptions
        );
        $feedUrl = $this->url()->fromRoute(
            'feed',
            array('userhash' => $userhash),
            $urlOptions
        );

        $feed = new Feed($user, $todos, $appUrl, $feedUrl);

        $response = $this->getResponse();

        $response->setContent($feed->getContent());
        $response->getHeaders()->addHeaders(
            array('Content-type' => 'application/rss+xml')
        );

        return $response;
    }

}
