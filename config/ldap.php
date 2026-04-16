<?php

return [
    'host' => '10.30.5.55',
    'port' => 389,
    'base_dn' => 'DC=vallestech,DC=local',

    // Esta es la forma que ya te funcionaba
    'bind_user' => 'ldap-bind',
    'bind_password' => 'P@ssw0rd',

    'user_filter' => '(&(objectClass=user)(sAMAccountName=%s))',

    'groups' => [
        'student' => 'CN=Alumnos,OU=Grupos,OU=WebVallesTech,DC=vallestech,DC=local',
        'teacher' => 'CN=Profesores,OU=Grupos,OU=WebVallesTech,DC=vallestech,DC=local',
    ],
];