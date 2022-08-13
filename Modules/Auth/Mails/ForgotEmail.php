<?php
namespace Modules\Auth\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotEmail extends Mailable
{
    use Queueable, SerializesModels;



    public function __construct(private $hash,private $mySubject)
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('auth::forgot')
            ->with([
                'hash' => $this->hash,
            ])->subject($this->mySubject);
    }
}
