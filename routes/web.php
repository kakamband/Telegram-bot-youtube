
<?php

Route::post("/".$_ENV['TELEGRAM_WEBHOOK_URL']."/webhook", "WebhookController@start");

