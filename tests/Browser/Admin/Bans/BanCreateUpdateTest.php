<?php

namespace Tests\Browser\Admin\Bans;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Traits\TestHasUsers;

class BanCreateUpdateTest extends DuskTestCase
{
    use DatabaseMigrations;
    use TestHasUsers;

    public function test_non_tooladmin_can_create_and_modify_ban()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/changelang/en')
                ->loginAs($this->getUser())
                ->visit(route('admin.bans.create'))
                ->assertDontSee('403')
                ->type('target', 'UTRS banned user')
                ->type('reason', 'UTRS public ban reason')
                ->type('comment', 'UTRS private ban comment')
                ->press('Submit')
                ->waitForText('Ban details',5)
                ->waitForText('UTRS banned user',5)
                ->waitForText('indefinite',5)
                ->waitForText('English Wikipedia',5)
                ->waitForText('Action: created, Reason: UTRS private ban comment',5)
                ->type('reason', 'Another reason.')
                ->click('[for=is_active-0]')
                ->press('Save')
                ->waitForText('unbanned',5);
                
        });
    }

    public function test_tooladmin_can_create_and_modify_user_ban()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/changelang/en')
                ->loginAs($this->getTooladminUser())
                ->visit(route('admin.bans.create'))
                ->assertDontSee('403')
                ->type('target', 'UTRS banned user')
                ->type('reason', 'UTRS public ban reason')
                ->type('comment', 'UTRS private ban comment')
                ->press('Submit')
                ->waitForText('Ban details',5)
                ->waitForText('UTRS banned user',5)
                ->waitForText('indefinite',5)
                ->waitForText('English Wikipedia',5)
                ->waitForText('Action: created, Reason: UTRS private ban comment',5)
                ->type('reason', 'Another reason.')
                ->click('[for=is_active-0]')
                ->press('Save')
                ->waitForText('unbanned',5);
        });
    }
}
