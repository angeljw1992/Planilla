@extends('layouts.admin')
@section('content')
@can('crear_empleado_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.crear-empleados.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.crearEmpleado.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'CrearEmpleado', 'route' => 'admin.crear-empleados.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.crearEmpleado.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-CrearEmpleado">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.crearEmpleado.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.crearEmpleado.fields.codigo_empleado') }}
                        </th>
                        <th>
                            {{ trans('cruds.crearEmpleado.fields.rol') }}
                        </th>
                        <th>
                            {{ trans('cruds.crearEmpleado.fields.restaurante') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach(App\Models\CrearEmpleado::ROL_SELECT as $key => $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach(App\Models\CrearEmpleado::RESTAURANTE_SELECT as $key => $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($crearEmpleados as $key => $crearEmpleado)
                        <tr data-entry-id="{{ $crearEmpleado->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $crearEmpleado->name ?? '' }}
                            </td>
                            <td>
                                {{ $crearEmpleado->codigo_empleado ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\CrearEmpleado::ROL_SELECT[$crearEmpleado->rol] ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\CrearEmpleado::RESTAURANTE_SELECT[$crearEmpleado->restaurante] ?? '' }}
                            </td>
                            <td>
                                @can('crear_empleado_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.crear-empleados.show', $crearEmpleado->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('crear_empleado_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.crear-empleados.edit', $crearEmpleado->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('crear_empleado_delete')
                                    <form action="{{ route('admin.crear-empleados.destroy', $crearEmpleado->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('crear_empleado_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.crear-empleados.massDestroy') }}",
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
  let table = $('.datatable-CrearEmpleado:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
})

</script>
@endsection