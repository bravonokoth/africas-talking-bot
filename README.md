<!-- Africa's Talking SMS Lead Generation Bot -->

This is a Laravel 9 application that implements a lead generation bot using the Africa’s Talking SMS API in the Sandbox environment. The bot allows users to send an SMS with the keyword "lead" to a shortcode (58116), prompting them to provide their name, email, and phone number. The collected data is stored in a database, and a confirmation message is sent back.
Note: Initially designed for WhatsApp, this project switched to SMS due to the Africa’s Talking WhatsApp Sandbox endpoint (https://chat.sandbox.africastalking.com/whatsapp/message/send) being marked "Coming soon" as of February 26, 2025.


<!-- Features -->

Responds to "lead" with a prompt for user details.
Captures and stores name, email, and phone number from formatted SMS messages.
Sends confirmation replies via SMS.
Logs incoming and outgoing messages for debugging.

<!-- Prerequisites -->

PHP: 8.0 or higher
Composer: For dependency management
Laravel: 9.x
MySQL: Or any Laravel-supported database
Africa’s Talking Account: Sandbox access with an API key
Ngrok: For exposing your local server to receive webhooks

