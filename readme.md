[![Latest Stable Version](http://poser.pugx.org/karacweb/infomaniak-newsletter/v)](https://packagist.org/packages/karacweb/infomaniak-newsletter)

Infomaniak Newsletter for Laravel helps you interact with Infomaniak's API.


## Installation

Install the package through Composer.

Run the Composer require command from the terminal:

```
composer require karacweb/infomaniak-newsletter
```

Publish the configuration using the following command:

```
php artisan vendor:publish --provider="Karacweb\InfomaniakNewsletter\ServiceProvider"
```

## Configuration

Set your env keys and list(s) id(s) in `config/infomaniak-newsletter.php`.

```php
return [
    /*
     * The API keys of an Infomaniak newsletter account. You can find yours at
     * https://newsletter.infomaniak.com/accounts/access-token
     */
    'apiKey' => env('INFOMANIAK_APIKEY'),
    'secretKey' => env('INFOMANIAK_SECRETKEY'),

    /*
     * The listName to use when no listName has been specified in a method.
     */
    'defaultListName' => 'subscribers',

    /*
     * Here you can define properties of the lists.
     */
    'lists' => [

        /*
         * This key is used to identify this list. It can be used
         * as the listName parameter provided in the various methods.
         *
         * You can set it to any string you want and you can add
         * as many lists as you want.
         */
        'subscribers' => [

            /*
             * Id of a newsletter contact list. You can retrieve it
             * by looking at the last characters of the list's URL :
             * https://newsletter.infomaniak.com/mailinglists/show/XXXXX
             */
            'id' => env('INFOMANIAK_LISTID'),

        ],
    ],
];
```

## Usage

### NewsletterInfomaniak::importContact()

Subscribe an email address to the default list.
```php
use InfomaniakNewsletter;

InfomaniakNewsletter::importContact("email@example.com");
```

Provide a `firstname` and a `lastname` as an optional parameter.
```php
InfomaniakNewsletter::importContact("email@example.com", ["firstname" => "John", "lastname" => "Doe"]);
```

Finally, choose the list to add the email to as a third parameter.
```php
InfomaniakNewsletter::importContact("email@example.com", [], "subscribers");
```

### NewsletterInfomaniak::isSubscribed()

Check if the email is subscribed to the default list.
```php
InfomaniakNewsletter::isSubscribed("email@example.com");
```

Check if the email is subscribed in the `subscribers` list.
```php
InfomaniakNewsletter::isSubscribed("email@example.com", "subscribers");
```

### NewsletterInfomaniak::unsubscribeContact()

Unsubscribe an email from the default list. The contact is not deleted, only its status change.
```php
InfomaniakNewsletter::unsubscribeContact("email@example.com");
```

Unsubscribe an email from the `subscribers` list.
```php
InfomaniakNewsletter::unsubscribeContact("email@example.com", "subscribers");
```

### NewsletterInfomaniak::deleteContact()

Remove an email from the default list.
```php
InfomaniakNewsletter::deleteContact("email@example.com");
```

Remove an email from the `subscribers` list
```php
InfomaniakNewsletter::deleteContact("email@example.com", "subscribers");
```

### NewsletterInfomaniak::getContact()

Get the data regarding a contact.
```php
InfomaniakNewsletter::getContact("email@example.com");
```

### NewsletterInfomaniak::getContacts()

Get the contacts from the default list. The result is paginated.
```php
InfomaniakNewsletter::getContacts();
```

Get the contacts from the `subscribers` list.
```php
InfomaniakNewsletter::getContacts("subscribers");
```

Get the contact from the `subscribers` list with specified pagination options.
```php
InfomaniakNewsletter::getContacts("subscribers", ["page" => 2, "perPage" => 50]);
```

### NewsletterInfomaniak::updateContact()

Update the firstname of a contact. This updates the contacts in all the mailing lists of the account.
```php
InfomaniakNewsletter::updateContact("email@example.com", ["firstname" => "Joe"]);
```

You can provide a lastname too.
```php
InfomaniakNewsletter::updateContact("email@example.com", ["firstname" => "Joe", "lastname" => "Donovan"]);
```

### NewsletterInfomaniak::getMailinglists()

Get the account's mailing lists. The result is paginated.
```php
InfomaniakNewsletter::getMailinglists();
```

Get the account's mailing lists with specified pagination options.
```php
InfomaniakNewsletter::getMailinglists(["page" => 1, "perPage" => 50]);
```

### NewsletterInfomaniak::getMailinglist()

Get the information of the default list.
```php
InfomaniakNewsletter::getMailinglist();
```

Get the information of the `subscribers` list.
```php
InfomaniakNewsletter::getMailinglist("subscribers");
```

### NewsletterInfomaniak::createMailinglist()

Create the `subscribers_fr` mailing list.
```php
InfomaniakNewsletter::createMailinglist("subscribers_fr");
```

### NewsletterInfomaniak::updateMailinglist()

Rename the `subscribers_fr` mailing list to `subscribers_french`.
```php
InfomaniakNewsletter::updateMailinglist("subscribers_fr", "subscribers_french");
```

### NewsletterInfomaniak::deleteMailinglist()

Delete the default list.
```php
InfomaniakNewsletter::deleteMailinglist();
```

Delete the `subscribers_french` mailing list.
```php
InfomaniakNewsletter::deleteMailinglist("subscribers_french");
```

### NewsletterInfomaniak::getTask()

Get information about the task `123456`.
```php
InfomaniakNewsletter::getTask(123456);
```
