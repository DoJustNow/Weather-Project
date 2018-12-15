<?php

namespace App\Console\Commands;

use App\Weather;
use Exception;
use Illuminate\Console\Command;
use Log;
use VK\Client\VKApiClient;

class VkSyncWallPosts extends Command
{

    private $postsIdBuffer;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wall:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Комманда выполняющая синхронизацию постов VK.';

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
     * @throws \VK\Exceptions\Api\VKApiBlockedException
     * @throws \VK\Exceptions\Api\VKApiUserDeletedException
     * @throws \VK\Exceptions\VKApiException
     * @throws \VK\Exceptions\VKClientException
     */
    public function handle()
    {
        $vk           = new VKApiClient();
        $access_token = env('VK_ACCESS_TOKEN');
        //Число получаемых записей со страницы
        $numberGetPosts = 100;
        $offset         = 0;
        //Зпись в буфер всех ID постов VK из БД
        Weather::select('posted')
               ->where('posted', '!=', 0)
               ->orderBy('created_at')
               ->chunk($numberGetPosts, function ($posts) {
                   foreach ($posts as $post) {
                       $this->postsIdBuffer[] = $post->posted;
                   }
               });
        //Удаление дубликатов из буфера
        $this->postsIdBuffer = array_unique($this->postsIdBuffer);
        //Цикл по постам на стене VK
        do {
            try {
                $response = $vk->wall()
                               ->get($access_token,
                                   [
                                       'filter' => 'owner',
                                       'count'  => $numberGetPosts,
                                       'offset' => $offset,
                                   ]);
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
                $this->error($exception->getMessage());

                return;
            }
            $postsCount = count($response['items']);
            $this->info("Количество постов полученных со стены: $postsCount");

            //Цикл по полученным постам из VK
            foreach ($response['items'] as $item) {
                //Если на стене есть пост c ID из базы данных удаляем его из буфера т.к он активен
                //По итогу в массиве остануться только ID постов которые небыли найдены на стене
                if (($key = array_search($item['id'], $this->postsIdBuffer))
                    !== false
                ) {
                    unset($this->postsIdBuffer[$key]);
                }
            }
            //Цикл по записям в БД и выборка всех постов с posted который есть в буфере
            //то есть те посты которые небыли найдены на стене
            Weather::whereIn('posted', $this->postsIdBuffer)
                   ->chunk($numberGetPosts, function ($weathers) {
                       foreach ($weathers as $weather) {
                           $this->info("Пост: $weather->posted небыл найден.");
                           $weather->posted = 0;
                           $weather->save();
                       }
                   });
            //Увеличить сдвиг
            $offset += $numberGetPosts;
        } while ($postsCount > 0);
    }
}
