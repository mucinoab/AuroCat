<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    /**
     * Test the route rates with option gwad return OK.
     */
    public function testRouteRatesWithOptionGwad()
    {
        $this->get('rates/gwad')
                ->assertOk();
    }
    /**
     * Test the route rates with option tgp return OK.
     */
    public function testRouteRatesWithOptionTgp() {
        $this->get('rates/tgp')
                ->assertOk();
    }
    /**
     * Test the route rates with option tp return OK.
     */

     public function testRouteRatesWithOptionTp(){
         $this->get('rates/tp')
                ->assertOk();
     }
}
