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
        border: none;
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

    .disabled{
        border-color: #ccc!important;
        color: #ccc!important;
        cursor: not-allowed;
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
            <h1>Register</h1>
            <form method='post'>
                <input type="text" name="username" placeholder="Username" id='anumt' class='input-bx' onkeydown="alnum(event, this.id)" required>
                <label style='color:rgb(222, 145, 2);font-size:14px;' id='accepted'></label>
                <input type="text" name="fullname" placeholder="Display Name" class='input-bx' required>
                <div class="password-container">
                    <input type="password" id="password-o" name='password' class="password-input" placeholder="Enter password" oninput="rageem()" required>
                    <span onclick="togglePasswordVisibility('o')" class="toggle-password" aria-label="Show/Hide Password">
                        <i class='uil uil-eye' id='modm-o'></i>
                    </span>
                </div>

                <div class="password-container">
                    <input type="password" id="password-t" name='password-conf' class="password-input" placeholder="Confirm password" oninput="rageem()" required>
                    <span onclick="togglePasswordVisibility('t')" class="toggle-password" aria-label="Show/Hide Password">
                        <i class='uil uil-eye' id='modm-t'></i>
                    </span>
                </div>
                <center>
                <label style='color:red;font-size:14px;' id='plebl'></label>
                </center>
            <button class='button remew' id='mebble' disabled>Register</button>
            </form>
            <p class="nemmy">Registered already? <a href="/auth/login" class="linkc">Login</a>.</p>
        </div>
    </section>
    
    <script>
    function togglePasswordVisibility(vm) {
        var passwordInput = document.getElementById('password-'+vm);
        var toggleIcon = document.getElementById('modm-'+vm);
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.setAttribute("class", "uil uil-eye-slash");
        } else {
            passwordInput.type = "password";
            toggleIcon.setAttribute("class", "uil uil-eye");
        }
    }

    function rageem(){
        var o = document.getElementById('password-o');
        var t = document.getElementById('password-t');
        var m = document.getElementById('mebble');
        var p = document.getElementById('plebl');
        
        if(o.value == t.value){
            if((o.value.length >= 8 && t.value.length >= 8) || (o.value == '' && t.value == '')){
                m.removeAttribute("disabled"); 
                p.innerText = "";
                m.setAttribute("class", "button remew");
            }else{
                p.innerHTML = "Passwords Should Be Equal to More then 8 Characters<br>";
            }
        }else{
            p.innerHTML = "Passwords don't match<br>";
            m.setAttribute("disabled", true);
            m.setAttribute("class", "button disabled");
        }
    }

    function alnum(e, vex){
        if(e.key !== 'ArrowRight' && e.key !== 'ArrowLeft' && e.key !== 'Backspace'){
            e.preventDefault();    
            document.getElementById('accepted').innerHTML = "";
        }
        var elem = document.getElementById(vex);
        var allowed = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l','m','n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ,'-', '_'];
        if(allowed.indexOf(e.key) !== -1){
            elem.value += e.key;
            document.getElementById('accepted').innerHTML = "";
        }else{
            if(e.key !== 'ArrowRight' && e.key !== 'ArrowLeft' && e.key !== 'Backspace'&& e.key !== 'Shift'){
                console.warn(`Username doenst accept key: ${e.key}`);
                document.getElementById('accepted').innerHTML = "Only AlphaNumeric characters and '-' & '_' are allowed.<br>";
            }else{
                document.getElementById('accepted').innerHTML = "";

            }
        }
    }
    </script>
    