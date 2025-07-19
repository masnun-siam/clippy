@extends('errors.layout')

@section('title', 'Service Unavailable - Clippy')

@php
    $code = '503';
    $title = 'Service Unavailable - Clippy';
    $message = 'Clippy is temporarily down for maintenance. We\'ll be back shortly with improvements!';
@endphp
