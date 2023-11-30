<?php

namespace Tests\Acceptance;

use App\Models\Client;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\WithoutMiddleware;
use TestCase;

/**
 * Class AccountTest
 * @package Tests\Acceptance
 */
class AccountTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    /**
     * @group testAccount
     */
    public function testGetEmptyAccount()
    {
        $this->get('/v1/account/100239183')
            ->seeStatusCode(404)
            ->seeJson([]);
    }

    /**
     * @group testAccount
     */
    public function testCreateAccountWithNotExistVatNumber()
    {
        $this->post("/v1/account/create", ['vat_number' => '123456789','balance' => '100',]);
        $this->seeStatusCode(404);
    }
}
