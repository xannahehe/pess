<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Police Emergency Service System</title>
	<link href="anna.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php require_once 'nav.php'; ?>
	
	<?php //ifpost back
	
	if (isset($_POST["btnDispatch"]))
	{
		require_once 'db.php';
		//create db connection
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
		//check connection
		if ($mysqli->connect_error)
		{
			die("Failed to connect to MYSQL: ".$mysql->connect_error);
		}
		
		$patrolcarDispatched = $_POST["chkPatrolcar"]; //array of patrolcar being dispatched from post back
		$numOfPatrolcarDispatched = count($patrolcarDispatched);

	//insert new incident
	$incidentStatus;
		if ($numOfPatrolcarDispatched > 0)
		{
			$incidentStatus='2'; //incident status to be set as dispatched
		}
		else
		{
			$incidentStatus='1'; //incident status to be set as pending
		}
	
		$sql = "INSERT INTO incident (callerName, phoneNumber, incidentTypeId, incidentLocation, incidentDesc, incidentStatusId) VALUES (?,?,?,?,?,?)";
		
		if (!($stmt = $mysqli->prepare($sql)))
		{
			die("Prepare failed: ".$mysql->error);
		}
		
		if (!$stmt->bing_param('ssssss', $_POST['callerName'],
							  $_POST['contactNo'],
							  $_POST['incidentType'],
							  $_POST['location'],
							  $_POST['incidentDesc'],
							  $incidentStatus))
	
		{
			die("Binding parameters failed: ".$stmt->error);
		}
		
		if (!$stmt->execute())
		{
			die("Insert incident table failed: ".$stmt->error);
		}
		
		$stmt->close();
		
		$mysqli->close();
	}
	?>
	
	<form name="form1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
		<table>
		<tr>
			<td colspan="2">Incident Detail</td>
			</tr>
			
			<tr>
			<td>Caller's Name :</td>
				<td>
				<?php echo $_POST['callerName']?>
					<input type="hidden" name="callerName" id="callerName" value="<?php echo $_POST['callerName']?>">
				</td>
			</tr>
			
			<tr>
			<td>Contact No :</td>
			    <td>
				<?php echo $_POST['contactNo']?>
				<input type="hidden" name="contactNo" id="contactNo" value="<?php echo $_POST['contactNo']?>">
			</td>
			</tr>
			
			<tr>
			<td>Location :</td>
			<td>
				<?php echo $_POST['location']?>
			    <input type="hidden" name="location" id="location" value="<?php echo $_POST[location]?>">
			</td>
			</tr>
			
			<tr>
				<td>Description :</td>
				<td>
					<textarea name="incidentDesc" cols="45" rows="5" readonly id="incidentDesc"><?php echo $_POST['incidentDesc']?></textarea>
					<input name="incidentDesc" value="<?php echo $_POST['incidentDesc']?>"
				</td>
			</tr>
		</table>
		
		<?php
	//connect to database..
	require_once 'db.php';
					
					//create db connection
					$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
					//create db connection
					if ($mysqli->connect_error)
					{
					die("Failed to connect to MySQL?: ".$mysqli->connect_error);	
					}
					
					//retrive from patrol car table those patrol that are 2:patrol or 3:free
					$sql = "SELECT patrolcarId, statusDesc FROM patrolcar JOIN patrolcar_status ON patrolcar.patrolcarStatusId=patrolcar_status.StatusId WHERE patrolcar.patrolcarStatusId='2' OR patrolcar.patrolcarStatusId='3'";
					
					if (!($stmt = $mysqli->prepare($sql))) {
						die("Prepare failed: ".$mysqli->error);
					}
					
					if (!$stmt->execute()) {
						die("Execute failed: ".$stmt->error);
					}
					
					if (!($resultset = $stmt->get_result()))
					{
						die("Getting result set failed: ".$stmt->error);
					}
					
					$patrolcarArray;
					
					while ($row = $resultset->fetch_assoc())
					{
						$patrolcarArray[$row['patrolcarId']] = $row['statusDesc'];
					}
					
					$stmt->close();
					
					$resultset->close();
					
					$mysqli->close();
			?>
		<!-- populate table with patrol car data -->
		<br><br><table border="1" align="center">
		<tr>
			<td colspan="3">Dispatch Patrolcar Panel</td>
	</tr>
		
		<?php
		foreach($patrolcarArray as $key=>$value){
			?>
		<tr>
		<td>
			<input type="checkbox" name="chkPatrolcar[]"
				   value="<?php echo $key?>">
			</td>
			
			<td><?php echo $key?></td>
			<td><?php echo $value?></td>
		</tr>
	<?php } ?>
		
		<tr>
			<td>
				<input type="reset" name="btnCancel" id="btnCancel" value="Reset">
					   </td>
			
			<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btnDispatch" id="btnDispatch" value="Dispatch"
						</td>
		</tr>
		</table>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	</form>
</body>
</html>