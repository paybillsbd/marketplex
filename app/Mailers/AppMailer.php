<?php

namespace MarketPlex\Mailers;

use MarketPlex\User;
use Illuminate\Contracts\Mail\Mailer;

class AppMailer
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

    /**
     * Deliver the email confirmation to vendor.
     *
     * @param  User $user
     * @param  array $data
     * @return void
     */
    public function sendEmailConfirmationTo(User $user, array $data)
    {
        $this->from = config('mail.from.address');
        $this->to = $user->email;
        $this->view = 'auth.emails.confirm';
        $this->data = compact('user', 'data');
        $this->subject = config('app.vendor') . ' - Email verification!';
        $this->name = config('mail.from.name');

        $this->deliver();
    }

    /**
     * Deliver the email notification to vendor for approval status.
     *
     * @param  User $user
     * @param  array $data
     * @return void
     */
    public function sendEmailForApprovalNotificationTo(User $user, array $data)
    {
        $this->from = config('mail.from.address');
        $this->to = $user->email;
        $this->view = 'auth.emails.notification-approval';
        $this->data = compact('user', 'data');
        $this->subject = config('app.vendor') . ' - Elements Approval Notification!';
        $this->name = config('mail.from.name');

        $this->deliver();

        $this->subject = config('app.vendor') . ' - Elements Approval Notification!';
        $this->view = 'auth.emails.notification-approval-admin';
        $this->to = config('mail.admin.address');

        $this->deliver();
    }


    /**
     * Deliver the email confirmation to user for profile update.
     *
     * @param  User $user
     * @param  array $data
     * @return void
     */
    public function sendEmailProfileUpdateConfirmationTo(User $user, array $data)
    {
        $this->from = config('mail.from.address');
        $this->to = $user->email;
        $this->view = 'auth.emails.confirm-profile-edit';
        $this->data = compact('user', 'data');
        $this->subject = config('app.vendor') . ' - Profile change verification!';
        $this->name = config('mail.from.name');

        $this->deliver();
    }

    /**
     * Deliver the email confirmation to customer.
     *
     * @param  User $user
     * @return void
     */
    public function sendEmailConfirmationToCustomer(User $user)
    {
        $this->from = config('mail.from.address');
        $this->to = $user->email;
        $this->view = 'auth.emails.confirm-customer';
        $this->data = compact('user');
        $this->subject = config('app.vendor') . ' - Email verification!';
        $this->name = config('mail.from.name');

        $this->deliver();
    }

    /**
     * Delivers an email to admin for special login url.
     *
     * @return void
     */
    public function sendEmailToAdminForSignupUrl()
    {
        $original = str_random(10);
        $token = bcrypt($original);

        $this->from = config('mail.from.address');
        $this->to = config('mail.admin.address');
        $this->view = 'vendor.emails.admin-login-secured-url';
        $this->data = compact('token', 'original');
        $this->subject = config('app.vendor') . ' - Secured Login For Admin!';
        $this->name = config('mail.admin.name');
        
        $this->deliver();
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
