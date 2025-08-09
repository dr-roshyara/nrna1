<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PublishersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample publishers for NRNA election system
        // In real implementation, these should be actual committee members
        
        $publishers = [
            [
                'name' => 'Election Committee Chairperson',
                'title' => 'Committee Chairperson',
                'email' => 'chair@nrna-germany.org',
                'should_agree' => true,
                'priority_order' => 1,
                'notes' => 'Primary authority for result publication'
            ],
            [
                'name' => 'Chief Election Officer',
                'title' => 'Chief Election Officer',
                'email' => 'ceo@nrna-germany.org',
                'should_agree' => true,
                'priority_order' => 2,
                'notes' => 'Technical oversight and verification'
            ],
            [
                'name' => 'Independent Observer 1',
                'title' => 'Independent Observer',
                'email' => 'observer1@nrna-germany.org',
                'should_agree' => true,
                'priority_order' => 3,
                'notes' => 'External verification authority'
            ],
            [
                'name' => 'Independent Observer 2',
                'title' => 'Independent Observer',
                'email' => 'observer2@nrna-germany.org',
                'should_agree' => true,
                'priority_order' => 4,
                'notes' => 'External verification authority'
            ],
            [
                'name' => 'Committee Member - Legal',
                'title' => 'Legal Committee Member',
                'email' => 'legal@nrna-germany.org',
                'should_agree' => true,
                'priority_order' => 5,
                'notes' => 'Legal compliance verification'
            ],
            [
                'name' => 'Committee Member - Technical',
                'title' => 'Technical Committee Member',
                'email' => 'tech@nrna-germany.org',
                'should_agree' => true,
                'priority_order' => 6,
                'notes' => 'Technical system verification'
            ],
            [
                'name' => 'State Representative - Bavaria',
                'title' => 'Bavaria State Representative',
                'email' => 'bavaria@nrna-germany.org',
                'should_agree' => true,
                'priority_order' => 7,
                'notes' => 'Regional representation'
            ],
            [
                'name' => 'State Representative - NRW',
                'title' => 'NRW State Representative',
                'email' => 'nrw@nrna-germany.org',
                'should_agree' => true,
                'priority_order' => 8,
                'notes' => 'Regional representation'
            ],
            [
                'name' => 'General Secretary',
                'title' => 'General Secretary',
                'email' => 'secretary@nrna-germany.org',
                'should_agree' => false, // Optional publisher
                'priority_order' => 9,
                'notes' => 'Administrative oversight (optional)'
            ],
            [
                'name' => 'Treasurer',
                'title' => 'Treasurer',
                'email' => 'treasurer@nrna-germany.org',
                'should_agree' => false, // Optional publisher
                'priority_order' => 10,
                'notes' => 'Financial oversight (optional)'
            ]
        ];

        foreach ($publishers as $publisherData) {
            // Find or create user
            $user = User::where('email', $publisherData['email'])->first();
            
            if (!$user) {
                // Create user if doesn't exist
                $user = User::create([
                    'name' => $publisherData['name'],
                    'email' => $publisherData['email'],
                    'password' => Hash::make('password123'), // Default password
                    'email_verified_at' => now(),
                    'is_voter' => false, // Publishers are not voters
                    'can_vote' => false,
                ]);
                
                echo "Created user: {$user->email}\n";
            }

            // Generate unique authorization password
            $authPassword = Publisher::generateAuthorizationPassword();
            
            // Create publisher record
            $publisher = Publisher::create([
                'publisher_id' => Publisher::generatePublisherId(),
                'user_id' => $user->id,
                'name' => $publisherData['name'],
                'title' => $publisherData['title'],
                'should_agree' => $publisherData['should_agree'],
                'authorization_password' => $authPassword, // Will be hashed automatically
                'is_active' => true,
                'priority_order' => $publisherData['priority_order'],
                'notes' => $publisherData['notes']
            ]);

            echo "Created publisher: {$publisher->name} (ID: {$publisher->publisher_id})\n";
            echo "  - User: {$user->email}\n";
            echo "  - Title: {$publisher->title}\n";
            echo "  - Required: " . ($publisher->should_agree ? 'YES' : 'NO') . "\n";
            echo "  - Auth Password: {$authPassword}\n";
            echo "  - Priority: {$publisher->priority_order}\n";
            echo "----------------------------------------\n";
        }

        echo "\nPublisher setup complete!\n";
        echo "Required publishers: " . Publisher::required()->count() . "\n";
        echo "Total publishers: " . Publisher::count() . "\n";
        
        echo "\n🔐 IMPORTANT: Save these authorization passwords securely!\n";
        echo "Publishers will need these passwords to authorize result publication.\n";
        
        // Display summary of required publishers
        echo "\n📋 REQUIRED PUBLISHERS FOR AUTHORIZATION:\n";
        $requiredPublishers = Publisher::required()->byPriority()->get();
        foreach ($requiredPublishers as $index => $publisher) {
            echo ($index + 1) . ". {$publisher->name} ({$publisher->title})\n";
        }
    }
}