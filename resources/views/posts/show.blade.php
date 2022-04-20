@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 blog-main">
                <div class="blog-post">
                    <h2 class="blog-post-title">{{$post->title}}</h2>
                    <p>
                        {{$post->body}}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
