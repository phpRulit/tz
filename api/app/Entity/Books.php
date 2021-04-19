<?php


namespace App\Entity;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $author
 * @property string $description
 * @property int $sum_voices
 * @property int $count_voices
 * @property float $estimate
 * @property string $img
 * @property Carbon $created_at
 */
class Books extends Model
{
    protected $table = 'books';

    protected $guarded = ['id'];
}
