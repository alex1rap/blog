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
                <div class="card">
                    <form action="/posts/{{$post->id}}">
                        <div class="btn-group mb-2">
                            @if($post->canLike)
                                <a href="/posts/{{$post->id}}/like" class="btn btn-success">
                                    <i class="bi bi-hand-thumbs-up"></i>
                                    {{$post->likes ?? 0}}
                                </a>
                            @else
                                <div class="input-group-text">
                                    <i class="bi bi-hand-thumbs-up"></i>
                                    {{$post->likes ?? 0}}
                                </div>
                            @endif
                            @if($post->canDislike)
                                <a href="/posts/{{$post->id}}/dislike" class="btn btn-danger">
                                    <i class="bi bi-hand-thumbs-down"></i>
                                    {{$post->dislikes ?? 0}}
                                </a>
                            @else
                                <div class="input-group-text">
                                    <i class="bi bi-hand-thumbs-down"></i>
                                    {{$post->dislikes ?? 0}}
                                </div>
                            @endif

                            @if($post->author == $userId)
                                <a href="/posts/{{$post->id}}/edit" class="btn btn-primary">{{__('Edit')}}</a>
                                {{csrf_field()}}
                                {!! method_field('delete') !!}
                                <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
