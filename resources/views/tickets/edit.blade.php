@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Tickets / Edit </h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="nome">ID</label>
                    <p class="form-control-static">{{$ticket->id}}</p>
                </div>
                <div class="form-group">
                     <label for="title">TITLE</label>
                     <input type="text" name="title" class="form-control" value="{{$ticket->title}}"/>
                </div>
                    <div class="form-group">
                     <label for="priority">PRIORITY</label>
                     <input type="text" name="priority" class="form-control" value="{{$ticket->priority}}"/>
                </div>
                    <div class="form-group">
                     <label for="state">STATE</label>
                     <input type="text" name="state" class="form-control" value="{{$ticket->state}}"/>
                </div>
                    <div class="form-group">
                     <label for="sla">SLA</label>
                     <input type="text" name="sla" class="form-control" value="{{$ticket->sla}}"/>
                </div>
                    <div class="form-group">
                     <label for="timeout">TIMEOUT</label>
                     <input type="text" name="timeout" class="form-control" value="{{$ticket->timeout}}"/>
                </div>



            <a class="btn btn-default" href="{{ route('tickets.index') }}">Back</a>
            <button class="btn btn-primary" type="submit" >Save</a>
            </form>
        </div>
    </div>


@endsection