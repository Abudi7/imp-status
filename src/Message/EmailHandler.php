<?php

namespace App\Message;

final class EmailHandler
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */
    private $subject;
    private $emailContent;

    public function __construct(string $to, string $subject, string $emailContent)
    {
        // Other initialization...

        $this->subject = $subject;
        $this->emailContent = $emailContent;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getEmailContent(): string
    {
        return $this->emailContent;
    }
//     private $name;

//     public function __construct(string $name)
//     {
//         $this->name = $name;
//     }

//    public function getName(): string
//    {
//        return $this->name;
//    }
}
