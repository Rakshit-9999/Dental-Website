<?php
// Initialize variables
session_start();
$current_year = date("Y");
$success_message = "";
$error_message = "";
$login_message = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check which form was submitted
    if (isset($_POST['appointment_submit'])) {
        // Appointment form processing
        $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
        $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
        $appointment_date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
        $appointment_time = filter_input(INPUT_POST, 'appointment_time', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $treatment = filter_input(INPUT_POST, 'treatment', FILTER_SANITIZE_STRING);
        $notes = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING);
        
        // Validate inputs
        if (!$fname || !$lname || !$appointment_date || !$appointment_time || !$email || !$treatment) {
            $error_message = "Please fill in all required fields.";
        } else {
            // Here you would typically save to database or send an email
            // For now, we'll just set a success message
            $success_message = "Thank you, $fname! Your appointment has been scheduled for $appointment_date at $appointment_time.";
            
            // Optional: Send email notification
            // mail("mdclinicphagwarapb@gmail.com", "New Appointment Request", "Name: $fname $lname\nDate: $appointment_date\nTime: $appointment_time\nEmail: $email\nTreatment: $treatment\nNotes: $notes");
        }
    } elseif (isset($_POST['feedback_submit'])) {
        // Feedback form processing
        $feedback = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
        
        if (!$feedback) {
            $error_message = "Please enter your feedback.";
        } else {
            $success_message = "Thank you for your feedback!";
            
            // Optional: Save feedback to database or send email
            // mail("mdclinicphagwarapb@gmail.com", "New Feedback", "Feedback: $feedback");
        }
    } elseif (isset($_POST['login_submit'])) {
        // Login form processing
        $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);
        $otp = filter_input(INPUT_POST, 'otp', FILTER_SANITIZE_STRING);
        $user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING);
        
        // Validate login (in a real scenario, you would verify against a database)
        if (!$contact || !$otp) {
            $login_message = "error:Please fill in all required fields.";
        } else {
            // For demonstration purposes, we'll accept any OTP as "1234"
            if ($otp == "1234") {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_type'] = $user_type;
                $_SESSION['contact'] = $contact;
                
                // Redirect based on user type
                if ($user_type == 'admin') {
                    header("Location: admin-dashboard.php");
                    exit;
                } else {
                    $login_message = "success:Login successful! Welcome to MD Clinic.";
                }
            } else {
                $login_message = "error:Invalid OTP. Please try again.";
            }
        }
    } elseif (isset($_POST['send_otp'])) {
        // OTP request processing
        $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);
        $contact_type = filter_input(INPUT_POST, 'contact_type', FILTER_SANITIZE_STRING);
        
        if (!$contact) {
            $login_message = "error:Please enter your contact information.";
        } else {
            // Validate contact based on type
            $is_valid = false;
            if ($contact_type == 'mobile' && preg_match('/^\d{10}$/', $contact)) {
                $is_valid = true;
            } elseif ($contact_type == 'email' && filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                $is_valid = true;
            }
            
            if ($is_valid) {
                // In a real application, you would generate and send an OTP
                // For demo purposes, we'll just pretend we sent "1234"
                $login_message = "success:OTP sent to your $contact_type. Please check and enter below.";
                $_SESSION['otp_sent'] = true;
                $_SESSION['contact'] = $contact;
            } else {
                $login_message = "error:Invalid $contact_type format. Please try again.";
            }
        }
    } elseif (isset($_POST['logout'])) {
        // Logout processing
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}

// Get promo end date
$promo_end_date = "2025/05/28 ";

