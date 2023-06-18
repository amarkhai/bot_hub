<?php

declare(strict_types=1);

namespace App\Http\Webhooks;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use GuzzleHttp\Client;
use Illuminate\Support\Stringable;

class RateWebhookHandler extends WebhookHandler
{
    private const TEMPLATE = <<<rate
*Курс "МИР"*:
1 RUB = rub-to-amd AMD
1 AMD = amd-to-rub RUB
rate;

    public function __construct(private readonly Client $httpClient)
    {
        parent::__construct();
    }

    public function start()
    {
        $this->chat->markdown('*Курс свежайший, как бабушкины пирожки*')->send();
        $this->chat
            ->message('Этот бот покажет, как рубль встает с колен')
            ->keyboard(Keyboard::make()->buttons([
                Button::make('Показать')->action('showRate'),
            ]))
            ->send();
    }

    public function help(...$args)
    {
        print_r($args);
    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->chat->html('Такой команде еще не обучен')->send();
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $this->chat
            ->html('Хьюстон, у нас проблемы! Неопознанное сообщение!')
            ->send();
    }

    public function showRate(): void
    {
        $this->chat->html('Загружаем свежайшие курсы')->send();
        $response = $this->httpClient->get('https://mironline.ru/support/list/kursy_mir/');
        preg_match('/Армянский драм\s+.+\s+.+\s+.+\s+.+\s+.+\s+(\d,\d+)<br>/u', $response->getBody()->getContents(), $matches);
        $amdToRub = (float) str_replace(',', '.', $matches[1]);
        $rubToAmd = round(1 / $amdToRub, 4);
        $this->chat
            ->markdown(str_replace(['rub-to-amd', 'amd-to-rub'], [$rubToAmd, $amdToRub], self::TEMPLATE))
            ->send();
    }
}
