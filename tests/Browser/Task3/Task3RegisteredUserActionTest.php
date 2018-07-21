<?php

namespace Tests\Browser\Task3;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\TestDataFactory;

class Task3RegisteredUserActionTest extends DuskTestCase
{
    use DatabaseMigrations;


    public  function test_user_can_see_add_lot_page() {
        $this->browse(
            function(Browser $browser) {
                $user = TestDataFactory::createUser();
                $browser
                   ->loginAs($user)
                    ->visit('/market/lots/add')
                    ->assertPathIs('/market/lots/add');
            }
        );
    }

    public function test_all_fields_are_empty()
    {
        $this->browse(
            function (Browser $browser) {
                $user = TestDataFactory::createUser();
                $browser
                    ->loginAs($user)
                    ->visit('/market/lots/add');
                $this->assertEmpty($browser->value('input[name=currency]'));
                $this->assertEmpty($browser->value('input[name=price]'));
                $this->assertEmpty($browser->value('input[name=openDate]'));
                $this->assertEmpty($browser->value('input[name=closeDate]'));
            }
        );
    }


    public function test_validate() {
        $this->browse(
        function(Browser $browser) {
            $user = TestDataFactory::createUser();
            $browser
                ->loginAs($user)
                ->visit('/market/lots/add')
                ->press('Save')
                ->assertPathIs('/market/lots/add')
                ->assertSee('The currency field is required.')
                ->assertSee('The price field is required.')
                ->assertSee('The open date field is required.')
                ->assertSee('The close date field is required.');
        });
    }
}