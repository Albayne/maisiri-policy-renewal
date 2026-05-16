<?php
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/PolicyController.php';
require_once __DIR__ . '/../controllers/UserController.php';

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    // Auth
    case 'login':
        (new AuthController())->loginForm();
        break;
    case 'login_post':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;

    // Dashboard
    case 'dashboard':
        (new DashboardController())->index();
        break;

    // Policies
    case 'policies':
        (new PolicyController())->index();
        break;
    case 'view_policy':
        (new PolicyController())->view();
        break;
    case 'add_policy':
        (new PolicyController())->addForm();
        break;
    case 'add_policy_post':
        (new PolicyController())->add();
        break;
    case 'edit_policy':
        (new PolicyController())->editForm();
        break;
    case 'edit_policy_post':
        (new PolicyController())->edit();
        break;
    case 'delete_policy':
        (new PolicyController())->delete();
        break;
    case 'upload_document':
        (new PolicyController())->uploadDocument();
        break;
    case 'download_document':
        (new PolicyController())->downloadDocument();
        break;

    // Users (admin)
    case 'users':
        (new UserController())->index();
        break;
    case 'add_user':
        (new UserController())->addForm();
        break;
    case 'add_user_post':
        (new UserController())->add();
        break;
    case 'edit_user':
        (new UserController())->editForm();
        break;
    case 'edit_user_post':
        (new UserController())->edit();
        break;
    case 'deactivate_user':
        (new UserController())->deactivate();
        break;

    default:
        http_response_code(404);
        require __DIR__ . '/../views/error/404.php';
}
