 <?php
 session_start();


 function makeWithdrawal($withdraw_amt){ //withdrawal function

 	if(!is_numeric($withdraw_amt))
 		return false;
 	
 	if(($_SESSION['account_balance'] - 1000) <= $withdraw_amt) //1k is account threshold
 		return false;

 	$_SESSION['account_balance'] -= $withdraw_amt; // perform withdrawal
 	return true;
 }


 function makeDeposit($deposit_amt){ //deposit function

 	if(!is_numeric($deposit_amt))
 		return false;

 	if($deposit_amt > 500000) //check that deposit is less than 500k
 		return false;

 	$_SESSION['account_balance'] += $deposit_amt; // perform deposit
 	return true;
 }


 function getAccountBalance(){ //get account balance function

 	//return account balance if set or false if not set
 	return isset($_SESSION['account_balance']) ? $_SESSION['account_balance'] : false; 
 }


 function exitAtm(){ //end transaction

 	$_SESSION['user_logged_in'] = false; //unset login session
 }


 if(isset($_POST['exit_atm'])){

 	exitAtm(); //call the exitAtm function
 }


 if(!$_SESSION['user_logged_in']){

 	$_SESSION['account_balance'] = 50000; //set initial balance on pageload
 	$_SESSION['user_logged_in'] = false; //unset login session
 }


 if(isset($_POST['pin'])){ //when user enters pin

 	if($_POST['atm_pin'] == "1234"){ //if pin is correct
 		$_SESSION['trial_count'] = 0; //reset failed trial count to 0
 		$_SESSION['user_logged_in'] = true; //set session variable
 		$screen = "Pin accepted"; //set success screen message
 	}

 	else{
 		$_SESSION['user_logged_in'] = false; //unset login session
 		$screen = "Wrong Pin. Try again"; //set error screen message
 		$_SESSION['trial_count']++; //count unsuccessful trials
 	}
 }

 if(isset($_SESSION['trial_count']) && $_SESSION['trial_count'] > 3){ //redirect on more than 3 failed trials

 	$_SESSION['trial_count'] = 0; //reset failed trial count to 0
 	header('Location: https://daltondev.com');
 	exit();
 }


 if(isset($_POST['deposit'])){

 	$deposit_amt = $_POST['deposit_amt'];

 	if(makeDeposit($deposit_amt)){

 		$screen = "Deposited NGN " . number_format($deposit_amt, 0);  
 	}
 	else{

 		$screen = "Transaction failed!";
 	}
 }


 if(isset($_POST['withdraw'])){

 	$withdraw_amt = $_POST['withdraw_amt'];

 	if(makeWithdrawal($withdraw_amt)){

 		$screen = "Withdrew NGN " . number_format($withdraw_amt, 0);  
 	}
 	else{

 		$screen = "Transaction failed!";
 	}
 }


 if(isset($_POST['check_balance'])){

 	$screen = "Bal: NGN " . number_format(getAccountBalance(), 2);
 }
 
 
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title></title>
 	<style type="text/css">

 		form{
 			margin-bottom: 10px;
 		}

 		.exit{
 			background-color: red;
 			color: white
 		}

 		.screen{
 			background-color: white;
 			color: black
 		}


 		.pin{
 			background-color: green;
 			color: white
 		}

 		.deposit{
 			background-color: tomato;
 			color: white
 		}

 		.withdraw{
 			background-color: dodgerblue;
 			color: white
 		}
 		.balance{
 			background-color: magenta;
 			color: white
 		}

 	</style>
 </head>
 <body>

 	<label for="scrn"><b>Screen</b></label><br>
 	<input type="text" name="screen" value="<?php echo $screen; ?>" class="screen" placeholder="Perform a transaction" id="scrn" disabled><br><br>

 	<?php if(!$_SESSION['user_logged_in']){ ?>
 	<form method="post" >
 		<label for="pn">ATM Pin: (1234) </label><br>
 		<input type="number" name="atm_pin" class="pin" id="pn" placeholder="Enter Pin" required>
 		<button name="pin" class="pin"> Enter </button><br>
 	</form>
 	<?php } 

 	if($_SESSION['user_logged_in']){ ?>
 	<form method="post" >
 		<label for="dep"> Deposit Cash: </label><br>
 		<input type="number" name="deposit_amt" class="deposit" id="dep" placeholder="NGN" required>
 		<button name="deposit" class="deposit"> Enter </button><br>
 		<h4 style="color: red; margin: 0px"></h4>
 	</form>

 	<form method="post" >
 		<label for="withd"> Withdraw Cash: </label><br>
 		<input type="number" name="withdraw_amt" class="withdraw" id="withd" placeholder="NGN" required>
 		<button name="withdraw" class="withdraw"> Enter </button><br><br>
 	</form>

 	<form method="post">
 		<h4 style="color: red; margin: 5px"></h4>
 		<button name="check_balance" class="balance"> Check Balance </button>
 	</form>
 	
 	<form method="post">
 		<h4 style="color: red; margin: 5px"></h4>
 		<button name="exit_atm" class="exit"> Logout </button>
 	</form>
 	<?php } ?> 

 </body>
 </html>  
