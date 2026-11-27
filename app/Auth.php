<?php
declare(strict_types=1);

namespace App;

/**
 * Handles rAthena account registration, session authentication, and character self-service.
 */
final class Auth
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name('ASTRARO_SID');
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
            ]);
        }
    }

    public static function currentUser(): ?array
    {
        if (empty($_SESSION['account_id'])) {
            return null;
        }
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT account_id, userid, sex, email, group_id, state, logincount, lastlogin, last_ip, birthdate, character_slots, vip_time
             FROM login WHERE account_id = ? LIMIT 1'
        );
        $stmt->execute([(int)$_SESSION['account_id']]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function hashPassword(string $plain): string
    {
        return USE_MD5_PASSWORDS ? md5($plain) : $plain;
    }

    public static function verifyPassword(string $plain, string $stored): bool
    {
        $candidate = self::hashPassword($plain);
        return hash_equals($stored, $candidate);
    }

    public static function register(string $userid, string $password, string $email, string $sex, string $birthdate = '2000-01-01'): array
    {
        $userid = trim($userid);
        $email = trim($email);
        $sex = in_array($sex, ['M', 'F'], true) ? $sex : 'M';

        if (!preg_match('/^[A-Za-z0-9_]{4,23}$/', $userid)) {
            return ['ok' => false, 'error' => 'Username must be 4–23 characters (letters, numbers, underscore only).'];
        }
        if (strlen($password) < 4 || strlen($password) > 32) {
            return ['ok' => false, 'error' => 'Password must be between 4 and 32 characters.'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 39) {
            return ['ok' => false, 'error' => 'Please enter a valid email address (max 39 characters).'];
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthdate)) {
            $birthdate = '2000-01-01';
        }

        $pdo = Database::connection();
        $check = $pdo->prepare('SELECT account_id FROM login WHERE userid = ? LIMIT 1');
        $check->execute([$userid]);
        if ($check->fetch()) {
            return ['ok' => false, 'error' => 'This username is already taken.'];
        }

        $stmt = $pdo->prepare(
            'INSERT INTO login (userid, user_pass, sex, email, group_id, state, last_ip, birthdate, character_slots)
             VALUES (?, ?, ?, ?, 0, 0, ?, ?, 15)'
        );
        $stmt->execute([
            $userid,
            self::hashPassword($password),
            $sex,
            $email,
            $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            $birthdate,
        ]);

        $accountId = (int)$pdo->lastInsertId();
        $_SESSION['account_id'] = $accountId;

        return ['ok' => true, 'account_id' => $accountId];
    }

    public static function login(string $userid, string $password): array
    {
        $userid = trim($userid);
        if ($userid === '' || $password === '') {
            return ['ok' => false, 'error' => 'Please enter both username and password.'];
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT account_id, userid, user_pass, state FROM login WHERE userid = ? LIMIT 1');
        $stmt->execute([$userid]);
        $row = $stmt->fetch();

        if (!$row || !self::verifyPassword($password, $row['user_pass'])) {
            return ['ok' => false, 'error' => 'Invalid username or password.'];
        }
        if ((int)$row['state'] !== 0) {
            return ['ok' => false, 'error' => 'This account is currently restricted or banned.'];
        }

        session_regenerate_id(true);
        $_SESSION['account_id'] = (int)$row['account_id'];
        return ['ok' => true];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public static function changePassword(int $accountId, string $currentPassword, string $newPassword): array
    {
        if (strlen($newPassword) < 4 || strlen($newPassword) > 32) {
            return ['ok' => false, 'error' => 'New password must be 4–32 characters.'];
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT user_pass FROM login WHERE account_id = ? LIMIT 1');
        $stmt->execute([$accountId]);
        $row = $stmt->fetch();
        if (!$row || !self::verifyPassword($currentPassword, $row['user_pass'])) {
            return ['ok' => false, 'error' => 'Current password is incorrect.'];
        }

        $upd = $pdo->prepare('UPDATE login SET user_pass = ? WHERE account_id = ?');
        $upd->execute([self::hashPassword($newPassword), $accountId]);
        return ['ok' => true];
    }

    public static function userCharacters(int $accountId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT c.char_id, c.char_num, c.name, c.class, c.base_level, c.job_level, c.zeny,
                    c.last_map, c.last_x, c.last_y, c.online, c.last_login, g.name AS guild_name
             FROM `char` c
             LEFT JOIN guild g ON g.guild_id = c.guild_id
             WHERE c.account_id = ? AND c.delete_date = 0
             ORDER BY c.char_num ASC'
        );
        $stmt->execute([$accountId]);
        return $stmt->fetchAll();
    }

    public static function resetPosition(int $accountId, int $charId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT char_id, name, online FROM `char` WHERE char_id = ? AND account_id = ? LIMIT 1');
        $stmt->execute([$charId, $accountId]);
        $char = $stmt->fetch();

        if (!$char) {
            return ['ok' => false, 'error' => 'Character not found on your account.'];
        }
        if ((int)$char['online'] === 1) {
            return ['ok' => false, 'error' => 'Please log out of the game before resetting character position.'];
        }

        $upd = $pdo->prepare("UPDATE `char` SET last_map = 'prontera', last_x = 156, last_y = 191 WHERE char_id = ? AND account_id = ?");
        $upd->execute([$charId, $accountId]);
        return ['ok' => true, 'name' => $char['name']];
    }

    public static function resetLook(int $accountId, int $charId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT char_id, name, online FROM `char` WHERE char_id = ? AND account_id = ? LIMIT 1');
        $stmt->execute([$charId, $accountId]);
        $char = $stmt->fetch();

        if (!$char) {
            return ['ok' => false, 'error' => 'Character not found on your account.'];
        }
        if ((int)$char['online'] === 1) {
            return ['ok' => false, 'error' => 'Please log out of the game before resetting character look.'];
        }

        $upd = $pdo->prepare('UPDATE `char` SET hair = 1, hair_color = 0, clothes_color = 0 WHERE char_id = ? AND account_id = ?');
        $upd->execute([$charId, $accountId]);
        return ['ok' => true, 'name' => $char['name']];
    }
}
