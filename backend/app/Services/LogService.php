<?php

namespace App\Services;

use App\Models\SyncLog;
use Phalcon\Di\Injectable;

class LogService extends Injectable
{
    public function save(array $data): void
    {
        $log = new SyncLog();
        $log->status = $data['success'] ? 'success' : 'error';
        $log->message = json_encode([
            'created' => $data['created'] ?? 0,
            'updated' => $data['updated'] ?? 0,
            'errors'  => $data['errors'] ?? [],
        ]);
        $log->save();
    }
}
