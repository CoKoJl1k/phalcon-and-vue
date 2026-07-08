<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class SyncLog extends Model
{
    public $id;
    public $status;
    public $message;
    public $created_at;

    public function initialize()
    {
        $this->setSource('sync_logs');
    }
}
