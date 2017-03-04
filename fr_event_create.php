<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_event_create.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program adds/inserts a new event (table: fr_events)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["fr_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}

require '../database/database.php';

if ( !empty($_POST)) { // if not first time through

	// initialize user input validation variables
	$dateError = null;
	$timeError = null;
	$locationError = null;
	$descriptionError = null;
	$personError = null; 
	
	// initialize $_POST variables
	$date = $_POST['event_date'];
	$time = $_POST['event_time'];
	$location = $_POST['event_location'];
	$description = $_POST['event_description'];	
	$person = $_POST['person'];		
	
	// validate user input
	$valid = true;
	if (empty($date)) {
		$dateError = 'Please enter Date';
		$valid = false;
	}
	if (empty($time)) {
		$timeError = 'Please enter Time';
		$valid = false;
	} 		
	if (empty($location)) {
		$locationError = 'Please enter Location';
		$valid = false;
	}		
	if (empty($description)) {
		$descriptionError = 'Please enter Description';
		$valid = false;
	}
	// don't require there to be a person in charge

	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO fr_events (event_date, event_time, event_location, event_description, person_in_charge) values(?, ?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($date,$time,$location,$description,$person));
		Database::disconnect();
		header("Location: fr_events.php");
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Add New Event</h3>
			</div>
	
			<form class="form-horizontal" action="fr_event_create.php" method="post">
			
				<div class="control-group <?php echo !empty($dateError)?'error':'';?>">
					<label class="control-label">Date</label>
					<div class="controls">
						<input name="event_date" type="date"  placeholder="Date" value="<?php echo !empty($date)?$date:'';?>">
						<?php if (!empty($dateError)): ?>
							<span class="help-inline"><?php echo $dateError;?></span>
						<?php endif; ?>
					</div>
				</div>
			  
				<div class="control-group <?php echo !empty($timeError)?'error':'';?>">
					<label class="control-label">Time</label>
					<div class="controls">
						<input name="event_time" type="time" placeholder="Time" value="<?php echo !empty($time)?$time:'';?>">
						<?php if (!empty($timeError)): ?>
							<span class="help-inline"><?php echo $timeError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($locationError)?'error':'';?>">
					<label class="control-label">Location</label>
					<div class="controls">
						<input name="event_location" type="text" placeholder="Location" value="<?php echo !empty($location)?$location:'';?>">
						<?php if (!empty($locationError)): ?>
							<span class="help-inline"><?php echo $locationError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
					<label class="control-label">Description</label>
					<div class="controls">
						<input name="event_description" type="text" placeholder="Description" value="<?php echo !empty($description)?$description:'';?>">
						<?php if (!empty($descriptionError)): ?>
							<span class="help-inline"><?php echo $descriptionError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Person in Charge</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM fr_persons ORDER BY lname ASC, fname ASC';
							echo "<select class='form-control' name='person' id='person_id'>";
							foreach ($pdo->query($sql) as $row) {
								echo "<option value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->
				
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Create</button>
					<a class="btn" href="fr_events.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- div: class="container" -->
				
    </div> <!-- div: class="container" -->
	
</body>
</html>