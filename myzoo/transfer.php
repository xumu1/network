<?php 
  require_once("includes/common.php"); 
  nav_start_outer("Transfer");
  nav_start_inner();
  if($_POST['submission']) {
	    $recipient = $_POST['recipient'];
    $zoobars = (int) $_POST['zoobars'];
    $sql = "SELECT Zoobars FROM Person WHERE PersonID=$user->id";
    $rs = $db->executeQuery($sql);
    $rs = mysql_fetch_array($rs);
    $sender_balance = $rs["Zoobars"] - $zoobars;
    $sql = "SELECT PersonID FROM Person WHERE Username='$recipient'";
    $rs = $db->executeQuery($sql);
    $rs = mysql_fetch_array($rs);
    $recipient_exists = $rs["PersonID"];
    if($zoobars > 0 && $sender_balance >= 0 && $recipient_exists) {
      $sql = "UPDATE Person SET Zoobars = $sender_balance " .
             "WHERE PersonID=$user->id";
      $db->executeQuery($sql);
      $sql = "SELECT Zoobars FROM Person WHERE Username='$recipient'";
      $rs = $db->executeQuery($sql);
	$rs = mysql_fetch_array($rs);
      $recipient_balance = $rs["Zoobars"] + $zoobars;
      $sql = "UPDATE Person SET Zoobars = $recipient_balance " .
             "WHERE Username='$recipient'";
      $db->executeQuery($sql);
      $result = "Sent $zoobars zoobars";
    }
    else $result = "Transfer to $recipient failed.";
  }
?>
<p><b>Balance:</b>
<span id="myZoobars">  <?php 
  $sql = "SELECT Zoobars FROM Person WHERE PersonID=$user->id";
  $rs = $db->executeQuery($sql);
  $rs = mysql_fetch_array($rs);
  $balance = $rs["Zoobars"];
  echo $balance > 0 ? $balance : 0;
?> </span> zoobars</p>
<form method=POST name=transferform
  action="<?php echo $_SERVER['PHP_SELF']?>">
<p>Send <input name=zoobars type=text value="<?php 
  echo $_POST['zoobars']; 
?>" size=5> zoobars</p>
<p>to <input name=recipient type=text value="<?php 
  echo $_POST['recipient']; 
?>"></p>
<input type=submit name=submission value="Send">
</form>
<span class=warning><?php 
  echo "$result"; 
?></span>
<?php 
  nav_end_inner();
?>
<script type="text/javascript" src="zoobars.js.php"></script>
<?php
  nav_end_outer(); 
?>
