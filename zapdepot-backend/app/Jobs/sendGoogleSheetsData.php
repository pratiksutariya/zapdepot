<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\GoogleAccount;

class sendGoogleSheetsData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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
      \Log::info("message.....");
           $data = $this->details;
           $gAccount = googleAccount::find($data['receiver_id']);
           $zapData = ZapDetail::where('zap_id',$data['zap_id'])->where('event_type','receiver')->where('interation_type','google_sheet')->first();
           if($zapData) {
                $google_sheet_id = $zapData->tag_id;
                $url="https://sheets.googleapis.com/v4/spreadsheets/$google_sheet_id/values/A1:B1:append?valueInputOption=RAW";
                $method="POST";
                $headers=array('Authorization: Bearer '.$gAccount->access_token,
                'Content-Type: application/json');
                $field=[
                        [
                          $data["name"]
                        ],
                        [
                          $data["email"]
                        ],
                    ];
                $data1["range"]="A1:B1";
                $data1["values"]=$field;
                $data1["majorDimension"]="COLUMNS";
                $new_data1=json_encode($data1);
                $access_options = connectIntegration($url,$headers,$method,$new_data1);
    }
  }
}
