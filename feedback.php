<?php
// Initialize variables to store form data
$formSubmitted = false;
$formErrors = [];
$current_year = date("Y");

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    $requiredFields = ['fname', 'lname', 'email', 'visit-date', 'treatment', 'overall-rating', 
                     'cleanliness', 'staff', 'wait-time', 'quality', 'satisfaction'];
    
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $formErrors[] = "Please fill in all required fields";
            break;
        }
    }
    
    // If no errors, process the form
    if (empty($formErrors)) {
        // Get form data
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'];
        $visitDate = $_POST['visit-date'];
        $treatment = $_POST['treatment'];
        $overallRating = $_POST['overall-rating'];
        $cleanliness = $_POST['cleanliness'];
        $staff = $_POST['staff'];
        $waitTime = $_POST['wait-time'];
        $quality = $_POST['quality'];
        $satisfaction = $_POST['satisfaction'];
        $improvements = $_POST['improvements'] ?? '';
        $comments = $_POST['comments'] ?? '';
        $recommend = isset($_POST['recommend']) ? 'Yes' : 'No';
        $contactPermission = isset($_POST['contact-permission']) ? 'Yes' : 'No';
        
        // Store feedback in database (example - replace with your actual database code)
        // Connect to database
        /*
        $servername = "localhost";
        $username = "your_username";
        $password = "your_password";
        $dbname = "dental_clinic";
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Prepare SQL statement
        $sql = "INSERT INTO feedback (fname, lname, phone, email, visit_date, treatment, overall_rating, 
                cleanliness, staff, wait_time, quality, satisfaction, improvements, comments, recommend, contact_permission) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssss", $fname, $lname, $phone, $email, $visitDate, $treatment, 
                          $overallRating, $cleanliness, $staff, $waitTime, $quality, $satisfaction, 
                          $improvements, $comments, $recommend, $contactPermission);
        
        // Execute query
        if ($stmt->execute()) {
            $formSubmitted = true;
        } else {
            $formErrors[] = "Error: " . $stmt->error;
        }
        
        // Close connection
        $stmt->close();
        $conn->close();
        */
        
        // For now, just send an email with the feedback
        $to = "mdclinicphagwarapb@gmail.com";
        $subject = "Feedback from $fname $lname";
        
        $message = "Patient Feedback Details:\n\n";
        $message .= "Name: $fname $lname\n";
        $message .= "Phone: $phone\n";
        $message .= "Email: $email\n";
        $message .= "Visit Date: $visitDate\n";
        $message .= "Treatment: $treatment\n";
        $message .= "Overall Rating: $overallRating/5\n";
        $message .= "Cleanliness: $cleanliness\n";
        $message .= "Staff: $staff\n";
        $message .= "Wait Time: $waitTime\n";
        $message .= "Quality of Treatment: $quality\n";
        $message .= "Satisfaction: $satisfaction\n";
        $message .= "Improvements: $improvements\n";
        $message .= "Comments: $comments\n";
        $message .= "Would Recommend: $recommend\n";
        $message .= "Contact Permission: $contactPermission\n";
        
        $headers = "From: $email";
        
        // Comment out mail() for testing
        mail($to, $subject, $message, $headers);
        
        // Set success flag
        $formSubmitted = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Modern Dental Clinic - Feedback Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,700,900"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
  
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/style.css">
    
    <style>
      .feedback-header {
        background: linear-gradient(to right, #308494, #5ca9cf);
        padding: 30px 0;
        margin-bottom: 40px;
        border-radius: 5px;
      }
      
      .feedback-title {
        color: white;
        font-weight: bold;
      }
      
      .feedback-subtitle {
        color: rgba(255, 255, 255, 0.8);
      }
      
      .rating-container {
        margin-bottom: 2rem;
      }
      
      .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
      }
      
      .rating-stars input {
        display: none;
      }
      
      .rating-stars label {
        cursor: pointer;
        font-size: 30px;
        color: #ddd;
        transition: 0.3s;
        margin-right: 5px;
      }
      
      .rating-stars label:hover,
      .rating-stars label:hover ~ label,
      .rating-stars input:checked ~ label {
        color: #FFC107;
      }
      
      .rating-label {
        margin-bottom: 0.5rem;
        font-weight: bold;
        display: block;
      }
      
      .aspect-rating {
        margin-bottom: 1.5rem;
      }
      
      .radio-item {
        margin-right: 15px;
        display: inline-block;
      }
      
      .feedback-form-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        padding: 30px;
        margin-bottom: 50px;
      }
      
      .icon-row {
        margin-bottom: 30px;
        text-align: center;
      }
      
      .icon-box {
        display: inline-block;
        width: 80px;
        height: 80px;
        line-height: 80px;
        text-align: center;
        border-radius: 50%;
        background: linear-gradient(to right, #308494, #5ca9cf);
        color: white;
        font-size: 40px;
        margin: 0 15px;
        transition: transform 0.3s;
      }
      
      .icon-box:hover {
        transform: scale(1.1);
      }
      
      .btn-gradient {
        background: linear-gradient(to right, #308494, #5ca9cf);
        border: none;
        color: white;
        transition: all 0.3s;
      }
      
      .btn-gradient:hover {
        background: linear-gradient(to right, #5ca9cf, #308494);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(48, 132, 148, 0.3);
      }
      
      .form-control:focus {
        border-color: #5ca9cf;
        box-shadow: 0 0 0 0.2rem rgba(92, 169, 207, 0.25);
      }
      
      .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #5ca9cf;
        border-color: #308494;
      }
      
      .satisfaction-emoji {
        font-size: 24px;
        margin-right: 10px;
      }
      body {
    background-image: url('images/oik.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
  }
    </style>
  </head>
  <body>
 
    <div class="site-wrap">

        <div class="site-mobile-menu">
          <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close mt-3">
              <span class="icon-close2 js-menu-toggle"></span>
            </div>
          </div>
          <div class="site-mobile-menu-body"></div>
        </div> <!-- .site-mobile-menu -->
        
        <div class="site-navbar-wrap" style="box-shadow: 2PX 2px 4px rgba(0, 0, 0, 0.1)">
    
          <div class="site-navbar-top">
            <div class="container py-2">
              <div class="row align-items-center">
                <div class="col-6">
               
                  <a href="https://www.instagram.com/moderndentalclinicphagwara?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="p-2 pl-0"><span class="icon-instagram"></span></a>
                </div>
                <div class="col-6">
                  <div class="d-flex ml-auto">
                    
                    <a href="#" class="d-flex align-items-center ml-auto mr-4">
                      <span class="icon-phone mr-2"></span>
                      <span class="d-none d-md-inline-block "style="text-shadow: 2px 2px 4px #FFFFFF; color: black;">90419-11360</span>
                    </a>
                    <a href="#" class="d-flex align-items-center">
                      <span class="icon-envelope mr-2"></span>
                      <span class="d-none d-md-inline-block" style="text-shadow: 2px 2px 4px #FFFFFF; color: black;">mdclinicphagwarapb@gmail.com</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="site-navbar">
            <div class="container py-1">
              <div class="row align-items-center">
                <div class="col-2">
    <h4 class="mb-0 site-logo" style="text-shadow: 2px 2px 4px #000000;"><a href="index.html"><strong> Modern Dental Clinic Phagwara</strong></a></h4>            </div>
                <div class="col-10">
                  <nav class="site-navigation text-right" role="navigation">
                    <div class="container">
                      <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#" class="site-menu-toggle js-menu-toggle text-white"><span class="icon-menu h3"></span></a></div>
    
                      <ul class="site-menu js-clone-nav d-none d-lg-block">
                        <li><a href="contact.php">Book</a></li>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.html">About Us</a></li>
                        <li><a href="services.html">Services</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    <li class="active"><a href="feedback.php">Feedback</a></li>

                      </ul>
                    </div>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
    <!-- Main Content - Feedback Form -->
    <div class="site-section" style="background: linear-gradient(to bottom, #5ca9cf, #ffffff);">

        <div class="container">
<br>        
       <div class="feedback-header text-center" data-aos="fade-up" style="background-image: url('images/oik.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <h2 class="feedback-title" style="backdrop-filter: blur(10px); background-color: rgba(255, 255, 255, 0.2); padding: 10px; border-radius: 10px; display: inline-block;">PATIENT FEEDBACK</h2><br>

<p class="feedback-subtitle" style="backdrop-filter: blur(10px); background-color: rgba(255, 255, 255, 0.2); padding: 10px; border-radius: 10px; display: inline-block;" ">We value your thoughts on your dental experience with us</p>
        </div>
        
        <div class="row">
          <div class="col-lg-8 mx-auto">
            
            <div class="icon-row" data-aos="fade-up">
              <div class="icon-box">
                <span class="flaticon-tooth"></span>
              </div>
              <div class="icon-box">
                <span class="flaticon-tooth-whitening"></span>
              </div>
              <div class="icon-box">
                <span class="flaticon-tooth-pliers"></span>
              </div>
            </div>
            
            <div class="feedback-form-container" data-aos="fade-up">
              <?php if ($formSubmitted): ?>
                <div class="alert alert-success">
                  <h4 class="alert-heading">Thank you for your feedback!</h4>
                  <p>We appreciate you taking the time to share your experience with us. Your feedback helps us improve our services.</p>
                  <hr>
                  <p class="mb-0">We'll review your comments and may contact you if you've given us permission to do so.</p>
                </div>
                <div class="text-center mt-4">
                  <a href="index.php" class="btn btn-primary btn-lg btn-gradient px-5">Return to Home</a>
                </div>
              <?php else: ?>
                <?php if (!empty($formErrors)): ?>
                  <div class="alert alert-danger">
                    <ul class="mb-0">
                      <?php foreach ($formErrors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endif; ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                  
                  <div class="row form-group">
                    <div class="col-md-6 mb-3 mb-md-0">
                      <label class="font-weight-bold" for="fname">First Name</label>
                      <input type="text" id="fname" class="form-control" placeholder="First Name" name="fname" value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                      <label class="font-weight-bold" for="lname">Last Name</label>
                      <input type="text" id="lname" class="form-control" placeholder="Last Name" name="lname" value="<?php echo isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : ''; ?>" required>
                    </div>
                  </div>
                  
                  <div class="row form-group">
                    <div class="col-md-6 mb-3 mb-md-0">
                      <label class="font-weight-bold" for="phone">Phone Number</label>
                      <input type="tel" id="phone" class="form-control" placeholder="Phone Number" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                      <label class="font-weight-bold" for="email">Email</label> 
                      <input type="email" id="email" class="form-control" placeholder="Email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>
                  </div>
                  
                  <div class="row form-group">
                    <div class="col-md-6 mb-3 mb-md-0">
                      <label class="font-weight-bold" for="visit-date">Date of Visit</label> 
                      <input type="date" id="visit-date" class="form-control px-2" name="visit-date" value="<?php echo isset($_POST['visit-date']) ? htmlspecialchars($_POST['visit-date']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                      <label class="font-weight-bold" for="treatment">Treatment Received</label> 
                      <select name="treatment" id="treatment" class="form-control" required>
                        <option value="">Select treatment</option>
                        <option value="Dental exam" <?php echo (isset($_POST['treatment']) && $_POST['treatment'] === 'Dental exam') ? 'selected' : ''; ?>>Dental exam</option>
                        <option value="Dental emergency" <?php echo (isset($_POST['treatment']) && $_POST['treatment'] === 'Dental emergency') ? 'selected' : ''; ?>>Dental emergency</option>
                        <option value="Teeth cleaning" <?php echo (isset($_POST['treatment']) && $_POST['treatment'] === 'Teeth cleaning') ? 'selected' : ''; ?>>Teeth cleaning</option>
                        <option value="Teeth whitening" <?php echo (isset($_POST['treatment']) && $_POST['treatment'] === 'Teeth whitening') ? 'selected' : ''; ?>>Teeth whitening</option>
                        <option value="Extraction" <?php echo (isset($_POST['treatment']) && $_POST['treatment'] === 'Extraction') ? 'selected' : ''; ?>>Extraction</option>
                        <option value="Trauma surgery" <?php echo (isset($_POST['treatment']) && $_POST['treatment'] === 'Trauma surgery') ? 'selected' : ''; ?>>Trauma surgery</option>
                        <option value="Laser filling" <?php echo (isset($_POST['treatment']) && $_POST['treatment'] === 'Laser filling') ? 'selected' : ''; ?>>Laser filling</option>
                        <option value="Other" <?php echo (isset($_POST['treatment']) && $_POST['treatment'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                      </select>
                    </div>
                  </div>
                  
                  <div class="row form-group">
                    <div class="col-md-12">
                      <div class="rating-container">
                        <label class="rating-label">How would you rate your overall experience?</label>
                        <div class="rating-stars">
                          <input type="radio" id="star5" name="overall-rating" value="5" <?php echo (isset($_POST['overall-rating']) && $_POST['overall-rating'] === '5') ? 'checked' : ''; ?> required>
                          <label for="star5">★</label>
                          <input type="radio" id="star4" name="overall-rating" value="4" <?php echo (isset($_POST['overall-rating']) && $_POST['overall-rating'] === '4') ? 'checked' : ''; ?>>
                          <label for="star4">★</label>
                          <input type="radio" id="star3" name="overall-rating" value="3" <?php echo (isset($_POST['overall-rating']) && $_POST['overall-rating'] === '3') ? 'checked' : ''; ?>>
                          <label for="star3">★</label>
                          <input type="radio" id="star2" name="overall-rating" value="2" <?php echo (isset($_POST['overall-rating']) && $_POST['overall-rating'] === '2') ? 'checked' : ''; ?>>
                          <label for="star2">★</label>
                          <input type="radio" id="star1" name="overall-rating" value="1" <?php echo (isset($_POST['overall-rating']) && $_POST['overall-rating'] === '1') ? 'checked' : ''; ?>>
                          <label for="star1">★</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="aspect-rating">
                        <label class="rating-label">Our dental facility cleanliness</label>
                        <div>
                          <div class="radio-item">
                            <input type="radio" id="cleanliness1" name="cleanliness" value="Excellent" <?php echo (isset($_POST['cleanliness']) && $_POST['cleanliness'] === 'Excellent') ? 'checked' : ''; ?> required>
                            <label for="cleanliness1">Excellent</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="cleanliness2" name="cleanliness" value="Good" <?php echo (isset($_POST['cleanliness']) && $_POST['cleanliness'] === 'Good') ? 'checked' : ''; ?>>
                            <label for="cleanliness2">Good</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="cleanliness3" name="cleanliness" value="Average" <?php echo (isset($_POST['cleanliness']) && $_POST['cleanliness'] === 'Average') ? 'checked' : ''; ?>>
                            <label for="cleanliness3">Average</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="cleanliness4" name="cleanliness" value="Poor" <?php echo (isset($_POST['cleanliness']) && $_POST['cleanliness'] === 'Poor') ? 'checked' : ''; ?>>
                            <label for="cleanliness4">Poor</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-6">
                      <div class="aspect-rating">
                        <label class="rating-label">Staff friendliness and professionalism</label>
                        <div>
                          <div class="radio-item">
                            <input type="radio" id="staff1" name="staff" value="Excellent" <?php echo (isset($_POST['staff']) && $_POST['staff'] === 'Excellent') ? 'checked' : ''; ?> required>
                            <label for="staff1">Excellent</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="staff2" name="staff" value="Good" <?php echo (isset($_POST['staff']) && $_POST['staff'] === 'Good') ? 'checked' : ''; ?>>
                            <label for="staff2">Good</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="staff3" name="staff" value="Average" <?php echo (isset($_POST['staff']) && $_POST['staff'] === 'Average') ? 'checked' : ''; ?>>
                            <label for="staff3">Average</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="staff4" name="staff" value="Poor" <?php echo (isset($_POST['staff']) && $_POST['staff'] === 'Poor') ? 'checked' : ''; ?>>
                            <label for="staff4">Poor</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="aspect-rating">
                        <label class="rating-label">Wait time experience</label>
                        <div>
                          <div class="radio-item">
                            <input type="radio" id="wait1" name="wait-time" value="Excellent" <?php echo (isset($_POST['wait-time']) && $_POST['wait-time'] === 'Excellent') ? 'checked' : ''; ?> required>
                            <label for="wait1">Excellent</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="wait2" name="wait-time" value="Good" <?php echo (isset($_POST['wait-time']) && $_POST['wait-time'] === 'Good') ? 'checked' : ''; ?>>
                            <label for="wait2">Good</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="wait3" name="wait-time" value="Average" <?php echo (isset($_POST['wait-time']) && $_POST['wait-time'] === 'Average') ? 'checked' : ''; ?>>
                            <label for="wait3">Average</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="wait4" name="wait-time" value="Poor" <?php echo (isset($_POST['wait-time']) && $_POST['wait-time'] === 'Poor') ? 'checked' : ''; ?>>
                            <label for="wait4">Poor</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-6">
                      <div class="aspect-rating">
                        <label class="rating-label">Quality of treatment</label>
                        <div>
                          <div class="radio-item">
                            <input type="radio" id="quality1" name="quality" value="Excellent" <?php echo (isset($_POST['quality']) && $_POST['quality'] === 'Excellent') ? 'checked' : ''; ?> required>
                            <label for="quality1">Excellent</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="quality2" name="quality" value="Good" <?php echo (isset($_POST['quality']) && $_POST['quality'] === 'Good') ? 'checked' : ''; ?>>
                            <label for="quality2">Good</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="quality3" name="quality" value="Average" <?php echo (isset($_POST['quality']) && $_POST['quality'] === 'Average') ? 'checked' : ''; ?>>
                            <label for="quality3">Average</label>
                          </div>
                          <div class="radio-item">
                            <input type="radio" id="quality4" name="quality" value="Poor" <?php echo (isset($_POST['quality']) && $_POST['quality'] === 'Poor') ? 'checked' : ''; ?>>
                            <label for="quality4">Poor</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row form-group mt-4">
                    <div class="col-md-12">
                      <label class="font-weight-bold">How satisfied were you with your visit?</label>
                      <div class="d-flex align-items-center mt-2">
                        <div class="radio-item">
                          <input type="radio" id="satisfaction1" name="satisfaction" value="Very Satisfied" <?php echo (isset($_POST['satisfaction']) && $_POST['satisfaction'] === 'Very Satisfied') ? 'checked' : ''; ?> required>
                          <label for="satisfaction1"><span class="satisfaction-emoji">😃</span> Very Satisfied</label>
                        </div>
                        <div class="radio-item">
                          <input type="radio" id="satisfaction2" name="satisfaction" value="Satisfied" <?php echo (isset($_POST['satisfaction']) && $_POST['satisfaction'] === 'Satisfied') ? 'checked' : ''; ?>>
                          <label for="satisfaction2"><span class="satisfaction-emoji">🙂</span> Satisfied</label>
                        </div>
                        <div class="radio-item">
                          <input type="radio" id="satisfaction3" name="satisfaction" value="Neutral" <?php echo (isset($_POST['satisfaction']) && $_POST['satisfaction'] === 'Neutral') ? 'checked' : ''; ?>>
                          <label for="satisfaction3"><span class="satisfaction-emoji">😐</span> Neutral</label>
                        </div>
                        <div class="radio-item">
                          <input type="radio" id="satisfaction4" name="satisfaction" value="Dissatisfied" <?php echo (isset($_POST['satisfaction']) && $_POST['satisfaction'] === 'Dissatisfied') ? 'checked' : ''; ?>>
                          <label for="satisfaction4"><span class="satisfaction-emoji">🙁</span> Dissatisfied</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row form-group mt-4">
                    <div class="col-md-12">
                      <label class="font-weight-bold" for="improvements">What could we improve?</label> 
                      <textarea name="improvements" id="improvements" cols="30" rows="4" class="form-control" placeholder="Please share your suggestions for improvement..."><?php echo isset($_POST['improvements']) ? htmlspecialchars($_POST['improvements']) : ''; ?></textarea>
                    </div>
                  </div>
                  
                  <div class="row form-group mt-4">
                    <div class="col-md-12">
                      <label class="font-weight-bold" for="comments">Additional Comments</label> 
                      <textarea name="comments" id="comments" cols="30" rows="4" class="form-control" placeholder="Tell us more about your experience..."><?php echo isset($_POST['comments']) ? htmlspecialchars($_POST['comments']) : ''; ?></textarea>
                    </div>
                  </div>
                  
                  <div class="row form-group mt-4">
                    <div class="col-md-12">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="recommend" name="recommend" <?php echo isset($_POST['recommend']) ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="recommend">I would recommend Modern Dental Clinic to friends and family</label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row form-group">
                    <div class="col-md-12">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="contact-permission" name="contact-permission" <?php echo isset($_POST['contact-permission']) ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="contact-permission">I give permission for Modern Dental Clinic to contact me regarding my feedback</label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row form-group mt-4">
                    <div class="col-md-12 text-center">
                      <input type="submit" value="Submit Feedback" class="btn btn-primary btn-lg btn-gradient px-5">
                    </div>
                  </div>
                </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <footer class="site-footer" style="background: linear-gradient(to bottom, #a40c0c, #000000);">

      <div class="container">
        <div class="row">
          
          <div class="col-lg-4 mx-auto">
            <h2 class="footer-heading mb-4">Quick Links</h2>
            <ul class="list-unstyled">
              <li><a href="index.php">Home</a></li>
              <li><a href="about.html">About Us</a></li>
              <li><a href="services.html">Services</a></li>
              <li><a href="contact.php">Contact</a></li>
              <li><a href="feedback.php">Feedback</a></li>
            </ul>
          </div>
          <div class="col-lg-4">
            <h2 class="footer-heading mb-4">Connect With Us</h2>
            <div class="social-links">
              <a href="https://www.instagram.com/moderndentalclinicphagwara?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="instagram"><span class="icon-instagram"></span></a>
            </div>
            <div class="mt-4">
              <p class="mb-2"><span class="icon-room mr-2"></span> Model Town, Phagwara, Punjab 144401</p>
              <p class="mb-2"><span class="icon-phone mr-2"></span>90419-11360</p>
              <p class="mb-2"><span class="icon-envelope mr-2"></span>mdclinicphagwarapb@gmail.com</p>
            </div>
          </div>
        </div>
        <div class="row text-center">
          <div class="col-md-12">
            <div class="border-top pt-5">
            Copyright &copy; <?php echo $current_year; ?> All rights reserved to <a href="https://rakshitsharma.netlify.app/">Rakshit Sharma</a>

            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/aos.js"></script>

  <script src="js/main.js"></script>
    
  </body>
</html>