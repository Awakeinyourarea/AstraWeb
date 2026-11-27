<?php
declare(strict_types=1);

namespace App;

/**
 * Live SQL queries against rAthena (astra_main) for status, rankings, and vending.
 */
final class ServerData
{
    public static function summary(): array
    {
        $pdo = Database::connection();
        $counts = $pdo->query(
            'SELECT
                (SELECT COUNT(*) FROM `char` WHERE online = 1) AS online_players,
                (SELECT COUNT(*) FROM vendings WHERE autotrade = 1) AS autotrade_players,
                (SELECT COUNT(*) FROM login WHERE state = 0) AS total_accounts,
                (SELECT COUNT(*) FROM `char` WHERE delete_date = 0) AS total_chars,
                (SELECT COUNT(*) FROM guild) AS total_guilds,
                (SELECT COUNT(*) FROM vendings) AS active_vendings'
        )->fetch() ?: [];

        $ports = Helpers::serverPortsStatus();

        return [
            'online_players'    => (int)($counts['online_players'] ?? 0),
            'autotrade_players' => (int)($counts['autotrade_players'] ?? 0),
            'total_accounts'    => (int)($counts['total_accounts'] ?? 0),
            'total_chars'       => (int)($counts['total_chars'] ?? 0),
            'total_guilds'      => (int)($counts['total_guilds'] ?? 0),
            'active_vendings'   => (int)($counts['active_vendings'] ?? 0),
            'ports'             => $ports,
            'server_online'     => ($ports['login'] && $ports['char'] && $ports['map']),
        ];
    }

    public static function champions(): array
    {
        $pdo = Database::connection();

        $mvp = $pdo->query(
            'SELECT c.name, COUNT(m.mvp_id) AS kills
             FROM mvplog m
             INNER JOIN `char` c ON c.char_id = m.kill_char_id
             INNER JOIN login l ON l.account_id = c.account_id
             WHERE c.delete_date = 0 AND l.group_id < 10
             GROUP BY c.char_id, c.name
             ORDER BY kills DESC
             LIMIT 1'
        )->fetch();

        $woe = $pdo->query(
            'SELECT g.name, COUNT(gc.castle_id) AS castles
             FROM guild_castle gc
             INNER JOIN guild g ON g.guild_id = gc.guild_id
             GROUP BY g.guild_id, g.name
             ORDER BY castles DESC
             LIMIT 1'
        )->fetch();

        $topLevel = $pdo->query(
            'SELECT c.name, c.base_level
             FROM `char` c
             INNER JOIN login l ON l.account_id = c.account_id
             WHERE c.delete_date = 0 AND l.group_id < 10
             ORDER BY c.base_level DESC, c.job_level DESC, c.base_exp DESC
             LIMIT 1'
        )->fetch();

        return [
            'pvp' => $topLevel ? $topLevel['name'] : '—',
            'woe' => $woe ? $woe['name'] : '—',
            'mvp' => $mvp ? $mvp['name'] : '—',
        ];
    }

    public static function topPreview(): array
    {
        $pdo = Database::connection();
        $players = $pdo->query(
            'SELECT c.char_id, c.name, c.class, c.base_level, c.job_level, c.online, g.name AS guild_name
             FROM `char` c
             INNER JOIN login l ON l.account_id = c.account_id
             LEFT JOIN guild g ON g.guild_id = c.guild_id
             WHERE c.delete_date = 0 AND l.group_id < 10
             ORDER BY c.base_level DESC, c.job_level DESC, c.base_exp DESC
             LIMIT 5'
        )->fetchAll();

        $guilds = $pdo->query(
            'SELECT g.guild_id, g.name, g.master, g.guild_lv, g.average_lv, g.exp,
                    (SELECT COUNT(*) FROM guild_member gm WHERE gm.guild_id = g.guild_id) AS member_count,
                    (SELECT COUNT(*) FROM guild_castle gc WHERE gc.guild_id = g.guild_id) AS castle_count
             FROM guild g
             ORDER BY g.guild_lv DESC, g.exp DESC
             LIMIT 5'
        )->fetchAll();

        return ['players' => $players, 'guilds' => $guilds];
    }

