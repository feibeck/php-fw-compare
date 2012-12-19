<?php

namespace Todo\ViewHelper;

use Zend\View\Helper\AbstractHelper;

use Todo\Entity\Todo;

class Priority extends AbstractHelper
{

    public function __invoke(Todo $todo)
    {
        switch ($todo->getPriority()) {
            case Todo::PRIORITY_LOW:
                return "Low";
            case Todo::PRIORITY_NORMAL:
                return "Normal";
            case Todo::PRIORITY_HIGH:
                return "High";
        }
    }

}
