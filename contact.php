<?php
// Initialize variables for form processing
$successMessage = '';
$errorMessage = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $date = $_POST['date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $email = $_POST['email'] ?? '';
    $treatment = $_POST['treatment'] ?? '';
    $note = $_POST['note'] ?? '';
    
    // Validate required fields
    if (empty($fname) || empty($lname) || empty($date) || empty($appointment_time) || empty($email) || empty($treatment)) {
        $errorMessage = "Please fill in all required fields.";
    } else {
        // Email content
        $to = "mdclinicphagwarapb@gmail.com";
        $subject = "New Appointment Request from $fname $lname";
        $message = "
        <html>
        <head>
            <title>New Appointment Request</title>
        </head>
        <body>
            <h2>New Appointment Request</h2>
            <p><b>Name:</b> $fname $lname</p>
            <p><b>Date:</b> $date</p>
            <p><b>Time:</b> $appointment_time</p>
            <p><b>Email:</b> $email</p>
            <p><b>Treatment:</b> $treatment</p>
            <p><b>Notes:</b> $note</p>
        </body>
        </html>
        ";
        
        // Headers for HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: $email" . "\r\n";
        
        // Send email
        if (mail($to, $subject, $message, $headers)) {
            $successMessage = "Thank you! Your appointment request has been submitted. We will contact you shortly.";
            
            // Save to database if needed (commented code example)
            /*
            $servername = "localhost";
            $username = "username";
            $password = "password";
            $dbname = "dentist_appointments";
            
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $sql = "INSERT INTO appointments (fname, lname, date, appointment_time, email, treatment, note)
            VALUES ('$fname', '$lname', '$date', '$appointment_time', '$email', '$treatment', '$note')";
            
            if ($conn->query($sql) === TRUE) {
                $successMessage = "Thank you! Your appointment request has been submitted. We will contact you shortly.";
            } else {
                $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
            }
            
            $conn->close();
            */
        } else {
            $errorMessage = "There was a problem sending your request. Please try again later.";
        }
    }
}

// Process feedback form
$feedbackSuccess = '';
$feedbackError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback_submit'])) {
    $feedback = $_POST['message'] ?? '';
    
    if (!empty($feedback)) {
        $to = "mdclinicphagwarapb@gmail.com";
        $subject = "New Feedback";
        $message = "
        <html>
        <head>
            <title>New Feedback</title>
        </head>
        <body>
            <h2>New Feedback</h2>
            <p>$feedback</p>
        </body>
        </html>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        if (mail($to, $subject, $message, $headers)) {
            $feedbackSuccess = "Thank you for your feedback!";
        } else {
            $feedbackError = "There was a problem sending your feedback. Please try again later.";
        }
    } else {
        $feedbackError = "Please enter your feedback.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Dente &mdash; Colorlib Website Template</title>
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
    <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="96x96" >
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
  


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tilt.js/1.2.1/tilt.jquery.min.js"></script>

    
    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">
    <style>
     
      
      .Button-wrapper {
        position: relative;
      }
      
      .Button {
        font-family: "Gilroy ExtraBold", system-ui, sans-serif;
        font-style: normal;
        font-weight: normal;
        letter-spacing: 2px;
        font-size: 22px;
        line-height: 68px;
        text-transform: uppercase;
        background: #4a4a4a ;
        color: #F8FAFF;
        appearance: none;
        border: none;
        border-radius: 10px;
        min-width: 200px;
        padding: 0 24px;
        box-shadow: 0 10px 60px -10px #10c7cd;
        cursor: pointer;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
        user-select: none;
        transform-style: preserve-3d;
        transform: perspective(250px) scale3d(1,1,1);
        transition: all 1s cubic-bezier(0.03,0.98,0.52,0.99);
        will-change: transform, box-shadow, background;
        outline: none;
        position: relative;
        z-index: 2;
      }
      
      .Button:hover {
        background: #27cff5;
        box-shadow: 0 8px 65px -5px #ffffff;
      }
      
      .Button:active {
        background: #27daf5;
        box-shadow: 0 10px 60px -10px #10a7cd;
        transform: perspective(250px) scale3d(1, 1, 1) translateY(5%) !important;
      }
      
      .Symbol {
        position: absolute;
        width: 10px;
        height: 10px;
        z-index: -1;
        animation: explode .8s reverse forwards ease-in;
      }
      
      @keyframes explode {
        from { opacity: 0; }
        to {
          top: 50%;
          left: 50%;
          opacity: 1;
        }
      }
      
      .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
      }
      
      .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
      }
      
      .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
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
<h4 class="mb-0 site-logo" style="text-shadow: 2px 2px 4px #000000;"><a href="index.php"><strong> Modern Dental Clinic Phagwara</strong></a></h4>            </div>
            <div class="col-10">
              <nav class="site-navigation text-right" role="navigation">
                <div class="container">
                  <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#" class="site-menu-toggle js-menu-toggle text-white"><span class="icon-menu h3"></span></a></div>

                  <ul class="site-menu js-clone-nav d-none d-lg-block">
                    <li class="active"  ><a href="contact.php">Book</a></li>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="services.html">Services</a></li>
                    <li class="active"><a href="contact.php">Contact</a></li>
                    <li><a href="feedback.php">Feedback</a></li>

                  </ul>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
    
