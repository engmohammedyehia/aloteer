<?php
namespace PHPMVC\Controllers;

use PHPMVC\Models\TransactionConditionModel;

class TestController extends AbstractController
{
    public function defaultAction()
    {
        $startingDate = new \DateTime('2018-01-10');
        $numberOfInstalments = 8;
        $paidEvery = 3;

        // Adding the first instalment
        $instalmentsDates = [];
        $instalmentsDates[] = $startingDate->format('Y-m-d');
        $numberOfInstalments--;

        $dateInterval = new \DateInterval('P' . $paidEvery . 'M');

        for ($i = 0; $i < $numberOfInstalments; $i++) {
            $lastInsertedDate = new \DateTime($instalmentsDates[$i]);
            $instalmentsDates[] = $lastInsertedDate->add($dateInterval)->format('Y-m-d');
        }

        var_dump($instalmentsDates);
    }
}