<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    protected $user;
    
    public function __construct()
    {
        $this->middleware(['auth:api']);
        $this->user = $this->guard()->user();
    }

    protected function guard()
    {
        return Auth::guard();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $books = Book::all();
        $result = [];
        foreach($books as $book){
            $temp = [];
            $temp['id'] = $book->id;
            $temp['title'] = $book->title;
            $temp['genre'] = $book->genre;
            $temp['description'] = $book->description;
            $temp['author'] = $book->author;
            $temp['created_by'] = $book->user->email;
            $temp['created_at'] = $book->created_at;
            $temp['updated_at'] = $book->updated_at;

            $result[] = $temp;
        }
        return response()->json($result, 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(),[
                'title' => 'required|string',
                'genre' => 'required|string',
                'description' => 'required|string',
                'author' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
            ], 400);
        }

        $book = new Book();
        $book->title = $request->title;
        $book->genre = $request->genre;
        $book->description = $request->description;
        $book->author = $request->author;

        if ($this->user->books()->save($book)) {
            return response()->json([
                    'status' => true,
                    'message'   => 'The book was created successfully',
            ], 200);
        } else {
            return response()->json([
                    'status'  => false,
                    'message' => 'Sorry, the book could not be created.',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
        $result = [];
        $result['id'] = $book->id;
        $result['title'] = $book->title;
        $result['genre'] = $book->genre;
        $result['description'] = $book->description;
        $result['author'] = $book->author;
        $result['created_by'] = $book->user->email;
        $result['created_at'] = $book->created_at;
        $result['updated_at'] = $book->updated_at;

        return response()->json($result, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
        if ($book->delete()) {
            return response()->json([
                    'status' => true,
                    'message'   => 'The book was deleted successfully',
            ], 200);
        } else {
            return response()->json([
                    'status'  => false,
                    'message' => 'Sorry, the book could not be deleted.',
            ], 200);
        }
    }
}