<div class="site-blocks-cover inner-page" style="background-image: url(images/oik.jpg); background-size: cover; background-position: center; height: 100vh; data-aos="fade" data-stellar-background-ratio="0.5"> 
    <div class="container">
    <div class="row align-items-center">
      <div class="col-md-7">
        <div class="col-md-7 text-right" style="position: relative; z-index: 1;">
          <span class="sub-text" style="float:right; text-shadow: 2px 2px 4px #000000;">Get In Touch</span>
          <h1 style="text-align:right; text-shadow: 2px 2px 4px #000000;"><strong>Contact us </strong> </h1>
        </div>
      </div>
    </div>
  </div>
<div class="blur-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('images/contact.avif '); filter: blur(3px); opacity: 0.1 "></div>
</div>
</div>

  
<div class="site-section" style="background: linear-gradient(to bottom, #47817d, #3499da);">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-lg-6 mb-5 mb-lg-0">
        <h2 class="site-heading text-white mb-5 "><strong style="color: white;">Appointment</strong></h2>
        
        <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        
        <form action="process_appointment.php" method="POST">
  <div class="row form-group">
    <div class="col-md-6 mb-3 mb-md-0">
      <label class="font-weight-bold" for="fname">First Name</label>
      <input type="text" id="fname" class="form-control" placeholder="First Name" name="fname" required>
    </div>
    <div class="col-md-6">
      <label class="font-weight-bold" for="lname">Last Name</label>
      <input type="text" id="lname" class="form-control" placeholder="Last Name" name="lname" required>
    </div>
  </div>
  
  <div class="row form-group">
    <div class="col-md-6 mb-3 mb-md-0">
      <label class="font-weight-bold" for="mobile">Mobile Number</label>
      <input type="tel" id="mobile" class="form-control" placeholder="Mobile Number" name="mobile" pattern="[0-9]+" 
             oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
      <small class="form-text text-muted">Numbers only</small>
    </div>
    <div class="col-md-6">
      <label class="font-weight-bold" for="email">Email</label> 
      <input type="email" id="email" class="form-control" placeholder="Email" name="email" required>
    </div>
  </div>
  
  <div class="row form-group">
    <div class="col-md-6 mb-3 mb-md-0">
      <label class="font-weight-bold" for="date">Date</label> 
      <input type="date" id="date" class="form-control px-2" placeholder="Date of visit" name="date" 
             min="<?php echo date('Y-m-d'); ?>" required>
    </div>
    <div class="col-md-6">
      <label class="font-weight-bold" for="appointment_time">Preferred Time</label> 
      <select name="appointment_time" id="appointment_time" class="form-control" required>
        <option value="">Select a time</option>
        <?php
        // Generate time slots dynamically
        $start_time = strtotime('10:00');
        $end_time = strtotime('16:30');
        $interval = 30 * 60; // 30 minutes in seconds

        for ($time = $start_time; $time <= $end_time; $time += $interval) {
            $formatted_time = date('g:i A', $time);
            echo "<option value=\"$formatted_time\">$formatted_time</option>";
        }
        ?>
      </select>
    </div>
  </div>
  
  <div class="row form-group">
    <div class="col-md-12">
      <label class="font-weight-bold" for="treatment">Treatment Needed</label> 
      <select name="treatment" id="treatment" class="form-control" size="1" required>
        <?php
        // Define treatments dynamically
        $treatments = [
          'Dental exam', 'Dental emergency', 'Teeth cleaning', 
          'Teeth whitening', 'Extraction', 'Trauma surgery',
          'Laser filling', 'Other'
        ];
        
        // Loop through treatments to display them
        foreach ($treatments as $treatment) {
            echo "<option value=\"$treatment\">$treatment</option>";
        }
        ?>
      </select>
    </div>
  </div>
  
  <div class="row form-group">
    <div class="col-md-12">
      <label class="font-weight-bold" for="note">Notes</label> 
      <textarea name="note" id="note" cols="30" rows="5" class="form-control" placeholder="Write your notes or questions here..."></textarea>
    </div>
  </div>
  
  <div class="row form-group">
    <div class="col-md-12">
      <input type="submit" name="appointment_submit" value="Book Appointment" class="btn btn-primary">
      <input type="hidden" name="access_key" value="d1e92cbf-7008-4ae8-8c0b-c3e250352acf" readonly>
    </div>
  </div>
