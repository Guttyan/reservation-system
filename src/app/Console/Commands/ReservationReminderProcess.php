<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationReminder;

class ReservationReminderProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to users with reservations today';

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
     * @return int
     */
    public function handle()
    {
        $today = now()->toDateString();
        $reservations = Reservation::whereDate('date', $today)->get();

        foreach ($reservations as $reservation) {
            $qrCodePath = public_path($reservation->qr_code);
            Mail::to($reservation->user->email)->send(new ReservationReminder($reservation, $qrCodePath));
        }


        $this->info('Reminder emails sent successfully!');
    }
}
