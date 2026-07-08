<?php

namespace App\Controllers;

use App\Models\SyncLog;
use App\Services\SyncService;
use App\Stripe\MockStripeClient;
use Phalcon\Di\Injectable;

class SyncController extends Injectable
{
    public function syncAction(): string
    {
        $stripeClient = new MockStripeClient();
        $syncService = new SyncService($this->db, $stripeClient);
        $result = $syncService->run();

        $log = new SyncLog();
        $log->status = $result['success'] ? 'success' : 'error';
        $log->message = json_encode([
            'created' => $result['created'] ?? 0,
            'updated' => $result['updated'] ?? 0,
            'errors'  => $result['errors'] ?? [],
        ]);
        $log->save();

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