</form>

<script>
// JavaScript to ensure date input doesn't allow past dates
document.addEventListener('DOMContentLoaded', function() {
    // Set min date to today
    const dateInput = document.getElementById('date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);
    
    // Extra validation when form is submitted
    document.querySelector('form').addEventListener('submit', function(e) {
        const selectedDate = new Date(dateInput.value);
        const currentDate = new Date();
        currentDate.setHours(0, 0, 0, 0);
        
        if (selectedDate < currentDate) {
            e.preventDefault();
            alert('Please select a future date for your appointment.');
        }
    });
});
</script>
      </div>
      <div class="col-md-12 col-lg-6">
        <h2 class="site-heading text-black mb-5" style ="color: white;">  <strong style="color: white;"> How Did We Do?</strong></h2>
     
  <div class="Button-wrapper">
    <button class="Button" onclick="window.location.href='Feedback.php'">Give FeedBack?</button>
  </div>
  
  <!-- symbols -->
  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="donut" viewBox="0 0 14 14">
      <path fill="#F4157E" fill-rule="nonzero" d="M7 12c2.76 0 5-2.24 5-5S9.76 2 7 2 2 4.24 2 7s2.24 5 5 5zm0 2c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/>
    </symbol>
    
    <symbol id="circle" viewBox="0 0 10 10">
      <circle cx="5" cy="5" r="5" fill="#F4157E" fill-rule="evenodd"/>
    </symbol>
    
    <symbol id="tri_hollow" viewBox="0 0 12 11">
      <path fill="#F4157E" fill-rule="nonzero" d="M3.4 8.96h5.2L6 4.2 3.4 8.95zM6 0l6 11H0L6 0z"/>
    </symbol>
    
    <symbol id="triangle" viewBox="0 0 10 9">
      <path fill="#F4157E" fill-rule="evenodd" d="M5 0l5 9H0"/>
    </symbol>
    
    <symbol id="square" viewBox="0 0 8 8">
      <path fill="#F4157E" fill-rule="evenodd" d="M0 0h8v8H0z"/>
    </symbol>
    
    <symbol id="squ_hollow" viewBox="0 0 8 8">
      <path fill="#F4157E" fill-rule="nonzero" d="M1.5 1.5v5h5v-5h-5zM0 0h8v8H0V0z"/>
    </symbol>
  </svg>
  
  <script>
    // tilt.js
    $('.Button').tilt({ scale: 1.1, speed: 1000 });
    
    // click event
    $('.Button').on('click', function(e) {
      explode(e.pageX, e.pageY);
    });
    
    document.addEventListener("touchstart", function(){}, true);
    
    // symbols
    function explode(x, y) {
      var symbolArray = [
        '#donut',
        '#circle',
        '#tri_hollow',
        '#triangle',
        '#square',
        '#squ_hollow'
      ];
      
      var particles = 10, 
          explosion = $('.Button-wrapper');
          
      for (var i = 0; i < particles; i++) {
        var randomSymbol = Math.floor(Math.random()*symbolArray.length);
        // positioning x,y of the particles
        var x = (explosion.width() / 2) + rand(80, 150) * Math.cos(2 * Math.PI * i / rand(particles - 10, particles + 10)),
            y = (explosion.height() / 2) + rand(80, 150) * Math.sin(2 * Math.PI * i / rand(particles - 10, particles + 10)),
            deg = rand(0, 360) + 'deg',
            scale = rand(0.5, 1.1),
            // particle element creation
            elm = $(
              '<svg class="Symbol" style="top:' + y + 'px; left:' + x + 'px; transform: scale(' + scale + ') rotate(' + deg + ');">' + 
              '<use xlink:href="' + symbolArray[randomSymbol] + '" />' + 
              '</svg>'
             );
        
        if (i == 0) { // only need to target one of the symbols.
          // css3 animation end detection
          elm.one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
            elm.siblings('svg').remove().end().remove(); // remove particles when animation is over.
          });
        }
        
        explosion.prepend(elm);
      }
    }
    
    function rand(min, max) {
      return Math.floor(Math.random() * (max + 1)) + min;
    }
  </script>

      </div>
    </div>
  </div>
