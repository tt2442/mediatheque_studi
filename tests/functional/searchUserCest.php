<?php

namespace App\Tests;

use App\Tests\FunctionalTester;

class searchUserCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/login');

        $I->submitForm('form', [
            'email' => 'administrateur@mediatheque.com',
            'password' => 'p;P73VWOP>b$CY+R/~^B',
        ]);
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $I->amOnPage('/mes-emprunts');

        $I->see("Liste de mes emprunts", 'h1');

        $I->amOnRoute('employe');

        $I->see("Espace Employés", 'h1');

        $I->amOnRoute('user_index');

        $I->see("Listes des utilisateurs", 'h1');

        $I->amOnPage('/user/inactifs');

        $I->see("Listes des comptes à valider", 'h1');

        $I->amOnPage('/livre');

        $I->see("Liste des livres", 'h1');

        $I->amOnPage('/genre');

        $I->see("Listes des genres", 'h1');

        $I->amOnPage('/emprunt/comfirm');

        $I->see("Liste des emprunts à comfirmer", 'h1');

        $I->amOnPage('/emprunts/retard');

        $I->see("Liste des emprunts en retard", 'h1');
    }
}
