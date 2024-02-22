<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: ./index.php');
    exit();
} else {
    require_once '../vendor/autoload.php';

    // Create connection
    $conn = new MongoDB\Client('mongodb://localhost:27017');

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;
    $vehicles = $academy->vehicles;
    $quizzes = $academy->quizzes;
    $allocations = $academy->allocations;

    if (isset($_POST['submit'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $matricule = $_POST['matricule'];
        $username = $_POST['username'];
        $subsidiary = $_POST['subsidiary'];
        $department = $_POST['department'];
        $role = $_POST['role'];
        $profile = $_POST['profile'];
        $gender = $_POST['gender'];
        $password = $_POST['password'];
        $country = $_POST['country'];
        $certificate = $_POST['certificate'];
        $subBrand = $_POST['subBrand'];
        $vehicle = $_POST['vehicle'];
        $brand = $_POST['brand'];
        $birthdate = date('d-m-Y', strtotime($_POST['birthdate']));
        $recrutmentDate = date('d-m-Y', strtotime($_POST['recrutmentDate']));
        $level = $_POST['level'];
        $manager = $_POST['manager'];

        $techs = [];

        $password_hash = sha1($password);
        $member = $users->findOne([
        '$and' => [
            ['username' => $username],
            ['active' => true],
        ],
    ]);
        if (empty($firstName) ||
        empty($lastName) ||
        empty($role) ||
        empty($username) ||
        empty($matricule) ||
        empty($birthdate) ||
        empty($certificate) ||
        empty($subsidiary) ||
        empty($department) ||
        empty($recrutmentDate) ||
        empty($gender) ||
        empty($level) ||
        empty($vehicle) ||
        empty($brand) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        preg_match('/^[\D]{15}$/', $phone) ||
        preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{6,}$/', $password)) {
            $error = 'Champ obligatoire';
            $email_error = ("L'adresse email est invalide");
            $phone_error = ('Le numéro de téléphone est invalide');
            $password_error = ('Le mot de passe doit être au moins six caractères, un chiffre, une lettre majiscule');
        } elseif ($member) {
            $error_msg = 'Cet utilisateur existe déjà';
        } else {
            if ($profile == 'Technicien') {
                if ($manager) {
                    $personT = [
                        'users' => [],
                        'username' => $username,
                        'matricule' => $matricule,
                        'firstName' => ucfirst($firstName),
                        'lastName' => ucfirst($lastName),
                        'email' => $email,
                        'phone' => +$phone,
                        'gender' => $gender,
                        'level' => $level,
                        'country' => $country,
                        'profile' => $profile,
                        'birthdate' => $birthdate,
                        'recrutmentDate' => $recrutmentDate,
                        'certificate' => ucfirst($certificate),
                        'subsidiary' => ucfirst($subsidiary),
                        'department' => ucfirst($department),
                        'vehicle' => $vehicle,
                        'brand' => $brand,
                        'subBrand' => $subBrand,
                        'role' => ucfirst($role),
                        'password' => $password_hash,
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'active' => true,
                        'created' => date('d-m-Y'),
                    ];
                    $user = $users->insertOne($personT);
                    $users->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($manager)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                } else {
                    $personT = [
                        'users' => [],
                        'username' => $username,
                        'matricule' => $matricule,
                        'firstName' => ucfirst($firstName),
                        'lastName' => ucfirst($lastName),
                        'email' => $email,
                        'phone' => +$phone,
                        'gender' => $gender,
                        'level' => $level,
                        'country' => $country,
                        'profile' => $profile,
                        'birthdate' => $birthdate,
                        'recrutmentDate' => $recrutmentDate,
                        'certificate' => ucfirst($certificate),
                        'subsidiary' => ucfirst($subsidiary),
                        'department' => ucfirst($department),
                        'vehicle' => $vehicle,
                        'brand' => $brand,
                        'subBrand' => $subBrand,
                        'role' => ucfirst($role),
                        'password' => $password_hash,
                        'manager' => "",
                        'active' => true,
                        'created' => date('d-m-Y'),
                    ];
                    $user = $users->insertOne($personT);
                }
                $vehicleFacJu = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Factuel'],
                    ['level' => 'Junior'],
                    ['active' => true],
                ],
            ]);
                $vehicleDeclaJu = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Declaratif'],
                    ['level' => 'Junior'],
                    ['active' => true],
                ],
            ]);
                $vehicleFacSe = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Factuel'],
                    ['level' => 'Senior'],
                    ['active' => true],
                ],
            ]);
                $vehicleDeclaSe = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Declaratif'],
                    ['level' => 'Senior'],
                    ['active' => true],
                ],
            ]);
                $vehicleFacEx = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Factuel'],
                    ['level' => 'Expert'],
                    ['active' => true],
                ],
            ]);
                $vehicleDeclaEx = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Declaratif'],
                    ['level' => 'Expert'],
                    ['active' => true],
                ],
            ]);
                if ($level == 'Junior') {
                    if ($vehicleFacJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacJu->type,
                        'level' => $vehicleFacJu->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaJu->type,
                        'level' => $vehicleDeclaJu->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    $success_msg = 'Technicien ajouté avec succès';
                } elseif ($level == 'Senior') {
                    if ($vehicleFacJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacJu->type,
                        'level' => $vehicleFacJu->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaJu->type,
                        'level' => $vehicleDeclaJu->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleFacSe) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacSe->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacSe->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacSe->type,
                        'level' => $vehicleFacSe->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaSe) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaSe->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaSe->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaSe->type,
                        'level' => $vehicleDeclaSe->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    $success_msg = 'Technicien ajouté avec succès';
                } elseif ($level == 'Expert') {
                    if ($vehicleFacJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacJu->type,
                        'level' => $vehicleFacJu->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaJu->type,
                        'level' => $vehicleDeclaJu->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleFacSe) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacSe->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacSe->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacSe->type,
                        'level' => $vehicleFacSe->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaSe) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaSe->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaSe->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaSe->type,
                        'level' => $vehicleDeclaSe->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleFacEx) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacEx->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacEx->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacEx->type,
                        'level' => $vehicleFacEx->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaEx) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaEx->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaEx->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaEx->type,
                        'level' => $vehicleDeclaEx->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    $success_msg = 'Technicien ajouté avec succès';
                }
            } elseif ($profile == 'Manager (à évaluer)') {
                if ($manager) {
                    $personM = [
                        'users' => [],
                        'username' => $username,
                        'matricule' => $matricule,
                        'firstName' => ucfirst($firstName),
                        'lastName' => ucfirst($lastName),
                        'email' => $email,
                        'phone' => +$phone,
                        'gender' => $gender,
                        'level' => $level,
                        'country' => $country,
                        'profile' => "Manager",
                        'birthdate' => $birthdate,
                        'recrutmentDate' => $recrutmentDate,
                        'certificate' => ucfirst($certificate),
                        'subsidiary' => ucfirst($subsidiary),
                        'department' => ucfirst($department),
                        'vehicle' => $vehicle,
                        'brand' => $brand,
                        'subBrand' => $subBrand,
                        'role' => ucfirst($role),
                        'password' => $password_hash,
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'test' => true,
                        'active' => true,
                        'created' => date('d-m-Y'),
                    ];
                    $user = $users->insertOne($personM);
                    $users->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($manager)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                } else {
                    $personM = [
                        'users' => [],
                        'username' => $username,
                        'matricule' => $matricule,
                        'firstName' => ucfirst($firstName),
                        'lastName' => ucfirst($lastName),
                        'email' => $email,
                        'phone' => +$phone,
                        'gender' => $gender,
                        'level' => $level,
                        'country' => $country,
                        'profile' => $profile,
                        'birthdate' => $birthdate,
                        'recrutmentDate' => $recrutmentDate,
                        'certificate' => ucfirst($certificate),
                        'subsidiary' => ucfirst($subsidiary),
                        'department' => ucfirst($department),
                        'vehicle' => $vehicle,
                        'brand' => $brand,
                        'subBrand' => $subBrand,
                        'role' => ucfirst($role),
                        'password' => $password_hash,
                        'manager' => "",
                        'active' => true,
                        'created' => date('d-m-Y'),
                    ];
                    $user = $users->insertOne($personM);
                }
                $vehicleFacJu = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Factuel'],
                    ['level' => 'Junior'],
                    ['active' => true],
                ],
            ]);
                $vehicleDeclaJu = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Declaratif'],
                    ['level' => 'Junior'],
                    ['active' => true],
                ],
            ]);
                $vehicleFacSe = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Factuel'],
                    ['level' => 'Senior'],
                    ['active' => true],
                ],
            ]);
                $vehicleDeclaSe = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Declaratif'],
                    ['level' => 'Senior'],
                    ['active' => true],
                ],
            ]);
                $vehicleFacEx = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Factuel'],
                    ['level' => 'Expert'],
                    ['active' => true],
                ],
            ]);
                $vehicleDeclaEx = $vehicles->findOne([
                '$and' => [
                    ['label' => $vehicle],
                    ['brand' => $brand],
                    ['type' => 'Declaratif'],
                    ['level' => 'Expert'],
                    ['active' => true],
                ],
            ]);
                if ($level == 'Junior') {
                    if ($vehicleFacJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacJu->type,
                        'level' => $vehicleFacJu->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaJu->type,
                        'level' => $vehicleDeclaJu->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    $success_msg = 'Manager ajouté avec succès';
                } elseif ($level == 'Senior') {
                    if ($vehicleFacJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacJu->type,
                        'level' => $vehicleFacJu->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaJu->type,
                        'level' => $vehicleDeclaJu->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleFacSe) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacSe->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacSe->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacSe->type,
                        'level' => $vehicleFacSe->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaSe) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaSe->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaSe->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaSe->type,
                        'level' => $vehicleDeclaSe->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    $success_msg = 'Manager ajouté avec succès';
                } elseif ($level == 'Expert') {
                    if ($vehicleFacJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacJu->type,
                        'level' => $vehicleFacJu->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaJu) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaJu->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaJu->type,
                        'level' => $vehicleDeclaJu->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleFacSe) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacSe->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacSe->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacSe->type,
                        'level' => $vehicleFacSe->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaSe) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaSe->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaSe->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaSe->type,
                        'level' => $vehicleDeclaSe->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleFacEx) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleFacEx->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleFacEx->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleFacEx->type,
                        'level' => $vehicleFacEx->level,
                        'active' => false,
                        'created' => date('d-m-Y'),
                        ];
                        $allocations->insertOne($allocates);
                    }
                    if ($vehicleDeclaEx) {
                        $vehicles->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($vehicleDeclaEx->_id)],
                        ['$push' => ['users' => new MongoDB\BSON\ObjectId($user->getInsertedId())]]
                    );
                        $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId($vehicleDeclaEx->_id),
                        'user' => new MongoDB\BSON\ObjectId($user->getInsertedId()),
                        'type' => $vehicleDeclaEx->type,
                        'level' => $vehicleDeclaEx->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date('d-m-Y'),
                    ];
                        $allocations->insertOne($allocates);
                    }
                    $success_msg = 'Manager ajouté avec succès';
                }
            } elseif ($profile == 'Manager (non évalué)') {
                $personM = [
                    'users' => [],
                    'username' => $username,
                    'matricule' => $matricule,
                    'firstName' => ucfirst($firstName),
                    'lastName' => ucfirst($lastName),
                    'email' => $email,
                    'phone' => +$phone,
                    'gender' => $gender,
                    'level' => $level,
                    'country' => $country,
                    'profile' => "Manager",
                    'birthdate' => $birthdate,
                    'recrutmentDate' => $recrutmentDate,
                    'certificate' => ucfirst($certificate),
                    'subsidiary' => ucfirst($subsidiary),
                    'department' => ucfirst($department),
                    'vehicle' => $vehicle,
                    'brand' => $brand,
                    'subBrand' => $subBrand,
                    'role' => ucfirst($role),
                    'password' => $password_hash,
                    'test' => false,
                    'active' => true,
                    'created' => date('d-m-Y'),
                ];
                $user = $users->insertOne($personM);
            } elseif ($profile == 'Admin') {
                $personA = [
                'users' => [],
                'username' => $username,
                'matricule' => $matricule,
                'firstName' => ucfirst($firstName),
                'lastName' => ucfirst($lastName),
                'email' => $email,
                'phone' => +$phone,
                'gender' => $gender,
                'level' => 'Non applicable',
                'country' => $country,
                'profile' => $profile,
                'birthdate' => $birthdate,
                'recrutmentDate' => $recrutmentDate,
                'certificate' => ucfirst($certificate),
                'subsidiary' => ucfirst($subsidiary),
                'department' => ucfirst($department),
                'vehicle' => $vehicle,
                'brand' => $brand,
                'subBrand' => $subBrand,
                'role' => ucfirst($role),
                'password' => $password_hash,
                'active' => true,
                'created' => date('d-m-Y'),
            ];
                $users->insertOne($personA);
                $success_msg = 'Administrateur ajouté avec succès';
            }
        }
    } ?>

