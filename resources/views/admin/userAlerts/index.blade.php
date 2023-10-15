@extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row">
    <div class="col-md-auto">
        <h1>{{ trans('cruds.userAlert.title') }}</h1>
      </div>
      @can('user_alert_create')
      <div class="col-md-auto">
        <a class="btn btn-success" href="{{ route('admin.user-alerts.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.userAlert.title_singular') }}
        </a>
      </div>
      @endcan
</div>


<div class="card">
       <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-UserAlert">
                <thead>
                    <tr>
                        <th width="10">

                        </th>

                        <th>
                            {{ trans('cruds.userAlert.fields.alert_text') }}
                        </th>
                        <th>
                            {{ trans('cruds.userAlert.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userAlerts as $key => $userAlert)
                        <tr data-entry-id="{{ $userAlert->id }}">
                            <td>

                            </td>

                            <td>
                               <a href="{{ $userAlert->alert_link ?? '' }}" target="_blank"> {{ $userAlert->alert_text ?? '' }} </a>
                            </td>
                            <td>
                                {{ $userAlert->created_at ?? '' }}
                            </td>
                            <td>
                                @can('user_alert_delete')
                                    <form action="{{ route('admin.user-alerts.destroy', $userAlert->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('user_alert_delete')
  let deleteButtonTrans = '<i class="fas fa-trash" style="font-size:14px;"></i>'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.user-alerts.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-UserAlert:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
