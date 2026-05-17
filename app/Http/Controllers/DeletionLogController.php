<?php

namespace App\Http\Controllers;

use App\Models\DeletionLog;
use Illuminate\Support\Facades\Session;

class DeletionLogController extends Controller
{
    public function index()
    {
        if (! Session::get('logged_in')) {
            return redirect()->route('login');
        }

        $logs = DeletionLog::query()
            ->orderByDesc('deleted_at')
            ->orderByDesc('id')
            ->paginate(50);

        return view('deletion-log.index', compact('logs'));
    }
}
