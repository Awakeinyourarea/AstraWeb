<?php
declare(strict_types=1);

namespace App;

/**
 * Indexes and queries rAthena YAML item_db and mob_db files via a fast JSON cache.
 */
final class ItemMobDb
{
    private const ITEM_CACHE = STORAGE_PATH . '/cache/items.json';
    private const MOB_CACHE  = STORAGE_PATH . '/cache/mobs.json';

    public static function ensureCache(): void
    {
        if (!is_file(self::ITEM_CACHE)) {
            self::buildItemCache();
        }
        if (!is_file(self::MOB_CACHE)) {
            self::buildMobCache();
        }
    }

    public static function buildItemCache(): int
    {
        $files = [
            RATHENA_DB_PATH . '/item_db_usable.yml',
            RATHENA_DB_PATH . '/item_db_equip.yml',
            RATHENA_DB_PATH . '/item_db_etc.yml',
        ];
        $importFile = dirname(RATHENA_DB_PATH) . '/import/item_db.yml';
        if (is_file($importFile)) {
            $files[] = $importFile;
        }

        $items = [];
        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }
            $handle = fopen($file, 'rb');
            if (!$handle) {
                continue;
            }

            $current = null;
            $inBody = false;
            $inSubBlock = false;
            while (($line = fgets($handle)) !== false) {
                $trimmed = ltrim($line);
                if ($trimmed === '' || $trimmed[0] === '#') {
                    continue;
                }
                if (str_starts_with($line, 'Body:')) {
                    $inBody = true;
                    continue;
                }
                if (!$inBody) {
                    continue;
                }

                if (preg_match('/^\s{2}-\s+Id:\s*(\d+)/', $line, $m)) {
                    if ($current !== null) {
                        $items[$current['id']] = $current;
                    }
                    $current = [
                        'id'     => (int)$m[1],
                        'aegis'  => '',
                        'name'   => '',
                        'type'   => 'Etc',
                        'sub'    => '',
                        'buy'    => 0,
                        'sell'   => 0,
                        'weight' => 0,
                        'atk'    => 0,
                        'matk'   => 0,
                        'def'    => 0,
                        'slots'  => 0,
                        'elv'    => 0,
                    ];
                    $inSubBlock = false;
                    continue;
                }

                if ($current === null) {
                    continue;
                }

                // Skip deeply nested or multi-line script blocks
                if (preg_match('/^\s{4}([A-Za-z0-9_]+):\s*(.*)$/', $line, $m)) {
                    $key = $m[1];
                    $val = trim($m[2]);
                    $inSubBlock = ($val === '' || $val === '|');
                    if ($inSubBlock) {
                        continue;
                    }
                    $val = trim($val, "\"'");
                    switch ($key) {
                        case 'AegisName': $current['aegis'] = $val; break;
                        case 'Name':      $current['name'] = $val; break;
                        case 'Type':      $current['type'] = $val; break;
                        case 'SubType':   $current['sub'] = $val; break;
                        case 'Buy':       $current['buy'] = (int)$val; break;
                        case 'Sell':      $current['sell'] = (int)$val; break;
                        case 'Weight':    $current['weight'] = (int)$val; break;
                        case 'Attack':    $current['atk'] = (int)$val; break;
                        case 'MagicAttack': $current['matk'] = (int)$val; break;
                        case 'Defense':   $current['def'] = (int)$val; break;
                        case 'Slots':     $current['slots'] = (int)$val; break;
                        case 'EquipLevelMin': $current['elv'] = (int)$val; break;
                    }
                }
            }
            if ($current !== null) {
                $items[$current['id']] = $current;
            }
            fclose($handle);
        }

        ksort($items);
        file_put_contents(self::ITEM_CACHE, json_encode($items, JSON_UNESCAPED_UNICODE));
        return count($items);
    }

    public static function buildMobCache(): int
    {
        $files = [RATHENA_DB_PATH . '/mob_db.yml'];
        $importFile = dirname(RATHENA_DB_PATH) . '/import/mob_db.yml';
        if (is_file($importFile)) {
            $files[] = $importFile;
        }

        $mobs = [];
        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }
            $handle = fopen($file, 'rb');
            if (!$handle) {
                continue;
            }

            $current = null;
            $inBody = false;
            $inDrops = false;
            $dropItem = null;
            while (($line = fgets($handle)) !== false) {
                $trimmed = ltrim($line);
                if ($trimmed === '' || $trimmed[0] === '#') {
                    continue;
                }
                if (str_starts_with($line, 'Body:')) {
                    $inBody = true;
                    continue;
                }
                if (!$inBody) {
                    continue;
                }

                if (preg_match('/^\s{2}-\s+Id:\s*(\d+)/', $line, $m)) {
                    if ($current !== null) {
                        if ($dropItem !== null) {
                            $current['drops'][] = $dropItem;
                            $dropItem = null;
                        }
                        $mobs[$current['id']] = $current;
                    }
                    $current = [
                        'id'      => (int)$m[1],
                        'aegis'   => '',
                        'name'    => '',
                        'lv'      => 1,
                        'hp'      => 1,
                        'bexp'    => 0,
                        'jexp'    => 0,
                        'mvpexp'  => 0,
                        'size'    => 'Small',
                        'race'    => 'Formless',
                        'elem'    => 'Neutral',
                        'elemlv'  => 1,
                        'mvp'     => false,
                        'drops'   => [],
                    ];
                    $inDrops = false;
                    continue;
                }

                if ($current === null) {
                    continue;
                }

                if (preg_match('/^\s{4}([A-Za-z0-9_]+):\s*(.*)$/', $line, $m)) {
                    if ($dropItem !== null) {
                        $current['drops'][] = $dropItem;
                        $dropItem = null;
                    }
                    $key = $m[1];
                    $val = trim($m[2], " \t\r\n\"'");
                    $inDrops = ($key === 'Drops' || $key === 'MvpDrops');
                    if ($key === 'MvpDrops') {
                        $current['mvp'] = true;
                    }
                    if ($val === '') {
                        continue;
                    }
                    switch ($key) {
                        case 'AegisName':    $current['aegis'] = $val; break;
                        case 'Name':         $current['name'] = $val; break;
                        case 'Level':        $current['lv'] = (int)$val; break;
                        case 'Hp':           $current['hp'] = (int)$val; break;
                        case 'BaseExp':      $current['bexp'] = (int)$val; break;
                        case 'JobExp':       $current['jexp'] = (int)$val; break;
                        case 'MvpExp':
                            $current['mvpexp'] = (int)$val;
                            if ((int)$val > 0) {
                                $current['mvp'] = true;
                            }
                            break;
                        case 'Size':         $current['size'] = $val; break;
                        case 'Race':         $current['race'] = $val; break;
                        case 'Element':      $current['elem'] = $val; break;
                        case 'ElementLevel': $current['elemlv'] = (int)$val; break;
                    }
                    continue;
                }

                if ($inDrops) {
                    if (preg_match('/^\s{6}-\s+Item:\s*(.+)$/', $line, $m)) {
                        if ($dropItem !== null) {
                            $current['drops'][] = $dropItem;
                        }
                        $dropItem = ['item' => trim($m[1], " \t\r\n\"'"), 'rate' => 0];
                    } elseif ($dropItem !== null && preg_match('/^\s{8}Rate:\s*(\d+)/', $line, $m)) {
                        $dropItem['rate'] = (int)$m[1];
                    }
                }
            }
            if ($current !== null) {
                if ($dropItem !== null) {
                    $current['drops'][] = $dropItem;
                }
                $mobs[$current['id']] = $current;
            }
            fclose($handle);
        }

        ksort($mobs);
        file_put_contents(self::MOB_CACHE, json_encode($mobs, JSON_UNESCAPED_UNICODE));
        return count($mobs);
    }

    public static function allItems(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        self::ensureCache();
        $data = json_decode((string)file_get_contents(self::ITEM_CACHE), true);
        $cache = is_array($data) ? $data : [];
        return $cache;
    }

    public static function allMobs(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        self::ensureCache();
        $data = json_decode((string)file_get_contents(self::MOB_CACHE), true);
        $cache = is_array($data) ? $data : [];
        return $cache;
    }

    public static function findItem(int $id): ?array
    {
        $items = self::allItems();
        return $items[$id] ?? null;
    }

    public static function findMob(int $id): ?array
    {
        $mobs = self::allMobs();
        return $mobs[$id] ?? null;
    }

    public static function searchItems(string $q = '', string $type = '', int $page = 1, int $perPage = 30): array
    {
        $items = self::allItems();
        $qLower = mb_strtolower(trim($q));
        $filtered = [];

        foreach ($items as $item) {
            if ($type !== '' && strcasecmp($item['type'], $type) !== 0) {
                continue;
            }
            if ($qLower !== '') {
                $idMatch = (string)$item['id'] === $qLower;
                $nameMatch = str_contains(mb_strtolower($item['name']), $qLower);
                $aegisMatch = str_contains(mb_strtolower($item['aegis']), $qLower);
                if (!$idMatch && !$nameMatch && !$aegisMatch) {
                    continue;
                }
            }
            $filtered[] = $item;
        }

        $total = count($filtered);
        $offset = max(0, ($page - 1) * $perPage);
        return [
            'rows'  => array_slice($filtered, $offset, $perPage),
            'total' => $total,
            'page'  => $page,
            'pages' => max(1, (int)ceil($total / $perPage)),
        ];
    }

    public static function searchMobs(string $q = '', string $race = '', string $onlyMvp = '', int $page = 1, int $perPage = 30): array
    {
        $mobs = self::allMobs();
        $qLower = mb_strtolower(trim($q));
        $filtered = [];

        foreach ($mobs as $mob) {
            if ($onlyMvp === '1' && empty($mob['mvp'])) {
                continue;
            }
            if ($race !== '' && strcasecmp($mob['race'], $race) !== 0) {
                continue;
            }
            if ($qLower !== '') {
                $idMatch = (string)$mob['id'] === $qLower;
                $nameMatch = str_contains(mb_strtolower($mob['name']), $qLower);
                $aegisMatch = str_contains(mb_strtolower($mob['aegis']), $qLower);
                if (!$idMatch && !$nameMatch && !$aegisMatch) {
                    continue;
                }
            }
            $filtered[] = $mob;
        }

        $total = count($filtered);
        $offset = max(0, ($page - 1) * $perPage);
        return [
            'rows'  => array_slice($filtered, $offset, $perPage),
            'total' => $total,
            'page'  => $page,
            'pages' => max(1, (int)ceil($total / $perPage)),
        ];
    }
}
