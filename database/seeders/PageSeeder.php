<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'homepage',
                'title' => 'Homepage',
                'content' => '<h1>Welcome to MyCard</h1><p>Your digital invitation management system.</p>',
                'meta_title' => 'MyCard - Digital Invitation Management System',
                'meta_description' => 'Create and manage beautiful digital invitations for your events with ease.',
                'is_active' => true,
                'sort_order' => 1,
                'welcome_title' => 'Welcome to Our Platform',
                'welcome_content' => 'Discover how our digital invitation system can transform your event planning experience with cutting-edge technology and seamless integration.',
                'benefits_title' => 'Why Choose Our Platform',
                'benefits_subtitle' => 'Experience the difference with our innovative event management solution',
                'benefits_content' => '[{"icon":"⏱️","title":"Save Time","description":"Create and send invitations in minutes, not hours"},{"icon":"💰","title":"Cost Effective","description":"Reduce printing and postage costs significantly"},{"icon":"📱","title":"Mobile Friendly","description":"Perfect display on all devices and screen sizes"},{"icon":"🔒","title":"Secure","description":"Enterprise-grade security for your event data"}]',
                'faq_title' => 'Frequently Asked Questions',
                'faq_subtitle' => 'Find answers to common questions about our platform',
                'faq_content' => '[{"question":"How do I get started?","answer":"Simply sign up for a free account, choose a template, customize it for your event, and start sending invitations!"},{"question":"Can I use my own domain?","answer":"Yes, our premium plans include custom domain support so you can use your own branded URLs."},{"question":"Is there a mobile app?","answer":"Our platform is fully responsive and works perfectly on mobile devices. We\'re also working on dedicated mobile apps!"}]',
            ],
            [
                'slug' => 'about',
                'title' => 'About Us',
                'content' => '<h1>About MyCard</h1><p>We provide a comprehensive digital invitation management platform to help you create, manage, and send beautiful invitations for all your events.</p>',
                'meta_title' => 'About Us - MyCard',
                'meta_description' => 'Learn more about MyCard and our mission to revolutionize event invitation management.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'content' => '<h1>Privacy Policy</h1><p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information when you use our services.</p><h2>Information We Collect</h2><p>We collect information that you provide directly to us when you create an account, use our services, or contact us for support.</p><h2>How We Use Your Information</h2><p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p><h2>Data Security</h2><p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>',
                'meta_title' => 'Privacy Policy - MyCard',
                'meta_description' => 'Read our privacy policy to understand how we collect, use, and protect your personal information.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'slug' => 'terms-of-use',
                'title' => 'Terms of Service',
                'content' => '<h1>Terms of Service</h1><p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p><h2>Use License</h2><p>Permission is granted to temporarily access the materials on our website for personal, non-commercial transitory viewing only.</p><h2>Disclaimer</h2><p>The materials on our website are provided on an "as is" basis. We make no warranties, expressed or implied, and hereby disclaim all other warranties.</p><h2>Limitations</h2><p>In no event shall our company or its suppliers be liable for any damages arising out of the use or inability to use the materials on our website.</p>',
                'meta_title' => 'Terms of Service - MyCard',
                'meta_description' => 'Read our terms of service to understand the rules and regulations for using our services.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'slug' => 'cookie-policy',
                'title' => 'Cookie Policy',
                'content' => '<h1>Cookie Policy</h1><p>This cookie policy explains what cookies are and how we use them on our website.</p><h2>What Are Cookies</h2><p>Cookies are small text files that are placed on your computer or mobile device when you visit a website. They are widely used to make websites work more efficiently.</p><h2>How We Use Cookies</h2><p>We use cookies to improve your experience on our website, analyze site usage, and assist in our marketing efforts.</p><h2>Types of Cookies We Use</h2><ul><li>Essential Cookies: Required for the website to function properly</li><li>Analytics Cookies: Help us understand how visitors interact with our website</li><li>Preference Cookies: Remember your preferences and settings</li></ul><h2>Managing Cookies</h2><p>You can control and manage cookies through your browser settings. However, disabling cookies may affect the functionality of our website.</p>',
                'meta_title' => 'Cookie Policy - MyCard',
                'meta_description' => 'Learn about how we use cookies on our website and how to manage your cookie preferences.',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'content' => '<h1>Contact Us</h1><p>We would love to hear from you. Get in touch with us using the information below or fill out the contact form.</p><h2>Contact Information</h2><p><strong>Email:</strong> support@mycard.com</p><p><strong>Phone:</strong> +255 XXX XXX XXX</p><p><strong>Address:</strong> Your Address Here</p><h2>Business Hours</h2><p>Monday - Friday: 9:00 AM - 5:00 PM</p><p>Saturday: 10:00 AM - 2:00 PM</p><p>Sunday: Closed</p>',
                'meta_title' => 'Contact Us - MyCard',
                'meta_description' => 'Get in touch with us. We are here to help you with any questions or concerns.',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
