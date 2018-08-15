<?php
namespace PHPMVC\Controllers;

use PHPMVC\Lib\Database\DatabaseHandler;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\BranchModel;
use PHPMVC\Models\ChequeModel;
use PHPMVC\Models\ClientModel;
use PHPMVC\Models\UserSettingsModel;

class ReportsController extends AbstractController
{
    use InputFilter;

    public function dailyAction()
    {
        $this->language->load('template.common');
        $this->language->load('cheques.default');
        $this->language->load('reports.daily');

        $this->_data['branches'] = BranchModel::getAll();
        $this->_data['cheques'] = false;

        if (isset($_POST['submit']) && $this->requestHasValidToken()) {
            if($_POST['from'] === '') {
                $this->messenger->add($this->language->get('message_empty_start_date'), Messenger::APP_MESSAGE_ERROR);
                $this->_view();
            }
            $startDate = new \DateTime($this->filterString($_POST['from']));
            $branch = $_POST['branch'] === '' ? false : $this->filterInt($_POST['branch']);
            $this->_data['cheques'] = ChequeModel::getChequesForReport($startDate->format('Y-m-d'), $branch);
        }

        $this->_view();
    }

    public function clientAction()
    {
        $this->language->load('template.common');
        $this->language->load('cheques.default');
        $this->language->load('reports.client');

        $this->_data['clients'] = ClientModel::getAll();
        $this->_data['cheques'] = false;

        if (isset($_POST['submit']) && $this->requestHasValidToken()) {
            if($_POST['client'] === '') {
                $this->messenger->add($this->language->get('message_user_not_selected'), Messenger::APP_MESSAGE_ERROR);
                $this->_view();
            }
            $client = $this->filterInt($_POST['client']);
            $this->_data['cheques'] = ChequeModel::getChequesForClientReport($client);
        }

        $this->_view();
    }
}