    public static function rankings(string $type = 'level', string $search = '', int $page = 1, int $perPage = 25): array
    {
        $pdo = Database::connection();
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;
        $search = trim($search);
        $like = '%' . $search . '%';

        switch ($type) {
            case 'guild':
                $where = $search !== '' ? 'WHERE (g.name LIKE ? OR g.master LIKE ?)' : '';
                $params = $search !== '' ? [$like, $like] : [];
                $countStmt = $pdo->prepare("SELECT COUNT(*) FROM guild g $where");
                $countStmt->execute($params);
                $total = (int)$countStmt->fetchColumn();

                $sql = "SELECT g.guild_id, g.name, g.master, g.guild_lv, g.average_lv, g.max_member, g.exp,
                               (SELECT COUNT(*) FROM guild_member gm WHERE gm.guild_id = g.guild_id) AS member_count,
                               (SELECT COUNT(*) FROM guild_castle gc WHERE gc.guild_id = g.guild_id) AS castle_count
                        FROM guild g
                        $where
                        ORDER BY g.guild_lv DESC, g.exp DESC
                        LIMIT $perPage OFFSET $offset";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                return ['rows' => $stmt->fetchAll(), 'total' => $total, 'page' => $page, 'pages' => max(1, (int)ceil($total / $perPage))];

            case 'zeny':
                $where = 'WHERE c.delete_date = 0 AND l.group_id < 10';
                $params = [];
                if ($search !== '') {
                    $where .= ' AND c.name LIKE ?';
                    $params[] = $like;
                }
                $countStmt = $pdo->prepare("SELECT COUNT(*) FROM `char` c INNER JOIN login l ON l.account_id = c.account_id $where");
                $countStmt->execute($params);
                $total = (int)$countStmt->fetchColumn();

                $sql = "SELECT c.char_id, c.name, c.class, c.base_level, c.job_level, c.zeny, c.online, g.name AS guild_name
                        FROM `char` c
                        INNER JOIN login l ON l.account_id = c.account_id
                        LEFT JOIN guild g ON g.guild_id = c.guild_id
                        $where
                        ORDER BY c.zeny DESC, c.base_level DESC
                        LIMIT $perPage OFFSET $offset";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                return ['rows' => $stmt->fetchAll(), 'total' => $total, 'page' => $page, 'pages' => max(1, (int)ceil($total / $perPage))];

            case 'mvp':
                $where = 'WHERE c.delete_date = 0 AND l.group_id < 10';
                $params = [];
                if ($search !== '') {
                    $where .= ' AND c.name LIKE ?';
                    $params[] = $like;
                }
                $countStmt = $pdo->prepare(
                    "SELECT COUNT(DISTINCT m.kill_char_id)
                     FROM mvplog m
                     INNER JOIN `char` c ON c.char_id = m.kill_char_id
                     INNER JOIN login l ON l.account_id = c.account_id
                     $where"
                );
                $countStmt->execute($params);
                $total = (int)$countStmt->fetchColumn();

                $sql = "SELECT c.char_id, c.name, c.class, c.base_level, c.online, g.name AS guild_name,
                               COUNT(m.mvp_id) AS mvp_kills, MAX(m.mvp_date) AS last_mvp
                        FROM mvplog m
                        INNER JOIN `char` c ON c.char_id = m.kill_char_id
                        INNER JOIN login l ON l.account_id = c.account_id
                        LEFT JOIN guild g ON g.guild_id = c.guild_id
                        $where
                        GROUP BY c.char_id, c.name, c.class, c.base_level, c.online, g.name
                        ORDER BY mvp_kills DESC, last_mvp DESC
                        LIMIT $perPage OFFSET $offset";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                return ['rows' => $stmt->fetchAll(), 'total' => $total, 'page' => $page, 'pages' => max(1, (int)ceil($total / $perPage))];

            case 'castles':
                $sql = 'SELECT gc.castle_id, gc.guild_id, gc.economy, gc.defense, g.name AS guild_name, g.master
                        FROM guild_castle gc
                        LEFT JOIN guild g ON g.guild_id = gc.guild_id
                        ORDER BY gc.castle_id ASC';
                $rows = $pdo->query($sql)->fetchAll();
                return ['rows' => $rows, 'total' => count($rows), 'page' => 1, 'pages' => 1];

            case 'level':
            default:
                $where = 'WHERE c.delete_date = 0 AND l.group_id < 10';
                $params = [];
                if ($search !== '') {
                    $where .= ' AND c.name LIKE ?';
                    $params[] = $like;
                }
                $countStmt = $pdo->prepare("SELECT COUNT(*) FROM `char` c INNER JOIN login l ON l.account_id = c.account_id $where");
                $countStmt->execute($params);
                $total = (int)$countStmt->fetchColumn();

                $sql = "SELECT c.char_id, c.name, c.class, c.base_level, c.job_level, c.base_exp, c.online, c.last_map, g.name AS guild_name
                        FROM `char` c
                        INNER JOIN login l ON l.account_id = c.account_id
                        LEFT JOIN guild g ON g.guild_id = c.guild_id
                        $where
                        ORDER BY c.base_level DESC, c.job_level DESC, c.base_exp DESC
                        LIMIT $perPage OFFSET $offset";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                return ['rows' => $stmt->fetchAll(), 'total' => $total, 'page' => $page, 'pages' => max(1, (int)ceil($total / $perPage))];
        }
    }

