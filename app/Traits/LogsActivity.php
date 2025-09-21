<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            ActivityLog::log(
                'created',
                "Se creó un nuevo registro de {$model->getTable()}",
                $model
            );
        });

        static::updated(function (Model $model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);

            ActivityLog::log(
                'updated',
                "Se actualizó un registro de {$model->getTable()}",
                $model,
                $model->getOriginal(),
                $changes
            );
        });

        static::deleted(function (Model $model) {
            ActivityLog::log(
                'deleted',
                "Se eliminó un registro de {$model->getTable()}",
                $model
            );
        });
    }
} 