<?php

namespace App\Http\Controllers;

use App\Models\Movies;
use App\Models\Comments;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class likeController extends Controller
{
    
//######---mainView.php---################################################################### 
    public function updateLikePlus(Request $request, $id)
    {   
        $pseudo_user = Auth::user()->pseudo;
//-----check if already give a like or dislike------------------------- 
        $checkLikePlus = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_movie', '=', $id)
            ->where('likeplus', '=', true)
            ->get();
            
        $checkLikeMoins = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_movie', '=', $id)
            ->where('likemoins', '=', true)
            ->get();
            
        if (count($checkLikePlus) > 0 || count($checkLikeMoins) > 0){
            return redirect() -> back()
                ->with('success', 'You\'ve already liked this movie.');
        }

//----update table like for checks later------------------------------
        $likesPlusOld = trim($request->get('likePlusOld'));
        $likesPlusNew = (int)$likesPlusOld + 1;

        DB::table('movies')
            ->where('id', '=', $id)
            ->update(['likeplus' => $likesPlusNew]);
        
        DB::table('likes')->insert([
                'id_movie' => $id,
                'pseudo' => $pseudo_user,
                'likeplus' => true,
        ]);

    //-----update number of likes in users table--------------------------
        $nb_likesOld = trim($request->get('inputLikes'));
        $nb_likesNew = (int)$nb_likesOld + 1;
        DB::table('users')
            ->where('pseudo', '=', $pseudo_user)
            ->update(['likes' =>  $nb_likesNew]);
        
        return redirect() -> back();
    }

    public function updateLikeMoins(Request $request, $id)
    {
    //-----check if already give a like or dislike------------------------- 
        $pseudo_user = Auth::user()->pseudo;
        $checkLikePlus = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_movie', '=', $id)
            ->where('likeplus', '=', true)
            ->get();

        $checkLikeMoins = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_movie', '=', $id)
            ->where('likemoins', '=', true)
            ->get();
            
        if (count($checkLikePlus) > 0 || count($checkLikeMoins) > 0) {
            return redirect() -> back()
                ->with('success', 'You\'ve already disliked this movie.');
        }
        
    //----update table like for checks later------------------------------
        $likeMoinsOld = trim($request->get('likeMoinsOld'));
        $likeMoinsNew = (int)$likeMoinsOld + 1;

        DB::table('movies')
            ->where('id', '=', $id)
            ->update(['likemoins' => $likeMoinsNew]);
        
        DB::table('likes')->insert([
                'id_movie' => $id,
                'pseudo' => $pseudo_user,
                'likemoins' => true,
            ]);

    //-----update number of likes in users table--------------------------
        $nb_likesOld = trim($request->get('inputLikes'));
        $nb_likesNew = (int)$nb_likesOld + 1;
        DB::table('users')
            ->where('pseudo', '=', $pseudo_user)
            ->update(['likes' =>  $nb_likesNew]);

        return redirect() -> back();
    }


//######---detailMovie.php---###############################################################  
    public function updateLikePlusDetailMovie(Request $request, $id)
    {
        $pseudo_user = Auth::user()->pseudo;
        $dataMovie['movies'] = Movies::query()
                    ->where('id', '=', $id)
                    ->get();
            
        $dataComments['comments'] = Comments::rightJoin('users', 'comments.pseudo', '=', 'users.pseudo')
                    ->select('comments.*', 'users.likes', 'users.admin', 'users.comments')
                    ->where('comments.id_movie', '=', $id)
                    ->get();

    //-----check if already give a like or dislike------------------------- 
        $checkLikePlus = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_movie', '=', $id)
            ->where('likeplus', '=', true)
            ->get();
                    
        $checkLikeMoins = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_movie', '=', $id)
            ->where('likemoins', '=', true)
            ->get();

                    
        if (count($checkLikePlus) > 0 || count($checkLikeMoins) > 0){
            return view('movies.detailMovie', $dataMovie, $dataComments)
            ->with('success', 'You\'ve already liked this movie.');
        }
        
    //----update table like for checks later------------------------------
        $likesPlusOld = Auth::user()->likes;
        $likesPlusNew = (int)$likesPlusOld + 1;

        DB::table('movies')
            ->where('id', '=', $id)
            ->update(['likeplus' => $likesPlusNew]);
        
        DB::table('likes')->insert([
                'id_movie' => $id,
                'pseudo' => $pseudo_user,
                'likeplus' => true,
        ]);
    
//-----update number of likes in users table--------------------------
        $nb_likesOld = trim($request->get('inputLikes'));
        $nb_likesNew = (int)$nb_likesOld + 1;
        DB::table('users')
            ->where('pseudo', '=', $pseudo_user)
            ->update(['likes' =>  $nb_likesNew]);
    
        return view('movies.detailMovie', $dataMovie, $dataComments);
    }

    public function updateLikeMoinsDetailMovie(Request $request, $id)
    {   
        $pseudo_user = Auth::user()->pseudo;

    //-----check if already give a like or dislike------------------------- 
        $checkLikePlus = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_movie', '=', $id)
            ->where('likeplus', '=', true)
            ->get();
                    
        $checkLikeMoins = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_movie', '=', $id)
            ->where('likemoins', '=', true)
            ->get();
                    
        if (count($checkLikePlus) > 0 || count($checkLikeMoins) > 0){
            $dataMovie['movies'] = Movies::query()
                ->where('id', '=', $id)
                ->get();

            $dataComments['comments'] = Comments::rightJoin('users', 'comments.pseudo', '=', 'users.pseudo')
                ->select('comments.*', 'users.likes', 'users.admin', 'users.comments')
                ->where('comments.id_movie', '=', $id)
                ->get();
            $message = 'You\'ve already disliked this movie.';
            return view('movies.detailMovie', $dataMovie, $dataComments, compact('message'));
        }
            
    //----update table like for checks later------------------------------

        $likeMoinsOld = trim($request->get('likeMoinsOld'));
        $likeMoinsNew = (int)$likeMoinsOld + 1;

        DB::table('movies')
            ->where('id', '=', $id)
            ->update(['likemoins' => $likeMoinsNew]);
        
        DB::table('likes')->insert([
                'id_movie' => $id,
                'pseudo' => $pseudo_user,
                'likemoins' => true,
            ]);

    //-----update number of likes in users table--------------------------
        $nb_likesOld = Auth::user()->likes;
        $nb_likesNew = (int)$nb_likesOld + 1;
        DB::table('users')
            ->where('pseudo', '=', $pseudo_user)
            ->update(['likes' =>  $nb_likesNew]);

        $dataMovie['movies'] = Movies::query()
            ->where('id', '=', $id)
            ->get();

        $dataComments['comments'] = Comments::rightJoin('users', 'comments.pseudo', '=', 'users.pseudo')
            ->select('comments.*', 'users.likes', 'users.admin', 'users.comments')
            ->where('comments.id_movie', '=', $id)
            ->get();

        return view('movies.detailMovie', $dataMovie, $dataComments);
    }

    public function updateLikePlusComment(Request $request, $id_comment)
    {
        $pseudo_user = Auth::user()->pseudo;

    //-----check if already give a like or dislike-------------------------

        $checkLikePlus = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_comment', '=', $id_comment)
            ->where('likeplus', '=', true)
            ->get();

        $checkLikeMoins = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_comment', '=', $id_comment)
            ->where('likemoins', '=', true)
            ->get();
            
        if (count($checkLikePlus) > 0 || count($checkLikeMoins) > 0) {
            $id_movie = trim($request->get('inputIdMovie'));
            $dataMovie['movies'] = Movies::query()
                ->where('id', '=', $id_movie)
                ->get();

            $dataComments['comments'] = Comments::rightJoin('users', 'comments.pseudo', '=', 'users.pseudo')
                ->select('comments.*', 'users.likes', 'users.admin', 'users.comments')
                ->where('comments.id_movie', '=', $id_movie)
                ->get();

            $message = 'You\'ve already disliked or liked this comment.';
            return view('movies.detailMovie', $dataMovie, $dataComments, compact('message'));
        }

        //----update table like for checks later------------------------------

        $likePlusOld = trim($request->get('likePlusOld'));
        $likePlusNew = (int)$likePlusOld + 1;
        
        DB::table('comments')
            ->where('id', '=', $id_comment)
            ->update(['likeplus' => $likePlusNew]);

        DB::table('likes')->insert([
                'id_comment' => $id_comment,
                'pseudo' => $pseudo_user,
                'likemoins' => true,
        ]);

//-----update number of likes in users table--------------------------
        $nb_likesOld = trim($request->get('inputLikes'));
        $nb_likesNew = (int)$nb_likesOld + 1;
        DB::table('users')
            ->where('pseudo', '=', $pseudo_user)
            ->update(['likes' =>  $nb_likesNew]);

        $id_movie = trim($request->get('inputIdMovie'));
        $dataMovie['movies'] = Movies::query()
            ->where('id', '=', $id_movie)
            ->get();
    
        $dataComments['comments'] = Comments::rightJoin('users', 'comments.pseudo', '=', 'users.pseudo')
            ->select('comments.*', 'users.likes', 'users.admin', 'users.comments')
            ->where('comments.id_movie', '=', $id_movie)
            ->get();

        return view('movies.detailMovie', $dataMovie, $dataComments);
    }

    public function updateLikeMoinsComment(Request $request, $id_comment)
    {   
        $pseudo_user = Auth::user()->pseudo;

    //-----check if already give a like or dislike-------------------------

        $checkLikePlus = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_comment', '=', $id_comment)
            ->where('likeplus', '=', true)
            ->get();

        $checkLikeMoins = DB::table('likes')
            ->where('pseudo', '=', $pseudo_user)
            ->where('id_comment', '=', $id_comment)
            ->where('likemoins', '=', true)
            ->get();
            
        if (count($checkLikePlus) > 0 || count($checkLikeMoins) > 0) {
            $id_movie = trim($request->get('inputIdMovie'));
            $dataMovie['movies'] = Movies::query()
            ->where('id', '=', $id_movie)
            ->get();

            $dataComments['comments'] = Comments::rightJoin('users', 'comments.pseudo', '=', 'users.pseudo')
            ->select('comments.*', 'users.likes', 'users.admin', 'users.comments')
            ->where('comments.id_movie', '=', $id_movie)
            ->get();
            $message = 'You\'ve already disliked this movie.';
            return view('movies.detailMovie', $dataMovie, $dataComments, compact('message'));
        }

    //----update table like for checks later------------------------------

        $likeMoinsOld = trim($request->get('likeMoinsOld'));
        $likeMoinsNew = (int)$likeMoinsOld + 1;
        
        DB::table('comments')
            ->where('id', '=', $id_comment)
            ->update(['likemoins' => $likeMoinsNew]);

        DB::table('likes')->insert([
                'id_comment' => $id_comment,
                'pseudo' => $pseudo_user,
                'likemoins' => true,
        ]);

//-----update number of likes in users table--------------------------
        $nb_likesOld = trim($request->get('inputLikes'));
        $nb_likesNew = (int)$nb_likesOld + 1;
        DB::table('users')
            ->where('pseudo', '=', $pseudo_user)
            ->update(['likes' =>  $nb_likesNew]);

        $id_movie = trim($request->get('inputIdMovie'));
        $dataMovie['movies'] = Movies::query()
            ->where('id', '=', $id_movie)
            ->get();
    
        $dataComments['comments'] = Comments::rightJoin('users', 'comments.pseudo', '=', 'users.pseudo')
            ->select('comments.*', 'users.likes', 'users.admin', 'users.comments')
            ->where('comments.id_movie', '=', $id_movie)
            ->get();

        return view('movies.detailMovie', $dataMovie, $dataComments);
        // return redirect()->back();
    }

}