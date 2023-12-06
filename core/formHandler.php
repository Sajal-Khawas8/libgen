<?php
require "./classes/validations.php";
require "./classes/user.php";

if(isset($_POST['masterLogin'])) {
    $isDataValid = true;
    $validation = new ValidateData();
    $query = new DatabaseQuery();
    $uuid = $query->selectColumn('uuid', 'users', $_POST['email'], 'email');
    $err = [
        'emailErr' => $validation->validateAdminLoginEmail($_POST['email'], $isDataValid),
        'passwordErr' => $validation->validatePassword($_POST['password'], $uuid, $isDataValid)
    ];
    if($isDataValid) {
        setcookie('user', $uuid);
        $_SESSION['isAdmin'] = true;
        $_SESSION['refresh'] = true;
        header("Location: /admin");
        exit;
    } else {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /masterLogin");
        exit;
    }
}

if(isset($_POST['logout'])) {
    setcookie('user', '', time() - 1);
    unset($_SESSION['isAdmin']);
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}

if(isset($_POST['register'])) {
    $validationObj = new ValidateData();
    $user = new User($validationObj);
    unset($_POST['register'], $_POST['id'], $_POST['role']);
    $uuid = $user->addUser($_POST);
    setcookie('user', $uuid, time() + 86400);
    $_SESSION['refresh'] = true;
    $location = $_COOKIE['prevPage'] ?? '/libgen';
    header("Location: $location");
    exit;
}

if(isset($_POST['registerAdmin'])) {
    $validationObj = new ValidateData();
    $user = new User($validationObj);
    unset($_POST['registerAdmin'], $_POST['id']);
    $_POST['role'] = true;
    $user->addUser($_POST);
    $_SESSION['refresh'] = true;
    header("Location: /admin/team");
    exit;
}

if(isset($_POST['login'])) {
    $isDataValid = true;
    $query = new DatabaseQuery();
    $uuid = $query->selectColumn('uuid', 'users', $_POST['email'], 'email');
    $validation = new ValidateData();
    $err = [
        'emailErr' => $validation->validateLoginEmail($_POST['email'], $isDataValid),
        'passwordErr' => $validation->validatePassword($_POST['password'], $uuid, $isDataValid)
    ];
    if($isDataValid) {
        setcookie('user', $uuid, time() + 86400);
        $_SESSION['refresh'] = true;
        $location = $_COOKIE['prevPage'] ?? '/libgen';
        header("Location: $location");
        exit;
    } else {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /login");
        exit;
    }
}

if(isset($_POST['updateAdmin'])) {
    header("Location: /admin/settings/update?{$_POST['id']}");
}

if(isset($_POST['updateUser'])) {
    header("Location: /update?{$_POST['id']}");
}

if(isset($_POST['updateData'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    unset($_POST['updateData'], $_POST['id']);
    if($id === $_COOKIE['user']) {
        $validationObj = new ValidateData();
        $user = new User($validationObj);
        $user->updateUser($_POST, $_COOKIE['user']);
        $_SESSION['refresh'] = true;
        $location = isset($_SESSION['isAdmin']) ? '/admin/settings' : '/settings';
        header("Location: $location");
        exit;
    } else {
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
}

if(isset($_POST['deleteAccount'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if($id === $_COOKIE['user']) {
        $user = new User();
        $user->removeUser($id);
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    } else {
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
}

if(isset($_POST['removeAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = $query->selectColumn('isSuper', 'users', $_COOKIE['user'], 'uuid');
    if($isSuperAdmin) {
        $config = require "./core/config.php";
        $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        $user = new User();
        $user->removeUser($id);
        $query->update('users', 'role=false, isSuper=false, ', $id, 'uuid');
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    } else {
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
}

if(isset($_POST['makeSuperAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = $query->selectColumn('isSuper', 'users', $_COOKIE['user'], 'uuid');
    if($isSuperAdmin) {
        $config = require "./core/config.php";
        $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        $query->update('users', 'isSuper=true, ', $id, 'uuid');
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    } else {
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
}

if(isset($_POST['removeSuperAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = $query->selectColumn('isSuper', 'users', $_COOKIE['user'], 'uuid');
    if($isSuperAdmin) {
        $config = require "./core/config.php";
        $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        $query->update('users', 'isSuper=false, ', $id, 'uuid');
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    } else {
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
}

if(isset($_POST['blockUser'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $user = new User();
    $user->removeUser($id);
    $_SESSION['refresh'] = true;
    header("Location: /admin/readers");
    exit;
}

if (isset($_POST['searchUser'])) {
    $query=new DatabaseQuery();
    $validation=new ValidateData();
    $isDataValid=true;
    $err=$validation->validateLoginEmail($_POST['email'], $isDataValid);
    if ($isDataValid) {
        $uuid=$query->selectColumn('uuid', 'users', $_POST['email'], 'email');
        $config=require "./core/config.php";
        $uuid=openssl_encrypt($uuid, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        header("Location: /admin/readers?$uuid");
        exit;
    } else {
        setcookie('err', $err, time() + 2);
        setcookie('data', $_POST['email'], time() + 2);
        header("Location: /admin/readers");
        exit;
    }
}

if (isset($_POST['searchAdmin'])) {
    $query=new DatabaseQuery();
    $validation=new ValidateData();
    $isDataValid=true;
    $err=$validation->validateLoginEmail($_POST['email'], $isDataValid);
    if ($isDataValid) {
        $uuid=$query->selectColumn('uuid', 'users', $_POST['email'], 'email');
        $config=require "./core/config.php";
        $uuid=openssl_encrypt($uuid, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        header("Location: /admin/team?$uuid");
        exit;
    } else {
        setcookie('err', $err, time() + 2);
        setcookie('data', $_POST['email'], time() + 2);
        header("Location: /admin/team");
        exit;
    }
}
?>