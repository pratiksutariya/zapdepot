<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ErrorLog;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function add_error_log($error_log) {
        $log = new ErrorLog;
        $log->user_id = $error_log['user_id'];
        $log->error_log = $error_log['error_log'];
        $log->zap_id = $error_log['zap_id'];
        $log->integration_type = $error_log['type'];
        $log->description = $error_log['description'];

        $log->save();
    }
}
