<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success Page</title>
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
</head>
<body>
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Payment Successful!</h1>
        <p>Your payment has been processed successfully.</p>
        <p>PNR: {{ $payment->pnr }}</p>
        <p>Meal Option: {{ $payment->mealop ? 'Yes' : 'No' }}</p>
        <a href="{{ url('/') }}">Return to Home</a>
    </div>
@endsection