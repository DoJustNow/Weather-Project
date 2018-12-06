<?php

namespace App\Console\Commands;

use App\Weather;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use VK\Client\VKApiClient;

class VkSyncWallPosts extends Command
{

    private $postsIdBuffer;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wall:post:sync';

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
     */
    public function handle()
    {
        $vk = new VKApiClient();
        $access_token = env('VK_ACCESS_TOKEN');
        $numberGetPosts = 100;
        $wallPosts = Weather::select('posted')
            ->where('posted', '!=', 0)
            ->orderBy('created_at')
            ->chunk($numberGetPosts/*TODO*/,
                function (Collection $posts) {
                    foreach ($posts as $post) {
                        $this->postsIdBuffer[]
                            = $post->posted;
                    }
                });
        //Получили ID всех уникальных постов за все время
        $postsId = array_unique($this->postsIdBuffer);

        /*TODO Цикл по постам вк*/
        $offset = 0;
        do{
        $response = $vk->wall()
            ->get($access_token,
                [
                    'filter' => 'owner',
                    'count' => $numberGetPosts,
                    'offset' => $offset,
                ]);
        $postsCount = count($response['items']);
        $this->info('Количество постов полученных со стены: '.$postsCount);

        //Цикл по полученным постам из VK
        foreach ($response['items'] as $item) {
            //Если на стене есть пост c ID из базы данных удаляем его из буфера т.к он активен
            //По итогу в массиве остануться только ID постов которые небыли найдены на стене
            if (($key = array_search($item['id'], $postsId)) !== false) {
                unset($postsId[$key]);
            }
        }
        Weather::whereIn('posted', $postsId)
            ->chunk($numberGetPosts, function (Collection $weathers) {
                foreach ($weathers as $weather) {
                    $this->info('Пост: '.$weather->posted.' небыл найден.');
                    $weather->posted = 0;
                    $weather->save();
                }
            });
        //Увеличить сдвиг
        $offset +=  $numberGetPosts;
    }while($postsCount >0);
    }
}
