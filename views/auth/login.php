<style>
    *{
        box-sizing: border-box;
    }
    .auth-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .button {
        background-color: #04AA6D; /* Green */
        border: none;
        color: white;
        padding: 9px 20px;
        text-align: center;
        text-decoration: none;
        border-radius: 2px;

        display: inline-block;
        font-size: 14px;
        margin: 0px 5px;
        transition-duration: 0.4s;
        cursor: pointer;
    }

    .main-container {
        width: 400px;
        padding: 10px;
    }
    @media screen and (max-width: 400px){
        .main-container {
            width: 100%;
        }
    }
    
    .input-bx, .password-input {
        width: 98%;
        padding: 10px;
        margin: 5px;
        display: flex;
    align-items: center;
    border-style: solid;
    /* border-width: 1px; */
    border-radius: 2px;
    border-color: #555555;
    outline: none;
    }
    
    .password-container {
        position: relative;
    }
    
    .toggle-password {
        position: absolute;
        right: 10px; /* added some right padding for better positioning */
        top: 10px;
        background: transparent;
        cursor: pointer;
        border: none;
        outline: none; /* removes the focus outline */
    }
    .linkc{
        color: rgb(0, 149, 255);
        text-decoration: none;
    }
    .linkc:hover{
        text-decoration: underline;
    }
    .nemmy{
        font-size: 14px;
    }
    .remew {
        background-color: white;
        color: black;
        border: 2px solid #555555;
    }

    .remew:hover {
        background-color: #555555;
        color: white;
    }
    </style>
    
    <section class='auth-container'>
        <div class='main-container'>
            <center>
                <img src='/public/images/logo.png' alt='logo' style='height: 50px;'>
            </center>
            <h1>Login</h1>
            <form method='post'>
                <input type="text" name="username" placeholder="Username" class='input-bx' required>
                <div class="password-container">
                    <input type="password" id="password" name='password' class="password-input" placeholder="Enter password" required>
                    <span onclick="togglePasswordVisibility()" class="toggle-password" aria-label="Show/Hide Password">
                        <i class='uil uil-eye' id='modm'></i>
                    </span>
                </div>
            <button class='button remew'>Login</button>
            </form>
            <p class="nemmy">Not Registered yet? <a href="/auth/register" class="linkc">Register</a>.</p>
        </div>
    </section>
    
    <script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var toggleIcon = document.getElementById('modm');
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.setAttribute("class", "uil uil-eye-slash");
        } else {
            passwordInput.type = "password";
            toggleIcon.setAttribute("class", "uil uil-eye");
        }
    }
    </script>
    