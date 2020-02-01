<?php 

$plugins = array();

// Reading plugins folder names

foreach(glob('wp-content/plugins/*', GLOB_ONLYDIR) as $dir) {
    $dir = str_replace('wp-content/plugins/', '', $dir);
    array_push($plugins, $dir);
}


$download_base_url = "https://downloads.wordpress.org/plugin/";

$done = false;

if (!empty($_POST["plugins"])) {
    $done = true;
}

if (isset($_POST['plugins'])) 
{
    foreach ($_POST['plugins']  as $plugin) {

            // Downloading the plugin from wp.org repo. If plugin is premium and does not exist, skip.

            $plugin_zip = @fopen($download_base_url . $plugin . ".zip", 'r');

            // Moving .zip to plugins folder
      
            file_put_contents( __DIR__ . "/wp-content/plugins/" . $plugin . ".zip", $plugin_zip);

            $file =  __DIR__ . "/wp-content/plugins/" . $plugin . ".zip";
    
            $path = pathinfo(realpath($file), PATHINFO_DIRNAME);

            // Unzip the plugin
    
            $zip = new ZipArchive;
            $res = $zip->open($file);
            if ($res === TRUE) {
                $zip->extractTo($path);
                $zip->close();
            }

            // Delete the .zip
    
            unlink( __DIR__ . "/wp-content/plugins/" . $plugin . ".zip");
        }
     }

     $_POST = array();
     unset($_POST);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WordPress Plugin Reinstall</title>
</head>

<body>

    <form action="./reinstall.php" method="POST">
        <div class="plugins-wrap">
            <?php foreach ($plugins as $plugin) { ?>
            <input class="plugin-checkbox" id="<?php echo $plugin; ?>" type="checkbox" value="<?php echo $plugin; ?>"
                name="plugins[]">
            <label for="<?php echo $plugin; ?>"><?php echo str_replace("-"," ",$plugin); ?></label>
            <?php } ?>
        </div>

        <button type="submit">REINSTALL</button>
    </form>
    <?php
        if ($done === true) { ?>
    <div class="done">
        <p style="font-variant: small-caps;">reinstall completed</p>
    </div>
    <?php } ?>
</body>

<style>
@import url('https://fonts.googleapis.com/css?family=Montserrat&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Montserrat', sans-serif;
    background: #21272C;
    background-blend-mode: multiply;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    min-height: 100vh;
}

.plugins-wrap {
    align-items: center;
    justify-items: center;
    width: auto;
    height: auto;
    background-color: none;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-column-gap: 20px;
    grid-row-gap: 20px;
}

.plugin-checkbox {
    position: absolute;
    opacity: 0;

}

.plugin-checkbox:checked+label {
    box-shadow:
        inset -3px -3px 5px 2px #272E35,
        inset 3px 3px 5px 2px #1D2328;
    font-size: 1.25em;
}

.plugin-checkbox+label {
    text-transform: capitalize;
    transition-duration: 0.2s;
    width: 300px;
    min-height: 100px;
    background-color: #21272C;
    border-radius: 8px;
    cursor: pointer;
    text-align: center;
    display: flex;

    align-items: center;
    justify-content: center;
    box-shadow:
        -3px -3px 5px 2px #272E35,
        3px 3px 5px 2px #1D2328;
    font-size: 1.3em;
    color: #e6e6e6;
    user-select: none;
}

form {
    text-align: center;
    padding: 60px;
    border-radius: 8px;
    box-shadow:
        -3px -3px 5px 2px #272E35,
        3px 3px 5px 2px #1D2328;
}

form button {
    background-color: #21272C;
    border-radius: 8px;
    cursor: pointer;
    box-shadow:
        -3px -3px 5px 2px #272E35,
        3px 3px 5px 2px #1D2328;
    padding: 5px 20px;
    border: none;
    font-family: 'Montserrat', sans-serif;
    font-size: 1.2em;
    color: #e6e6e6;
    padding: 10px 20px;

    letter-spacing: 2px;
    margin-top: 40px;
    outline: none;
    transition-duration: 0.2s;
}

form button:active {
    box-shadow:
        inset -3px -3px 5px 2px #272E35,
        inset 3px 3px 5px 2px #1D2328;

}

.done {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.done p {
    font-size: 3em;
    color: green;
    font-family: 'Montserrat', sans-serif;
    font-weight: bold;

}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    var doneDiv = document.querySelector(".done");
    if (doneDiv) {
        doneDiv.addEventListener("click", e => {
            doneDiv.remove();
        })
    }

}, false);
</script>

</html>
