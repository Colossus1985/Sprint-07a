@extends('movies.home')
@section('content')
<link href="{{ asset('/app.css') }}" rel="stylesheet">
    <div class="d-flex flex-column mt-4">
        <div class="d-flex flex-column">
            <div class="d-flex flex-row mb-3">
                <div class="pull-right">
                    <a class="btn btn-primary me-3" href="{{ route('home') }}" enctype="multipart/form-data"> Back</a>
                </div>
                @auth
                @if (Auth::user()->admin == true)
                <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('create') }}">Add New Movie</a>
                </div>
                <div class="ms-3">
                    <a class="btn btn-primary" href="{{ route('users') }}">Users</a>
                </div>
                @endif
                @endauth
            </div>
        </div>
        
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif
                @if ($message != null || $message !='')
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif

        @foreach ($movies as $movie)
        <!-- DEBUT DE MON affiche DU FILM  -->
        <div class="d-flex flex-row">
            <div class="">
                <img style="width: 23rem; height: 30rem" src="{{ Storage::url($movie->img) }}"  alt="{{ $movie->name }}">
            </div>
            <div class="d-flex flex-column justify-content-between ms-4">
                <div class="d-flex flex-column movie-details-content">
                    <h2>{{ $movie->name }}</h2>
                    <div class="d-flex flex-row mb-3">
                        <span class="fw-bolder">🎞 {{ $movie ->genre }}</span>
                        <span class="ms-5 fw-bolder">🗓 {{ $movie ->release }}</span>
                        <span class="ms-5 fw-bolder">🕒 {{ $movie ->time }}</span>
                        @guest
                            <span class="ms-5">👍</span>
                        @endguest
                        @auth
                            <form class="ms-4" action="{{ route('updateLikePlus', $movie->id) }}" method="GET">
                                <input class="visually-hidden" name="inputLikes" value="{{ Auth::user()->likes }}">
                                <input class="visually-hidden" name="likePlusOld" value="{{ $movie ->likeplus }}" readonly>
                                <button class="btn" tupe="submit" name="likePlus" readonly>👍</button>
                            </form>
                        @endauth
                        <span class="ms-1 fw-bolder">{{ $movie ->likeplus }}</span>
                        @guest
                            <span class="ms-5">👎</span>
                        @endguest
                        @auth
                            <form class="ms-4" action="{{ route('updateLikeMoins', $movie->id) }}" method="GET">
                                <input class="visually-hidden" name="inputLikes" value="{{ Auth::user()->likes }}">
                                <input class="visually-hidden" name="likeMoinsOld" value="{{ $movie ->likemoins }}" readonly>
                                <button class="btn" type="submit" name="likeMoins" readonly>👎</button>
                            </form>
                        @endauth
                        <span class="ms-1 fw-bolder">{{ $movie ->likemoins }}</span>
                    </div>
                    <p>{{ $movie ->synopsis }} 
                </div>
                @auth
                @if (Auth::user()->admin == true)
                <div class="d-flex flex-row">
                    <form class="d-flex flex-fill" action="{{ route('movies.destroy', $movie->id) }}" method="Post"> 
                        <a class="btn btn-primary flex-fill me-2" href="{{ route('movies.edit', $movie->id) }}">Edit</a>
                        @csrf 
                        @method('DELETE')                                      
                        <button type="submit" class="btn btn-danger flex_fill" onclick="return confirm('The Movie and all Comments will be deleted');">Delete</button> 
                    </form>
                </div>
                @endif
                @endauth
                @endforeach
            </div>
        </div>
            <!-- FIN L'AFFICHE FILM  -->
        <div>
            <div>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                <h3>Comments</h3>
                @auth
                    <div class="form-floating">
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="text" name="inputMovieName" class="visually-hidden" value="{{ $movie->name }}" readonly>
                            <input type="text" name="inputMovieId" class="visually-hidden" value="{{ $movie->id }}" readonly>
                            <input type="text" name="inputPseudo" class="visually-hidden" value="{{ Auth::user()->pseudo }}" readonly>
                            <div class="form-group form-floating mb-3">
                                <textarea type="text" name="commentArea" class="form-control" id="floatingName" maxlength="1000" style="height: 100px"></textarea>
                                <label for="floatingName">Leave a comment here</label>
                            </div>
                            <button type="submit" class="form-control btn btn-info">Send Comment</button>
                        </form>
                    </div>  
                @endauth
                @guest
                    <div class="form-floating">
                        <p>To let a comment, please login!</p>
                    </div>
                @endguest
                @foreach ($comments as $comment)
                    <div class="d-flex flex-column border border-3 rounded my-1 px-2 pb-2">
                        <div class="d-flex flex-row justify-content-end">
                            <div class="flex-fill">
                                <p class="mb-0">{{ \Carbon\Carbon::parse($comment->created_at)->locale('nl')->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div class="flex-fill">
                                <p class="mb-0">{{ $comment->pseudo }}</p>
                            </div>
                            <div class="flex-fill">
                                @if ($comment->admin == true)
                                <p class="mb-0">Status : 👑</p>
                                @elseif ($comment->admin == false)
                                <p class="mb-0">Status : ♟️</p>
                                @endif
                            </div>
                            <div class="flex-fill">
                                <p class="mb-0">Likes : <strong>{{ $comment->likes }}</strong></p>
                            </div>
                            <div class="flex-fill">
                                <p class="mb-0">Comments : <strong>{{ $comment->comments }}</strong></p>
                            </div>
                            
                        </div>
                        <div class="d-flex">
                            <p class="overflow-hidden">{{ $comment->comment }}</p>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="d-flex flex-row">
                                <div class="d-flex flex-row">
                                    <form class="ms-3" action="{{ route('updateLikePlusComment', $comment->id) }}" method="GET">
                                        @auth
                                            <input class="visually-hidden" name="inputLikes" value="{{ Auth::user()->likes }}" readonly>
                                            <input class="visually-hidden" name="likePlusOld" value="{{ $comment->likeplus }}" readonly>
                                            <input class="visually-hidden" name="inputIdMovie" value="{{ $movie->id }}" readonly>
                                        @endauth
                                        @guest
                                            <p>👍</p>
                                        @endguest
                                        @auth
                                            <button class="btn"  type="submit" name="likePlus" readonly>👍</button>
                                        @endauth
                                    </form>
                                    <p class="mb-0 fw-bolder">{{ $comment->likeplus }}</p>
                                </div>
                                <div class="d-flex flex-row">
                                    <form class="ms-3" action="{{ route('updateLikeMoinsComment', $comment->id) }}" method="GET">
                                        @auth
                                            <input class="visually-hidden" name="inputLikes" value="{{ Auth::user()->likes }}" readonly>
                                            <input class="visually-hidden" name="likeMoinsOld" value="{{ $comment->likemoins }}" readonly>
                                            <input class="visually-hidden" name="inputIdMovie" value="{{ $movie->id }}" readonly>
                                        @endauth
                                        @guest
                                            <p>👎</p>
                                        @endguest
                                        @auth
                                            <button class="btn"  type="submit" name="likeMoins" readonly>👎</button>
                                        @endauth
                                    </form>
                                    <p class="mb-0 fw-bolder">{{ $comment->likemoins }}</p>
                                </div>
                            </div>
                            <div>
                                @auth
                                @if (Auth::user()->pseudo == $comment->pseudo || Auth::user()->admin == true )
                                <div class="d-flex flex-row">
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"> 
                                        <input class="visually-hidden" name="inputNameMovie" value="{{ $movie->name }}">
                                            <input class="visually-hidden" name="inputIdComment" value="{{ $comment->id }}">
                                            @if (Auth::user()->pseudo == $comment->pseudo)
                                            <a class="btn btn-primary" href="{{ route('comments.edit', $comment->id, $movie->id) }}">Modify</a>
                                            @endif
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('The Comment will be deleted');">Delete</button> 
                                        </form>
                                    </div> 
                                @endif
                                @endauth
                            </div> 
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection