<?php
include 'common_vars.inc';
require('res/translations/bg.php'); // TODO: Change when switching languages
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $messages['home_files']; ?> - HomeVault</title>
    <!-- TODO: Switch to local instead of CDN cause Seray would be mad otherwise; 
         TODO 2: Add a common header -->
    <link rel="stylesheet" href="res/stylesheets/bootstrap.min.css"> 
    <link rel="stylesheet" href="res/stylesheets/main.css?v=3">
    <style type="text/css">
        .body-overlay {
            background: url('res/drawables/homevault_default_backdrop.jpg') no-repeat center center fixed; 
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            padding-top: 20px;
            width: 100vw;
            height: 100vh;
        }
        .toolbar { 
            width: calc(100vw - 60px);
            margin-left: 30px;
            margin-right: 30px;
        }
        .main-wrapper { 
            width: calc(100vw - 60px);
            margin-left: 30px;
            margin-right: 30px;
            margin-top: 20px;
            height: calc(100vh - 150px);
            display: flex;
            flex-flow: column;
        }
        .form-group {
            margin-top: 30px;
        }
        input[type="button"] {
            padding-left: 18px;
            padding-right: 18px;
            border-radius: 100px;
        }
    </style>
</head>
<body>
<div class="body-overlay">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true) {
    header('location: login.php');
}

if (!empty($_FILES['file']['name'])) {
    $filecount = count($_FILES['file']['name']);
   
    // Looping through multiple uploaded files
    for ($i=0; $i<$filecount; $i++) {
        $filename = $_FILES['file']['name'][$i];
        if (file_exists($_SESSION["folder_loc"] . '/files' . '/' . $filename)) {
            $path_parts = pathinfo($filename);
            $filename = $path_parts['filename'] . '_' . date('Y-m-d_H-i-s') . '.' . $path_parts['extension'];
        }
        move_uploaded_file($_FILES['file']['tmp_name'][$i], $_SESSION["folder_loc"] . '/files' . '/' . $filename);
        //echo '<p>' . $_SESSION["folder_loc"] . '/files' . '/' . $filename . '</p>';
    }
} 

if (isset($_POST['new_folder']) && !empty($_POST['new_folder'])) {
    mkdir($_SESSION["folder_loc"] . '/files' . '/' . $_POST['new_folder']);
}
?>
    <div class="toolbar popout-card">
        <div class="left-action"><img src="res/drawables/md_long_hamburger_button.svg"></div>
        <div class="right-action"><a href="logout.php"><img src="res/drawables/md_user_circle.svg"></a></div>
        <div class="center-logo"><img src="res/drawables/homevault_logo_big.svg"></div>
    </div>  
    <div class="main-wrapper popout-card">
        <div class="center-elements">
            <form id="create_folder" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="new_folder" id="new_folder">
                <!-- The button is in the next form in order to be on the same line -->
            </form>
            <form id="file_upload" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="file[]" id="file" multiple hidden>
                <!--<input type="button" id="add_folder" class="btn btn-primary" value="<?php echo $messages['create_folder']; ?>" style="margin-right: 10px;">
                <input type="button" id="upload_overlay" class="btn btn-primary" value="<?php echo $messages['upload_file']; ?>">-->
            </form>
        </div>
        <iframe src="file_manager.php" allowtransparency="true" frameBorder="0" style="flex: 1; width: 100%;"></iframe>
    </div>  
    <script>
    $(document).ready(function() {
        $('body').css('display', 'none');
        $('body').fadeIn(600);
        $("#file").change(function(){
            document.getElementById("file_upload").submit();
        });
    });
    document.getElementById('upload_overlay').addEventListener('click', openDialog);
    document.getElementById('add_folder').addEventListener('click', createFolder);

    function openDialog() {
        document.getElementById('file').click();
    }
    function createFolder() {
        var folder_name = prompt('<?php echo $messages['create_folder_name_desc']; ?>', '<?php echo $messages['create_folder']; ?>'); // TODO: Get rid of this js prompt cause it's just lazy
        if (folder_name != null) {
            document.getElementById("new_folder").value = folder_name;
            document.getElementById("create_folder").submit();
        }
    }

    </script>    
</div>
</body>
</html>