<?php

require_once __DIR__ . '/../app/database/db.class.php';

class LoginResult
{
}

class LoginFailure extends LoginResult
{
    public string $error_message;

    public function __construct($error_message)
    {
        $this->error_message = $error_message;
    }

    public function success()
    {
        return false;
    }
}

class LoginSuccess extends LoginResult
{
    public function success()
    {
        return true;
    }
};

class LoginService
{
    public function attempt($username, $password)
    {
        $db = DB::getConnection();

        try {
            $st = $db->prepare('SELECT id, username, password_hash, has_registered FROM burza_users WHERE username=:username');
            $st->execute(array('username' => $_POST['username']));
        } catch (PDOException $e) {
            exit('DB error (LoginService.attempt): ' . $e->getMessage());
        }

        $row = $st->fetch();

        if ($row === false) {
            return new LoginFailure('Korisnik s tim imenom ne postoji.');
        } else if ($row['has_registered'] === '0') {
            return new LoginFailure('Korisnik s tim imenom se nije još registrirao. Provjerite e-mail.');
        } else if (!password_verify($_POST['password'], $row['password_hash'])) {
            return new LoginFailure('Lozinka nije ispravna.');
        } else {
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['id'] = $row['id'];
            return new LoginSuccess();
        }
    }

    public function attempt_register($username, $password)
    {
        $db = DB::getConnection();

        try {
            $st = $db->prepare('SELECT * FROM burza_users WHERE username=:username');
            $st->execute(array('username' => $_POST['username']));
        } catch (PDOException $e) {
            exit('Greška u bazi: ' . $e->getMessage());
        }

        if ($st->rowCount() !== 0) {
            return new LoginFailure('Korisnik s tim imenom već postoji u bazi.');
        }

        $reg_seq = '';
        for ($i = 0; $i < 20; ++$i)
            $reg_seq .= chr(rand(0, 25) + ord('a')); // Zalijepi slučajno odabrano slovo

        try {
            $st = $db->prepare('INSERT INTO burza_users(username, password_hash, email, registration_sequence, has_registered) VALUES ' .
                '(:username, :password, :email, :reg_seq, 0)');

            $st->execute(array(
                'username' => $_POST['username'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'email' => $_POST['email'],
                'reg_seq'  => $reg_seq
            ));
        } catch (PDOException $e) {
            exit('DB error (LoginService.attempt_register): ' . $e->getMessage());
        }

        // Sad mu još pošalji mail
        $to       = $_POST['email'];
        $subject  = 'Registracijski mail';
        $message  = 'Poštovani ' . $_POST['username'] . "!\nZa dovršetak registracije kliknite na sljedeći link: ";
        $message .= 'http://' . $_SERVER['SERVER_NAME'] . htmlentities(dirname($_SERVER['PHP_SELF'])) . '/teamup.php?rt=register/verify&niz=' . $reg_seq . "\n";
        $headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
            'Reply-To: rp2@studenti.math.hr' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $isOK = mail($to, $subject, $message, $headers);

        if (!$isOK) {
            return new LoginFailure('Ne mogu poslati mail. (Pokrenite na rp2 serveru.)');
        }

        return new LoginSuccess();
    }

    public function logout()
    {
        unset($_SESSION['username']);
    }
};
