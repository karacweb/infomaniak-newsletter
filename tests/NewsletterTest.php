<?php

use GuzzleHttp\Psr7\Response;
use \Infomaniak\ClientApiNewsletter\Action;
use \Infomaniak\ClientApiNewsletter\Client;
use Karacweb\InfomaniakNewsletter\Newsletter;
use PHPUnit\Framework\TestCase;

class NewsletterTest extends TestCase {

    protected $infomaniakApi;

    protected $newsletter;

    public function setUp(): void {
        $this->infomaniakApi = Mockery::mock(Client::class);

        $response = Mockery::mock(Response::class);
        $response->shouldReceive('success')->andReturn(true);
        $response->shouldReceive('datas')->andReturn([]);
        $this->infomaniakApi->shouldReceive('get')->once()->andReturn($response);
        $this->infomaniakApi->shouldReceive('post')->once()->andReturn($response);
        $this->infomaniakApi->shouldReceive('put')->once()->andReturn($response);

        $lists = Newsletter::createLists(
            [
                'lists' => [
                    'subscribers_en' => [
                        'id' => 123,
                    ],
                    'subscribers_fr' => [
                        'id' => 456,
                    ],
                ],
                'defaultListName' => 'subscribers_fr',
            ]
        );

        $this->newsletter = new Newsletter($this->infomaniakApi, $lists, 'subscribers_en');
    }

    public function testCanSubscribeEmailAddress() {
        $email = 'info@example.com';

        $this->infomaniakApi->shouldReceive('post')->withArgs([
            Client::MAILINGLIST,
            [
                'id'        => 123,
                'action'    => Action::IMPORTCONTACT,
                'params'    => [
                    'contacts' => [
                        [
                            "email" => $email
                        ]
                    ]
                ]
            ]
        ]);

        $this->newsletter->importContact($email);
    }
}
