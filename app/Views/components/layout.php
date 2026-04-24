<!DOCTYPE html>
<html lang="zxx" class="js">

<head>    
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="./images/LOGO 5.png">
    <!-- Page Title  -->
    <title>My Photo</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="../dashlite/assets/css/dashlite.css?ver=3.2.3">
    <link id="skin-default" rel="stylesheet" href="../dashlite/assets/css/theme.css?ver=3.2.3">

    <style>
        .avatar {
            width: 32px;
            height: 32px;
            background-color: #e57373; /* Adjust this color as needed */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: bold;
            font-family: Arial, sans-serif;
            text-transform: uppercase; /* Ensures the initial is always capitalized */
        }

    </style>
</head>

<body class="nk-body ui-rounder has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <?= $this->include('components/sidebar') ?>
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- main header @s -->
                <?= $this->include('components/header') ?>
                <!-- main header @e -->

                <!-- content @s -->
                <div class="nk-content nk-content-fluid">
                    <div class="container-xl wide-xl">
                        <div class="nk-content-body">
                            <?= $this->renderSection('content'); ?>                                  
                        </div>
                    </div>
                </div>
                <!-- content @e -->
                <!-- footer @s -->
                <?= $this->include('components/footer') ?>
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->    
    <!-- JavaScript -->
    <script src="../dashlite/assets/js/bundle.js?ver=3.1.3"></script>
    <script src="../dashlite/assets/js/scripts.js?ver=3.1.3"></script>
    <script src="../dashlite/assets/js/charts/gd-default.js?ver=3.1.3"></script>    
    
</body>
<?= $this->renderSection('js') ?>
</html>