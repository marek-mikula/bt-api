<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $user_id
 * @property array $results
 * @property int $correct
 * @property int $wrong
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 */
class QuizResult extends Model
{
    use HasFactory;

    protected $table = 'quiz_results';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'results',
        'correct',
        'wrong',
    ];

    protected $casts = [
        'results' => 'array',
    ];

    /**
     * @see QuizResult::$user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
