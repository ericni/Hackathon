

    <?php
    session_start();
    // The following function returns a word of dots, each dot represents a letter
    function word2dots($word) {
    $wordlength = strlen($word);
    $dotWord = "";
    for ($i = 0; $i < $wordlength; $i++) {
    $dotWord = "".$dotWord.".";
    }
    return $dotWord;
    }
    // The following function opens the file, reads it, saves it into an array and returns a random word
    function getWord() {
    // Opening and reading file
    $filename = "words.txt"; // You can change this into whatever words file you'd like, as long as it's local.
    @$filehandle = fopen($filename,"r");
    @$filecontent = fread($filehandle,filesize($filename));
    @fclose($filehandle);
    if (!$filehandle) {
    $_SESSION['message'] = "Could not open ".$filename;
    }
    // Splitting the content into the $words array
    $words = split("\|", $filecontent);
    $words_amount = count($words);
    // Calculating random number
    $random_number = mt_rand(0,$words_amount);
    // Returning random word
    $theWord2 = $words[$random_number];
    return $theWord2;
    }
    // Resetting the message
    $_SESSION['message'] = "";
    // Retrieving the guessWord, theWord and the amount of errors.
    if (isset($_POST['send_letter']) && $_SESSION['gameEnded'] != true) {
    if (isset($_POST['HangManLetter']) && preg_match("/[A-Z\s_]/i", $_POST['HangManLetter']) > 0) {
    // Getting the $letter value
    $letter = htmlentities(stripslashes($_POST['HangManLetter']));
    // Retrieving session variables
    $theWord = $_SESSION['theWord'];
    $guessWord = $_SESSION['guessWord'];
    $error_amount = $_SESSION['error_amount'];
    // Checking wether the $letter occurs in the word
    // Filling the arrays:
    for ($d = 0; $d < strlen($theWord); $d++) {
    $theWordArray[$d] = substr($theWord, $d, 1);
    $guessWordArray[$d] = substr($guessWord, $d, 1);
    }
    // Checking occurance of the letter in theWord
    $letterOccured = false;
    for ($f = 0; $f < strlen($theWord); $f++) {
    if ($theWordArray[$f] == $letter) {
    $letterOccured = true;
    $guessWordArray[$f] = $theWordArray[$f];
    }
    }
    // Updating the guessWord:
    $guessWord = "";
    for ($r = 0; $r < strlen($theWord); $r++) {
    $guessWord = "".$guessWord."".$guessWordArray[$r]."";
    }
    $_SESSION['guessWord'] = $guessWord;
    if ($_SESSION['guessWord'] == $_SESSION['theWord']) {
    $_SESSION['message'] = "You won! <input type='submit' name='reset' value='Try again?' />";
    unset($_SESSION['theWord']);
    unset($_SESSION['guessWord']);
    $_SESSION['gameEnded'] = true;
    $_SESSION['guessWord'] = $theWord;
    }
    if ($letterOccured == false) {
    $error_amount++;
    $_SESSION['error_amount'] = $error_amount;
    // If the error_amount is higher as 9, the player lost
    if ($error_amount > 9) {
    $_SESSION['message'] = "You lost! <input type='submit' name='reset' value='Try again?' />";
    unset($_SESSION['theWord']);
    unset($_SESSION['guessWord']);
    $_SESSION['gameEnded'] = true;
    $_SESSION['guessWord'] = $theWord;
    }
    }
    } else {
    // Showing the message
    if (preg_match("/[A-Z\s_]/i", $_POST['letter']) < 0) {
    $_SESSION['message'] = "Only alphanumeric symbols are allowed!";
    } else {
    $_SESSION['message'] = "Enter a letter!";
    } // End of else isaplha ($_POST['HangManLetter'])
    } // End of else isset($_POST['HangManLetter']) and preg_match("/[A-Z\s_]/i", $_POST['HangManLetter'])
    } else { // If the game has been resetted or there has not yet been a game played
    $theWord = getWord();
    $guessWord = word2dots($theWord);
    $error_amount = 0;
    $_SESSION['theWord'] = $theWord;
    $_SESSION['guessWord'] = $guessWord;
    $_SESSION['error_amount'] = $error_amount;
    $_SESSION['gameEnded'] = false;
    }
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
    <link rel="stylesheet" href="style1.css" type="text/css" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Hangman</title>
    </head>
    <body onload="document.getElementById('HangManInput').focus()">
    <div style="background-color:#EFEFEF;position:relative; top:0px; left:0px; border:2px ridge #BBBBBB; width:200px; height:280px; font-family:Times New Roman; font-weight:normal; font-style:normal; text-decoration:none;">
     <form action="" method="post" onsubmit="return true;">
      <div id="HangManTitle" style="font-size:22px; position:absolute; top:5px; width:200px; text-align:center;">
       Hangman
      </div>
      <div id="HangManConsole" style="font-size:16px; position:absolute; top:34px; text-align:center; width:200px;">
       Enter a letter: <input type="text" maxlength="1" size="1" id="HangManInput" name="HangManLetter" /><input type="hidden" value="true" name="send_letter" /><input type="submit" value="Go" name="send_letter_button" /><br />
       The word: <span id="HangManGuessWord"><?php echo $_SESSION['guessWord']; ?></span><br />
      </div>
      <div id="HangManImage" style="position:absolute; top:85px; left:22px;">


       <img src="vmtn<?php echo $_SESSION['error_amount']; ?>.jpg" id="HangManIMG" width="25" style="border:1px ridge #BBBBBB;" /> 
          <?php if($_SESSION['error_amount'] == 1) { echo "Novice";}?> 
          <?php if($_SESSION['error_amount'] == 2) { echo "Enthusiast";}?> 
      </div>
      <div id="HangManMessage" style="font-size:16px; position:absolute; top:250px; width:200px; text-align:center;">
       <?php echo $_SESSION['message']; ?>
      </div>
     </form>
    </div>
    </body>
    </html>


