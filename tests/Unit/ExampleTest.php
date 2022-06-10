<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        // $response =$this->get('/');
         $response = $this->get('/');
        // $response->$this->assertStatus(200);
         $response->assertStatus(200);
    }
}
