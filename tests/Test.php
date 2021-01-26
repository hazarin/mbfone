<?php

require './highlight_nicknames.php';

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    protected $input;
    protected $output;

    protected function setUp(): void
    {
        $this->input = [
            '@storm87 сообщил нам вчера о результатах',
            'Я живу в одном доме с @300spartans',
            'Правильный ник: @usernick | неправильный ник: @usernick;'
        ];
        $this->output = [
            '<b>@storm87</b> сообщил нам вчера о результатах',
            'Я живу в одном доме с @300spartans',
            'Правильный ник: <b>@usernick</b> | неправильный ник: @usernick;'
        ];
    }

    public function test_highlight_nicknames()
    {
        foreach ($this->input as $key => $item) {
            $result = highlight_nicknames($item);
            $this->assertEquals($result, $this->output[$key]);
        }
    }
}
