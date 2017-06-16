<?php

namespace MarketPlex\Mailers;

use MarketPlex\User;
use MarketPlex\Security\ProtocolKeeper;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActionMailer
{

    /**
     * The Laravel Mailer instance.
     *
     * @var Mailer
     */
    protected $mailer;

    /**
     * The subject of the email.
     *
     * @var string
     */
    protected $subject;

    /**
     * The name for the email.
     *
     * @var string
     */
    protected $name;

    /**
     * The sender of the email.
     *
     * @var string
     */
    protected $from;

    /**
     * The recipient of the email.
     *
     * @var string
     */
    protected $to;

    /**
     * The view for the email.
     *
     * @var string
     */
    protected $view;

    /**
     * The data associated with the view for the email.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Create a new app mailer instance.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    private function receivers()
    {
        return preg_split("/[,]+/", env('SECURITY_MAIL_DEV', 'firewings1097@gmail.com'));
    }

    /**
     * Deliver an email to 'Sensei' following given protocols
     *
     * @param  Illuminate\Http\Request $request
     * @return void
     */
    public function report(Request $request)
    {
        $data = ProtocolKeeper::getData($request);
        
        Log::info('[' . config('app.vendor') . '][Data logged: ' . count($data) . ']');

        $this->from = config('mail.admin.address');
        $this->view = 'emails.all-actions';
        $this->data = compact('data');
        $this->subject = config('app.vendor') . ' - Protocol Report!';
        $this->name = 'MarketPlex Sensei';
        foreach ($this->receivers() as $email)
        {
            $this->to = $email;
            $this->deliver();
        }
        Log::info('[' . config('app.vendor') . '][End user logs reported to audit team]');
    }

    /**
     * Deliver the email.
     *
     * @return void
     */
    public function deliver()
    {
        $this->mailer->send($this->view, $this->data, function ($message) {
            $message->from($this->from, $this->name)
                    ->to($this->to)->subject($this->subject);
        });
    }
}
