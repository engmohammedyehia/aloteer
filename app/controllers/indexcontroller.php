<?php
namespace PHPMVC\Controllers;

use PHPMVC\Models\ClientModel;
use PHPMVC\Models\TransactionModel;
use PHPMVC\Models\UserModel;

class IndexController extends AbstractController
{
    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('index.default');

        $this->_data['clientsCount'] = ClientModel::count();
        $this->_data['usersCount'] = UserModel::count();
        $this->_data['transactionsCount'] = TransactionModel::count();

        $this->language->swapKey('text_online_rate', [$this->_data['usersCount']]);

        if($this->session->lang == 'ar') {
            $labels = '[ "يناير", "فبراير", "مارس", "إبريل", "مايو", "يونيو", "يوليو", "أغسطس",
                    "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر" ]';
        } else {
            $labels = '[ "Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug",
                    "Sep", "Oct", "Nov", "Dec" ]';
        }
        $this->_data['barchart'] = '<script>';
        $this->_data['barchart'] .= "
            var barChartData = {
                labels: {$labels},
                datasets: [{
                    label: 'المعاملات',
                    backgroundColor: '#f5f5f5',
                    borderColor: '#0077b5',
                    borderWidth: 2,
                    data: [0,0,0,0,0,0,0,0,0,0,0,0]
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
        $this->_data['barchart'] .= '</script>';
        $this->_data['barchart'] .= '<script>';
        $this->_data['barchart'] .= "
            var barChartData = {
                labels: {$labels},
                datasets: [{
                    label: 'فرع مكة',
                    backgroundColor: '#0077b5',
                    borderColor: '#0077b5',
                    borderWidth: 2,
                    data: [0,0,0,0,0,0,0,0,0,0,0,0]
                },
                {
                    label: 'فرع المدينة',
                    backgroundColor: '#f5f5f5',
                    borderColor: '#0077b5',
                    borderWidth: 2,
                    data: [0,0,0,0,0,0,0,0,0,0,0,0]
                }]
            
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




