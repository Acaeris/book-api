<?php

namespace spec\App\Repository;

use App\Entity\Book;
use Doctrine\DBAL\Connection;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

class BookRepositorySpec extends ObjectBehavior
{
    private $mockBooks = [
        [
            "isbn" => "978-1491918661",
            "title" => "Learning PHP, MySQL & Javascript: With jQuery, CSS & HTML5",
            "author" => "Robin Nixon",
            "category" => "PHP, Javascript",
            "price" => "9.99 GBP"
        ]
    ];

    private $mockCategories = [
        [ "category" => "PHP, Javascript" ],
        [ "category" => "Linux" ],
        [ "category" => "Javascript" ]
    ];

    public function let(
        LoggerInterface $log,
        Connection $connection
    ) {
        $this->beConstructedWith($log, $connection);
    }

    public function it_can_create_a_book_object_from_data() {
        $this->create($this->mockBooks[0])->shouldReturnAnInstanceOf(Book::class);
    }

    public function it_should_be_able_to_fetch_all_categories(Connection $connection)
    {
        $connection->fetchAll("SELECT UNIQUE category FROM books")->willReturn($this->mockCategories);
        $this->fetchCategories()->shouldReturn(["PHP", "Javascript", "Linux"]);
    }

    public function it_should_be_able_to_fetch_by_author(Connection $connection)
    {
        $connection->fetchAll("SELECT * FROM books WHERE author = :author", ["author" => "Robin Nixon"])->willReturn($this->mockBooks);
        $this->fetchByAuthor("Robin Nixon")->shouldReturnCollectionOfBooks();
    }

    public function it_should_be_able_to_fetch_by_category(Connection $connection)
    {
        $connection->fetchAll("SELECT * FROM books WHERE category LIKE :category", ["category" => "%PHP%"])->willReturn($this->mockBooks);
        $this->fetchByCategory("PHP")->shouldReturnCollectionOfBooks();
    }

    public function it_should_be_able_to_fetch_by_author_and_category(Connection $connection)
    {
        $connection->fetchAll("SELECT * FROM books WHERE author = :author AND category LIKE :category", ["author" => "Robin Nixon", "category" => "%PHP%"])->willReturn($this->mockBooks);
        $this->fetchByAuthorAndCategory("Robin Nixon", "PHP")->shouldReturnCollectionOfBooks();
    }

    public function it_should_be_able_to_store_a_new_book_entry(
        Connection $connection,
        Book $book
    ) {
        $book->getISBN()->willReturn("978-1491918661");
        $book->toArray()->willReturn($this->mockBooks[0]);
        $connection->fetchAll("SELECT isbn FROM books WHERE isbn = :isbn", ["isbn" => "978-1491918661"])->willReturn([]);
        $connection->insert("books", $this->mockBooks[0])->shouldBeCalled();
        $this->store($book)->shouldReturn(true);
    }

    public function it_should_be_able_to_update_an_existing_book_entry(
        Connection $connection,
        Book $book
    ) {
        $book->getISBN()->willReturn("978-1491918661");
        $book->toArray()->willReturn($this->mockBooks[0]);
        $connection->fetchAll("SELECT isbn FROM books WHERE isbn = :isbn", ["isbn" => "978-1491918661"])->willReturn($this->mockBooks);
        $connection->update("books", $this->mockBooks[0], ['isbn' => $this->mockBooks[0]["isbn"]])->shouldBeCalled();
        $this->store($book)->shouldReturn(true);
    }

    public function getMatchers(): array
    {
        return [
            'returnCollectionOfBooks' => function(array $books) {
                foreach ($books as $book) {
                    if (!$book instanceof Book) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}