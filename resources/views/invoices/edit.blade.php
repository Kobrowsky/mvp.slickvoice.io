@extends('layouts.app')

@section('title', 'Edit Invoice')

@section('content')
    {!! Form::model($invoice, ['route' => ['invoices.update', $invoice->id], 'method' => 'put', 'id' => 'invoice-edit']) !!}
    @include('invoices.partials.form')
    {!! Form::close() !!}
@endsection