<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $infoMail;

    public function __construct($infoMail)
    {
        $this->infoMail = $infoMail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.notification')
                    ->subject($this->infoMail['subject'])
                    ->with([
                        'subject' => $this->infoMail['subject'],
                        'to' => $this->infoMail['address'],
                        'content' => $this->infoMail['content'],
                        'shop' => isset($this->infoMail['shop']) ? $this->infoMail['shop'] : null,
                    ]);
    }
}
