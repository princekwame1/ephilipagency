<?php

namespace App\Support;

/**
 * Every piece of public-site text, image and section switch that can be edited in Admin → Site content.
 *
 * Each field is stored as a Setting under its key; the value here is the default shown until the admin
 * changes it. Text can use {business} and {tagline}, which are replaced with the brand settings.
 *
 * To make something new editable: add a field here, then use site('key') in the view.
 */
class SiteContent
{
    /** @return array<string, array{label: string, sections: array<string, array{label: string, fields: array<string, array>}>}> */
    public static function pages(): array
    {
        $t = fn (string $label, string $default = '', ?string $hint = null) => ['type' => 'text', 'label' => $label, 'default' => $default, 'hint' => $hint];
        $area = fn (string $label, string $default = '', ?string $hint = null) => ['type' => 'textarea', 'label' => $label, 'default' => $default, 'hint' => $hint];
        $show = fn (string $label = 'Show this section') => ['type' => 'toggle', 'label' => $label, 'default' => '1', 'hint' => null];
        $img = fn (string $label, string $default, ?string $hint = null) => ['type' => 'image', 'label' => $label, 'default' => $default, 'hint' => $hint];

        return [
            'home' => ['label' => 'Home page', 'sections' => [
                'hero' => ['label' => 'Hero (top of the page)', 'fields' => [
                    'hero_title' => $t('Headline', 'Find a place that feels like home.'),
                    'hero_text' => $area('Intro text', 'Apartments, houses, hostels, short stays and commercial spaces across Accra, verified and ready to view.'),
                    'hero_image_path' => $img('Background photo', 'images/hero-villa.webp', 'Wide landscape photo, at least 1600 px across.'),
                    'home_search_placeholder' => $t('Search box hint', 'Area, estate or property name'),
                    'home_search_button' => $t('Search button', 'Search'),
                    'stat_listings_label' => $t('Listings figure label', 'Live listings'),
                    'stat_clients' => $t('Happy clients figure', '1,200+', 'Leave blank to hide.'),
                    'stat_clients_label' => $t('Happy clients label', 'Happy clients'),
                    'stat_years' => $t('Years figure', '8', 'Leave blank to hide.'),
                    'stat_years_label' => $t('Years label', 'Years in Accra'),
                ]],
                'categories' => ['label' => 'Browse by type', 'fields' => [
                    'home_categories_show' => $show(),
                    'home_categories_eyebrow' => $t('Small heading', 'Browse by type'),
                    'home_categories_title' => $t('Heading', 'Every kind of space'),
                    'home_categories_button' => $t('Button', 'All properties'),
                ]],
                'featured' => ['label' => 'Featured properties', 'fields' => [
                    'home_featured_show' => $show(),
                    'home_featured_eyebrow' => $t('Small heading', 'Hand-picked'),
                    'home_featured_title' => $t('Heading', 'Featured properties'),
                ]],
                'why' => ['label' => 'Why choose us', 'fields' => [
                    'home_why_show' => $show(),
                    'why_image_path' => $img('Background photo', 'images/why-estate.webp'),
                    'home_why_eyebrow' => $t('Small heading', 'Why {business}'),
                    'home_why_title' => $t('Heading', 'Property search, without the runaround.'),
                    'home_why_text' => $area('Text', 'We do the legwork before you ever book a viewing, so what you see online is what you get at the door.'),
                    'home_why_button' => $t('Button', 'How we work', 'Links to the About page. Leave blank to hide.'),
                    'home_why_1_title' => $t('Point 1 heading', 'Verified in person'),
                    'home_why_1_text' => $area('Point 1 text', 'Every home is visited, photographed and checked by our team before it is listed.'),
                    'home_why_2_title' => $t('Point 2 heading', 'Prices up front'),
                    'home_why_2_text' => $area('Point 2 text', 'Rent, deposits and service charges are shown clearly. No surprise agent fees.'),
                    'home_why_3_title' => $t('Point 3 heading', 'One agent, start to finish'),
                    'home_why_3_text' => $area('Point 3 text', 'The same person handles your viewings, paperwork and move-in.'),
                    'home_why_4_title' => $t('Point 4 heading', 'Free viewings that suit you'),
                    'home_why_4_text' => $area('Point 4 text', 'Book a time online at no cost. We confirm by phone or WhatsApp within hours.'),
                ]],
                'latest' => ['label' => 'Newest listings', 'fields' => [
                    'home_latest_show' => $show(),
                    'home_latest_eyebrow' => $t('Small heading', 'Just listed'),
                    'home_latest_title' => $t('Heading', 'New this week'),
                ]],
                'areas' => ['label' => 'Neighbourhoods', 'fields' => [
                    'home_areas_show' => $show(),
                    'home_areas_eyebrow' => $t('Small heading', 'Neighbourhoods'),
                    'home_areas_title' => $t('Heading', 'Where people are moving'),
                ]],
                'owners' => ['label' => 'Owners banner', 'fields' => [
                    'home_owners_show' => $show(),
                    'home_owners_eyebrow' => $t('Small heading', 'For landlords and owners'),
                    'home_owners_title' => $t('Heading', 'Have a property to rent, sell or lease?'),
                    'home_owners_text' => $area('Text', 'Send us the details and a few photos. We will visit, photograph it professionally and put it in front of qualified tenants and buyers.'),
                    'home_owners_button' => $t('Main button', 'List your property'),
                    'home_owners_button2' => $t('Second button', 'Talk to us', 'Links to the Contact page. Leave blank to hide.'),
                ]],
                'reviews' => ['label' => 'Client reviews', 'fields' => [
                    'home_reviews_show' => $show(),
                    'home_reviews_eyebrow' => $t('Small heading', 'Client stories'),
                    'home_reviews_title' => $t('Heading', 'Trusted by tenants, buyers and owners'),
                    'home_reviews_button' => $t('"Leave a review" link', 'Leave a review', 'Leave blank to hide.'),
                ]],
            ]],

            'about' => ['label' => 'About page', 'sections' => [
                'intro' => ['label' => 'Introduction', 'fields' => [
                    'about_eyebrow' => $t('Small heading', 'About {business}'),
                    'about_title' => $t('Heading', 'A calmer way to rent, buy and stay.'),
                    'about' => $area('Story', "We help people find homes, short stays and workspaces across Ghana. Every listing is visited and checked by our team before it goes live, prices are shown up front, and one agent walks you through the whole process, from the first viewing to the keys.\n\nWhether you are relocating, investing, looking for a student hostel or booking a few nights in a serviced apartment, we keep things simple, honest and quick.", 'Leave a blank line between paragraphs.'),
                    'about_image_path' => $img('Photo', 'images/hero-villa.webp', 'Shown tall (portrait) beside the story.'),
                    'about_badge_text' => $t('Gold badge text', 'years helping people move', 'Shown under the years figure.'),
                ]],
                'stats' => ['label' => 'Figures', 'fields' => [
                    'about_stats_show' => $show(),
                    'about_stat_listings' => $t('Listings label', 'Live listings'),
                    'about_stat_areas' => $t('Neighbourhoods label', 'Neighbourhoods'),
                    'about_stat_clients' => $t('Clients label', 'Happy clients'),
                    'about_stat_years' => $t('Years label', 'Years in business'),
                ]],
                'services' => ['label' => 'Services', 'fields' => [
                    'about_services_show' => $show(),
                    'about_services_eyebrow' => $t('Small heading', 'What we do'),
                    'about_services_title' => $t('Heading', 'Services'),
                    'about_service_1_title' => $t('Service 1 (rent) heading', 'Lettings'),
                    'about_service_1_text' => $area('Service 1 text', 'Apartments and houses for long-term rent, with tenancy agreements handled for you.'),
                    'about_service_2_title' => $t('Service 2 (sale) heading', 'Sales'),
                    'about_service_2_text' => $area('Service 2 text', 'Homes and land for sale, including off-plan developments from trusted builders.'),
                    'about_service_3_title' => $t('Service 3 (short stay) heading', 'Short stays'),
                    'about_service_3_text' => $area('Service 3 text', 'Serviced apartments, guest houses and Airbnbs for a few nights or a few months.'),
                    'about_service_4_title' => $t('Service 4 (lease) heading', 'Commercial'),
                    'about_service_4_text' => $area('Service 4 text', 'Offices, shops and warehouses on lease, sized for teams of every kind.'),
                    'about_services_link' => $t('Card link text', 'See listings'),
                ]],
                'reviews' => ['label' => 'Client reviews', 'fields' => [
                    'about_reviews_show' => $show(),
                ]],
            ]],

            'contact' => ['label' => 'Contact page', 'sections' => [
                'intro' => ['label' => 'Introduction', 'fields' => [
                    'contact_eyebrow' => $t('Small heading', 'Contact'),
                    'contact_title' => $t('Heading', "Let's find your next place together."),
                    'contact_text' => $area('Text', 'Tell us what you are looking for, your budget and when you want to move. An agent will call you back, usually the same day.'),
                ]],
                'form' => ['label' => 'Form', 'fields' => [
                    'contact_message_label' => $t('Message box label', 'What are you looking for?'),
                    'contact_message_hint' => $t('Message box example', 'e.g. 2-bedroom furnished apartment in East Legon, up to GH₵ 8,000 a month, from January'),
                    'contact_button' => $t('Send button', 'Send message'),
                    'contact_success_title' => $t('Thank-you heading', 'Message sent'),
                    'contact_success' => $area('Thank-you message', 'Thanks {name}! We will be in touch shortly.', '{name} is replaced with the visitor’s first name.'),
                ]],
            ]],

            'submit' => ['label' => 'List your property', 'sections' => [
                'intro' => ['label' => 'Introduction', 'fields' => [
                    'submit_eyebrow' => $t('Small heading', 'For owners and landlords'),
                    'submit_title' => $t('Heading', 'List your property with us.'),
                    'submit_text' => $area('Text', 'Share the basics and a few phone photos or a video. We will call to arrange a visit, take professional photos and publish your listing.'),
                    'submit_step_1_title' => $t('Step 1 heading', 'Send the details'),
                    'submit_step_1_text' => $t('Step 1 text', 'Takes about two minutes. Rough figures are fine.'),
                    'submit_step_2_title' => $t('Step 2 heading', 'We visit and verify'),
                    'submit_step_2_text' => $t('Step 2 text', 'An agent checks the property and agrees the price and terms with you.'),
                    'submit_step_3_title' => $t('Step 3 heading', 'Go live'),
                    'submit_step_3_text' => $t('Step 3 text', 'Your listing goes up with professional photos and we start booking viewings.'),
                ]],
                'form' => ['label' => 'Form', 'fields' => [
                    'submit_button' => $t('Send button', 'Send for review'),
                    'submit_success_title' => $t('Thank-you heading', 'Thank you!'),
                    'submit_success' => $area('Thank-you message', 'Our team will review your property and call you within one working day.'),
                ]],
            ]],

            'property' => ['label' => 'Property pages', 'sections' => [
                'headings' => ['label' => 'Section headings', 'fields' => [
                    'property_about_title' => $t('Description', 'About this home'),
                    'property_amenities_title' => $t('Amenities', 'Amenities'),
                    'property_video_title' => $t('Video', 'Video tour'),
                    'property_location_title' => $t('Location', 'Location'),
                    'property_payment_title' => $t('Payment calculator', 'Payment estimate'),
                    'property_similar_eyebrow' => $t('Similar listings: small heading', 'Keep looking'),
                    'property_similar_title' => $t('Similar listings: heading', 'Similar properties'),
                ]],
                'map' => ['label' => 'Locked map', 'fields' => [
                    'property_map_title' => $t('Heading', 'Book a viewing to see the location'),
                    'property_map_text' => $area('Text', 'Your agent shares the exact address and directions once your free viewing is booked.', 'Shown after “This home is in [area]”.'),
                    'property_map_button' => $t('Button', 'Book a free viewing'),
                ]],
                'booking' => ['label' => 'Booking form', 'fields' => [
                    'property_tab_viewing' => $t('Viewing tab', 'Book a viewing'),
                    'property_tab_question' => $t('Question tab', 'Ask a question'),
                    'property_viewing_button' => $t('Viewing button', 'Request free viewing'),
                    'property_question_button' => $t('Question button', 'Send message'),
                    'property_viewing_note' => $t('Note under the viewing button', 'Viewings are free. No booking fee, no obligation.'),
                    'property_mobile_button' => $t('Phone screen button', 'Book free viewing'),
                    'property_success_title' => $t('Thank-you heading', 'Request received'),
                    'property_viewing_success' => $area('Viewing thank-you', 'Thanks {name}! Your viewing request is in. We will call you to confirm the time.', '{name} is replaced with the visitor’s first name.'),
                    'property_question_success' => $area('Question thank-you', 'Thanks {name}! We received your message and will get back to you shortly.'),
                ]],
            ]],

            'reviews' => ['label' => 'Review page', 'sections' => [
                'page' => ['label' => 'Leave a review', 'fields' => [
                    'review_eyebrow' => $t('Small heading', 'Your feedback'),
                    'review_title' => $t('Heading', 'How did we do?'),
                    'review_text' => $area('Text', 'Tell us about renting, buying or staying with {business}. Reviews appear on the site once our team has checked them.'),
                    'review_button' => $t('Send button', 'Send review'),
                    'review_success_title' => $t('Thank-you heading', 'Thank you!'),
                    'review_success' => $area('Thank-you message', 'Thanks {name}! Your review will appear once our team has checked it.'),
                ]],
            ]],

            'other' => ['label' => 'Other pages', 'sections' => [
                'saved' => ['label' => 'Saved properties', 'fields' => [
                    'saved_eyebrow' => $t('Small heading', 'Your shortlist'),
                    'saved_title' => $t('Heading', 'Saved properties'),
                    'saved_text' => $t('Text', 'Saved on this device. Tap the heart on any listing to add or remove it.'),
                    'saved_empty_title' => $t('Empty heading', 'Nothing saved yet'),
                    'saved_empty_text' => $area('Empty text', 'Browse listings and tap the heart to build a shortlist you can compare and share with family.'),
                ]],
                'journal' => ['label' => 'Journal', 'fields' => [
                    'journal_eyebrow' => $t('Small heading', 'Journal'),
                    'journal_title' => $t('Heading', 'Guides, tips and market news'),
                    'journal_empty' => $t('No articles text', 'No articles yet. Check back soon.'),
                ]],
            ]],

            'layout' => ['label' => 'Header & footer', 'sections' => [
                'nav' => ['label' => 'Menu', 'fields' => [
                    'nav_rent' => $t('Rent', 'Rent'),
                    'nav_buy' => $t('Buy', 'Buy'),
                    'nav_short' => $t('Short stays', 'Short stays'),
                    'nav_all' => $t('All properties', 'All properties'),
                    'nav_about' => $t('About', 'About us'),
                    'nav_contact' => $t('Contact', 'Contact us'),
                    'nav_cta' => $t('Gold button', 'List your property'),
                    'nav_rent_show' => $show('Show “Rent”'),
                    'nav_buy_show' => $show('Show “Buy”'),
                    'nav_short_show' => $show('Show “Short stays”'),
                    'nav_all_show' => $show('Show “All properties”'),
                    'nav_about_show' => $show('Show “About”'),
                    'nav_contact_show' => $show('Show “Contact”'),
                ]],
                'footer' => ['label' => 'Footer', 'fields' => [
                    'footer_text' => $area('Text under the logo', '{tagline}. Verified listings, honest prices and one agent from first viewing to keys.'),
                    'footer_explore' => $t('Column 1 heading', 'Explore'),
                    'footer_company' => $t('Column 2 heading', 'Company'),
                    'footer_contact' => $t('Column 3 heading', 'Get in touch'),
                    'footer_review_link' => $t('“Leave a review” link', 'Leave a review', 'Leave blank to hide.'),
                    'footer_rights' => $t('After the copyright', 'All rights reserved.'),
                    'footer_note' => $t('Small print', 'Prices and availability can change. Please confirm with our team before paying anything.'),
                ]],
            ]],
        ];
    }

    /** @return array<string, array> key => field definition */
    public static function fields(): array
    {
        $all = [];
        foreach (self::pages() as $page) {
            foreach ($page['sections'] as $section) {
                $all += $section['fields'];
            }
        }

        return $all;
    }

    /** @return array<string, string> key => default value (images default to '' so a saved upload is distinguishable) */
    public static function defaults(): array
    {
        return array_map(fn ($f) => $f['type'] === 'image' ? '' : $f['default'], self::fields());
    }

    /** Fields of one admin tab. */
    public static function pageFields(string $page): array
    {
        $fields = [];
        foreach (self::pages()[$page]['sections'] ?? [] as $section) {
            $fields += $section['fields'];
        }

        return $fields;
    }
}
