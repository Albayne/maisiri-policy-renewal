<?php
require_once __DIR__ . '/../models/Policy.php';
require_once __DIR__ . '/../middleware/Auth.php';

class DashboardController
{
    public function index(): void
    {
        Auth::requireAuth();
        $policyModel = new Policy();
        $stats = $policyModel->getDashboardStats();
        require __DIR__ . '/../views/dashboard/index.php';
    }
}
