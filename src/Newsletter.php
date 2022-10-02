<?php

declare(strict_types=1);

namespace Karacweb\InfomaniakNewsletter;

use Illuminate\Support\Collection;
use Infomaniak\ClientApiNewsletter\Action;
use Infomaniak\ClientApiNewsletter\Client;

final class Newsletter
{
    protected $infomaniakApi;
    protected Collection $lists;
    protected string $defaultList;
    public function __construct(Client $infomaniakApi, Collection $lists, string $defaultList)
    {
        $this->infomaniakApi = $infomaniakApi;
        $this->lists = $lists;
        $this->defaultList = $defaultList;
    }

        public static function createLists(array $configLists)
        {
            $collection = collect();
            foreach ($configLists['lists'] as $key => $list) {
                $collection->put($key, $list['id']);
            }

            return $collection;
        }

        public function findList(string $listName)
        {
            if ($listName === '') {
                return intval($this->lists->get($this->defaultList));
            }
            return intval($this->lists->get($listName));
        }

        /**
         * Retrieve the contact in all the account's lists
         */
        public function getContact(string $email)
        {
            $response = $this->infomaniakApi->get(Client::CONTACT, [
                'id' => $email,
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        /**
         * Edit the contact in all the account's lists
         */
        public function updateContact(string $email, array $fields = [])
        {
            $parameters = array_merge(
                ['email' => $email],
                $fields
            );
            $response = $this->infomaniakApi->put(Client::CONTACT, [
                'id' => $email,
                'params' => $parameters,
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        public function createMailinglist(string $listName)
        {
            $response = $this->infomaniakApi->post(Client::MAILINGLIST, [
                'params' => [
                    'name' => $listName,
                ],
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        public function updateMailinglist(string $listName, string $name)
        {
            $listId = $this->findList($listName);
            $response = $this->infomaniakApi->put(Client::MAILINGLIST, [
                'id' => $listId,
                'params' => [
                    'name' => $name,
                ],
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        public function deleteMailinglist(string $listName = '')
        {
            $listId = $this->findList($listName);
            $response = $this->infomaniakApi->delete(Client::MAILINGLIST, [
                'id' => $listId,
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        /**
         * @param string $listName Name of the mailing list
         * @param array $options = [
         *   'page' => 2, // Current page
         *   'perPage' => 20, // Number of contacts per page
         * ]
         */
        public function getContacts(string $listName = '', array $options = [])
        {
            $listId = $this->findList($listName);
            $response = $this->infomaniakApi->get(Client::MAILINGLIST, [
                'id' => $listId,
                'action' => Action::LISTCONTACT,
                'params' => $options,
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        public function getMailinglist(string $listName = '')
        {
            $listId = $this->findList($listName);
            $response = $this->infomaniakApi->get(Client::MAILINGLIST, [
                'id' => $listId,
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        /**
         * @param array $options = [
         *   'page' => 2, // Current page
         *   'perPage' => 20, // Number of lists per page
         * ]
         */
        public function getMailinglists(array $options = [])
        {
            $response = $this->infomaniakApi->get(Client::MAILINGLIST, [
                'params' => $options,
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        /**
         * @param string $email The contact's email address
         * @param array $fields = [
         *      "firstname" => "John",
         *      "lastname" => "Doe",
         * ]
         * @param string $listName Name of the mailing list
         */
        public function importContact(string $email, array $fields = [], string $listName = '')
        {
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }

            $listId = $this->findList($listName);
            $parameters = array_merge(
                ['email' => $email],
                $fields
            );
            $response = $this->infomaniakApi->post(Client::MAILINGLIST, [
                'id' => $listId,
                'action' => Action::IMPORTCONTACT,
                'params' => [
                    'contacts' => [
                        $parameters,
                    ],
                ],
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        public function deleteContact(string $email, string $listName = '')
        {
            $listId = $this->findList($listName);
            $response = $this->infomaniakApi->post(Client::MAILINGLIST, [
                'id' => $listId,
                'action' => Action::MANAGECONTACT,
                'params' => [
                    'email' => $email,
                    'status' => Action::DELETE,
                ],
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        public function unsubscribeContact(string $email, string $listName = '')
        {
            $listId = $this->findList($listName);
            $response = $this->infomaniakApi->post(Client::MAILINGLIST, [
                'id' => $listId,
                'action' => Action::MANAGECONTACT,
                'params' => [
                    'email' => $email,
                    'status' => Action::UNSUBSCRIBE,
                ],
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        public function getTask(int $taskId)
        {
            $response = $this->infomaniakApi->get(Client::TASK, [
                'id' => $taskId,
            ]);
            if (! $response->success()) {
                return false;
            }

            return $response->datas();
        }

        public function isSubscribed(string $email, string $listName = '')
        {
            $contact = $this->getContact($email);
            if (! $contact) {
                return false;
            }

            $listId = $this->findList($listName);
            if (array_key_exists('mailinglists', $contact) && is_array($contact['mailinglists'])) {
                foreach ($contact['mailinglists'] as $mailinglist) {
                    if (array_key_exists('id', $mailinglist) && $listId === $mailinglist['id'] &&
                        array_key_exists('status', $mailinglist) && $mailinglist['status'] === 1) { // 1 = "subscribed"
                        return true;
                    }
                }
            }
            return false;
        }
}
