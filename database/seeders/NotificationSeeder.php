<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        
        if ($users->count() === 0) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        // Create sample notifications for each user
        foreach ($users as $user) {
            // Sample donation notification
            NotificationService::createNotification(
                $user,
                'donation',
                'Thank you for your donation! Your contribution helps support our alumni community.',
                'Donation',
                1
            );

            // Sample event notification
            NotificationService::createNotification(
                $user,
                'event',
                'New alumni event: Annual Reunion 2024 - Register now!',
                'AlumniPost',
                1
            );

            // Sample message notification
            NotificationService::createNotification(
                $user,
                'message',
                'You have received a new message from the admin team.',
                'Message',
                1
            );

            // Sample news notification
            NotificationService::createNotification(
                $user,
                'news',
                'Important update: New features added to the alumni portal',
                'News',
                1
            );

            // Create one read notification
            $readNotification = NotificationService::createNotification(
                $user,
                'comment',
                'Someone commented on your post: "Great to see everyone at the reunion!"',
                'Comment',
                1
            );
            $readNotification->markAsRead();
        }

        $this->command->info('Created sample notifications for all users.');
    }
}
