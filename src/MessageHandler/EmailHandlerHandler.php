<?php

namespace App\MessageHandler;

use App\Message\EmailHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class EmailHandlerHandler implements MessageHandlerInterface
{
    public function __invoke(EmailHandler $message)
    {
        // do something with your message
    }
}
