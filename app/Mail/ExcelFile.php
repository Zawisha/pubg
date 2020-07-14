<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExcelFile extends Mailable
{
    use Queueable, SerializesModels;

    public $fileName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'),
            config('mail.from.name'))
            ->subject('Unitourn - файл со статистикой')
            ->attach($this->fileName)
            ->view('emails.file');
    }
}
