<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCrearEmpleadoRequest;
use App\Http\Requests\StoreCrearEmpleadoRequest;
use App\Http\Requests\UpdateCrearEmpleadoRequest;
use App\Models\CrearEmpleado;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CrearEmpleadoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('crear_empleado_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $crearEmpleados = CrearEmpleado::all();

        return view('admin.crearEmpleados.index', compact('crearEmpleados'));
    }

    public function create()
    {
        abort_if(Gate::denies('crear_empleado_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.crearEmpleados.create');
    }

    public function store(StoreCrearEmpleadoRequest $request)
    {
        $crearEmpleado = CrearEmpleado::create($request->all());

        return redirect()->route('admin.crear-empleados.index');
    }

    public function edit(CrearEmpleado $crearEmpleado)
    {
        abort_if(Gate::denies('crear_empleado_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.crearEmpleados.edit', compact('crearEmpleado'));
    }

    public function update(UpdateCrearEmpleadoRequest $request, CrearEmpleado $crearEmpleado)
    {
        $crearEmpleado->update($request->all());

        return redirect()->route('admin.crear-empleados.index');
    }

    public function show(CrearEmpleado $crearEmpleado)
    {
        abort_if(Gate::denies('crear_empleado_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.crearEmpleados.show', compact('crearEmpleado'));
    }

    public function destroy(CrearEmpleado $crearEmpleado)
    {
        abort_if(Gate::denies('crear_empleado_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $crearEmpleado->delete();

        return back();
    }

    public function massDestroy(MassDestroyCrearEmpleadoRequest $request)
    {
        CrearEmpleado::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
