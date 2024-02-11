<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\GohighlevelAccounts;
use Illuminate\Support\Facades\Http;
use App\Models\ZapDetail;
use Illuminate\Support\Facades\Log;

class sendContactToGohighlevel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $details;
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('send-gohigh');
        $user_data = $this->details;
        // dd($user_data);
        $url = "https://rest.gohighlevel.com/v1/contacts/";
        $data = GohighlevelAccounts::find($user_data['receiver_id']);
        // dump($data);
        // dd($user_data);
        if ($data) {
            $tags = ZapDetail::where('zap_id', $user_data['zap_id'])->where('event_type', 'receiver')->where('interation_type', 'gohighlevel')->pluck('tag_id');
            $array = ['email' => $user_data['email'], 'firstName' => $user_data['firstName'],'lastName' => $user_data['lastName'], 'phone' => $user_data['phone'], 'tags' => $tags];
            // dd($data);
            // $array = [
            //     'contact' => [
            //         'email' => $user_data['email'],
            //         'firstName' => $user_data['firstName'],
            //         'lastName' => $user_data['lastName'],
            //         'phone' => $user_data['phone'],
            //         'tags' => $tags
            //     ]
            // ];
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $data->api_key,
                'Content-Type' => 'application/json',
            ])->post($url, $array);
            // dump($response->object());
            if ($response->status() == 201 || $response->status() == 200) {
                $rdata = $response->object();
                $contact = $rdata->contact;
                $tags = ZapDetail::where('zap_id', $user_data['zap_id'])->where('event_type', 'receiver')->where('interation_type', 'gohighlevel')->first();
                if ($tags) {
                    if ($tags->tag_id) {
                        $tag_url = "https://rest.gohighlevel.com/v1/tags/";
                        $tagarray = [
                            'tags' => [
                                "id" => $contact->id,
                                'name' => $tags->tag_id,
                            ]
                        ];
                        $response = Http::withHeaders([
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                            'api-token' => $data->api_key,
                        ])->post($tag_url, $tagarray);
                    }
                }
            }
            // dd($response);
        }
    }
}
