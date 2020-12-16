<?php
    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        //Read Data
        if(isset($_POST['search'])) {
            $detailsID = $_POST['search'];
            $sql = NULL;

            $detailsID = mysqli_real_escape_string($db, $detailsID);

            $student= false;
            $staff= false;
            $adviser= false;
            $contact= false;

            $testStudentSQL = "SELECT detailsID FROM Student WHERE detailsID='$detailsID'";
            if(isset($testStudentSQL)) {
                $result = mysqli_query($db,$testStudentSQL) or die(mysqli_error($db));
                if(mysqli_fetch_array($result)['detailsID'] == $detailsID) {
                    $student=true;
                    $sql = "SELECT a.firstname, a.middlename, a.lastname, a.email, a.mobile, b.buildingNumber, b.firstLine, b.secondLine, b.postcode, c.studentID, c.maxMonthly, c.degreeCategory, c.startYear, c.graduateYear, c.studentStatus, c.leaseNumber, c.adviserID, c.courseNumber FROM GeneralDetails a INNER JOIN Address b ON a.addressID=b.addressID INNER JOIN Student c ON a.detailsID=c.detailsID WHERE a.detailsID='$detailsID'";
                }
            }

            $testStaffSQL = "SELECT detailsID FROM ResidentStaff WHERE detailsID='$detailsID'";
            if(isset($testStaffSQL)) {
                $result = mysqli_query($db,$testStaffSQL) or die(mysqli_error($db));
                if(mysqli_fetch_array($result)['detailsID'] == $detailsID) {
                    $staff=true;
                    $sql = "SELECT a.firstname, a.middlename, a.lastname, a.email, a.mobile, b.buildingNumber, b.firstLine, b.secondLine, b.postcode, c.staffID, c.position, c.location FROM GeneralDetails a INNER JOIN Address b ON a.addressID=b.addressID INNER JOIN ResidentStaff c ON a.detailsID=c.detailsID WHERE a.detailsID='$detailsID'";
                }
            }

            $testAdviserSQL = "SELECT detailsID FROM Adviser WHERE detailsID='$detailsID'";
            if(isset($testAdviserSQL)) {
                $result = mysqli_query($db,$testAdviserSQL) or die(mysqli_error($db));
                if(mysqli_fetch_array($result)['detailsID'] == $detailsID) {
                    $adviser=true;
                    $sql = "SELECT a.firstname, a.middlename, a.lastname, a.email, a.mobile, b.buildingNumber, b.firstLine, b.secondLine, b.postcode, c.universityID, c.adviserID FROM GeneralDetails a INNER JOIN Address b ON a.addressID=b.addressID INNER JOIN Adviser c ON a.detailsID=c.detailsID WHERE a.detailsID='$detailsID'";
                }
            }

            $testContactSQL = "SELECT detailsID FROM EmergencyContact WHERE detailsID='$detailsID'";
            if(isset($testContactSQL)) {
                $result = mysqli_query($db,$testContactSQL) or die(mysqli_error($db));
                if(mysqli_fetch_array($result)['detailsID'] == $detailsID) {
                    $contact=true;
                    $sql = "SELECT a.firstname, a.middlename, a.lastname, a.email, a.mobile, b.buildingNumber, b.firstLine, b.secondLine, b.postcode, c.studentID, c.relationship, c.contactID FROM GeneralDetails a INNER JOIN Address b ON a.addressID=b.addressID INNER JOIN EmergencyContact c ON a.detailsID=c.detailsID WHERE a.detailsID='$detailsID'";
                }
            }

            if(isset($sql)) {
                $result = mysqli_query($db,$sql) or die(mysqli_error($db));
                $count = mysqli_num_rows($result);

                $result = mysqli_fetch_array($result);

                if($count >= 1) {
                    $firstname = ucfirst($result['firstname']);
                    $middlename = ucfirst($result['middlename']); 
                    $lastname = ucfirst($result['lastname']); 
                    $email = $result['email']; 
                    $mobile = $result['mobile'];
                    $buildingNumber = $result['buildingNumber'];
                    $firstLine = $result['firstLine'];
                    $secondLine = $result['secondLine'];
                    $postcode = $result['postcode']; 

                    if($student) {
                        $studentID = $result['studentID'];
                        $degreeCategory = $result['degreeCategory'];
                        $startYear = $result['startYear'];
                        $graduateYear = $result['graduateYear'];
                        $studentStatus = $result['studentStatus'];
                        $leaseNumber = $result['leaseNumber'];
                        $adviserID = $result['adviserID'];
                        $courseNumber = $result['courseNumber'];
                        $studentHide = "studentShow";
                        $maxMonthly = $result['maxMonthly'];
                    }
                    else if($staff) {
                        $staffID = $result['staffID'];
                        $position = $result['position'];
                        $location = $result['location'];
                        $staffHide = "staffShow";
                    }
                    else if($adviser) {
                        $adviserID = $result['adviserID'];
                        $universityID = $result['universityID'];
                        $adviserHide = "adviserShow";
                    }
                    else if($contact) {
                        $contactID = $result['contactID'];
                        $studentID = $result['studentID'];
                        $relationship = $result['relationship'];
                        $contactHide = "contactShow";
                    }

                }
                else {
                    header("location: PeopleSearch.php");
                }
            }
        }
        
        if (isset($_POST['update'])) {
        
            //Update Data

            $message = "";
            $valid=true;

            //error_log("Reached 1", 0);

            //Check shared fields
            if(isset($_POST['detailsID']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['mobile']) && isset($_POST['buildingNumber']) && isset($_POST['firstLine']) && isset($_POST['postcode'])) {
                //Generic Details Defined
                $detailsID = $_POST['detailsID'];
                $firstname = strtolower($_POST['firstname']);
                $middlename = strtolower($_POST['middlename']);
                $lastname = strtolower($_POST['lastname']);
                $email = $_POST['email'];
                $mobile = $_POST['mobile'];
                $buildingNumber = $_POST['buildingNumber'];
                $firstLine = $_POST['firstLine'];
                $secondLine = $_POST['secondLine'];
                $postcode = $_POST['postcode'];

                //Validate the email address
                if(!empty($email)) { //Not an empty field?
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Correct Format?
                        $message = "Please enter a valid email address";
                        $valid=false;
                    }
                }
                else {
                    $message = "Please enter a valid email address";
                    $valid=false;
                }

                if(!empty($mobile)) {
                    $mobile = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);
                    $mobile = str_replace("-", "", $mobile);
                    if (strlen($mobile)!=11) {
                        $message = "Please enter a valid UK mobile number";
                        $valid=false;
                    }
                }
                else {
                    $message = "Please enter a mobile number";
                    $valid=false;
                }

                if(!empty($postcode)) {
                    if (!(strlen($postcode)>=5 && strlen($postcode)<=8)) {
                        $message = "Please enter a valid UK postcode";
                        $valid=false;
                    }
                }
                else {
                    $message = "Please enter a postcode";
                    $valid=false;
                }

                if(empty($firstLine)) {
                    $message= "Please enter the first line address";
                    $valid=false;
                }

                if(empty($buildingNumber)) {
                    $message= "Please enter the building number";
                    $valid=false;
                }

                if(empty($firstname)) {
                    $message= "Please enter a first name";
                    $valid=false;
                }

                if(empty($lastname)) {
                    $message= "Please enter the last name";
                    $valid=false;
                }

                $sqlGeneric = "";

                $sqlGeneric = "UPDATE GeneralDetails a INNER JOIN Address b ON a.addressID=b.addressID SET a.firstname='$firstname', a.middlename='$middlename', a.lastname='$lastname', a.email='$email', a.mobile='$mobile', b.buildingNumber='$buildingNumber', b.firstLine='$firstLine', b.secondLine='$secondLine', b.postcode='$postcode' WHERE a.detailsID='$detailsID';";

                $sqlDistinct = "";

                //Check distinct fields of student
                if(!empty($_POST['studentID']) && isset($_POST['maxMonthly'])) {
                    $studentID = $_POST['studentID'];
                    $degreeCategory = $_POST['degreeCategory'];
                    $startYear = $_POST['startYear'];
                    $graduateYear = $_POST['graduateYear'];
                    $studentStatus = $_POST['studentStatus'];
                    $leaseNumber = $_POST['leaseNumber'];
                    $adviserID = $_POST['adviserID'];
                    $courseNumber = $_POST['courseNumber'];
                    $maxMonthly = $_POST['maxMonthly'];

                    if(!empty($graduateYear)) {
                        if(!($graduateYear > 2010 && $graduateYear < 2030)) {
                            $message = "Please enter a valid graduate year between 2010 and 2030";
                            $valid=false;
                        }
                    }

                    if(!empty($startYear)) {
                        if(!($startYear > 2010 && $startYear < 2030)) {
                            $message = "Please enter a valid start year between 2010 and 2030";
                            $valid=false;
                        }
                    }

                    if(!empty($leaseNumber)) {
                        if(!is_int((int)$leaseNumber)) {
                            $message = "Please enter a valid lease number";
                            $valid=false;
                        }
                    }

                    if(!empty($adviserID)) {
                        if(!is_int((int)$adviserID)) {
                            $message = "Please enter a valid adviser number";
                            $valid=false;
                        }
                    }
                    
                    if(!empty($maxMonthly)) {
                        if(!is_numeric((float)$maxMonthly)) {
                            $message = "Please enter maximum monthly rent (£GBP)";
                            $valid=false;
                        }
                    }
                    else {
                        $message = "Please enter maximum monthly rent (£GBP)";
                        $valid=false;
                    }
                    
                    if(empty($adviserID)) {
                        $sqlDistinct = "UPDATE Student SET degreeCategory='$degreeCategory', maxMonthly='$maxMonthly', startYear='$startYear', graduateYear='$graduateYear', studentStatus='$studentStatus', leaseNumber='$leaseNumber', adviserID=NULL, courseNumber='$courseNumber' WHERE detailsID='$detailsID';";
                    }
                    else {
                        $sqlDistinct = "UPDATE Student SET degreeCategory='$degreeCategory', maxMonthly='$maxMonthly', startYear='$startYear', graduateYear='$graduateYear', studentStatus='$studentStatus', leaseNumber='$leaseNumber', adviserID='$adviserID', courseNumber='$courseNumber' WHERE detailsID='$detailsID';";
                    }
                }
                //Check distinct fields of an Emergency Contact
                else if(!empty($_POST['relationship']) && !empty($_POST['studentID'])) {
                    $relationship = $_POST['relationship'];
                    $studentID = $_POST['studentID'];

                    if(!empty($studentID)) {
                        if(!is_int((int)$studentID)) {
                            $message = "Please enter a valid student Id";
                            $valid=false;
                        }
                    }
                    else {
                        $message = "Please enter a valid student Id";
                        $valid=false;
                    }

                    if(empty($relationship)) {
                        $message = "Please enter the emergency contact's relationship to the student";
                        $valid=false;   
                    }
                    
                    $sqlDistinct = "UPDATE EmergencyContact SET studentID='$studentID', relationship='$relationship' WHERE detailsID='$detailsID';";

                }
                //Check distinct fields of an Adviser
                else if(!empty($_POST['universityID'])) {
                    $universityID = $_POST['universityID'];

                    if(!empty($universityID)) {
                        if(!is_int((int)$universityID)) {
                            $message = "Please enter a valid university Id";
                            $valid=false;
                        }
                    }
                    else {
                        $message = "Please enter a valid university Id";
                        $valid=false;
                    }
                    $sqlDistinct = "UPDATE Adviser SET universityID='$universityID' WHERE detailsID='$detailsID';";
                }
                else if(!empty($_POST['staffID'])) {
                    $position = $_POST['position'];
                    $location = $_POST['location'];

                    $sqlDistinct = "UPDATE ResidentStaff SET position='$position', location='$location' WHERE detailsID='$detailsID';";
                }

                if($valid==true) {
                    $errors = [];
                    $db->autocommit(FALSE);
                    if(!$db->query($sqlGeneric)) {
                        $errors[] = $db->error;   
                    }
                    if(!$db->query($sqlDistinct)) {
                        $errors[] = $db->error;  
                    }

                    if (count($errors) === 0) {
                        $db->commit();
                        $message = "The update is successful";
                    } 
                    else {
                        $db->rollback();
                        foreach($errors as $e) {
                            $message = $message . "Error: " . $e . "<br>";    
                        }
                    }
                    $db->autocommit(TRUE); 
                }
            }
        }
        else if (isset($_POST['delete'])) {
            if(!empty($_POST['deleteRecord']) && !empty($_POST['studentID']) && !empty($_POST['adviserID'])) {
                $id = $_POST['studentID'];
                
                $sqlDelete = "DELETE a, b, c FROM EmergencyContact a INNER JOIN GeneralDetails b ON a.detailsID=b.detailsID INNER JOIN Address c ON b.addressID=c.addressID WHERE a.studentID='$id'";
                $sqlDelete2 = "DELETE a, b, c FROM Student a INNER JOIN GeneralDetails b ON a.detailsID=b.detailsID INNER JOIN Address c ON b.addressID=c.addressID WHERE a.studentID='$id'";

                $errors = [];
                $db->autocommit(FALSE);
                if(!$db->query($sqlDelete)) {
                    $errors[] = $db->error;   
                }
                if(!$db->query($sqlDelete2)) {
                    $errors[] = $db->error;  
                }

                if (count($errors) === 0) {
                    $db->commit();
                    $message = "The emergency contact has been deleted.";
                } 
                else {
                    $db->rollback();
                    foreach($errors as $e) {
                        $message = $message . "Error: " . $e . "<br>";    
                    }
                }
                $db->autocommit(TRUE); 
            }
            else if(!empty($_POST['deleteRecord']) && !empty($_POST['staffID'])) {
                $id = $_POST['staffID'];
                $sqlUpdate = "UPDATE HallOfResidents SET staffID = NULL WHERE staffID='$id'";
                $sqlUpdate1 = "UPDATE Inspection SET staffID = NULL WHERE staffID='$id'";
                $sqlDelete = "DELETE a, b, c, d FROM Login a INNER JOIN ResidentStaff b ON a.loginID=b.loginID INNER JOIN GeneralDetails c ON b.detailsID=c.detailsID INNER JOIN Address d ON c.addressID=d.addressID WHERE b.staffID='$id'";
                
                $errors = [];
                $db->autocommit(FALSE);
                if(!$db->query($sqlUpdate)) {
                    $errors[] = $db->error;   
                }
                if(!$db->query($sqlUpdate2)) {
                    $errors[] = $db->error;  
                }
                if(!$db->query($sqlDelete)) {
                    $errors[] = $db->error;  
                }

                if (count($errors) === 0) {
                    $db->commit();
                    $message = "The staff member has been deleted.";
                } 
                else {
                    $db->rollback();
                    foreach($errors as $e) {
                        $message = $message . "Error: " . $e . "<br>";    
                    }
                }
                $db->autocommit(TRUE);
            }
            else if(!empty($_POST['deleteRecord']) && !empty($_POST['adviserID']) && !empty($_POST['universityID'])) {
                $id = $_POST['adviserID'];
                $sqlUpdate = "UPDATE Student SET adviserID = NULL WHERE adviserID='$id'";
                $sqlDelete = "DELETE a, b, c FROM Adviser a INNER JOIN GeneralDetails b ON a.detailsID=b.detailsID INNER JOIN Address c ON b.addressID=c.addressID WHERE a.adviserID='$id'";
                
                $errors = [];
                $db->autocommit(FALSE);
                if(!$db->query($sqlUpdate)) {
                    $errors[] = $db->error;   
                }
                if(!$db->query($sqlDelete)) {
                    $errors[] = $db->error;  
                }

                if (count($errors) ===0) {
                    $db->commit();
                    $message = "The adviser has been deleted.";
                } 
                else {
                    $db->rollback();
                    foreach($errors as $e) {
                        $message = $message . "Error: " . $e . "<br>";    
                    }
                }
                $db->autocommit(TRUE);
            }
            else if(!empty($_POST['deleteRecord']) && !empty($_POST['contactID']) && !empty($_POST['relationship'])) {
                $id = $_POST['contactID'];
                $sqlDelete = "DELETE a, b, c FROM EmergencyContact a INNER JOIN GeneralDetails b ON a.detailsID=b.detailsID INNER JOIN Address c ON b.addressID=c.addressID WHERE a.contactID='$id'";
                
                $db->autocommit(FALSE);
                $db->query($sqlDelete);
                
                if ($db->commit()) {
                    $message = "The emergency contacts' record has been deleted!!";
                } 
                else {
                    $message = $db->error;
                }
                $db->autocommit(TRUE); 
            }
            else {
                $message = "You must confirm by ticking the box to delete the record!";
            }
        }
    }
