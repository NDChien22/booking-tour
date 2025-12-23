<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateCompletedBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:update-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update booking status to completed if departure date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updated = Booking::where('status', '!=', 'cancelled')
            ->whereDate('departure_date', '<=', Carbon::today())
            ->where('status', '!=', 'completed')
            ->update(['status' => 'completed']);

        $this->info('Updated ' . $updated . ' bookings to completed status!');
    }
}
