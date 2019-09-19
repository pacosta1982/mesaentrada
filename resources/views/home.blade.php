@extends('adminlte::page')

@section('title', 'Mesa de Entrada')


@section('content')
<section class="invoice">
    <form action="/filtros" method="post">
        @csrf
    <div class="row no-print">
      <div class="col-xs-6">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label>Número Expediente Matriz</label>
            <input type="text" class="form-control" maxlength="7"  name="nro_exp" value="{{isset($exp)?$exp:''}}" placeholder="Ingrese N° de Expediente Matriz" class="form-control">
        </div>
      </div>
    <div class="col-xs-6">
        <div class="form-group {{ $errors->has('state_id') ? 'has-error' : '' }}">
            <label >Código Proyecto</label>
            <input type="text" class="form-control" maxlength="7"  name="project_id" value="{{isset($pro)?$pro:''}}" placeholder="Ingrese Codigo de Proyecto" class="form-control">
        </div>
    <button type="submit" class="btn btn-primary btn-flat pull-right"><i class="fa fa-search"></i>Buscar</button>
    </div>
    </div>
    </form>
  </section>

  @if (isset($expediente))

  <section class="invoice">

        <!-- title row -->
        <div class="row">
          <div class="col-xs-6 ">
            <h4 class="page-header">
              <i class="fa fa-home"></i> <strong>Proyecto: {{ $project->name }}</strong>

            </h4>
          </div>
          <div class="col-xs-6 ">
            <h4 class="page-header">
              <i class="fa fa-file-pdf-o"></i>  <strong>Expediente Matriz N° {{(substr($expediente->NroExp,0,-2)).'-'.(substr($expediente->NroExp,-2))}} Serie: {!! $expediente->NroExpS !!}</strong>
            </h4>
          </div>
          <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-6 invoice-col">
                <strong>SAT:</strong>  {{ $project->sat_id?$project->getSat->NucNomSat:"" }}<br>
                <strong>Cant. Postulantes:</strong>  {{ $postulantes->count() }}<br>
                <strong>Departamento: </strong>{{ $project->state_id?$project->getState->DptoNom:"" }}<br>
                <strong>Distrito:</strong> {{ $project->city_id }}<br>

          </div>
          <!-- /.col -->
          <div class="col-sm-6 invoice-col">
                <strong>Solicitante:</strong>  {!! $expediente->NroExpsol !!}<br>
                <strong>Concepto:</strong> {!! $expediente->NroExpCon !!}<br>
                <strong>Recibido por:</strong> {!! $expediente->NUsuNombre !!}<br>
                <strong>Tipo Expediente:</strong> {!! $expediente->TexCod?$expediente->tiposol->TexDes:"" !!}<br>
          </div>

        </div>
        <br>
        <form action="/vincularpostulantes" method="post">
            @csrf
        <input type="hidden" name="nro_expv" value="{{$exp}}">
        <input type="hidden" name="project_idv" value="{{$pro}}">
        @if (!$pre)
            @if ($proexp)
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Alerta!</h4>
                El Proyecto ya fue Vinculado!!!
            </div>
            @else
            <button type="submit" class="btn btn-block btn-flat btn-success btn-lg"><i class="fa fa-users"></i> Vincular Postulantes a Matriz</button>
            @endif
        @else
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> Alerta!</h4>
            El expediente {{(substr($expediente->NroExp,0,-2)).'-'.(substr($expediente->NroExp,-2))}} Serie: {!! $expediente->NroExpS !!} ya posee vinculaciones.
          </div>
        @endif


        </form>
        <br>
        <table class="table">
                <tbody>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th class="text-center">Cédula</th>
                  <th class="text-center">Ingreso</th>
                  <th class="text-center">Nivel</th>
                  <th class="text-center">Miembros</th>
                </tr>
                @foreach($postulantes as $key=>$post)
                <tr>
                  <td>{{$key+1}}</td>
                  <td>{{ $post->postulante_id?$post->getPostulante->first_name:"" }} {{ $post->postulante_id?$post->getPostulante->last_name:"" }}</td>
                  <td class="text-center">{{ $post->postulante_id?$post->getPostulante->cedula:"" }} </td>
                  <td class="text-center">{{ number_format(App\Models\ProjectHasPostulantes::getIngreso($post->postulante_id),0,".",".") }} </td>
                  <td class="text-center">{{ App\Models\ProjectHasPostulantes::getNivel($post->postulante_id) }}</td>
                  <td class="text-center">{{ $post->getMembers->count() + 1 }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>


    </section>
  @endif
@stop
