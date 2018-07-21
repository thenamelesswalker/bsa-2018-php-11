<?php

namespace Tests\Browser\Task3;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Task3UnregisteredUserActionTest extends DuskTestCase
{
    use DatabaseMigrations;

    public  function test_user_cant_see_add_lot_page() {
        $this->browse(
            function(Browser $browser) {
                $browser
                    ->visit('/market/lots/add')
                    ->assertPathIs('/login');
            }
        );
    }

}