<?php

namespace App\Jobs;

use App\Models\Policy;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPolicySmsReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Policy $policy;

    public function __construct(Policy $policy)
    {
        $this->policy = $policy;
    }

    public function handle(): void
    {
        // Guard conditions
        if ((Setting::get('sms_enabled', '0') !== '1')) {
            return;
        }
        if (!$this->policy->customer_phone) {
            return;
        }

        // Prepare message
        $template = Setting::get('sms_template', 'Sayın {customerTitle}, {policyNumber} numaralı poliçenizin bitiş tarihi olan {endDate} yaklaşıyor. Yenileme için bize ulaşabilirsiniz.');
        $message = str_replace([
            '{customerTitle}', '{policyNumber}', '{endDate}'
        ], [
            $this->policy->customer_title,
            $this->policy->policy_number,
            optional($this->policy->end_date)->format('Y-m-d')
        ], $template);

        // TODO: Integrate real SMS provider here (Netgsm/Twilio). For now, just log.
        Log::info('SMS reminder would be sent', [
            'to' => $this->policy->customer_phone,
            'message' => $message,
            'policy_id' => $this->policy->id,
        ]);

        // Mark as sent
        $this->policy->forceFill(['sms_reminder_sent_at' => now()])->save();
    }
}


