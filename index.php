<?php
include("db_config.php");

$validation_msg = null;
$success_msg = "";

$c_one = rand(10, 30);
$c_two = rand(10, 30);
$form_key = generateFormKey();
$ip = getIpAddress();

if ("POST" == $_SERVER["REQUEST_METHOD"] && 0 != count($_POST)) {

    if (!isset($_POST["name"]) || empty($_POST["name"]) || !preg_match('/^[a-zA-Z\s]+$/', data_trim($_POST["name"]))) {
        $validation_msg .= "<li> Only letters and space allowed </li>";
    }

    if (!isset($_POST["mail"]) || empty($_POST["mail"]) || !filter_var(data_trim($_POST["mail"]), FILTER_VALIDATE_EMAIL)) {
        $validation_msg .= "<li> Invalid email format </li>";
    }

    if (!isset($_POST["phone"]) || empty($_POST["phone"]) || !preg_match('/^[0-9]{10}+$/', data_trim($_POST["phone"]))) {
        $validation_msg .= "<li> Invalid phone format </li>";
    }

    if (!isset($_POST["subject"]) || empty($_POST["subject"])) {
        $validation_msg .= "<li> Please Enter valid subject </li>";
    }

    if (!isset($_POST["message"]) || empty($_POST["message"])) {
        $validation_msg .= "<li> Please Enter Message and try again </li>";
    }

    if (!isset($_COOKIE["captcha"])) {
        $validation_msg .= "<li> Please Enter valid Captcha </li>";
    }

    if (!isset($_POST["token"]) || !isset($_POST["org_token"])) {
        $validation_msg .= "<li> something want wrong. please try again </li>";
    }

    if ($_POST["token"] != $_POST["org_token"]) {
        $validation_msg .= "<li> something want wrong. please try again </li>";
    }

    if ($_COOKIE["captcha"] != $_POST["cp"]) {
        $validation_msg .= "<li> Please Enter valid Captcha </li>";
    }

    $last = $db_conn->query("select * from  contact_form where createdAt >= DATE_SUB(NOW(),INTERVAL 2 HOUR) AND user_ip='" . $ip . "'");
    if (0 != $last->num_rows) {
        $validation_msg .= "<li> You are already submitted the contact form, so please try again after 2 hours. </li>";
    }

    if (null == $validation_msg) {
        $name = $_POST["name"];
        $phone = $_POST["phone"];
        $mail = $_POST["mail"];
        $subject = $_POST["subject"];
        $message = $_POST["message"];

        $sql_qry = "insert into contact_form (user_ip, name, phone, email, subject, message) values('" . $ip . "', '" . $name . "', '" . $phone . "', '" . $mail . "', '" . $subject . "', '" . $message . "')";

        if (TRUE === $db_conn->query($sql_qry)) {
            $success_msg = "Data Successfully added";

            $mail_body = "
<html>
<head>
<title> New Inquiry </title>
</head>
<body>
<p>Hello,</p>
<table>
<tr>
<th>Name</th>
<th>" . $name . "</th>
</tr>
<tr>
<td>E-mail</td>
<th>" . $mail . "</th>
</tr>
<tr>
<td>Phone</td>
<th>" . $phone . "</th>
</tr>
<tr>
<td>Subject</td>
<th>" . $subject . "</th>
</tr>
<tr>
<td>Inquiry Message</td>
<th>" . $message . "</th>
</tr>
</table>
</body>
</html>
";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <' . $from_mail . '>' . "\r\n";
            $headers .= 'Cc: ' . $cc_mail . "\r\n";

            mail($to, $mail_subject, $mail_body, $headers);


            header("location: index.php?msg=Data Successfully added");
        } else {
            $validation_msg = "<li> Error : " . $db_conn->error . "</li>";
        }
    }
    if (!empty($validation_msg)) {
        $validation_msg = "<ul style='color: red'>" . $validation_msg . "</ul>";
    }
}
setcookie("captcha", $c_one + $c_two, time() + (60 * 30), "/");
?>


<?php
if (isset($_GET["msg"]) && !empty($_GET["msg"])) {
?>
    <h2 style="color: green"><?= $_GET["msg"] ?></h2>
<?php
}
?>

<hr />
<?= $validation_msg ?>
<hr />
<hr />

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
    <h2> Contact Us</h2>
    <input type="hidden" value="<?= $form_key ?>" name="token">
    <table>
        <tr>
            <td>
                <label>Full Name</label>
            </td>
            <td><b>:</b></td>
            <td>
                <input type="text" name="name" value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label>E-Mail</label>
            </td>
            <td><b>:</b></td>
            <td>
                <input type="email" name="mail" value="<?= isset($_POST['mail']) ? $_POST['mail'] : '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label>Phone </label>
            </td>
            <td><b>:</b></td>
            <td>
                <input type="text" name="phone" value="<?= isset($_POST['phone']) ? $_POST['phone'] : '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label>Subject</label>
            </td>
            <td><b>:</b></td>
            <td>
                <input type="text" name="subject" value="<?= isset($_POST['subject']) ? $_POST['subject'] : '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label>Message</label>
            </td>
            <td><b>:</b></td>
            <td>
                <textarea rows="5" name="message"><?= isset($_POST['message']) ? $_POST['message'] : '' ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label>Captcha</label>
            </td>
            <td><b>:</b></td>
            <td>
                <?= $c_one ?> + <?= $c_two ?>
                <input type="text" name="cp" value="">
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <button type="submit" name="submit"> submit </button>
            </td>
        </tr>
    </table>
    <input type="hidden" value="<?= $form_key ?>" name="org_token">
</form>