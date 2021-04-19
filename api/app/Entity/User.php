<?php

namespace App\Entity;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $role
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'users';

    const ROLE_USER = '0';
    const ROLE_ADMIN = '1';
    const ROLE_MODERATOR = '2';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function rolesList()
    {
        return [
            self::ROLE_USER => 'Пользователь',
            self::ROLE_MODERATOR => 'Модератор',
            self::ROLE_ADMIN => 'Админ',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\MailResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        $name = $this->name;
        $email = $this->email;
        $verification_code = Str::random(30);
        $crypt_code = Crypt::encrypt($verification_code);
        DB::table('user_verifications')->insert(['user_id'=>$this->id,'token'=>$verification_code]);
        $subject = "Подтвердите Вашу регистрацию.";
        Mail::send('email.verify', ['name' => $name, 'verification_code' => $crypt_code],
            function($mail) use ($email, $name, $subject){
                $mail->from(getenv('MAIL_FROM_ADDRESS'), "Название супер сайта");
                $mail->to($email, $name);
                $mail->subject($subject);
            });
    }

    public function changeRole($role)
    {
        //если роль не существует в списке ролей, то кидаем исключение
        if(!array_key_exists($role, self::rolesList())) {
            throw new \InvalidArgumentException('Неизвестная роль "' . $role . '"');
        }
        //если пытаемся изменить роль на такую же, то кидаем исключение
        if ($this->role === $role) {
            throw new \DomainException('Роль уже проставлена.');
        }
        //если проверки пройдены, об обновляем роль пользователю
        $this->update(['role' => $role]);
    }

    public function books()
    {
        return $this->hasMany(Books::class, 'user_id', 'id');
    }

    public function voices()
    {
        return $this->belongsTo(UserVoice::class, 'id', 'user_id');
    }
}
