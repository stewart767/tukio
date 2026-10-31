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
                'name' => 'Royal Gold & Ivory Wedding',
                'category' => 'wedding',
                'description' => 'Luxury gold and ivory royal wedding card with serif calligraphy and elegant borders',
                'html_content' => '<div class="royal-wedding bg-[#fdfbf7] border-4 border-[#d4af37] p-8 rounded-2xl shadow-2xl text-center max-w-lg mx-auto">
                    <div class="text-[#d4af37] text-2xl font-serif tracking-widest uppercase mb-2">Save The Date</div>
                    <h1 class="text-3xl font-extrabold text-[#1a202c] font-serif mb-3">{{event_title}}</h1>
                    <p class="text-sm italic text-[#718096] mb-6">Together with their families, invite you to share in the joy of their holy matrimony</p>
                    <div class="bg-gradient-to-r from-[#fefcf8] via-[#f7f3e8] to-[#fefcf8] border-y border-[#d4af37] p-4 mb-6">
                        <div class="text-lg font-bold text-[#b78727] mb-1">{{event_date}} | {{event_time}}</div>
                        <div class="text-sm font-semibold text-[#2d3748]">{{event_venue}}</div>
                        <div class="text-xs text-[#718096]">{{event_venue_address}}</div>
                    </div>
                    <div class="text-xs tracking-wider text-[#b78727] font-bold uppercase">Meza: {{table_number}} | {{invitation_type}}</div>
                </div>',
                'css_content' => '.royal-wedding { font-family: "Cinzel", "Playfair Display", serif; }',
            ],
            [
                'name' => 'African Floral Send-Off',
                'category' => 'send_off',
                'description' => 'Vibrant rose gold & African floral elegance for unforgettable send-off ceremonies',
                'html_content' => '<div class="sendoff-floral bg-gradient-to-b from-rose-50 via-white to-amber-50 p-8 rounded-2xl border-2 border-rose-300 shadow-xl text-center max-w-lg mx-auto">
                    <span class="inline-block px-3 py-1 bg-rose-100 text-rose-800 text-xs font-bold rounded-full uppercase tracking-wider mb-3">Send-Off Celebration</span>
                    <h1 class="text-3xl font-black text-rose-950 mb-3">{{event_title}}</h1>
                    <p class="text-sm text-gray-600 mb-5">Karibu ujumuike nasi kumuaga na kumpongeza binti yetu mpendwa katika safari yake mpya ya maisha</p>
                    <div class="bg-white/90 rounded-xl p-4 shadow-sm border border-rose-200 mb-5">
                        <div class="text-base font-bold text-rose-900">{{event_date}} • {{event_time}}</div>
                        <div class="text-sm text-gray-700 font-medium">{{event_venue}}</div>
                    </div>
                    <p class="text-xs font-semibold text-rose-700">Dress Code: Elegance & Traditional Glamour</p>
                </div>',
                'css_content' => '.sendoff-floral { font-family: "Poppins", sans-serif; }',
            ],
            [
                'name' => 'Glamour Chic Kitchen Party',
                'category' => 'kitchen_party',
                'description' => 'Pastel pink & champagne luxury kitchen party card with delicate touches',
                'html_content' => '<div class="kitchen-chic bg-gradient-to-tr from-pink-50 via-pink-100/60 to-purple-50 p-8 rounded-2xl border border-pink-200 shadow-lg text-center max-w-lg mx-auto">
                    <div class="text-pink-600 font-semibold text-xs uppercase tracking-widest mb-1">Exclusive Ladies Gathering</div>
                    <h1 class="text-3xl font-extrabold text-pink-900 mb-3">{{event_title}}</h1>
                    <p class="text-xs text-pink-800 mb-4">Sherehe ya mafunzo, zawadi na shangwe kwa bibi harusi mtarajiwa</p>
                    <div class="bg-white/80 backdrop-blur rounded-xl p-4 border border-pink-200 mb-4">
                        <div class="font-bold text-sm text-gray-800">{{event_date}} saa {{event_time}}</div>
                        <div class="text-xs text-gray-600">{{event_venue}}</div>
                    </div>
                    <div class="text-xs font-bold text-pink-700">Zawadi & Michango inakaribishwa kwa furaha!</div>
                </div>',
                'css_content' => '.kitchen-chic { font-family: "Playfair Display", serif; }',
            ],
            [
                'name' => 'Kipaimara & Ubatizo Blessing',
                'category' => 'traditional',
                'description' => 'Holy communion, baptism, and confirmation blessing celebration invitation',
                'html_content' => '<div class="communion-blessing bg-slate-50 border-2 border-amber-400 p-8 rounded-2xl shadow-xl text-center max-w-lg mx-auto">
                    <div class="text-amber-700 font-bold text-xs uppercase tracking-widest mb-2">✝ Ibada ya Shukrani & Sherehe ✝</div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-3">{{event_title}}</h1>
                    <p class="text-xs text-slate-600 mb-4">"Mimi na nyumba yangu tutamtumikia Bwana" - Karibu ushiriki nasi furaha ya sakramenti takatifu</p>
                    <div class="bg-white rounded-xl p-4 border border-slate-200 mb-4">
                        <div class="font-bold text-sm text-slate-800">{{event_date}} • {{event_time}}</div>
                        <div class="text-xs text-slate-600">{{event_venue}}</div>
                    </div>
                    <div class="text-xs text-amber-800 font-medium">Karibu sana tujumuike kwa chakula cha mchana na shangwe!</div>
                </div>',
                'css_content' => '.communion-blessing { font-family: "Cinzel", Georgia, serif; }',
            ],
            [
                'name' => 'Executive Corporate Summit & Gala',
                'category' => 'corporate',
                'description' => 'Midnight navy and platinum executive card for high-level dinners and award ceremonies',
                'html_content' => '<div class="corp-summit bg-slate-900 text-white p-8 rounded-2xl border border-slate-700 shadow-2xl text-center max-w-lg mx-auto">
                    <div class="text-indigo-400 font-bold text-xs uppercase tracking-widest mb-2">Annual Gala & Awards</div>
                    <h1 class="text-3xl font-black text-white mb-3">{{event_title}}</h1>
                    <p class="text-xs text-slate-400 mb-5">An exclusive evening of innovation, executive networking, and excellence recognition</p>
                    <div class="bg-slate-800/80 rounded-xl p-4 border border-slate-700 mb-5">
                        <div class="font-bold text-sm text-indigo-300">{{event_date}} | {{event_time}}</div>
                        <div class="text-xs text-slate-300">{{event_venue}}</div>
                    </div>
                    <div class="text-xs text-slate-400">Strictly Black Tie / Official Executive Attire Required</div>
                </div>',
                'css_content' => '.corp-summit { font-family: "Inter", sans-serif; }',
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
