<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Auth\Notifications\ResetPassword;


class MailResetPasswordNotification extends ResetPassword
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        parent::__construct($token);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        $link = url( env('URL_FRONTPAGE') . "reset-password/".$this->token );
        return ( new MailMessage )
            ->subject( 'Уведомление о сбросе пароля' )
            ->line( "Здравствуйте! Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи." )
            ->action( 'Сбросить пароль', $link )
            ->line( "Срок действия ссылки для сброса пароля истечет в ".config('auth.passwords.users.expire')." минут." )
            ->line( "Если вы не запрашивали сброс пароля, дальнейшие действия не требуются." );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray()
    {
        return [
            //
        ];
    }
}
