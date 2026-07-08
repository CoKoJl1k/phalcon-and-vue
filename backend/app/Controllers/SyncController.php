<?php

namespace App\Controllers;

use App\Models\SyncLog;
use App\Services\LogService;
use App\Services\SyncService;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use Phalcon\Di\Injectable;

/** @property LogService $logService */
/** @property SyncService $syncService */
/** @property Postgresql $db */
class SyncController extends Injectable
{
    public function syncAction(): string
    {
        $result = $this->syncService->run();
        $this->logService->save($result);

        return json_encode($result);
    }

    public function lastAction(): string
    {
        $log = SyncLog::findFirst(['order' => 'created_at DESC']);
        if (!$log) {
            return json_encode(null);
        }

        return json_encode([
            'id'         => $log->id,
            'status'     => $log->status,
            'message'    => json_decode($log->message, true),
            'created_at' => $log->created_at,
        ]);
    }
}
