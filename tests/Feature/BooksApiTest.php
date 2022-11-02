<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;



    /** @test */
    function can_get_all_books(){

        $books = Book::factory(4)->create();

       // dd((route('books.index')));
        $response= $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title'=> $books[0]->title
        ]);
    }

    /** @test */
    function can_get_one_book(){

        $book = Book::factory()->create();

        // dd((route('books.index')));
        $response= $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title'=> $book->title,
        ]);

    }
    /** @test */
    function can_create_one_book(){

        $this->postJson(route('books.store'),[])
        ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'),[
            'title'=>'my new book'
        ])->assertJsonFragment([
            'title'=>'my new book'
        ]);

        $this->assertDatabaseHas('books',[
            'title'=>'my new book'
        ]);
    }
    /** @test */
    function can_update_books(){

        $book = Book::factory()->create();
        $this->patchJson(route('books.update',$book),[])
            ->assertJsonValidationErrorFor('title');
        // dd((route('books.index')));
        $this->patchJson(route('books.update', $book),[
            'title'=> 'Edited book',
        ])->assertJsonFragment([
            'title'=> 'Edited book',
        ]);
        $this->assertDatabaseHas('books',[
            'title'=>'Edited book'
        ]);
    }

    /** @test  */
    function can_delete(){

        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy',$book))
            ->assertNoContent();

        $this->assertDatabaseCount('books',0);
    }

}
