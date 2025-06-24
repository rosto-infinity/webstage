<?php

return [
    'roles' => explode(',', env('APP_USER_ROLES', 'user,admin,superadmin'))
];