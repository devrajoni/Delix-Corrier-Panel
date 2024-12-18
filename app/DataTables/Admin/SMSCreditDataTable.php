<?php

namespace App\DataTables\Admin;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\SMSCredit;
use Yajra\DataTables\Services\DataTable;

class SMSCreditDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addIndexColumn()
        ->addColumn('title', fn($query) => $query->title)
        ->addColumn('description', fn($query) => $query->description)
        ->addColumn('date_time', function ($query) {
            return view('admin.subscription.sms-credit.column.date_time', compact('query'));
        })->addColumn('type', function ($query) {
            return view('admin.subscription.sms-credit.column.type', compact('query'));
        })->addColumn('quantity', function ($query) {
            return view('admin.subscription.sms-credit.column.quantity', compact('query'));
        })
        ->setRowId('id');
    }
    public function query(SMSCredit $model): QueryBuilder
    {
        $query      = $model::query();
        $query->when(request('search')['value'] ?? false, function ($query) {
            $search = request('search')['value'];

            $query->where(function ($query) use ($search) {
                $query->where('type', 'like', "%$search%")
                      ->orWhere('quantity', 'like', "%$search%");
            });
        });

        return $query->latest();
    }


    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->setTableAttribute('style', 'width:99.8%')
            ->footerCallback('function ( row, data, start, end, display ) {

                $(".dataTables_length select").addClass("form-select form-select-lg without_search mb-3");
                selectionFields();
            }')
            ->parameters([
                'dom'        => 'Blfrtip',
                'buttons'    => [
                    [],
                ],
                'lengthMenu' => [[10, 25, 50, 100, 250,500], [10, 25, 50, 100, 250,500]],
                'language'   => [
                    'searchPlaceholder' => __('search'),
                    'lengthMenu'        => '_MENU_ '.__('sms_credit_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(10),
            Column::computed('date_time')->title(__('date_time')),
            Column::computed('title')->title(__('title')),
            Column::computed('description')->title(__('description')),
            Column::computed('type')->title(__('type')),
            Column::computed('quantity')->title(__('quantity')),
        ];
    }

    protected function filename(): string
    {
        return 'sms_history'.date('YmdHis');
    }
}
