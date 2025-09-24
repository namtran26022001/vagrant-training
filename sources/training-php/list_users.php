<?php
// Start the session
session_start();
if (empty($_SESSION['csrf-token'])) {
    $_SESSION['csrf-token'] = bin2hex(random_bytes(32));
}

if (isset($_SESSION['new_token'])) {
    $token = $_SESSION['new_token'];
    unset($_SESSION['new_token']); // xài xong thì xoá
    echo "<script src='auth_token.js'></script>";
    echo "<script>saveToken('$token');</script>";
}

require_once 'models/UserModel.php';
$userModel = new UserModel();

$params = [];
if (!empty($_GET['keyword'])) {
    $params['keyword'] = $_GET['keyword'];
}

$users = $userModel->getUsers($params);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <?php include 'views/meta.php' ?>
</head>

<body>
    <?php include 'views/header.php' ?>
    <div class="container">
        <?php if (!empty($users)) { ?>
            <div class="alert alert-warning" role="alert">
                List of users! <br>
                Hacker: http://php.local/list_users.php?keyword=ASDF%25%22%3BTRUNCATE+banks%3B%23%23
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">Type</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <th scope="row"><?php echo $user['id'] ?></th>
                            <td>
                                <?php echo $user['name'] ?>
                            </td>
                            <td>
                                <?php echo $user['fullname'] ?>
                            </td>
                            <td>
                                <?php echo $user['type'] ?>
                            </td>
                            <td>
                                <!-- Update Form -->
                                <form action="form_user.php" method="get" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                                    <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token'] ?>">
                                    <button type="submit" style="background:none;border:none;padding:0;">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true" title="Update"></i>
                                    </button>
                                </form>
                                <!-- View Form -->
                                <form action="view_user.php" method="get" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                                    <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token'] ?>">
                                    <button type="submit" style="background:none;border:none;padding:0;">
                                        <i class="fa fa-eye" aria-hidden="true" title="View"></i>
                                    </button>
                                </form>
                                <!-- Delete Form -->
                                <form action="delete_user.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                                    <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token'] ?>">
                                    <button type="submit" style="background:none;border:none;padding:0;">
                                        <i class="fa fa-eraser" aria-hidden="true" title="Delete"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-dark" role="alert">
                This is a dark alert—check it out!
            </div>
        <?php } ?>
    </div>
</body>

</html>