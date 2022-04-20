@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('Dashboard') }}
                        <a href="/posts/create" class="btn btn-primary float-end">{{__('Create post')}}</a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>

        <h1 class="posts">{{__('Posts:')}}</h1>

        <div class="row mb-3">
            @foreach($posts as $post)

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="blog-post-title">{{ $post->title }}</h2>
                        </div>
                        <div class="card-body">
                            {{ $post->intro }}
                        </div>
                        <form class="card-footer" action="/posts/{{$post->id}}" method="post">
                            <div class="btn-group mb-2">
                                <a href="/posts/{{$post->id}}" class="btn btn-default">{{__('Read more')}}</a>

                                <div class="input-group-text">
                                    <i class="bi bi-hand-thumbs-up"></i>
                                    {{$post->likes ?? 0}}
                                </div>
                                <div class="input-group-text">
                                    <i class="bi bi-hand-thumbs-down"></i>
                                    {{$post->dislikes ?? 0}}
                                </div>

                                @if($post->author == $author)
                                    <a href="/posts/{{$post->id}}/edit" class="btn btn-primary">{{__('Edit')}}</a>
                                    {{csrf_field()}}
                                    {!! method_field('delete') !!}
                                    <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

            @endforeach
        </div>

    </div>
@endsection
