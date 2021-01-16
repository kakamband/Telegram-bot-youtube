## Telegram-bot-youtube
This bot search and play youtube videos without ads.

Working example ![logobott2](https://vap.in.ua/storage/app/public/logobott2.png)   [Lviv_eco_news_bot](https://t.me/VapInUaBot) (Just type: Youtube madonna)

### How to use
This bot is built on a https://github.com/irazasyed/telegram-bot-sdk that needs to be installed in your project.

After telegram-sdk is installed you need to create and add your **bot name** and **bot token** in your **.env** file like so
```
TELEGRAM_BOT_TOKEN = YOUR-BOT-TOKEN
TELEGRAM_BOT_NAME = YOUR-BOT-NAME
```

Next step is to create and set Webhook. You can use this tutorial [Set_webhook](https://www.xibel-it.eu/setup-telegram-bot-sdk-with-webhook-in-laravel/)

When Webhook is created open **App\Http\Middleware\VerifyCsrfToken.php** file in your project and add Webhook like so
```
 protected $except = [
        '/89MAaMrev3P6yWCwJOHopn3T6AeiXHWftJaZzJeJFKJjHfAN3rJhC3V/webhook/',
    ];
```

