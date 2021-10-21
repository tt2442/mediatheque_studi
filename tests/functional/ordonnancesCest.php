<?php
// namespace App\Tests;

// use App\Tests\FunctionalTester;

// class ordonnancesCest
// {
//     public function _before(FunctionalTester $I)
//     {
//     }

    // tests
    // public function tryToTest(FunctionalTester $I)
    // {
    //     $I->amOnPage('/');

    //     $I->submitForm('form', [
    //         'email' => 'testinfirmier@test.fr',
    //         'password' => 'testpassword1',
    //     ]);
    //     $I->seeElement('#qXEHf');
    //     $I->click('#menu_2');
    //     $I->amOnRoute('ordonnance_new'); //bouton créer ordo

    //     //création de l'ordo
    //     $I->submitForm(
    //         'form[name=ordonnance]',
    //         [
    //             'ordonnance' => [
    //                 'titre' => 'Soin des jambes',
    //                 'patient' => '3',
    //             ],
    //         ],
    //         '#ajouter_soin'
    //     );
    //     //pour les tests on va au soin
    //     $I->amOnPage('/admin/soin/new/1');
    //     //ajout d'un soin
    //     $I->submitForm(
    //         'form[name=soin]',
    //         [
    //             'soin' => [
    //                 'Description' => 'nettoyage',
    //                 'kmplaine' => '0',
    //                 'kmmontagne' => '1',
    //                 'kmski' => '0',
    //                 'acte' => '1 AEF, 2 TRF',
    //             ],
    //         ],
    //         '#sauvegarder'
    //     );
    //     //on contrôle le soin
    //     $I->amOnPage('/admin/soin/1');
    //     $I->see('Soin n°=1', 'h1');
    //     $I->click('#revenirordo');
    //     //on teste l'édition
    //     $I->amOnPage('/admin/soin/new/1/1');
    //     $I->submitForm(
    //         'form[name=soin]',
    //         [
    //             'soin' => [
    //                 'Description' => 'nettoyage des plaies',
    //             ],
    //         ],
    //         '#sauvegarder'
    //     );
    //     $I->see('nettoyage des plaies', 'td');
    //     //on ajoute un autre soin pour ajouter une planification
    //     $I->click("#ajouter_soin");
    //     $I->submitForm(
    //         'form[name=soin]',
    //         [
    //             'soin' => [
    //                 'Description' => 'lavage',
    //                 'kmplaine' => '1',
    //                 'kmmontagne' => '1',
    //                 'kmski' => '0',
    //                 'acte' => '1 AEF, 3 MNR',
    //             ],
    //         ],
    //         '#add_tag_link_planification'
    //     );

    // }
//}
