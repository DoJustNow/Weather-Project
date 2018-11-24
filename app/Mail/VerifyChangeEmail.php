<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyChangeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verify_url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $verify_url)
    {
        //
        $this->verify_url = $verify_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.verify');
    }
}
