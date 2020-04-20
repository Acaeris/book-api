<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Psr\Log\LoggerInterface;

class BookRepository
{
    private $connection;
    private $log;

    public function __construct(
        LoggerInterface $log,
        Connection $connection
    ) {
        $this->log = $log;
        $this->connection = $connection;
    }

    /**
     * @param string $author
     * @return Book[]
     */
    public function fetchByAuthor(string $author): array
    {
        $data = $this->connection->fetchAll("SELECT * FROM books WHERE author = :author", ["author" => $author]);
        $books = [];

        foreach ($data as $entry) {
            $books[] = $this->create($entry);
        }

        return $books;
    }

    /**
     * @param string $category
     * @return Book[]
     */
    public function fetchByCategory(string $category): array
    {
        $data = $this->connection->fetchAll("SELECT * FROM books WHERE category LIKE :category", ["category" => '%' . $category . '%']);
        $books = [];

        if (!empty($data)) {
            foreach ($data as $entry) {
                $books[] = $this->create($entry);
            }
        }

        return $books;
    }

    /**
     * @param string $author
     * @param string $category
     * @return Book[]
     */
    public function fetchByAuthorAndCategory(string $author, string $category): array
    {
        $data = $this->connection->fetchAll("SELECT * FROM books WHERE author = :author AND category LIKE :category", ["author" => $author, "category" => '%' . $category . '%']);
        $books = [];

        if (!empty($data)) {
            foreach ($data as $entry) {
                $books[] = $this->create($entry);
            }
        }

        return $books;
    }

    /**
     * @param string[] $data
     * @return Book
     */
    public function create(array $data): Book
    {
        return new Book(
            $data['isbn'],
            $data['title'],
            $data['author'],
            $data['category'],
            $data['price']
        );
    }

    /**
     * @param Book $book
     * @return bool
     */
    public function store(Book $book): bool
    {
        $check = $this->connection->fetchAll("SELECT isbn FROM books WHERE isbn = :isbn", ["isbn" => $book->getISBN()]);

        try {
            if (empty($check)) {
                $this->connection->insert("books", $book->toArray());
            } else {
                $this->connection->update("books", $book->toArray(), ['isbn' => $book->getISBN()]);
            }
        } catch (DBALException $e) {
            $this->log->error("Unable to store data for the book " . $book->getISBN(), $e);
            return false;
        }

        return true;
    }

    public function fetchCategories(): array
    {
        $data = $this->connection->fetchAll("SELECT UNIQUE category FROM books");
        $result = [];

        foreach ($data as $book) {
            $categories = explode(",", $book["category"]);

            foreach ($categories as $category) {
                $result[] = trim($category);
            }
        }

        return array_unique($result);
    }
}
