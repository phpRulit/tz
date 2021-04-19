<?php


namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $book_id
 */
class UserVoice extends Model
{
    protected $table = 'user_voices';

    public $timestamps = false;

    protected $guarded = ['id'];

}
