<?php
session_start();
include_once "../language.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION["profile"])) {
    header("Location: ../../");
    exit();
}

require_once "../../vendor/autoload.php";
$conn = new MongoDB\Client("mongodb://localhost:27017");
$academy = $conn->academy;
$users = $academy->users;
$allocations = $academy->allocations;
$connections = $academy->connections;

function canSeeColumn($columnProfiles) {
    return in_array($_SESSION["profile"], $columnProfiles);
}

function isVisible($user) {
    $profile = $_SESSION["profile"];
    $subsidiary = $_SESSION["subsidiary"];
    $department = $_SESSION["department"] ?? null;

    if ($profile === "Admin") {
        return !in_array($user["profile"], ["Admin", "Directeur Pièce et Service", "Directeur des Opérations", "Directeur Groupe", "Super Admin"]) && $user["subsidiary"] === $subsidiary;
    }
    if ($profile === "Ressource Humaine") {
        return !in_array($user["profile"], ["Ressource Humaine", "Directeur Pièce et Service", "Directeur des Opérations", "Directeur Groupe", "Super Admin", "Admin"]) && $user["subsidiary"] === $subsidiary;
    }
    if ($profile === "Super Admin") {
        return $user["profile"] !== "Super Admin";
    }
    if ($profile === "Directeur Groupe") {
        return $user["profile"] !== "Super Admin" && $user["profile"] !== "Directeur Groupe";
    }
    if (in_array($profile, ["Directeur Pièce et Service", "Directeur des Opérations"])) {
        return !in_array($user["profile"], ["Directeur Pièce et Service", "Directeur des Opérations", "Directeur Groupe", "Super Admin"]) && $user["subsidiary"] === $subsidiary;
    }
    return false;
}

if (isset($_POST["excel"])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $headers = ["username", "matricule", "firstName", "lastName", "email", "phone", "gender", "birthdate", "level", "country", "profile", "certificate", "subsidiary", "department", "role", "recrutmentDate", "visiblePassword"];
    $labels = ["Nom d'utilisateur", "Matricule", "Prénoms", "Noms", "Email", "Numéro de téléphone", "Sexe", "Date de naissance", "Niveau technique", "Pays", "Profil", "Diplôme", "Filiale", "Département", "Fonction", "Date de recrutement", "Mot de Passe"];
    foreach ($labels as $index => $label) {
        $sheet->setCellValueByColumnAndRow($index + 1, 1, $label);
    }
    $rows = $users->find();
    $i = 2;
    foreach ($rows as $user) {
        foreach ($headers as $j => $field) {
            $sheet->setCellValueByColumnAndRow($j + 1, $i, $user[$field] ?? '');
        }
        $i++;
    }
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=Utilisateurs.xlsx");
    header("Cache-Control: max-age=0");
    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");
    exit();
}

$columns = [
    'prenomsNoms' => ['label' => $prenomsNoms, 'profiles' => ['Admin', 'Super Admin', 'Directeur Groupe', 'Directeur Pièce et Service', 'Directeur des Opérations']],
    'email' => ['label' => $email, 'profiles' => ['Admin']],
    'pays' => ['label' => $pays, 'profiles' => ['Super Admin', 'Directeur Groupe', 'Directeur Pièce et Service', 'Directeur des Opérations']],
    'profil' => ['label' => $profil, 'profiles' => ['Admin', 'Super Admin', 'Directeur Groupe', 'Directeur Pièce et Service', 'Directeur des Opérations']],
    'department' => ['label' => $department, 'profiles' => ['Admin', 'Super Admin', 'Directeur Groupe', 'Directeur Pièce et Service', 'Directeur des Opérations']],
    'levelTech' => ['label' => $levelTech, 'profiles' => ['Admin', 'Super Admin', 'Directeur Groupe', 'Directeur Pièce et Service', 'Directeur des Opérations']],
    'username' => ['label' => $username, 'profiles' => ['Admin', 'Super Admin']],
    'Password' => ['label' => $Password, 'profiles' => ['Admin', 'Super Admin']],
    'status' => ['label' => $status, 'profiles' => ['Super Admin']],
    'title_collaborators' => ['label' => $title_collaborators, 'profiles' => ['Admin', 'Super Admin', 'Directeur Groupe', 'Directeur Pièce et Service', 'Directeur des Opérations']]
];

$query = ['active' => true];
if (!in_array($_SESSION["profile"], ["Super Admin", "Directeur Groupe"]) && $_SESSION["department"] !== 'Equipment & Motors') {
    $query['department'] = $_SESSION["department"];
}
$usersCursor = $users->find($query);

