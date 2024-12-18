<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\SMSHistoryDataTable;
use Illuminate\Http\Request;

class SMSHistoryController extends Controller
{
    public function index(SMSHistoryDataTable $dataTable)
    {

        return $dataTable->render('admin.subscription.sms-history.index');

    }
}
