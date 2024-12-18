<?php

namespace App\DataTables\Admin;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\SMSHistory;
use Yajra\DataTables\Services\DataTable;

class SMSHistoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addIndexColumn()
        ->addColumn('date_time', function ($query) {
            return view('admin.subscription.sms-history.column.date_time', compact('query'));
        })->addColumn('phone', function ($query) {
            return view('admin.subscription.sms-history.column.phone', compact('query'));
        })->addColumn('message', function ($query) {
            return view('admin.subscription.sms-history.column.message', compact('query'));
        })->addColumn('sms_count', function ($query) {
            return view('admin.subscription.sms-history.column.sms_count', compact('query'));
        })->addColumn('status', function ($query) {
            return view('admin.subscription.sms-history.column.status', compact('query'));
        })
        ->setRowId('id');
    }
    public function query(SMSHistory $model): QueryBuilder
    {
        $query      = $model::query();
        $query->when(request('search')['value'] ?? false, function ($query) {
            $search = request('search')['value'];

            $query->where(function ($query) use ($search) {
                $query->where('status', 'like', "%$search%")
                      ->orWhere('message_id', 'like', "%$search%")
                      ->orWhere('to', 'like', "%$search%");
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
                'lengthMenu' => [[10, 25, 50, 100, 250], [10, 25, 50, 100, 250]],
                'language'   => [
                    'searchPlaceholder' => __('search'),
                    'lengthMenu'        => '_MENU_ '.__('sms_history_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(60),
            Column::computed('date_time')->title(__('date_time'))->addClass('w-10')->addClass('text-center'),
            Column::computed('phone')->title(__('phone'))->addClass('text-center'),
            Column::computed('message')->title(__('message'))->addClass('text-center'),
            Column::computed('sms_count')->title(__('sms_count'))
            ->exportable(false)->addClass('text-center')
            ->printable(false)
            ->width(60),
            Column::computed('status')->title(__('status'))->addClass('text-center')
            ->exportable(false)
            ->printable(false)
            ->width(60),

        ];
    }

    protected function filename(): string
    {
        return 'sms_history'.date('YmdHis');
    }
}
