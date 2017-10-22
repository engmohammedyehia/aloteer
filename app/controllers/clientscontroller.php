<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Messenger;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\Models\ClientModel;

class ClientsController extends AbstractController
{
    use InputFilter;
    use Helper;

    private $_createActionRoles =
    [
        'name'                  => 'req|alpha|between(3,80)',
        'id_type'               => 'req|num|inset[1,2]',
        'id_number'             => 'req|alphanum|max(15)|lang(en)',
        'mobile'                => 'req|alphanum|max(15)|lang(en)',
        'phone'                 => 'lang(en)',
        'fax'                   => 'lang(en)',
        'email'                 => 'req|email|max(50)',
        'pobox'                 => 'lang(en)',
        'city'                  => 'req|num',
        'zip_code'              => 'lang(en)'
    ];

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('clients.default');
        $this->language->load('clients.cities');
        $this->_data['clients'] = ClientModel::getAll();
        $this->_view();
    }

    public function createAction()
    {
        $this->language->load('template.common');
        $this->language->load('clients.labels');
        $this->language->load('clients.cities');
        $this->language->load('clients.add');
        $this->language->load('clients.messages');
        $this->language->load('validation.errors');

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {

            $client = new ClientModel();
            $client->name = $this->filterString(@$_POST['name']);
            $client->id_type = $this->filterInt(@$_POST['id_type']);
            $client->id_number = $this->filterString(@$_POST['id_number']);
            $client->mobile = $this->filterString(@$_POST['mobile']);
            $client->phone = $this->filterString(@$_POST['phone']);
            $client->fax = $this->filterString(@$_POST['fax']);
            $client->email = $this->filterEmail(@$_POST['email']);
            $client->pobox = $this->filterString(@$_POST['pobox']);
            $client->city = $this->filterString(@$_POST['city']);
            $client->zip_code = $this->filterString(@$_POST['zip_code']);
            $client->created = date('Y-m-d');

            if($client->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
            } else {
                $this->messenger->add($this->language->get('message_save_failed'), Messenger::APP_MESSAGE_ERROR);
            }
            $this->redirect('/clients');
        }
        $this->_view();
    }

    public function editAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $client = ClientModel::getByPK($id);
        if($client === false) {
            $this->redirect('/clients');
        }

        $this->language->load('template.common');
        $this->language->load('clients.labels');
        $this->language->load('clients.cities');
        $this->language->load('clients.edit');
        $this->language->load('clients.messages');
        $this->language->load('validation.errors');

        $this->language->swapKey('title', [$client->name]);

        $this->_data['client'] = $client;

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {

            $client->name = $this->filterString(@$_POST['name']);
            $client->id_type = $this->filterInt(@$_POST['id_type']);
            $client->id_number = $this->filterString(@$_POST['id_number']);
            $client->mobile = $this->filterString(@$_POST['mobile']);
            $client->phone = $this->filterString(@$_POST['phone']);
            $client->fax = $this->filterString(@$_POST['fax']);
            $client->email = $this->filterEmail(@$_POST['email']);
            $client->pobox = $this->filterString(@$_POST['pobox']);
            $client->city = $this->filterString(@$_POST['city']);
            $client->zip_code = $this->filterString(@$_POST['zip_code']);

            if($client->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
            } else {
                $this->messenger->add($this->language->get('message_save_failed'), Messenger::APP_MESSAGE_ERROR);
            }

            $this->redirect('/clients');
        }
        $this->_view();
    }

    public function viewAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $client = ClientModel::getByPK($id);
        if($client === false) {
            $this->redirect('/clients');
        }

        $this->language->load('template.common');
        $this->language->load('clients.labels');
        $this->language->load('clients.cities');
        $this->language->load('clients.view');

        $this->language->swapKey('title', [$client->name]);

        $this->_data['client'] = $client;

        $this->_view();
    }

    public function deleteAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $client = ClientModel::getByPK($id);
        if($client === false) {
            $this->redirect('/clients');
        }
        $this->language->load('clients.messages');
        if($client->delete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_failed'), Messenger::APP_MESSAGE_ERROR);
        }
        $this->redirect('/clients');
    }
}