<?php
include_once 'partials/header.php'; ?>

<!--begin::Title-->
<title>Ajouter Utilisateur | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content" data-select2-id="select2-data-kt_content">
  <!--begin::Post-->
  <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
    <!--begin::Container-->
    <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
      <!--begin::Modal body-->
      <div class='container mt-5 w-50'>
        <img src='../public/images/logo.png' alt='10' height='170' style='display: block; margin-left: auto; margin-right: auto; width: 50%;'>
        <h1 class='my-3 text-center'>Ajouter un utilisateur</h1>

        <?php
                   if (isset($success_msg)) {
                ?>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
          <center><strong><?php echo $success_msg; ?></strong></center>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;
            </span>
          </button>
        </div>
        <?php
                   } ?>
        <?php
                   if (isset($error_msg)) {
                ?>
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
          <center><strong><?php echo $error_msg; ?></strong></center>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;
            </span>
          </button>
        </div>
        <?php
                } ?>

        <form method='POST'><br>
          <!--begin::Input group-->
          <div class='row fv-row mb-7'>
            <!--begin::Input group-->
            <div class='row g-9 mb-7'>
              <!--begin::Col-->
              <div class='col-md-6 fv-row'>
                <!--begin::Label-->
                <label class='required form-label fw-bolder text-dark fs-6'>Prénoms</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class='form-control form-control-solid' placeholder='' name='firstName' <?php
                                    if (isset($_POST['submit'])) {
                                        echo 'value="'.$firstName.'"';
                                    }
                                     ?> />
                <?php
                             if (isset($error)) {
                                     ?>
                <span class='text-danger'>
                  <?php echo $error; ?>
                </span>
                <?php
                                 } ?>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class='col-md-6 fv-row'>
                <!--begin::Label-->
                <label class='required form-label fw-bolder text-dark fs-6'>Noms</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class='form-control form-control-solid' placeholder='' name='lastName' <?php
                                    if (isset($_POST['submit'])) {
                                        echo 'value="'.$lastName.'"';
                                    }
                                     ?> />
                <!--end::Input-->
                <?php
                             if (isset($error)) {
                                     ?>
                <span class='text-danger'>
                  <?php echo $error; ?>
                </span>
                <?php
                                 } ?>
              </div>
              <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='fv-row mb-7'>
              <!--begin::Label-->
              <label class='required form-label fw-bolder text-dark fs-6'>Nom d'utilisateur</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type='text' class='form-control form-control-solid' placeholder='' name='username' <?php
                                    if (isset($_POST['submit'])) {
                                        echo 'value="'.$username.'"';
                                    }
                                     ?> />
              <!--end::Input-->
              <?php
                          if (isset($error)) {
                                  ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                              } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='fv-row mb-7'>
              <!--begin::Label-->
              <label class='required form-label fw-bolder text-dark fs-6'>Matricule</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type='text' class='form-control form-control-solid' placeholder='' name='matricule' <?php
                                    if (isset($_POST['submit'])) {
                                        echo 'value="'.$matricule.'"';
                                    }
                                     ?> />
              <!--end::Input-->
              <?php
                          if (isset($error)) {
                                  ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                              } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='d-flex flex-column mb-7 fv-row'>
              <!--begin::Label-->
              <label class='form-label fw-bolder text-dark fs-6'>
                <span class='required'>Sexe</span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name='gender' aria-label='Select a Country' data-control='select2' data-placeholder='Sélectionnez votre sexe...' class='form-select form-select-solid fw-bold'>
                <option>Sélectionnez votre
                  sexe...</option>
                <option value='Feminin'>
                  Feminin
                </option>
                <option value='Masculin'>
                  Masculin
                </option>
              </select>
              <!--end::Input-->
            </div>
            <!--end::Input group-->
            <?php
               if (isset($error)) {
            ?>
            <span class='text-danger'>
              <?php echo $error; ?>
            </span>
            <?php
              } ?>
            <!--begin::Input group-->
            <div class='fv-row mb-7'>
              <!--begin::Label-->
              <label class='form-label fw-bolder text-dark fs-6'>
                <span>Email</span>
                <span class='ms-1' data-bs-toggle='tooltip' title='Votre adresse email doit être active'>
                  <i class='ki-duotone ki-information fs-7'><span class='path1'></span><span class='path2'></span><span class='path3'></span></i>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type='email' class='form-control form-control-solid' placeholder='' name='email' <?php
                                    if (isset($_POST['submit'])) {
                                        echo 'value="'.$email.'"';
                                    }
                                     ?> />
              <!--end::Input-->
              <?php
                if (isset($email_error)) {
              ?>
              <span class='text-danger'>
                <?php echo $email_error; ?>
              </span>
              <?php
               } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='fv-row mb-7'>
              <!--begin::Label-->
              <label class='required form-label fw-bolder text-dark fs-6'>Numéro
                de téléphone</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type='text' class='form-control form-control-solid' placeholder='' name='phone' <?php
                                    if (isset($_POST['submit'])) {
                                        echo 'value="'.$phone.'"';
                                    }
                                     ?> />
              <!--end::Input-->
              <?php
                if (isset($phone_error)) {
              ?>
              <span class='text-danger'>
                <?php echo $phone_error; ?>
              </span>
              <?php
               } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='fv-row mb-15'>
              <!--begin::Label-->
              <label class='required form-label fw-bolder text-dark fs-6'>Date
                de naissance</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type='date' class='form-control form-control-solid' placeholder='' name='birthdate' <?php
                                    if (isset($_POST['submit'])) {
                                        echo 'value="'.$birthdate.'"';
                                    }
                                     ?> />
              <!--end::Input-->
              <?php
                if (isset($error)) {
              ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='d-flex flex-column mb-7 fv-row'>
              <!--begin::Label-->
              <label class='form-label fw-bolder text-dark fs-6'>
                <span class='required'>Pays</span> <span class='ms-1' data-bs-toggle='tooltip' title="Votre pays d'origine">
                  <i class='ki-duotone ki-information fs-7'><span class='path1'></span><span class='path2'></span><span class='path3'></span></i>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name='country' aria-label='Select a Country' data-control='select2' data-placeholder='Sélectionnez votre pays...' class='form-select form-select-solid fw-bold'>
                <option>Sélectionnez votre pays...</option>
                <option value='Afghanistan'>Afghanistan</option>
                <option value='Albania'>Albania</option>
                <option value='Algeria'>Algeria</option>
                <option value='American Samoa'>American Samoa</option>
                <option value='Andorra'>Andorra</option>
                <option value='Angola'>Angola</option>
                <option value='Anguilla'>Anguilla</option>
                <option value='Antartica'>Antarctica</option>
                <option value='Antigua and Barbuda'>Antigua and Barbuda</option>
                <option value='Argentina'>Argentina</option>
                <option value='Armenia'>Armenia</option>
                <option value='Aruba'>Aruba</option>
                <option value='Australia'>Australia</option>
                <option value='Austria'>Austria</option>
                <option value='Azerbaijan'>Azerbaijan</option>
                <option value='Bahamas'>Bahamas</option>
                <option value='Bahrain'>Bahrain</option>
                <option value='Bangladesh'>Bangladesh</option>
                <option value='Barbados'>Barbados</option>
                <option value='Belarus'>Belarus</option>
                <option value='Belgium'>Belgium</option>
                <option value='Belize'>Belize</option>
                <option value='Benin'>Benin</option>
                <option value='Bermuda'>Bermuda</option>
                <option value='Bhutan'>Bhutan</option>
                <option value='Bolivia'>Bolivia</option>
                <option value='Bosnia and Herzegowina'>Bosnia and Herzegowina</option>
                <option value='Botswana'>Botswana</option>
                <option value='Bouvet Island'>Bouvet Island</option>
                <option value='Brazil'>Brazil</option>
                <option value='British Indian Ocean Territory'>British Indian Ocean Territory</option>
                <option value='Brunei Darussalam'>Brunei Darussalam</option>
                <option value='Bulgaria'>Bulgaria</option>
                <option value='Burkina Faso'>Burkina Faso</option>
                <option value='Burundi'>Burundi</option>
                <option value='Cambodia'>Cambodia</option>
                <option value='Cameroon'>Cameroon</option>
                <option value='Canada'>Canada</option>
                <option value='Cape Verde'>Cape Verde</option>
                <option value='Cayman Islands'>Cayman Islands</option>
                <option value='Central African Republic'>Central African Republic</option>
                <option value='Chad'>Chad</option>
                <option value='Chile'>Chile</option>
                <option value='China'>China</option>
                <option value='Christmas Island'>Christmas Island</option>
                <option value='Cocos Islands'>Cocos ( Keeling ) Islands</option>
                <option value='Colombia'>Colombia</option>
                <option value='Comoros'>Comoros</option>
                <option value='Congo'>Congo</option>
                <option value='Congo'>Congo, the Democratic Republic of the</option>
                <option value='Cook Islands'>Cook Islands</option>
                <option value='Costa Rica'>Costa Rica</option>
                <option value="Cota D'Ivoire">Cote d'Ivoire</option>
                <option value="Croatia">Croatia (Hrvatska)</option>
                <option value="Cuba">Cuba</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="East Timor">East Timor</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands">Falkland Islands (Malvinas)</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="France Metropolitan">France, Metropolitan</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Territories">French Southern Territories</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guinea">Guinea</option>
                <option value="Guinea-Bissau">Guinea-Bissau</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
                <option value="Holy See">Holy See (Vatican City State)</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="India">India</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Iran">Iran (Islamic Republic of)</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Democratic People's Republic of Korea">Korea, Democratic People's
                  Republic of
                </option>
                <option value="Korea">Korea, Republic of</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Lao">Lao People's Democratic Republic</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macau">Macau</option>
                <option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malawi">Malawi</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Micronesia">Micronesia, Federated States of</option>
                <option value="Moldova">Moldova, Republic of</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Namibia">Namibia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherlands">Netherlands</option>
                <option value="Netherlands Antilles">Netherlands Antilles</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau">Palau</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Philippines">Philippines</option>
                <option value="Pitcairn">Pitcairn</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russia">Russian Federation</option>
                <option value="Rwanda">Rwanda</option>
                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                <option value="Saint LUCIA">Saint LUCIA</option>
                <option value="Saint Vincent">Saint Vincent and the Grenadines</option>
                <option value="Samoa">Samoa</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovakia">Slovakia (Slovak Republic)</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="South Georgia">South Georgia and the South Sandwich Islands</option>
                <option value="Span">Spain</option>
                <option value="SriLanka">Sri Lanka</option>
                <option value="St. Helena">St. Helena</option>
                <option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Svalbard">Svalbard and Jan Mayen Islands</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syria">Syrian Arab Republic</option>
                <option value="Taiwan">Taiwan, Province of China</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania">Tanzania, United Republic of</option>
                <option value="Thailand">Thailand</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey">Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks and Caicos">Turks and Caicos Islands</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Emirates">United Arab Emirates</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
                <option value="United States Minor Outlying Islands">United States Minor Outlying
                  Islands</option>
                <option value="Uruguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnam">Viet Nam</option>
                <option value="Virgin Islands ( British )">Virgin Islands (British)</option>
                <option value="Virgin Islands ( U.S )">Virgin Islands (U.S.)</option>
                <option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
                <option value="Western Sahara">Western Sahara</option>
                <option value="Yemen">Yemen</option>
                <option value="Serbia">Serbia</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
              </select>
              <!--end::Input-->
              <?php
                     if (isset($error)) {
                         ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                     } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required">Profil</span> <span class='ms-1' data-bs-toggle='tooltip' title="Choississez le profile de l' utilisateur">
                  <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="profile" aria-label="Select a Country" data-control="select2" data-placeholder="Sélectionnez le profile..." class="form-select form-select-solid fw-bold">
                <option>Sélectionnez le
                  profil...</option>
                <option value="Admin">
                  Administrateur
                </option>
                <option value="Manager (à évaluer)">
                  Manager (à évaluer)
                </option>
                <option value="Manager (non évalué)">
                  Manager (non évalué)
                </option>
                <option value="Technicien">
                  Technicien
                </option>
              </select>
              <!--end::Input-->
              <?php
                     if (isset($error)) {
                         ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                     } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row hidden" id="metier">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required">Niveau</span> <span class="ms-1" data-bs-toggle="tooltip" title="Choississez le niveau du technicien ou du manager">
                  <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="level" aria-label="Select a Country" data-control="select2" data-placeholder="Sélectionnez le niveau..." class="form-select form-select-solid fw-bold">
                <option>Sélectionnez le
                  niveau...</option>
                <option value="Junior">
                  Junior
                </option>
                <option value="Senior">
                  Senior
                </option>
                <option value="Expert">
                  Expert
                </option>
              </select>
              <!--end::Input-->
              <?php
                     if (isset($error)) {
                         ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                     } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required form-label fw-bolder text-dark fs-6">Certificat plus élévé</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="text" class="form-control form-control-solid" placeholder="" name="certificate" <?php
                                    if (isset($_POST['submit'])) {
                                        echo 'value="'.$certificate.'"';
                                    }
                                     ?> />
              <!--end::Input-->
              <?php
                     if (isset($error)) {
                         ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                     } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required form-label fw-bolder text-dark fs-6">Filiale</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="text" class="form-control form-control-solid" placeholder="" name="subsidiary" <?php
                                    if (isset($_POST['submit'])) {
                                        echo 'value="'.$subsidiary.'"';
                                    }
                                     ?> />
              <!--end::Input-->
              <?php
                     if (isset($error)) {
                         ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                     } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required">Département</span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="department" aria-label="Select a Country" data-control="select2" data-placeholder="Sélectionnez votre département..." class="form-select form-select-solid fw-bold">
                <option>Sélectionnez votre département...</option>
                <option value="Equipment">
                  Equipment
                </option>
                <option value="Motors">
                  Motors
                </option>
              </select>
              <!--end::Input-->
              <?php
                     if (isset($error)) {
                         ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                     } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required form-label fw-bolder text-dark fs-6">Fonction</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="text" class="form-control form-control-solid fw-bold" placeholder="" name="role" <?php
                                    if (isset($_POST['submit'])) {
                                        echo 'value="'.$role.'"';
                                    }
                                     ?> />
              <!--end::Input-->
              <?php
                     if (isset($error)) {
                         ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                     } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required">Type de vehicule</span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="vehicle" aria-label="Select a Country" data-control="select2" data-placeholder="Sélectionnez votre type de vehicule principal..." class="form-select form-select-solid fw-bold">
                <option value="">Sélectionnez votre type de vehicule principal...</option>
                <option value="Bus">
                  Bus
                </option>
                <option value="Camions">
                  Camions
                </option>
                <option value="Chariots">
                  Chariots
                </option>
                <option value="Engins">
                  Engins
                </option>
                <option value="Voitures">
                  Voitures
                </option>
              </select>
              <!--end::Input-->
              <?php
                     if (isset($error)) {
                         ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                     } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required">Marque de véhicule principal</span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="brand" aria-label="Select a Country" data-control="select2" data-placeholder="Sélectionnez la marque de véhicule principal..." class="form-select form-select-solid fw-bold">
                <option value="">Sélectionnez la marque de véhicule principal...</option>
                <option value="CITROEN">
                  CITROEN
                </option>
                <option value="FUSO">
                  FUSO
                </option>
                <option value="HINO">
                  HINO
                </option>
                <option value="JCB">
                  JCB
                </option>
                <option value="KING LONG">
                  KING LONG
                </option>
                <option value="MERCEDES">
                  MERCEDES
                </option>
                <option value="MERCEDES TRUCK">
                  MERCEDES TRUCK
                </option>
                <option value="RENAULT TRUCK">
                  RENAULT TRUCK
                </option>
                <option value="PEUGEOT">
                  PEUGEOT
                </option>
                <option value="SINOTRUCK">
                  SINOTRUCK
                </option>
                <option value="SUZUKI">
                  SUZUKI
                </option>
                <option value="TOYOTA">
                  TOYOTA
                </option>
                <option value="TOYOTA BT">
                  TOYOTA BT
                </option>
                <option value="TOYOTA FORFLIT">
                  TOYOTA FORFLIT
                </option>
              </select>
              <!--end::Input-->
              <?php
                     if (isset($error)) {
                         ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                     } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="">Marque de vehicule secondaire</span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="subBrand[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="Sélectionnez votre type de vehicule secondaire..." class="form-select form-select-solid fw-bold">
                <option value="">Sélectionnez votre marque de vehicule secondaire...</option>
                <option value="CITROEN">
                  CITROEN
                </option>
                <option value="FUSO">
                  FUSO
                </option>
                <option value="HINO">
                  HINO
                </option>
                <option value="JCB">
                  JCB
                </option>
                <option value="KING LONG">
                  KING LONG
                </option>
                <option value="MERCEDES">
                  MERCEDES
                </option>
                <option value="MERCEDES TRUCK">
                  MERCEDES TRUCK
                </option>
                <option value="RENAULT TRUCK">
                  RENAULT TRUCK
                </option>
                <option value="PEUGEOT">
                  PEUGEOT
                </option>
                <option value="SINOTRUCK">
                  SINOTRUCK
                </option>
                <option value="SUZUKI">
                  SUZUKI
                </option>
                <option value="TOYOTA">
                  TOYOTA
                </option>
                <option value="TOYOTA BT">
                  TOYOTA BT
                </option>
                <option value="TOYOTA FORFLIT">
                  TOYOTA FORFLIT
                </option>
              </select>
              <!--end::Input-->
              <?php
                     if (isset($error)) {
                         ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                     } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required form-label fw-bolder text-dark fs-6">Date
                de recrutement</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="date" class="form-control form-control-solid" placeholder="" name="recrutmentDate" <?php
                                    if (isset($_POST['submit'])) {
                                        echo 'value="'.$recrutmentDate.'"';
                                    }
                                     ?> />
              <!--end::Input-->
              <?php
                     if (isset($error)) {
                         ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                     } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-10 fv-row" data-kt-password-meter="true">
              <!--begin::Wrapper-->
              <div class="mb-1">
                <!--begin::Label-->
                <label class="required form-label fw-bolder text-dark fs-6">Mot
                  de
                  passe</label>
                <!--end::Label-->
                <!--begin::Input wrapper-->
                <div class="position-relative mb-3">
                  <input class="form-control form-control-solid" type="password" placeholder="" name="password" autocomplete="off" />
                </div>
                <!--end::Input wrapper-->
                <?php
                if (isset($password_error)) {
                    ?>
                <span class="text-danger">
                  <?php echo $password_error; ?>
                </span>
                <?php
                } ?>
                <?php
                     if (isset($error)) {
                         ?>
                <span class='text-danger'>
                  <?php echo $error; ?>
                </span>
                <?php
                     } ?>
                <!--begin::Meter-->
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                  </div>
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                  </div>
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                  </div>
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px">
                  </div>
                </div>
                <!--end::Meter-->
              </div>
              <!--end::Wrapper-->
              <!--begin::Hint-->
              <div class="text-muted">Utilisez 6
                caractères ou plus avec un mélange de
                lettres en majuscule et minuscule
                &amp; de chiffres.</div>
              <!--end::Input group-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required">Manager</span>
                <span class="ms-1" data-bs-toggle="tooltip" title="Choississez le manager de cet technicien et uniquement quand le profil est technicien">
                  <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                </span>
              </label>
              <select name="manager" aria-label="Select a Country" data-control="select2" data-placeholder="Sélectionnez votre manager..." class="form-select form-select-solid fw-bold">
                <option value="">Sélectionnez votre
                  manager...</option>
                <?php
                                    $manager = $users->find([
                                        '$and' => [
                                            ['profile' => 'Manager'],
                                            ['active' => true],
                                        ],
                                    ]);
                                    foreach ($manager as $manager) {
                                    ?>
                <option value='<?php echo $manager->_id; ?>'>
                  <?php echo $manager->firstName; ?> <?php echo $manager->lastName; ?>
                </option>
                <?php
                                    } ?>
              </select>
              <?php
                                if (isset($error)) {
                                    ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php
                    } ?>
            </div>
            <!--end::Input group-->
            <!--end::Scroll-->
            <!--end::Modal body-->
            <!--begin::Modal footer-->
            <div class=" modal-footer flex-center">
              <!--begin::Button-->
              <button type="submit" name="submit" class="btn btn-primary">
                <span class="indicator-label">
                  Valider
                </span>
                <span class="indicator-progress">
                  Patientez... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
              </button>
              <!--end::Button-->
            </div>
            <!--end::Modal footer-->
          </div>
        </form>
      </div>
      <!--end::Modal body-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::Post-->
</div>
<!--end::Body-->
<?php
include './partials/footer.php'; ?>
<?php
} ?>

<script>

</script>