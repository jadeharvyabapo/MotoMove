
//sign up start


document.addEventListener('DOMContentLoaded', function () {
    const signUpForm = document.getElementById('signupForm');
    if (signUpForm) {
        signUpForm.addEventListener('submit', async function (event) {
            event.preventDefault(); // Prevent form from submitting and refreshing
            await signup(); // Call the signup function
        });
    }
});


// Sign-Up Function
async function signup() {
    const fullname = document.getElementById("fullname").value.trim();
    const email = document.getElementById("signupUsername").value.trim();
    const password1 = document.getElementById("signupPassword1").value;
    const password2 = document.getElementById("signupPassword2").value;
    const role = document.getElementById("signupselect").value;

    // Validate input fields
    if (!fullname || !email || !password1 || !password2 || !role) {
        alert("Please fill out all fields.");
        return;
    }

    // Check if passwords match
    if (password1 !== password2) {
        alert("Passwords do not match!");
        return;
    }

    // Prepare user data for backend
    const userData = {
        fullname: fullname,
        email: email,
        password: password1,
        role: role
    };

    // Send data to the backend
    try {
        const response = await fetch("http://localhost/Motor-Cycle-Website-main/signup.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(userData)
        });

        const result = await response.json();
        if (result.success) {
            alert("Sign-up successful! You can now sign in.");

            // Trigger the click event on the sign-in button
            const loginBtn = document.getElementById('login'); // Adjust the ID as per your button's ID
            if (loginBtn) {
                loginBtn.click(); // Simulate a click
            }
        } else {
            alert(result.message || "Sign-up failed!");
        }
    } catch (error) {
        console.error("Error during signup:", error);
        alert("An error occurred during signup. Please try again.");
    }
}

// Set up event listeners once when the page loads
window.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });
});


//signup ends









//signin starts

// Sign-in starts

async function login(event) {
    event.preventDefault(); // Prevent form from submitting and refreshing the page
    const email = document.getElementById("loginUsername").value.trim();
    const password = document.getElementById("loginPassword").value;
    const role = document.getElementById("loginselect").value;

    // Submit login data to the backend
    const response = await fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json', // Change to JSON
        },
        body: JSON.stringify({
            action: 'login', // Specify the action as 'login'
            email: email,
            password: password,
            role: role,
        }),
    });

    const result = await response.json();
    console.log(result); // Debugging the result

    if (result.success) {


        const uniqueSessionId = 'tab_' + Date.now() + '_' + Math.random().toString(36).substring(7);

        // Store the unique session ID in sessionStorage for the current tab
        sessionStorage.setItem('unique_session_id', uniqueSessionId);

        // Optionally store additional user data in sessionStorage
        sessionStorage.setItem('user_id', result.user.userId); // Assuming `userId` is returned
        sessionStorage.setItem('role', role);


        // Check the role and the registration status
        if (role === 'rider') {
            window.location.href = 'index.html';
        }


        
        else if (role === 'admin' || role === 'Admin') {
            window.location.href = 'admin.html';
        }

        
        else if (role === 'driver') {
            // Check if the driver is registered or not
            if (result.user.DriverRegistrationStatus === null) {
                // Redirect to driver registration page if not registered
                console.log(result.user.DriverRegistrationStatus);
                window.location.href = 'drive.html';
            } else {
                // Redirect to driver dashboard if registered
                window.location.href = 'dashboarddriver.html';
                console.log(result.user.DriverRegistrationStatus);

            }
        } else {
            alert(result.message); // Show error message if role is neither rider nor driver
        }
    } else {
        alert(result.message); // Show error message from backend
    }
}

// Add an event listener to your login form
document.getElementById("signInForm").addEventListener("submit", login);


// Sign-in ends


//sign in end



// Book a ride start


// Book a ride ends
