@extends('errors.layout')

@section('title', 'Server Error - Clippy')

@php
    $code = '500';
    $title = 'Server Error - Clippy';
    $message = 'Something went wrong on our end. Our team has been notified and is working to fix this issue.';
@endphp
