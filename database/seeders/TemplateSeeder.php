<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Template;
use App\Models\User;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        if (!$admin) {
            return;
        }

        $templates = [
            [
                'name' => 'Elegant Wedding',
                'category' => 'wedding',
                'description' => 'A beautiful and elegant wedding invitation template',
                'html_content' => '<div class="wedding-invitation bg-gradient-to-br from-pink-100 to-rose-200 p-8 rounded-lg shadow-lg text-center">
                    <h1 class="text-4xl font-bold text-rose-800 mb-4">{{event_title}}</h1>
                    <p class="text-lg text-rose-700 mb-6">You are cordially invited to celebrate</p>
                    <div class="bg-white p-6 rounded-lg mb-6">
                        <p class="text-gray-700 mb-2"><strong>Date:</strong> {{event_date}}</p>
                        <p class="text-gray-700 mb-2"><strong>Time:</strong> {{event_time}}</p>
                        <p class="text-gray-700"><strong>Venue:</strong> {{event_venue}}</p>
                    </div>
                    <p class="text-rose-600">We look forward to celebrating with you!</p>
                </div>',
                'css_content' => '.wedding-invitation { font-family: "Playfair Display", serif; }',
            ],
            [
                'name' => 'Modern Corporate',
                'category' => 'corporate',
                'description' => 'Professional corporate event invitation template',
                'html_content' => '<div class="corporate-invitation bg-blue-50 p-8 rounded-lg shadow-lg">
                    <div class="text-center mb-6">
                        <h1 class="text-3xl font-bold text-blue-900 mb-2">{{event_title}}</h1>
                        <p class="text-blue-700">Professional Event Invitation</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg border-l-4 border-blue-500">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600"><strong>Date:</strong> {{event_date}}</p>
                                <p class="text-gray-600"><strong>Time:</strong> {{event_time}}</p>
                            </div>
                            <div>
                                <p class="text-gray-600"><strong>Venue:</strong> {{event_venue}}</p>
                                <p class="text-gray-600"><strong>Address:</strong> {{event_venue_address}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-6">
                        <p class="text-blue-800 font-semibold">RSVP Required</p>
                    </div>
                </div>',
                'css_content' => '.corporate-invitation { font-family: "Inter", sans-serif; }',
            ],
            [
                'name' => 'Birthday Celebration',
                'category' => 'birthday',
                'description' => 'Fun and colorful birthday party invitation template',
                'html_content' => '<div class="birthday-invitation bg-gradient-to-br from-yellow-400 to-pink-500 p-8 rounded-lg shadow-lg text-white text-center">
                    <h1 class="text-4xl font-bold mb-4">Happy Birthday!</h1>
                    <h2 class="text-2xl mb-6">{{full_name}}</h2>
                    <p class="text-xl mb-6">You\'re invited to celebrate!</p>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm p-6 rounded-lg mb-6">
                        <p class="mb-2"><strong>Date:</strong> {{birthday_date}}</p>
                        <p class="mb-2"><strong>Venue:</strong> {{venue}}</p>
                    </div>
                    <p class="text-lg">Let\'s make it a memorable celebration!</p>
                </div>',
                'css_content' => '.birthday-invitation { font-family: "Comic Sans MS", cursive; }',
            ],
            [
                'name' => 'Fun Kitchen Party',
                'category' => 'kitchen_party',
                'description' => 'Vibrant and fun kitchen party invitation template',
                'html_content' => '<div class="kitchen-party-invitation bg-gradient-to-r from-orange-400 to-pink-500 p-8 rounded-lg shadow-lg text-white text-center">
                    <h1 class="text-4xl font-bold mb-4">{{event_title}}</h1>
                    <p class="text-xl mb-6">Let\'s celebrate together!</p>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm p-6 rounded-lg mb-6">
                        <p class="mb-2"><strong>Date:</strong> {{event_date}}</p>
                        <p class="mb-2"><strong>Time:</strong> {{event_time}}</p>
                        <p class="mb-2"><strong>Venue:</strong> {{event_venue}}</p>
                    </div>
                    <p class="text-lg">Bring your dancing shoes and party spirit!</p>
                </div>',
                'css_content' => '.kitchen-party-invitation { font-family: "Poppins", sans-serif; }',
            ],
            [
                'name' => 'Farewell Send-off',
                'category' => 'send_off',
                'description' => 'Heartfelt send-off party invitation template',
                'html_content' => '<div class="sendoff-invitation bg-gradient-to-br from-blue-600 to-purple-700 p-8 rounded-lg shadow-lg text-white text-center">
                    <h1 class="text-4xl font-bold mb-4">{{event_title}}</h1>
                    <p class="text-xl mb-6">Join us in celebrating and bidding farewell</p>
                    <div class="bg-white bg-opacity-10 backdrop-blur-sm p-6 rounded-lg mb-6">
                        <p class="mb-2"><strong>Date:</strong> {{event_date}}</p>
                        <p class="mb-2"><strong>Time:</strong> {{event_time}}</p>
                        <p class="mb-2"><strong>Venue:</strong> {{event_venue}}</p>
                    </div>
                    <p class="text-lg">Let\'s make memories together before the journey begins!</p>
                </div>',
                'css_content' => '.sendoff-invitation { font-family: "Montserrat", sans-serif; }',
            ],
        ];

        foreach ($templates as $template) {
            Template::create([
                'name' => $template['name'],
                'category' => $template['category'],
                'description' => $template['description'],
                'html_content' => $template['html_content'],
                'css_content' => $template['css_content'],
                'is_active' => true,
                'created_by' => $admin->id,
            ]);
        }
    }
}
