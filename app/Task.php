<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 * @package App
 *
 * @property int $status
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $doer_id
 * @property int $owner_id
 * @property int $project_id
 * @property string $rate
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property string $status_label
 * @property File[] $files
 */
class Task extends Model
{
    const STATUS_OPEN = 1;
    const STATUS_DONE = 2;
    const STATUS_WAIT_FOR_PAY = 3;
    const STATUS_CLOSED = 4;
    const STATUS_ON_HOLD = 5;
    const STATUS_IN_PROCESS = 6;

    public $appends = ['status_label'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'ext');
    }

    static public function statusLabels()
    {
        return [
            self::STATUS_ON_HOLD => [
                'id' => self::STATUS_ON_HOLD,
                'name' => 'Ожидает решения',
                'class' => 'text-warning',
            ],
            self::STATUS_OPEN => [
                'id' => self::STATUS_OPEN,
                'name' => 'Открыта',
            ],
            self::STATUS_IN_PROCESS => [
                'id' => self::STATUS_IN_PROCESS,
                'name' => 'В работе',
            ],
            self::STATUS_DONE => [
                'id' => self::STATUS_DONE,
                'name' => 'Готова',
            ],
            self::STATUS_WAIT_FOR_PAY => [
                'id' => self::STATUS_WAIT_FOR_PAY,
                'name' => 'Ожидает оплаты',
            ],
            self::STATUS_CLOSED => [
                'id' => self::STATUS_CLOSED,
                'name' => 'Закрыта',
            ],
        ];
    }

    static public function statusLabelsList()
    {
        return [
            [
                'id' => self::STATUS_ON_HOLD,
                'name' => 'Ожидает решения',
                'class' => 'text-info',
            ],
            [
                'id' => self::STATUS_OPEN,
                'name' => 'Открыта',
                'class' => 'text-danger',
            ],
            [
                'id' => self::STATUS_IN_PROCESS,
                'name' => 'В работе',
                'class' => 'text-danger',
            ],
            [
                'id' => self::STATUS_DONE,
                'name' => 'Готова',
                'class' => 'text-success',
            ],
            [
                'id' => self::STATUS_WAIT_FOR_PAY,
                'name' => 'Ожидает оплаты',
                'class' => 'text-primary',
            ],
            [
                'id' => self::STATUS_CLOSED,
                'name' => 'Закрыта',
                'class' => 'text-secondary',
            ],
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::statusLabels()[$this->status]['name'];
    }
}
