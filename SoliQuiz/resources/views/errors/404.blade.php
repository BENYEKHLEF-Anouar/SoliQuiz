@extends('errors.layout')

@section('title', 'Page Introuvable')
@section('code', '404')
@section('message', 'Oups ! Page introuvable.')
@section('description', 'La ressource que vous recherchez semble avoir disparu ou a été déplacée définitivement.')

@section('icon')
<svg class="text-white size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
</svg>
@endsection
