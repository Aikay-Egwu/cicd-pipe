<?php

namespace App\Models;

use Database\Factories\TodoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string|null $subject
 * @property string $title
 * @property string|null $description
 * @property string|null $due_date
 * @property bool $complete
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['subject', 'title', 'description', 'due_date', 'complete'])]
class Todo extends Model
{
    /** @use HasFactory<TodoFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'complete' => 'boolean',
        ];
    }
}
