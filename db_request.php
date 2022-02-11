<?php



// $host = "localhost";
// $db = "dz_file_upload_db";
// $user = "root";
// $pwd = "";

// $conn = mysqli_connect($host, $user, $pwd, $db);

// if (!$conn) {
//     die("Could not connect to the database because: ". mysqli_error($conn));
// }

$file = $_FILES["file"];


$fileName = $file["name"]; // the name of the file

$fileType = $file["type"]; // the file type

$fileTmpName = $file["tmp_name"]; // the temp name
echo explode("/", mime_content_type($fileTmpName))[0];
die();

$fileError = $file["error"]; // to check if any error ocurred or not - 1 or 0

$fileSize = $file["size"]; // the file size

$fileExt = explode(".", $fileName); // splitted the filename and the file extension

$year = date("Y", strtotime("now"));

$month = date("m", strtotime("now"));

$mainLocation = "uploads/";

$parentsLocation = $year . "/" . $month . "/";

$folder = $mainLocation . $parentsLocation;

if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

$realFilename = uniqid($fileExt[0]) . "." . end($fileExt);
$filePermLoc = $folder . $realFilename;
if(move_uploaded_file($fileTmpName, $filePermLoc)){
    $sql = "INSERT INTO files (filename, parentFolder)VALUES(?,?)";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $realFilename, $parentsLocation);

    mysqli_stmt_execute($stmt);
    echo json_encode(["status"=>"0", "msg"=>"the file " . $fileName . " uploaded successfully!"]);
} else {
    echo json_encode(["status"=>"1", "msg"=>"the file " . $fileName . " couldn't be uploaded due to some issues"]);
}



