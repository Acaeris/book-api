<?php

namespace App\Entity;

use App\Entity\Immutable\ImmutableInterface;
use App\Entity\Immutable\ImmutableTrait;
use App\Validator\Constraint as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class Book implements ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /**
     * @Assert\NotBlank
     * @AppAssert\ContainsISBN
     */
    private $isbn;

    /**
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @Assert\NotBlank
     */
    private $author;

    /**
     * @Assert\NotBlank
     */
    private $categories;

    /**
     * @Assert\NotBlank
     */
    private $price;

    public function __construct(
        string $isbn,
        string $title,
        string $author,
        string $categories,
        string $price
    ) {
        $this->constructImmutable();

        $this->isbn = $isbn;
        $this->title = $title;
        $this->author = $author;
        $this->categories = explode(", ", $categories);
        $this->price = $price;
    }

    public function getISBN(): string
    {
        return $this->isbn;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function toArray(): array
    {
        return [
            "isbn" => $this->isbn,
            "title" => $this->title,
            "author" => $this->author,
            "category" => implode(", ", $this->categories),
            "price" => $this->price
        ];
    }
}
