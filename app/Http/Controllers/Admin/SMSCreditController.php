<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\SMSCreditDataTable;
use App\Models\SMSCredit;
use App\Models\SMSHistory;
use Illuminate\Http\Request;

class SMSCreditController extends Controller
{
    public function index(SMSCreditDataTable $dataTable)
    {

        $total_credit                 = SMSCredit::sum('quantity') ?? 0;
        $usage                        = SMSHistory::where('status', 'sent')->sum(column: 'count') ?? 0;
        $data['available_credit']     = $total_credit - $usage;
        $data['sms_delivered']        = $usage;
        $data['last_credit_added']    = SMSCredit::orderBy('id', 'desc')->pluck('quantity')->first() ?? 0;
        $data['sms_rejected']         = SMSHistory::where('status', 'rejectd')->sum(column: 'count') ?? 0;

        return $dataTable->render('admin.subscription.sms-credit.index', $data);



    }
}
