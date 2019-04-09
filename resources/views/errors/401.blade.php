@extends('errors::illustrated-layout')

@section('code', '401')
@section('title', __('Sin autorización'))

@section('image')
<div style="background-image: url({{ asset('/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
</div>
@endsection

@section('message', __('Disculpe, no tiene autorización para este recurso. '))
