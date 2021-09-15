<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Redis;

class ReddisConnectionTest extends TestCase
{
  /**
   * Test we can reach the redis server.
   *
   * @return void
   */
  public function test_example()
  {
    $redis = Redis::connection();
    $this->assertTrue($redis->ping());
  }
}