include_once "partials/header.php";
?>
<!--begin::Title-->
<title><?php echo $list_user ?> | CFAO Mobility Academy</title>
<!--end::Title-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <h1 class="text-dark fw-bolder my-1 fs-1">
                    <?php echo $list_user ?>
                </h1>
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"></i>
                        <input type="text" id="search" class="form-control form-control-solid w-250px ps-12" placeholder="<?php echo $recherche ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <div class="container-xxl">
            <div class="card">
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable" id="kt_customers_table">
                            <thead>
                                <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2 sorting_disabled"></th>
                                    <?php foreach ($columns as $col): if (canSeeColumn($col['profiles'])): ?>
                                        <th><?php echo $col['label']; ?></th>
                                    <?php endif; endforeach; ?>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600" id="table">
                                <?php
                                // Collect modals to output after the table
                                $modals = '';
                                foreach ($usersCursor as $user):
                                    if (isVisible($user)):
                                ?>
                                    <tr>
                                        <td></td>
                                        <?php if (canSeeColumn($columns['prenomsNoms']['profiles'])): ?>
                                            <td><?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></td>
                                        <?php endif; ?>
                                        <?php if (canSeeColumn($columns['email']['profiles'])): ?>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <?php endif; ?>
                                        <?php if (canSeeColumn($columns['pays']['profiles'])): ?>
                                            <td><?php echo htmlspecialchars($user['country']); ?></td>
                                        <?php endif; ?>
                                        <td><?php echo ($user['profile'] == 'Manager' && !empty($user['test'])) ? 'Manager - Technicien' : htmlspecialchars($user['profile']); ?></td>
                                        <td><?php echo htmlspecialchars($user['department']); ?></td>
                                        <td><?php echo htmlspecialchars($user['level']); ?></td>
                                        <?php if (canSeeColumn($columns['username']['profiles'])): ?>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <?php endif; ?>
                                        <?php if (canSeeColumn($columns['Password']['profiles'])): ?>
                                            <td><?php echo htmlspecialchars($user['visiblePassword']); ?></td>
                                        <?php endif; ?>
                                        <?php if (canSeeColumn($columns['status']['profiles'])): ?>
                                            <?php $status = $connections->findOne(['user' => new MongoDB\BSON\ObjectId($user['_id']), 'status' => 'Online']); ?>
                                            <td><span class="badge <?php echo isset($status) ? 'badge-light-success' : 'badge-light-danger'; ?> fs-7 m-1"><?php echo isset($status) ? 'Online' : 'Offline'; ?></span></td>
                                        <?php endif; ?>
                                        <?php if ($user['profile'] === 'Manager' && canSeeColumn($columns['title_collaborators']['profiles'])): ?>
                                            <td><a href="#" class="btn btn-light btn-active-light-primary text-primary fw-bolder btn-sm" data-bs-toggle="modal" data-bs-target="#modal_<?php echo $user['_id']; ?>"><?php echo $voir_collab; ?></a></td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php
                                    if ($user['profile'] === 'Manager' && canSeeColumn($columns['title_collaborators']['profiles'])):
                                        $collaborators = $users->find([
                                            '_id' => ['$in' => $user['users'] ?? []],
                                            'active' => true
                                        ]);
                                        ob_start();
                                    ?>
                                    <div class="modal fade" id="modal_<?php echo $user['_id']; ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header pb-0 border-0 justify-content-end">
                                                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1"></i>
                                                    </div>
                                                </div>
                                                <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                                                    <div class="text-center mb-13">
                                                        <h1 class="mb-3"><?php echo $title_collaborators ?></h1>
                                                    </div>
                                                    <div class="mb-10">
                                                        <div class="mh-300px scroll-y me-n7 pe-7">
                                                            <?php foreach ($collaborators as $collab): ?>
                                                                <div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="ms-5">
                                                                            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">
                                                                                <?php echo $collab['firstName'] . ' ' . $collab['lastName']; ?>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        $modals .= ob_get_clean();
                                    endif;
                                    ?>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// Output all modals after the table
echo $modals;
?>
<?php include_once "partials/footer.php"; ?>
<script src="https://code.jquery.com/jquery-3.6.3.js"></script>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<script src="../../public/js/main.js"></script>
<script>
$(document).ready(function() {
    $("#excel").on("click", function() {
        TableToExcel.convert(document.querySelector("table"), { name: "Users.xlsx" });
    });
});
</script> 