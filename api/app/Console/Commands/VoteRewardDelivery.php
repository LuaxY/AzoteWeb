<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\VoteReward;
use App\Gift;

class VoteRewardDelivery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vote_reward_delivery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send vote reward to each users';

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
        $users = User::all();

        foreach ($users as $user) {
            $numberOfRewards = (int)($user->votes / 10);

            $rewards = VoteReward::orderBy('votes', 'asc')->take($numberOfRewards)->get();
            $rewardItemsId = [];

            foreach ($rewards as $reward) {
                $description = "Cadeau " . $reward->votes . " votes";

                if (Gift::where('user_id', $user->id)->where('description', $description)->first()) {
                    continue;
                }

                $gift = new Gift;
                $gift->user_id     = $user->id;
                $gift->item_id     = $reward->itemId;
                $gift->description = $description;
                $gift->save();

                $rewardItemsId[] = $reward->itemId;
            }

            echo "{$user->pseudo}: ".count($rewardItemsId)."/$numberOfRewards gifts [" . implode(', ', $rewardItemsId) . "]\n";
        }
    }
}
