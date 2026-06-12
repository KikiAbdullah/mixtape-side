<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataDraft;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupDraftsCommand extends Command
{
    protected $signature = 'drafts:cleanup';
    protected $description = 'Cleanup old rejected, expired, or cancelled draft assets';

    public function handle()
    {
        $cutoff = Carbon::now()->subDays(7);
        
        $oldDrafts = DataDraft::whereIn('status', ['Rejected', 'Expired', 'Cancelled'])
            ->where('updated_at', '<', $cutoff)
            ->get();

        foreach ($oldDrafts as $draft) {
            $data = $draft->proposed_data;
            // Logic to find file paths in JSON and delete them
            // This is a simplified example
            foreach ($data as $key => $value) {
                if (is_string($value) && strpos($value, 'drafts/') !== false) {
                    if (Storage::disk('public')->exists($value)) {
                        Storage::disk('public')->delete($value);
                        $this->info("Deleted asset: $value");
                    }
                }
            }
        }

        $this->info('Draft cleanup completed.');
    }
}
