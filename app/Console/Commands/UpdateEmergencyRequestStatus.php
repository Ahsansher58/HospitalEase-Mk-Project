<?php

namespace App\Console\Commands;

use App\Models\EmergencyRequest;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateEmergencyRequestStatus extends Command
{
  /**
   * The name and signature of the console command.
   */
  protected $signature = 'emergency:update-status';

  /**
   * The console command description.
   */
  protected $description = 'Update the status of emergency requests from 1 to 0 after 12 hours';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $updatedCount = EmergencyRequest::where('status', 1)
      ->where('date_time', '<=', Carbon::now()->subHours(12))
      ->update(['status' => 0]);

    $this->info("Updated $updatedCount emergency requests to status 0.");
  }
}
