@extends('errors.layout')

@section('title', 'Access Forbidden - Clippy')

@php
    $code = '403';
    $title = 'Access Forbidden - Clippy';
    $message = 'You don\'t have permission to access this resource. Please check your credentials and try again.';
@endphp
