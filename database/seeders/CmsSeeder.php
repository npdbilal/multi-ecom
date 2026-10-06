<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Sample CMS pages and homepage banners. Fully manageable afterwards
 * from the admin panel (Pages / Banners).
 */
class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'body' => "<h2>About Us</h2>\n<p>Welcome to our store — a modern, modular e-commerce experience built with Laravel. We curate quality products and deliver them to your doorstep.</p>",
                'meta_title' => 'About Us',
                'sort_order' => 1,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'body' => "<h2>Contact Us</h2>\n<p>Questions about an order? Reach us any time:</p>\n<ul>\n<li>Email: hello@example.com</li>\n<li>Phone: +92 300 1234567</li>\n</ul>",
                'meta_title' => 'Contact Us',
                'sort_order' => 2,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms',
                'body' => "<h2>Terms &amp; Conditions</h2>\n<p>By shopping with us you agree to our fair-use shopping terms: accurate product information, secure checkout, and a 7-day return window on eligible items.</p>",
                'meta_title' => 'Terms & Conditions',
                'sort_order' => 3,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'body' => "<h2>Privacy Policy</h2>\n<p>We collect only the data needed to fulfil your orders. We never sell your personal information to third parties.</p>",
                'meta_title' => 'Privacy Policy',
                'sort_order' => 4,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page + ['is_active' => true]);
        }

        $banners = [
            [
                'title' => 'New Season Collection',
                'subtitle' => 'Fresh arrivals up to 30% off — limited time only.',
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&q=80',
                'link' => '/products',
                'sort_order' => 1,
            ],
            [
                'title' => 'Free Shipping Over $50',
                'subtitle' => 'No code needed. Applied automatically at checkout.',
                'image' => 'https://images.unsplash.com/photo-1586880244406-556ebe35f282?w=1600&q=80',
                'link' => '/products',
                'sort_order' => 2,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['title' => $banner['title']],
                $banner + ['is_active' => true]
            );
        }
    }
}
