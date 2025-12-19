<?php

/**
 * Gestion de l'authentification
 */

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Vérifier si l'utilisateur est connecté
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

/**
 * Rediriger vers la page de connexion si non connecté
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: /index.php');
        exit;
    }
}

/**
 * Connexion de l'utilisateur
 */
function login($userId, $username, $nom)
{
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['nom'] = $nom;
    $_SESSION['login_time'] = time();
}

/**
 * Déconnexion de l'utilisateur
 */
function logout()
{
    session_unset();
    session_destroy();
}

/**
 * Obtenir l'utilisateur connecté
 */
function getCurrentUser()
{
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'nom' => $_SESSION['nom']
        ];
    }
    return null;
}
