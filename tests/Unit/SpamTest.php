<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Inspections\Spam;

class SpamTest extends TestCase
{


    /** @test */
    public function it_check_for_invalid_keywords()
    {

        $spam = new Spam();

        $this->assertFalse($spam->detect("Innocent Reply"));

        $this->expectException(\Exception::class);

        $span->detect("yahoo customer support");
    }

    /** @test */
    public function it_check_for_any_key_being_help_down()
    {
        $spam = new Spam();

        $this->expectException(\Exception::class);

        $spam->detect("aaaaaaaaaaaaaaaaaaaaaaa");

    }
}