</div>


    <div class="promo py-5" style="background: linear-gradient(to bottom, #3499d9, #a40c0c);">
      <div class="container text-center">
        <span class="d-block h3 mb-3 font-weight-bold text-white">Promo For Dental exam from <del> ₹1000</del> now <strong class="font-weight-bold">₹500</strong></span>
        <div id="date-countdown" class="mt-0" data-countdown="2025/04/23"></div>
      </div>
    </div>
    

    <footer class="site-footer" style="background: linear-gradient(to bottom, #a40c0c, #000000);">

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
                  <li><a href="services.html">Services</a></li>
                  <li><a href="about.html">About us </a></li>
                  <li><a href="contact.php">Contact Us</a></li>
                  <li><a href="feedback.php">FeedBack </a></li>

                </ul>
              </div>
              <div class="col-md-6 col-lg-6">
                <ul class="list-unstyled">
                 
                </ul>
              </div>
            </div>

            
          </div>
          

          <div class="col-lg-4 mb-5 mb-lg-0">

            <div class="mb-5">
              <h3 class="footer-heading mb-2">FeedBack</h3>

              <?php if (!empty($feedbackSuccess)): ?>
              <div class="alert alert-success"><?php echo $feedbackSuccess; ?></div>
              <?php endif; ?>
              
              <?php if (!empty($feedbackError)): ?>
              <div class="alert alert-danger"><?php echo $feedbackError; ?></div>
              <?php endif; ?>

              <form action="https://api.web3forms.com/submit" method="POST">
  <input type="hidden" name="access_key" value="d1e92cbf-7008-4ae8-8c0b-c3e250352acf" readonly>
  <input type="hidden" name="success_url" value="https://your-website.com/success" readonly>
  <div class="input-group mb-3">
    <input type="text" class="form-control border-white text-white bg-transparent" name="message" placeholder="Send FeedBack" aria-label="Enter Email" aria-describedby="button-addon2">
    <div class="input-group-append">
      <button class="btn btn-primary" type="submit" id="button-addon2">Send</button>
    </div>
  </div>
</form>
            </div>

            <div class="row">
              <div class="col-md-12">
                <h3 class="footer-heading mb-4">Follow Us</h3>

                <div>

                  <a href="https://www.instagram.com/moderndentalclinicphagwara?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
                </div>
              </div>
            </div>


          </div>
          
        </div>
        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <p>
              Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved to<a href="https://rakshitsharma.netlify.app/"> Rakshit Sharma</a>
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
    
  </body>
</html>