?>

<html>
   
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="stylesheet.css?<?php echo time(); ?>">
    </head>
    
    <body>
        <ul>
            <li><a id="link" class="active" href="Dashboard.php">Dashboard</a></li>
            <li id="link"  style="float:right"><a href="Logout.php">Logout</a></li>
            <li style="float:right"><a><?php if(isset($username)){echo $username;}?></a></li>
        </ul>
        <div class="updateResults">
            <form action = "" method = "post">
                <a><?php if(isset($message)){echo $message;}?></a>
                <p>First Name</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($firstname)){echo ucfirst($firstname);} ?>" type = "text" name = "firstname"/>
                
                <p>Middle Name</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($middlename) && !empty($middlename)){echo ucfirst($middlename);}?>" type = "text" name = "middlename"/>
                
                <p>Last Name</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($lastname)){echo ucfirst($lastname);} ?>" type = "text" name = "lastname"/>
                
                <p>Email</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($email)){echo $email;} ?>" type = "email" name = "email"/>
                
                <p>Mobile</p>
                <input class="resultField" value="<?php if(isset($mobile)){echo $mobile;} ?>" type = "text" name = "mobile"/>
                
                <p>Building Number</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($buildingNumber)){echo $buildingNumber;} ?>" type = "text" name = "buildingNumber"/>
                
                <p>First Line Address</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($firstLine)){echo $firstLine;} ?>" type = "text" name = "firstLine"/>
                
                <p>Second Line Address</p>
                <input placeholder="N/A" placeholder="N/A" class="resultField" value="<?php if(isset($secondLine) && !empty($secondLine)){echo $secondLine;} ?>" type = "text" name = "secondLine"/>
                
                <p>Postcode</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($postcode)){echo $postcode;} ?>" type = "text" name = "postcode"/>
                
                <p id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>">Student Id</p>
                <input placeholder="N/A" id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>" class="resultField" value="<?php if(isset($studentID) && !empty($studentID)){echo $studentID;}?>" type = "text" name = "studentID" readonly/>
                
                <p id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>">Degree Category</p>
                <input placeholder="N/A" id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>" class="resultField" value="<?php if(isset($degreeCategory)){echo $degreeCategory;} ?>" type = "text" name = "degreeCategory"/>
                
                <p id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>">Start Year</p>
                <input placeholder="N/A" id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>" class="resultField" value="<?php if(isset($startYear)){echo $startYear;} ?>" type = "text" name = "startYear"/>
                
                <p id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>">Graduate Year</p>
                <input placeholder="N/A" id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>" class="resultField" value="<?php if(isset($graduateYear)){echo $graduateYear;} ?>" type = "text" name = "graduateYear"/>
                
                <p id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>">Student Status</p>
                <input placeholder="N/A" id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>" class="resultField" value="<?php if(isset($studentStatus)){echo $studentStatus;} ?>" type = "text" name = "studentStatus"/>
                
                <p id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>">Lease Number</p>
                <input placeholder="N/A" id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>" class="resultField" value="<?php if(isset($leaseNumber)){echo $leaseNumber;} ?>" type = "text" name = "leaseNumber"/>
                
                <p id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>">Adviser Id</p>
                <input placeholder="N/A" id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>" class="resultField" value="<?php if(isset($adviserID)){echo $adviserID;} ?>" type = "text" name = "adviserID"/>
                
                <p id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>">Course Number</p>
                <input placeholder="N/A" id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>" class="resultField" value="<?php if(isset($courseNumber)){echo $courseNumber;} ?>" type = "text" name = "courseNumber"/>
                
                <p id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>">Max Monthly Rent (£GBP)</p>
                <input placeholder="N/A" id="<?php if(isset($studentHide) && !empty($studentHide)){echo $studentHide;} else {echo "studentHide";} ?>" class="resultField" value="<?php if(isset($maxMonthly)){echo $maxMonthly;} ?>" type = "text" name = "maxMonthly"/>
                
                <p id="<?php if(isset($staffHide) && !empty($staffHide)){echo $staffHide;} else {echo "staffHide";} ?>">Staff Id</p>
                <input placeholder="N/A" id="<?php if(isset($staffHide) && !empty($staffHide)){echo $staffHide;} else {echo "staffHide";} ?>" class="resultField" value="<?php if(isset($staffID)){echo $staffID;} ?>" type = "text" name = "staffID" readonly/>
                
                <p id="<?php if(isset($staffHide) && !empty($staffHide)){echo $staffHide;} else {echo "staffHide";} ?>">Position</p>
                <input placeholder="N/A" id="<?php if(isset($staffHide) && !empty($staffHide)){echo $staffHide;} else {echo "staffHide";} ?>" class="resultField" value="<?php if(isset($position)){echo $position;} ?>" type = "text" name = "position"/>
                
                <p id="<?php if(isset($staffHide) && !empty($staffHide)){echo $staffHide;} else {echo "staffHide";} ?>">Location</p>
                <input placeholder="N/A" id="<?php if(isset($staffHide) && !empty($staffHide)){echo $staffHide;} else {echo "staffHide";} ?>" class="resultField" value="<?php if(isset($location)){echo $location;} ?>" type = "text" name = "location"/>
                
                <p id="<?php if(isset($adviserHide) && !empty($adviserHide)){echo $adviserHide;} else {echo "adviserHide";} ?>">Adviser Id</p>
                <input placeholder="N/A" id="<?php if(isset($adviserHide) && !empty($adviserHide)){echo $adviserHide;} else {echo "adviserHide";} ?>" class="resultField" value="<?php if(isset($adviserID)){echo $adviserID;} ?>" type = "text" name = "adviserID" readonly/>
                
                <p id="<?php if(isset($adviserHide) && !empty($adviserHide)){echo $adviserHide;} else {echo "adviserHide";} ?>">University Id</p>
                <input placeholder="N/A" id="<?php if(isset($adviserHide) && !empty($adviserHide)){echo $adviserHide;} else {echo "adviserHide";} ?>" class="resultField" value="<?php if(isset($universityID)){echo $universityID;} ?>" type = "text" name = "universityID"/>
                
                <p id="<?php if(isset($contactHide) && !empty($contactHide)){echo $contactHide;} else {echo "contactHide";} ?>">Contact Id</p>
                <input placeholder="N/A" id="<?php if(isset($contactHide) && !empty($contactHide)){echo $contactHide;} else {echo "contactHide";} ?>" class="resultField" value="<?php if(isset($contactID)){echo $contactID;} ?>" type = "text" name = "contactID" readonly/>
                
                <p id="<?php if(isset($contactHide) && !empty($contactHide)){echo $contactHide;} else {echo "contactHide";} ?>">Student Id</p>
                <input placeholder="N/A" id="<?php if(isset($contactHide) && !empty($contactHide)){echo $contactHide;} else {echo "contactHide";} ?>" class="resultField" value="<?php if(isset($studentID)){echo $studentID;} ?>" type = "text" name = "studentID"/>
                
                <p id="<?php if(isset($contactHide) && !empty($contactHide)){echo $contactHide;} else {echo "contactHide";} ?>">Relationship</p>
                <input placeholder="N/A" id="<?php if(isset($contactHide) && !empty($contactHide)){echo $contactHide;} else {echo "contactHide";} ?>" class="resultField" value="<?php if(isset($relationship)){echo $relationship;} ?>" type = "text" name = "relationship"/>
                
                <input type="hidden" name="detailsID" value="<?php if(isset($detailsID)){echo $detailsID;} ?>" /> 
                                
                <hr>
                     
                <input type="submit" name="update" value="Update Details"/>
                
                <hr>
                
                <div style="display:flex; justify-content:center;">
                
                    <a>Tick the box to delete the record</a><input type="checkbox" name="deleteRecord" value="Yes"/>
                    
                </div>
                
                <hr>
                
                <input type="submit" name="delete" value="Delete Record"/>
                
                <hr>
                
                <a href="PeopleSearch.php"><input class="backButton" type="button" value="Create New Search" /></a>
                
            </form>
        </div>
        
    </body>
</html>

