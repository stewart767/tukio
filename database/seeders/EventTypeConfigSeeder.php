<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EventTypeConfig;

class EventTypeConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventTypes = [
            'wedding' => [
                'fields' => [
                    [
                        'name' => 'wedding_title',
                        'label' => 'Wedding Title',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter wedding title'
                    ],
                    [
                        'name' => 'groom_name',
                        'label' => 'Groom\'s Name',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter groom\'s full name'
                    ],
                    [
                        'name' => 'bride_name',
                        'label' => 'Bride\'s Name',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter bride\'s full name'
                    ],
                    [
                        'name' => 'wedding_date',
                        'label' => 'Wedding Date',
                        'type' => 'date',
                        'required' => true
                    ],
                    [
                        'name' => 'venue',
                        'label' => 'Venue',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter wedding venue'
                    ],
                    [
                        'name' => 'couple_photo_main',
                        'label' => 'Main Couple Photo',
                        'type' => 'file',
                        'required' => false,
                        'accept' => 'image/*',
                        'description' => 'Primary couple photo for invitation display'
                    ],
                    [
                        'name' => 'couple_photo_secondary',
                        'label' => 'Secondary Couple Photo',
                        'type' => 'file',
                        'required' => false,
                        'accept' => 'image/*',
                        'description' => 'Additional couple photo for invitation display'
                    ],
                    [
                        'name' => 'contact_person_name',
                        'label' => 'Contact Person Name',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter contact person name'
                    ],
                    [
                        'name' => 'contact_person_phone',
                        'label' => 'Contact Person Phone',
                        'type' => 'tel',
                        'required' => true,
                        'placeholder' => 'Enter contact person phone'
                    ]
                ]
            ],
            'birthday' => [
                'fields' => [
                    [
                        'name' => 'full_name',
                        'label' => 'Full Name',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter full name'
                    ],
                    [
                        'name' => 'birthday_date',
                        'label' => 'Birthday Date',
                        'type' => 'date',
                        'required' => true
                    ],
                    [
                        'name' => 'venue',
                        'label' => 'Venue',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter birthday venue'
                    ],
                    [
                        'name' => 'photo',
                        'label' => 'Photo',
                        'type' => 'file',
                        'required' => false,
                        'accept' => 'image/*'
                    ]
                ]
            ],
            'kitchen_party' => [
                'fields' => [
                    [
                        'name' => 'bride_name',
                        'label' => 'Bride\'s Name',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter bride\'s full name'
                    ],
                    [
                        'name' => 'venue',
                        'label' => 'Venue',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter kitchen party venue'
                    ],
                    [
                        'name' => 'date',
                        'label' => 'Date',
                        'type' => 'date',
                        'required' => true
                    ],
                    [
                        'name' => 'photo',
                        'label' => 'Photo',
                        'type' => 'file',
                        'required' => false,
                        'accept' => 'image/*'
                    ]
                ]
            ],
            'send_off' => [
                'fields' => [
                    [
                        'name' => 'groom_name',
                        'label' => 'Groom\'s Name',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter groom\'s full name'
                    ],
                    [
                        'name' => 'venue',
                        'label' => 'Venue',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter send-off venue'
                    ],
                    [
                        'name' => 'date',
                        'label' => 'Date',
                        'type' => 'date',
                        'required' => true
                    ],
                    [
                        'name' => 'photo',
                        'label' => 'Photo',
                        'type' => 'file',
                        'required' => false,
                        'accept' => 'image/*'
                    ]
                ]
            ],
            'corporate' => [
                'fields' => [
                    [
                        'name' => 'event_title',
                        'label' => 'Event Title',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter event title'
                    ],
                    [
                        'name' => 'venue',
                        'label' => 'Venue',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter corporate event venue'
                    ],
                    [
                        'name' => 'date',
                        'label' => 'Date',
                        'type' => 'date',
                        'required' => true
                    ],
                    [
                        'name' => 'event_banner',
                        'label' => 'Event Banner',
                        'type' => 'file',
                        'required' => false,
                        'accept' => 'image/*'
                    ],
                    [
                        'name' => 'contact_person_name',
                        'label' => 'Contact Person Name',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter contact person name'
                    ],
                    [
                        'name' => 'contact_person_phone',
                        'label' => 'Contact Person Phone',
                        'type' => 'tel',
                        'required' => true,
                        'placeholder' => 'Enter contact person phone'
                    ]
                ]
            ]
        ];

        foreach ($eventTypes as $eventType => $config) {
            EventTypeConfig::create([
                'event_type' => $eventType,
                'fields_config' => $config,
                'is_active' => true,
            ]);
        }
    }
}
