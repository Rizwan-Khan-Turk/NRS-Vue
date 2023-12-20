<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($po_number = null)
    {
        $this->po_number = $po_number;
    }

    public function build()
    {

        return $this->subject('Purchase Order Status')
            ->view('emails.welcome',['po_number' => $this->po_number]); // View file for email content
    }
}
