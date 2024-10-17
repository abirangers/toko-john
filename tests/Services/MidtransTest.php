<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Services\Midtrans;
use Midtrans\Config;

class MidtransTest extends TestCase
{
    public function test_midtrans_configuration_is_set_correctly()
    {
        $midtrans = new Midtrans();

        $this->assertEquals(config('midtrans.server_key'), Config::$serverKey);
        $this->assertEquals(config('midtrans.is_production'), Config::$isProduction);
        $this->assertEquals(config('midtrans.is_sanitized'), Config::$isSanitized);
        $this->assertEquals(config('midtrans.is_3ds'), Config::$is3ds);
    }
}