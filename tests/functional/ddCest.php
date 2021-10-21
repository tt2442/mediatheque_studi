<?php

namespace App\Tests;

use App\Tests\FunctionalTester;

class ddCest
{
    public function _before(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $I->amOnPage('/login');

        $I->submitForm('form', [
            'email' => 'administrateur@mediatheque.com',
            'password' => 'p;P73VWOP>b$CY+R/~^B',
        ]);

        $I->see("Se déconnecter", 'a');

        $I->amOnPage('/');

        $I->submitForm('form', [
            'search' => 'le',
            'type' => 'Romans',
            'genre' => 'ipsam',
        ]);

        $I->see("Voir plus", 'a');
    }
}
