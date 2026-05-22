<?php
session_start();
require 'include/dbcon.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM user WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "User not found.";
    exit();
}

$user = $result->fetch_assoc();
$profilePic = (!empty($user['image']) && file_exists($user['image'])) ? $user['image'] : 'default-profile.jpg';

$page_title = 'Profile Settings - Book Hub';
include 'include/header.php';
?>

<main style="padding: 30px; max-width: 700px; margin: 0 auto;">

    <!-- Header Row -->
    <div style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 30px;
    ">
        <h2 style="margin: 0; font-size: 1.8rem;">⚙️ Profile Settings</h2>
        <a href="profile.php" style="
            padding: 9px 18px;
            background: #444;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        ">← Back to Profile</a>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div style="padding: 12px 16px; margin-bottom: 20px; border-radius: 6px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; font-weight: 500;">
            ✅ <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="padding: 12px 16px; margin-bottom: 20px; border-radius: 6px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; font-weight: 500;">
            ❌ <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['info'])): ?>
        <div style="padding: 12px 16px; margin-bottom: 20px; border-radius: 6px; background: #cce7ff; color: #0c5460; border: 1px solid #b8daff; font-weight: 500;">
            ℹ️ <?= htmlspecialchars($_SESSION['info']) ?>
        </div>
        <?php unset($_SESSION['info']); ?>
    <?php endif; ?>

    <!-- Form -->
    <div style="
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    ">
        <form action="update-profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">

            <!-- Profile Picture -->
            <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 30px;">
                <div style="position: relative; width: 130px; height: 130px;">
                    <img
                        id="profile-pic"
                        src="<?= htmlspecialchars($profilePic) ?>"
                        alt="Profile Picture"
                        style="
                            width: 130px;
                            height: 130px;
                            object-fit: cover;
                            border-radius: 50%;
                            border: 4px solid #1abc9c;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                            display: block;
                        "
                    >
                    <button
                        type="button"
                        onclick="document.getElementById('profile-image').click()"
                        style="
                            position: absolute;
                            bottom: 0;
                            right: 0;
                            background: #1abc9c;
                            border: none;
                            border-radius: 50%;
                            width: 38px;
                            height: 38px;
                            color: white;
                            font-size: 18px;
                            cursor: pointer;
                            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
                        "
                    >📷</button>
                    <input
                        type="file"
                        name="profile_image"
                        id="profile-image"
                        accept="image/*"
                        onchange="previewImage(event)"
                        style="display: none;"
                    >
                </div>
                <p style="margin-top: 10px; font-size: 13px; color: #999;">Click the camera icon to change photo</p>
            </div>

            <!-- Fields -->
            <?php
            $fields = [
                ['id' => 'username', 'label' => 'Username',     'type' => 'text',     'value' => $user['username'] ?? '', 'required' => true],
                ['id' => 'name',     'label' => 'Full Name',    'type' => 'text',     'value' => $user['name']     ?? '', 'required' => true],
                ['id' => 'email',    'label' => 'Email',        'type' => 'email',    'value' => $user['email']    ?? '', 'required' => true],
                ['id' => 'nic',      'label' => 'NIC',          'type' => 'text',     'value' => $user['nic']      ?? '', 'required' => false],
                ['id' => 'contact',  'label' => 'Phone Number', 'type' => 'text',     'value' => $user['contact']  ?? '', 'required' => false],
                ['id' => 'address',  'label' => 'Address',      'type' => 'text',     'value' => $user['address']  ?? '', 'required' => false],
            ];
            foreach ($fields as $f): ?>
                <div style="margin-bottom: 18px;">
                    <label for="<?= $f['id'] ?>" style="display: block; font-weight: 600; margin-bottom: 6px; color: #333; font-size: 14px;">
                        <?= $f['label'] ?>
                    </label>
                    <input
                        type="<?= $f['type'] ?>"
                        id="<?= $f['id'] ?>"
                        name="<?= $f['id'] ?>"
                        value="<?= htmlspecialchars($f['value']) ?>"
                        <?= $f['required'] ? 'required' : '' ?>
                        style="
                            width: 100%;
                            padding: 10px 12px;
                            font-size: 14px;
                            border: 1.5px solid #ccc;
                            border-radius: 6px;
                            box-sizing: border-box;
                            transition: border-color 0.2s;
                        "
                        onfocus="this.style.borderColor='#1abc9c'"
                        onblur="this.style.borderColor='#ccc'"
                    >
                </div>
            <?php endforeach; ?>

            <!-- New Password -->
            <div style="margin-bottom: 25px;">
                <label for="password" style="display: block; font-weight: 600; margin-bottom: 6px; color: #333; font-size: 14px;">
                    New Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Leave blank to keep current password"
                    style="
                        width: 100%;
                        padding: 10px 12px;
                        font-size: 14px;
                        border: 1.5px solid #ccc;
                        border-radius: 6px;
                        box-sizing: border-box;
                    "
                    onfocus="this.style.borderColor='#1abc9c'"
                    onblur="this.style.borderColor='#ccc'"
                >
            </div>

            <!-- Submit -->
            <button type="submit" style="
                width: 100%;
                padding: 13px;
                background: #1abc9c;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 700;
                cursor: pointer;
                transition: background 0.2s;
            "
                onmouseover="this.style.background='#16a085'"
                onmouseout="this.style.background='#1abc9c'"
            >
                💾 Save Changes
            </button>

        </form>
    </div>

</main>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function () {
            document.getElementById("profile-pic").src = reader.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>

<?php include 'include/footer.php'; ?>