    public static function whosell(string $search = '', int $page = 1, int $perPage = 30): array
    {
        $pdo = Database::connection();
        $sql = 'SELECT v.id AS vending_id, v.title, v.map, v.x, v.y, v.autotrade,
                       c.name AS vendor_name,
                       vi.amount, vi.price,
                       ci.nameid, ci.refine, ci.card0, ci.card1, ci.card2, ci.card3
                FROM vending_items vi
                INNER JOIN vendings v ON v.id = vi.vending_id
                INNER JOIN cart_inventory ci ON ci.id = vi.cartinventory_id
                INNER JOIN `char` c ON c.char_id = v.char_id
                ORDER BY vi.price ASC';
        $all = $pdo->query($sql)->fetchAll();
        $itemsDb = ItemMobDb::allItems();

        $q = mb_strtolower(trim($search));
        $enriched = [];
        foreach ($all as $row) {
            $itemId = (int)$row['nameid'];
            $itemInfo = $itemsDb[$itemId] ?? null;
            $itemName = $itemInfo['name'] ?? ("Item #" . $itemId);
            $slots = (int)($itemInfo['slots'] ?? 0);

            if ($q !== '') {
                $match = (string)$itemId === $q
                    || str_contains(mb_strtolower($itemName), $q)
                    || str_contains(mb_strtolower($row['title']), $q)
                    || str_contains(mb_strtolower($row['vendor_name']), $q)
                    || str_contains(mb_strtolower($row['map']), $q);
                if (!$match) {
                    continue;
                }
            }

            $cards = [];
            foreach (['card0', 'card1', 'card2', 'card3'] as $ck) {
                $cid = (int)$row[$ck];
                if ($cid > 4000) {
                    $cards[] = $itemsDb[$cid]['name'] ?? ("Card #{$cid}");
                }
            }

            $row['item_name'] = $itemName;
            $row['item_slots'] = $slots;
            $row['item_type'] = $itemInfo['type'] ?? 'Etc';
            $row['cards'] = $cards;
            $enriched[] = $row;
        }

        $total = count($enriched);
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;
        return [
            'rows'  => array_slice($enriched, $offset, $perPage),
            'total' => $total,
            'page'  => $page,
            'pages' => max(1, (int)ceil($total / $perPage)),
        ];
    }
}
