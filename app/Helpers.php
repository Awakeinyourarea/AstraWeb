<?php
declare(strict_types=1);

namespace App;

/**
 * Shared utility helpers for escaping, CSRF, URLs, job names, and port checks.
 */
final class Helpers
{
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function baseUrl(string $path = ''): string
    {
        $base = rtrim(BASE_URL, '/');
        if ($path === '' || $path === '/') {
            return $base === '' ? '/' : $base . '/';
        }
        return $base . '/' . ltrim($path, '/');
    }

    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(24));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function verifyCsrf(?string $token): bool
    {
        if (!is_string($token) || empty($_SESSION['_csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['_csrf_token'], $token);
    }

    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
    }

    public static function getFlash(): ?array
    {
        if (empty($_SESSION['_flash'])) {
            return null;
        }
        $flash = $_SESSION['_flash'];
        unset($_SESSION['_flash']);
        return $flash;
    }

    public static function json(array $payload, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Check Login/Char/Map TCP ports with a 15-second file cache to prevent lag.
     */
    public static function serverPortsStatus(): array
    {
        $cacheFile = STORAGE_PATH . '/cache/ports_status.json';
        if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < 15) {
            $cached = json_decode((string)file_get_contents($cacheFile), true);
            if (is_array($cached)) {
                return $cached;
            }
        }

        $ports = [
            'login' => defined('PORT_LOGIN') ? PORT_LOGIN : 6900,
            'char'  => defined('PORT_CHAR') ? PORT_CHAR : 6121,
            'map'   => defined('PORT_MAP') ? PORT_MAP : 5121,
        ];

        $result = [];
        foreach ($ports as $key => $port) {
            $errno = 0;
            $errstr = '';
            $fp = @fsockopen(SERVER_HOST, $port, $errno, $errstr, 0.25);
            if ($fp) {
                fclose($fp);
                $result[$key] = true;
            } else {
                $result[$key] = false;
            }
        }
        $result['checked_at'] = time();

        @file_put_contents($cacheFile, json_encode($result));
        return $result;
    }

    public static function jobName(int $classId): string
    {
        static $jobs = [
            0 => 'Novice', 1 => 'Swordman', 2 => 'Mage', 3 => 'Archer', 4 => 'Acolyte',
            5 => 'Merchant', 6 => 'Thief', 7 => 'Knight', 8 => 'Priest', 9 => 'Wizard',
            10 => 'Blacksmith', 11 => 'Hunter', 12 => 'Assassin', 14 => 'Crusader',
            15 => 'Monk', 16 => 'Sage', 17 => 'Rogue', 18 => 'Alchemist', 19 => 'Bard',
            20 => 'Dancer', 23 => 'Super Novice', 24 => 'Gunslinger', 25 => 'Ninja',
            4001 => 'Novice High', 4002 => 'Swordman High', 4003 => 'Mage High',
            4004 => 'Archer High', 4005 => 'Acolyte High', 4006 => 'Merchant High',
            4007 => 'Thief High', 4008 => 'Lord Knight', 4009 => 'High Priest',
            4010 => 'High Wizard', 4011 => 'Whitesmith', 4012 => 'Sniper',
            4013 => 'Assassin Cross', 4015 => 'Paladin', 4016 => 'Champion',
            4017 => 'Professor', 4018 => 'Stalker', 4019 => 'Creator',
            4020 => 'Clown', 4021 => 'Gypsy',
            4023 => 'Baby Novice', 4045 => 'Super Baby',
            4046 => 'Taekwon', 4047 => 'Star Gladiator', 4049 => 'Soul Linker',
            4054 => 'Rune Knight', 4055 => 'Warlock', 4056 => 'Ranger',
            4057 => 'Arch Bishop', 4058 => 'Mechanic', 4059 => 'Guillotine Cross',
            4060 => 'Rune Knight T', 4061 => 'Warlock T', 4062 => 'Ranger T',
            4063 => 'Arch Bishop T', 4064 => 'Mechanic T', 4065 => 'Guillotine Cross T',
            4066 => 'Royal Guard', 4067 => 'Sorcerer', 4068 => 'Minstrel',
            4069 => 'Wanderer', 4070 => 'Sura', 4071 => 'Genetic', 4072 => 'Shadow Chaser',
            4073 => 'Royal Guard T', 4074 => 'Sorcerer T', 4075 => 'Minstrel T',
            4076 => 'Wanderer T', 4077 => 'Sura T', 4078 => 'Genetic T', 4079 => 'Shadow Chaser T',
            4190 => 'Ex. Super Novice', 4211 => 'Kagerou', 4212 => 'Oboro',
            4215 => 'Rebellion', 4218 => 'Summoner',
            4239 => 'Star Emperor', 4240 => 'Soul Reaper',
            4252 => 'Dragon Knight', 4253 => 'Meister', 4254 => 'Shadow Cross',
            4255 => 'Arch Mage', 4256 => 'Cardinal', 4257 => 'Windhawk',
            4258 => 'Imperial Guard', 4259 => 'Biolo', 4260 => 'Abyss Chaser',
            4261 => 'Elemental Master', 4262 => 'Inquisitor', 4263 => 'Troubadour',
            4264 => 'Trouvere', 4302 => 'Sky Emperor', 4303 => 'Soul Ascetic',
            4304 => 'Shinkiro', 4305 => 'Shiranui', 4306 => 'Night Watch',
            4307 => 'Hyper Novice', 4308 => 'Spirit Handler',
        ];
        return $jobs[$classId] ?? "Job #{$classId}";
    }

    public static function formatZeny(int|string $amount): string
    {
        return number_format((int)$amount) . ' z';
    }

    public static function castleName(int $castleId): string
    {
        static $castles = [
            0 => 'Aldebaran - Neuschwanstein', 1 => 'Aldebaran - Hohenschwangau',
            2 => 'Aldebaran - Nuenberg', 3 => 'Aldebaran - Wuerzburg', 4 => 'Aldebaran - Rothenburg',
            5 => 'Geffen - Repherion', 6 => 'Geffen - Eeyolbriggar', 7 => 'Geffen - Yesnelph',
            8 => 'Geffen - Bergel', 9 => 'Geffen - Mersetzdeitz',
            10 => 'Payon - Bright Arbor', 11 => 'Payon - Scarlet Palace',
            12 => 'Payon - Holy Shadow', 13 => 'Payon - Sacred Altar', 14 => 'Payon - Bamboo Grove Hill',
            15 => 'Prontera - Kriemhild', 16 => 'Prontera - Swanhild', 17 => 'Prontera - Fadhgridh',
            18 => 'Prontera - Skoegul', 19 => 'Prontera - Gondul',
            24 => 'Schwaltzvalt - Himinn', 25 => 'Schwaltzvalt - Andlangr',
            26 => 'Schwaltzvalt - Viblainn', 27 => 'Schwaltzvalt - Hljod', 28 => 'Schwaltzvalt - Skidbladnir',
            29 => 'Arunafeltz - Mardol', 30 => 'Arunafeltz - Cyr', 31 => 'Arunafeltz - Horn',
            32 => 'Arunafeltz - Gefn', 33 => 'Arunafeltz - Banadis',
        ];
        return $castles[$castleId] ?? "Castle #{$castleId}";
    }
}
