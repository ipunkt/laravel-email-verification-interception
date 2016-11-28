<?php

return [
    /**
     * Settings for Activation Mail
     */
    'activation' => [
        /**
         * Mail subject
         */
        'subject' => 'Activate your email address',

        /**
         * From settings
         */
        'from' => [
            /**
             * from email, required
             * @var string
             */
            'email' => null,

            /**
             * from name, optional
             * @var string|null
             */
            'name' => null,
        ],

        /**
         * mail template
         */
        'view' => 'emails.registration.activate',
    ],
];