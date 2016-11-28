<?php
$errors = '';
if (isset($_POST['submit'])) {
    $email = filter_input(INPUT_POST, 'inputEmail', FILTER_VALIDATE_EMAIL);//$_POST['inputEmail'];
    $password = filter_input(INPUT_POST, 'inputPassword', FILTER_SANITIZE_STRING);//$_POST['inputPassword'];
    $userDao = new UserDao();
    $user = $userDao->findByCredentials($email, $password);        
    if($email === $user->getEmail() && $password === $user->getPassword()){
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_role'] = $user->getRole();       
        header('Location: index.php');
    }else{
        $errors = 'These credentials are not recognised.';
    }   
}