// Check if user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
$otp_sent = isset($_SESSION['otp_sent']) ? $_SESSION['otp_sent'] : false;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Modern Dental Clinic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">

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
    
    <!-- Add custom styles for notifications and login modal -->
    <style>
      /* Existing notification styles */
      .notification {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
      }
      .success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
      }
      .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
      }
      
      /* Login modal styles */
      .login-container {
        max-width: 450px;
        margin: 0 auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      }
      
      .login-tabs {
        display: flex;
        margin-bottom: 20px;
        border-bottom: 1px solid #ddd;
      }
      
      .login-tab {
        flex: 1;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        color: #555;
        background-color: #f9f9f9;
      }
      
      .login-tab.active {
        background-color: #fff;
        color: #0055cc;
        font-weight: bold;
        border-bottom: 3px solid #0055cc;
      }
      
      .otp-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
      }
      
      .otp-input {
        width: 50px;
        height: 50px;
        text-align: center;
        font-size: 24px;
        border: 1px solid #ddd;
        border-radius: 5px;
      }
      
      /* User menu styles */
      .user-menu {
        position: relative;
        display: inline-block;
      }
      
      .user-menu-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: #fff;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        border-radius: 5px;
      }
      
      .user-menu-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
      }
      
      .user-menu-content a:hover {
        background-color: #f1f1f1;
      }
      
      .user-menu:hover .user-menu-content {
        display: block;
      }
      
      .user-icon {
        display: flex;
        align-items: center;
        cursor: pointer;
        color: white;
      }
      
      .user-icon span {
        margin-left: 5px;
      }
      
      /* Admin dashboard styles */
      .admin-section {
        margin-top: 30px;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
      }
      
      .appointment-card {
        background-color: #fff;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      }
      
      .appointment-header {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 10px;
      }
      
      .appointment-actions {
        margin-top: 10px;
      }
      
      .accept-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 3px;
        cursor: pointer;
      }
      
      .reject-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 3px;
        margin-left: 10px;
        cursor: pointer;
      }
      
      /* OTP verification form */
      .verification-form {
        display: <?php echo $otp_sent ? 'block' : 'none'; ?>;
      }
      
      .contact-form {
        display: <?php echo $otp_sent ? 'none' : 'block'; ?>;
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
    
    
    <div class="site-navbar-wrap">
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
                  <span class="d-none d-md-inline-block">90419-11360</span>
                </a>
                <a href="#" class="d-flex align-items-center">
                  <span class="icon-envelope mr-2"></span>
                  <span class="d-none d-md-inline-block">mdclinicphagwarapb@gmail.com</span>
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
              <h2 class="mb-0 site-logo"><a href="index.php">Modern Dental Clinic Phagwara</a></h2>
            </div>
            <div class="col-10">
              <nav class="site-navigation text-right" role="navigation">
                <div class="container">
                  <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#" class="site-menu-toggle js-menu-toggle text-white"><span class="icon-menu h3"></span></a></div>
                  <ul class="site-menu js-clone-nav d-none d-lg-block">
                    <li><a href="contact.php">Book</a></li>
                    <li class="active"><a href="index.php">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="services.html">Services</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="feedback.php">Feedback</a></li>
                    <?php if ($is_logged_in): ?>
                      <li class="user-menu">
                        <div class="user-icon">
                          <span class="icon-user"></span>
                          <span><?php echo htmlspecialchars($_SESSION['contact']); ?></span>
                        </div>
                        <div class="user-menu-content">
                          <?php if ($user_type === 'admin'): ?>
                            <a href="admin-dashboard.php">Admin Dashboard</a>
                          <?php else: ?>
                            <a href="#appointments">My Appointments</a>
                          <?php endif; ?>
                          <a href="#" data-toggle="modal" data-target="#profileModal">Profile</a>
                          <form method="post" action="">
                            <button type="submit" name="logout" style="width: 100%; text-align: left; background: none; border: none; padding: 12px 16px;">Logout</button>
                          </form>
                        </div>
                      </li>
                    <?php else: ?>
                      <li><a href="#" data-toggle="modal" data-target="#loginModal">Login</a></li>
                    <?php endif; ?>
                  </ul>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Display success/error messages if they exist -->
    <?php if ($success_message): ?>
    <div class="container mt-4">
      <div class="notification success"><?php echo $success_message; ?></div>
    </div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
    <div class="container mt-4">
      <div class="notification error"><?php echo $error_message; ?></div>
    </div>
    <?php endif; ?>

    <div class="site-blocks-cover" style="background-image: url(images/i_bg.jpg);" data-aos="fade" data-stellar-background-ratio="0.5" class="img-fluid"> 
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-22">
            <span class="sub-text <i class="ri-font-size-20"></i><h4>We Priortize </h4></span>
            <h1>Your <strong> Smile</strong></h1>
          </div>
        </div>
      </div>
    </div>  

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loginModalLabel">Login to MD Clinic</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="login-container">
              <?php if ($login_message): ?>
                <?php
                  $message_parts = explode(':', $login_message);
                  $message_type = $message_parts[0];
                  $message_text = $message_parts[1];
                ?>
                <div class="notification <?php echo $message_type; ?>"><?php echo $message_text; ?></div>
              <?php endif; ?>
              
              <div class="login-tabs">
                <div class="login-tab <?php echo ($otp_sent || !isset($_POST['contact_type']) || $_POST['contact_type'] == 'mobile') ? 'active' : ''; ?>" onclick="switchLoginTab('mobile')">Mobile</div>
                <div class="login-tab <?php echo (isset($_POST['contact_type']) && $_POST['contact_type'] == 'email') ? 'active' : ''; ?>" onclick="switchLoginTab('email')">Email</div>
              </div>
              
              <!-- Contact Form (Mobile/Email) -->
              <form method="post" action="" class="contact-form">
                <div class="form-group" id="mobile-input-group">
                  <label for="mobile">Mobile Number</label>
                  <input type="tel" id="mobile" name="contact" class="form-control" placeholder="Enter your 10-digit mobile number" maxlength="10">
                </div>
                
                <div class="form-group" id="email-input-group" style="display: none;">
                  <label for="email-login">Email Address</label>
                  <input type="email" id="email-login" name="contact" class="form-control" placeholder="Enter your email address">
                </div>
                
                <div class="form-group">
                  <label for="user-type">Login As</label>
                  <select id="user-type" name="user_type" class="form-control">
                    <option value="user">Patient/User</option>
                    <option value="admin">Admin</option>
                  </select>
                </div>
                
                <input type="hidden" name="contact_type" id="contact-type" value="mobile">
                <button type="submit" name="send_otp" class="btn btn-primary btn-block">Get OTP</button>
              </form>
              
              <!-- OTP Verification Form -->
              <form method="post" action="" class="verification-form">
                <h5 class="mb-3">Enter Verification Code</h5>
                <p class="text-muted mb-3">We've sent a code to your <?php echo isset($_POST['contact_type']) ? $_POST['contact_type'] : 'mobile/email'; ?>: <?php echo isset($_SESSION['contact']) ? htmlspecialchars($_SESSION['contact']) : ''; ?></p>
                
                <div class="form-group">
                  <label for="otp">OTP Code</label>
                  <input type="text" id="otp" name="otp" class="form-control" placeholder="Enter the 4-digit OTP" maxlength="4">
                </div>
                
                <input type="hidden" name="contact" value="<?php echo isset($_SESSION['contact']) ? htmlspecialchars($_SESSION['contact']) : ''; ?>">
                <input type="hidden" name="user_type" value="<?php echo isset($_POST['user_type']) ? $_POST['user_type'] : 'user'; ?>">
                
                <button type="submit" name="login_submit" class="btn btn-primary btn-block">Verify & Login</button>
                
                <div class="text-right mt-3">
                  <a href="#" onclick="resendOTP(event)">Resend OTP</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="profileModalLabel">My Profile</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p><strong>Contact:</strong> <?php echo isset($_SESSION['contact']) ? htmlspecialchars($_SESSION['contact']) : ''; ?></p>
            <p><strong>Account Type:</strong> <?php echo isset($_SESSION['user_type']) ? ucfirst(htmlspecialchars($_SESSION['user_type'])) : ''; ?></p>
            
            <?php if ($user_type === 'user'): ?>
              <div class="mt-4">
                <h6>Personal Information</h6>
                <form>
                  <div class="form-group">
                    <label for="profile-name">Full Name</label>
                    <input type="text" id="profile-name" class="form-control" placeholder="Your full name">
                  </div>
                  <div class="form-group">
                    <label for="profile-phone">Phone Number</label>
                    <input type="tel" id="profile-phone" class="form-control" placeholder="Your phone number">
                  </div>
                  <div class="form-group">
                    <label for="profile-email">Email Address</label>
                    <input type="email" id="profile-email" class="form-control" placeholder="Your email address">
                  </div>
                  <button type="button" class="btn btn-primary">Update Profile</button>
                </form>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Rest of the existing content... -->
    <div class="site-block-1">
      <div class="container">
        <div class="row">
          <?php
          // Define services dynamically
          $services = [
            [
              'icon' => 'flaticon-tooth',
              'title' => 'Cosmetic dental',
              'description' => 'Cosmetic dental procedures improve the appearance of your teeth.'
            ],
            [
              'icon' => 'flaticon-tooth-whitening',
              'title' => 'Tooth Whitening',
              'description' => 'Tooth whitening enhances your smile by removing stains and discoloration.'
            ],
            [
              'icon' => 'flaticon-tooth-pliers',
              'title' => 'Tooth Extraction',
              'description' => 'Tooth extraction is a simple and safe procedure to remove damaged teeth.'
            ]
          ];
          
          // Loop through services to display them
          foreach ($services as $service):
          ?>
          <div class="col-lg-4">
            <div class="site-block-feature d-flex p-4 rounded mb-4">
              <div class="mr-3">
                <span class="icon <?php echo $service['icon']; ?> font-weight-light text-white h2"></span>
              </div>
              <div class="text">
                <h3><?php echo $service['title']; ?></h3>
                <p><?php echo $service['description']; ?></p>
              </div>
            </div>   
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Admin Dashboard Section (shown only if logged in as admin) -->
    <?php if ($is_logged_in && $user_type === 'admin'): ?>
    <div class="container admin-section">
      <h2>Admin Dashboard</h2>
      <p>Welcome to your admin dashboard. Here you can manage appointments and clinic operations.</p>
      
      <div class="row mt-4">
        <div class="col-md-6">
          <h4>Pending Appointments</h4>
          <div class="appointment-card">
            <div class="appointment-header">
              <strong>April 20, 2025 - 10:00 AM</strong>
              <span>John Doe</span>
            </div>
            <div>Reason: General Checkup</div>
            <div>Contact: +1 234-567-8901</div>
            <div class="appointment-actions">
              <button class="accept-btn">Accept</button>
              <button class="reject-btn">Reject</button>
            </div>
          </div>
          
          <div class="appointment-card">
            <div class="appointment-header">
              <strong>April 21, 2025 - 2:30 PM</strong>
              <span>Jane Smith</span>
            </div>
            <div>Reason: Dental Pain</div>
            <div>Contact: +1 987-654-3210</div>
            <div class="appointment-actions">
              <button class="accept-btn">Accept</button>
              <button class="reject-btn">Reject</button>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <h4>Today's Appointments</h4>
          <div class="appointment-card">
            <div class="appointment-header">
              <strong>Today - 3:15 PM</strong>
              <span>Michael Johnson</span>
            </div>
            <div>Reason: Follow-up</div>
            <div>Contact: +1 555-123-4567</div>
            <div>Status: Confirmed</div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- User Appointments Section (shown only if logged in as user) -->
    <?php if ($is_logged_in && $user_type === 'user'): ?>
    <div class="container admin-section" id="appointments">
      <h2>My Appointments</h2>
      <p>Here you can view and manage your upcoming appointments.</p>
      
      <div class="appointment-card">
        <div class="appointment-header">
          <strong>April 22, 2025 - 11:30 AM</strong>
          <span class="badge badge-success">Confirmed</span>
        </div>
        <div>Doctor: Dr. Sarah Williams</div>
        <div>Reason: Annual Checkup</div>
        <div class="appointment-actions">
          <button class="btn btn-sm btn-outline-danger">Cancel Appointment</button>
          <button class="btn btn-sm btn-outline-primary ml-2">Reschedule</button>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="site-section" style="background: linear-gradient(to bottom, #5ca9cf, #ffffff);">
      <!-- Existing content here... -->
      <div class="site-section site-block-3">
        <div class="container">
          <div class="row">
            <div class="col-lg-6 mb-5 mb-lg-0">
              <img src="images/indexx.avif" alt="Image" class="img-fluid">
            </div>
            <div class="col-lg-6">
              <div class="row row-items">
                <div class="col-md-6">
                  <a href="#" class="d-flex text-center feature active p-4 mb-4">
                    <span class="align-self-center w-100">
                      <span class="d-block mb-3">
                        <span class="flaticon-tooth-whitening display-3"></span>
                      </span>
                      <h3>Tooth Whitening</h3>
                    </span>
                  </a>
                </div>
                <div class="col-md-6">
                  <a href="#" class="d-flex text-center feature p-4 mb-4">
                    <span class="align-self-center w-100">
                      <span class="d-block mb-3">
                        <span class="flaticon-stethoscope display-3"></span>
                      </span>
                      <h3>Proper Care</h3>
                    </span>
                  </a>
                </div>
              </div>
              <div class="row row-items last">
                <div class="col-md-6">
                  <a href="#" class="d-flex text-center feature p-4 mb-4">
                    <span class="align-self-center w-100">
                      <span class="d-block mb-3">
                        <span class="flaticon-first-aid-kit display-3"></span>
                      </span>
                      <h3>First Aid Kit</h3>
                    </span>
                  </a>
                </div>
                <div class="col-md-6">
                  <a href="#" class="d-flex text-center active feature p-4 mb-4">
                    <span class="align-self-center w-100">
                      <span class="d-block mb-3">
                        <span class="flaticon-tooth-pliers display-3"></span>
                      </span>
                      <h3>Extraction</h3>
                    </span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    
          <div class="site-section " style="background: linear-gradient(to bottom, #ffffff, #308494);">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <h2 class="site-heading text-center">Book Your <strong>Appointment</strong></h2>
        <form action="" method="post" class="p-5 bg-white">
          <div class="row form-group">
            <div class="col-md-6 mb-3 mb-md-0">
              <label class="text-black" for="fname">First Name</label>
              <input type="text" id="fname" name="fname" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="text-black" for="lname">Last Name</label>
              <input type="text" id="lname" name="lname" class="form-control" required>
            </div>
          </div>

          <div class="row form-group">
            <div class="col-md-12">
              <label class="text-black" for="email">Email</label>
              <input type="email" id="email" name="email" class="form-control" required>
            </div>
          </div>

          <div class="row form-group">
            <div class="col-md-6 mb-3 mb-md-0">
              <label class="text-black" for="date">Date</label>
              <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="text-black" for="appointment_time">Time</label>
              <select id="appointment_time" name="appointment_time" class="form-control" required>
                <option value="">--Select Time--</option>
                <option value="9:00 AM">9:00 AM</option>
                <option value="10:00 AM">10:00 AM</option>
                <option value="11:00 AM">11:00 AM</option>
                <option value="12:00 PM">12:00 PM</option>
                <option value="2:00 PM">2:00 PM</option>
                <option value="3:00 PM">3:00 PM</option>
                <option value="4:00 PM">4:00 PM</option>
                <option value="5:00 PM">5:00 PM</option>
              </select>
            </div>
          </div>

          <div class="row form-group">
            <div class="col-md-12">
              <label class="text-black" for="treatment">Treatment</label>
              <select id="treatment" name="treatment" class="form-control" required>
                <option value="">--Select Treatment--</option>
                <option value="Checkup">General Checkup</option>
                <option value="Cleaning">Teeth Cleaning</option>
                <option value="Whitening">Teeth Whitening</option>
                <option value="Filling">Dental Filling</option>
                <option value="Root Canal">Root Canal Treatment</option>
                <option value="Extraction">Tooth Extraction</option>
                <option value="Implant">Dental Implant</option>
                <option value="Braces">Orthodontic Treatment</option>
              </select>
            </div>
          </div>

          <div class="row form-group">
            <div class="col-md-12">
              <label class="text-black" for="note">Additional Notes</label>
              <textarea name="note" id="note" cols="30" rows="5" class="form-control" placeholder="Write any additional information here..."></textarea>
            </div>
          </div>

          <div class="row form-group">
            <div class="col-md-12">
              <input type="submit" name="appointment_submit" value="Book Appointment" class="btn btn-primary btn-md text-white">
            </div>
          </div>
        </form>
      </div>
      <div class="col-lg-6">
        <div class="p-5 bg-white">
          <h2 class="site-heading text-center">Our <strong>Clinic Hours</strong></h2>
          <table class="table">
            <tbody>
              <tr>
                <th>Monday - Friday</th>
                <td>9:00 AM - 6:00 PM</td>
              </tr>
              <tr>
                <th>Saturday</th>
                <td>9:00 AM - 5:00 PM</td>
              </tr>
              <tr>
                <th>Sunday</th>
                <td>Closed</td>
              </tr>
            </tbody>
          </table>

          <div class="mt-5">
            <h3 class="mb-4">Special Promotion!</h3>
            <p>Get 20% off on all cosmetic dental procedures until <?php echo $promo_end_date; ?></p>
            <p>Call us at <strong>90419-11360</strong> to avail this offer or book your appointment online.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Testimonials Section -->
<div class="site-section bg-light">
  <div class="container">
    <div class="row mb-5">
      <div class="col-md-12 text-center">
        <h2 class="site-heading">Happy <strong>Clients</strong></h2>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-lg-4">
        <div class="site-block-testimony p-4 text-center">
          <div class="mb-3">
            <img src="images/person_1.jpg" alt="Image" class="img-fluid rounded-circle w-25">
          </div>
          <p>"I was terrified of dental treatments, but Dr. Modi made me feel so comfortable. The clinic is clean, modern, and the staff is friendly. Highly recommended!"</p>
          <p><strong>— Priya Singh</strong></p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="site-block-testimony p-4 text-center">
          <div class="mb-3">
            <img src="images/person_2.jpg" alt="Image" class="img-fluid rounded-circle w-25">
          </div>
          <p>"The whitening treatment I received has completely transformed my smile. The results were amazing and the procedure was painless. Thank you, Modern Dental Clinic!"</p>
          <p><strong>— Rahul Verma</strong></p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="site-block-testimony p-4 text-center">
          <div class="mb-3">
            <img src="images/person_3.jpg" alt="Image" class="img-fluid rounded-circle w-25">
          </div>
          <p>"I had a dental emergency and they fit me in right away. The doctor was so gentle and fixed my broken tooth perfectly. I won't go anywhere else from now on!"</p>
          <p><strong>— Ananya Patel</strong></p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Feedback Section -->
<div class="site-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <h2 class="site-heading">Share Your <strong>Feedback</strong></h2>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <form action="" method="post" class="p-5 bg-white">
          <div class="row form-group">
            <div class="col-md-12">
              <label class="text-black" for="message">Your Feedback</label>
              <textarea name="message" id="message" cols="30" rows="7" class="form-control" placeholder="Share your experience with us..."></textarea>
            </div>
          </div>

          <div class="row form-group">
            <div class="col-md-12">
              <input type="submit" name="feedback_submit" value="Submit Feedback" class="btn btn-primary btn-md text-white">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Map Section -->
<div class="site-block-half d-flex">
  <div class="image bg-image" style="background-image: url('images/about.jpg');"></div>
  <div class="text py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="site-heading mb-3">Our <strong>Location</strong></h2>
          <p>Find us at the heart of Phagwara for convenient dental care.</p>
          <p>Address: Modern Dental Clinic, Near Bus Stand, Phagwara, Punjab, India</p>
          <p>
            <a href="https://maps.google.com/?q=Modern+Dental+Clinic+Phagwara" class="btn btn-primary btn-md text-white">Get Directions</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="site-footer border-top">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 mb-5 mb-lg-0">
        <div class="row mb-5">
          <div class="col-md-12">
            <h3 class="footer-heading mb-4">Navigation</h3>
          </div>
          <div class="col-md-6 col-lg-6">
            <ul class="list-unstyled">
              <li><a href="index.php">Home</a></li>
              <li><a href="about.html">About Us</a></li>
              <li><a href="services.html">Services</a></li>
            </ul>
          </div>
          <div class="col-md-6 col-lg-6">
            <ul class="list-unstyled">
              <li><a href="contact.php">Book Appointment</a></li>
              <li><a href="contact.php">Contact</a></li>
              <li><a href="feedback.php">Feedback</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="mb-5">
          <h3 class="footer-heading mb-4">Recent News</h3>
          <div class="block-25">
            <ul class="list-unstyled">
              <li class="mb-3">
                <a href="#" class="d-flex">
                  <figure class="image mr-4">
                    <img src="images/news_1.jpg" alt="" class="img-fluid">
                  </figure>
                  <div class="text">
                    <span class="small text-uppercase date">Apr 10, <?php echo $current_year; ?></span>
                    <h3 class="heading font-weight-light">New advanced teeth whitening technology now available</h3>
                  </div>
                </a>
              </li>
              <li class="mb-3">
                <a href="#" class="d-flex">
                  <figure class="image mr-4">
                    <img src="images/news_2.jpg" alt="" class="img-fluid">
                  </figure>
                  <div class="text">
                    <span class="small text-uppercase date">Apr 5, <?php echo $current_year; ?></span>
                    <h3 class="heading font-weight-light">Free dental checkup for children below 12 years</h3>
                  </div>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mb-5 mb-lg-0">
        <div class="mb-5">
          <h3 class="footer-heading mb-4">Contact Info</h3>
          <ul class="list-unstyled">
            <li class="d-flex">
              <span class="mr-3 icon icon-map"></span><span class="text">Modern Dental Clinic, Near Bus Stand, Phagwara, Punjab, India</span>
            </li>
            <li class="d-flex">
              <span class="mr-3 icon icon-phone"></span><span class="text">90419-11360</span>
            </li>
            <li class="d-flex">
              <span class="mr-3 icon icon-envelope"></span><span class="text">mdclinicphagwarapb@gmail.com</span>
            </li>
          </ul>
        </div>
        <div>
          <h3 class="footer-heading mb-4">Follow Us</h3>
          <div>
            <a href="https://www.instagram.com/moderndentalclinicphagwara?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="pl-0 pr-3"><span class="icon-instagram"></span></a>
            <a href="#" class="pl-3 pr-3"><span class="icon-facebook"></span></a>
          </div>
        </div>
      </div>
    </div>
    <div class="row pt-5 mt-5 text-center">
      <div class="col-md-12">
        <p>
          <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
          Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Modern Dental Clinic, Phagwara
          <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
        </p>
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

<script>
  // Function to switch between mobile and email login tabs
  function switchLoginTab(tabType) {
    if (tabType === 'mobile') {
      document.getElementById('mobile-input-group').style.display = 'block';
      document.getElementById('email-input-group').style.display = 'none';
      document.getElementById('contact-type').value = 'mobile';
      document.querySelectorAll('.login-tab')[0].classList.add('active');
      document.querySelectorAll('.login-tab')[1].classList.remove('active');
    } else {
      document.getElementById('mobile-input-group').style.display = 'none';
      document.getElementById('email-input-group').style.display = 'block';
      document.getElementById('contact-type').value = 'email';
      document.querySelectorAll('.login-tab')[0].classList.remove('active');
      document.querySelectorAll('.login-tab')[1].classList.add('active');
    }
  }
  
  // Function to handle OTP resend
  function resendOTP(event) {
    event.preventDefault();
    alert('OTP resent successfully!');
    // In a real application, you would make an AJAX call to resend the OTP
  }
  
  // Initialize bootstrap datepicker for the appointment date
  $(document).ready(function() {
    $('#date').datepicker({
      format: 'yyyy-mm-dd',
      startDate: new Date(),
      autoclose: true
    });
    
    // Auto open login modal if there's a login message
    <?php if ($login_message): ?>
      $('#loginModal').modal('show');
    <?php endif; ?>
  });
</script>

</body>
</html>