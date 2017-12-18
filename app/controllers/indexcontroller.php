<?php
namespace PHPMVC\Controllers;

use PHPMVC\Models\BranchModel;
use PHPMVC\Models\ClientModel;
use PHPMVC\Models\TransactionModel;
use PHPMVC\Models\TransactionStatusModel;
use PHPMVC\Models\UserModel;
use PHPMVC\Models\ChequeModel;

class IndexController extends AbstractController
{
    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('index.default');
        $this->language->load('transactions.status');

        $this->_data['clientsCount'] = ClientModel::count();
        $this->_data['usersCount'] = UserModel::count();
        $this->_data['transactionsCount'] = TransactionModel::count();
        $transactionsReviewCount = TransactionModel::getAllForReview();
        $this->_data['transactionsReviewCount'] = false === $transactionsReviewCount ? 0 : (int) count($transactionsReviewCount);
        $transactionsReviewedCount = TransactionModel::getAllForReviewed();
        $this->_data['transactionsReviewedCount'] = false === $transactionsReviewedCount ? 0 : (int) count($transactionsReviewedCount);
        $issuesCheques = ChequeModel::getPrintedCheques();
        $this->_data['issuesCheques'] = false === $issuesCheques ? 0 : (int) count($issuesCheques);
        $handedOverCheques = ChequeModel::getHandedOverToClientCheques();
        $this->_data['handedOverCheques'] = false === $handedOverCheques ? 0 : (int) count($handedOverCheques);
        $this->_data['notCoveredCheques'] = ChequeModel::getNotCoveredCheques();

        $this->_data['realTimeActions'] = TransactionStatusModel::getLatestStatuses();

        if($this->session->lang == 'ar') {
            $labels = '[ "يناير", "فبراير", "مارس", "إبريل", "مايو", "يونيو", "يوليو", "أغسطس",
                    "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر" ]';
        } else {
            $labels = '[ "Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug",
                    "Sep", "Oct", "Nov", "Dec" ]';
        }
        $monthlyTransactions = TransactionModel::getTransactionsForMonths();
        $this->_data['barchart'] = '<script>';
        $this->_data['barchart'] .= "
            var barChartData = {
                labels: {$labels},
                datasets: [{
                    label: 'المعاملات',
                    backgroundColor: '#f5f5f5',
                    borderColor: '#0077b5',
                    borderWidth: 2,
                    data: [{$monthlyTransactions[0]},{$monthlyTransactions[1]},{$monthlyTransactions[2]},{$monthlyTransactions[3]},{$monthlyTransactions[4]},{$monthlyTransactions[5]},{$monthlyTransactions[6]},{$monthlyTransactions[7]},{$monthlyTransactions[8]},{$monthlyTransactions[9]},{$monthlyTransactions[10]},{$monthlyTransactions[11]}]
                }]
            
            };
        ";
        $this->_data['barchart'] .= "
            var ctx = document.getElementById('chart').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'line',
                data: barChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    options: {
                        layout: {
                            padding: {
                                left: 150,
                                right: 150,
                                top: 150,
                                bottom: 150
                            }
                        }
                    }
                }
            });
        ";
        $branches = BranchModel::getAll();
        $chequesIssued = [];
        $colors = ['#0077b5', '#ccc', '#60aed6', '#e4e4e4'];
        $i = 0;
        foreach ($branches as $branch) {
            $chequesIssued[] = "{
                    label: '{$branch->BranchName}',
                    backgroundColor: '" . $colors[$i++] . "',
                    borderWidth: 0,
                    data: [
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 1) . ",
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 2) . ",
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 3) . ",
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 4) . ",
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 5) . ",
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 6) . ",
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 7) . ",
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 8) . ",
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 9) . ",
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 10) . ",
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 11) . ",
                        " . ChequeModel::getMonthlyIssuedChequesPerBranch($branch->BranchId, 12) . "
                    ]
                }";
        }
        $chequesIssued = implode(',', $chequesIssued);
        $this->_data['barchart'] .= '</script>';
        $this->_data['barchart'] .= '<script>';
        $this->_data['barchart'] .= "
            var barChartData = {
                labels: {$labels},
                datasets: [$chequesIssued]
            };
        ";
        $this->_data['barchart'] .= "
            var ctx = document.getElementById('chart2').getContext('2d');
            window.myBar2 = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    options: {
                        layout: {
                            padding: {
                                left: 150,
                                right: 150,
                                top: 150,
                                bottom: 150
                            }
                        }
                    }
                }
            });
        ";
        $this->_data['barchart'] .= '</script>';

        $this->_view();
    }
}




