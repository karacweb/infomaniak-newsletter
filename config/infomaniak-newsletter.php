<?php

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