<?php

namespace spec\App\Entity;

use App\Entity\Immutable\ImmutableException;
use PhpSpec\ObjectBehavior;

class BookSpec extends ObjectBehavior
{
    private $isbn = "978-1491918661";
    private $title = "Learning PHP, MySQL & Javascript: With jQuery, CSS & HTML5";
    private $author = "Robin Nixon";
    private $category = "PHP, Javascript";
    private $price = "9.99 GBP";

    public function let()
    {
        $this->beConstructedWith(
            $this->isbn,
            $this->title,
            $this->author,
            $this->category,
            $this->price
        );
    }

    public function it_should_be_immutable()
    {
        $this->shouldThrow(ImmutableException::class)->during("__set", ["title", "Different Title"]);
    }

    public function it_has_an_ISBN()
    {
        $this->getISBN()->shouldReturn($this->isbn);
    }

    public function it_has_a_title()
    {
        $this->getTitle()->shouldReturn($this->title);
    }

    public function it_has_an_author()
    {
        $this->getAuthor()->shouldReturn($this->author);
    }

    public function it_has_categories()
    {
        $this->getCategories()->shouldReturn(["PHP", "Javascript"]);
    }

    public function it_has_a_price()
    {
        $this->getPrice()->shouldReturn($this->price);
    }

    public function it_can_be_converted_to_array()
    {
        $this->toArray()->shouldReturn([
            'isbn' => $this->isbn,
            'title' => $this->title,
            'author' => $this->author,
            'category' => $this->category,
            'price' => $this->price
        ]);
    }
}