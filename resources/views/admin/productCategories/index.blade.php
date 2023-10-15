@extends('layouts.admin')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-md-auto">
            <h1>{{ trans('cruds.productCategory.title_singular') }}</h1>
          </div>
          @can('product_category_create')
          <div class="col-md-auto">
            <a class="btn btn-success" href="{{ route('admin.product-categories.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.productCategory.title_singular') }}
            </a>
          </div>
          @endcan
    </div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ProductCategory">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.productCategory.fields.name') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productCategories as $key => $productCategory)
                        <tr data-entry-id="{{ $productCategory->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.products.filter', ['category' => $productCategory->id]) }}">
                                    {{ $productCategory->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                @can('product_category_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.product-categories.edit', $productCategory->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('product_category_delete')
                                    <form action="{{ route('admin.product-categories.destroy', $productCategory->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('product_category_delete')
  let deleteButtonTrans = '<i class="fas fa-trash" style="font-size:14px;"></i>'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.product-categories.massDestroy') }}",
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
  let table = $('.datatable-ProductCategory:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
