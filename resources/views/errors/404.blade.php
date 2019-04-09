@extends('errors::illustrated-layout')

@section('code', '404')
@section('title', __('No existe'))

@section('image')
<div style="background-image: url({{ asset('/svg/404.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
</div>
@endsection

@section('message', __('Disculpa, el recurso que buscas no existe.'))
