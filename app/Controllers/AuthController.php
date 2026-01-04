<?php
namespace App\Controllers;

// app/controllers/AuthController.php
require_once __DIR__ . "/../Models/User.php";
require_once __DIR__ . "/Controller.php";
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        // Jika sudah login, lempar ke dashboard
        if (isset($_SESSION["user_id"])) {
            $this->redirect("dashboard")->with("success", "Anda sudah login");
            exit();
        }

        // Tampilkan view login
        $this->view("auth/login", ["title" => "Login"]);
    }

    public function submitLogin()
    {
        $this->validateLogin();

        $username = $_POST["username"];
        $password = $_POST["password"];

        // Cek user di database
        $user = User::getCredential($username);

        // Verifikasi Password
        if (!($user && password_verify($password, $user["password"]))) {
            $this->redirect("login")->with(
                "error",
                "Username atau password salah"
            );
            exit();
        }

        $this->createSession($user);

        // Redirect sukses ke dashboard
        $this->redirect("dashboard")->with("success", "Anda berhasil login");
        exit();
    }

    private function validateLogin()
    {
        if (empty($_POST["username"]) || empty($_POST["password"])) {
            $this->redirect("login")->with(
                "error",
                "Username atau password tidak boleh kosong"
            );
            exit();
        }
    }

    private function createSession($user)
    {
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
    }

    public function logout()
    {
        session_destroy();
        $this->redirect("login")->with("success", "Anda berhasil logout");
        exit();
    }
}
