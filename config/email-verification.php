<?php

return [
    /**
     * Settings for Activation Mail
     */
    'activation' => [
        /**
         * shall we register the event listening our-self?
         */
        'register-event-listening' => true,

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
        'view' => 'email-verification::emails.registration.activate',
    ],

    /**
     * Which is your user model for granting access to?
     */
    'user-model' => 'App\User',
];