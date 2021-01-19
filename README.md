## Telegram-bot-youtube
Bot search and play youtube videos without ads.

Working example ![logobott2](https://vap.in.ua/storage/app/public/logobott2.png)   [Lviv_eco_news_bot](https://t.me/VapInUaBot) (Type: Youtube madonna)

### How to use
This bot use [Youtube v3 api](https://developers.google.com/youtube/v3/getting-started)</br>
Bot is built on a https://github.com/irazasyed/telegram-bot-sdk and use Webhook</br>
(if you have some troubles with installation or webhook you can use this tutorial [Set_webhook](https://www.xibel-it.eu/setup-telegram-bot-sdk-with-webhook-in-laravel/))</br>

After telegram-sdk is installed and Webhook is set  you need to create and add your **bot name**, **bot token**, **youtube v3 api key** in your **.env** file like so
```
TELEGRAM_BOT_TOKEN = YOUR-BOT-TOKEN
TELEGRAM_BOT_NAME = YOUR-BOT-NAME
YOUTUBE_API_KEY=YOUR-API-KEY
YOUR_MESSAGE_ID=YOUR_MESSAGE_ID // not necessary this can be added later
```
Next step

Copy _Myclass_ folder in your App folder</br>
Copy _Commands_ folder in your App folder</br>
Copy _telegram.php_ from config folder in your App/config folder</br>
Copy _database_ folder and run migrations</br>
Replace your _WebhookController_ on Controller from routs folder</br>

All done.

