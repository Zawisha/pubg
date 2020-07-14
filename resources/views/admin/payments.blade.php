<div class="alert alert-info">
    @lang('admin.payments.warning')
</div>
<table class="table-primary table table-striped  table table-striped">
    <thead>
    <tr role="row">
        <th class="row-header">
            #
        </th>
        <th class="row-header">
            @lang('admin.payments.date')
        </th>
        <th class="row-header">
            @lang('admin.payments.amount')
        </th>
        <th class="row-header">
            @lang('admin.payments.type')
        </th>
        <th class="row-header">
            @lang('admin.payments.status')
        </th>
        <th class="row-header">
            @lang('admin.payments.comment')
        </th>
        <th class="row-header">
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($transactions as $transaction)
        <tr role="row" class="odd">
            <td class="sorting_1">
                <div class="row-text">
                    {{$transaction->id}}
                </div>
            </td>
            <td class="sorting_1">
                <div class="row-text">
                    {{$transaction->created_at}}
                </div>
            </td>
            <td class="sorting_1">
                <div class="row-text">
                    {{$transaction->amount > 0 ? '+' : ''}}{{$transaction->amount}}
                </div>
            </td>
            <td class="sorting_1">
                <div class="row-text">
                    {{$transaction->getTypeName()}}
                </div>
            </td>
            <td class="sorting_1">
                <div class="row-text">
                    {{$transaction->getStatusText()}}
                </div>
            </td>
            <td class="sorting_1">
                <div class="row-text">
                    {{$transaction->getCommentsText()}}
                </div>
            </td>
            <td class="sorting_1">
                <div class="row-text">
                    {{--@if(strpos($transaction->reason, 'refunded') === false)--}}
                    {{--<a href="{{route('admin.transaction.rollback', ['$transaction' => $transaction])}}"--}}
                    {{--class="btn btn-primary btn-sm">Rollback</a>--}}
                    {{--@endif--}}
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>