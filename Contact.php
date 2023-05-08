<?php
//Header
include("./templates/Header.php");
?>
<main class="contactContainer">

    <?php
    // モード切り替え
    $mode = 'input';
    $errmessage = array();

    // 戻るボタン　押したとき
    if (isset($_POST['back']) && $_POST['back']) {
        // 何もしない

        // ============================================
        // name エラー定義
        // ============================================
    } else if (isset($_POST['confirm']) && $_POST['confirm']) {
        if (!$_POST['fullname']) {

            // formの所でエラーを表示するために、連想配列で定義
            $errmessage['emptyName'] = "* Please enter your name";
        } else if (mb_strlen($_POST['fullname']) > 100) {
            $errmessage['tooManyName'] = "Name must be within 100 characters";
        }
        // Validation Passed ↓ ↓ ↓
        $_SESSION['fullname'] = htmlspecialchars($_POST['fullname'], ENT_QUOTES);

        // ============================================
        // email  エラー定義
        // ============================================
        if (!$_POST['email']) {
            $errmessage['emptyEmail'] = "Please enter your email";
        } else if (mb_strlen($_POST['email']) > 200) {
            $errmessage['tooManyEmail'] = "Email must be within 200 characters";
        } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errmessage['notValidEmail'] = "Email is Not valid";
        }
        // Validation Passed ↓ ↓ ↓
        $_SESSION['email']    = htmlspecialchars($_POST['email'], ENT_QUOTES);

        // ============================================
        // message　エラー定義
        // ============================================
        if (!$_POST['message']) {
            $errmessage['emptyMsg'] = "Please enter your message";
        } else if (mb_strlen($_POST['message']) > 500) {
            $errmessage['tooManyMsg'] = "Message must be within 500 characters";
        }
        // Validation Passed ↓ ↓ ↓
        $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

        // ============================================
        // Select Box (category)
        // ============================================
        $_SESSION['category'] = htmlspecialchars($_POST['category'], ENT_QUOTES);

        // ============================================
        // Radio Button
        // ============================================
        $_SESSION['ASAP'] = htmlspecialchars($_POST['radio'], ENT_QUOTES);


        // エラーがなければ確認画面へ
        if ($errmessage) {
            $mode = 'input';
        } else {
            $mode = 'confirm';
        }

        // ====================================
        // 送信ボタンを押したとき
        // ウェブサイトのオーナーが受け取る 
        // ====================================
    } else if (isset($_POST['send']) && $_POST['send']) {
        $message  = "お問い合わせを受け付けました \r\n"
            . "名前: " . $_SESSION['fullname'] . "\r\n"
            . "email: " . $_SESSION['email'] . "\r\n"
            . "お問い合わせ内容:\r\n"
            . "category: " . $_SESSION['category'] . "\r\n"
            . "radioooo: " . $_SESSION['ASAP'] . "\r\n"

            . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
        mail($_SESSION['email'], 'お問い合わせありがとうございます', $message);
        mail('drumer19861018@gmail.com', 'お問い合わせありがとうございます', $message);
        $_SESSION = array();
        $mode = 'send';

        // セッションを初期化
    } else {
        $_SESSION['fullname'] = "";
        $_SESSION['email']    = "";
        $_SESSION['message']  = "";
        $_SESSION['category']  = "Adoption";
        $_SESSION['ASAP']  = "ASAP";
    }
    ?>

    <!-- ==================================== -->
    <!-- HTML  入力画面 モード -->
    <!-- ==================================== -->
    <?php if ($mode == 'input') { ?>


        <?php
        // エラーメッセージがある場合は表示
        // テキスト部分には、$errmessage に格納されている複数のエラーメッセージが、implode('<br>', $errmessage) を使って改行を挟んで連結されて表示されます。 implode()は、配列の要素を文字列に変換して、文字列を結合する関数です。第一引数には結合する区切り文字列を指定し、第二引数には結合する配列を指定
        // if ($errmessage) {
        // echo '<div style="color:red;">';
        // echo implode('<br>', $errmessage);
        // echo '</div>';
        // }
        ?>

        <form action="./Contact.php" method="post" class="container">
            <h1 class="contactFormTitle">Contact</h1>

            <!-- =========================== -->
            <!-- Name: Error Message handling -->
            <!-- =========================== -->
            <!-- empty error & less than 100 error -->
            <?php if (isset($errmessage['emptyName'])) : ?>
                <p class="text-danger mb-0">
                    <?php echo htmlspecialchars($errmessage['emptyName'], ENT_QUOTES); ?>
                </p>
                <!-- Less than 200 error -->
            <?php elseif (isset($errmessage['tooManyName'])) : ?>
                <p class="text-danger mb-0">
                    <?php echo htmlspecialchars($errmessage['tooManyName'], ENT_QUOTES); ?>
                </p>
            <?php endif; ?>
            <!-- Name HTML Form -->
            名前 <input type="text" name="fullname" value="<?php echo htmlspecialchars($_SESSION['fullname'], ENT_QUOTES) ?>">

            <br>
            <br>

            <!-- =========================== -->
            <!-- Email:  Error Message handling -->
            <!-- =========================== -->
            <!-- empty error -->
            <?php if (isset($errmessage['emptyEmail'])) : ?>
                <p class="text-danger mb-0">
                    <?php echo htmlspecialchars($errmessage['emptyEmail'], ENT_QUOTES); ?>
                </p>
                <!-- Less than 200 error -->
            <?php elseif (isset($errmessage['tooManyEmail'])) : ?>
                <p class="text-danger mb-0">
                    <?php echo htmlspecialchars($errmessage['tooManyEmail'], ENT_QUOTES); ?>
                </p>
            <?php elseif (isset($errmessage['notValidEmail'])) : ?>
                <p class="text-danger mb-0">
                    <?php echo htmlspecialchars($errmessage['notValidEmail'], ENT_QUOTES); ?>
                </p>
            <?php endif; ?>

            Eメール <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES) ?>">
            <br>
            <br>

            <!-- =========================== -->
            <!-- Message: Error Message handling -->
            <!-- =========================== -->
            <!-- empty error -->
            <?php if (isset($errmessage['emptyMsg'])) : ?>
                <p class="text-danger mb-0">
                    <?php echo htmlspecialchars($errmessage['emptyMsg'], ENT_QUOTES); ?>
                </p>
                <!-- Less than 500 error -->
            <?php elseif (isset($errmessage['tooManyMsg'])) : ?>
                <p class="text-danger mb-0">
                    <?php echo htmlspecialchars($errmessage['tooManyMsg'], ENT_QUOTES); ?>
                </p>
            <?php endif; ?>


            お問い合わせ内容
            <br>
            <textarea cols="40" rows="8" name="message"><?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES) ?></textarea>
            <br>
            <br>

            <div class="categorySelectBox">
                <span>Category:</span>
                <select name="category" value="<?php echo htmlspecialchars($_SESSION['category'], ENT_QUOTES) ?>">
                    <!-- メールにはこの value の値が表示される -->
                    <option value="Adoption">Adoption</option>
                    <option value="Estimation">Estimation</option>
                    <option value="Cat Food">Cat Food</option>
                    <option value="Cat Toys">Cat Toys</option>
                </select>
            </div>

            <br>
            <br>

            <!-- names have to the same -->
            <!-- ラジオボタンの値は、$_SESSION変数に保存する必要なし。$_POST変数から値を取得して、必要な場合はセッションに保存する。 $_POST変数から値を取得し、選択されたラジオボタンに checked属性 を追加 
        isset()関数を使用して、POSTされた値があるかどうかを確認しています。POSTされた値がない場合は、最初にアクセスしたときと同じ初期状態で表示されるÏ-->
            <div class="radioBtns">
                <label>
                    <input type="radio" name="radio" value="Pick Up" <?php if (!isset($_POST['radio']) || $_POST['radio'] == 'Pick Up') echo htmlspecialchars('checked', ENT_QUOTES); ?>>Pick Up
                </label>

                <label>
                    <input type="radio" name="radio" value="Delivery" <?php if (isset($_POST['radio']) && $_POST['radio'] == 'Delivery') echo htmlspecialchars('checked', ENT_QUOTES); ?>>Delivery
                </label>
            </div>

            <br>
            <br>

            <!-- Button -->
            <input class="contactBtn" type="submit" name="confirm" value="Confirm" />
        </form>

        <!-- ================================= -->
        <!-- Confirm Mode -->
        <!-- 送った人が受け取る -->
        <!-- ================================= -->
    <?php } else if ($mode == 'confirm') { ?>
        <!-- 確認画面 -->
        <form action="./Contact.php" method="post" class="container confirmScreen">
            名前 <?php echo htmlspecialchars($_SESSION['fullname'], ENT_QUOTES) ?><br>
            <br>
            Eメール <?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES) ?><br>
            <br>
            Category: <?php echo htmlspecialchars($_SESSION['category'], ENT_QUOTES) ?><br>
            <br>

            radiooo: <?php echo htmlspecialchars($_SESSION['ASAP'], ENT_QUOTES) ?><br>
            <br>

            お問い合わせ内容<br>
            <?php echo htmlspecialchars(nl2br($_SESSION['message']), ENT_QUOTES) ?><br>
            <input type="submit" name="back" value="Back" class="confirmBtn" />
            <input type="submit" name="send" value="Send" class="confirmBtn" />
        </form>
    <?php } else { ?>
        <!-- ================================= -->
        <!-- Thanks screen -->
        <!-- ================================= -->
        <div class="container thanksScreen">
            <h2 class="thanksMsg">送信しました。お問い合わせありがとうございました。</h2>
        </div>

    <?php } ?>



























</main>












<?php
// Footer
include("./templates/Footer.php")
?>