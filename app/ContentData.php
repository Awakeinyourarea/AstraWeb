<?php
declare(strict_types=1);

namespace App;

/**
 * Editable portal content for Home features, News, Wiki guides, Downloads, and Donate tiers.
 */
final class ContentData
{
    public static function features(): array
    {
        return [
            [
                'badge' => 'Rates & Progression',
                'title' => 'Balanced 10x / 10x Mid-Rate',
                'desc'  => 'Smooth leveling curve designed for solo adventurers and party grinds, paired with boosted 5x Equipment and 10x Card drops.',
                'tone'  => 'amber',
                'icon'  => 'spark',
            ],
            [
                'badge' => 'Episode 19+',
                'title' => 'Modern Renewal Mechanics',
                'desc'  => '4th Job classes, Trait Statuses (POW/STA/WIS/SPL/CON/CRT), Enchant Grade, and latest official instances ready to explore.',
                'tone'  => 'cyan',
                'icon'  => 'compass',
            ],
            [
                'badge' => 'Fair Play',
                'title' => 'Protected & Transparent Economy',
                'desc'  => 'Live WhoSell market indexing, full server logging, and clean anti-bot safeguards to keep Zeny and MVP hunting rewarding.',
                'tone'  => 'rose',
                'icon'  => 'shield',
            ],
            [
                'badge' => 'Quality of Life',
                'title' => 'Player-First Control Panel',
                'desc'  => 'Instant web registration, live server status checks, character position reset, and searchable Item/Monster databases.',
                'tone'  => 'orange',
                'icon'  => 'crown',
            ],
        ];
    }

    public static function news(): array
    {
        return [
            [
                'tag'   => 'Launch',
                'date'  => '2026-11-28',
                'title' => SITE_NAME . ' Web Portal & Control Panel Live',
                'body'  => 'Register your game account, track live server population, browse active player vendings in WhoSell, and inspect full Renewal Item & Monster stats.',
            ],
            [
                'tag'   => 'Rates',
                'date'  => '2026-11-25',
                'title' => 'Server Rates & Renewal Configuration',
                'body'  => 'Base & Job EXP are set to 10x with 5x Equipment drops and 10x Card drops (1x MVP Card). Join our Discord for WoE & Event schedules.',
            ],
            [
                'tag'   => 'Event',
                'date'  => '2026-11-20',
                'title' => 'Weekend Hunting & MVP Challenge',
                'body'  => 'Form a party and climb the Level, Guild, and MVP leaderboards. Top guilds earn War of Emperium castle recognition directly on the portal.',
            ],
        ];
    }

    public static function wikiSections(): array
    {
        return [
            [
                'id'      => 'server-info',
                'title'   => 'Server Information & Rates',
                'summary' => 'Core configuration synced with ' . SITE_NAME . ' emulator settings.',
                'rows'    => [
                    ['Episode / Mode', SITE_EPISODE],
                    ['Base & Job EXP', RATES_DETAIL['base_exp'] . ' / ' . RATES_DETAIL['job_exp']],
                    ['Common / Heal / Usable Drops', RATES_DETAIL['common']],
                    ['Equipment Drops', RATES_DETAIL['equip']],
                    ['Normal Card Drops', RATES_DETAIL['card']],
                    ['MVP Card Drops', RATES_DETAIL['mvp_card']],
                    ['Server Timezone', 'UTC+7 (Asia/Bangkok)'],
                ],
            ],
            [
                'id'      => 'commands',
                'title'   => 'Useful Player Commands',
                'summary' => 'Essential @commands available in-game (subject to your group permissions).',
                'rows'    => [
                    ['@rates', 'Display current server EXP and drop rates'],
                    ['@commands', 'List all player commands available to your account'],
                    ['@mi / @mobinfo <name|id>', 'Check monster HP, element, race, and drop rates'],
                    ['@ii / @iteminfo <name|id>', 'Check item stats, weight, and NPC buy/sell value'],
                    ['@whodrops <item>', 'Find which monsters drop a specific item'],
                    ['@autotrade / @at', 'Keep your vending shop open while offline'],
                    ['@refresh', 'Synchronize character position and visual state with client'],
                ],
            ],
            [
                'id'      => 'rules',
                'title'   => 'Community & Fair-Play Rules',
                'summary' => 'Keep ' . SITE_NAME . ' welcoming and competitive for everyone.',
                'rows'    => [
                    ['No Bots / Macros', 'Automated farming, pixel bots, or unattended macro scripts are strictly prohibited.'],
                    ['No RMT', 'Real-money trading of accounts, Zeny, or items outside official channels results in permanent bans.'],
                    ['Respectful Conduct', 'No harassment, hate speech, or impersonating Staff/GM members.'],
                    ['Bug Reporting', 'Exploiting bugs instead of reporting them to Staff will lead to item/account rollback.'],
                ],
            ],
        ];
    }

    public static function donatePackages(): array
    {
        return [
            [
                'name'    => 'Starter Explorer Pack',
                'price'   => '$5 / 150 THB',
                'credits' => '500 Cash Points',
                'perks'   => ['500 Cash Points', '3-Day Field Manual Bundle', 'Cosmetic Costume Box'],
                'popular' => false,
            ],
            [
                'name'    => 'Adventurer Support Pack',
                'price'   => '$15 / 450 THB',
                'credits' => '1,650 Cash Points',
                'perks'   => ['1,500 + 150 Bonus Cash Points', '7-Day VIP Access', 'HE Bubble Gum x5', 'Exclusive Aura Ticket'],
                'popular' => true,
            ],
            [
                'name'    => 'Guild Patron Pack',
                'price'   => '$30 / 900 THB',
                'credits' => '3,500 Cash Points',
                'perks'   => ['3,000 + 500 Bonus Cash Points', '30-Day VIP Access', 'Guild Supply Box', 'Priority Support Role'],
                'popular' => false,
            ],
        ];
    }
}
