<?php

namespace App\Controllers;

use App\Models\SyncLog;
use App\Services\LogService;
use App\Services\SyncService;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use Phalcon\Di\Injectable;
use Phalcon\Http\ResponseInterface;

/** @property LogService $logService */
/** @property SyncService $syncService */
/** @property Postgresql $db */
class SyncController extends Injectable
{
    public function syncAction(): ResponseInterface
    {
        $result = $this->syncService->run();
        $this->logService->save($result);

        return $this->response->setJsonContent($result);
    }

    public function lastAction(): ResponseInterface
    {
        $log = SyncLog::findFirst(['order' => 'created_at DESC']);

        return $this->response->setJsonContent([
            'id'         => $log?->id,
            'status'     => $log?->status,
            'message'    => $log?->message ? json_decode($log->message, true) : null,
            'created_at' => $log?->created_at,
        ]);
    }
}
