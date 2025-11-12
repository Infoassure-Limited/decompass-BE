<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class ConsultationFormMessage extends Mailable // implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data; //make it public so that the view resource can access it

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        return $this->subject('THE COMPASS-Consultation Schedule')->view('emails.consultation_message', compact('data'));
    }
}
