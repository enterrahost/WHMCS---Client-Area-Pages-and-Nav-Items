<?php

use WHMCS\View\Menu\Item as MenuItem;

/*
|--------------------------------------------------------------------------
| WHMCS ClientAreaNavbars Hook
|--------------------------------------------------------------------------
| This is for WHMCS and has been tested on version 8.12.x
|
| This hook hides or shows the "Affiliates" navbar item based on whether
| the user is in a specific client group (e.g., group ID = 1 or 10).
|
| You can find and configure client groups in the `tblclientgroups` table.
|
| You're welcome to use and modify this for your needs. It can be adapted
| to show/hide other menu items in the client area (e.g. "Submit Ticket"),
| depending on group-based access or other logic.
| 
| place is includes/hooks/
|
| — Enterrahost
*/

add_hook('ClientAreaNavbars', 1, function () {
    try {
        $client = Menu::context('client');

        if (!is_null($client) && is_object($client)) {
            $userId = $client->id;

            $command = 'GetClientsDetails';
            $postData = [
                'clientid' => $userId,
                'stats' => false,
            ];
            $results = localAPI($command, $postData);

            $allowedGroupIds = [1, 10]; // Add any other group IDs here
            $isAffiliate = false;

            if ($results['result'] === 'success') {
                $groupId = (int)$results['client']['groupid'];
                if (in_array($groupId, $allowedGroupIds)) {
                    $isAffiliate = true;
                }
            }

            $primaryNavbar = Menu::primaryNavbar();
            if ($primaryNavbar instanceof MenuItem && !$isAffiliate) {
                $primaryNavbar->removeChild('Affiliates');
            }
        }
    } catch (Exception $e) {
        logActivity("Affiliate Navbar Hook Error: " . $e->getMessage());
    }
});

/*
|--------------------------------------------------------------------------
| WHMCS ClientAreaPageAffiliates Hook
|--------------------------------------------------------------------------
| This is for WHMCS and has been tested on version 8.12.x
|
| This hook prevents direct access to the affiliates.php page by users
| who are NOT part of the allowed client groups (e.g., group IDs 1 or 10).
|
| If the user is not logged in, it redirects to the login page.
| If the user is logged in but not in the right group, it redirects to
| the client area home (or you can change this to a custom page).
|
| — Enterrahost
*/

add_hook('ClientAreaPageAffiliates', 1, function ($vars) {
    try {
        $allowedGroupIds = [1, 10]; // Add any other group IDs here

        if (isset($_SESSION['uid'])) {
            $clientId = $_SESSION['uid'];

            $command = 'GetClientsDetails';
            $postData = [
                'clientid' => $clientId,
                'stats' => false,
            ];
            $results = localAPI($command, $postData);

            if ($results['result'] === 'success') {
                $groupId = (int)$results['client']['groupid'];
                if (!in_array($groupId, $allowedGroupIds)) {
                    header("Location: clientarea.php");
                    exit;
                }
            }
        } else {
            header("Location: login.php");
            exit;
        }
    } catch (Exception $e) {
        logActivity("Affiliate Page Access Hook Error: " . $e->getMessage());
    }
});
