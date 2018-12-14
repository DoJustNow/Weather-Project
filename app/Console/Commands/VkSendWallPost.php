<?php

namespace App\Console\Commands;

use App\Weather;
use Exception;
use Illuminate\Console\Command;
use Log;
use VK\Client\VKApiClient;

class VkSendWallPost extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wall:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Публикация поста на стену личной страницы VK с прогнозом погды';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Число отправляемых записей о прогнозе погоды в одном посте
        $numberForecastSent = 4;
        $vk                 = new VKApiClient();
        $access_token       = env('VK_ACCESS_TOKEN');
        $message            = '';
        do {
            //задержка т.к максимум допустимо 3 обращения в секунду к API
            usleep(340);
            $weathers = Weather::where('posted', 0)
                               ->orderBy('created_at')
                               ->limit($numberForecastSent)
                               ->get();
            if ($weathers->isEmpty()) {
                $this->info("Новых записей для публикации нет.");

                return;
            }
            //Перебор записей погоды из БД; reverse чтобы они шли снизу вверх по дате
            foreach ($weathers->reverse() as $weather) {
                //Формирование сообщения поста
                $message .= "Date: $weather->created_at " .
                            "City: $weather->city " .
                            "Тemp: $weather->temperature " .
                            "Weather: $weather->condition " .
                            "Wind: $weather->wind_speed " .
                            "API: $weather->api\n\n";
            }
            //Публикация поста
            try {
                $result = $vk->wall()
                             ->post($access_token, ['message' => $message]);
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
                $this->error($exception->getMessage());

                return;
            }

            //Назначаем id опубликованного поста записям

            foreach ($weathers as $weather) {
                $weather->posted = $result['post_id'];
                $weather->save();
            }
            $this->info("ID поста: $result[post_id]");
        } while ($weathers->count() == $numberForecastSent);

    }
}
