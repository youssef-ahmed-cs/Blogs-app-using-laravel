@extends('Layouts.app')
@section('title') Show @endsection
@section('content')

<div class="container">
    <div class="card mt-4">
        <div class="card-header">
            <b><b>Post Info</b></b>
        </div>
        <div class="card-body">
            <h5 class="card-title">Title: {{$post['title']}}</h5>
            <p class="card-text">Description: {{$post['description']}}</p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <b><b>Post Creator Info.</b></b>
        </div>
        <div class="card-body">
            <h5 class="card-title  font-bold">
                Name: {{$post->user_creator ? $post->user_creator->name : 'NOT FOUND'}}</h5>
            <p class="card-text  font-bold">
                Email: {{$post->user_creator ? $post->user_creator->email : 'NOT FOUND'}} </p>
            <p class="card-text  font-bold">Created At: {{$post->created_at->format('Y-m-d')}}</p>
        </div>
    </div>
@endsection

